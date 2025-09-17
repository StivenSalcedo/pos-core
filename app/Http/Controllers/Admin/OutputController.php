<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Output;
use App\Traits\UtilityTrait;
use Illuminate\Http\Request;
use Mpdf\HTMLParserMode;
use Illuminate\Support\Facades\View;

class OutputController extends Controller {

    use UtilityTrait;

    public function show(Output $output) {

        $company = session('config');

        if ($company->type_bill === '0') {
            $pdf = $this->initMPdf();
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make("pdf.output.footer"));
            $pdf->WriteHTML(View::make("pdf.output.template", compact('output', 'company')), HTMLParserMode::HTML_BODY);
        }else{
            $pdf = $this->initMPdfTicket(130);
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make("pdf.output-ticket.footer"));
            $pdf->WriteHTML(View::make("pdf.output-ticket.template", compact('output', 'company')), HTMLParserMode::HTML_BODY);
        }

        return $pdf->Output('N° Pago-' . $output->id . '.pdf', 'I');
    }

    public function download(Output $output) {

        $company = session('config');

        if ($company->type_bill === '0') {
            $pdf = $this->initMPdf();
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make("pdf.output.footer"));
            $pdf->WriteHTML(View::make("pdf.output.template", compact('output', 'company')), HTMLParserMode::HTML_BODY);
        }else{
            $pdf = $this->initMPdfTicket(130);
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make("pdf.output-ticket.footer"));
            $pdf->WriteHTML(View::make("pdf.output-ticket.template", compact('output', 'company')), HTMLParserMode::HTML_BODY);
        }

        return $pdf->Output('N°-Recibo' . $output->id . '.pdf', 'D');
    }
}
