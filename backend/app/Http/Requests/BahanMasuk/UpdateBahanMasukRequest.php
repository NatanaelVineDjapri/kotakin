<?php

namespace App\Http\Requests\BahanMasuk;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBahanMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'umkm_id' => 'required|integer|exists:umkms,id',
            'bahan_baku_id' => 'required|integer|exists:bahan_bakus,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'jumlah' => 'required|numeric|min:0.01',
            'harga_satuan' => 'required|numeric|min:0',
            'harga_total' => 'nullable|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ];
    }
}
