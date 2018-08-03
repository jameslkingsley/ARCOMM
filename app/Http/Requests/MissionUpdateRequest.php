<?php

namespace App\Http\Requests;

use App\Models\Mission;
use App\Traits\MissionRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class MissionUpdateRequest extends FormRequest
{
    use MissionRequest;

    /**
     * The mission that will be updated.
     *
     * @var \App\Models\Mission
     */
    protected $mission;

    /**
     * Handles the execution of the request.
     *
     * @return mixed
     */
    public function handle(Mission $mission)
    {
        $this->mission = $mission;

        if (!$this->validFilename()) {
            throw new ValidationException('Filename must follow the format ARC_TVT/COOP_Name_Author.Map');
        }

        $this->store();
        $this->unpack('unpacked_update');

        try {
            // If there are any strict errors
            // an exception will be thrown.
            $tests = $this->runTests();

            Storage::delete("missions/{$this->mission->ref}/original.pbo");
            Storage::move("missions/{$this->mission->ref}/update.pbo", "missions/{$this->mission->ref}/original.pbo");
            Storage::deleteDirectory("missions/{$this->mission->ref}/unpacked");
            Storage::move("missions/{$this->mission->ref}/unpacked_update", "missions/{$this->mission->ref}/unpacked");

            $this->updateRecord($tests->validSyntax->data);
            $this->createErrorNote($tests, $this->mission);

            return $this->mission;
        } catch (\Exception $error) {
            Storage::delete("missions/{$this->mission->ref}/update.pbo");
            Storage::deleteDirectory("missions/{$this->mission->ref}/unpacked_update");

            throw $error;
        }
    }

    /**
     * Stores the uploaded file.
     *
     * @return void
     */
    public function store()
    {
        $directory = config('app.env') === 'testing' ? 'missions_test' : 'missions';
        $this->path = $this->file->storeAs("$directory/{$this->mission->ref}", 'update.pbo');
        $this->fullPath = storage_path("app/{$this->path}");
    }

    /**
     * Updates the mission database record.
     *
     * @return \App\Models\Mission
     */
    public function updateRecord($configs)
    {
        $this->mission->update([
            'mode' => $this->mode,
            'map_id' => $this->map()->id,
            'name' => $configs->ext->onLoadName,
            'summary' => $configs->ext->onLoadMission,
            'ext' => json_encode((array) $configs->ext),
            'sqm' => json_encode((array) $configs->sqm),
            'cfg' => json_encode((array) $configs->cfg->cfgARCMF),
        ]);
    }
}
