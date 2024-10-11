<?php

namespace App\Http\Resources\Etc;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemHistoryResource extends JsonResource
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
            'userid' => $this->userid,
            'username' => $this->rel_userid->name,
            'route' => $this->route,
            'item' => $this->item,
            'activity' => $this->activity,
            'tag' => $this->tag,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->rel_created_by,
        ];
    }
}
