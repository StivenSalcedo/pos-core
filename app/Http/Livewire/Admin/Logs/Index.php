<?php

namespace App\Http\Livewire\Admin\Logs;

use App\Models\Log;
use Illuminate\Http\Request;
use Livewire\Component;

class Index extends Component {

    public $openShow=false;

    public $log;

    public function mount(Request $request){
        $this->log = new Log();        
    }

    public function render() {

        $logs = Log::latest()->get();

        return view('livewire.admin.logs.index', compact('logs'));
    }

    public function openShow(Log $log){
        $this->log = $log;
        $this->openShow = true;
    }

    public function destroy(Log $log){
        $this->log = new Log();
        $log->delete();
        $this->emit('success', 'Log eliminado con éxito');
    }
}
