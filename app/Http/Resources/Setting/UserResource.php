<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $passwordStatus = Hash::check('Esdm123!', $this->password);
        return [
            'id' => $this->id,
            'userid' => $this->userid,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'address' => $this->address,
            'compid' => $this->compid,
            'compname' => $this->rel_compid != null ? $this->rel_compid->compname : ($this->roleid == 'USER' ? 'Tanpa Perusahaan' : 'Balai ESDM'),
            'phone' => $this->phone,
            'roleid' => $this->roleid,
            'rolename' => $this->rel_roleid != null ? $this->rel_roleid->rolename : 'Tanpa Peran',
            'photo' => $this->photo ? $this->photo : null,
            'photo_path' => $this->photo_path ? url($this->photo_path) : null,
            'password' => $passwordStatus ? 'default' : 'secure'
        ];
    }
}
