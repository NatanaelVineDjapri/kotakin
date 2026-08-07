<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UmkmResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_umkm' => $this->nama_umkm,
            'email_pemilik' => $this->email_pemilik,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
            'status_langganan' => $this->status_langganan,
            'tanggal_mulai_langganan' => $this->tanggal_mulai_langganan?->format('Y-m-d'),
            'tanggal_berakhir_langganan' => $this->tanggal_berakhir_langganan?->format('Y-m-d'),
        ];
    }
}