<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToUmkm;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gaji extends Model
{
    use BelongsToUmkm;

    protected $fillable = [
        'umkm_id', 'karyawan_id', 'periode_bulan', 'periode_tahun', 'gaji_pokok',
        'tunjangan', 'potongan', 'total_gaji', 'status_pembayaran', 'tanggal_dibayar',
    ];

    protected $casts = [
        'tanggal_dibayar' => 'date',
    ];

    public function umkm(): BelongsTo { return $this->belongsTo(Umkm::class); }
    public function karyawan(): BelongsTo { return $this->belongsTo(Karyawan::class); }
}
