<?php

namespace App\Http\Livewire\Admin\Services;

use Livewire\Component;
use App\Models\Service;
use App\Models\ServiceNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;

class SendEmail extends Component
{
    public $serviceId;
    public $service;
    public $emailTo;
    public $emailMessage = '';
    public $attachPdf = true;
    public $open = false;

    protected $rules = [
        'emailTo' => 'required|email',
        'emailMessage' => 'nullable|string|max:1000',
        'attachPdf' => 'boolean',
    ];

    protected $listeners = [
        'open-send-email' => 'openModal',
    ];

    public function openModal($serviceId)
    {
        $this->serviceId = $serviceId;
        $this->service = Service::with('customer', 'equipmentType', 'brand', 'state')->findOrFail($serviceId);

        $this->emailTo = $this->service->customer?->email ?? '';
        $this->emailMessage = '';
        $this->attachPdf = true;
        $this->open = true;
    }

    public function sendEmail()
    {
        $this->validate();

        try {
            $pdfPath = null;
            if ($this->attachPdf) {
                $pdf = $this->createPDF($this->service, 'S');
                $filename = $this->service->id . '.pdf';
                $pdfPath = storage_path('app/temp/' . $filename);
                Storage::disk('public')->put('temp/' . $filename, $pdf);

                // Generamos la URL pública
                $pdfUrl = asset('storage/temp/' . $filename);
                //http://127.0.0.1:8000/storage/temp/17.pdf
            }

             Mail::send('emails.service-notification', [
                'service' => $this->service,
                'messageBody' => $this->emailMessage,
            ], function ($message) use ($pdfUrl) {
                $message->to($this->emailTo)
                        ->subject('Detalle del servicio #' . $this->service->id);

                if ($this->attachPdf && $pdfUrl) {
                    $message->attach($pdfUrl);
                }
            });

            ServiceNotification::create([
                'service_id' => $this->service->id,
                'channel' => 'email',
                'destination' => $this->emailTo,
                'message' => 'Detalle de servicio #' . $this->service->id . ':' . $this->emailMessage,
                'status' => 'enviado',
            ]);

            $this->emitTo('admin.services.edit', 'refreshNotifications');
            $this->emit('success', 'Correo enviado correctamente');
            $this->open = false;
        } catch (\Exception $e) {
            $this->emit('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }

    protected function createPDF(Service $service, $dest)
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

        $pdf = new Mpdf(['format' => 'A4']);
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
            'saldo'     => $balance,
        ]), HTMLParserMode::HTML_BODY);
        // $pdf->WriteHTML(View::make('pdf.service.template-detail', compact('company', 'service')), HTMLParserMode::HTML_BODY);
        $pdf->SetTitle('Servicio ' . $service->id);
        return $pdf->Output('Servicio_' . $service->id . '.pdf', $dest);
    }

    public function render()
    {
        return view('livewire.admin.services.send-email');
    }
}
