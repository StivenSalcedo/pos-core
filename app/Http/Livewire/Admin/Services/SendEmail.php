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
        $this->service = Service::with('customer')->findOrFail($serviceId);

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

            /* Mail::send('emails.service-notification', [
                'service' => $this->service,
                'messageBody' => $this->emailMessage,
            ], function ($message) use ($pdfPath) {
                $message->to($this->emailTo)
                        ->subject('Detalle del servicio #' . $this->service->id);

                if ($this->attachPdf && $pdfPath) {
                    $message->attach($pdfPath);
                }
            });*/

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
        $pdf = new Mpdf(['format' => 'A4']);
        $pdf->setFooter('{PAGENO}');
        $pdf->SetHTMLFooter(View::make('pdf.service.footer'));
        $pdf->WriteHTML(View::make('pdf.service.template-detail', compact('company', 'service')), HTMLParserMode::HTML_BODY);
        $pdf->SetTitle('Servicio ' . $service->id);
        return $pdf->Output('Servicio_' . $service->id . '.pdf', $dest);
    }

    public function render()
    {
        return view('livewire.admin.services.send-email');
    }
}
