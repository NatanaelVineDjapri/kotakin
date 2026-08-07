<?php

namespace App\Http\Controllers;

use App\Http\Requests\Absensi\RekapHarianRequest;
use App\Http\Requests\Absensi\RekapMingguanRequest;
use App\Http\Requests\Absensi\RekapBulananRequest;
use App\Http\Requests\Absensi\DetailKaryawanRequest;
use App\Http\Resources\RekapHarianResource;
use App\Http\Resources\RekapMingguanResource;
use App\Http\Resources\RekapBulananResource;
use App\Http\Resources\AbsensiResource;
use App\Models\Karyawan;
use App\Services\AbsensiService;
use Illuminate\Http\JsonResponse;

class AbsensiController extends Controller
{
    public function __construct(protected AbsensiService $service) {}

    /**
     * Rekap Harian: daftar hadir/telat/izin/alpha seluruh karyawan dalam 1 hari.
     * GET /api/v1/absensi/rekap/harian
     * Query params: tanggal (Y-m-d), karyawan_id, status
     */
    public function rekapHarian(RekapHarianRequest $request): JsonResponse
    {
        $umkmId = $request->user()->umkm_id;
        $data   = $this->service->getRekapHarian($umkmId, $request->validated());

        return response()->json([
            'data'    => new RekapHarianResource($data),
            'message' => 'Rekap absensi harian berhasil diambil.',
        ]);
    }

    /**
     * Export rekap harian ke Excel.
     * GET /api/v1/absensi/rekap/harian/export
     * Query params: tanggal (Y-m-d), karyawan_id, status
     */
    public function exportRekapHarian(RekapHarianRequest $request)
    {
        $umkmId = $request->user()->umkm_id;

        return $this->service->exportRekapHarian($umkmId, $request->validated());
    }

    /**
     * Rekap Mingguan: total kehadiran per karyawan dalam rentang 1 minggu.
     * GET /api/v1/absensi/rekap/mingguan
     * Query params: tanggal_mulai (Y-m-d), tanggal_akhir (Y-m-d), karyawan_id
     * Default: Senin s.d. Minggu minggu ini.
     */
    public function rekapMingguan(RekapMingguanRequest $request): JsonResponse
    {
        $umkmId = $request->user()->umkm_id;
        $data   = $this->service->getRekapMingguan($umkmId, $request->validated());

        return response()->json([
            'data'    => new RekapMingguanResource($data),
            'message' => 'Rekap absensi mingguan berhasil diambil.',
        ]);
    }

    /**
     * Export rekap mingguan ke Excel.
     * GET /api/v1/absensi/rekap/mingguan/export
     */
    public function exportRekapMingguan(RekapMingguanRequest $request)
    {
        $umkmId = $request->user()->umkm_id;

        return $this->service->exportRekapMingguan($umkmId, $request->validated());
    }

    /**
     * Rekap Bulanan: total kehadiran per karyawan + dasar potongan gaji dalam 1 bulan.
     * GET /api/v1/absensi/rekap/bulanan
     * Query params: bulan (1-12), tahun (YYYY), karyawan_id
     * Default: bulan & tahun saat ini.
     */
    public function rekapBulanan(RekapBulananRequest $request): JsonResponse
    {
        $umkmId = $request->user()->umkm_id;
        $data   = $this->service->getRekapBulanan($umkmId, $request->validated());

        return response()->json([
            'data'    => new RekapBulananResource($data),
            'message' => 'Rekap absensi bulanan berhasil diambil.',
        ]);
    }

    /**
     * Export rekap bulanan ke Excel.
     * GET /api/v1/absensi/rekap/bulanan/export
     */
    public function exportRekapBulanan(RekapBulananRequest $request)
    {
        $umkmId = $request->user()->umkm_id;

        return $this->service->exportRekapBulanan($umkmId, $request->validated());
    }

    /**
     * Detail per Karyawan: semua record absensi 1 karyawan (waktu, lokasi lat-long, foto).
     * GET /api/v1/absensi/karyawan/{karyawan}
     * Query params: tanggal_mulai (Y-m-d), tanggal_akhir (Y-m-d), status
     */
    public function detailKaryawan(DetailKaryawanRequest $request, Karyawan $karyawan): JsonResponse
    {
        $umkmId = $request->user()->umkm_id;
        $data   = $this->service->getDetailKaryawan($umkmId, $karyawan->load('user'), $request->validated());

        return response()->json([
            'data' => [
                'karyawan'  => $data['karyawan'],
                'ringkasan' => $data['ringkasan'],
                'absensi'   => AbsensiResource::collection($data['absensi']),
            ],
            'message' => 'Detail absensi karyawan berhasil diambil.',
        ]);
    }
}
