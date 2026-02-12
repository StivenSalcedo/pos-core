<?php

namespace App\Http\Livewire;

use App\Models\Sale;
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
       // if (in_array(request()->getHost(), $this->enableDomains)) {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
       // }
    }

    public function render()
    {
       // if (in_array(request()->getHost(), $this->enableDomains)) {
            $this->getData();
       // }

        return view('livewire.dashboard')->with('enableDomains', $this->enableDomains)->layoutData(['title' => 'Dashboard']);
    }

    protected function getData()
    {
       
         [$from, $to] = $this->resolveDates();
        $bills = Sale::select(DB::raw('sum(products.cost) as cost, sum(sales.total) as total'))
            ->join('products', 'products.id', '=', 'sales.product_id')
            ->join('terminals', 'products.terminal_id', '=', 'terminals.id')
            ->filterByTerminalPermission(auth()->user(), '')
             ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('sales.created_at', [$from, $to]);
            })
            ->first();

        $this->saleTotal = $bills->total;
        $this->costTotal = $bills->cost;
    }

     private function resolveDates()
    {
        switch ((int) $this->filterDate) {
            case 1: // Hoy
                return [now()->startOfDay(), now()->endOfDay()];

            case 2: // Esta semana
                return [now()->startOfWeek(), now()->endOfWeek()];

            case 3: // Últimos 7 días
                return [now()->subDays(6)->startOfDay(), now()->endOfDay()];

            case 4: // Semana pasada
                return [
                    now()->subWeek()->startOfWeek(),
                    now()->subWeek()->endOfWeek()
                ];

            case 5: // Hace 15 días
                return [now()->subDays(14)->startOfDay(), now()->endOfDay()];

            case 6: // Este mes
                return [now()->startOfMonth(), now()->endOfMonth()];

            case 7: // Mes pasado
                return [
                    now()->subMonth()->startOfMonth(),
                    now()->subMonth()->endOfMonth()
                ];

            case 8: // Rango manual
                return [
                    \Carbon\Carbon::parse($this->startDate)->startOfDay(),
                    \Carbon\Carbon::parse($this->endDate)->endOfDay()
                ];

            default: // 0 => Todos
                return [null, null];
        }
    }
}
