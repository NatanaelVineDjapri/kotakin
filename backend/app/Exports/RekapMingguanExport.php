<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class RekapMingguanExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(protected array $data) {}

    public function collection(): Collection
    {
        return collect($this->data['per_karyawan'])->map(function ($item, $index) {
            return [
                'no'         => $index + 1,
                'nama'       => $item['nama'],
                'nip'        => $item['nip'],
                'jabatan'    => $item['jabatan'],
                'total_hari' => $item['total_hari'],
                'hadir'      => $item['hadir'],
                'telat'      => $item['telat'],
                'izin'       => $item['izin'],
                'sakit'      => $item['sakit'],
                'alpha'      => $item['alpha'],
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
            'Total Hari Absen',
            'Hadir',
            'Telat',
            'Izin',
            'Sakit',
            'Alpha',
        ];
    }

    public function title(): string
    {
        return 'Rekap Mingguan ' . $this->data['tanggal_mulai'] . ' s.d. ' . $this->data['tanggal_akhir'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                  'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '7C3AED']]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 15,
            'D' => 20,
            'E' => 18,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 10,
            'J' => 10,
        ];
    }
}
