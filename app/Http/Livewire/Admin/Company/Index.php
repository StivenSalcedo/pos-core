<?php

namespace App\Http\Livewire\Admin\Company;

use Livewire\Component;

class Index extends Component {

    public function render(){
        return view('livewire.admin.company.index')->layoutData(['title' => 'Configuración']);
    }
}
