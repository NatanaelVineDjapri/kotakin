<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk rekap harian absensi.
 * $this->resource = array hasil dari AbsensiService::getRekapHarian()
 */
class RekapHarianResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'tanggal'   => $this->resource['tanggal'],
            'ringkasan' => $this->resource['ringkasan'],
            'detail'    => AbsensiResource::collection($this->resource['detail']),
        ];
    }
}
