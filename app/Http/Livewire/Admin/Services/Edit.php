<?php

namespace App\Http\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Service;
use App\Models\Customer;
use App\Models\User;
use App\Models\ServiceState;
use App\Models\EquipmentType;
use App\Models\Brand;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\ServiceAttachment;
use Illuminate\Support\Str;
use App\Traits\LivewireTrait;


class Edit extends Component
{
    use LivewireTrait;
    use WithFileUploads;


    public Service $service;
    public  $responsibles, $technicians, $states, $equipmentTypes, $brands;
    public $tab = 'create'; // tab activa
    public $selectedCustomer = [];
    public $customers = [];
    public $details = [];
    public $products = [];
    public $payments = [];
    public $histories = [];
    public $searchCustomer = '';
    public $photo;
    public $openUploadModal = false;
    public $cameraPhoto;
    public $date_entry_time;



    protected $listeners = [
        'openEdit',
        'refreshdata',
        'refreshBrands',
        'set-customer' => 'setCustomerFromModal',
        'refreshServiceDetails',
        'refreshProductDetails',
        'refreshPaymentDetails',
        'refreshAttachments' => '$refresh',
        'uploadCameraPhoto'  => 'saveCameraPhoto',
    ];



    protected $rules = [
        'service.date_entry' => 'required|date',
        'service.date_due' => 'required|date|after_or_equal:service.date_entry',
        'service.customer_id' => 'required|exists:customers,id',
        'service.responsible_id' => 'required|exists:users,id',
        'service.tech_assigned_id' => 'nullable|exists:users,id',
        'service.state_id' => 'required|exists:service_states,id',
        'service.model' => 'required|string|max:150',
        'service.problem_description' => 'nullable|string|max:500',
        'service.diagnosis' => 'nullable|string|max:500',
        'service.equipment_type_id' => 'required|exists:equipment_types,id',
        'service.document_number' => 'nullable|string|max:255',
        'service.password' => 'nullable|string|max:255',
        'service.accessories' => 'nullable|string|max:255',
        'service.user' => 'nullable|string|max:255',
        'service.brand_id' => 'required|exists:brands,id',


    ];

    public $rulesCreate = [
        'service.date_entry'       => 'required|date',
        'service.date_due'         => 'required|date|after_or_equal:date_entry',
        'service.responsible_id'   => 'required|exists:users,id',
        'service.tech_assigned_id' => 'nullable|exists:users,id',
        'service.customer_id'      => 'required|exists:customers,id',
        'service.equipment_type_id' => 'required|exists:equipment_types,id',
        'service.state_id'          => 'required|exists:service_states,id',
        'service.brand_id' => 'required|exists:brands,id',
    ];



    protected $validationAttributes = [
        'service.date_entry' => 'Fecha de Ingreso',
        'service.date_due' => 'Fecha de Vencimiento',
        'service.responsible_id' => 'Responsable',
        'service.customer_id' => 'Cliente',
        'service.equipment_type_id' => 'Tipo de Equipo',
        'service.brand_id' => 'Marca',
    ];

    public function validatePhoto()
    {
        $this->validate([
            'photo' => 'required|image|max:4096',
        ]);
    }

    protected $messages = [
        'photo.required' => 'Debe seleccionar una imagen.',
        'photo.image' => 'El archivo debe ser una imagen válida.',
        'photo.max' => 'La imagen no debe superar los 4MB.',
    ];

    public function mount(Service $service)
    {
        $this->service = $service;

        if (!$this->service->id) {
            $today = Carbon::now();
            $this->service->date_entry = $today->format('Y-m-d');
            $this->service->date_due = $today->copy()->addDays(3)->format('Y-m-d');
        } else {
            $this->date_entry_time = $this->service->date_entry;
            $this->service->date_entry = Carbon::parse($this->service->date_entry)->format('Y-m-d');
        }

        Log::debug('Llegó a setCustomerFromModal', ['data' => $service]);
        $this->responsibles = User::pluck('name', 'id');
        $this->technicians = User::pluck('name', 'id');
        $this->states = ServiceState::pluck('name', 'id');
        $this->equipmentTypes = EquipmentType::pluck('name', 'id');

        $this->brands = Brand::pluck('name', 'id');
        $this->service->load(['details.component', 'details.brand']);

        if ($service->customer) {
            $this->selectedCustomer = [
                'id' => $service->customer->id,
                'names' => $service->customer->names,
                'no_identification' => $service->customer->no_identification,
            ];
        }

        if (is_null($this->service->state_id) && $this->states->isNotEmpty()) {
            $this->service->state_id = $this->states->keys()->first();
            $this->service->load(['details.component', 'details.brand']);
        }
        $this->refreshServiceDetails();
        $this->refreshProductDetails();
        $this->refreshPaymentDetails();
    }


