<?php

namespace App\Http\Controllers;

use App\Http\Requests\BahanMasuk\StoreBahanMasukRequest;
use App\Http\Requests\BahanMasuk\UpdateBahanMasukRequest;
use App\Http\Resources\BahanMasukResource;
use App\Models\BahanMasuk;
use App\Services\BahanMasukService;

class BahanMasukController extends Controller
{
    public function __construct(protected BahanMasukService $service) {}

    public function index()
    {
        return BahanMasukResource::collection($this->service->getAll());
    }

    public function store(StoreBahanMasukRequest $request)
    {
        $bahanMasuk = $this->service->create($request->validated());
        return new BahanMasukResource($bahanMasuk);
    }

    public function show(BahanMasuk $bahanMasuk)
    {
        return new BahanMasukResource($bahanMasuk);
    }

    public function update(UpdateBahanMasukRequest $request, BahanMasuk $bahanMasuk)
    {
        $bahanMasuk = $this->service->update($bahanMasuk, $request->validated());
        return new BahanMasukResource($bahanMasuk);
    }

    public function destroy(BahanMasuk $bahanMasuk)
    {
        $this->service->delete($bahanMasuk);
        return response()->json(['message' => 'Bahan Masuk berhasil dihapus']);
    }
}
