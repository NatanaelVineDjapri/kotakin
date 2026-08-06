<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BahanBakuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'umkm_id' => $this->umkm_id,
            'supplier_id' => $this->supplier_id,
            'nama_bahan' => $this->nama_bahan,
            'satuan' => $this->satuan,
            'stok_saat_ini' => $this->stok_saat_ini,
            'stok_minimum' => $this->stok_minimum,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
