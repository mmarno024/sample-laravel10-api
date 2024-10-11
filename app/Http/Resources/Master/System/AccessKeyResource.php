<?php

namespace App\Http\Resources\Master\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessKeyResource extends JsonResource
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
            'accessid' => $this->accessid,
            'accessname' => $this->accessname,
            'accessgroup' => $this->accessgroup,
            'created_by' => $this->rel_created_by,
        ];
    }
}
