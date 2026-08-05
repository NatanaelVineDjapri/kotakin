<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Buat 4 role default untuk sistem Kotakin.
     *
     * Role didefinisikan HANYA di sini (Spatie Laravel Permission).
     * Tidak ada enum role di kolom manapun pada tabel database.
     *
     * Hierarki akses (dari tertinggi ke terendah):
     *   super_admin → admin → kasir / karyawan
     */
    public function run(): void
    {
        $roles = [
            'super_admin', // Pemilik platform Kotakin (developer) — lintas semua UMKM, endpoint /platform/*
            'admin',       // Pemilik / manajer UMKM — akses penuh dalam 1 UMKM
            'kasir',       // Operator POS — buat transaksi penjualan & lihat stok (read-only)
            'karyawan',    // Karyawan biasa — absensi & lihat slip gaji sendiri
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role, 'guard_name' => 'web']
            );
        }

        $this->command->info('RoleSeeder: 4 role berhasil dibuat — super_admin, admin, kasir, karyawan');
    }
}
