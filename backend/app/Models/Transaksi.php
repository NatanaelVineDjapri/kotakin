<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToUmkm;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use BelongsToUmkm;

    protected $fillable = [
        'umkm_id', 'kasir_id', 'kode_transaksi', 'total', 'metode_pembayaran', 'tanggal',
    ];

    protected $casts = ['tanggal' => 'datetime'];

    public function umkm(): BelongsTo { return $this->belongsTo(Umkm::class); }
    public function kasir(): BelongsTo { return $this->belongsTo(User::class, 'kasir_id'); }
    public function detailTransaksis(): HasMany { return $this->hasMany(DetailTransaksi::class); }
}
