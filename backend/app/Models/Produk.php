<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToUmkm;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use BelongsToUmkm, SoftDeletes;

    protected $fillable = [
        'umkm_id', 'kategori_id', 'kode_produk', 'nama_produk',
        'harga_jual', 'stok', 'gambar', 'status',
    ];

    public function umkm(): BelongsTo { return $this->belongsTo(Umkm::class); }
    public function kategori(): BelongsTo { return $this->belongsTo(Kategori::class); }
    public function detailTransaksis(): HasMany { return $this->hasMany(DetailTransaksi::class); }
}
