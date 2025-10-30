<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashClosing;
use App\Models\Company;
use App\Models\Purchase;
use App\Traits\UtilityTrait;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Mpdf\HTMLParserMode;

class CashClosingController extends Controller {

    use UtilityTrait;

    public function show(CashClosing $cashClosing) {

       $company = session('config');
 
        if ($company->type_bill === '0') {
            $pdf = $this->initMPdf();
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make("pdf.cash-closing.footer"));
            $pdf->WriteHTML(View::make("pdf.cash-closing.template", compact('cashClosing')), HTMLParserMode::HTML_BODY);
        }else{
            $pdf = $this->initMPdfTicket(180);
            $pdf->setFooter('{PAGENO}');
            $pdf->SetHTMLFooter(View::make("pdf.cash-closing-ticket.footer"));
            $pdf->WriteHTML(View::make("pdf.cash-closing-ticket.template", compact('cashClosing')), HTMLParserMode::HTML_BODY);
        }

        return $pdf->Output('N° Cierre de caja-' . $cashClosing->id . '.pdf', 'I');
    }

}
