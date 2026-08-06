<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk rekap mingguan per karyawan.
 * $this->resource = array hasil dari AbsensiService::getRekapMingguan()
 */
class RekapMingguanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'periode' => [
                'tanggal_mulai' => $this->resource['tanggal_mulai'],
                'tanggal_akhir' => $this->resource['tanggal_akhir'],
            ],
            'ringkasan' => $this->resource['ringkasan'],
            'per_karyawan' => $this->resource['per_karyawan'],
        ];
    }
}
