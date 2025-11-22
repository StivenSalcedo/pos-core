<?php

namespace App\Http\Livewire\Admin\Services;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\ServiceService;
use App\Exceptions\CustomException;
use Illuminate\Validation\ValidationException;
use App\Services\FactusConfigurationService;
use Illuminate\Support\Facades\Log;
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $selectedState = '';
    public $showModal = false;
    public $serviceIdToDelete = null;

    protected $paginationTheme = 'tailwind';

    protected $listeners = ['confirmDelete',
     'render',
     'deleteService' => 'deleteService',];

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
                     // Borrar hijos primero (si no hay cascade en DB)
                $service->details()->delete();
                $service->products()->delete();
                $service->payments()->delete();
                $service->attachments()->each(function ($attachment) {
                    // Elimina archivo físico si existe
                    if (Storage::disk('public')->exists($attachment->path)) {
                        Storage::disk('public')->delete($attachment->path);
                    }
                    $attachment->delete();
                });
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

    public function printReceipt($id)
    {
        $this->dispatchBrowserEvent('print-ticket', $id);
    }
    public function redirectToEdit($id)
    {
        return redirect()->route('admin.services.edit', $id);
    }

    public function validateElectronicBill(Service $service)
    {
        try {
            ServiceService::validateElectronicBill($service);
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

       $this->dispatchBrowserEvent('print-ticket', $service->id);
       $this->render();
    }
}
