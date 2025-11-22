<?php

namespace App\Services\Factus;

use App\Enums\LegalOrganization;
use App\Exceptions\CustomException;
use App\Models\Service;
use App\Models\Tribute;
use App\Services\CompanyService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class ElectronicServiceService
{
    /**
     * Guarda la factura en la API
     */
    public static function validate(Service $service): Response
    {
        $data = self::prepareData($service);
        $response = HttpService::apiHttp()
            ->post('bills/validate', $data);
 Log::debug('Respuesta', ['data' => $response]);
        return $response;
    }

    private static function prepareData(Service $service): array
    {
        Log::debug('Llegó a validar', ['data' => $service]);
        $details = $service->products;
        $items = [];
        $reference_code=Str::uuid()->toString();
        $apiTributeId = Tribute::where('name', 'IVA')->get()->first()->api_tribute_id;
        foreach ($details as $detail) {
            
            $items[] = [
                'code_reference' => $detail->product->reference,
                'name' => $detail->product->name,
                'quantity' => $detail->quantity,
                'discount_rate' => calculateDiscountPercentage($detail->unit_price, (int) $detail->quantity, $detail->discount),
                'discount' => $detail->discount,
                'price' => $detail->unit_price,
                'tax_rate' => $detail->product->taxRates->first()->rate,
                'withholding_taxes' => [],
                'is_excluded' => $detail->product->taxRates->first()->id === 1 ? 1 : 0, // No esta excluido de IVA
                'unit_measure_id' => 70, // Unidad
                'standard_code_id' => 1, // Estándar de adopción del contribuyente
                'tribute_id' => $apiTributeId,
            ];
        }

        $customer = [
            'identification_document_id' => $service->customer->identification_document_id,
            'identification' => $service->customer->no_identification,
            'tribute_id' => $service->customer->tribute,
            'legal_organization_id' => $service->customer->legal_organization,
            'dv' => $service->customer->dv,
            'names' => $service->customer->legal_organization == LegalOrganization::NATURAL_PERSON->value ? $service->customer->names : null,
            'company' => $service->customer->legal_organization == LegalOrganization::LEGAL_PERSON->value ? $service->customer->names : null,
            'email' => $service->customer->email,
            'phone' => $service->customer->phone,
            'address' => $service->customer->direction,
        ];

        $data = [
            'reference_code' => $reference_code,
            'payment_method_code' => $service->payments->first()->paymentMethod->code,
            'customer' => $customer,
            'items' => $items,
            'observation' => '',
        ];

        $numbering_range_id = auth()->user()->terminals->first()->factus_numbering_range_id;

        if ($numbering_range_id) {
            $data['numbering_range_id'] = $numbering_range_id;
        }

        return $data;
    }

    /**
     * Guarda los datos de la factura validada en la base de datos
     */
    public static function saveElectronicBill(array $data, Service $service): void
    {
        //$service->number = $data['data']['bill']['number'];
       // $service->save();

        $electronicBill = $data['data']['bill'];
        $numberingRange = $data['data']['numbering_range'];

        $data = [
            'number' => $electronicBill['number'],
            'qr_image' => $electronicBill['qr_image'],
            'cufe' => $electronicBill['cufe'],
            'numbering_range' => json_encode($numberingRange),
            'is_validated' => true,
            'reference_code'=> $electronicBill['reference_code'],
        ];

        if (! $service->electronicBill) {
            $service->electronicBill()->create($data);
        } else {
            $service->electronicBill()->update($data);
        }
    }

    /**
     * Guarda la nota credito en la API
     */
    public static function storeCreditNote(Service $service): void
    {
        $response = HttpService::apiHttp()
            ->post('credit-notes/store-using-bill', ['bill_number' => $service->number])
            ->json();

        $service->electronicCreditNote()->create([
            'number' => $response['data']['credit_note']['number'],
        ]);
    }

    /**
     * Envia la nota credito a la DIAN
     */
    public static function validateCreditNote(Service $service): Response
    {
        $service = $service->fresh();
        $response = HttpService::apiHttp()->post('credit-notes/send/'.$service->electronicCreditNote->number);

        return $response;
    }

    /**
     * Guarda los datos de la nota credito validada en la base de datos
     */
    public static function saveCreditNote(array $data, Service $service): void
    {
        $electronicCreditNote = $data['data']['credit_note'];

        $service->electronicCreditNote()->update([
            'number' => $electronicCreditNote['number'],
            'qr_image' => $electronicCreditNote['qr_image'],
            'cude' => $electronicCreditNote['cude'],
            'is_validated' => true,
        ]);
    }
}
