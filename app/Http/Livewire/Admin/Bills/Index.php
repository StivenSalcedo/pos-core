<?php

namespace App\Http\Livewire\Admin\Bills;

use App\Exceptions\CustomException;
use App\Exports\BillsExport;
use App\Http\Controllers\Log;
use App\Models\Bill;
use App\Models\Product;
use App\Models\Terminal;
use App\Services\BillService;
use App\Services\FactusConfigurationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    protected $listeners = ['render'];

    protected $query;

    public $search;

    public $filter = '1';

    public $status = '0';

    public $filterDate = '0';

    public $terminals;

    public $terminal_id = '';

    public $startDate;

    public $endDate;

    public $file;

    public $urlBill;

    public $filters = [
        1 => 'N° Factura',
        2 => 'Nombre de cliente',
        3 => 'Nombre de cajero',
    ];

    public function mount()
    {
        $this->terminals = Terminal::all()->pluck('name', 'id');
        $this->getQuery();
    }

    public function render()
    {
        $this->getQuery();

        $total = $this->query->sum('total');
        $bills = $this->query->latest()->paginate(10);

        return view('livewire.admin.bills.index', compact('bills', 'total'))->layoutData(['title' => 'Facturas']);
    }

    protected function getQuery()
    {
        $filter = [1 => 'id', 2 => 'names', 3 => 'name'][$this->filter];

        $user = auth()->user();

        $query = Bill::query()
            ->with(
                'electronicBill',
                'user',
                'terminal',
                'customer',
                'paymentMethod',
                'finance',
                'electronicCreditNote'
            )
            ->search($filter, $this->search)
            ->status($this->status)
            ->date($this->filterDate, $this->startDate, $this->endDate);

        // 🔐 VALIDACIÓN DE PERMISO
        if (!$user->can('ver todas las sedes')) {
            $query->terminal($user->terminals->first()->id);
        } else {
            $query->terminal($this->terminal_id);
        }

        $this->query = $query;
    }

    public function cancelBill(Bill $bill)
    {
        if ($bill->status === '1') {
            return $this->emit('alert', 'La factura ya ha sido cancelada');
        }

        if (FactusConfigurationService::isApiEnabled() && $bill->electronicBill && !$bill->electronicBill->is_validated) {
            return $this->emit('alert', 'La factura no ha sido validada por la DIAN. No se puede anular');
        }

        BillService::storeElectronicCreditNote($bill);

        $details = $bill->details;

        try {

            DB::beginTransaction();

            $bill->status = '1';
            $bill->save();

            foreach ($details as $value) {

                if (is_array($value->presentation)) {
                    Product::where('id', $value->product_id)->increment('stock', $value->amount);
                } else {
                    $units = $value->amount * $value->presentation->quantity;
                    Product::where('id', $value->product_id)->increment('units', $units);

                    $product = Product::find($value->product_id);
                    $stock = bcdiv($product->units / $product->quantity, '1');

                    Product::where('id', $value->product_id)->update(['stock' => $stock]);
                }
            }

            BillService::validateElectronicCreditNote($bill);

            DB::commit();
        } catch (CustomException $ce) {
            DB::rollback();
            Log::error($ce->getMessage());

            return $this->emit('error', $ce->getMessage());
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage(), $bill->toArray());

            return $this->emit('error', 'Oops... Ha ocurrido un error inesperado. Vuelve a intentarlo');
        }

        return $this->emit('success', 'Factura anulada con éxito');
    }

    public function exportBills()
    {
        $this->getQuery();
        $query = $this->query->latest();
        $date = now()->format('d-m-Y');

        return Excel::download(new BillsExport($query), "Facturas-$date.xlsx");
    }

    public function validateElectronicBill(Bill $bill)
    {
        try {
            BillService::validateElectronicBill($bill);
        } catch (CustomException $ce) {
            Log::error($ce->getMessage());

            return $this->emit('error', $ce->getMessage());
        } catch (ValidationException $ce) {
            $errors = $ce->errors();
            foreach ($errors as $field => $errorMessages) {
                foreach ($errorMessages as $errorMessage) {
                    $this->addError($field, $errorMessage);
                }
            }

            return;
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [], $th->getLine());

            return $this->emit('error', 'Ha sucedido un error inesperado. Vuelve a intentarlo');
        }

        $this->dispatchBrowserEvent('print-ticket', $bill->id);
    }

    public function validateElectronicCreditNote(Bill $bill)
    {
        try {
            BillService::validateElectronicCreditNote($bill);
        } catch (CustomException $ce) {
            Log::error($ce->getMessage());

            return $this->emit('error', $ce->getMessage());
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), [], $th->getLine());

            return $this->emit('error', 'Ha sucedido un error inesperado. Vuelve a intentarlo');
        }

        $this->dispatchBrowserEvent('print-ticket', $bill->id);
    }
}
