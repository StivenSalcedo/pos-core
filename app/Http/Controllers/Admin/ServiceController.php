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
        $pdf = $this->initMPdf();
        $pdf->setFooter('{PAGENO}');
        $pdf->SetHTMLFooter(View::make('pdf.service.footer'));
        $pdf->WriteHTML(View::make('pdf.service.template-detail', compact('company', 'service')), HTMLParserMode::HTML_BODY);
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
            'customer'
        ]);
        $company = CompanyService::companyData();

        // Calcular totales
        $subtotal = $service->products->sum(fn($p) => $p->unit_price * $p->quantity);
        $discount = $service->products->sum(fn($p) => $p->discount ?? 0);
        $total    = $subtotal - $discount;

        // Agregar los campos calculados directamente al objeto Service
        $service->subtotal = round($subtotal, 2);
        $service->discount = round($discount, 2);
        $service->total    = round($total, 2);

        $data = [
            'company' => $company,
            'service' => $service,
        ];


        return response()->json(['data' => $data]);
    }
}
