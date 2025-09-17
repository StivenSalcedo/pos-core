<?php

namespace App\Http\Livewire\Admin\Products;

use App\Imports\ProductsImport;
use App\Models\Bill;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{

    use WithFileUploads;

    protected $listeners = ['openImport'];

    public $openImport = false;

    public $preFile, $file;

    public function render()
    {
        return view('livewire.admin.products.import');
    }

    public function updatedPreFile($value)
    {
        $this->validate(['preFile' =>  'mimes:xlsx,xlsm|max:30720']);
        $this->file = $value;
    }

    public function openImport()
    {
        if (!Bill::count() && !Purchase::count()) {
            $this->openImport = true;
        }
    }

    public function downloadExample()
    {

        return Storage::download('public/xlsx-templates/plantilla-productos.xlsx');
    }

    public function loadProducts()
    {
        $this->deleteProducts();

        $url = $this->file->storeAs('files', "products.{$this->file->extension()}");

        Excel::import(new ProductsImport, $url);
        $this->emitTo('admin.products.index', 'render');
        $this->emit('success', 'Productos exportados con éxito');
        $this->reset();
    }

    protected function deleteProducts()
    {
        DB::table('presentations')->delete();
        DB::table('product_tax_rate')->delete();
        DB::table('products')->delete();
    }
}
