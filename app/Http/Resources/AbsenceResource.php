<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AbsenceResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user' => [
                'name' => $this->user->username,
                'steam_id' => $this->user->steam_id
            ],
            'operation' => [
                'starts_at' => $this->operation->starts_at
            ]
        ];
    }
}
