<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithEvents
{
    protected $search;
    protected $productIds;
    protected $from;
    protected $to;
    protected $orderUnits;

    public function __construct($search, $productIds, $from, $to, $orderUnits)
    {
        $this->search     = $search;
        $this->productIds = $productIds;
        $this->from       = $from;
        $this->to         = $to;
        $this->orderUnits = $orderUnits;
    }

    public function collection()
    {
        return Sale::query()
            ->join('products', 'products.id', '=', 'sales.product_id')
            ->selectRaw('
                products.reference,
                sales.source,
                products.name,
                SUM(sales.quantity) as quantity,
                SUM(sales.units) as units,
                SUM(sales.total) as total
            ')
            ->when($this->from && $this->to, function ($q) {
                $q->whereBetween('sales.created_at', [$this->from, $this->to]);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('products.name', 'LIKE', "%{$this->search}%")
                      ->orWhere('products.reference', 'LIKE', "%{$this->search}%");
                });
            })
            ->when($this->productIds, function ($q) {
                $q->whereIn('sales.product_id', $this->productIds);
            })
            ->groupBy(
                'products.reference',
                'products.name',
                'sales.source'
            )
            ->when($this->orderUnits, function ($q) {
                $q->orderBy('quantity', $this->orderUnits);
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'Referencia',
            'Origen',
            'Producto',
            'Cantidad',
            'Total'
        ];
    }

    public function map($row): array
    {
        return [
            $row->reference,
            ucfirst($row->source), // bill | service
            $row->name,
            $row->quantity,
            $row->total,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'E' => [
                'numberFormat' => [
                    'formatCode' => '"$"#,##0'
                ]
            ],
            1 => [
                'font' => ['bold' => true]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow() + 1;

                $sheet->setCellValue("C{$lastRow}", 'TOTAL');
                $sheet->setCellValue("D{$lastRow}", "=SUM(D2:D" . ($lastRow - 1) . ")");
                $sheet->setCellValue("E{$lastRow}", "=SUM(E2:E" . ($lastRow - 1) . ")");

                $sheet->getStyle("C{$lastRow}:E{$lastRow}")
                    ->getFont()->setBold(true);
            }
        ];
    }
}
