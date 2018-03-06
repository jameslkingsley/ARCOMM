<?php

namespace App\Http\Requests;

use App\Tests\FilesExist;
use App\Tests\ValidSyntax;
use App\Tests\LoadoutsExcludeACRE;
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

        $errors = $this->runTests();

        if (empty($errors)) {
            return response(200);
        } else {
            return response()->json([
                'errors' => $errors
            ]);
        }
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
        $this->path = $this->file->storeAs("missions/{$this->key}", 'original.pbo');
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
        $mapName = last(explode('.', $name));
        $parts = explode('_', rtrim($name, ".{$mapName}"));

        if (sizeof($parts) < 3) {
            return false;
        }

        if (!in_array(strtolower($parts[1]), ['coop', 'co', 'tvt', 'pvp', 'adv', 'preop'])) {
            return false;
        }

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

        // $cwd = getcwd();
        // chdir($unpacked);

        // Debinarize mission.sqm
        // If it's not binned, armake exits gracefully
        // shell_exec("$armake derapify -f mission.sqm mission.sqm");

        // chdir($cwd);

        $this->fullUnpacked = $unpacked;
        $this->unpacked = dirname($this->path) . '/unpacked';
    }

    /**
     * Runs through the tests.
     *
     * @return void
     */
    public function runTests()
    {
        $errors = [];

        foreach ($this->testCases as $test) {
            $instance = new $test($this);

            $passes = $instance->passes(function ($message) use (&$errors) {
                $errors[] = $message;
                return false;
            });

            if (!$passes) {
                throw new \Exception(implode('\n', $errors));
            }
        }

        return $errors;
    }
}
