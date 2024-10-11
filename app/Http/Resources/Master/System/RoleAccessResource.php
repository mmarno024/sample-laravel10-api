<?php

namespace App\Http\Resources\Master\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleAccessResource extends JsonResource
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
            'roleid' => $this->roleid,
            'rolename' => $this->rolename,
            'created_by' => $this->rel_created_by,
        ];
    }
}
