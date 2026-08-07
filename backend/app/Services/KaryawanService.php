<?php

namespace App\Services;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

class KaryawanService
{
    public function getAll()
    {
        return Karyawan::with('user')->latest()->paginate(10);
    }

    public function getById(Karyawan $karyawan)
    {
        return $karyawan->load(['user', 'absensis', 'gajis', 'jadwals']);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'umkm_id' => auth()->user()->umkm_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'is_active' => true,
            ]);

            $user->assignRole('karyawan');

            return Karyawan::create([
                'umkm_id' => auth()->user()->umkm_id,
                'user_id' => $user->id,
                'nip' => $data['nip'] ?? null,
                'jabatan' => $data['jabatan'],
                'no_hp' => $data['no_hp'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'tanggal_bergabung' => $data['tanggal_bergabung'],
                'foto' => $data['foto'] ?? null,
                'status' => $data['status'],
            ]);
        });
    }

    public function update(Karyawan $karyawan, array $data)
    {
        return DB::transaction(function () use ($karyawan, $data) {
            $karyawan->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            $karyawan->update([
                'nip' => $data['nip'] ?? null,
                'jabatan' => $data['jabatan'],
                'no_hp' => $data['no_hp'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'tanggal_bergabung' => $data['tanggal_bergabung'],
                'foto' => $data['foto'] ?? $karyawan->foto,
                'status' => $data['status'],
            ]);

            return $karyawan->fresh()->load('user');
        });
    }

    public function deactivate(Karyawan $karyawan)
    {
        return DB::transaction(function () use ($karyawan) {
            $karyawan->user->update([
                'is_active' => false,
            ]);

            $karyawan->update([
                'status' => 'nonaktif',
            ]);

            $karyawan->delete();
        });
    }
}