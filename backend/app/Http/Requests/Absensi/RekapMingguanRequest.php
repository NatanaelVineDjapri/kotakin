<?php

namespace App\Http\Requests\Absensi;

use Illuminate\Foundation\Http\FormRequest;

class RekapMingguanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_mulai' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_akhir' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:tanggal_mulai'],
            'karyawan_id'   => ['nullable', 'integer', 'exists:karyawans,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_mulai.date_format'     => 'Format tanggal_mulai harus YYYY-MM-DD.',
            'tanggal_akhir.date_format'     => 'Format tanggal_akhir harus YYYY-MM-DD.',
            'tanggal_akhir.after_or_equal'  => 'tanggal_akhir harus sama atau setelah tanggal_mulai.',
            'karyawan_id.exists'            => 'Karyawan tidak ditemukan.',
        ];
    }
}
