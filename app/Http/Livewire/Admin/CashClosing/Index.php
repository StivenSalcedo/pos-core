<?php

namespace App\Http\Livewire\Admin\CashClosing;

use App\Exports\CashClosingExport;
use App\Models\CashClosing;
use App\Models\Terminal;
use App\Models\User;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{

    protected $listeners = ['render'];

    public $terminals, $arrayTerminals, $terminal_id = '', $openModal = false, $filterDate = '0', $startDate, $endDate;
    public $users, $user_id = '';
    public $totales;

    public function mount()
    {
        $this->users = User::all()->pluck('name', 'id');
        $this->terminals = Terminal::where('status', Terminal::ACTIVE)->get();
        $this->arrayTerminals = Terminal::all()->pluck('name', 'id');
    }

    public function render()
    {
        $this->totales = CashClosing::date($this->filterDate, $this->startDate, $this->endDate)
            ->selectRaw('SUM(total_sales) as total_sales, SUM(outputs) as outputs, SUM(tip) as tips')
            ->terminal($this->terminal_id)
            ->responsible($this->user_id)
            ->first();

        $closings = CashClosing::with('user', 'terminal')
            ->date($this->filterDate, $this->startDate, $this->endDate)
            ->terminal($this->terminal_id)
            ->responsible($this->user_id)
            ->latest()
            ->paginate('10');

        return view('livewire.admin.cash-closing.index', compact('closings'))->layoutData(['title' => 'Cierres de caja']);
    }

    public function export()
    {
        $date = now()->format('d-m-Y');
        $query = CashClosing::date($this->filterDate, $this->startDate, $this->endDate)
            ->terminal($this->terminal_id)
            ->responsible($this->user_id)
            ->latest();

        return Excel::download(new CashClosingExport($query), "Cierres-de-caja-$date.xlsx");
    }
}
