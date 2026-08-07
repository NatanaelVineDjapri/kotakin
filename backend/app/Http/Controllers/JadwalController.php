<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Karyawan;
use App\Services\JadwalService;
use App\Http\Resources\JadwalResource;
use App\Http\Requests\StoreJadwalRequest;
use App\Http\Requests\UpdateJadwalRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JadwalController extends Controller
{
    public function __construct(protected JadwalService $jadwalService) {}

    public function index(): AnonymousResourceCollection
    {
        return JadwalResource::collection(
            $this->jadwalService->getAll()
        );
    }

    public function kalenderMingguan(): JsonResponse
    {
        $grouped = $this->jadwalService->getKalenderMingguan();

        $data = $grouped->map(function ($items) {
            return JadwalResource::collection($items);
        });

        return response()->json([
            'data'    => $data,
            'message' => null,
            'errors'  => null,
        ]);
    }

    public function byKaryawan(Karyawan $karyawan): AnonymousResourceCollection
    {
        return JadwalResource::collection(
            $this->jadwalService->getByKaryawan($karyawan)
        );
    }

    public function show(Jadwal $jadwal): JadwalResource
    {
        return new JadwalResource(
            $this->jadwalService->getById($jadwal)
        );
    }

    public function store(StoreJadwalRequest $request): JadwalResource
    {
        $jadwal = $this->jadwalService->create($request->validated());

        return new JadwalResource($jadwal);
    }

    public function update(UpdateJadwalRequest $request, Jadwal $jadwal): JadwalResource
    {
        $jadwal = $this->jadwalService->update($jadwal, $request->validated());

        return new JadwalResource($jadwal);
    }

    public function destroy(Jadwal $jadwal): JsonResponse
    {
        $this->jadwalService->delete($jadwal);

        return response()->json([
            'data'    => null,
            'message' => 'Jadwal berhasil dihapus.',
            'errors'  => null,
        ]);
    }
}