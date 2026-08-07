<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'karyawan_id' => [
                'required',
                'integer',
                Rule::exists('karyawans', 'id')->where(function ($query) {
                    $query->where('umkm_id', auth()->user()->umkm_id);
                }),
            ],
            'hari' => [
                'required',
                Rule::in(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']),
            ],
            'jam_masuk'  => ['required', 'date_format:H:i'],
            'jam_pulang' => ['required', 'date_format:H:i', 'after:jam_masuk'],
            'shift'      => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'karyawan_id.exists' => 'Karyawan tidak ditemukan atau bukan milik UMKM Anda.',
            'jam_pulang.after'   => 'Jam pulang harus setelah jam masuk.',
        ];
    }
}