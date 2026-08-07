<?php

namespace App\Http\Requests\Produk;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'umkm_id' => 'required|integer|exists:umkms,id',
            'kategori_id' => 'nullable|integer|exists:kategoris,id',
            'kode_produk' => 'nullable|string|max:50',
            'nama_produk' => 'required|string|max:150',
            'harga_jual' => 'required|numeric',
            'stok' => 'nullable|integer',
            'gambar' => 'nullable|string',
            'status' => 'nullable|in:aktif,nonaktif',
        ];
    }
}
