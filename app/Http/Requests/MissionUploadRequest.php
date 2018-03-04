<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MissionUploadRequest extends FormRequest
{
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
            'file' => 'required|file|mimes:pbo'
        ];
    }

    /**
     * Handles the execution of the request.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->file->store('missions');

        $unpacked = $this->unpack($path);
    }

    /**
     * Unpacks the PBO file.
     *
     * @return string
     */
    public function unpack(string $path)
    {
        $armake = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            ? resource_path('utils/armake.exe')
            : 'armake';

        $unpacked = dirname($path) . '/unpacked';

        shell_exec("$armake unpack -f $path $unpacked");

        $cwd = getcwd();
        chdir($unpacked);

        // Debinarize mission.sqm
        // If it's not binned, armake exits gracefully
        shell_exec("$armake derapify -f mission.sqm mission.sqm");

        chdir($cwd);

        return $unpacked;
    }
}
