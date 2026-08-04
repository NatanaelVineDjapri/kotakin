<?php

namespace App\Services;

use App\Models\Kategori;
use Illuminate\Support\Collection;

class KategoriService
{
    public function getAll(): Collection
    {
        return Kategori::all();
    }

    public function create(array $data): Kategori
    {
        return Kategori::create($data);
    }

    public function update(Kategori $kategori, array $data): Kategori
    {
        $kategori->update($data);
        return $kategori;
    }

    public function delete(Kategori $kategori): void
    {
        $kategori->delete();
    }
}
