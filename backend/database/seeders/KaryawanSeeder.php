<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Umkm;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Absensi;
use Carbon\Carbon;

class KaryawanSeeder extends Seeder
{
    /**
     * Seed data karyawan + absensi untuk keperluan testing.
     *
     * Strategi:
     * - Ambil UMKM pertama yang ada (dari proses register sebelumnya).
     * - Kalau belum ada UMKM, buat dummy dulu.
     * - Buat 2 karyawan dengan data absensi bulan ini.
     */
    public function run(): void
    {
        // --- Ambil atau buat UMKM ---
        $umkm = Umkm::first();

        if ($umkm === null) {
            $umkm = Umkm::create([
                'nama_umkm'       => 'Warung Makan Sehat',
                'email_pemilik'   => 'owner@warungsehat.test',
                'status_langganan' => 'trial',
            ]);

            // Buat user admin untuk UMKM ini
            $adminUser = User::create([
                'umkm_id'  => $umkm->id,
                'name'     => 'Admin Warung',
                'email'    => 'admin@warungsehat.test',
                'password' => Hash::make('password'),
            ]);

            $adminUser->assignRole('admin');

            $this->command->info('KaryawanSeeder: UMKM dummy dibuat (tidak ada UMKM sebelumnya).');
        }

        $this->command->info('KaryawanSeeder: Menggunakan UMKM "' . $umkm->nama_umkm . '" (ID: ' . $umkm->id . ')');

        // --- Buat Karyawan 1 ---
        $user1 = User::firstOrCreate(
            ['email' => 'budi.karyawan@test.com'],
            [
                'umkm_id'  => $umkm->id,
                'name'     => 'Budi Santoso',
                'password' => Hash::make('password'),
            ]
        );
        $user1->assignRole('karyawan');

        $karyawan1 = Karyawan::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'umkm_id'           => $umkm->id,
                'nip'               => 'KRY-001',
                'jabatan'           => 'Kasir',
                'no_hp'             => '081234567890',
                'tanggal_bergabung' => '2025-01-01',
                'status'            => 'aktif',
            ]
        );

        // --- Buat Karyawan 2 ---
        $user2 = User::firstOrCreate(
            ['email' => 'sari.karyawan@test.com'],
            [
                'umkm_id'  => $umkm->id,
                'name'     => 'Sari Dewi',
                'password' => Hash::make('password'),
            ]
        );
        $user2->assignRole('karyawan');

        $karyawan2 = Karyawan::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'umkm_id'           => $umkm->id,
                'nip'               => 'KRY-002',
                'jabatan'           => 'Pramusaji',
                'no_hp'             => '089876543210',
                'tanggal_bergabung' => '2025-03-01',
                'status'            => 'aktif',
            ]
        );

        // --- Buat Data Absensi Bulan Ini (7 hari terakhir) ---
        $this->buatAbsensi($umkm->id, $karyawan1);
        $this->buatAbsensi($umkm->id, $karyawan2);

        $this->command->info('KaryawanSeeder: 2 karyawan + absensi 7 hari berhasil dibuat.');
        $this->command->info('  Karyawan 1 ID: ' . $karyawan1->id . ' (Budi Santoso)');
        $this->command->info('  Karyawan 2 ID: ' . $karyawan2->id . ' (Sari Dewi)');
        $this->command->info('  Login karyawan: email = budi.karyawan@test.com / password = password');
    }

    private function buatAbsensi(int $umkmId, Karyawan $karyawan): void
    {
        // Status absensi dummy untuk variasi data
        $statusList = ['hadir', 'hadir', 'telat', 'hadir', 'izin', 'hadir', 'alpha'];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);
            $status  = $statusList[$i];

            // Skip kalau sudah ada record di tanggal ini
            $sudahAda = Absensi::where('karyawan_id', $karyawan->id)
                ->whereDate('tanggal', $tanggal)
                ->exists();

            if ($sudahAda) {
                continue;
            }

            if ($status === 'hadir') {
                $waktuMasuk  = $tanggal->copy()->setTime(8, 0, 0);
                $waktuPulang = $tanggal->copy()->setTime(17, 0, 0);
                $latMasuk    = -6.2000 + (rand(-100, 100) / 10000);
                $longMasuk   = 106.8000 + (rand(-100, 100) / 10000);
            } elseif ($status === 'telat') {
                $waktuMasuk  = $tanggal->copy()->setTime(9, 30, 0);
                $waktuPulang = $tanggal->copy()->setTime(17, 0, 0);
                $latMasuk    = -6.2000 + (rand(-100, 100) / 10000);
                $longMasuk   = 106.8000 + (rand(-100, 100) / 10000);
            } else {
                $waktuMasuk  = null;
                $waktuPulang = null;
                $latMasuk    = null;
                $longMasuk   = null;
            }

            Absensi::create([
                'umkm_id'         => $umkmId,
                'karyawan_id'     => $karyawan->id,
                'jadwal_id'       => null,
                'tanggal'         => $tanggal->toDateString(),
                'waktu_masuk'     => $waktuMasuk,
                'waktu_pulang'    => $waktuPulang,
                'latitude_masuk'  => $latMasuk,
                'longitude_masuk' => $longMasuk,
                'latitude_pulang' => null,
                'longitude_pulang'=> null,
                'foto_masuk'      => null,
                'foto_pulang'     => null,
                'status'          => $status,
                'keterangan'      => ($status === 'izin') ? 'Izin keperluan keluarga' : null,
            ]);
        }
    }
}
