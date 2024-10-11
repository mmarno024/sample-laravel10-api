<?php

namespace App\Http\Resources\Master\Region;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'citid' => $this->citid,
            'provid' => $this->provid,
            'provname' => $this->provname,
            'citname' => $this->citname,
        ];
    }
}
