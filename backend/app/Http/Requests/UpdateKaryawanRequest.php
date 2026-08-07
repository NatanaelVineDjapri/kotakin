<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $karyawan = $this->route('karyawan');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($karyawan->user_id)],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('karyawans', 'nip')->ignore($karyawan->id)],
            'jabatan' => ['required', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'tanggal_bergabung' => ['required', 'date'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email sudah digunakan.',
            'nip.unique' => 'NIP sudah digunakan.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}