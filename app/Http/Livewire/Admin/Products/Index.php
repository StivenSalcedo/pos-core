<?php

namespace App\Http\Livewire\Admin\Products;

use App\Exports\ProductsExport;
use App\Http\Controllers\Log;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Terminal;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{

    use WithPagination;

    //TODO Actualizar la funcion de importa productos deste excel

    protected $listeners = ['render'];

    public $search, $filter = '1';

    public $totalCost;

    public $terminals;

    public $status = [
        1 => 'Sin Stock',
        2 => 'Activado',
        3 => 'Desactivado'

    ];

    public $state_id = '';

    public $terminal_id = '';

    public $filters = [
        1 => 'Referencia',
        2 => 'Nombre'
    ];



    public function mount()
    {
        $this->terminals = Terminal::all()->pluck('name', 'id');
        $this->getTotalCost();
    }

    public function render()
    {


        $products = $this->queryFilteredProducts()
            ->latest()
            ->paginate(50);

        return view('livewire.admin.products.index', compact('products'))->layoutData(['title' => 'Productos']);
    }

    public function queryFilteredProducts()
    {
        $filter = [1 => 'reference',  2 => 'name'][$this->filter];

        return Product::with('taxRates', 'taxRates.tribute', 'terminal', 'provider', 'brand')
            ->where($filter, 'LIKE', '%' . $this->search . '%')
            ->terminal($this->terminal_id)
            ->when(
                $this->state_id == 1,
                fn($q) =>
                $q->where('stock', 0)->where('has_inventory', '0')
            )
            ->when(
                $this->state_id == 2,
                fn($q) =>
                $q->where('status', '0')
            )
            ->when(
                $this->state_id == 3,
                fn($q) =>
                $q->where('status', '1')
            )
            ->filterBarcode($filter, $this->search);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function getTotalCost()
    {

        $products = Product::where('status', Product::ACTIVE)
            ->select('id', 'name', 'cost', 'stock')
            ->get();

        $total = 0;

        foreach ($products as $item) {
            $total = $total + ($item->cost * $item->stock);
        }

        $this->totalCost = $total;
    }


    public function exportProducts()
    {
        try {
            $products = $this->queryFilteredProducts()
                ->latest()
                ->get();

            return Excel::download(new ProductsExport($products), 'Productos.xlsx');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->emit('error', 'Ocurrio un error al exportar los productos.');
        }
    }
}
