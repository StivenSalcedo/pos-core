<?php

namespace App\Http\Livewire\Admin\Outputs;

use Livewire\Component;
use App\Models\Output;
use App\Traits\LivewireTrait;

class Audit extends Component
{
     use LivewireTrait;
    public ?Output $output = null;
    public bool $open = false;


    protected $listeners = ['open'];

    public function open(int $outputId)
    {
        $this->output = Output::with('audits.user')->findOrFail($outputId);
        $this->open = true;
    }
    public function render()
    {
        return view('livewire.admin.outputs.audit');
    }
}
