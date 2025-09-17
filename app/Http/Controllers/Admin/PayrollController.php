<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Payroll;
use App\Traits\UtilityTrait;
use Illuminate\Http\Request;
use Mpdf\HTMLParserMode;
use Illuminate\Support\Facades\View;


class PayrollController extends Controller {

    use UtilityTrait;

    public function show(Payroll $payroll) {

        $company = session('config');

        $pdf = $this->initMPdf();
        $pdf->setFooter('{PAGENO}');
        $pdf->SetHTMLFooter(View::make("pdf.payroll.footer"));
        $pdf->WriteHTML(View::make("pdf.payroll.template", compact('payroll', 'company')), HTMLParserMode::HTML_BODY);

        return $pdf->Output('N° Pago-' . $payroll->id . '.pdf', 'I');
    }

    public function download(Payroll $payroll){
        
        $company = session('config');

        $pdf = $this->initMPdf();
        $pdf->setFooter('{PAGENO}');
        $pdf->SetHTMLFooter(View::make("pdf.payroll.footer"));
        $pdf->WriteHTML(View::make("pdf.payroll.template", compact('payroll', 'company')), HTMLParserMode::HTML_BODY);

        return $pdf->Output('N° Pago-' . $payroll->id . '.pdf', 'D');
    }
}
