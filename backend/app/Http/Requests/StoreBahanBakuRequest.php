<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBahanBakuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'umkm_id' => 'required|integer|exists:umkms,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'nama_bahan' => 'required|string|max:150',
            'satuan' => 'required|string|max:20',
            'stok_saat_ini' => 'nullable|numeric|min:0',
            'stok_minimum' => 'nullable|numeric|min:0',
        ];
    }
}
