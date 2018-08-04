<?php

namespace App\Http\Requests;

use ZanySoft\Zip\Zip;
use App\Models\Mission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;

class MissionDownloadRequest extends FormRequest
{
    /**
     * The mission to be downloaded.
     *
     * @var \App\Models\Mission
     */
    protected $mission;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Handles the request and returns the full download URL.
     *
     * @return string
     */
    public function handle(Mission $mission)
    {
        $this->mission = $mission;

        if ($this->wantsPbo()) {
            return $this->pboUrl();
        }

        return $this->zipUrl();
    }

    /**
     * Determines if the request wants a PBO response.
     *
     * @return boolean
     */
    public function wantsPbo()
    {
        return strtolower($this->query('format')) === 'pbo';
    }

    /**
     * Gets the full PBO URL.
     *
     * @return string
     */
    public function pboUrl()
    {
        return Storage::download(
            "missions/{$this->mission->ref}/original.pbo",
            $this->fileName() . '.pbo'
        );
    }

    /**
     * Gets the full Zip URL.
     *
     * @return string
     */
    public function zipUrl()
    {
        $zip = Zip::create(storage_path("app/missions/{$this->mission->ref}/{$this->fileName()}.zip"))
            ->add(storage_path("app/missions/{$this->mission->ref}/unpacked"), true)
            ->close();

        return response()->download(
            storage_path("app/missions/{$this->mission->ref}/{$this->fileName()}.zip")
        )->deleteFileAfterSend();
    }

    /**
     * Gets the file name.
     *
     * @return string
     */
    public function fileName()
    {
        $mode = $this->mission->mode === 'adversarial' ? 'tvt' : $this->mission->mode;

        return 'ARC_' . strtoupper($mode) . '_' . studly_case($this->mission->name) . '_' . strtoupper(substr($this->mission->user->name, 0, 4));
    }
}
