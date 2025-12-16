<?php

namespace App\Http\Livewire\Admin\Sales;

use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Index extends Component
{

    use WithPagination;

    public $search, $filterDate = '8', $startDate, $endDate, $productsArray, $productsSelected = [], $useBarcode;

    public $total = 0;

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

        /* $products = Sale::with('product')
            ->whereHas('product', function ($query) {
                $query
                    ->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('reference', 'LIKE', "%{$this->search}%");
            })
            ->selectRaw('MAX(product_id) AS product_id, SUM(quantity) AS quantity, SUM(units) AS units, SUM(total) AS total')
            ->date($this->filterDate, $this->startDate, $this->endDate)
            ->searchByIds($this->productsSelected)
            ->groupBy('product_id')
            ->paginate(10);

        $this->total = Sale::date($this->filterDate, $this->startDate, $this->endDate)
            ->whereHas('product', function ($query) {
                $query
                    ->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('reference', 'LIKE', "%{$this->search}%");
                })
            ->sum('total');*/



        /*
    |--------------------------------------------------------------------------
    | 1. Servicios PAGADOS y filtrados por fecha (services)
    |--------------------------------------------------------------------------
    */
        $paidServices = DB::table('services')
            ->select('services.id')
            ->whereBetween('services.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ])
            ->whereRaw('
            (
                SELECT COALESCE(SUM(sp.total),0)
                FROM service_products sp
                WHERE sp.service_id = services.id
            ) = (
                SELECT COALESCE(SUM(pay.amount),0)
                FROM service_payments pay
                WHERE pay.service_id = services.id
            )
        ');

        /*
    |--------------------------------------------------------------------------
    | 2. Ventas normales (sales)
    |--------------------------------------------------------------------------
    */
        $salesQuery = Sale::query()
            ->selectRaw('
            product_id,
            SUM(quantity) as quantity,
            SUM(units) as units,
            SUM(total) as total
        ')
            ->whereHas('product', function ($query) {
                $query->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('reference', 'LIKE', "%{$this->search}%");
            })
            ->date($this->filterDate, $this->startDate, $this->endDate)
            ->searchByIds($this->productsSelected)
            ->groupBy('product_id');

        /*
    |--------------------------------------------------------------------------
    | 3. Productos de SERVICIOS PAGADOS
    |--------------------------------------------------------------------------
    */
        $serviceQuery = DB::table('service_products')
            ->selectRaw('
            product_id,
            SUM(quantity) as quantity,
            0 as units,
            SUM(total) as total
        ')
            ->whereIn('service_id', $paidServices)
            ->when($this->productsSelected, function ($q) {
                $q->whereIn('product_id', $this->productsSelected);
            })
            ->groupBy('product_id');

        /*
    |--------------------------------------------------------------------------
    | 4. UNION FINAL
    |--------------------------------------------------------------------------
    */
       $products = DB::query()
    ->fromSub(
        $salesQuery->unionAll($serviceQuery),
        'report'
    )
    ->join('products', 'products.id', '=', 'report.product_id')
    ->selectRaw('
        report.product_id,
        products.reference,
        products.name,
        SUM(report.quantity) as quantity,
        SUM(report.units) as units,
        SUM(report.total) as total
    ')
    ->groupBy(
        'report.product_id',
        'products.reference',
        'products.name'
    )
    ->paginate(10);

        /*
    |--------------------------------------------------------------------------
    | 5. TOTAL GENERAL
    |--------------------------------------------------------------------------
    */
        $this->total = DB::query()
            ->fromSub(
                $salesQuery->unionAll($serviceQuery),
                'totals'
            )
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

    public function getToday()
    {
        Artisan::call('sales:update --today');
    }
}
