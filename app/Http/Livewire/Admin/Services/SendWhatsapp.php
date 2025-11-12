<?php

namespace App\Http\Livewire\Admin\Services;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Support\Facades\View;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use App\Models\ServiceNotification;
class SendWhatsapp extends Component
{
    public $service;
    public $phone;
    public $message;
 
    public $openModal = false;

    protected $listeners = ['openWhatsappModal' => 'openModalWindow'];

    public function openModalWindow($serviceId)
    {
        $this->resetValidation();
        $this->service = Service::with('customer')->findOrFail($serviceId);
        $this->phone ='57' . $this->service->customer->phone ?? '';
        $this->openModal = true;
    }

    public function sendMessage()
    {
        $this->validate([
            'phone' => 'required|regex:/^57\d{10}$/',
        ], [
            'phone.required' => 'Debe ingresar un número de teléfono válido (+57...).',
            'phone.regex' => 'El número debe ser un celular colombiano válido (ej: 573001112233).',
        ]);

        $pdf = $this->createPDF($this->service, 'S');
                $filename = $this->service->id . '.pdf';
                $pdfPath = storage_path('app/temp/' . $filename);
               Storage::disk('public')->put('temp/' . $filename, $pdf);

                // Generamos la URL pública
                $pdfUrl = asset('storage/temp/' . $filename);
                //http://127.0.0.1:8000/storage/temp/17.pdf

        try {
           /* $response = Http::withToken(env('WHATSAPP_TOKEN'))
                ->post(env('WHATSAPP_URL') . "/messages", [
                    "messaging_product" => "whatsapp",
                    "to" => $this->phone,
                    "type" => "template",
                    "template" => [
                        "name" => "service_notification",
                        "language" => ["code" => "es_ES"],
                        "components" => [
                            [
                                "type" => "body",
                                "parameters" => [
                                    ["type" => "text", "text" => $this->service->customer->names],
                                    ["type" => "text", "text" => $this->service->id],
                                ],
                            ],
                            [
                                "type" => "button",
                                "sub_type" => "url",
                                "index" => "0", // primer botón
                                "parameters" => [
                                    ["type" => "text", "text" => $this->service->id], // {{1}} en la URL del botón
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                throw new \Exception($response->body());
            }*/
             ServiceNotification::create([
                'service_id' => $this->service->id,
                'channel' => 'whatsapp',
                'destination' => $this->phone,
                'message' => 'Detalle de servicio #' . $this->service->id,
                'status' => 'enviado',
            ]);
            $this->emitTo('admin.services.edit', 'refreshNotifications');
            $this->emit('success', 'Mensaje enviado correctamente por WhatsApp.');
            $this->openModal = false;
        } catch (\Exception $e) {
            Log::error('Error enviando WhatsApp: ' . $e->getMessage());
            $this->emit('error', 'No se pudo enviar el mensaje: ' . $e->getMessage());
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
        return view('livewire.admin.services.send-whatsapp');
    }
}
