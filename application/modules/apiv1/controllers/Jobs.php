<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Jobs extends Common_Service_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->check_service_auth();
        $this->load->model('job_model'); //load image model
    }

    function assignJobs_get(){
        $authCheck  = $this->check_service_auth();
        $authToken  = $this->authData->authToken;
        $userId     = $this->authData->id;
        $userType   = $this->authData->userType;
        $authtoken  = $this->api_model->generate_token();
        switch ($userType) {
            case 1:
           //  $where =  array('j.customerId'=> $userId,'j.jobStatus !='=>2);
             $where =  array('j.customerId'=> $userId);
                break;
            case 2:
              //$where =  array('j.driverId'=> $userId,'j.jobStatus !='=>2);
              $where =  array('j.driverId'=> $userId);
                break;
            
            default:
                $where =  array();
                break;
        }
        $jobs = $this->job_model->assignJobs($where);
        if(is_array($jobs)){
            $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(127), 'jobs' =>$jobs);         
        }else{
            $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(114),'jobs'=>array());
        }   
        $this->response($response);    
    } //End Function  
    function nearByJobs_get(){
        $authCheck      = $this->check_service_auth();
        $authToken      = $this->authData->authToken;
        $userId         = $this->authData->id;
        $userType       = $this->authData->userType;
        $latitude       = $this->authData->latitude;
        $longitude      = $this->authData->longitude;
        $authtoken      = $this->api_model->generate_token();
        switch ($userType) {
            case 1:
           //  $where =  array('j.customerId'=> $userId,'j.jobStatus !='=>2);
             $where =  array('j.customerId'=> $userId,'j.jobStatus !='=>2);
                break;
            case 2:
              //$where =  array('j.driverId'=> $userId,'j.jobStatus !='=>2);
              $where =  array('j.driverId'=> $userId,'j.jobStatus !='=>2);
                break;
            
            default:
                $where =  array();
                break;
        }
        $jobs = $this->job_model->nearByJobs($where,$latitude,$longitude);
        if(is_array($jobs)){
            $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(127), 'jobs' =>$jobs);       
        }else{
            $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(114),'jobs'=>array());
        }   
        $this->response($response);    
    } //End Function  
    
    function completeJobs_get(){
        $authCheck  = $this->check_service_auth();
        $authToken  = $this->authData->authToken;
        $userId     = $this->authData->id;
        $userType   = $this->authData->userType;
        $authtoken  = $this->api_model->generate_token();
        switch ($userType) {
            case 1:
             $where     =  array('j.customerId'=> $userId,'j.jobStatus'=>2);
                break;
            case 2:
              $where    =  array('j.driverId'=> $userId,'j.jobStatus'=>2);
                break;
            
            default:
                $where  =  array('j.jobStatus'=>2);
                break;
        }
        $jobs = $this->job_model->assignJobs($where);
        if(is_array($jobs)){
            $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(127), 'jobs' =>$jobs);
                
        }else{
            $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(114),'jobs'=>array());
        }   
        $this->response($response);    
    } //End Function  
      
    function jobDetail_post(){
        $authCheck  = $this->check_service_auth();
        $authToken  = $this->authData->authToken;
        $userId     = $this->authData->id;
        $userType   = $this->authData->userType;
        $authtoken  = $this->api_model->generate_token();
        $this->form_validation->set_rules('jobId', 'jobId', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));   
        }
        else{
            $jobId          = $this->post('jobId');
            $jobs           = $this->job_model->jobDetail($jobId);
            if($jobs){
                $response   = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(127), 'jobs' =>$jobs);
            }else{
                $response   = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(114),'jobs'=>new stdClass());
            } 
        }
        $this->response($response);    
    } //End Function
    function jobActivity_post(){
        $this->form_validation->set_rules('jobId', 'jobId', 'trim|required');
        $this->form_validation->set_rules('jobDateTime', 'jobDateTime', 'trim|required');
        $this->form_validation->set_rules('jobStatus', 'job status', 'trim|required');
        if (empty($_FILES['signature']['name'])) {
            $this->form_validation->set_rules('signature', 'signature image', 'trim|required');
        }
        if (empty($_FILES['workImage']['name'])) {
            $this->form_validation->set_rules('workImage', 'work image', 'trim|required');
        }
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
        }else{
			
            $filesCount     = sizeof($_FILES['workImage']['name']);
          //   pr($_FILES['workImage']['name']);
            if($filesCount>4){
                $response   = array('status' => FAIL, 'message' =>"work image max 4 image upload.");
                $this->response($response);  
            }
            $data_val   = array();
            $jobId      = $this->post('jobId');
            $where      = array('jobId'=>$jobId);
            $isExist    = $this->common_model->is_data_exists('jobs',$where);
            if($isExist){
                if($isExist->jobStatus==2){
                     $response = array('status' => FAIL, 'message' =>"job already completed.");
                }else{
                    /*JonType Question answer*/
                    $questionAnswer             = $this->post('questionAnswer');
                    $questions                  = !empty($questionAnswer) ? json_decode($questionAnswer,true):array();
                    $updateQ                    = array() ;
                    if(!empty($questions) && is_array($questions)){
                        foreach ($questions as $k => $question) {
                            $questionId         = $question['questionId'];
                            $answer             = $question['answer'];
                            $whereQ             = array('questionId'=>$questionId,'jobId'=>$jobId);
                            $isExistQ           = $this->common_model->is_data_exists('jobQuestionAnswer',$whereQ);
                            if($isExistQ){
                                $up = $this->common_model->updateFields('jobQuestionAnswer',array('answer'=>$answer),$whereQ);
                                $updateQ[] = $up? 1:0;
                            }else{
                                $updateQ[] = 0;
                            }
                        }
                    }// Question answer
                    /*JonType Question answer*/
                    /* log_event(json_encode($_POST), 'jobs_log.txt');  //create log of notifcation
                    log_event(json_encode($_FILES), 'jobs_log.txt');  //create log of notifcation*/
                    $jobReport                      = isset($isExist->jobReport) ? $isExist->jobReport:"";
                    $report                         = !empty($jobReport) ? json_decode($jobReport,true):array();
                    $jobStatus                      = $this->post('jobStatus');
                    $jobDateTime                    =  date("Y-m-d h:i:s A",strtotime($this->post('jobDateTime')));;
                    switch ($jobStatus) {
                        case 'start':
                            $res=1;
                            $jobActivity = 1;
                            $data_val['startDateTime']      = $jobDateTime;
                            break;
                        case 'end':
                            $res=1;
                            $jobActivity = 2;
                            $data_val['endDateTime']        = $jobDateTime;
                            break;
                        default:
                             $res=0;
                            break;
                    }
                    $data_val['comments']                   = $this->post('comments');
                    if($res){
                        $workImage = array();
                        $this->load->library('s3');
                        $this->load->model('s3_model');
                        $uploadFor = "jobs";
                        for ($i=0; $i <$filesCount-1 ; $i++) { 
                            $name = $_FILES['workImage']['name'][$i];
                            $size = $_FILES['workImage']['size'][$i];
                            $tmp  = $_FILES['workImage']['tmp_name'][$i];
                            $ext  = $this->s3_model->getExtension($name);
                            $actual_image_name = time().".".$ext;
                            if($this->s3->putObjectFile($tmp, BUCKETNAME , $uploadFor.'/'.$actual_image_name, S3::ACL_PUBLIC_READ) )
                            {
                                $workImage[]   =  $actual_image_name;
                            }                   
                        }
                        //sinature
                        $signaturename          = $_FILES['signature']['name'];
                        $signaturesize          = $_FILES['signature']['size'];
                        $signaturetmp           = $_FILES['signature']['tmp_name'];
                        $signatureext           = $this->s3_model->getExtension($signaturename);;
                        //Rename image name.
                        $actual_image_signature = time().".".$signatureext;
                        if($this->s3->putObjectFile($signaturetmp, BUCKETNAME , $uploadFor.'/'.$actual_image_signature, S3::ACL_PUBLIC_READ) )
                        {
                            $signature    = $actual_image_signature;
                        }
                        switch ($jobStatus) {
                            case 'start':
                                $data_val['driverSignature']        = $signature;
                                $data_val['workImage']              = $workImage;
                                $report['beforeWork']               = $data_val;
                            break;
                            case 'end':
                                $data_val['customerSignature']      = $signature;
                                $data_val['workImage']              = $workImage;
                                $report['afterWork']                = $data_val;
                            break;

                            default:
                            $res=0;
                            break;
                        }
                        $update = $this->common_model->updateFields('jobs',array('jobStatus'=>$jobActivity,'jobReport'=>json_encode($report)),$where);
                     $this->job_model->jobTimingSet($jobId,$isExist->driverId,'complete');
                        $showmsg  = ($jobActivity==2)? "Job has been completed successfully." : "Job has been started successfully.";
                        $response = array('status'=>SUCCESS,'message'=>$showmsg);
                    }//end if   
                }      
            }else{
               $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(118)); 
            }          
        }//end if
        $this->response($response);    
    }//end function
    function jobTracking_post(){
        $authCheck  = $this->check_service_auth();
        $authToken  = $this->authData->authToken;
        $driverId   = $this->authData->id;
        $this->form_validation->set_rules('jobId', 'jobId', 'trim|required');
        $this->form_validation->set_rules('latitude', 'latitude', 'trim|required');
        $this->form_validation->set_rules('longitude', 'longitude', 'trim|required');
        if($this->authData->userType==1){
            $response = array('status' => FAIL, 'message' =>"job tracking use only Driver.");
        }
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
        }else{
            $driverId       = $this->authData->id;
            $jobId          = $this->post('jobId');
            $latitude       = $this->post('latitude');
            $longitude      = $this->post('longitude');
            $where          = array('jobId'=>$jobId);
            $isJob          = $this->common_model->is_data_exists('jobs',$where);
            if($isJob){

                $result     = $this->job_model->jobTracking($jobId,$driverId,$latitude,$longitude);
                $response   = array('status' => SUCCESS, 'message' =>$result); 
            }else{
                $response   = array('status' => FAIL, 'message' =>"Something going wrong.");  
            }
        } 
        $this->response($response);    
    }//end function
    function jobQuestionAnswer_post(){
        $authCheck  = $this->check_service_auth();
        $authToken  = $this->authData->authToken;
        $driverId   = $this->authData->id;
        $this->form_validation->set_rules('jobId', 'jobId', 'trim|required');
        $this->form_validation->set_rules('questionId', 'questionId', 'trim|required');
        $this->form_validation->set_rules('answer', 'answer', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
        }else{
            $jobId          = $this->post('jobId');
            $questionId     = $this->post('questionId');
            $answer         = $this->post('answer');
            $where          = array('questionId'=>$questionId,'jobId'=>$jobId);
            $isExist        = $this->common_model->is_data_exists('jobQuestionAnswer',$where);
            if($isExist){
                $update     = $this->common_model->updateFields('jobQuestionAnswer',array('answer'=>$answer),$where);
                if($update){
                    $response   = array('status' => SUCCESS, 'message' =>ResponseMessages::getStatusCodeMessage(122));
                }else{
                     $response  = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(118));
                }     
            }else{
                $response       = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(118));  
            }
        }
        $this->response($response);
    }//end function
    function allQuestionAnswer_post(){
        $authCheck  = $this->check_service_auth();
        $authToken  = $this->authData->authToken;
        $driverId   = $this->authData->id;
        $this->form_validation->set_rules('jobId', 'jobId', 'trim|required');
        $this->form_validation->set_rules('questionAnswer', 'questionAnswer', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
        }else{
            $jobId              = $this->post('jobId');
            $questionAnswer     = $this->post('questionAnswer');
            $questions          = !empty($questionAnswer) ? json_decode($questionAnswer,true):array();
            $update             = array() ;
            foreach ($questions as $k => $question) {
                    $questionId         = $question['questionId'];
                    $answer             = $question['answer'];
                    $where              = array('questionId'=>$questionId,'jobId'=>$jobId);
                    $isExist            = $this->common_model->is_data_exists('jobQuestionAnswer',$where);

                     if($isExist){
                        $up             = $this->common_model->updateFields('jobQuestionAnswer',array('answer'=>$answer),$where);
                        $update[]       = $up? 1:0;
                     }else{
                        $update[]       = 0;
                     }
            }
            if(!in_array(0,$update)){
                $response = array('status' => SUCCESS, 'message' =>ResponseMessages::getStatusCodeMessage(122));
            }else{
                $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(118));
            }
        }
        $this->response($response);
    }//end function  
    function driverTracking_post(){
        $authCheck  = $this->check_service_auth();
        $authToken  = $this->authData->authToken;
        $driverId   = $this->authData->id;
        $this->form_validation->set_rules('latitude', 'latitude', 'trim|required');
        $this->form_validation->set_rules('longitude', 'longitude', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
        }else{
            $where                      = array('id'=>$driverId);
            $isExist                    = $this->common_model->is_data_exists('users',$where);
            if($isExist){
                $setData['latitude']    = $this->post('latitude');
                $setData['longitude']   = $this->post('longitude');
                $update                 = $this->common_model->updateFields('users',$setData,$where);
                if($update){
                    $user               = $this->common_model->is_data_exists('users',$where);
                    $response           = array('status' => SUCCESS, 'message' =>ResponseMessages::getStatusCodeMessage(123),'data'=>array('latitude'=>$user->latitude,'longitude'=>$user->longitude));
                }else{
                    $response           = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(118));
                }
            }else{
                $response               = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(118));
            }
        }
        $this->response($response);
    }//end function
}//End Class 