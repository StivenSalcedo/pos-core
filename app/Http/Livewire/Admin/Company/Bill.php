<?php

namespace App\Http\Livewire\Admin\Company;

use App\Models\Company;
use Livewire\Component;

class Bill extends Component {

    public $company;

    protected $validationAttributes = [
        'width_ticket' => 'tamaño del ticket',
        'percentage_tip' => 'porcentaje de propina'
    ];

    public function mount(){
        $this->company = Company::first();
    }

    protected function rules(){
        return [
            'company.barcode' => 'required|integer|min:0|max:1',
            'company.type_bill' => 'required|integer|min:0|max:1',
            'company.width_ticket' => 'required|integer|min:70|max:110',
            'company.percentage_tip' => 'required|integer|min:0|max:20',
        ];
    }

    public function render() {
        return view('livewire.admin.company.bill');
    }

    public function update(){
        $this->validate();
        $this->company->save();
        session()->put('config', $this->company);

        return $this->emit('success', 'Configuración de factura actualizada con éxito');
    }
}
