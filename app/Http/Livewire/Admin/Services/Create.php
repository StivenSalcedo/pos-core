<?php

namespace App\Http\Livewire\Admin\Services;

use App\Models\Service;
use App\Models\User;
use App\Models\Customer;
use App\Traits\LivewireTrait;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use LivewireTrait;

    protected $listeners = ['openCreate'];

    public $openCreate = false;

    public $date_entry;
    public $date_due;
    public $document_number;
    public $responsible_id;
    public $tech_assigned_id;
    public $customer_id;

    public $responsibles;
    public $technicians;
    public $customers;
    public $searchCustomer = '';
public $customerResults = [];
public $selectedCustomer = null;


    public function mount()
    {
        $today = Carbon::now();
        $this->date_entry = $today->format('Y-m-d');
        $this->date_due = $today->copy()->addDays(3)->format('Y-m-d');

        $this->responsibles = User::select('id', 'name')->get()->pluck('name', 'id');
        $this->technicians = User::select('id', 'name')->get()->pluck('name', 'id');
        $this->customers = Customer::select('id', 'names')->get()->pluck('names', 'id');
    }

    public function render()
    {
        return view('livewire.admin.services.create');
    }

    public function openCreate()
    {
        $this->resetValidation();
        $this->openCreate = true;
       
    }
    protected $validationAttributes = [
        'date_entry' => 'Fecha de Ingreso',
        'date_due' => 'Fecha de Vencimiento',
        'responsible_id' => 'Responsable',
        'customer_id' => 'Cliente',
    ];

    public function store()
    {
        $rules = [
            'date_entry'       => 'required|date',
            'date_due'         => 'required|date|after_or_equal:date_entry',
            'document_number'  => 'nullable|string|max:50',
            'responsible_id'   => 'required|exists:users,id',
            'tech_assigned_id' => 'nullable|exists:users,id',
            'customer_id'      => 'required|exists:customers,id',
        ];

        $this->applyTrim(array_keys($rules));

        $data = $this->validate($rules);

        $service = Service::create([
            'date_entry'       => $data['date_entry'],
            'date_due'         => $data['date_due'],
            'document_number'  => $data['document_number'] ?? null,
            'responsible_id'   => $data['responsible_id'],
            'tech_assigned_id' => $data['tech_assigned_id'] ?? null,
            'customer_id'      => $data['customer_id'],
            'state_id'        => null, // Por defecto “En revisión”
            'model'            => 'N/A',
        ]);

        // Emitir evento para el componente index
        $this->emit('success', 'Servicio creado con éxito');
        $this->emitTo('admin.services.index', 'render');

        // Cerrar modal
        $this->openCreate = false;

        // Redirigir a editar servicio (detalle)
        return redirect()->route('services.edit', $service->id);
    }

    // búsqueda dinámica
    public function updatedSearchCustomer()
    {
        if (strlen($this->searchCustomer) > 1) {
            $this->customerResults = \App\Models\Customer::query()
                ->where('names', 'like', '%' . $this->searchCustomer . '%')
                ->orWhere('no_identification', 'like', '%' . $this->searchCustomer . '%')
                ->limit(10)
                ->get();
        } else {
            $this->customerResults = [];
        }
    }

    public function selectCustomer($id)
    {
        $this->selectedCustomer = \App\Models\Customer::find($id);
        $this->customer_id = $this->selectedCustomer->id;
        $this->searchCustomer = $this->selectedCustomer->names;
        $this->customerResults = [];
    }

    public function clearCustomer()
    {
        $this->selectedCustomer = null;
        $this->customer_id = null;
        $this->searchCustomer = '';
    }
}
