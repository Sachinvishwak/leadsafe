<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_model extends CI_Model {
    var $column_sel = array('j.jobId',
        'j.jobName',
        'j.jobTypeId',
        'j.driverId',
        'c.fullName as customerName',
        'jt.jobType',
        'd.fullName as driverName',
        'j.customerId',
        'j.jobStatus',
        'j.startDate',
        'j.startTime',
        '(case when (j.jobStatus = 0) 
        THEN "Open" when (j.jobStatus = 1) 
        THEN "In-progress" when (j.jobStatus = 2) 
        THEN "Completed" ELSE
        "Unknown" 
        END) as statusShow',
        'j.jobStatus as JobStatus',
        'j.address',
        'j.street',
        'j.street2',
        'j.city',
        'j.state',
        'j.zip',
        'j.country',
        'j.latitude',
        'j.longitude',
        'j.jobReport',
        'j.geoFencing',
        'j.points',
        'j.polygonColor',
        'j.crd',
        'j.upd',
        '(case when (j.workPriority = 0) 
        THEN "Low" when (j.workPriority = 1) 
        THEN "Medium" when (j.workPriority = 2) 
        THEN "High" ELSE
        "Unknown" 
        END) as priority',
        'j.workPriority');
    public function __construct(){
        parent::__construct();

    }
    function  nearByJobs($where,$latitude,$longitude){
        $this->column_sel[]  =  "j.latitude";
        $this->column_sel[]  =  "j.longitude";
        $this->column_sel[]  =  "( 3959 * acos ( cos ( radians(".$latitude.") ) * cos( radians(j.latitude ) ) * cos( radians(j.longitude ) - radians(".$longitude.") ) + sin ( radians(".$latitude.") ) * sin( radians(j.latitude ) ) ) ) AS distance";
        $sel_fields = array_filter($this->column_sel); 
        $this->db->select($sel_fields);
        $this->db->from('jobs as j');
        $this->db->join('jobType as jt','j.jobTypeId=jt.jobTypeId');
        $this->db->join('users as c','c.id=j.customerId','left');
        $this->db->join('users as d','d.id=j.driverId','left');
        !empty($where) ? $this->db->where($where) :"";
        $this->db->having("distance < 20000");
        $this->db->order_by('distance','asc');
        //$this->db->order_by('j.upd','desc');
        $sql = $this->db->get();
        if($sql->num_rows()):
            $res = $sql->result();
            foreach($res as $k =>$row){
                $report = !empty($row->jobReport) ? json_decode($row->jobReport,true): array();
                if(!empty($report)):
                    $report = $this->reportFormat($report);
                endif;
                $res[$k]->jobReport     = !empty($report) ? $report : new stdClass();
                $res[$k]->generatePdf   = base_url().'pdfset/download/'.encoding($row->jobId);
                $timinig                = $this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(outDateTime, inDateTime)))) as timeDuration')->from('jobTiming')->where(array('jobId'=>$row->jobId,'inDateTime !='=>'0000-00-00 00:00:00','outDateTime !='=>'0000-00-00 00:00:00'))->order_by('jobTimeId','asc')->group_by('jobId')->get();
                if($timinig->num_rows()){
                    $time               = isset($timinig->row()->timeDuration) ? $timinig->row()->timeDuration:"NA";
                }else{
                    $time                   = 'NA';
                }
                $res[$k]->timeDuration      = $time;
                $res[$k]->geoTimeDuration   = $this->geoTimeDuration($row->jobId);
                if($res[$k]->geoFencing==1){
                    $geopint                = substr_replace($row->points,"",-1); 
                    $geopint                = trim($geopint);
                    $res[$k]->geoFencingUrl = "https://maps.googleapis.com/maps/api/staticmap?center=".$row->latitude.",".$row->longitude."&zoom=auto&scale=1&size=640x500&maptype=satellite&format=png&visual_refresh=true&markers=size:mid%7Ccolor:red%7Clabel:o%7C".$row->latitude.",".$row->longitude."&path=fillcolor:0xFEFEFE%7Ccolor:0xFEFEFE|weight:1|".$geopint."&key=".GOOGLE_API_KEY;
                }else{
                    $res[$k]->geoFencingUrl = ""; 
                }
                $jobTypeQuestions           = $this->jobTypeQuestions($row->jobId,$row->jobTypeId);
                $res[$k]->jobTypeQuestions  = !empty($jobTypeQuestions) ? $jobTypeQuestions :array();
              //  $miles = $this->common_model->roadRouteDistance($row->latitude,$row->longitude,$latitude,$longitude);
                $res[$k]->distance = number_format($row->distance,2);//miles
            }
            return $res;
        endif;
        return false;
    } //ENd Function
    function  assignJobs($where){

        $sel_fields = array_filter($this->column_sel); 
        $this->db->select($sel_fields);
        $this->db->from('jobs as j');
        $this->db->join('jobType as jt','j.jobTypeId=jt.jobTypeId');
        $this->db->join('users as c','c.id=j.customerId','left');
        $this->db->join('users as d','d.id=j.driverId','left');
        !empty($where) ? $this->db->where($where) :"";

        $this->db->order_by('j.upd','desc');
        $sql = $this->db->get();
        if($sql->num_rows()):
            $res = $sql->result();
            foreach($res as $k =>$row){
                $report = !empty($row->jobReport) ? json_decode($row->jobReport,true): array();
                if(!empty($report)):
                    $report = $this->reportFormat($report);
                endif;
                $res[$k]->jobReport         = !empty($report) ? $report : new stdClass();
                $res[$k]->generatePdf       = base_url().'pdfset/download/'.encoding($row->jobId);
                $timinig                    = $this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(outDateTime, inDateTime)))) as timeDuration')->from('jobTiming')->where(array('jobId'=>$row->jobId,'inDateTime !='=>'0000-00-00 00:00:00','outDateTime !='=>'0000-00-00 00:00:00'))->order_by('jobTimeId','asc')->group_by('jobId')->get();
                if($timinig->num_rows()){
                    $time                   = isset($timinig->row()->timeDuration) ? $timinig->row()->timeDuration:"NA";
                }else{
                    $time                   = 'NA';
                }
                $res[$k]->timeDuration      = $time;
                $res[$k]->geoTimeDuration   = $this->geoTimeDuration($row->jobId);
                if($res[$k]->geoFencing==1){
                    $geopint                = substr_replace($row->points,"",-1); 
                    $geopint                = trim($geopint);
                    $res[$k]->geoFencingUrl = "https://maps.googleapis.com/maps/api/staticmap?center=".$row->latitude.",".$row->longitude."&zoom=auto&scale=1&size=640x500&maptype=satellite&format=png&visual_refresh=true&markers=size:mid%7Ccolor:red%7Clabel:o%7C".$row->latitude.",".$row->longitude."&path=fillcolor:0xFEFEFE%7Ccolor:0xFEFEFE|weight:1|".$geopint."&key=".GOOGLE_API_KEY;
                }else{
                    $res[$k]->geoFencingUrl = ""; 
                }
                $jobTypeQuestions           = $this->jobTypeQuestions($row->jobId,$row->jobTypeId);
                $res[$k]->jobTypeQuestions  = !empty($jobTypeQuestions) ? $jobTypeQuestions :array();
            }
            return $res;
        endif;
        return false;
    } //ENd Function

    function  jobDetail($jobId){
        $this->column_sel[] = 'j.jobReport';
        $sel_fields         = array_filter($this->column_sel); 
        $this->db->select($sel_fields);
        $this->db->from('jobs as j');
        $this->db->join('jobType as jt','j.jobTypeId=jt.jobTypeId');
        $this->db->join('users as c','c.id=j.customerId','left');
        $this->db->join('users as d','d.id=j.driverId','left');
        $this->db->where('j.jobId',$jobId);
        $sql        = $this->db->get();
        if($sql->num_rows()):
           $row     = $sql->row();
           $report  = !empty($row->jobReport) ? json_decode($row->jobReport,true): array();
            if(!empty($report)):
               $report      = $this->reportFormat($report);
            endif;
            $row->jobReport = !empty($report) ? $report : new stdClass();
            $timinig        = $this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(outDateTime, inDateTime)))) as timeDuration')->from('jobTiming')->where(array('jobId'=>$row->jobId,'inDateTime !='=>'0000-00-00 00:00:00','outDateTime !='=>'0000-00-00 00:00:00'))->order_by('jobTimeId','asc')->group_by('jobId')->get();
            if($timinig->num_rows()){
                $time = isset($timinig->row()->timeDuration) ? $timinig->row()->timeDuration:"NA";
            }else{
                $time = 'NA';
            }
            $row->timeDuration      = $time;
            $row->geoTimeDuration   = $this->geoTimeDuration($row->jobId);
            if($row->geoFencing==1){
                $geopint            = substr_replace($row->points,"",-1); 
                $geopint            = trim($geopint);
                $row->geoFencingUrl = "https://maps.googleapis.com/maps/api/staticmap?center=".$row->latitude.",".$row->longitude."&zoom=auto&scale=1&size=640x500&maptype=satellite&format=png&visual_refresh=true&markers=size:mid%7Ccolor:red%7Clabel:o%7C".$row->latitude.",".$row->longitude."&path=fillcolor:0xFEFEFE%7Ccolor:0xFEFEFE|weight:1|".$geopint."&key=".GOOGLE_API_KEY;
            }else{
                $row->geoFencingUrl = ""; 
            }
            $row->generatePdf       = base_url().'pdfset/download/'.encoding($row->jobId);
            $jobTypeQuestions       = $this->jobTypeQuestions($row->jobId,$row->jobTypeId);
            $row->jobTypeQuestions  = !empty($jobTypeQuestions) ? $jobTypeQuestions :array();
            return $row;
        endif;
        return false;
    }//end function
    function reportFormat($report){
        if(!empty($report)):
            $bimage = $aimage = array();
            if(isset($report['beforeWork'])){
                if(isset($report['beforeWork']['driverSignature']) && !empty($report['beforeWork']['driverSignature'])){
                    $report['beforeWork']['driverSignature'] =  S3JOBS_URL.$report['beforeWork']['driverSignature'] ;
                }else{
                    $report['beforeWork']['driverSignature'] = "";
                }
                $beforeWorkImage = isset($report['beforeWork']['workImage']) ? $report['beforeWork']['workImage']:array();
                for ($i=0; $i <sizeof($beforeWorkImage) ; $i++) { 
                    $bimage[] = S3JOBS_URL.$beforeWorkImage[$i];
                }
                $report['beforeWork']['workImage']          = $bimage;
            }else{
               $report['beforeWork'] = new stdClass();  
            }
            if(isset($report['afterWork'])){
                if(isset($report['afterWork']['customerSignature']) && !empty($report['afterWork']['customerSignature'])){
                     $report['afterWork']['customerSignature']   = S3JOBS_URL.$report['afterWork']['customerSignature'];
                }else{
                    $report['afterWork']['customerSignature'] ="";
                }
                $afterWorkImage = isset($report['afterWork']['workImage']) ? $report['afterWork']['workImage']:array();
                for ($j=0; $j <sizeof($afterWorkImage) ; $j++) { 
                    $aimage[] = S3JOBS_URL.$afterWorkImage[$j];
                }
                $report['afterWork']['workImage'] = $aimage;
            }else{
              $report['afterWork'] = new stdClass();   
            }  
            return $report;
        endif;
        $x = new stdClass();
        return $x;
    }//end function
    function jobTracking($jobId,$driverId,$latitude,$longitude){
       $jobMsg  = new stdClass();
       $sql     = "SELECT jobId FROM jobs as j  WHERE ST_CONTAINS(j.boundary, Point($longitude,$latitude))
            AND j.jobId = $jobId AND j.driverId = $driverId AND j.jobStatus = 1";
        $area   = $this->db->query($sql);
       
        if($area->num_rows()): //inside area
            $job    =  $area->row();
            $this->jobTimingSet($jobId,$driverId,'inside');
            $jobMsg = "The driver is working on job place inside.";
        else:
            $this->jobTimingSet($jobId,$driverId,'outside');
            $jobMsg = "The driver is job place area outside.";
        endif;//End area
        return  $jobMsg;
    }//end function
    function jobTimingSet($jobId,$driverId,$areaStatus){
        $isExistTime    = $this->common_model->is_data_exists('jobTiming',array('jobId'=>$jobId,'driverId'=>$driverId));
        $mailsent       = false;
        $setin          = false;
        if(!$isExistTime){
            $mailsent   = true;
        }
        $this->db->select("*")->from('jobTiming');
        $this->db->where(array('jobId'=>$jobId,'driverId'=>$driverId));
        $this->db->order_by('jobTimeId','desc');
        $this->db->limit(1);
        $sql =$this->db->get();
        if($sql->num_rows()){
            //exits
            $jobtime            = $sql->row();
            $inDateTime         = $jobtime->inDateTime;
            $outDateTime        = $jobtime->outDateTime;
            if($areaStatus=='inside'){
                if($inDateTime !='0000-00-00 00:00:00' && $outDateTime !='0000-00-00 00:00:00'){
                    $date       = date("Y-m-d H:i:s");
                    $setData    = array('jobId'=>$jobId,'driverId'=>$driverId,'inDateTime'=>$date);
                    $set        = ($areaStatus=='inside') ? $this->common_model->insertData('jobTiming',$setData) : false;  
                }
            }else if($areaStatus=='outside'){
                if($inDateTime !='0000-00-00 00:00:00' && $outDateTime =='0000-00-00 00:00:00'){
                    $date       = date("Y-m-d H:i:s");
                    $setData    = array('outDateTime'=>$date);
                    $update     = $this->common_model->updateFields('jobTiming',$setData,array('jobTimeId'=>$jobtime->jobTimeId,'jobId'=>$jobId,'driverId'=>$driverId));
                }
            }else{
                if($inDateTime !='0000-00-00 00:00:00' && $outDateTime =='0000-00-00 00:00:00'){
                    $date       = date("Y-m-d H:i:s");
                    $setData    = array('outDateTime'=>$date);
                    $update     = $this->common_model->updateFields('jobTiming',$setData,array('jobTimeId'=>$jobtime->jobTimeId,'jobId'=>$jobId,'driverId'=>$driverId));
                }  
            }
            //exits
        }else{
            //data not exist
            if($areaStatus !='complete'){
                $date       = date("Y-m-d H:i:s");
                $setData    = array('jobId'=>$jobId,'driverId'=>$driverId,'inDateTime'=>$date);
                $set        = ($areaStatus=='inside') ? $this->common_model->insertData('jobTiming',$setData) : false;
                $setin      = true;
            } 
        }//end if
        if($mailsent && $setin){
            $jobTime    = $this->common_model->is_data_exists('jobTiming',array('jobId'=>$jobId,'driverId'=>$driverId));
            $job        = $this->common_model->is_data_exists('jobs',array('jobId'=>$jobId,'driverId'=>$driverId));
            $driver     = $this->common_model->is_data_exists('users',array('id'=>$driverId));
            $customer     = $this->common_model->is_data_exists('users',array('id'=>$job->customerId));
            //email send
            if($jobTime){
            //send mail
                $maildata['title']              = "";
                $message                        = "";
                $message                    .= "Hello<br>";
                $message                    .= $driver->fullName." has arrived at the location for:<br><br>";
                $message                    .= "Job Reference: ".$job->jobName."<br>";
                $message                    .= "Location: ".$job->address."<br>";
                $message                    .= "Date & Time: ".date("d/m/Y H:i A",strtotime($jobTime->inDateTime))."<br>";
                $message                    .= "Company: ".SITE_NAME."<br><br><br>";
                $message                    .= "Thanks,<br>Admin";

                $maildata['message']         = $message;
                $subject                     = $driver->fullName." has arrived at ".SITE_NAME." for Job Ref: ".$job->jobName;
                $message                     = $this->load->view('emails/email',$maildata,TRUE);
                $emails                      = $this->common_model->adminEmails();
                if($customer){
                    array_push($emails,$customer->email);
                }
                if(!empty($emails)){
                    $this->load->library('smtp_email');
                    $this->smtp_email->send_mail_multiple($emails,$subject,$message);
                }
                //send mail
            }
            //email send
        }
        return true;
    }//end function
    function jobTypeQuestions($jobId,$jobTypeId){
        $array = array();
        $this->db->select('q.questionId,q.question,q.type,q.options,ans.answerId,ans.answer');
        $this->db->from('jobTypeQuestions as q');
        $this->db->join('jobQuestionAnswer ans','ans.questionId=q.questionId','left');
        $this->db->where(array('ans.jobId'=>$jobId,'q.jobTypeId'=>$jobTypeId));
        $this->db->order_by('q.questionId','asc');
        $sql=$this->db->get();
        if($sql->num_rows()){
            $array = $sql->result();
        }
        return $array; 
    }//end function
    function geoTimeDuration($jobId){
        $timeing    = array();
        $time       = $this->db->select('jobId,
                                        inDateTime,
                                        outDateTime,
                                        SEC_TO_TIME(TIME_TO_SEC(timediff(outDateTime, inDateTime))) as timeDuration,
                                        (case when (inDateTime = "0000-00-00 00:00:00") 
                                        THEN "-" ELSE
                                        inDateTime 
                                        END) as startTime,
                                        (case when (outDateTime = "0000-00-00 00:00:00") 
                                        THEN "Progress" ELSE
                                        outDateTime 
                                        END) as endTime')->from('jobTiming')->where(array('jobId'=>$jobId))->order_by('jobTimeId','asc')->get();
        if($time->num_rows()){
            $timeing = $time->result();

        }
        $timinigT    = $this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(outDateTime, inDateTime)))) as timeDuration')->from('jobTiming')->where(array('jobId'=>$jobId,'inDateTime !='=>'0000-00-00 00:00:00','outDateTime !='=>'0000-00-00 00:00:00'))->order_by('jobTimeId','asc')->get();
        $timeT       = 'NA';
        if($timinigT->num_rows()){
            $timeT = isset($timinigT->row()->timeDuration) ? $timinigT->row()->timeDuration:"NA";
        }else{
            $timeT = 'NA';
        }
        return array('timinig'=>$timeing,'total'=>$timeT);
    }//end Function
}//Function 