<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Purchase;
use App\Traits\UtilityTrait;
use Illuminate\Support\Facades\View;
use Mpdf\HTMLParserMode;

class PurchaseController extends Controller {

    use UtilityTrait;

    public function show(Purchase $purchase) {

        $company = session('config');

        if ($company->type_bill === '0') {
            $pdf = $this->initMPdf();
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make("pdf.purchase.footer"));
            $pdf->WriteHTML(View::make("pdf.purchase.template", compact('purchase', 'company')), HTMLParserMode::HTML_BODY);
        }else{
            $pdf = $this->initMPdfTicket(130);
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make("pdf.purchase-ticket.footer"));
            $pdf->WriteHTML(View::make("pdf.purchase-ticket.template", compact('purchase', 'company')), HTMLParserMode::HTML_BODY);
        }

        return $pdf->Output('N° Compra-' . $purchase->id . '.pdf', 'I');
    }

    public function download(Purchase $purchase){

        $company = session('config');

        if ($company->type_bill === '0') {
            $pdf = $this->initMPdf();
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make("pdf.purchase.footer"));
            $pdf->WriteHTML(View::make("pdf.purchase.template", compact('purchase', 'company')), HTMLParserMode::HTML_BODY);
        }else{
            $pdf = $this->initMPdfTicket(130);
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make("pdf.purchase-ticket.footer"));
            $pdf->WriteHTML(View::make("pdf.purchase-ticket.template", compact('purchase', 'company')), HTMLParserMode::HTML_BODY);
        }

        return $pdf->Output('N° Compra-' . $purchase->id . '.pdf', 'D');
    }
}
