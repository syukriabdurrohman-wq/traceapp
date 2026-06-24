<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class ReportPdfService
{
    public function render(array $bundle): string
    {
        $options = new Options();
        $options->set('dpi', 150);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html   = view('Reports/PdfTemplate', ['bundle' => $bundle]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }
}
