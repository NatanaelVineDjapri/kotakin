<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'nama_umkm' => ['sometimes', 'required', 'string', 'max:150'],
            'email_pemilik' => ['sometimes', 'required', 'string', 'max:150'],
            'no_hp' => ['sometimes', 'nullable', 'string', 'max:20'],
            'alamat' => ['sometimes', 'nullable', 'string'],
        ];
    }
}