<?php

namespace App\Traits;

use App\Models\NumberingRange;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;

trait UtilityTrait {

    public function initMPdf(): Mpdf {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $pdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                base_path('public/fonts/roboto/'),
            ]),
            'fontdata' => $fontData + [
                'roboto' => [
                    'R' => 'Roboto-Regular.ttf',
                    'B' => 'Roboto-Bold.ttf',
                    'I' => 'Roboto-Italic.ttf',
                ]
            ],
            'default_font' => 'roboto',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_header' => 5,
            'margin_footer' => 2
        ]);

        $pdf->WriteHTML(file_get_contents(base_path('resources/views/pdf/styles.css')), HTMLParserMode::HEADER_CSS);
        return $pdf;
    }

    protected function initMPdfTicket($height): Mpdf {

        $width = session('config')->width_ticket;

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $pdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                base_path('public/fonts/roboto/'),
            ]),
            'fontdata' => $fontData + [
                'roboto' => [
                    'R' => 'Roboto-Regular.ttf',
                    'B' => 'Roboto-Bold.ttf',
                    'I' => 'Roboto-Italic.ttf',
                ]
            ],
            'default_font' => 'roboto',
            'margin_left' => 3,
            'margin_right' => 3,
            'margin_top' => 10,
            'margin_bottom' => 20,
            'margin_header' => 3,
            'margin_footer' => 8,
            'format' => [$width, $height],
            'dpi' => 96

        ]);

        $pdf->WriteHTML(file_get_contents(base_path('resources/views/pdf/styles.css')), HTMLParserMode::HEADER_CSS);

        return $pdf;
    }
}
