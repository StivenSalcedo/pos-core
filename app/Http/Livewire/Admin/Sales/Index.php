<?php

namespace App\Http\Livewire\Admin\Sales;

use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use App\Exports\PaymentMethodSummaryExport;
use App\Exports\EmployeeSummaryExport;
class Index extends Component
{

    use WithPagination;

    public $search, $filterDate = '1', $startDate, $endDate, $productsArray, $productsSelected = [], $useBarcode;

    public $total = 0;
    public $orderUnits = null; // asc | desc | null
    public function mount()
    {

        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');

        $this->useBarcode = intval(Company::first()->barcode);

        $this->productsArray = Product::select(['id', 'reference', 'name'])
            ->where('status', '0')
            ->get()
            ->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'reference' => $item->reference,
                    'name' => $item->name,
                ];
            })
            ->toArray();
    }

    public function render()
    {
        [$from, $to] = $this->resolveDates();

        /*
    |--------------------------------------------------------------------------
    | 1. BASE QUERY (sales)
    |--------------------------------------------------------------------------
    */
        $baseReportQuery = Sale::query()
            ->join('products', 'products.id', '=', 'sales.product_id')
            ->selectRaw('
            sales.product_id,
            products.reference,
            products.name,
            SUM(sales.quantity) as quantity,
            SUM(sales.units) as units,
            SUM(sales.total) as total
        ')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('sales.created_at', [$from, $to]);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('products.name', 'LIKE', "%{$this->search}%")
                        ->orWhere('products.reference', 'LIKE', "%{$this->search}%");
                });
            })
            ->when($this->productsSelected, function ($q) {
                $q->whereIn('sales.product_id', $this->normalizeProductIds());
            })
            ->groupBy(
                'sales.product_id',
                'products.reference',
                'products.name'
            );

        /*
    |--------------------------------------------------------------------------
    | 2. PAGINADO
    |--------------------------------------------------------------------------
    */
        $products = (clone $baseReportQuery)
            ->when($this->orderUnits, function ($q) {
                $q->orderBy('quantity', $this->orderUnits);
            })
            ->paginate(10);

        /*
    |--------------------------------------------------------------------------
    | 3. TOTAL GENERAL
    |--------------------------------------------------------------------------
    */
        $this->total = Sale::query()
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('product', function ($q) {
                    $q->where('name', 'LIKE', "%{$this->search}%")
                        ->orWhere('reference', 'LIKE', "%{$this->search}%");
                });
            })
            ->when($this->productsSelected, function ($q) {
                $q->whereIn('product_id', $this->normalizeProductIds());
            })
            ->sum('total');

        return view('livewire.admin.sales.index', compact('products'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function updatedProductsSelected()
    {
        $this->resetPage();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function refreshData()
    {


        Artisan::call('sales:update', [
            '--filter' => $this->filterDate,
            '--from'   => $this->startDate,
            '--to'     => $this->endDate,
        ]);

        $this->resetPage();
    }

    private function resolveDates()
    {
        switch ((int) $this->filterDate) {
            case 1: // Hoy
                return [now()->startOfDay(), now()->endOfDay()];

            case 2: // Esta semana
                return [now()->startOfWeek(), now()->endOfWeek()];

            case 3: // Últimos 7 días
                return [now()->subDays(6)->startOfDay(), now()->endOfDay()];

            case 4: // Semana pasada
                return [
                    now()->subWeek()->startOfWeek(),
                    now()->subWeek()->endOfWeek()
                ];

            case 5: // Hace 15 días
                return [now()->subDays(14)->startOfDay(), now()->endOfDay()];

            case 6: // Este mes
                return [now()->startOfMonth(), now()->endOfMonth()];

            case 7: // Mes pasado
                return [
                    now()->subMonth()->startOfMonth(),
                    now()->subMonth()->endOfMonth()
                ];

            case 8: // Rango manual
                return [
                    \Carbon\Carbon::parse($this->startDate)->startOfDay(),
                    \Carbon\Carbon::parse($this->endDate)->endOfDay()
                ];

            default: // 0 => Todos
                return [null, null];
        }
    }

    private function normalizeProductIds()
    {
        return collect($this->productsSelected)
            ->flatten()
            ->filter()
            ->values()
            ->toArray();
    }

    public function toggleOrderUnits()
    {
        $this->orderUnits = match ($this->orderUnits) {
            'asc'  => 'desc',
            'desc' => null,
            default => 'asc',
        };

        $this->resetPage();
    }

    public function exportExcel()
    {
        [$from, $to] = $this->resolveDates();
        $filename = 'reporte_productos';
         if($from && $to)
         {
            $filename.='_' . $from . '_a_' . $to . '.xlsx';
         }
         else
         {
            $filename.= '.xlsx';
         }
        return Excel::download(
            new SalesReportExport(
                $this->search,
                $this->normalizeProductIds(),
                $from,
                $to,
                $this->orderUnits
            ),
            $filename
        );
    }



    public function exportExcelPaymentMethodSummary()
    {
         [$from, $to] = $this->resolveDates();
         $filename = 'resumen_metodos_pago';
         if($from && $to)
         {
            $filename.='_' . $from . '_a_' . $to . '.xlsx';
         }
         else
         {
            $filename.= '.xlsx';
         }
       
        return Excel::download(
            new PaymentMethodSummaryExport(
                $from,
                $to
            ),
            $filename
        );
    }

    public function exportExcelEmployeeSummarySummary()
    {
         [$from, $to] = $this->resolveDates();
         $filename = 'resumen_empleado';
         if($from && $to)
         {
            $filename.='_' . $from . '_a_' . $to . '.xlsx';
         }
         else
         {
            $filename.= '.xlsx';
         }
       
        return Excel::download(
            new EmployeeSummaryExport(
                $from,
                $to
            ),
            $filename
        );
    }
}
