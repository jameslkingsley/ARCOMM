<?php

namespace App\Http\Requests;

use App\Traits\MissionRequest;
use Illuminate\Foundation\Http\FormRequest;

class MissionUploadRequest extends FormRequest
{
    use MissionRequest;
}
