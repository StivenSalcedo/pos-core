<?php

namespace App\Http\Livewire\Admin\Customers;

use App\Imports\CustomersImport;
use App\Models\Bill;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{
    use WithFileUploads;

    protected $listeners = ['openImport'];

    public $openImport=false;

    public $preFile, $file;

    public function render()
    {
        return view('livewire.admin.customers.import');
    }

    public function updatedPreFile($value){
        $this->validate(['preFile' =>  'mimes:xlsx,xlsm|max:30720']);
        $this->file = $value;
    }

    public function openImport(){
        if (!Bill::count()) {
            $this->openImport = true;
        }
    }

    public function downloadExample(){
        return Storage::download('public/xlsx-templates/plantilla-clientes.xlsx');
    }

    public function loadProducts(){

        $products = Customer::all()->pluck('id');
        Customer::destroy($products);

        $url = $this->file->storeAs('files', "customers.{$this->file->extension()}");

        Customer::create([
            'no_identification' => '222222222222',
            'names' => 'Consumidor final',
            'top' => '0',
        ]);

        Excel::import(new CustomersImport, $url);
        $this->emitTo('admin.customers.index', 'render');
        $this->emit('success', 'Clientes exportados con éxito');
        $this->reset();

    }
}
