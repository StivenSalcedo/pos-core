<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailySale;
use App\Traits\UtilityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mpdf\HTMLParserMode;

class DailySaleController extends Controller
{
    use UtilityTrait;

    protected $query = null;

    public function __construct()
    {
        if (request()->exists('filterDate') && request()->exists('startDate') && request()->exists('endDate')) {
            $this->query = DailySale::query()
                ->orderBy('id', 'DESC')
                ->date(request('filterDate'), request('startDate'), request('endDate'));
        }
    }

    protected function createDPF(DailySale $dailySale, $dest)
    {
        $company = session('config');

        $pdf = $this->initMPdf();
        $pdf->setFooter('{PAGENO}');
        $pdf->SetHTMLFooter(View::make("pdf.daily-sale.footer"));
        $pdf->WriteHTML(View::make("pdf.daily-sale.template", compact('dailySale', 'company')), HTMLParserMode::HTML_BODY);

        $pdf->SetTitle('Venta diaria');


        return $pdf->Output('Venta diaria.pdf', $dest);
    }

    protected function createDPF2($dest)
    {
        $company = session('config');
        $collectDailySales = $this->query->get();
        $startDate = $collectDailySales->last()->format_creation_date;
        $endDate = $collectDailySales->first()->format_creation_date;

        $pdf = $this->initMPdf();

        $pdf->setFooter('{PAGENO}');
        $pdf->SetHTMLFooter(View::make("pdf.daily-sale.footer"));
        $dailySales = $collectDailySales->take(21);
        $page=$pdf->page;
        $pdf->WriteHTML(View::make("pdf.daily-sale.template2", compact('page', 'dailySales', 'company', 'startDate', 'endDate')), HTMLParserMode::HTML_BODY);

        $collectDailySales = $collectDailySales->splice(20);
        $chunks = $collectDailySales->chunk(32);


        foreach ($chunks as $key => $dailySales) {
            $page=$pdf->page;
            $pdf->AddPage();
            $pdf->WriteHTML(View::make("pdf.daily-sale.template2", compact('page','dailySales', 'company', 'startDate', 'endDate')), HTMLParserMode::HTML_BODY);
        }

        $pdf->SetTitle('Venta diaria');

        return $pdf->Output('Venta diaria.pdf', $dest);
    }

    public function show(DailySale $dailySale)
    {
        if ($this->query) {
            return $this->createDPF2('I');
        }

        return $this->createDPF($dailySale, 'I');
    }

    public function download(DailySale $dailySale)
    {
        return $this->createDPF($dailySale, 'D');
    }
}
