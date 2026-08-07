<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_supplier' => 'required|string|max:150',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ];
    }
}
