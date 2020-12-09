<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Pdfset extends Common_Front_Controller {

  /**
   * load list modal 
   */
  function __Construct(){
    parent::__Construct();
    $this->load->library('pdf');
  }
  function download(){
    $this->load->model('jobs/job_model');
    $jobId      = decoding($this->uri->segment(3)); 
    $where      = array('jobId'=>$jobId);
    $job        = $this->job_model->jobDetail($jobId);
    $questions  = $this->job_model->jobTypeQuetions($job['jobId'],$job['jobTypeId']);
      ob_start();
    // create new PDF document
    $pdf        = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(SITE_NAME);
    $pdf->SetTitle(SITE_NAME.'-'.$job['jobName']);
    $pdf->SetSubject(SITE_NAME.'-'.$job['jobName']);
    $pdf->SetKeywords(SITE_NAME.','.$job['jobName'].',Job Information');
    // set default header data
    //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'', PDF_HEADER_STRING);
    //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'', PDF_HEADER_STRING);
    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
       require_once(dirname(__FILE__).'/lang/eng.php');
       $pdf->setLanguageArray($l);
    }
    // ---------------------------------------------------------
    // set font
    $pdf->SetFont('helvetica', 'N', 10);
    // add a page
    $pdf->AddPage();
    // print a line
    $pdf->Cell(0, 12, 'JOB DETAILS',0, 0, 'C');
    $pdf->Ln(5);
    $pdf->Ln(5);
    $pdf->Write(0, 'Date: '. date('d/m/Y') , '', 0, 'L', false, 0, true, false, 0);
    // Logged in username
    $userName = $_SESSION[ADMIN_USER_SESS_KEY]['fullName'];
    $pdf->Write(0, 'By: '.$userName, '', 0, 'R', true, 0, false, false, 0);
    $pdf->Ln(5);  
    $pdf->SetFont('helvetica', '', 9);
    // --------------------------------------
    $content        = '';
    $showbtn        = false;
    $labelShow      = "";
    switch ($job['jobStatus']) {
      case 0:
        $labelShow  = '<label color="red">Open</label>';
        break;
      case 1:
        $labelShow  = '<label color="blue">In Progress</label>';
        break;
      case 2:
        $labelShow  = '<label color="green">Completed</label>';
        break;
      default:
        break;
    }
    $workPriority     = "";
    switch ($job['workPriority']) {
      case 0:
        $workPriority = '<label color="green" >'.$job['priority'].'</label>';
        break;
      case 1:
        $workPriority = '<label color="blue">'.$job['priority'].'</label>';
        break;
      case 2:
        $workPriority = '<label color="red">'.$job['priority'].'</label>';
        break;
      
      default:
        break;
    }
   // $content .= '<table bgcolor="#cccccc" border="0" cellspacing="1" cellpadding="4">';
    $content .= '<table  border="0" cellspacing="1" cellpadding="4" bgcolor="#EAECF0">';
    $content .= '<tr  bgcolor="#cccccc"><th align="left" colspan="4"><b>Basic Information</b></th></tr>';
    $content .= '<tr bgcolor="#EAECF0">';
    $content .= '<td><strong>Job Name</strong> :</td><td>'.$job['jobName'].'</td>';
    $content .= '<td><strong>Job Type</strong> :</td><td>'.$job['jobType'].'</td>';
    $content .= '</tr>';  
    $content .= '<tr bgcolor="#EAECF0">';
    $content .= '<td><strong>Job Status</strong> :</td><td><span style="font-size: medium;"><b>'.$labelShow.'</b></span></td>';
    $content .= '<td><strong>Creation Date</strong> :</td><td>'.date("d/m/Y",strtotime($job['startDate']))." ".$job['startTime'].'</td>';
    $content .= '</tr>';

    /*$content .= '<tr bgcolor="#EAECF0">';
    $content .= '<td><strong>Work Priority</strong> :</td><td><span style="font-size: medium;"><b>'.$workPriority.'</b></span></td>';
    $content .= '<td></td><td></td>';
    $content .= '</tr>';  */

    $content .= '<tr bgcolor="#EAECF0">';
    $content .= '<td><strong>Customer Name</strong> :</td><td>'.$job['customerName'].'</td>';
    $content .= '<td><strong>Driver Name</strong> :</td><td>'.$job['driverName'].'</td>'; 
    $content .= '</tr>';
    $content .= '<tr bgcolor="#EAECF0">';
    $content .= '<td><strong>Address</strong> :</td><td colspan="3"><strong>'.$job['address'].'</strong></td>';
    $content .= '</tr>';
    $content .= '</table>';
    $content .= '<table  border="0" cellspacing="1" cellpadding="4">';
    $content .= '<tr  bgcolor="#cccccc"><th align="left" colspan="2"><b>BEFORE WORK</b></th><th align="left" colspan="2"><b>AFTER WORK</b></th></tr>';
    /*$content .= '<tr bgcolor="#EAECF0">';
    $content .= '<td colspan="2"><strong>Job Work Time Duration </strong> :</td><td colspan="2">'.$job['timeDuration'].' (<b>'.$labelShow.'</b>)</td>';
    $content .= '</tr>';*/
    $content .= '<tr bgcolor="#EAECF0">';
    if(!empty($job['jobReport'])):
      $reports  = json_decode($job['jobReport'],true);
      $before   = isset($reports['beforeWork']) ? $reports['beforeWork']:array();
      $after    = isset($reports['afterWork']) ? $reports['afterWork']:array();   
      if(!empty($before)):
        $content .='<td colspan="2"><p><strong>Job Start </strong><span align="right" >&nbsp;&nbsp;'.date("d/m/Y H:i A",strtotime($before['startDateTime'])).'</span></p><p><strong>Work image</strong></p><div><p>';
        $bworkImage = (isset($before['workImage'])&& !empty($before['workImage'])) ? $before['workImage']:array();
        for ($i=0; $i <sizeof($bworkImage) ; $i++) {
          $image1   = S3JOBS_URL.$before['workImage'][$i];
          $content .= '<img src="'.$image1.'" alt="" width="95" height="95" border="0" />&nbsp;';
        }
        $content .='</p></div><p><strong>Comments </strong></p><p align="left" >&nbsp;&nbsp;'.$before['comments'].'</p><p align="right"><img src="'.S3JOBS_URL.$before['driverSignature'].'" alt="" width="90" height="90" border="0" /></p><p align="right"><b>Driver Signature</b></p></td>';
      else:
        $content .='<td colspan="2" align="center"> No record found</td>';
      endif;  
      if(!empty($after)):
        $content .='<td colspan="2"><p><strong>Job End </strong><span align="right" >&nbsp;&nbsp;'.date("d/m/Y H:i A",strtotime($after['endDateTime'])).'</span></p><p><strong>Work image</strong></p><div><p>';
        $aworkImage = (isset($after['workImage'])&& !empty($after['workImage'])) ? $after['workImage']:array();
        for ($j=0; $j <sizeof($aworkImage) ; $j++) {
          $image    = S3JOBS_URL.$after['workImage'][$j];
          $content .= '<img src="'.$image.'" alt="" width="95" height="95" border="0" />&nbsp;';
        }
        $content .='</p></div><p><strong>Comments </strong></p><p align="left" >&nbsp;&nbsp;'.$after['comments'].'</p><p align="right"><img src="'.S3JOBS_URL.$after['customerSignature'].'" alt="" width="90" height="90" border="0" /></p><p align="right"><b>Customer Signature</b></p></td>';
             else:
        $content .= '<td colspan="2" align="center"> No record found</td>';
      endif;       
    else:
      $content .= '<td colspan="4" align="center"> No record found</td>';   
    endif;
    $content .= '</tr>'; 
    $content .= '</table>';
    /*questions manage*/     
    if(!empty($questions)){ 
      $content .= '<table  border="0" cellspacing="1" cellpadding="4">';
      $content .= '<tr  bgcolor="#cccccc"><th align="left" colspan="4" ><b>JOB CHECKLIST</b></th></tr>';
      foreach ($questions as $key => $question) {
        $content .= '<tr bgcolor="#EAECF0">';
        $content .= '<td colspan="4"><p><strong>Question : '.$question->question.'</strong></p><p><strong>Answer :</strong> '.(!empty($question->answer) ? $question->answer :"NA").'</p>';
        $content .= '</td>';
        $content .= '</tr>';
      }
      $content .= '</table>';
    }
    /*questions manage*/
    /*questions manage*/
    /*tracking*/
    if(!empty($job['geoTimeDuration'])): $geoTimeDuration = $job['geoTimeDuration']; if(!empty($geoTimeDuration['timinig'])){

      $content .= '<table  border="0" cellspacing="1" cellpadding="4">';
      $content .= '<tr  bgcolor="#cccccc"><th align="left" colspan="4"><b>JOB WORK TIME (Geo Fencing Tracking)</b></th></tr>';
            $content .= '<tr  bgcolor="#cccccc"><th align="left"><b>In Time</b></th><th align="left"><b>Out Time</b></th><th align="right" colspan="2"><b>Total Time</b></th></tr>';

      foreach ($geoTimeDuration['timinig'] as $y => $v) {
        $content .= '<tr bgcolor="#EAECF0">';
          // $content .= '<td>'.($y+1).'</td>';
          $content .= '<td>'.date('d/m/Y H:i A',strtotime($v->startTime)).'</td>';
          $content .= '<td>'.(($v->endTime!='Progress') ?date('d/m/Y H:i A',strtotime($v->endTime)):$v->endTime).'</td>';
          $content .= '<td colspan="2" align="right">'.((($v->endTime!='Progress') && !empty($v->timeDuration))? $v->timeDuration:"NA").'</td>';
        // $content .= '<td ><strong>Job Work Time Duration </strong> :</td><td>'.$job['timeDuration'].' (<b>'.$labelShow.'</b>)</td>';
        $content .= '</tr>';
             
      }
      $content .= '<tr  bgcolor="#cccccc"><th align="left" colspan="2"><b>Total Time Duration</b></th><th align="right" colspan="2"><b>'.$geoTimeDuration['total'].'</b></th></tr>';
      $content .='</table>';
    }
  endif;
        /*tracking*/
  
        if($job['geoFencing']==1){
          /*Geo fencing manage*/
          $content .= '<table  border="0" cellspacing="1" cellpadding="4">';
            $content .= '<tr  bgcolor="#cccccc"><th align="left" colspan="4" ><b>GEO FENCING</b></th></tr>';
            $content .= '<tr>';
              $content .= '<td colspan="4"><strong>Geo image :</strong>';
              $content .= '</td>';
            $content .= '</tr>';
            $content .= '<tr>';
              $content .= '<td colspan="4">';
                $content .= '<img src="'.$job['geoFencingUrl'].'" alt="Map" border="0" />';
              $content .= '</td>';
            $content .= '</tr>';
          $content .= '</table>';
          /*Geo fencing manage*/
        }
      $pdf->writeHTML($content, true, false, true, false, '');
      // reset pointer to the last page
      $pdf->lastPage();
      $fileName = SITE_NAME."-".$job['jobName'].strtotime(date("Y-m-d H:i:s")).".pdf";
      // $pdf->Output($fileName, 'I');
      // $pdf->Output($fileName,'D');
      $pdf->Output($fileName, 'D');
      ob_end_flush();
      //Close and output PDF document
      //============================================================+
      // END OF FILE
      //============================================================+  
	}//ENd FUnction
}//End Class
?>
