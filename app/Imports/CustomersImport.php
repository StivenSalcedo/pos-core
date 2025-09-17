<?php

namespace App\Imports;

use App\Enums\CustomerTributes;
use App\Enums\LegalOrganization;
use App\Models\Customer;
use App\Models\IdentificationDocument;
use App\Rules\Identification;
use App\Rules\Phone;
use App\Utilities\SanitizeData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    private Collection $documents;

    private array $tributes;

    private array $legalOrganizations;

    public function __construct()
    {
        $this->documents = IdentificationDocument::enabled()->get()->pluck('name', 'id');
        $this->tributes = CustomerTributes::getCasesLabel();
        $this->legalOrganizations = LegalOrganization::getCasesLabel();
    }

    public function model(array $row)
    {
        $data = SanitizeData::trim($row);

        $data = $this->formatData($data);

        $this->validateModel($data);

        $data = $this->verifyTypeDocument($data);

        return new Customer($data);
    }

    protected function validateModel($row)
    {
        $rules = [
            'identification_document_id' => ['required', Rule::in($this->documents->toArray())],
            'legal_organization' => ['required', Rule::in($this->legalOrganizations)],
            'tribute' => ['required', Rule::in($this->tributes)],
            'no_identification' => ['required', 'string', new Identification, 'unique:customers'],
            'names' => 'required|string|min:5|max:250',
            'direction' => 'nullable|string|max:250',
            'phone' => ['nullable', 'string', new Phone],
            'email' => 'nullable|string|email|max:250',
        ];

        Validator::make($row, $rules)->validate();

    }

    protected function formatData(array $data): array
    {
        $data = [
            'identification_document_id' => $data['tipo_de_documento'],
            'legal_organization' => $data['tipo_de_persona'],
            'tribute' => $data['responsabilidad_tributaria'],
            'no_identification' => $data['numero_de_identificacion'],
            'names' => $data['nombres'],
            'direction' => $data['direccion'],
            'phone' => $data['celular'],
            'email' => $data['email'],
        ];

        return $data;
    }

    protected function verifyTypeDocument(array $data): array
    {
        $data['identification_document_id'] = array_search($data['identification_document_id'], $this->documents->toArray());

        if ($data['identification_document_id'] === IdentificationDocument::NIT) {

            $data['tribute'] = (string) array_search($data['tribute'], $this->tributes);
            $data['legal_organization'] = (string) array_search($data['legal_organization'], $this->legalOrganizations);

        } else {

            $data['tribute'] = CustomerTributes::NOT_RESPONSIBLE->value;
            $data['legal_organization'] = LegalOrganization::NATURAL_PERSON->value;

        }

        return $data;
    }
}
