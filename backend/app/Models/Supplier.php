<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToUmkm;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use BelongsToUmkm;

    protected $fillable = ['umkm_id', 'nama_supplier', 'no_hp', 'alamat'];

    public function umkm(): BelongsTo { return $this->belongsTo(Umkm::class); }
    public function bahanBakus(): HasMany { return $this->hasMany(BahanBaku::class); }
}
