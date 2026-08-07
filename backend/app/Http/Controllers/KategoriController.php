<?php

namespace App\Http\Controllers;

use App\Http\Requests\Kategori\StoreKategoriRequest;
use App\Http\Requests\Kategori\UpdateKategoriRequest;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use App\Services\KategoriService;

class KategoriController extends Controller
{
    public function __construct(protected KategoriService $service) {}

    public function index()
    {
        return KategoriResource::collection($this->service->getAll());
    }

    public function store(StoreKategoriRequest $request)
    {
        $kategori = $this->service->create($request->validated());
        return new KategoriResource($kategori);
    }

    public function show(Kategori $kategori)
    {
        return new KategoriResource($kategori);
    }

    public function update(UpdateKategoriRequest $request, Kategori $kategori)
    {
        $kategori = $this->service->update($kategori, $request->validated());
        return new KategoriResource($kategori);
    }

    public function destroy(Kategori $kategori)
    {
        $this->service->delete($kategori);
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}
