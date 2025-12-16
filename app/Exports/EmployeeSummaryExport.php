<?php

namespace App\Exports;

use App\Models\Sale;
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

class EmployeeSummaryExport implements
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
        $query = Sale::query()
            ->join('users as u', 'u.id', '=', 'sales.user_id')
            ->selectRaw('
                u.name as employee,
                SUM(CASE WHEN sales.source = "bill" THEN sales.total ELSE 0 END) as total_bills,
                SUM(CASE WHEN sales.source = "service" THEN sales.total ELSE 0 END) as total_services,
                SUM(sales.total) as total_general
            ')
            ->whereNotNull('sales.user_id')
            ->groupBy('u.name')
            ->orderBy('u.name');

        /* =========================
         * FECHAS (YA RESUELTAS)
         * ========================= */
        if ($this->from && $this->to) {
            $query->whereBetween('sales.created_at', [$this->from, $this->to]);
        }

        $report = $query->get();

        /* =========================
         * FILA TOTAL
         * ========================= */
        $report->push([
            'employee'       => 'TOTAL',
            'total_bills'    => $report->sum('total_bills'),
            'total_services' => $report->sum('total_services'),
            'total_general'  => $report->sum('total_general'),
        ]);

        return $report;
    }

    /* =========================
     * EXCEL
     * ========================= */
    public function headings(): array
    {
        return [
            'Empleado',
            'Total facturas',
            'Total servicios',
            'Total general',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'C' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'D' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            $lastRow => [
                'font' => ['bold' => true],
                'borders' => [
                    'top' => ['borderStyle' => 'thin'],
                ],
            ],
        ];
    }
}
