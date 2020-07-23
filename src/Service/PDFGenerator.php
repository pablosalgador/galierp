<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFGenerator{

  public function presupuestoAPDF($html)
  {

    $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Helvetica');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

  }

}
