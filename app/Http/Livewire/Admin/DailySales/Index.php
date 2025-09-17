<?php

namespace App\Http\Livewire\Admin\DailySales;

use App\Exports\DailySalesExport;
use App\Http\Controllers\Admin\DailySaleController;
use App\Models\DailySale;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    public $search, $filterDate = '0', $startDate, $endDate;

    protected $query;

    public function render()
    {
        $this->hydrate();
        $subtotal = $this->query->sum('subtotal_amount');
        $discount = $this->query->sum('discount_amount');
        $iva = $this->query->sum('iva_amount');
        $inc = $this->query->sum('inc_amount');
        $total = $this->query->sum('total_amount');

        $sales = $this->query->paginate(10);

        return view('livewire.admin.daily-sales.index', compact('sales', 'subtotal', 'discount', 'iva', 'inc', 'total'))->layoutData(['title' => 'Reporte de ventas diarias']);
    }

    public function hydrate()
    {
        $this->query = DailySale::query()
            ->latest('creation_date')
            ->date($this->filterDate, $this->startDate, $this->endDate);
    }

    public function showPdf(DailySale $dailySale)
    {
        $objDailySale = new DailySaleController;
    }

    public function updatedFilterDate()
    {
        $this->resetPage();
        $this->reset('startDate', 'endDate');
    }

    public function exportSales()
    {
        return Excel::download(new DailySalesExport($this->query), 'Reporte de ventas diarias.xlsx');
    }

    public function downloadPDF()
    {
        $query = "?filterDate=$this->filterDate&startDate=$this->startDate&endDate=$this->endDate";
        return route('admin.daily-sales.pdf') . $query;
    }
}
