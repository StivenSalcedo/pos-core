<?php

namespace App\Http\Livewire;

use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $saleTotal = 0;

    public $costTotal = 0;

    public $filterDate = 6;

    public $startDate = null;

    public $endDate = null;

    protected $enableDomains = [
        ''
    ];

    public function mount()
    {
        if (in_array(request()->getHost(), $this->enableDomains)) {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
    }

    public function render()
    {
        if (in_array(request()->getHost(), $this->enableDomains)) {
            $this->getData();
        }

        return view('livewire.dashboard')->with('enableDomains', $this->enableDomains)->layoutData(['title' => 'Dashboard']);
    }

    protected function getData()
    {
        $dataFrom = Carbon::parse('31-07-2024')->startOfDay();

        $bills = Bill::select(DB::raw('sum(cost) as cost, sum(total) as total'))
            ->where('created_at', '>=', $dataFrom)
            ->date($this->filterDate, $this->startDate, $this->endDate)
            ->status(1)
            ->first();

        $this->saleTotal = $bills->total;
        $this->costTotal = $bills->cost;
    }
}
