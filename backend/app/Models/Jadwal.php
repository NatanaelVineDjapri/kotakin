<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\BelongsToUmkm;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Jadwal extends Model
{
    use BelongsToUmkm;

    protected $fillable = [
        'umkm_id', 'karyawan_id', 'hari', 'jam_masuk', 'jam_pulang', 'shift',
    ];

    public function umkm(): BelongsTo { return $this->belongsTo(Umkm::class); }
    public function karyawan(): BelongsTo { return $this->belongsTo(Karyawan::class); }
    public function absensis(): HasMany { return $this->hasMany(Absensi::class); }
}
