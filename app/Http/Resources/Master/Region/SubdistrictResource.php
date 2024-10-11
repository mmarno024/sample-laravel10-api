<?php

namespace App\Http\Resources\Master\Region;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubdistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subid' => $this->subid,
            'disid' => $this->disid,
            'disname' => $this->disname,
            'provid' => $this->provid,
            'provname' => $this->provname,
            'citid' => $this->citid,
            'citname' => $this->citname,
            'subname' => $this->subname,
        ];
    }
}
