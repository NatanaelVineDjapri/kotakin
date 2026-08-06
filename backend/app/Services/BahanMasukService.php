<?php

namespace App\Services;

use App\Models\BahanMasuk;
use App\Models\BahanBaku;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BahanMasukService
{
    public function getAll(): Collection
    {
        return BahanMasuk::all();
    }

    public function create(array $data): BahanMasuk
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['harga_total'])) {
                $data['harga_total'] = $data['jumlah'] * $data['harga_satuan'];
            }

            $bahanMasuk = BahanMasuk::create($data);

            $bahanBaku = BahanBaku::findOrFail($data['bahan_baku_id']);
            $bahanBaku->increment('stok_saat_ini', $data['jumlah']);

            return $bahanMasuk;
        });
    }

    public function update(BahanMasuk $bahanMasuk, array $data): BahanMasuk
    {
        return DB::transaction(function () use ($bahanMasuk, $data) {
            if (!isset($data['harga_total']) && isset($data['jumlah']) && isset($data['harga_satuan'])) {
                $data['harga_total'] = $data['jumlah'] * $data['harga_satuan'];
            }

            $bahanBakuId = $data['bahan_baku_id'] ?? $bahanMasuk->bahan_baku_id;
            $newJumlah = $data['jumlah'] ?? $bahanMasuk->jumlah;

            $bahanBakuLama = BahanBaku::findOrFail($bahanMasuk->bahan_baku_id);
            $bahanBakuLama->decrement('stok_saat_ini', $bahanMasuk->jumlah);

            $bahanMasuk->update($data);

            $bahanBakuBaru = BahanBaku::findOrFail($bahanBakuId);
            $bahanBakuBaru->increment('stok_saat_ini', $newJumlah);

            return $bahanMasuk;
        });
    }

    public function delete(BahanMasuk $bahanMasuk): void
    {
        DB::transaction(function () use ($bahanMasuk) {
            $bahanBaku = BahanBaku::findOrFail($bahanMasuk->bahan_baku_id);
            $bahanBaku->decrement('stok_saat_ini', $bahanMasuk->jumlah);

            $bahanMasuk->delete();
        });
    }
}
