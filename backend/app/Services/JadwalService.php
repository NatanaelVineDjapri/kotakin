<?php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class JadwalService
{
    public function getAll(): Collection
    {
        return Jadwal::with('karyawan.user')
            ->orderBy('karyawan_id')
            ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->get();
    }

    public function getByKaryawan(Karyawan $karyawan): Collection
    {
        return $karyawan->jadwals()
            ->with('karyawan.user')
            ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->get();
    }

    public function getKalenderMingguan(): Collection
    {
        return Jadwal::with('karyawan.user')
            ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->get()
            ->groupBy('hari');
    }

    public function getById(Jadwal $jadwal): Jadwal
    {
        return $jadwal->load('karyawan.user');
    }

    public function create(array $data): Jadwal
    {
        $jadwal = Jadwal::updateOrCreate(
            [
                'karyawan_id' => $data['karyawan_id'],
                'hari'        => $data['hari'],
            ],
            [
                'jam_masuk'  => $data['jam_masuk'],
                'jam_pulang' => $data['jam_pulang'],
                'shift'      => $data['shift'] ?? null,
            ]
        );

        return $jadwal->load('karyawan.user');
    }

    public function update(Jadwal $jadwal, array $data): Jadwal
    {
        $jadwal->update($data);

        return $jadwal->fresh()->load('karyawan.user');
    }

    public function delete(Jadwal $jadwal): void
    {
        $jadwal->delete();
    }
}