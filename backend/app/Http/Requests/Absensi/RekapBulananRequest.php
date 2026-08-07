<?php

namespace App\Http\Requests\Absensi;

use Illuminate\Foundation\Http\FormRequest;

class RekapBulananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bulan'       => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun'       => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'karyawan_id' => ['nullable', 'integer', 'exists:karyawans,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'bulan.min'         => 'Bulan harus antara 1 s.d. 12.',
            'bulan.max'         => 'Bulan harus antara 1 s.d. 12.',
            'karyawan_id.exists' => 'Karyawan tidak ditemukan.',
        ];
    }
}
