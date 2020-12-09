<?php 
class Wpdf extends TCPDF {
    public function Header() {
          // Logo
        $image_file     = APP_ADMIN_ASSETS_IMG.'logo.png';
        $this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        // Set fonts for the table header
        $this->SetFont('helvetica', 'B', 10);
        // Title
        $this->Cell(0, 15, 'CG ROBINSON`S', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        // Get the current page break margin
        $bMargin            = $this->getBreakMargin();
        // Get current auto-page-break mode
        $auto_page_break    = $this->AutoPageBreak;

        // Disable auto-page-break
        $this->SetAutoPageBreak(false, 0);

        // Define the path to the image that you want to use as watermark.
        // Render the image
        $this->Image($img_file, 0, 0, 223, 280, '', '', '', false, 300, '', false, false, 0);
        // Restore the auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // Set the starting point for the page content
        $this->setPageMark();
    }
    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
    // This function is the main contant
    public function mainContent()
    {
        // Position at 20 mm form the top and bottom to buttons
        $this->SetYZ(-20);
        // Set fonts
        $this->SetFont('helvetica','I',8);
        // Page number
        $this->cell(0, 10, 'Page ');
    }
}
?>