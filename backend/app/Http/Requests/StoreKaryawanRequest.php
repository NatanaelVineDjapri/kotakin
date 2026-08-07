<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            'nip' => ['nullable','string','max:50', Rule::unique('karyawans', 'nip')],
            'jabatan' => ['required', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'tanggal_bergabung' => ['required', 'date'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            'status' => [
                'required',
                Rule::in(['aktif', 'nonaktif']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'nip.unique' => 'NIP sudah digunakan.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}