<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\JsonResponse;
use App\Services\KaryawanService;
use App\Http\Resources\KaryawanResource;
use App\Http\Requests\StoreKaryawanRequest;
use App\Http\Requests\UpdateKaryawanRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KaryawanController extends Controller
{
    public function __construct(protected KaryawanService $karyawanService) {}

    public function index()
    {
    return $this->karyawanService->getAll();
    }

    public function show(Karyawan $karyawan): KaryawanResource
    {
        return new KaryawanResource(
            $this->karyawanService->getById($karyawan)
        );
    }

    public function store(StoreKaryawanRequest $request)
    {
    $karyawan = $this->karyawanService->create(
        $request->validated()
    );

    return new KaryawanResource($karyawan);
    }

    public function update(UpdateKaryawanRequest $request, Karyawan $karyawan): KaryawanResource
    {
        $karyawan = $this->karyawanService->update(
            $karyawan,
            $request->validated()
        );

        return new KaryawanResource($karyawan);
    }

    public function destroy(Karyawan $karyawan): JsonResponse
    {
        $this->karyawanService->deactivate($karyawan);

        return response()->json([
            'data' => null,
            'message' => 'Karyawan berhasil dinonaktifkan.',
            'errors' => null,
        ]);
    }
}