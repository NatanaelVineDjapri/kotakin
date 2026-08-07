<?php

namespace App\Http\Requests\Absensi;

use Illuminate\Foundation\Http\FormRequest;

class DetailKaryawanRequest extends FormRequest
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
            'status'        => ['nullable', 'in:hadir,telat,izin,sakit,alpha'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_mulai.date_format'    => 'Format tanggal_mulai harus YYYY-MM-DD.',
            'tanggal_akhir.date_format'    => 'Format tanggal_akhir harus YYYY-MM-DD.',
            'tanggal_akhir.after_or_equal' => 'tanggal_akhir harus sama atau setelah tanggal_mulai.',
            'status.in'                    => 'Status harus salah satu: hadir, telat, izin, sakit, alpha.',
        ];
    }
}
