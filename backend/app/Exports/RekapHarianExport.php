<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class RekapHarianExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(protected array $data) {}

    public function collection(): Collection
    {
        return $this->data['detail']->map(function ($absensi, $index) {
            return [
                'no'           => $index + 1,
                'nama'         => $absensi->karyawan?->user?->name ?? '-',
                'nip'          => $absensi->karyawan?->nip ?? '-',
                'jabatan'      => $absensi->karyawan?->jabatan ?? '-',
                'status'       => strtoupper($absensi->status),
                'waktu_masuk'  => $absensi->waktu_masuk?->format('H:i:s') ?? '-',
                'waktu_pulang' => $absensi->waktu_pulang?->format('H:i:s') ?? '-',
                'lat_masuk'    => $absensi->latitude_masuk ?? '-',
                'long_masuk'   => $absensi->longitude_masuk ?? '-',
                'lat_pulang'   => $absensi->latitude_pulang ?? '-',
                'long_pulang'  => $absensi->longitude_pulang ?? '-',
                'keterangan'   => $absensi->keterangan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Karyawan',
            'NIP',
            'Jabatan',
            'Status',
            'Waktu Masuk',
            'Waktu Pulang',
            'Latitude Masuk',
            'Longitude Masuk',
            'Latitude Pulang',
            'Longitude Pulang',
            'Keterangan',
        ];
    }

    public function title(): string
    {
        return 'Rekap Harian ' . $this->data['tanggal'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                  'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2563EB']]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 15,
            'D' => 20,
            'E' => 10,
            'F' => 14,
            'G' => 14,
            'H' => 16,
            'I' => 16,
            'J' => 16,
            'K' => 16,
            'L' => 30,
        ];
    }
}
