<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'userid' => $this->userid,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'address' => $this->address,
            'compid' => $this->compid,
            'phone' => $this->phone,
            'def_role' => $this->rel_def_role != null ? $this->rel_def_role->rolename : '',
            'photo' => $this->photo ? $this->photo : null,
            'photo_path' => $this->photo_path ? url($this->photo_path) : null,
        ];
    }
}
