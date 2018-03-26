<?php

namespace App\Http\Requests;

use App\Models\Map;
use App\Models\Comment;
use App\Models\Mission;
use App\Tests\FilesExist;
use App\Tests\ValidSyntax;
use App\Tests\NoDuplicateIds;
use App\Tests\MissionIntelExists;
use App\Tests\LoadoutsExcludeACRE;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class MissionUploadRequest extends FormRequest
{
    /**
     * Array of test cases.
     * They are executed in the same order.
     *
     * @var array
     */
    protected $testCases = [
        FilesExist::class,
        ValidSyntax::class,
        MissionIntelExists::class,
        NoDuplicateIds::class,
        LoadoutsExcludeACRE::class,
    ];

    /**
     * Relative path to the original file.
     *
     * @var string
     */
    public $path;

    /**
     * Absolute path to the original file.
     *
     * @var string
     */
    public $fullPath;

    /**
     * Absolute path to the unpacked directory.
     *
     * @var string
     */
    public $fullUnpacked;

    /**
     * Relative path to the unpacked directory.
     *
     * @var string
     */
    public $unpacked;

    /**
     * Unique key for the mission.
     *
     * @var string
     */
    public $key;

    /**
     * Class name of the map.
     *
     * @var string
     */
    public $mapName;

    /**
     * Game mode of the mission.
     *
     * @var string
     */
    public $mode;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->member();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => ['required', 'file', function ($attribute, $value, $fail) {
                if (!ends_with($value->getClientOriginalName(), '.pbo')) {
                    return $fail('File must be a PBO.');
                }
            }]
        ];
    }

    /**
     * Handles the execution of the request.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->validFilename()) {
            throw new ValidationException('Filename must follow the format ARC_TVT/COOP_Name_Author.Map');
        }

        $this->generateKey();
        $this->store();
        $this->unpack();

        try {
            // If there are any strict errors
            // an exception will be thrown.
            $tests = $this->runTests();

            $record = $this->createRecord($tests->validSyntax->data);

            $this->createErrorNote($tests, $record);

            return $record;
        } catch (\Exception $error) {
            Storage::deleteDirectory("missions/{$this->key}");

            throw $error;
        }
    }

    /**
     * Creates a note if any non-fatal errors were found.
     *
     * @return void
     */
    public function createErrorNote($tests, $mission)
    {
        $errors = collect();

        foreach ($tests as $key => $test) {
            if (count($test->errors)) {
                $errors->push($test->errors);
            }
        }

        if (!$errors->isEmpty()) {
            $mission->comments()->save(
                new Comment([
                    'text' => $errors->flatten()->map(function ($error) {
                        return "- $error";
                    })->implode('<br />'),
                    'published_at' => now()
                ])
            );
        }
    }

    /**
     * Gets the map model for the mission.
     *
     * @return \App\Models\Map
     */
    public function map()
    {
        return Map::firstOrCreate(
            // Search by this key
            ['key' => $this->mapName],

            // Add these if creating
            ['name' => $this->mapName]
        );
    }

    /**
     * Creates the database mission record.
     *
     * @return Mission
     */
    public function createRecord($configs)
    {
        return auth()->user()->missions()->save(
            new Mission([
                'ref' => $this->key,
                'map_id' => $this->map()->id,
                'mode' => $this->mode,
                'name' => $configs->ext->onLoadName,
                'summary' => $configs->ext->onLoadMission,
                'ext' => json_encode((array) $configs->ext),
                'sqm' => json_encode((array) $configs->sqm),
                'cfg' => json_encode((array) $configs->cfg->cfgARCMF),
            ])
        );
    }

    /**
     * Generates and stores the unique key.
     *
     * @return void
     */
    public function generateKey()
    {
        $this->key = str_random(6);
    }

    /**
     * Stores the uploaded file.
     *
     * @return void
     */
    public function store()
    {
        $directory = config('app.env') === 'testing' ? 'missions_test' : 'missions';
        $this->path = $this->file->storeAs("$directory/{$this->key}", 'original.pbo');
        $this->fullPath = storage_path("app/{$this->path}");
    }

    /**
     * Determines if the file name conforms to the format.
     *
     * @return boolean
     */
    public function validFilename()
    {
        $name = $this->file->getClientOriginalName();

        if (strpos($name, '_') === false) {
            return false;
        }

        $name = substr($name, 0, -4);
        $this->mapName = last(explode('.', $name));
        $parts = explode('_', rtrim($name, ".{$this->mapName}"));

        if (sizeof($parts) < 3) {
            return false;
        }

        if (!in_array(strtolower($parts[1]), ['coop', 'co', 'tvt', 'pvp', 'adv', 'preop'])) {
            return false;
        }

        if (in_array(strtolower($parts[1]), ['tvt', 'pvp', 'adv'])) {
            $parts[1] = 'adversarial';
        }

        $this->mode = $parts[1];

        return true;
    }

    /**
     * Unpacks the PBO file.
     *
     * @return void
     */
    public function unpack()
    {
        $armake = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            ? resource_path('utils/armake.exe')
            : 'armake';

        $unpacked = dirname($this->fullPath) . '/unpacked';

        shell_exec("$armake unpack -f {$this->fullPath} $unpacked");

        $cwd = getcwd();
        chdir($unpacked);

        // Debinarize mission.sqm
        // If it's not binned, armake exits gracefully
        shell_exec("$armake derapify -f mission.sqm mission.sqm");

        chdir($cwd);

        $this->fullUnpacked = $unpacked;
        $this->unpacked = dirname($this->path) . '/unpacked';
    }

    /**
     * Runs through the tests.
     * Tests will gather any errors and data.
     *
     * @return void
     */
    public function runTests()
    {
        $result = [];

        foreach ($this->testCases as $test) {
            $classKey = camel_case(class_basename($test));
            $instance = new $test($this, array_to_object($result));
            $errors = [];
            $data = [];

            $passes = $instance->passes(function ($message) use (&$errors) {
                $errors[] = $message;
                return false;
            }, function ($d) use (&$data) {
                $data = $d;
                return true;
            });

            if (!$passes) {
                throw new \Exception(implode('\n', $errors));
            }

            $result[$classKey] = compact('errors', 'data');
        }

        return array_to_object($result);
    }
}
