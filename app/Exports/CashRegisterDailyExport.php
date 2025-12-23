<?php

namespace App\Exports;

use App\Models\Sale;
use App\Models\Output;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnFormatting
};
use PhpOffice\PhpSpreadsheet\Style\{
    NumberFormat,
    Alignment
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashRegisterDailyExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnFormatting
{
    protected ?string $from;
    protected ?string $to;

    public function __construct(?string $from, ?string $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function collection(): Collection
    {
        /* =========================
         * INGRESOS POR MÉTODO DE PAGO
         * ========================= */
        $sales = Sale::query()
            ->join('payment_methods as pm', 'pm.id', '=', 'sales.payment_method_id')
            ->selectRaw('
                pm.name as concept,
                SUM(sales.total) as amount
            ')
            ->when($this->from && $this->to, fn($q) =>
                $q->whereBetween('sales.created_at', [$this->from, $this->to])
            )
            ->groupBy('pm.id', 'pm.name')
            ->orderBy('pm.name')
            ->get();

        $totalSales = $sales->sum('amount');

        /* =========================
         * EGRESOS (SIN MÉTODO)
         * ========================= */
        $totalOutputs = Output::query()
            ->when($this->from && $this->to, fn($q) =>
                $q->whereBetween('created_at', [$this->from, $this->to])
            )
            ->sum('price');

        /* =========================
         * ARMADO DEL REPORTE
         * ========================= */
        $report = collect();

        foreach ($sales as $row) {
            $report->push([
                'concept' => $row->concept,
                'amount'  => $row->amount,
            ]);
        }

        $report->push([
            'concept' => 'TOTAL INGRESOS',
            'amount'  => $totalSales,
        ]);

        $report->push([
            'concept' => 'EGRESOS',
            'amount'  => -$totalOutputs,
        ]);

        $report->push([
            'concept' => 'NETO EN CAJA',
            'amount'  => $totalSales - $totalOutputs,
        ]);

        return $report;
    }

    /* =========================
     * EXCEL
     * ========================= */
    public function headings(): array
    {
        return ['Concepto', 'Valor'];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            $lastRow - 2 => ['font' => ['bold' => true]], // TOTAL INGRESOS
            $lastRow => [
                'font' => ['bold' => true],
                'borders' => [
                    'top' => ['borderStyle' => 'thin'],
                ],
            ],
        ];
    }
}
