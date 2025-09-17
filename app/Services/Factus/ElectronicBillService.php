<?php

namespace App\Services\Factus;

use App\Enums\LegalOrganization;
use App\Exceptions\CustomException;
use App\Models\Bill;
use App\Models\Tribute;
use App\Services\CompanyService;
use Illuminate\Http\Client\Response;

class ElectronicBillService
{
    /**
     * Guarda la factura en la API
     */
    public static function validate(Bill $bill): Response
    {
        $data = self::prepareData($bill);
        $response = HttpService::apiHttp()
            ->post('bills/validate', $data);

        return $response;
    }

    private static function prepareData(Bill $bill): array
    {
        $details = $bill->details;
        $items = [];

        foreach ($details as $detail) {

            $tribute1 = $detail->documentTaxes->first();
            $tribute2 = $tribute1->taxRates->first();
            $apiTributeId = Tribute::where('name', $tribute1->tribute_name)->get()->first()->api_tribute_id;

            $items[] = [
                'code_reference' => $detail->product->reference,
                'name' => $detail->name,
                'quantity' => $detail->amount,
                'discount_rate' => calculateDiscountPercentage($detail->price, (int) $detail->amount, $detail->discount),
                'discount' => $detail->discount,
                'price' => $detail->price,
                'tax_rate' => $tribute2->rate,
                'withholding_taxes' => [],
                'is_excluded' => $detail->product->taxRates->first()->id === 1 ? 1 : 0, // No esta excluido de IVA
                'unit_measure_id' => 70, // Unidad
                'standard_code_id' => 1, // Estándar de adopción del contribuyente
                'tribute_id' => $apiTributeId,
            ];
        }

        $customer = [
            'identification_document_id' => $bill->customer->identification_document_id,
            'identification' => $bill->customer->no_identification,
            'tribute_id' => $bill->customer->tribute,
            'legal_organization_id' => $bill->customer->legal_organization,
            'dv' => $bill->customer->dv,
            'names' => $bill->customer->legal_organization == LegalOrganization::NATURAL_PERSON->value ? $bill->customer->names : null,
            'company' => $bill->customer->legal_organization == LegalOrganization::LEGAL_PERSON->value ? $bill->customer->names : null,
            'email' => $bill->customer->email,
            'phone' => $bill->customer->phone,
            'address' => $bill->customer->direction,
        ];

        $data = [
            'reference_code' => $bill->reference_code,
            'payment_method_code' => $bill->paymentMethod->code,
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
    public static function saveElectronicBill(array $data, Bill $bill): void
    {
        $bill->number = $data['data']['bill']['number'];
        $bill->save();

        $electronicBill = $data['data']['bill'];
        $numberingRange = $data['data']['numbering_range'];

        $data = [
            'number' => $electronicBill['number'],
            'qr_image' => $electronicBill['qr_image'],
            'cufe' => $electronicBill['cufe'],
            'numbering_range' => json_encode($numberingRange),
            'is_validated' => true,
        ];

        if (! $bill->electronicBill) {
            $bill->electronicBill()->create($data);
        } else {
            $bill->electronicBill()->update($data);
        }
    }

    /**
     * Guarda la nota credito en la API
     */
    public static function storeCreditNote(Bill $bill): void
    {
        $response = HttpService::apiHttp()
            ->post('credit-notes/store-using-bill', ['bill_number' => $bill->number])
            ->json();

        $bill->electronicCreditNote()->create([
            'number' => $response['data']['credit_note']['number'],
        ]);
    }

    /**
     * Envia la nota credito a la DIAN
     */
    public static function validateCreditNote(Bill $bill): Response
    {
        $bill = $bill->fresh();
        $response = HttpService::apiHttp()->post('credit-notes/send/'.$bill->electronicCreditNote->number);

        return $response;
    }

    /**
     * Guarda los datos de la nota credito validada en la base de datos
     */
    public static function saveCreditNote(array $data, Bill $bill): void
    {
        $electronicCreditNote = $data['data']['credit_note'];

        $bill->electronicCreditNote()->update([
            'number' => $electronicCreditNote['number'],
            'qr_image' => $electronicCreditNote['qr_image'],
            'cude' => $electronicCreditNote['cude'],
            'is_validated' => true,
        ]);
    }
}
