<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk rekap bulanan absensi.
 * $this->resource = array hasil dari AbsensiService::getRekapBulanan()
 */
class RekapBulananResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'periode'      => $this->resource['periode'],
            'ringkasan'    => $this->resource['ringkasan'],
            'per_karyawan' => $this->resource['per_karyawan'],
        ];
    }
}