    public function update()
    {

        if (!$this->service->id) {


            $this->applyTrim(array_keys($this->rulesCreate));
            $data = $this->validate($this->rulesCreate);
            $service = Service::create([
                'date_entry'       => Carbon::parse($data['service']['date_entry'])->setTimeFromTimeString(now()->format('H:i:s')),
                'date_due'         => $data['service']['date_due'],
                'document_number'  => null,
                'responsible_id'   => $data['service']['responsible_id'],
                'tech_assigned_id' => $data['service']['tech_assigned_id'] ?? null,
                'customer_id'      => $data['service']['customer_id'],
                'state_id'        =>  $data['service']['state_id'],
                'model'            => 'N/A',
                'equipment_type_id' => $data['service']['equipment_type_id'],
                'brand_id' => $data['service']['brand_id'],
            ]);
            // ✅ Obtener el tipo de equipo con sus componentes por defecto
            $equipmentType = \App\Models\EquipmentType::with('components')->find($this->service->equipment_type_id);

            if ($equipmentType && $equipmentType->components->count() > 0) {
                foreach ($equipmentType->components as $component) {
                    $service->details()->create([
                        'component_id' => $component->id,
                        'quantity'     => $component->pivot->default_quantity ?? 1,
                        'reference'    => 'SIN REFERENCIA',
                        'capacity'     => 'N/A',
                    ]);
                }
            }

            // Emitir evento para el componente index
            $this->emit('success', 'Servicio creado con éxito');
            $this->dispatchBrowserEvent('redirect-after-success', [
                'url' => route('admin.services.edit', $service->id)
            ]);
        } else {
            $this->validate();
            $this->service->date_entry = Carbon::parse($this->service->date_entry)->setTimeFromTimeString(Carbon::parse($this->date_entry_time)->format('H:i:s'));
            $this->service->save();
            $this->service->date_entry = Carbon::parse($this->service->date_entry)->format('Y-m-d');
            $this->emit('success', 'Servicio actualizado correctamente');
        }
    }


    public function openPhotoUpload()
    {
        $this->reset('photo');
        $this->resetValidation();
        $this->openUploadModal = true;
    }

    public function saveCameraPhoto($base64Image)
    {
        try {
            if (!$base64Image) {
                $this->emit('error', 'No se pudo obtener la imagen de la cámara.');

                return;
            }

            // Quitar encabezado "data:image/png;base64,..."
            $imageData = explode(',', $base64Image)[1] ?? null;
            if (!$imageData) throw new \Exception("Formato inválido de imagen");

            $binary = base64_decode($imageData);

            // Crear nombre y ruta
            $filename = 'camera_' . Str::random(8) . '.jpg';
            $path = 'services/' . $this->service->id . '/' . $filename;

            Storage::disk('public')->put($path, $binary);

            // Guardar registro
            ServiceAttachment::create([
                'service_id' => $this->service->id,
                'path'       => $path,
                'filename'   => $filename,
            ]);


            $this->emit('success', 'La imagen capturada se subió correctamente.');


            $this->emitSelf('refreshAttachments');
        } catch (\Exception $e) {
            $this->emit('error', 'Error al guardar:' + $e->getMessage());
        }
    }

    public function savePhoto()
    {
        $this->validatePhoto();
        $path = $this->photo->storeAs('images/services', $this->service->id . '_' . $this->photo->getClientOriginalName(), 'public');
        //$path = $this->photo->store('services/' . $this->service->id, 'public');

        ServiceAttachment::create([
            'service_id' => $this->service->id,
            'path' => $path,
            'filename' => $this->photo->getClientOriginalName(),
        ]);

        $this->emit('success', 'La imagen se guardó correctamente.');


        $this->reset('photo');
        $this->openUploadModal = false;
        $this->emitSelf('refreshAttachments');
    }

