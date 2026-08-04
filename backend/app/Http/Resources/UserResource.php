<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'is_active'  => $this->is_active,
            'roles'      => $this->getRoleNames(),
            'umkm'       => $this->whenLoaded('umkm', fn() => [
                'id'                         => $this->umkm->id,
                'nama_umkm'                  => $this->umkm->nama_umkm,
                'status_langganan'           => $this->umkm->status_langganan,
                'tanggal_berakhir_langganan' => $this->umkm->tanggal_berakhir_langganan,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
