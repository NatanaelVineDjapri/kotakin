<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToUmkm;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use BelongsToUmkm;

    protected $fillable = [
        'umkm_id', 'karyawan_id', 'jadwal_id', 'tanggal', 'waktu_masuk', 'waktu_pulang',
        'latitude_masuk', 'longitude_masuk', 'latitude_pulang', 'longitude_pulang',
        'foto_masuk', 'foto_pulang', 'status', 'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_masuk' => 'datetime',
        'waktu_pulang' => 'datetime',
    ];

    public function umkm(): BelongsTo { return $this->belongsTo(Umkm::class); }
    public function karyawan(): BelongsTo { return $this->belongsTo(Karyawan::class); }
    public function jadwal(): BelongsTo { return $this->belongsTo(Jadwal::class); }
}
