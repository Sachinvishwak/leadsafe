<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Tasks extends Common_Admin_Controller{
    public function __construct(){
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        //$this->check_admin_service_auth();
    }
    public function add_post(){
        $this->form_validation->set_rules('name', 'name', 'trim|required|min_length[4]|regex_match[/^([a-z0-9 ])+$/i]');
     
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));    
        }else{
            //  pr($_FILES);
			$data_val['name']       	= $this->post('name');
			$data_val['description']       	= $this->post('description');
			/*$text_meta_val['fileType'] ='TEXT';
            $text_meta_val['file'] = $this->post('textfile');*/
            /********/
            $total_element_text              = $this->post('total_element_text');
            $total_element_text              = !empty($total_element_text) ? $total_element_text :0;
            $textData                    = array();
            $textDeleteId             = array();
            $j = 0;
            for ($i=0; $i < $total_element_text ; $i++) { 
                $k                       = $i+1;
                $textfile                = $this->post('textfile_'.$k);
                $textfileId              = $this->post('textfileId_'.$k);
                if(!empty($textfileId) && $textfileId !=0){
                    $textDeleteId[]    = $textfileId;
                }
                if(isset($textfile) && !empty($textfile)){
                    $textData[$j]['textfileId']      = $textfileId;
                    $textData[$j]['textfile']        = $textfile;
                    $j++;
                } 
            }
            /********/  
            /********/
            $this->load->model('Image_model');
          
            $total_element_image              = $this->post('total_element_image');
            $total_element_image              = !empty($total_element_image) ? $total_element_image :0;
            $imageData                    = array();
            $imageDeleteId             = array();
            $jm = 0;
            for ($im=0; $im < $total_element_image ; $im++) { 
                $km                       = $im+1;
                $imagefileId              = $this->post('imagefileId_'.$km);
                if (!empty($_FILES['fileImage_'.$km]['name'])) {
                      $imageF = $this->Image_model->updateDocument('fileImage_'.$km,'task_image');
                            //check for image name if present
                            if(array_key_exists("image_name",$imageF)):
                            $imageData[$jm]['imagefileId']      =  $imagefileId;
                            $imageData[$jm]['file']             =  $imageF['image_name'];
                            $jm++;

                            endif;

                    } 
              
            }
            $total_element_video              = $this->post('total_element_video');
            $total_element_video              = !empty($total_element_video) ? $total_element_video :0;
            $videoData                    = array();
            $videoDeleteId             = array();
            $jv = 0;
            for ($iv=0; $iv < $total_element_video ; $iv++) { 
                            $kv                       = $iv+1;
                            $videofileId              = $this->post('videofileId_'.$kv);
                            if (!empty($_FILES['videofile_'.$kv]['name'])) {
                            $videoF=$this->Image_model->updateDocument('videofile_'.$kv,'task_video');
                            //check for image name if present
                            if(array_key_exists("image_name",$videoF)):
                            $videoData[$jv]['videofileId']      =  $videofileId;
                            $videoData[$jv]['file']             =  $videoF['image_name'];
                            $jv++;

                            endif;

                        } 
                    }
            /********/
			$id                  = decoding($this->post('id'));
			$where                      = array('taskId'=>$id);
        	$isExist                    = $this->common_model->is_data_exists('tasks',$where);
        	if($isExist){
        		$result                 = $this->common_model->updateFields('tasks',$data_val,$where);
        		$msg                    = "Record updated successfully.";
                $taskId   = $id ;
        	}else{
        		$result                 = $this->common_model->insertData('tasks',$data_val);
        		$msg                    = "Record added successfully.";
                $taskId   = $result ;
        	}

             if(!empty($taskId)){
                if(!empty($textDeleteId)){
                    $this->common_model->deleteDataTaskMeta('task_meta',array('taskId'=>$taskId),$textDeleteId);
                }    
                for ($x=0; $x <sizeof($textData) ; $x++) { 
                    $textDatatext = array();
                    $textId =  $textData[$x]['textfileId'];
                    $textDatatext['description'] = $textData[$x]['textfile'];
                    $textDatatext['fileType']              = 'TEXT';
                    $isText                          = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$textId));
                    if($isText){
                        $this->common_model->updateFields('task_meta',$textDatatext,array('taskmetaId'=>$textId));
                    }else{
                        $textDatatext['taskId']     = $taskId;
                        $this->common_model->insertData('task_meta',$textDatatext);
                    }
                }
                for ($xm=0; $xm <sizeof($imageData) ; $xm++) { 
                    $imageDataimage                         = array();
                    $imageId                                =  $imageData[$xm]['imagefileId'];
                    $imageDataimage['file']                 = $imageData[$xm]['file'];
                    $imageDataimage['fileType']             = 'IMAGE';
                    
                    $isText                                 = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$imageId));
                    if($isText){
                        $this->common_model->updateFields('task_meta',$imageDataimage,array('taskmetaId'=>$imageId));
                    }else{
                        $imageDataimage['taskId']     = $taskId;
                        $this->common_model->insertData('task_meta',$imageDataimage);
                    }
                }
                for ($xv=0; $xv <sizeof($videoData) ; $xv++) { 
                    $videoDatavideo                         = array();
                    $videoId                                =  $videoData[$xv]['videofileId'];
                    $videoDatavideo['file']                 = $videoData[$xv]['file'];
                    $videoDatavideo['fileType']             = 'VIDEO';
                    
                    $isTextv                                 = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$videoId));
                    if($isTextv){
                        $this->common_model->updateFields('task_meta',$videoDatavideo,array('taskmetaId'=>$videoId));
                    }else{
                        $videoDatavideo['taskId']     = $taskId;
                        $this->common_model->insertData('task_meta',$videoDatavideo);
                    }
                }
                
            }
            if($result){
                //$text_meta_val['taskId'] = $result;
                //$this->common_model->insertData('task_meta',$text_meta_val);
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'task-detail/'.encoding($taskId));
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
        }
        $this->response($response);
    }//end function 
    
    
    public function addTaskStep_post(){

        $this->form_validation->set_rules('id', 'id', 'trim|required');
        $this->form_validation->set_rules('taskstepId', 'task step', 'trim|required');
     
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));    
        }else{
            $status = SUCCESS;
            $msg    = "Layer record added successfully.";
            $fileName = "";
            //
            $taskId                  = decoding($this->post('id'));
            $where                      = array('taskId'=>$taskId);
            $isExist = $this->common_model->is_data_exists('tasks',$where);
            $insertIdChange = 0;

            if($isExist){
                $taskstep = $this->post('taskstepId');
                switch ($taskstep) {
                case 'image':
                      $this->load->model('Image_model');
                    $imagefileId              = $this->post('imagefileId_1');
                    if (!empty($_FILES['fileImage_1']['name'])) {
                    $imageF = $this->Image_model->updateDocument('fileImage_1','task_image');
                 
                    if(array_key_exists("image_name",$imageF)):
                        
                        $file             =  $imageF['image_name'];

                        $data_val['fileType']              = 'IMAGE';
                        $data_val['file']              = $file;

                        $isImage = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$imagefileId));
                        if($isImage){
                            $this->common_model->updateFields('task_meta',$data_val,array('taskmetaId'=>$imagefileId));
                            $status = SUCCESS;
                            $msg    = "Layer record updated successfully.";
                        }else{
                            $data_val['taskId']     = $taskId;
                            $this->common_model->insertData('task_meta',$data_val);
                            $status = SUCCESS;
                            $insertIdChange = $this->db->insert_id();
                            $msg    = "Layer record added successfully.";
                            $fileName = $file;
                        }

                    endif;

                    } 
                    break;
                case 'video':
                      $this->load->model('Image_model');
                    $videofileId              = $this->post('videofileId_1');
                    if (!empty($_FILES['videofile_1']['name'])) {
                    $videoF=$this->Image_model->updateDocument('videofile_1','task_video');
                    //check for image name if present
                        if(array_key_exists("image_name",$videoF)):
                        $file            =  $videoF['image_name'];
                        $data_val['fileType']              = 'VIDEO';
                        $data_val['file']              = $file;

                        $isVideo                          = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$videofileId));
                        if($isVideo){
                            $this->common_model->updateFields('task_meta',$data_val,array('taskmetaId'=>$videofileId));
                            $status = SUCCESS;
                            $msg    = "Layer record updated successfully.";
                        }else{
                            $data_val['taskId']     = $taskId;
                            $this->common_model->insertData('task_meta',$data_val);
                            $insertIdChange = $this->db->insert_id();
                            $status = SUCCESS;
                            $msg    = "Layer record added successfully.";
                            $fileName = $file;
                        }

                        endif;
                    }
                    break;
                
                default:
                    $textfile                = $this->post('textfile_1');
                    $textfileId              = $this->post('textfileId_1');
                    $data_val['description']       = $textfile;
                    $data_val['fileType']              = 'TEXT';
                    
                    
                    $isText                          = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$textfileId));
                    if($isText){
                        $this->common_model->updateFields('task_meta',$data_val,array('taskmetaId'=>$textfileId));
                        $status = SUCCESS;
                        $msg    = "Layer record updated successfully.";
                    }else{
                        $data_val['taskId']     = $taskId;
                        $this->common_model->insertData('task_meta',$data_val);
                        $status = SUCCESS;
                        $insertIdChange = $this->db->insert_id();
                        $msg    = "Layer record added successfully.";
                    }
                    break;
            }
            $response  = array('status'=>$status,'message'=>$msg,'insertIdChange'=>$insertIdChange,'fileName'=>$fileName);
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
        }
        $this->response($response);
    }//end function 
    
    public function addTaskStepNew_post(){
        
        $this->form_validation->set_rules('id', 'id', 'trim|required');
        $this->form_validation->set_rules('taskstepId', 'task step', 'trim|required');
     
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));    
        }else{
            $status = SUCCESS;
            $msg    = "Layer record added successfully.";
            $fileName = "";
            //
            $taskId                  = decoding($this->post('id'));
            $where                      = array('taskId'=>$taskId);
            $insertIdChange = 0;

            $taskstep = $this->post('taskstepId');
            switch ($taskstep) {
                case 'image':
                    $this->load->model('Image_model');
                    $imagefileId              = $this->post('imagefileId_1');
                    if (!empty($_FILES['fileImage_1']['name'])) {
                $imageF = $this->Image_model->updateDocument('fileImage_1','task_image');
             
                if(array_key_exists("image_name",$imageF)):
                    
                    $file             =  $imageF['image_name'];
                    $data_val['fileType']              = 'IMAGE';
                    $data_val['file']              = $file;
                    $isImage = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$imagefileId));
                    if($isImage){
                        $this->common_model->updateFields('task_meta',$data_val,array('taskmetaId'=>$imagefileId));
                        $status = SUCCESS;
                        $msg    = "Layer record updated successfully.";
                    }else{
                        $data_val['taskId']     = $taskId;
                        $this->common_model->insertData('task_meta',$data_val);
                        $status = SUCCESS;
                        $insertIdChange = $this->db->insert_id();
                        $msg    = "Layer record added successfully.";
                        $fileName = $imageF['upload_path'];
                    }

                endif;

                } 
                break;
                case 'video':
                $videofileId = $this->post('videofileId_1');
                if (!empty($_FILES['videofile_1']['name'])) {
                    $configVideo['upload_path'] = 'uploads/task_video/';
                    $configVideo['max_size'] = '102400';
                    $configVideo['allowed_types'] = '*';
                    $configVideo['overwrite'] = FALSE;
                    $configVideo['remove_spaces'] = TRUE;
                    $this->load->library('upload', $configVideo);
                    $this->upload->initialize($configVideo);
                    if (!$this->upload->do_upload('videofile_1')) # form input field attribute
                    {
                        echo json_encode($this->upload->display_errors());
                        die;
                    }
                    else
                    {
                        $filedata = $this->upload->data();
                        $video_name = $filedata['file_name'];
                        $videoDatavideo['fileType']  = 'VIDEO';
                        $videoDatavideo['file'] = $video_name;
                        $isVideo = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$videofileId));
                        if($isVideo){
                            $this->common_model->updateFields('task_meta',$data_val,array('taskmetaId'=>$videofileId));
                            $status = SUCCESS;
                            $msg    = "Layer record updated successfully.";
                        }else{
                            $videoDatavideo['taskId']     = 0;
                            $this->common_model->insertData('task_meta',$videoDatavideo);
                            $status = SUCCESS;
                            $insertIdChange = $this->db->insert_id();
                            $msg    = "Layer record added successfully.";
                            $fileName = base_url().'uploads/task_video/'.$video_name;
                        }    
                    }
                }
                break;
            
            default:
                $textfile                = $this->post('textfile_1');
                $textfileId              = $this->post('textfileId_1');
                $data_val['description']       = $textfile;
                $data_val['fileType']              = 'TEXT';
                
                
                $isText                          = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$textfileId));
                if($isText){
                    $this->common_model->updateFields('task_meta',$data_val,array('taskmetaId'=>$textfileId));
                    $status = SUCCESS;
                    $msg    = "Layer record updated successfully.";
                }else{
                    $data_val['taskId']     = $taskId;
                    $this->common_model->insertData('task_meta',$data_val);
                    $status = SUCCESS;
                    $insertIdChange = $this->db->insert_id();
                    $msg    = "Layer record added successfully.";
                }
                break;
            }
            $response  = array('status'=>$status,'message'=>$msg,'insertIdChange'=>$insertIdChange,'EncodedinsertIdChange'=>encoding($insertIdChange),'fileName'=>$fileName);
                   
        }
        $this->response($response);
    }//end function 
    
    public function list_post(){
        $this->load->helper('text');
        $this->load->model('task_model');
        $this->task_model->set_data();
        $list       = $this->task_model->get_list();
        $data       = array();
        $no         = $_POST['start'];
        
        // tasks meta
        foreach ($list as $value3) {
            $id = $value3->taskId;      
            $task_meta =$this->db->get_where('task_meta',
                    array(
                        'taskId'=>$id,
                        'fileType !='=>'TEXT'
                    )
            )->result();
            $value3->task_meta = $task_meta;
        }
        //end
        
        
        foreach ($list as $serData) { 
            $action = '';
            $no++;
            $row    = array();
            $row[]  = $no;
           
            $link_url      = base_url().'task-detail/'.encoding($serData->taskId);
            $row[]  = '<a href="'.$link_url.'">'.display_placeholder_text($serData->name).'</a>'; 
            $row[]      = display_placeholder_text((mb_substr($serData->description, 0,100, 'UTF-8') .((strlen($serData->description) >100) ? '...' : '')));
            $uploaded_media = '';
            foreach($serData->task_meta as $task_meta)
            {
                 $breakone = '<div style="width: 233px;"></div>';
     $uploaded_media      .= '<label> TYPE:'.$task_meta->fileType.'<br>FILENAME: '.$task_meta->file.$breakone.'</label>';  
            }
            if($uploaded_media == "")
            {
                $uploaded_media = '<label>No Media Uploaded Yet..</label>';
            }
            $row[]  = $uploaded_media;
            if($serData->is_exported == 1){
                $row[]  = '<label>Imported From Admin</label>';
            }else{ 
                $row[]  = '<label>Added By SuperAdmin</label>'; 
            }
            if($serData->status){
            $row[]  = '<label class="label label-success">'.$serData->statusShow.'</label>';
            }else{ 
            $row[]  = '<label class="label label-danger">'.$serData->statusShow.'</label>'; 
            } 
            
            $link    = 'javascript:void(0)';
            $action .= "";
             if($serData->status){

                $action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->taskId).'" data-url="adminapi/tasks/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-check" aria-hidden="true"></i></a>';
            }else{
                $action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->taskId).'" data-url="adminapi/tasks/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-times" aria-hidden="true"></i></a>';
            }
            $link_url      = base_url().'task-detail/'.encoding($serData->taskId);
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Detail"><i class="fa fa-eye"  aria-hidden="true"></i></a>';
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="'.encoding($serData->taskId).'" data-url="adminapi/tasks/recordDelete" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $link_url      = base_url().'admin/tasks/edit/'.encoding($serData->taskId);
            
            
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Edit"><i class="fa fa-edit"  aria-hidden="true"></i></a>';
            $row[]  = $action;
            $data[] = $row;

        }
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->task_model->count_all(),
            "recordsFiltered"   => $this->task_model->count_filtered(),
            "data"              => $data,
            "full_data"         => $list
        );
        //output to json format
        $this->response($output);
    }//end function     
    function activeInactiveStatus_post(){
        $id            = decoding($this->post('id'));
        $where              = array('taskId'=>$id);
        $dataExist          = $this->common_model->is_data_exists('tasks',$where);
        if($dataExist){
            $status         = $dataExist->status ? 0:1;
            $dataExist      = $this->common_model->updateFields('tasks',array('status'=>$status),$where);
            $showmsg        = ($status==1)? 'Task published successfully' : 'Task Unpublished successfully';
            $response       = array('status'=>SUCCESS,'message'=>$showmsg." ".ResponseMessages::getStatusCodeMessage(128),'change_status'=>$status);
        }else{
            $response       = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }//end function
    function recordDelete_post(){
          $id            = decoding($this->post('id'));
        $where              = array('taskId'=>$id);
        $dataExist      = $this->common_model->is_data_exists('tasks',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('tasks',$where);
            $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }//end function
    
    
    //recordDeleteFromTaskList
    function recordDeleteFromTaskList_post(){
        $id            = decoding($this->post('id'));
        $where              = array('taskId'=>$id);
        $dataExist      = $this->common_model->is_data_exists('tasks',$where);
        if($dataExist){
            $table = 'tasks';
            $this->common_model->updateFields($table, array('is_deleted'=>1), $where);
            $response   = array('status'=>SUCCESS,'message'=>'Task Deleted Successfully');
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }//end function
    
    function recordDeleteMeta_post(){
          $id            = decoding($this->post('id'));
        $where              = array('taskmetaId'=>$id);
        ///pr($where);
        $dataExist      = $this->common_model->is_data_exists('task_meta',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('task_meta',$where);
            $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }//end function
    function recordorderMeta_post(){
          $data            = $this->post();
          $orders = isset($data[0]) ?  json_decode($data[0],true) : array();
          $i=1;
          foreach ($orders as $k => $v) {
             $task_metaId = isset($v['metaid']) ? $v['metaid'] :0;
             if(!empty($task_metaId)){
                $where = array();
                $where              = array('taskmetaId'=>$task_metaId);
                $dataExist      = $this->common_model->is_data_exists('task_meta',$where);
                if($dataExist){
                        $this->common_model->updateFields('task_meta',array('sorting_order'=>$i),$where);
                        $i++;
                    $response   = array('status'=>SUCCESS,'message'=>'Record has re-arrange successfully.');
                }else{
                    $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
                }
             }
          }
        /*$where              = array('taskmetaId'=>$id);
        ///pr($where);
        $dataExist      = $this->common_model->is_data_exists('task_meta',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('task_meta',$where);
            $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }*/
        $this->response($response);
    }//end function
    // Compress image
    function compressedImage($source, $path, $quality) 
    {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);

        // Save image 
        imagejpeg($image, $path, $quality);
        // sReturn compressed image 
        return $path;

    }//End function
    public function alllist_post()
    {
        if(isset($_POST['limit']))
        {
            $limit= $this->post('limit');
            $this->db->limit($limit);    
        }
        
        if(isset($_POST['task_name']) && $_POST['task_name'] != "")
        {
            $company_name= $this->post('task_name');
            $this->db->like('name',$company_name);
        }
        
        // is_exported => 0 super admin task is_exported => 1 imported from admin is_exported => -1 all
        if(isset($_POST['is_exported']) && $_POST['is_exported'] != -1)
        {
            $this->db->where('is_exported',$_POST['is_exported']);
        }
        // status => 0 Inactive status => 1 Active status => -1 all
        if(isset($_POST['status']) && $_POST['status'] != -1)
        {
            $this->db->where('status',$_POST['status']);
        }
        
        $this->db->where('created_by',1);
        $this->db->order_by("taskId", "desc");
        $data = $this->db->get('tasks')->result();
        foreach ($data as $key => $value) {
           
           if($value->status == 0){
               $changeStatus = 'Unpublished';
           }else{
               $changeStatus = 'Published';
           }
           $value->change_status = $changeStatus;
           $value->id = encoding($value->taskId);
           $task_meta = $this->db->get_where('task_meta',array('taskId'=>$value->taskId)
            )->result();
            
            foreach ($task_meta as $key2 => $value2) {
                $value2->newId = encoding($value2->taskmetaId);
                if($value2->file != "")
                {
                    if($value2->fileType=='IMAGE')
                    {
                        $value2->file = base_url('uploads/task_image/').$value2->file;
                    }else if($value2->fileType=='VIDEO'){
                        $value2->file = base_url('uploads/task_video/').$value2->file;    
                    }
                        
                }
            }
            $value->meta_data = $task_meta;
            
        }
        echo json_encode(array('success'=>true,'task_list'=>$data));
    }
    
    // task filter api
    public function admintaskfilter_post()
    {
        if(isset($_POST['limit']))
        {
            $limit= $this->post('limit');
            $this->db->limit($limit);    
        }
        // is_exported => 0 super admin task is_exported => 1 imported from admin is_exported => -1 all
        if(isset($_POST['is_exported']) && $_POST['is_exported'] != -1)
        {
            $this->db->where('is_exported',$_POST['is_exported']);
        }
        // status => 0 Inactive status => 1 Active status => -1 all
        if(isset($_POST['status']) && $_POST['status'] != -1)
        {
            $this->db->where('status',$_POST['status']);
        }
        $this->db->where('created_by',1);
        $data = $this->db->get('tasks')->result();
        foreach ($data as $key => $value) {
           $value->id = encoding($value->taskId);
        }
        echo json_encode(array('success'=>true,'task_list'=>$data));
    }
    
    public function adminimportlist_post()
    {
        if(isset($_POST['limit']))
        {
            $limit= $this->post('limit');
            $this->db->limit($limit);    
        }
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $this->db->like('name',$_POST['name']);    
        }
        $this->db->where('created_by',0);
        $this->db->where('is_deleted',0);
        $data = $this->db->get('tasks')->result();
        foreach ($data as $key => $value) {
           $value->id = encoding($value->taskId);
        }
        echo json_encode(array('success'=>true,'task_list'=>$data));
    }
    
    // single tasks detail
    public function taskdetail_post()
    {
        $id = decoding($this->post('task_id'));      
        $data = $this->db->get_where('tasks',array('taskId'=>$id))->result();
        foreach ($data as $key => $value) {
            $this->db->order_by('sorting_order','asc');
            $meta_data = $this->db->get_where('task_meta',array('taskId'=>$id))->result();
            $value->id = encoding($value->taskId);
            foreach ($meta_data as $key2 => $value2) {
                $value2->newId = encoding($value2->taskmetaId);
                if($value2->file != "")
                {
                    if($value2->fileType=='IMAGE')
                    {
                        $value2->file = base_url('uploads/task_image/').$value2->file;
                    }else if($value2->fileType=='VIDEO'){
                        $value2->file = base_url('uploads/task_video/').$value2->file;    
                    }
                        
                }
            }
            $value->meta_data = $meta_data;
        }
        
        $task_detail              = $data[0];

        $response = array('success'=>true,'task_detail'=>$task_detail);
        echo json_encode($response);
    }
    // filter Session
    public function setSession_post()
    {
        $filter_value = $this->input->post('filter_value');
        $_SESSION['filter_value'] = $filter_value;
        echo json_encode($_SESSION['filter_value']);
    }
    
    public function setSessionStatus_post()
    {
        $filter_value = $this->input->post('status_value');
        $_SESSION['status_value'] = $filter_value;
        echo json_encode($_SESSION['status_value']);
    }
    
    // single company detail
    public function companydetail_post()
    {
        $id = decoding($this->post('company_id'));      
        $data = $this->db->get_where('company',array('company_id'=>$id))->result();
        foreach ($data as $key => $value) {
            $value->id = encoding($value->company_id);
            if($value->profile_photo != "")
            {
                $value->profile_photo = 'https://www.valere.io/leadsafe/uploads/company/'.$value->profile_photo;    
            }
            if($value->insurence_certificate != "")
            {
                $value->insurence_certificate = 'https://www.valere.io/leadsafe/uploads/company/'.$value->insurence_certificate;    
            }
            if($value->licence != "")
            {
                $value->licence = 'https://www.valere.io/leadsafe/uploads/company/'.$value->licence;    
            }
        }
        echo json_encode($data[0]);
    }
    
    // import
    public function updateTask($table,$taskId,$array)
    {
        $this->db->where('taskId',$taskId);
        $this->db->update($table,$array);
    }
    
    function DuplicateMySQLRecord ($table, $primary_key_field, $primary_key_val)
    {
       $this->db->where($primary_key_field, $primary_key_val); 
       $query = $this->db->get($table);
        foreach ($query->result() as $row){   
           foreach($row as $key=>$val){        
              if($key != $primary_key_field){
                $this->db->set($key, $val);               
              }           
           }
        }
        $this->db->insert($table); 
        return $this->db->insert_id();
    }
    
    public function copyMetadata($taskId)
    {
        $this->db->where('taskId',$taskId);
        $data = $this->db->get('task_meta')->result();
        foreach ($data as $key => $value) {
            $insertId = $this->DuplicateMySQLRecord('task_meta','taskId',$value->taskId);
        } 
    }
    
    public function superAdminimport_post()
    {
        $taskId         = decoding($_POST['id']);
        $importedid = $taskId;
        $array = array('is_deleted'=>1);
        $this->updateTask('tasks',$taskId,$array);
        
        $insertId = $this->DuplicateMySQLRecord('tasks','taskId',$taskId);
        $taskId = $insertId;
        
        // $this->copyMetadata($taskId);
        
        $ALlData = $this->db->get_where('task_meta',array('taskId'=>$importedid))->result();
        foreach($ALlData as $ALL){
            $InsertArray = array(
                'taskId' => $taskId,
                'fileType' => $ALL->fileType,
                'file' => $ALL->file,
                'description' => $ALL->description,
                'sorting_order' => $ALL->sorting_order
            );
            $this->db->insert('task_meta',$InsertArray);
        }
        
        
        $array = array('created_by'=>1,'is_exported'=>1);
        $this->updateTask('tasks',$taskId,$array);
        
        $response   = array('status'=>SUCCESS,'message'=>'Imported Successfully');
        $this->response($response);
    }
    
    public function addnew_post(){
        $data_val['name']       	= $this->post('name');
	    $data_val['description']       	= $this->post('description');
		$result   = $this->common_model->insertData('tasks',$data_val);
		$taskId   = $result;
		$textData = $this->input->post('textfile_1');
		$textData = json_decode($textData);
		
		foreach($textData as $key=>$value)
		{
			$array = array(
			    'fileType' => 'TEXT',
			    'description' => $value,
			    'taskId' => $taskId
			);
			$result = $this->common_model->insertData('task_meta',$array);
		}
     
        $textData = $this->input->post('inserted_steps');
		$textData = json_decode($textData);
		foreach($textData as $key=>$value)
		{
		    $this->db->where('taskmetaId',$value);
            $result = $this->db->update('task_meta',array('taskId'=>$taskId));
		}
    
        $msg = 'Task Added Successfully';
        if($result){
            $response = array('status'=>SUCCESS,'data'=>$_POST,'message'=>$msg,'url'=>base_url().'task-detail/'.encoding($taskId));
        }else{
            $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
    
        $this->response($response);
    }
    
    
}//End Class 
