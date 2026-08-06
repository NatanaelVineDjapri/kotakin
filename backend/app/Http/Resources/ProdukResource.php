<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'umkm_id' => $this->umkm_id,
            'kategori_id' => $this->kategori_id,
            'kode_produk' => $this->kode_produk,
            'nama_produk' => $this->nama_produk,
            'harga_jual' => $this->harga_jual,
            'stok' => $this->stok,
            'gambar' => $this->gambar,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
