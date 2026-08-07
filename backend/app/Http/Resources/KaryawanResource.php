<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KaryawanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'nip'                => $this->nip,
            'jabatan'            => $this->jabatan,
            'no_hp'              => $this->no_hp,
            'alamat'             => $this->alamat,
            'tanggal_bergabung'  => $this->tanggal_bergabung,
            'foto'               => $this->foto,
            'status'             => $this->status,
            'user'               => $this->whenLoaded('user', fn() => [
                'id'        => $this->user->id,
                'name'      => $this->user->name,
                'email'     => $this->user->email,
                'is_active' => $this->user->is_active,
                'roles'     => $this->user->getRoleNames(),
            ]),
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}