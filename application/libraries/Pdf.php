 <?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/third_party/tcpdf/tcpdf.php';

class Pdf extends TCPDF { 
    function __construct(){ 
        parent::__construct(); 
    }
    
    // Page header
    public function Header()
    {
        // Logo
        $image_file = APP_ADMIN_ASSETS_IMG.'logo.png';
        $this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        // Set fonts for the table header
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, 'Sales Information for Products', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }//End Function

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }//End Function

    // This function is the main contant
    public function mainContent()
    {
        // Position at 20 mm form the top and bottom to buttons
        $this->SetYZ(-20);
        // Set fonts
        $this->SetFont('helvetica','I',8);
        // Page number
        $this->cell(0, 10, 'Page ');
    }//End Function
}//End CLass
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */
?>


