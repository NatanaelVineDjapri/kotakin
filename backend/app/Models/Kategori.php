<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToUmkm;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use BelongsToUmkm;

    protected $fillable = ['umkm_id', 'nama_kategori'];

    public function umkm(): BelongsTo { return $this->belongsTo(Umkm::class); }
    public function produks(): HasMany { return $this->hasMany(Produk::class); }
}
