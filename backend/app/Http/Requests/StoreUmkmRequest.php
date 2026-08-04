<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUmkmRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_umkm' => ['required', 'string', 'max:255'],
            'email_pemilik' => ['required', 'string', 'max:20'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ];
    }
}