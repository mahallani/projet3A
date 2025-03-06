<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $domPdf;

    public function __construct()
    {
        $this->domPdf = new Dompdf();
        
        // Configuration des options
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->setIsRemoteEnabled(true);
        
        $this->domPdf->setOptions($options);
    }

    public function generatePdf($html): string
    {
        // Chargement du HTML
        $this->domPdf->loadHtml($html);
        
        // Configuration du papier
        $this->domPdf->setPaper('A4', 'portrait');
        
        // Rendu du PDF
        $this->domPdf->render();
        
        // Retourne le contenu du PDF
        return $this->domPdf->output();
    }
}