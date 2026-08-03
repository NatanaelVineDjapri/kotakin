<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToUmkm;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    use BelongsToUmkm, SoftDeletes;

    protected $fillable = [
        'umkm_id', 'user_id', 'nip', 'no_hp', 'alamat', 'jabatan',
        'tanggal_bergabung', 'foto', 'face_id_encoding', 'status',
    ];

    protected $casts = [
        'tanggal_bergabung' => 'date',
    ];

    public function umkm(): BelongsTo { return $this->belongsTo(Umkm::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function jadwals(): HasMany { return $this->hasMany(Jadwal::class); }
    public function absensis(): HasMany { return $this->hasMany(Absensi::class); }
    public function gajis(): HasMany { return $this->hasMany(Gaji::class); }
}
