<?php

namespace App\Http\Requests\Absensi;

use Illuminate\Foundation\Http\FormRequest;

class RekapHarianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // otorisasi di middleware route
    }

    public function rules(): array
    {
        return [
            'tanggal'      => ['nullable', 'date_format:Y-m-d'],
            'karyawan_id'  => ['nullable', 'integer', 'exists:karyawans,id'],
            'status'       => ['nullable', 'in:hadir,telat,izin,sakit,alpha'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.date_format'  => 'Format tanggal harus YYYY-MM-DD.',
            'karyawan_id.exists'   => 'Karyawan tidak ditemukan.',
            'status.in'            => 'Status harus salah satu: hadir, telat, izin, sakit, alpha.',
        ];
    }
}
