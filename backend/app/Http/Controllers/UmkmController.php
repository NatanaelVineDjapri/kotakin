<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUmkmRequest;
use App\Http\Requests\UpdateUmkmRequest;
use App\Http\Resources\UmkmResource;
use App\Services\UmkmService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UmkmController extends Controller
{
    public function __construct(protected UmkmService $umkmService) {}

    public function show(Request $request)
    {
        $umkm = $this->umkmService->getAllUmkm();

        return UmkmResource::collection($umkm);
    }

    public function showById(int $id)
    {
        $umkm = $this->umkmService->getUmkmById($id);

        return new UmkmResource($umkm);
    }

    public function store(StoreUmkmRequest $request)
    {
        $umkm = $this->umkmService->createUmkm($request->validated());

        return new UmkmResource($umkm);
    }

    public function update(UpdateUmkmRequest $request, int $id)
    {
        $umkm = $this->umkmService->updateUmkmById($id,$request->validated());

        return new UmkmResource($umkm);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->umkmService->deleteUmkmById($id);

        return response()->json(['message' => 'UMKM berhasil dihapus.']);
    }
}