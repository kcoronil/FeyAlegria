<?php

namespace RosaMolas\genericoBundle\Service;

use RosaMolas\facturacionBundle\Entity\DetalleFactura;
use RosaMolas\facturacionBundle\Entity\Factura;
use RosaMolas\genericoBundle\Entity\Pagos;
use RosaMolas\genericoBundle\Form\PagosType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;
use Test\inicialBundle\Entity\TrazaEventosUsuarios;


class pdfService extends \FPDF
{
    function Header()
    {
        // Logo
        $this->Image('logo.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,'Title \n test',1,0,'C');
        // Line break
        $this->Ln(20);
    }

// Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    private $headerImage;
    private $headerfontFamily;
    private $headerfontType;
    private $headerfontSize;

}
