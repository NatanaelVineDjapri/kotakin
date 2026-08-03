<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToUmkm;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanMasuk extends Model
{
    use BelongsToUmkm;

    protected $fillable = [
        'umkm_id', 'bahan_baku_id', 'supplier_id', 'jumlah',
        'harga_satuan', 'harga_total', 'tanggal', 'keterangan',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function umkm(): BelongsTo { return $this->belongsTo(Umkm::class); }
    public function bahanBaku(): BelongsTo { return $this->belongsTo(BahanBaku::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
}
