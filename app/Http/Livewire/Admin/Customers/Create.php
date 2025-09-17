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

class Create extends Component
{
    use LivewireTrait;

    protected $listeners = ['openCreate'];

    public $openCreate = false;

    public $identificationDocuments;

    public $tributes;

    public $legalOrganizations;

    public $identification_document_id = IdentificationDocument::CEDULA;

    //public $legal_organization = LegalOrganization::NATURAL_PERSON->value;

    //public $tribute = CustomerTributes::NOT_RESPONSIBLE->value;

    public $no_identification;

    public $dv;

    public $names;

    public $direction;

    public $phone;

    public $email;

    public function mount()
    {
        $this->identificationDocuments = IdentificationDocument::enabled()->get()->pluck('name', 'id');
        $this->tributes = CustomerTributes::getCasesLabel();
        $this->legalOrganizations = LegalOrganization::getCasesLabel();
    }

    public function render()
    {
        return view('livewire.admin.customers.create');
    }

    public function openCreate()
    {
        $this->resetValidation();
        $this->openCreate = true;
    }

    public function store()
    {
        $rules = [
            'identification_document_id' => 'required|exists:identification_documents,id',
            'legal_organization' => ['required', Rule::in(LegalOrganization::getCases())],
            'tribute' => ['required', Rule::in(CustomerTributes::getCases())],
            'no_identification' => ['required', 'string', new Identification, 'unique:customers'],
            'dv' => 'required_if:identification_document_id,6|min:0|max:9',
            'names' => 'required|string|min:5|max:250',
            'direction' => 'nullable|string|max:250',
            'phone' => ['nullable', 'string', new Phone],
            'email' => 'nullable|string|email|max:250',
        ];

        $messages = [
            'no_identification.regex' => 'El campo número de identificación solo puede contener números',
        ];

        $attributes = [
            'identification_document_id' => 'tipo de documento',
        ];

        $this->identification_document_id = (int) $this->identification_document_id;
        if (! in_array($this->identification_document_id, IdentificationDocument::FOREING_DOCUMENTS)) {
            array_push($rules['no_identification'], 'regex:/^\d+$/');
        }

        $this->applyTrim(array_keys($rules));

        if ($this->identification_document_id != IdentificationDocument::NIT) {
            $this->legal_organization = LegalOrganization::NATURAL_PERSON->value;
            $this->tribute = CustomerTributes::NOT_RESPONSIBLE->value;
            $this->dv = null;
        }

        $data = $this->validate($rules, $messages, $attributes);

        $customer = Customer::create($data);

        $this->dispatchBrowserEvent('set-customer', $customer->only(['id', 'no_identification', 'names', 'phone']));

        $this->emit('success', 'Cliente creado con éxito');
        $this->emitTo('admin.customers.index', 'render');

        $this->resetExcept('identificationDocuments', 'tributes', 'legalOrganizations');
    }
}
