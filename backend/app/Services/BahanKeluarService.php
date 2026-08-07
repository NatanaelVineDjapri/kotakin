<?php

namespace App\Services;

use App\Models\BahanKeluar;
use App\Models\BahanBaku;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BahanKeluarService
{
    public function getAll(): Collection
    {
        return BahanKeluar::all();
    }

    public function create(array $data): BahanKeluar
    {
        return DB::transaction(function () use ($data) {
            $bahanBaku = BahanBaku::findOrFail($data['bahan_baku_id']);

            if ($bahanBaku->stok_saat_ini < $data['jumlah']) {
                throw ValidationException::withMessages(['jumlah' => 'Stok bahan baku tidak mencukupi']);
            }

            $bahanKeluar = BahanKeluar::create($data);
            $bahanBaku->decrement('stok_saat_ini', $data['jumlah']);

            return $bahanKeluar;
        });
    }

    public function update(BahanKeluar $bahanKeluar, array $data): BahanKeluar
    {
        return DB::transaction(function () use ($bahanKeluar, $data) {
            $bahanBakuId = $data['bahan_baku_id'] ?? $bahanKeluar->bahan_baku_id;
            $newJumlah = $data['jumlah'] ?? $bahanKeluar->jumlah;

            $bahanBakuLama = BahanBaku::findOrFail($bahanKeluar->bahan_baku_id);
            $bahanBakuLama->increment('stok_saat_ini', $bahanKeluar->jumlah);

            $bahanBakuBaru = BahanBaku::findOrFail($bahanBakuId);
            if ($bahanBakuBaru->stok_saat_ini < $newJumlah) {
                throw ValidationException::withMessages(['jumlah' => 'Stok bahan baku tidak mencukupi']);
            }

            $bahanKeluar->update($data);

            $bahanBakuBaru->decrement('stok_saat_ini', $newJumlah);

            return $bahanKeluar;
        });
    }

    public function delete(BahanKeluar $bahanKeluar): void
    {
        DB::transaction(function () use ($bahanKeluar) {
            $bahanBaku = BahanBaku::findOrFail($bahanKeluar->bahan_baku_id);
            $bahanBaku->increment('stok_saat_ini', $bahanKeluar->jumlah);

            $bahanKeluar->delete();
        });
    }
}
