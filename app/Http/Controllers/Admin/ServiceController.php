<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Terminal;
use App\Services\CompanyService;
use App\Traits\UtilityTrait;
use Illuminate\Support\Facades\View;
use Mpdf\HTMLParserMode;

class ServiceController extends Controller
{
    use UtilityTrait;

    protected function createDPF(Service $service, $dest)
    {

        $company = session('config');

        

        //if ($company->type_bill === '0') {
            $pdf = $this->initMPdf();
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make('pdf.service.footer'));
            $pdf->WriteHTML(View::make('pdf.service.template-detail', compact('company', 'service')), HTMLParserMode::HTML_BODY);
       /* } else {
            $height = $this->getHeigth($service->details, $range);
            $pdf = $this->initMPdfTicket($height);
            $pdf->SetHTMLFooter(View::make('pdf.ticket.footer'));
            $pdf->WriteHTML(View::make('pdf.ticket.template', compact('company', 'bill', 'range')), HTMLParserMode::HTML_BODY);
        }*/

        $pdf->SetTitle('Servicio '.$service->id);

        return $pdf->Output('Servicio '.$service->id.'.pdf', $dest);
    }

    public function show(Service $service)
    {
        return $this->createDPF($service, 'I');
    }

    public function download(Service $service)
    {
        return $this->createDPF($service, 'D');
    }

    public function getBillBase64($service_id)
    {
        $service = Service::find($service_id);

        return base64_encode($this->createDPF($service, 'S'));
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
    public function getBill(Service $service)
    {
        $customer = $service->customer;
        $range = $service->numberingRange;
        $products = $service->details->transform(fn ($item) => $item->only(['name', 'amount', 'total']));
        $company = CompanyService::companyData();
        $terminal = Terminal::findOrFail($service->terminal_id);

        $company['name']=!empty($terminal->name) ? $terminal->name : $company['name'];
        $company['direction']=!empty($terminal->address) ? $terminal->address: $company['direction'];
        $company['phone']=!empty($terminal->phone) ? $terminal->phone :  $company['phone'];

        $data = [

            'is_electronic' => $service->isElectronic,
            'company' => $company,
            'customer' => [
                'identification' => $customer->no_identification,
                'names' => $customer->names,
            ],
            'bill' => [
                'cash' => $service->cash,
                'change' => $service->change,
                'format_created_at' => $service->format_created_at,
                'discount' => $service->discount,
                'tip' => $service->tip,
                'number' => $service->number,
                'subtotal' => $service->subtotal,
                'total' => $service->total,
                'final_total' => $service->final_total,
                'user_name' => $service->user->name,
                'payment_method' => $service->paymentMethod->name,
            ],
            'products' => $products,
            'range' => [
                'prefix' => $range->prefix,
                'from' => $range->from,
                'to' => $range->to,
                'resolution_number' => $range->resolution_number,
                'date_authorization' => $range->format_date_authorization,
            ],
            'taxes' => $service->documentTaxes->map(function ($item) {
                return [
                    'tribute_name' => $item->tribute_name,
                    'tax_amount' => $item->tax_amount,
                ];
            }),
        ];

        if ($service->isElectronic) {
            $data['range'] = $service->electronicBill->numbering_range;
            $data['electronic_bill'] = $service->electronicBill->toArray();
        }

        return response()->json(['data' => $data]);
    }
}
