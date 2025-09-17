<?php

namespace App\Http\Livewire\Admin\Outputs;

use App\Models\Output;
use Livewire\Component;
use Illuminate\Support\Str;

class Show extends Component {


    protected $listeners=['openShow'];

    public $openShow=false, $output;

    public function mount(){
        $this->output = new Output();
    
    }

    public function render() {
        return view('livewire.admin.outputs.show');
    }

    public function openShow(Output $output){
        $this->output = $output;
        $this->output->price = '$ ' . number_format($this->output->price, 0, '.', ',');
        $this->openShow = true;
    }

    public function download(){
        return redirect()->route('admin.outputs.download', $this->output->id);
    }
}
