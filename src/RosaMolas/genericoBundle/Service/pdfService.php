<?php

namespace RosaMolas\genericoBundle\Service;



class pdfService extends \FPDF
{
    public function Header()
    {
        // Logo

        $this->Image($this->headerImage,10,10,18);

        // Arial bold 15
        $this->SetFont($this->headerfontFamily,$this->headerfontType ,$this->headerfontSize);
        // Title
        foreach($this->headerTitle as $title){
            $this->Cell(23);
            $this->MultiCell($this->GetPageWidth()-73, 5,$title,0,'L');
        }
        if(!empty($this->headerTopRight)){
            $this->SetY(10);
            $this->SetX(-50);
            $this->Cell(40, 5, iconv('utf-8', 'windows-1252', 'Página '.$this->PageNo()));
            $this->Ln(5);
            foreach($this->headerTopRight as $TopRight){
                $this->SetX(-50);
                $this->MultiCell(40, 5,$TopRight,0,'L');
            }
        }
        // Line break
        $this->Ln(5);
    }

// Page footer
    public function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
//        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    private $headerImage;
    private $headerfontFamily = 'Arial';
    private $headerfontType = 'B';
    private $headerfontSize = 10;
    private $headerTitle = 'Title';
    private $headerTopRight;

    public function setHeaderImage($headerImage){
        $this->headerImage = $headerImage;
    }
    public function setHeaderfontFamily($headerfontFamily){
        $this->headerfontFamily = $headerfontFamily;
    }
    public function setHeaderfontType($headerfontType){
        $this->headerfontType = $headerfontType;
    }
    public function setHeaderfontSize($headerfontSize){
        $this->headerfontSize = $headerfontSize;
    }
    public function setheaderTitle($headerTitle){
        $this->headerTitle = $headerTitle;
    }
    public function setHeaderTopRight($headerTopRight){
        $this->headerTopRight = $headerTopRight;
    }

}
