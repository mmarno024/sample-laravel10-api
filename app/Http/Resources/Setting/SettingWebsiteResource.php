<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingWebsiteResource extends JsonResource
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
            'isWelcome' => $this->isWelcome,
            'isAbout' => $this->isAbout,
            'isUser' => $this->isUser,
            'isUse' => $this->isUse,
            'isBook' => $this->isBook,
            'isReport' => $this->isReport,
        ];
    }
}
