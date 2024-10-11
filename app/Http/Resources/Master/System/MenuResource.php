<?php

namespace App\Http\Resources\Master\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'menuid' => $this->menuid,
            'groupid' => $this->groupid,
            'groupname' => $this->rel_groupid->groupname,
            'menuname' => $this->menuname,
            'url' => $this->url,
            'parent' => $this->parent,
            'icon' => $this->icon,
            'order_no' => $this->order_no,
            'created_by' => $this->rel_created_by,
        ];
    }
}
