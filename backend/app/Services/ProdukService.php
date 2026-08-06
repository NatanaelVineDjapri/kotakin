<?php

namespace App\Services;

use App\Models\Produk;
use Illuminate\Support\Collection;

class ProdukService
{
    public function getAll(): Collection
    {
        return Produk::all();
    }

    public function create(array $data): Produk
    {
        return Produk::create($data);
    }

    public function update(Produk $produk, array $data): Produk
    {
        $produk->update($data);
        return $produk;
    }

    public function delete(Produk $produk): void
    {
        $produk->delete();
    }
}
