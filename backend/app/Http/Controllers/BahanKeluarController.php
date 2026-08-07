<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBahanKeluarRequest;
use App\Http\Requests\UpdateBahanKeluarRequest;
use App\Http\Resources\BahanKeluarResource;
use App\Models\BahanKeluar;
use App\Services\BahanKeluarService;

class BahanKeluarController extends Controller
{
    public function __construct(protected BahanKeluarService $service) {}

    public function index()
    {
        return BahanKeluarResource::collection($this->service->getAll());
    }

    public function store(StoreBahanKeluarRequest $request)
    {
        $bahanKeluar = $this->service->create($request->validated());
        return new BahanKeluarResource($bahanKeluar);
    }

    public function show(BahanKeluar $bahanKeluar)
    {
        return new BahanKeluarResource($bahanKeluar);
    }

    public function update(UpdateBahanKeluarRequest $request, BahanKeluar $bahanKeluar)
    {
        $bahanKeluar = $this->service->update($bahanKeluar, $request->validated());
        return new BahanKeluarResource($bahanKeluar);
    }

    public function destroy(BahanKeluar $bahanKeluar)
    {
        $this->service->delete($bahanKeluar);
        return response()->json(['message' => 'Bahan Keluar berhasil dihapus']);
    }
}
