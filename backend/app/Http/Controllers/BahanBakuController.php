<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBahanBakuRequest;
use App\Http\Requests\UpdateBahanBakuRequest;
use App\Http\Resources\BahanBakuResource;
use App\Models\BahanBaku;
use App\Services\BahanBakuService;

class BahanBakuController extends Controller
{
    public function __construct(protected BahanBakuService $service) {}

    public function index()
    {
        return BahanBakuResource::collection($this->service->getAll());
    }

    public function store(StoreBahanBakuRequest $request)
    {
        $bahanBaku = $this->service->create($request->validated());
        return new BahanBakuResource($bahanBaku);
    }

    public function show(BahanBaku $bahanBaku)
    {
        return new BahanBakuResource($bahanBaku);
    }

    public function update(UpdateBahanBakuRequest $request, BahanBaku $bahanBaku)
    {
        $bahanBaku = $this->service->update($bahanBaku, $request->validated());
        return new BahanBakuResource($bahanBaku);
    }

    public function destroy(BahanBaku $bahanBaku)
    {
        $this->service->delete($bahanBaku);
        return response()->json(['message' => 'Bahan Baku berhasil dihapus']);
    }

    public function stokMenipis()
    {
        return BahanBakuResource::collection($this->service->getStokMenipis());
    }
}
