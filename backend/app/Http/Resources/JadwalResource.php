<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'karyawan'    => [
                'id'   => $this->karyawan->id,
                'nama' => $this->karyawan->user->name ?? null,
                'nip'  => $this->karyawan->nip,
            ],
            'hari'        => $this->hari,
            'jam_masuk'   => $this->jam_masuk,
            'jam_pulang'  => $this->jam_pulang,
            'shift'       => $this->shift,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}