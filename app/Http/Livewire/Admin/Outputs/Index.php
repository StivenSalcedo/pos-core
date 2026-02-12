<?php

namespace App\Http\Livewire\Admin\Outputs;

use App\Exports\OutputsExport;
use App\Models\Output;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Terminal;

class Index extends Component
{

    use WithPagination, AuthorizesRequests;

    protected $listeners = ['render'];

    public $search, $filterDate = '0', $startDate, $endDate;
    public $terminals;
    public $terminal_id = '';

    public function mount() {}
    public function render()
    {
        $this->terminals = Terminal::all()->pluck('name', 'id');
        $total = Output::filterByTerminalPermission(auth()->user(), $this->terminal_id)
        ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('id', 'LIKE', "%{$this->search}%")
                        ->orWhere('reason', 'LIKE', "%{$this->search}%")
                        ->orWhere('description', 'LIKE', "%{$this->search}%");
                });
            })
            ->date($this->filterDate, $this->startDate, $this->endDate)
            ->sum('price');

        $outputs = Output::with('user', 'terminal')
           ->filterByTerminalPermission(auth()->user(), $this->terminal_id)
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('id', 'LIKE', "%{$this->search}%")
                        ->orWhere('reason', 'LIKE', "%{$this->search}%")
                        ->orWhere('description', 'LIKE', "%{$this->search}%");
                });
            })
            ->date($this->filterDate, $this->startDate, $this->endDate)
            ->latest()
            ->paginate(10);

        return view('livewire.admin.outputs.index', compact('outputs', 'total'))->layoutData(['title' => 'Egresos']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function delete(Output $output)
    {
        $this->authorize('isAccounted', $output);
        $output->delete();
        $this->emit('success', 'Egreso eliminado con éxito');
    }

    public function exportOutputs()
    {

        $query = Output::query()
            ->where('id', 'LIKE', '%' . $this->search . '%')
            ->date($this->filterDate, $this->startDate, $this->endDate)
            ->latest();

        return Excel::download(new OutputsExport($query), 'Egresos.xlsx');
    }
}
