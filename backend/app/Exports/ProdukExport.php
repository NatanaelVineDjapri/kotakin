<?php

namespace App\Exports;

use App\Models\Produk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdukExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(protected Collection $produk) {}

    public function collection(): Collection
    {
        return $this->produk->map(function (Produk $item, $index) {
            return [
                'no'            => $index + 1,
                'kode_produk'   => $item->kode_produk ?? '-',
                'nama_produk'   => $item->nama_produk,
                'kategori'      => $item->kategori->nama_kategori ?? '-',
                'harga_jual'    => $item->harga_jual,
                'stok'          => $item->stok,
                'status'        => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Produk',
            'Nama Produk',
            'Kategori',
            'Harga Jual',
            'Stok',
            'Status',
        ];
    }

    public function title(): string
    {
        return 'Data Produk';
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
            'B' => 18,
            'C' => 30,
            'D' => 20,
            'E' => 15,
            'F' => 10,
            'G' => 12,
        ];
    }
}
