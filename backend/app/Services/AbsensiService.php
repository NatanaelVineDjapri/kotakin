<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapHarianExport;
use App\Exports\RekapMingguanExport;
use App\Exports\RekapBulananExport;

class AbsensiService
{
    /**
     * Rekap Harian: semua karyawan aktif beserta status absensinya pada tanggal tertentu.
     *
     * @param  int    $umkmId
     * @param  array  $filters  [tanggal, karyawan_id, status]
     * @return array
     */
    public function getRekapHarian(int $umkmId, array $filters = []): array
    {
        $tanggal = isset($filters['tanggal'])
            ? Carbon::parse($filters['tanggal'])->toDateString() //from string ke tanggal
            : Carbon::today()->toDateString();

        // Ambil semua absensi pada tanggal tersebut, scoped ke UMKM
        $query = Absensi::with(['karyawan.user', 'jadwal'])
            ->where('umkm_id', $umkmId)
            ->whereDate('tanggal', $tanggal);

        if (!empty($filters['karyawan_id'])) {
            $query->where('karyawan_id', $filters['karyawan_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $absensis = $query->orderBy('waktu_masuk')->get();

        // Karyawan yang belum ada record absensi hari ini = alpha (tidak tercatat)
        // Hanya dihitung jika tidak ada filter karyawan_id & status agar tidak bias
        $totalKaryawan = Karyawan::where('umkm_id', $umkmId)
            ->where('status', 'aktif')
            ->count();

        $ringkasan = [
            'total_karyawan' => $totalKaryawan,
            'hadir'          => $absensis->where('status', 'hadir')->count(),
            'telat'          => $absensis->where('status', 'telat')->count(),
            'izin'           => $absensis->where('status', 'izin')->count(),
            'sakit'          => $absensis->where('status', 'sakit')->count(),
            'alpha'          => $absensis->where('status', 'alpha')->count(),
            // yang belum melakukan absensi sama sekali
            'belum_absen'    => max(0, $totalKaryawan - $absensis->count()),
        ];

        return [
            'tanggal'   => $tanggal,
            'ringkasan' => $ringkasan,
            'detail'    => $absensis,
        ];
    }

    /**
     * Export rekap harian ke file Excel.
     *
     * @param  int    $umkmId
     * @param  array  $filters
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportRekapHarian(int $umkmId, array $filters = [])
    {
        $data = $this->getRekapHarian($umkmId, $filters);
        $filename = 'rekap-absensi-harian-' . $data['tanggal'] . '.xlsx';

        return Excel::download(new RekapHarianExport($data), $filename);
    }

    /**
     * Rekap Mingguan: total hadir/telat/izin/sakit/alpha per karyawan dalam rentang 1 minggu.
     *
     * @param  int    $umkmId
     * @param  array  $filters  [tanggal_mulai, tanggal_akhir, karyawan_id]
     * @return array
     */
    public function getRekapMingguan(int $umkmId, array $filters = []): array
    {
        // Default: Senin s.d. Minggu minggu ini
        if (isset($filters['tanggal_mulai'])) {
            $mulai = Carbon::parse($filters['tanggal_mulai'])->startOfDay();
        } else {
            $mulai = Carbon::now()->startOfWeek(Carbon::MONDAY);
        }

        if (isset($filters['tanggal_akhir'])) {
            $akhir = Carbon::parse($filters['tanggal_akhir'])->endOfDay();
        } else {
            $akhir = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        }

        $query = Absensi::with(['karyawan.user'])
            ->where('umkm_id', $umkmId)
            ->whereBetween('tanggal', [$mulai->toDateString(), $akhir->toDateString()]);

        if (!empty($filters['karyawan_id'])) {
            $query->where('karyawan_id', $filters['karyawan_id']);
        }

        $absensis = $query->get();

        // Kelompokkan per karyawan
        $perKaryawan = $absensis
            ->groupBy('karyawan_id')
            ->map(function ($records) {
                $karyawan = $records->first()->karyawan;

                if ($karyawan === null) {
                    $karyawanId = null;
                    $nama       = '-';
                    $nip        = '-';
                    $jabatan    = '-';
                } else {
                    $karyawanId = $karyawan->id;
                    $nip        = $karyawan->nip;
                    $jabatan    = $karyawan->jabatan;

                    if ($karyawan->user !== null) {
                        $nama = $karyawan->user->name;
                    } else {
                        $nama = '-';
                    }
                }

                return [
                    'karyawan_id' => $karyawanId,
                    'nama'        => $nama,
                    'nip'         => $nip,
                    'jabatan'     => $jabatan,
                    'total_hari'  => $records->count(),
                    'hadir'       => $records->where('status', 'hadir')->count(),
                    'telat'       => $records->where('status', 'telat')->count(),
                    'izin'        => $records->where('status', 'izin')->count(),
                    'sakit'       => $records->where('status', 'sakit')->count(),
                    'alpha'       => $records->where('status', 'alpha')->count(),
                ];
            })
            ->values();

        // Ringkasan keseluruhan
        $ringkasan = [
            'total_karyawan'    => $perKaryawan->count(),
            'total_hari_kerja'  => $mulai->diffInDays($akhir) + 1,
            'total_hadir'       => $absensis->where('status', 'hadir')->count(),
            'total_telat'       => $absensis->where('status', 'telat')->count(),
            'total_izin'        => $absensis->where('status', 'izin')->count(),
            'total_sakit'       => $absensis->where('status', 'sakit')->count(),
            'total_alpha'       => $absensis->where('status', 'alpha')->count(),
        ];

        return [
            'tanggal_mulai' => $mulai->toDateString(),
            'tanggal_akhir' => $akhir->toDateString(),
            'ringkasan'     => $ringkasan,
            'per_karyawan'  => $perKaryawan,
        ];
    }

    /**
     * Export rekap mingguan ke file Excel.
     */
    public function exportRekapMingguan(int $umkmId, array $filters = [])
    {
        $data     = $this->getRekapMingguan($umkmId, $filters);
        $filename = 'rekap-absensi-mingguan-' . $data['tanggal_mulai'] . '_sd_' . $data['tanggal_akhir'] . '.xlsx';

        return Excel::download(new RekapMingguanExport($data), $filename);
    }

    /**
     * Rekap Bulanan: total kehadiran per karyawan dalam 1 bulan + dasar potongan gaji.
     *
     * @param  int    $umkmId
     * @param  array  $filters  [bulan, tahun, karyawan_id]
     * @return array
     */
    public function getRekapBulanan(int $umkmId, array $filters = []): array
    {
        if (isset($filters['bulan'])) {
            $bulan = (int) $filters['bulan'];
        } else {
            $bulan = (int) Carbon::now()->month;
        }

        if (isset($filters['tahun'])) {
            $tahun = (int) $filters['tahun'];
        } else {
            $tahun = (int) Carbon::now()->year;
        }

        $awalBulan = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        // Total hari kalender dalam bulan ini (untuk hitung % kehadiran)
        $totalHariKalender = $awalBulan->daysInMonth;

        $query = Absensi::with(['karyawan.user'])
            ->where('umkm_id', $umkmId)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan);

        if (!empty($filters['karyawan_id'])) {
            $query->where('karyawan_id', $filters['karyawan_id']);
        }

        $absensis = $query->get();

        // Kelompokkan per karyawan + hitung dasar potongan
        $perKaryawan = $absensis
            ->groupBy('karyawan_id')
            ->map(function ($records) use ($totalHariKalender) {
                $karyawan = $records->first()->karyawan;

                $hadir  = $records->where('status', 'hadir')->count();
                $telat  = $records->where('status', 'telat')->count();
                $izin   = $records->where('status', 'izin')->count();
                $sakit  = $records->where('status', 'sakit')->count();
                $alpha  = $records->where('status', 'alpha')->count();
                $total  = $records->count();

                // Dasar potongan: hari alpha saja yang jadi pengurang gaji
                // (izin & sakit biasanya tidak dipotong — sesuaikan dengan kebijakan UMKM)
                $hariPotongan = $alpha;

                if ($total > 0) {
                    $persenKehadiran = round(($hadir + $telat) / $totalHariKalender * 100, 1);
                } else {
                    $persenKehadiran = 0;
                }

                if ($karyawan === null) {
                    $karyawanId = null;
                    $nama       = '-';
                    $nip        = '-';
                    $jabatan    = '-';
                } else {
                    $karyawanId = $karyawan->id;
                    $nip        = $karyawan->nip;
                    $jabatan    = $karyawan->jabatan;

                    if ($karyawan->user !== null) {
                        $nama = $karyawan->user->name;
                    } else {
                        $nama = '-';
                    }
                }

                return [
                    'karyawan_id'      => $karyawanId,
                    'nama'             => $nama,
                    'nip'              => $nip,
                    'jabatan'          => $jabatan,
                    'total_hari'       => $total,
                    'hadir'            => $hadir,
                    'telat'            => $telat,
                    'izin'             => $izin,
                    'sakit'            => $sakit,
                    'alpha'            => $alpha,
                    'persen_kehadiran' => $persenKehadiran,
                    // Field ini yang akan dipakai modul payroll untuk hitung potongan
                    'dasar_potongan'   => [
                        'hari_potongan'   => $hariPotongan,
                        'catatan'         => 'Potongan dihitung dari jumlah hari alpha.',
                    ],
                ];
            })
            ->values();

        $ringkasan = [
            'total_karyawan'   => $perKaryawan->count(),
            'total_hari_bulan' => $totalHariKalender,
            'total_hadir'      => $absensis->where('status', 'hadir')->count(),
            'total_telat'      => $absensis->where('status', 'telat')->count(),
            'total_izin'       => $absensis->where('status', 'izin')->count(),
            'total_sakit'      => $absensis->where('status', 'sakit')->count(),
            'total_alpha'      => $absensis->where('status', 'alpha')->count(),
        ];

        return [
            'periode' => [
                'bulan'        => $bulan,
                'tahun'        => $tahun,
                'label'        => $awalBulan->translatedFormat('F Y'), // "Agustus 2026"
                'tanggal_mulai' => $awalBulan->toDateString(),
                'tanggal_akhir' => $akhirBulan->toDateString(),
            ],
            'ringkasan'    => $ringkasan,
            'per_karyawan' => $perKaryawan,
        ];
    }

    /**
     * Export rekap bulanan ke file Excel.
     */
    public function exportRekapBulanan(int $umkmId, array $filters = [])
    {
        $data     = $this->getRekapBulanan($umkmId, $filters);
        $filename = 'rekap-absensi-' . $data['periode']['tahun'] . '-bulan-' . str_pad($data['periode']['bulan'], 2, '0', STR_PAD_LEFT) . '.xlsx';

        return Excel::download(new RekapBulananExport($data), $filename);
    }

    /**
     * Detail per Karyawan: semua record absensi 1 karyawan beserta waktu, lokasi, dan foto.
     *
     * @param  int                       $umkmId
     * @param  \App\Models\Karyawan      $karyawan
     * @param  array                     $filters  [tanggal_mulai, tanggal_akhir, status]
     * @return array
     */
    public function getDetailKaryawan(int $umkmId, Karyawan $karyawan, array $filters = []): array
    {
        // Pastikan karyawan milik UMKM yang sama (keamanan data lintas tenant)
        if ($karyawan->umkm_id !== $umkmId) {
            abort(403, 'Akses ditolak.');
        }

        $query = Absensi::with(['jadwal'])
            ->where('umkm_id', $umkmId)
            ->where('karyawan_id', $karyawan->id);

        if (!empty($filters['tanggal_mulai'])) {
            $query->whereDate('tanggal', '>=', $filters['tanggal_mulai']);
        }

        if (!empty($filters['tanggal_akhir'])) {
            $query->whereDate('tanggal', '<=', $filters['tanggal_akhir']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $absensis = $query->orderBy('tanggal', 'desc')->get();

        $ringkasan = [
            'total' => $absensis->count(),
            'hadir' => $absensis->where('status', 'hadir')->count(),
            'telat' => $absensis->where('status', 'telat')->count(),
            'izin'  => $absensis->where('status', 'izin')->count(),
            'sakit' => $absensis->where('status', 'sakit')->count(),
            'alpha' => $absensis->where('status', 'alpha')->count(),
        ];

        // Ambil nama karyawan dengan aman
        if ($karyawan->user !== null) {
            $namaKaryawan = $karyawan->user->name;
        } else {
            $namaKaryawan = '-';
        }

        return [
            'karyawan' => [
                'id'      => $karyawan->id,
                'nama'    => $namaKaryawan,
                'nip'     => $karyawan->nip,
                'jabatan' => $karyawan->jabatan,
                'foto'    => $karyawan->foto,
            ],
            'ringkasan' => $ringkasan,
            'absensi'   => $absensis,
        ];
    }
}

