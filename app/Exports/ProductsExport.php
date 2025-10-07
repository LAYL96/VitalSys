<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsExport implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    /**
     * Retorna todos los productos
     */
    public function collection()
    {
        return Product::select(
            'id',
            'name',
            'description',
            'price',
            'stock',
            'min_stock',
            'expiration_date'
        )->get();
    }

    /**
     * Encabezados de las columnas en el Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripción',
            'Precio (Q)',
            'Stock',
            'Stock mínimo',
            'Fecha de vencimiento'
        ];
    }

    /**
     * Mapeo de cada fila antes de exportar
     */
    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->description,
            number_format($product->price, 2), // precio con 2 decimales
            $product->stock,
            $product->min_stock,
            $product->expiration_date
                ? \Carbon\Carbon::parse($product->expiration_date)->format('d/m/Y')
                : 'N/A',
        ];
    }

    /**
     * Aplica filtros automáticos a la fila de encabezados
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:G1'; // Encabezados
                $event->sheet->getDelegate()->setAutoFilter($cellRange);

                // Estilo para encabezados
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Ajustar automáticamente el ancho de las columnas
                foreach (range('A', 'G') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
