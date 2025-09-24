<?php

namespace App\Exports;

use App\Models\Output;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OutputsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{

    use Exportable;

    public $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'N° pago',
            'Fecha',
            'Sede',
            'Responsable',
            'Caja',
            'Motivo',
            'Valor',
            'Descripcion',
        ];
    }

    public function map($output): array
    {

        return [
            [
                $output->id,
                $output->date->format('d-m-Y'),
                $output->terminal->name,
                $output->user->name,
                $output->from->getLabel(),
                $output->reason,
                $output->price,
                $output->description,
            ],
        ];
    }
}
