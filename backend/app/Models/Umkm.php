<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Umkm extends Model
{
    protected $fillable = [
        'nama_umkm', 'email_pemilik', 'no_hp', 'alamat', 'status_langganan', 'tanggal_mulai_langganan', 'tanggal_berakhir_langganan',
    ];

    protected $casts = [
        'tanggal_mulai_langganan' => 'date',
        'tanggal_berakhir_langganan' => 'date',
    ];

    public function users(): HasMany { return $this->hasMany(User::class); }
    
    public function karyawans(): HasMany { return $this->hasMany(Karyawan::class); }
    public function jadwals(): HasMany { return $this->hasMany(Jadwal::class); }
    public function absensis(): HasMany { return $this->hasMany(Absensi::class); }
    public function gajis(): HasMany { return $this->hasMany(Gaji::class); }
    public function kategoris(): HasMany { return $this->hasMany(Kategori::class); }
    public function produks(): HasMany { return $this->hasMany(Produk::class); }
    public function suppliers(): HasMany { return $this->hasMany(Supplier::class); }
    public function bahanBakus(): HasMany { return $this->hasMany(BahanBaku::class); }
    public function bahanMasuks(): HasMany { return $this->hasMany(BahanMasuk::class); }
    public function bahanKeluars(): HasMany { return $this->hasMany(BahanKeluar::class); }
    public function transaksis(): HasMany { return $this->hasMany(Transaksi::class); }
}
