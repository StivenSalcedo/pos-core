<?php

namespace App\Http\Livewire\Admin\Products;

use Livewire\Component;

class Presentations extends Component {

    protected $listeners = ['openPresentations'];

    public $openPresentations=false, $componentName;

    public $edit=false, $keyPresentation;

    public $name, $quantity, $price;

    public function render() {
        return view('livewire.admin.products.presentations');
    }

    public function openPresentations($componentName, $item=null, $key=null){
        $this->componentName = $componentName;
        $this->resetValidation();
        
        $this->keyPresentation = $key;

        if ($item) {
            $this->name = $item['name'];
            $this->quantity = $item['quantity'];
            $this->price = $item['price'];
            $this->edit = true;
        }else{
            $this->edit = false;
        }

        $this->openPresentations=true;
    }

    public function addPresentation(){

        $rules = [
            'name' => 'required|string|max:250',
            'quantity' => 'required|integer|min:1|max:99999999',
            'price' => 'required|integer|min:1|max:99999999',
        ];

        $attributes = [
            'name' => 'nombre',
            'quantity' => 'cantidad',
            'price' => 'precio',
        ];

        $array = $this->validate($rules, null, $attributes);

        $this->emitTo($this->componentName, 'setPresentation',  $array, $this->keyPresentation);

        $this->reset();
        $this->resetValidation();
    }

}
