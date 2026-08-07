<?php

namespace App\Services;

use App\Models\BahanBaku;
use Illuminate\Support\Collection;

class BahanBakuService
{
    public function getAll(): Collection
    {
        return BahanBaku::all();
    }

    public function create(array $data): BahanBaku
    {
        return BahanBaku::create($data);
    }

    public function update(BahanBaku $bahanBaku, array $data): BahanBaku
    {
        $bahanBaku->update($data);
        return $bahanBaku;
    }

    public function delete(BahanBaku $bahanBaku): void
    {
        $bahanBaku->delete();
    }
}
