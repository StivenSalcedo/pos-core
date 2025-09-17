<?php

namespace App\Http\Livewire\Admin\Company;

use App\Models\Company;
use Livewire\Component;

class QuickSales extends Component
{

    public $company;

    protected $validationAttributes = [
        'print' => 'impresión de factura',
        'change' => 'impresión de factura',
        'tables' => 'usar mesas',
    ];

    public function mount()
    {
        $this->company = Company::first();
    }

    protected function rules()
    {
        return [
            'company.print' => 'required|integer|min:0|max:1',
            'company.change' => 'required|integer|min:0|max:1',
            'company.tables' => 'required|integer|min:0|max:1',
        ];
    }

    public function render()
    {
        return view('livewire.admin.company.quick-sales');
    }

    public function update()
    {
        $this->validate();

        $this->company->save();

        session()->put('config', $this->company);

        $this->emit('success', 'Configuración de las ventas rápidas actualizada con éxito');
    }
}
