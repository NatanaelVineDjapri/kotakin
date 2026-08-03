<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToUmkm;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanBaku extends Model
{
    use BelongsToUmkm;

    protected $fillable = [
        'umkm_id', 'supplier_id', 'nama_bahan', 'satuan', 'stok_saat_ini', 'stok_minimum',
    ];

    public function umkm(): BelongsTo { return $this->belongsTo(Umkm::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function bahanMasuks(): HasMany { return $this->hasMany(BahanMasuk::class); }
    public function bahanKeluars(): HasMany { return $this->hasMany(BahanKeluar::class); }
}
