<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Http\Resources\ProdukResource;
use App\Models\Produk;
use App\Services\ProdukService;

class ProdukController extends Controller
{
    public function __construct(protected ProdukService $service) {}

    public function index()
    {
        return ProdukResource::collection($this->service->getAll());
    }

    public function store(StoreProdukRequest $request)
    {
        $produk = $this->service->create($request->validated());
        return new ProdukResource($produk);
    }

    public function show(Produk $produk)
    {
        return new ProdukResource($produk);
    }

    public function update(UpdateProdukRequest $request, Produk $produk)
    {
        $produk = $this->service->update($produk, $request->validated());
        return new ProdukResource($produk);
    }

    public function destroy(Produk $produk)
    {
        $this->service->delete($produk);
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
