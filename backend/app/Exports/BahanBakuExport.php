<?php

namespace App\Exports;

use App\Models\BahanBaku;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BahanBakuExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(protected Collection $bahanBaku) {}

    public function collection(): Collection
    {
        return $this->bahanBaku->map(function (BahanBaku $item, $index) {
            return [
                'no'             => $index + 1,
                'nama_bahan'     => $item->nama_bahan,
                'satuan'         => $item->satuan,
                'supplier'       => $item->supplier->nama_supplier ?? '-',
                'stok_saat_ini'  => $item->stok_saat_ini,
                'stok_minimum'   => $item->stok_minimum ?? '-',
                'status_stok'    => ($item->stok_minimum !== null && $item->stok_saat_ini <= $item->stok_minimum)
                    ? 'Menipis'
                    : 'Aman',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Bahan',
            'Satuan',
            'Supplier',
            'Stok Saat Ini',
            'Stok Minimum',
            'Status Stok',
        ];
    }

    public function title(): string
    {
        return 'Data Bahan Baku';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                  'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3B82F6']]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 12,
            'D' => 25,
            'E' => 15,
            'F' => 15,
            'G' => 14,
        ];
    }
}  
