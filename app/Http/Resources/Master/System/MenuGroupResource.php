<?php

namespace App\Http\Resources\Master\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'groupid' => $this->groupid,
            'groupname' => $this->groupname,
            'position' => $this->position,
            'created_by' => $this->rel_created_by,
        ];
    }
}
