<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BahanMasukResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'umkm_id' => $this->umkm_id,
            'bahan_baku_id' => $this->bahan_baku_id,
            'supplier_id' => $this->supplier_id,
            'jumlah' => $this->jumlah,
            'harga_satuan' => $this->harga_satuan,
            'harga_total' => $this->harga_total,
            'tanggal' => $this->tanggal->format('Y-m-d'),
            'keterangan' => $this->keterangan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
