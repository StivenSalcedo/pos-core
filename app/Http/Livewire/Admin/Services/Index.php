<?php

namespace App\Http\Livewire\Admin\Services;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $selectedState = '';
    public $showModal = false;
    public $serviceIdToDelete = null;

    protected $paginationTheme = 'tailwind';

    protected $listeners = ['confirmDelete','render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->serviceIdToDelete = $id;
        $this->dispatchBrowserEvent('confirm-delete');
    }

    public function deleteService()
    {
        if ($this->serviceIdToDelete) {
            DB::transaction(function () {
                $service = Service::find($this->serviceIdToDelete);
                if ($service) {
                    $service->delete();
                }
            });

            $this->dispatchBrowserEvent('notify', ['message' => 'Servicio eliminado con éxito']);
        }
    }

    public function render()
    {
        $query = Service::query()
            ->with(['customer', 'equipmentType', 'brand', 'state'])
            ->when(
                $this->search,
                fn($q) =>
                $q->where('model', 'like', "%{$this->search}%")
                    ->orWhereHas('customer', fn($c) =>
                    $c->where('names', 'like', "%{$this->search}%"))
            )
            ->when($this->selectedState !== null && $this->selectedState !== '', function ($q) {
                if ($this->selectedState === 'recibido') {
                    $q->whereNull('state_id');
                } elseif (is_numeric($this->selectedState)) {
                    $q->where('state_id', $this->selectedState);
                }
            })
            ->latest();

        return view('livewire.admin.services.index', [
            'services' => $query->paginate($this->perPage),
        ])->layoutData(['title' => 'Servicios Tec.']);
    }
}