    public function removePhoto($id)
    {
        $attachment = ServiceAttachment::findOrFail($id);

        // Eliminar archivo del disco
        if (Storage::disk('public')->exists($attachment->path)) {
            Storage::disk('public')->delete($attachment->path);
        }

        $attachment->delete();

        $this->emit('success', 'La imagen fue eliminada correctamente.');


        $this->emitSelf('refreshAttachments');
    }


    public function refreshdata($newEquipmentTypeId = null)
    {
        $this->equipmentTypes = EquipmentType::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        // Si se envió un ID válido, seleccionarlo automáticamente
        if ($newEquipmentTypeId && isset($this->equipmentTypes[$newEquipmentTypeId])) {
            $this->service->equipment_type_id = $newEquipmentTypeId;
        }
    }

    public function refreshBrands($newBrandId = null)
    {
        $this->brands = Brand::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        // Si se envió un ID válido, seleccionarlo automáticamente
        if ($newBrandId && isset($this->brands[$newBrandId])) {
            $this->service->brand_id = $newBrandId;
        }
    }

    public function refreshServiceDetails()
    {
        $this->details = $this->service->details()->with('component', 'brand')->get()->toArray();
    }

    public function refreshProductDetails()
    {
        $this->products = $this->service->products()->with('product')->get()->toArray();
    }

    public function goToHistoriesTab()
    {
        if (!$this->service->id) {
            return; // Previene acción si no se ha guardado aún
        }

        $this->tab = 'histories';
        $this->changeTab('histories'); // Reutiliza tu función existente
    }

    public function changeTab($newTab)
    {

        $this->tab = $newTab;

        if ($newTab === 'histories') {
            $this->refreshHistories();
        }
    }

    public function refreshHistories()
    {

        if (!$this->service->customer_id) {
            $this->histories = [];
            return;
        }

        $this->histories = Service::with(['customer', 'equipmentType', 'brand', 'state'])
            ->where('customer_id', $this->service->customer_id)
            ->where('id', '!=', $this->service->id)
            ->latest()
            ->take(20)
            ->get()
            ->toArray();
    }

    public function redirectToEdit($id)
    {
        $url = route('admin.services.edit', $id);
        $this->dispatchBrowserEvent('open-new-tab', ['url' => $url]);
    }




    public function refreshPaymentDetails()
    {
        $this->payments = $this->service->payments()->with('payment', 'user')->get();
    }



    public function clearCustomer()
    {
        $this->selectedCustomer = null;
        $this->service->customer_id = null;
        $this->searchCustomer = '';
    }

    public function setCustomerFromModal($customer)
    {
        $this->selectCustomer($customer['id']);
    }

    public function updatedSearchCustomer()
    {
        if (strlen($this->searchCustomer) > 1) {
            $this->customers = Customer::where('names', 'like', "%{$this->searchCustomer}%")
                ->orWhere('no_identification', 'like', "%{$this->searchCustomer}%")
                ->limit(10)->get();
        } else {
            $this->customers = [];
        }
    }

    public function selectCustomer($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $this->selectedCustomer = $customer->only(['id', 'names', 'no_identification']);
            $this->service->customer_id = $customer->id;
            $this->customers = [];
            $this->searchCustomer = $customer->names;
        }
    }

    public function deleteDetail($id)
    {
        $detail = $this->service->details()->find($id);

        if ($detail) {
            $detail->delete();
            $this->refreshServiceDetails();
        }
    }

    public function deleteProduct($id)
    {
        $product = $this->service->products()->find($id);

        if ($product) {
            $product->delete();
            $this->refreshProductDetails();
            Product::where('id', $product->product_id)->increment('stock', $product->quantity);
        }
    }

    public function removePayment($id)
    {

        $payment = $this->service->payments()->find($id);

        if ($payment) {
            $payment->delete();
            $this->refreshPaymentDetails();
        }
    }




    public function render()
    {
        return view('livewire.admin.services.edit')->layoutData(['title' => 'Detalle Servicio ' . $this->service->id]);
    }
}
