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
        // Variables acumuladas
        $subtotal = 0;
        $discount = 0;
        $totalTax = 0;
        $totalPaid = 0;

        foreach ($service->products as $item) {
            $quantity = (float) $item->quantity;
            $discountValue = (float) ($item->discount ?? 0);
            $unitPrice = (float) ($item->unit_price - ($discountValue / $quantity));
            // Valor fijo, no porcentaje

            // Obtener el impuesto principal del producto (si tiene varios, tomamos el primero)
            $taxData = $item->product->taxRates->first();
            $taxRateValue = 0;
            $taxIsPercent = true;

            if ($taxData) {
                $taxRateValue = (float) $taxData->rate;
                $taxIsPercent = (bool) $taxData->has_percentage;
            }

            // Desglose de IVA incluido
            $baseUnit = $unitPrice;
            $ivaUnit = 0;

            if ($taxIsPercent && $taxRateValue > 0) {
                // IVA incluido (ej. 19%)
                $baseUnit = $unitPrice / (1 + ($taxRateValue / 100));
                $ivaUnit  = $unitPrice - $baseUnit;
            } elseif (!$taxIsPercent && $taxRateValue > 0) {
                // IVA fijo (ej. $800 por unidad)
                $baseUnit = $unitPrice - $taxRateValue;
                $ivaUnit  = $taxRateValue;
            }

            // Totales por línea
            $lineBase = $baseUnit * $quantity;
            $lineTax  = $ivaUnit * $quantity;
            // Acumular totales
            $subtotal += $lineBase;
            $discount += $discountValue;
            $totalTax += $lineTax;
        }

        // Pagos realizados
        $totalPaid = $service->payments->sum('amount');

        // Total general
        $total = $subtotal + $totalTax;
        $balance = $total - $totalPaid;

        $pdf = $this->initMPdf();
        $pdf->setFooter('{PAGENO}');
        $pdf->SetHTMLFooter(View::make('pdf.service.footer'));
        $pdf->WriteHTML(View::make('pdf.service.template-detail', [
            'company'   => $company,
            'service'   => $service,
            'subtotal'  => $subtotal,
            'discount'  => $discount,
            'iva'       => $totalTax,
            'total'     => $total,
            'pagado'    => $totalPaid,
            'saldo'     => $balance
        ]), HTMLParserMode::HTML_BODY);
        $pdf->SetTitle('Servicio ' . $service->id);
        return $pdf->Output('Servicio ' . $service->id . '.pdf', $dest);
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
    public function getService(Service $service)
    {
        $service->load([
            'products.product', // 👈 trae el componente dentro de cada detalle
            'customer',
            'responsible',
            'equipmentType',
            'brand',
            'state',
            'payments.paymentMethod',
        ]);
        $company = CompanyService::companyData();

         // Variables acumuladas
        $subtotal = 0;
        $discount = 0;
        $totalTax = 0;
        $totalPaid = 0;
        $paymentsTotal=0;

        foreach ($service->products as $item) {
            $quantity = (float) $item->quantity;
            $discountValue = (float) ($item->discount ?? 0);
            $unitPrice = (float) ($item->unit_price - ($discountValue / $quantity));
            // Valor fijo, no porcentaje

            // Obtener el impuesto principal del producto (si tiene varios, tomamos el primero)
            $taxData = $item->product->taxRates->first();
            $taxRateValue = 0;
            $taxIsPercent = true;

            if ($taxData) {
                $taxRateValue = (float) $taxData->rate;
                $taxIsPercent = (bool) $taxData->has_percentage;
            }

            // Desglose de IVA incluido
            $baseUnit = $unitPrice;
            $ivaUnit = 0;

            if ($taxIsPercent && $taxRateValue > 0) {
                // IVA incluido (ej. 19%)
                $baseUnit = $unitPrice / (1 + ($taxRateValue / 100));
                $ivaUnit  = $unitPrice - $baseUnit;
            } elseif (!$taxIsPercent && $taxRateValue > 0) {
                // IVA fijo (ej. $800 por unidad)
                $baseUnit = $unitPrice - $taxRateValue;
                $ivaUnit  = $taxRateValue;
            }

            // Totales por línea
            $lineBase = $baseUnit * $quantity;
            $lineTax  = $ivaUnit * $quantity;
            // Acumular totales
            $subtotal += $lineBase;
            $discount += $discountValue;
            $totalTax += $lineTax;
        }

         foreach ($service->payments as $item) {
             $paymentsTotal += $item->amount;
         }

       

        // Total general
        $total = $subtotal + $totalTax;
       

        // Agregar los campos calculados directamente al objeto Service
        $service->subtotal = round($subtotal, 2);
        $service->discount = round($discount, 2);
        $service->total    = round($total, 2);
        $service->iva    = round($totalTax, 2);
        $service->paymentsTotal= round($paymentsTotal,2);
        $data = [
            'is_electronic' => $service->isElectronic,
            'company' => $company,
            'service' => $service,
        ];

         if ($service->isElectronic) {
            $data['range'] = $service->electronicBill->numbering_range;
            $data['electronic_bill'] = $service->electronicBill->toArray();
        }


        return response()->json(['data' => $data]);
    }
}
