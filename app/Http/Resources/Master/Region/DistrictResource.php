<?php

namespace App\Http\Resources\Master\Region;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'disid' => $this->disid,
            'provid' => $this->provid,
            'provname' => $this->provname,
            'citid' => $this->citid,
            'citname' => $this->citname,
            'disname' => $this->disname,
        ];
    }
}
