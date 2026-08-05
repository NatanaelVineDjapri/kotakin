<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Data User (Admin)
            'name'                  => ['required', 'string', 'max:150'],
            'email'                 => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],

            // Data UMKM
            'nama_umkm'             => ['required', 'string', 'max:150'],
            'no_hp'                 => ['nullable', 'string', 'max:20'],
            'alamat'                => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'nama_umkm.required'    => 'Nama bisnis wajib diisi.',
        ];
    }
}
