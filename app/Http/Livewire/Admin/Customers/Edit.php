<?php

namespace App\Http\Livewire\Admin\Customers;

use App\Enums\CustomerTributes;
use App\Enums\LegalOrganization;
use App\Models\Customer;
use App\Models\IdentificationDocument;
use App\Rules\Identification;
use App\Rules\Phone;
use App\Traits\LivewireTrait;
use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Models\Service;

class Edit extends Component
{
    use LivewireTrait;

    protected $listeners = ['openEdit'];

    public $identificationDocuments;

    public $tributes;

    public $legalOrganizations;

    public $openEdit = false;

    public $customer;
    public ?Service $service = null;

    protected function rules()
    {
        return [
            'customer.identification_document_id' => 'required|exists:identification_documents,id',
            'customer.legal_organization' => ['required', Rule::in(LegalOrganization::getCases())],
            'customer.tribute' => ['required', Rule::in(CustomerTributes::getCases())],
            'customer.no_identification' => ['required', 'string', new Identification, Rule::unique('customers', 'no_identification')->ignore($this->customer->id)],
            'customer.dv' => 'required_if:customer.identification_document_id,6|min:0|max:1',
            'customer.names' => 'required|string|min:5|max:250',
            'customer.direction' => 'nullable|string|max:250',
            'customer.phone' => ['nullable', 'string', new Phone],
            'customer.email' => 'nullable|string|email|max:250',
            'customer.top' => 'required|integer|min:0|max:1',
            'customer.neighborhood' => 'nullable|string|max:100',
            'customer.status' => 'required|integer|min:0|max:1',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'customer.identification_document_id' => 'tipo de documento',
        ];
    }

    protected function messages()
    {
        return [
            'customer.no_identification.regex' => 'El campo número de identificación solo puede contener números',
        ];
    }

    public function mount()
    {
        $this->customer = new Customer();
        $this->identificationDocuments = IdentificationDocument::enabled()->get()->pluck('name', 'id');
        $this->tributes = CustomerTributes::getCasesLabel();
        $this->legalOrganizations = LegalOrganization::getCasesLabel();
    }

    public function render()
    {
        return view('livewire.admin.customers.edit');
    }

    public function openEdit(Customer $customer, Service $service = null)
    {
        $this->customer = $customer;
        $this->service = $service;
        $this->resetValidation();
        $this->resetErrorBag();
        $this->openEdit = true;
    }

    public function update()
    {
        $rules = $this->rules();

        $this->customer->identification_document_id = (int) $this->customer->identification_document_id;

        if (! in_array($this->customer->identification_document_id, IdentificationDocument::FOREING_DOCUMENTS)) {
            array_push($rules['customer.no_identification'], 'regex:/^\d+$/');
        }

        $this->applyTrim(array_keys($rules));

        if ($this->customer->identification_document_id != IdentificationDocument::NIT) {
            $this->customer->legal_organization = LegalOrganization::NATURAL_PERSON->value;
            $this->customer->tribute = CustomerTributes::NOT_RESPONSIBLE->value;
            $this->customer->dv = null;
        }

        $this->validate($rules, $this->messages());
        if ($this->service) {
            $this->customer->setAuditParent($this->service);
        }
        $this->customer->save();
        $this->emitTo('admin.services.edit', 'set-customer', $this->customer);
        $this->customer = new Customer();

        $this->openEdit = false;

        $this->emitTo('admin.customers.index', 'render');

        $this->emit('success', 'Cliente actualizado con éxito');
    }
}
