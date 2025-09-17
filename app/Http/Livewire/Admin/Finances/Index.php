<?php

namespace App\Http\Livewire\Admin\Finances;

use App\Exports\FinancesExport;
use App\Http\Controllers\Log;
use App\Models\Bill;
use App\Models\DetailFinance;
use App\Models\Finance;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    protected $listeners = ['render' => 'updateTotals'];

    public $search;

    public $filter = '1';

    public $filterDate = '0';

    public $startDate;

    public $endDate;

    public $total;

    public $wallet;

    public $filterStatus;

    public $filters = [
        1 => 'N° Factura',
        2 => 'Identificación',
        3 => 'Nombre',
    ];

    public function mount()
    {
        $this->updateTotals();
    }

    public function render()
    {
        $filter = [1 => 'bill_id',  2 => 'no_identification', 3 => 'names'][$this->filter];

        $finances = Finance::query()
            ->with('bill', 'customer', 'details')
            ->search($filter, $this->search)
            ->whereRelation('bill', 'status', Bill::ACTIVA)
            ->date($this->filterDate, $this->startDate, $this->endDate)
            ->filterStatus($this->filterStatus)
            ->latest()
            ->paginate(10);

        return view('livewire.admin.finances.index', compact('finances'))->layoutData(['title' => 'Financiamientos']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function updateTotals()
    {
        $this->total = Bill::where('status', Bill::ACTIVA)
            ->whereHas('finance')
            ->sum('total');

        $paid = DB::table('detail_finances')
            ->join('finances', 'finances.id', 'detail_finances.finance_id')
            ->join('bills', 'bills.id', 'finances.bill_id')
            ->where('bills.status', Bill::ACTIVA)
            ->sum('detail_finances.value');

        $this->wallet = $this->total - $paid;
    }

    public function deleteFinance(Finance $finance)
    {

        try {
            DB::beginTransaction();
            DetailFinance::where('finance_id', $finance->id)->delete();
            $finance->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());

            return $this->emit('error', 'Ha ocurrido un error al eliminar la financiación');
        }

        $this->updateTotals();

        $this->emit('success', 'Financiación eliminada con éxito');
    }

    public function exportFinances()
    {

        $filter = [1 => 'bill_id',  2 => 'no_identification', 3 => 'names'][$this->filter];

        $query = Finance::query()
            ->search($filter, $this->search)
            ->whereRelation('bill', 'status', Bill::ACTIVA)
            ->date($this->filterDate, $this->startDate, $this->endDate)
            ->filterStatus($this->filterStatus)
            ->latest();

        return Excel::download(new FinancesExport($query), 'Financiaciones.xlsx');
    }
}
