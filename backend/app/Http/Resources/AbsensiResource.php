<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsensiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'tanggal'          => $this->tanggal?->format('Y-m-d'),
            'status'           => $this->status,
            'keterangan'       => $this->keterangan,
            'waktu_masuk'      => $this->waktu_masuk?->format('H:i:s'),
            'waktu_pulang'     => $this->waktu_pulang?->format('H:i:s'),
            'lokasi_masuk'     => $this->latitude_masuk !== null ? [
                'lat'  => (float) $this->latitude_masuk,
                'long' => (float) $this->longitude_masuk,
            ] : null,
            'lokasi_pulang'    => $this->latitude_pulang !== null ? [
                'lat'  => (float) $this->latitude_pulang,
                'long' => (float) $this->longitude_pulang,
            ] : null,
            'foto_masuk'       => $this->foto_masuk,
            'foto_pulang'      => $this->foto_pulang,
            'karyawan'         => $this->whenLoaded('karyawan', fn () => [
                'id'      => $this->karyawan->id,
                'nama'    => $this->karyawan->user?->name,
                'nip'     => $this->karyawan->nip,
                'jabatan' => $this->karyawan->jabatan,
            ]),
            'jadwal'           => $this->whenLoaded('jadwal', fn () => $this->jadwal ? [
                'jam_masuk'  => $this->jadwal->jam_masuk,
                'jam_pulang' => $this->jadwal->jam_pulang,
                'shift'      => $this->jadwal->shift,
            ] : null),
        ];
    }
}
