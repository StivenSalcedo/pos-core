<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Terminal;
use App\Services\CompanyService;
use App\Traits\UtilityTrait;
use Illuminate\Support\Facades\View;
use Mpdf\HTMLParserMode;

class BillController extends Controller
{
    use UtilityTrait;

    protected function createDPF(Bill $bill, $dest)
    {

        $company = session('config');

        $range = $bill->numberingRange;

        if ($company->type_bill === '0') {
            $pdf = $this->initMPdf();
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make('pdf.bill.footer'));
            $pdf->WriteHTML(View::make('pdf.bill.template', compact('company', 'bill', 'range')), HTMLParserMode::HTML_BODY);
        } else {
            $height = $this->getHeigth($bill->details, $range);
            $pdf = $this->initMPdfTicket($height);
            $pdf->SetHTMLFooter(View::make('pdf.ticket.footer'));
            $pdf->WriteHTML(View::make('pdf.ticket.template', compact('company', 'bill', 'range')), HTMLParserMode::HTML_BODY);
        }

        $pdf->SetTitle('Factura '.$bill->number);

        return $pdf->Output('Factura '.$bill->number.'.pdf', $dest);
    }

    public function show(Bill $bill)
    {
        return $this->createDPF($bill, 'I');
    }

    public function download(Bill $bill)
    {
        return $this->createDPF($bill, 'D');
    }

    public function getBillBase64($bill_id)
    {
        $bill = Bill::find($bill_id);

        return base64_encode($this->createDPF($bill, 'S'));
    }

    /**
     * * Calcula el tamaño de la factura
     * ! Esta funcion solo sirve con las impresoras superiores a 80cm
     */
    protected function getHeigth($details, $range)
    {
        $oneLine = 0;
        $twoLines = 0;

        foreach ($details as $value) {
            if (strlen($value->name) > 27) {
                $twoLines++;
            } else {
                $oneLine++;
            }
        }

        $heightDefault = $range->resolution_number ? 190 : 170;

        $heightOneLine = $oneLine > 7 ? 4.5 : 8;

        return ((int) ($oneLine * $heightOneLine)) + ((int) ($twoLines * 7.5)) + $heightDefault;
    }

    /**
     * Este funcion devuelve la informacion de la factura para la impresion en el frontend
     */
    public function getBill(Bill $bill)
    {
        $customer = $bill->customer;
        $range = $bill->numberingRange;
        $products = $bill->details->transform(fn ($item) => $item->only(['name', 'amount', 'total']));
        $company = CompanyService::companyData();
        $terminal = Terminal::findOrFail($bill->terminal_id);

        $company['name']=!empty($terminal->name) ? $terminal->name : $company['name'];
        $company['direction']=!empty($terminal->address) ? $terminal->address: $company['direction'];
        $company['phone']=!empty($terminal->phone) ? $terminal->phone :  $company['phone'];

        $data = [

            'is_electronic' => $bill->isElectronic,
            'company' => $company,
            'customer' => [
                'identification' => $customer->no_identification,
                'names' => $customer->names,
            ],
            'bill' => [
                'cash' => $bill->cash,
                'change' => $bill->change,
                'format_created_at' => $bill->format_created_at,
                'discount' => $bill->discount,
                'tip' => $bill->tip,
                'number' => $bill->number,
                'subtotal' => $bill->subtotal,
                'total' => $bill->total,
                'final_total' => $bill->final_total,
                'user_name' => $bill->user->name,
                'payment_method' => $bill->paymentMethod->name,
            ],
            'products' => $products,
            'range' => [
                'prefix' => $range->prefix,
                'from' => $range->from,
                'to' => $range->to,
                'resolution_number' => $range->resolution_number,
                'date_authorization' => $range->format_date_authorization,
            ],
            'taxes' => $bill->documentTaxes->map(function ($item) {
                return [
                    'tribute_name' => $item->tribute_name,
                    'tax_amount' => $item->tax_amount,
                ];
            }),
        ];

        if ($bill->isElectronic) {
            $data['range'] = $bill->electronicBill->numbering_range;
            $data['electronic_bill'] = $bill->electronicBill->toArray();
        }

        return response()->json(['data' => $data]);
    }
}
