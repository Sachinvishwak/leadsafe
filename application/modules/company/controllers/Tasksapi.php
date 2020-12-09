<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Tasksapi extends REST_Controller{
    public function __construct(){
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    
    function DuplicateMySQLRecord ($table, $primary_key_field, $primary_key_val)
    {
       $this->db->where($primary_key_field, $primary_key_val);
       $this->db->where('fileType!=', 'TEXT');
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
    
    /* add new */
    // public function addnew_post(){
    //     $data_val['name']         = $this->post('name');
    //   $data_val['description']        = $this->post('description');
    //   $data_val['project_id'] = $this->post('project_id');
    //   $data_val['company_id'] = $this->post('company_id');
    //   $data_val['task_status'] = $this->post('task_status');

    // $data_val['created_by'] = 0;
      
    // $result   = $this->common_model->insertData('tasks',$data_val);
    // $taskId   = $result;
    // $textData = $this->input->post('textfile_1');
    // $textData = json_decode($textData);
    
    // foreach($textData as $key=>$value)
    // {
    //   $array = array(
    //       'fileType' => 'TEXT',
    //       'description' => $value,
    //       'taskId' => $taskId
    //   );
    //   $result = $this->common_model->insertData('task_meta',$array);
    // }
     
    //     $textData = $this->input->post('inserted_steps');
    // $textData = json_decode($textData);
    // foreach($textData as $key=>$value)
    // {
    //     $this->db->where('taskmetaId',$value);
    //         $result = $this->db->update('task_meta',array('taskId'=>$taskId));
    // }
    
    //     $msg = 'Task Added Successfully';
    //     if($result){
    //         $response = array('status'=>SUCCESS,'data'=>$_POST,'message'=>$msg,'url'=>base_url().'task-detail/'.encoding($taskId));
    //     }else{
    //         $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
    //     }
    
    //     $this->response($response);
    // }
    /* end */
    
    // Task Progress
    public function taskprogress_post()
    {
        $completed_task = 0;
        $non_completed_task = 0;
        $project_id = $this->input->post('project_id');
        $data = $this->db->get_where('tasks',array('project_id'=>$project_id))->result();
        $numrows = $this->db->get_where('tasks',array('project_id'=>$project_id))->num_rows();
        foreach ($data as $key => $value) {
           if($value->task_status == 0)
           {
               $non_completed_task++;
           }else{
               $completed_task++;
           }
        }
        if($completed_task == 0)
        {
            echo json_encode(array('success'=>true,'message'=>'task progress Get Successfully','completed_task'=>"0.00",'non_completed_task'=>"0.00"));
        }else{
            $total = $non_completed_task + $completed_task;
            $completed_task = ( $completed_task / $total ) ;
            $completed_task = sprintf('%0.2f', round($completed_task, 2));
            echo json_encode(array('success'=>true,'message'=>'task progress Get Successfully','completed_task'=>$completed_task,'non_completed_task'=>"0.00"));
        }
    }
    
    public function addnew_post(){
        $this->form_validation->set_rules('name', 'name', 'trim|required|regex_match[/^([a-z0-9_ ])+$/i]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));    
        }else{ 
			$data_val['name']         = $this->post('name');
			$data_val['description']        = $this->post('description');
			$data_val['project_id'] = $this->post('project_id');
			$data_val['company_id'] = $this->post('company_id');
			//$data_val['created_by'] = 0;
			$data_val['role'] = $this->post('role');
			$data_val['created_by'] = 0;
			$data_val['user_id'] = $this->post('created_by');
			if($this->post('role') == "company" || $this->post('role') == "admin")
			{
				$msg = 'Task Added Successfully';
				$data_val['task_approved'] = 1;
			}
			else
			{
				$msg = 'Task Will be Added Successfully Once Admin Verified Your Added Task';
				$data_val['task_approved'] = 0;
			}
			$data_val['created_by_user_name'] = $this->post('created_by_user_name');
			$query = $this->db->get_where('tasks',array('project_id'=> $this->post('project_id'),'name'=>$this->post('name')))->num_rows();
			if ($query > 0){
				$response = array('status' => FAIL, 'message' => 'task Name already taken.','url'=>'');
				$this->response($response);
				die;
			}
			$result   = $this->common_model->insertData('tasks',$data_val);
			$taskId   = $result;
			$company_id = $this->post('company_id');
			if(isset($_POST['preopulated_ids']) && $_POST['preopulated_ids'] != 0)
			{
				$importedid = $this->post('preopulated_ids');
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
			}else{
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
			}
			if(isset($_POST['subcontractorIds']))
			{
				$contractorIds = json_decode($_POST['subcontractorIds']);
				foreach($contractorIds as $contractor)
				{
					$type = 'subcontractor';
					$contractorData = $this->db->get_where('contractor',array('id'=>$contractor))->result();
					if(isset($contractorData[0]))
					{
						$contractorData = $contractorData[0];
						$invitenewpeoplename = $contractorData->owner_first_name;
						$invitenewpeopleemail = $contractorData->email;
						$last_id = $contractorData->id;
						$this->inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for='task',$taskId,$type,$company_id,$last_id);    
					}
				}
			}
			if(isset($_POST['contractorIds']))
			{
				$contractorIds = json_decode($_POST['contractorIds']);
				foreach($contractorIds as $contractor)
				{
					$type = 'leadcontractor';
					$contractorData = $this->db->get_where('contractor',array('id'=>$contractor))->result();
					if(isset($contractorData[0]))
					{
						$contractorData = $contractorData[0];
						$invitenewpeoplename = $contractorData->owner_first_name;
						$invitenewpeopleemail = $contractorData->email;
						$last_id = $contractorData->id;
						$this->inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for='task',$taskId,$type,$company_id,$last_id);    
					}
				}
			}
			if(isset($_POST['crewIds']))
			{
				$crewIds = json_decode($_POST['crewIds']);
				foreach($crewIds as $crew)
				{
					$type = 'crew';
					$crewData = $this->db->get_where('crew_member',array('id'=>$crew))->result();
					if(isset($crewData[0])){
						$crewData = $crewData[0];
						$invitenewpeoplename = $crewData->name;
						$invitenewpeopleemail = $crewData->email;
						$last_id = $crewData->id;
						$this->inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for='task',$taskId,$type,$company_id,$last_id);   
					}
				}
			}

			if($this->post('role') == "company" || $this->post('role') == "admin")
			{
				$msg = 'Task Added Successfully';
			}
			else
			{
				$projectData = $this->db->get_where('project',array('id'=>$this->post('project_id')))->result();
				$projectData = $projectData[0];
				$msg = 'Task Will be Added Successfully Once Admin Verified Your Added Task';
				$data_val1['company_id'] = $projectData->company_id;
				$data_val1['task_id'] = $taskId;
				$data_val1['action'] = "add";
				$data_val1['message'] = "Task Added By The ".$this->post('created_by_user_name');
				$data_val1['type'] = "task";
				$data_val1['role'] = $this->post('role');
				$data_val1['created_by'] = $this->post('created_by');
				$this->common_model->insertData('task_approve_notifications',$data_val1);
			}
			if($result){
				$response = array('status'=>SUCCESS,'data'=>$_POST,'message'=>$msg,'url'=>base_url().'task-detail/'.encoding($taskId));
			}else{
				$response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
			}
        }
        $this->response($response);
	}
	
	public function getTaskRelatedNotification_post()
	{
		$company_id = $this->post('company_id');
        $data = $this->db->get('task_approve_notifications',array('company_id'=>$company_id))->result();
        echo json_encode(array('success'=>true,'data'=>$data,'message'=>'Task Related Notification Get Successfully'));
	}

	public function acceptTaskRelatedNotification_post()
	{
		$id = $this->post('id');
		$task_approve_notifications = $this->db->get_where('task_approve_notifications',array('id'=>$id))->result();
		$task_approve_notifications = $task_approve_notifications[0];
		if($task_approve_notifications->type == 'task')
		{
			if($task_approve_notifications->action == 'add')
			{
				$this->db->where('id',$task_approve_notifications->task_id);
				$array1 = array('task_approved'=>1);
				$this->db->update('tasks',$array1);	
			}else if($task_approve_notifications->action == 'delete')
			{
				$this->db->where('id',$task_approve_notifications->task_id);
				$this->db->delete('tasks');
			}
			else if($task_approve_notifications->action == 'edit')
			{
				$this->db->where('id',$task_approve_notifications->task_id);
				$array1 = array(
					'name'=>$task_approve_notifications->name,
					'description'=>$task_approve_notifications->description
				);
				$this->db->update('tasks',$array1);
			}
		}else if($task_approve_notifications->type == 'tasksteps')
		{
			if($task_approve_notifications->action == 'add')
			{
				$this->db->where('taskmetaId',$task_approve_notifications->task_setps_id);
				$array1 = array('task_approved'=>1);
				$this->db->update('task_meta',$array1);	
			}else if($task_approve_notifications->action == 'delete')
			{
				$this->db->where('taskmetaId',$task_approve_notifications->task_setps_id);
				$this->db->delete('task_meta');
			}else if($task_approve_notifications->action == 'edit')
			{
				$this->db->where('taskmetaId',$task_approve_notifications->task_setps_id);
				$array1 = array(
					'description'=>$task_approve_notifications->description
				);
				$this->db->update('task_meta',$array1);
			}
		}
		
		$this->db->where('id',$id);
		$array = array('is_approve'=>1);
		$this->db->update('task_approve_notifications',$array);	
        echo json_encode(array('success'=>true,'data'=>$array,'message'=>'Action Accepted Successfully'));
	}

	public function deleteTaskRelatedNotification_post()
	{
		$id = $this->post('id');
		$this->db->where('id',$id);
        $this->db->delete('task_approve_notifications');
        echo json_encode(array('success'=>true,'data'=>array(),'message'=>'Action Deleted Successfully'));
	}
        
    public function add_post(){
        $company_id = $this->post('company_id');
        $this->form_validation->set_rules('name', 'name', 'trim|required|regex_match[/^([a-z0-9_ ])+$/i]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));    
        }else{
          $data_val['name']         = $this->post('name');
          $data_val['description']        = $this->post('description');
          //$data_val['contractor'] = $this->post('contractor');
          $data_val['project_id'] = $this->post('project_id');
          $data_val['company_id'] = $this->post('company_id');
          $data_val['role'] = 'company';
          $data_val['created_by'] = 0;
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
              $data_val1['name']        = $this->post('name');
          $data_val1['description']         = $this->post('description');



            $query1 = $this->db->get_where('tasks',array('project_id'=>$this->post('project_id'),'taskId!='=> decoding($this->post('id')),'name'=>$this->post('name')))->num_rows();
          
          
            if ($query1 > 0){
                $response = array('status' => FAIL, 'message' => 'Task Name already taken.');
                $this->response($response);
                die;
            } 
            else
            {
				$result                 = $this->common_model->updateFields('tasks',$data_val1,$where);
				$msg                    = "Record updated successfully.";
				$taskId   = $id ;
            }

          }else{
              
        $query = $this->db->get_where('tasks',array('project_id'=> $this->post('project_id'),'name'=>$this->post('name')))->num_rows();
        if ($query > 0){
            $response = array('status' => FAIL, 'message' => 'Task Name already taken.','url'=>'');
            $this->response($response);
            die;
        }  
            $result                 = $this->common_model->insertData('tasks',$data_val);
            $msg                    = "Record added successfully.";
                 $taskId   = $result ;
          }


            if(!empty($taskId)){
                if(!empty($textDeleteId)){
                    $this->common_model->deleteDataTaskMeta('task_meta',array('taskId'=>$taskId),$textDeleteId);
                }    
                for ($x=0; $x <sizeof($textData) ; $x++) { 
                    $textDatatext                   = array();
                    $textId                            =  $textData[$x]['textfileId'];
                    $textDatatext['description']       = $textData[$x]['textfile'];
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
            
            // copy
            if(isset($_POST['importedids'])){
                $importedids = $this->post('importedids');
                foreach($importedids as $importedid){
                    $ALlData = $this->db->get_where('task_meta',array('taskId'=>$importedid,'fileType!='=>'TEXT'))->result();
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
                }
            }
            
            if(isset($_POST['contractorIds']))
            {
                $contractorIds = $_POST['contractorIds'];
                foreach($contractorIds as $contractor)
                {
                    $type = 'leadcontractor';
                    $contractorData = $this->db->get_where('contractor',array('id'=>$contractor))->result();
                    $contractorData = $contractorData[0];
                    $invitenewpeoplename = $contractorData->owner_first_name;
                    $invitenewpeopleemail = $contractorData->email;
                    $last_id = $contractorData->id;
                    $this->inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for='task',$taskId,$type,$company_id,$last_id);    
                }
            }
            if(isset($_POST['crewIds']))
            {
                $crewIds = $_POST['crewIds'];
                foreach($crewIds as $crew)
                {
                    $type = 'crew';
                    $crewData = $this->db->get_where('crew_member',array('id'=>$crew))->result();
                    $crewData = $crewData[0];
                    $invitenewpeoplename = $crewData->name;
                    $invitenewpeopleemail = $crewData->email;
                    $last_id = $crewData->id;
                    $this->inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for='task',$taskId,$type,$company_id,$last_id);    
                }
            }
            
            if($result){
                //$text_meta_val['taskId'] = $result;
                //$this->common_model->insertData('task_meta',$text_meta_val);
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'admin/task-detail/'.encoding($taskId));
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
        }
        $this->response($response);
    }//end function 
    
    public function ajaxForTaskPeopleinvite_post()
    {
        if(isset($_POST['invitenewpeoplename']))
        {
            $invitenewpeoplename = $_POST['invitenewpeoplename'];
        }
        if(isset($_POST['invitenewpeopleemail']))
        {
            $invitenewpeopleemail = $_POST['invitenewpeopleemail'];
        }
        if(isset($_POST['invitenewpeopleposition']))
        {
            $invitenewpeopleposition = $_POST['invitenewpeopleposition'];
        }
        $reciever_type = $_POST['role'];
        $receiver_id = $_POST['userid'];
        if($reciever_type == "leadcontractor" || $reciever_type == "subcontractor")
        {
            $reciever_data = $this->db->get_where('contractor',array('id'=>$receiver_id))->result();
            $reciever_data = $reciever_data[0];
            $invitenewpeoplename= $reciever_data->owner_first_name;
            $invitenewpeopleemail= $reciever_data->email;
        }else if($reciever_type == "crew")
        {
            $reciever_data = $this->db->get_where('crew_member',array('id'=>$receiver_id))->result();
            $reciever_data = $reciever_data[0];
            $invitenewpeoplename= $reciever_data->name;
            $invitenewpeopleemail= $reciever_data->email;
        }
        $is_for ='task';
        $taskId = $_POST['task_id'];
        $last_id = $_POST['userid'];
        $company_id = $_POST['company_id'];
        $this->inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for,$taskId,$reciever_type,$company_id,$last_id);
        $msg = 'People Invited Successfulyy';
        $response              = array('status'=>SUCCESS,'message'=>$msg);
        $this->response($response);
    }
    
    // Create People and invite 
    public function inviteNewPeopleCreateandSend_post()
    {
        $is_for = $_POST['is_for'];
        $task_id = $_POST['task_id'];
        $reciever_type = $_POST['reciever_type'];
        $sender_type = $_POST['sender_type'];
        $company_id = $_POST['company_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $last_id = 0;
        if($reciever_type == "leadcontractor" || $reciever_type == "subcontractor")
        {
            $reciever_data = $this->db->get_where('contractor',array('email'=>$email))->result();
            if(isset($reciever_data[0]))
            {
                $reciever_data = $reciever_data[0];
                $last_id = $reciever_data->id;
                $name = $reciever_data->owner_first_name;
            }else{
                $datainsert = array('owner_first_name'=>$name,'email'=>$email);
                $this->db->insert('contractor',$datainsert);
                $last_id = $this->db->insert_id();
            }
        }else if($reciever_type == "crew")
        {
            $reciever_data = $this->db->get_where('crew_member',array('email'=>$email))->result();
            if(isset($reciever_data[0]))
            {
                $reciever_data = $reciever_data[0];
                $last_id = $reciever_data->id;
                $name = $reciever_data->name;
            }else{
                $datainsert = array('name'=>$name,'email'=>$email);
                $this->db->insert('crew_member',$datainsert);
                $last_id = $this->db->insert_id();
            }
        }

        $this->inviteNewPeople($name,$email,$is_for,$task_id,$reciever_type,$company_id,$last_id);

        $response = array('status'=>SUCCESS,'message'=>'People Invited Successfully');
        echo json_encode($response);
    }
    
    /* invite */
    function inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for,$taskId,$type,$company_id,$last_id)
    {
        $array = array(
            'sender_id' => $company_id,
            'sender_type' => 'company',
            'receiver_id' => $last_id,
            'reciever_type' => $type,
            'is_for' => $is_for,
            'taskId'=>$taskId
        );
        $project_data = $this->db->get_where('tasks',array('taskId'=>$taskId))->result();
        $project_data = $project_data[0];
        $this->db->insert('invite',$array);
        $id = $this->db->insert_id();
        $link = base_url().'invitation/'.encoding($id);
        $data1['full_name']  = $invitenewpeoplename;
        $data1['url']        = $link;
        $data1['sender_type']  = 'company';
        $data1['is_role']  = $is_for;
        $data1['title']  = $project_data->name;
        $message            = $this->load->view('emails/work_invite',$data1,TRUE);
        $subject = 'Bild It - Task Invitation link';
        $to = $invitenewpeopleemail;
        $this->common_model->sendMemberMail($to,$link,$message,$subject);
    }
    
    public function detail_post()
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
        
        $involved_people = array();
        $noninvolved_people = array();
        $invite_people_data = $this->db->get_where('invite_people',array('taskId'=>$id))->result();
        foreach($invite_people_data as $value)
        {
            if($value->role == 'leadcontractor' || $value->role == 'subcontractor')
            {
                $user_data = $this->db->get_where('contractor',array('id'=>$value->user_id))->result();
                if(isset($user_data[0]))
                {
                    $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->owner_last_name;
                    $value->user_email = $user_data[0]->email;    
                }else{
                    $value->user_name = "";
                    $value->user_email = "";
                }
            }else if($value->role == 'crew'){
                $user_data = $this->db->get_where('crew_member',array('id'=>$value->user_id))->result();
                if(isset($user_data[0]))
                {
                    $value->user_name = $user_data[0]->name;
                    $value->user_email = $user_data[0]->email;    
                }else{
                    $value->user_name = "";
                    $value->user_email = "";
                }
            }
            if($value->user_name != "" && $value->user_email != "")
            {
                if($value->is_removed == 0)
                {
                    array_push($involved_people,$value);
                }else{
                    array_push($noninvolved_people,$value);
                }   
            }
        }

        $response = array('success'=>true,'task_detail'=>$task_detail,'involved_people'=>$involved_people,'noninvolved_people'=>$noninvolved_people);
        echo json_encode($response);
    }
    
    public function updateTask($table,$taskId,$array)
    {
        $this->db->where('taskId',$taskId);
        $this->db->update($table,$array);
    }
    
    public function addTaskStep_post(){
        $this->form_validation->set_rules('id', 'id', 'trim|required');
        $this->form_validation->set_rules('taskstepId', 'task step', 'trim|required');
     
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));    
        }else{
            $taskId                  = decoding($this->post('id'));
            $where                      = array('taskId'=>$taskId);
            $isExist                    = $this->common_model->is_data_exists('tasks',$where);

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

                        $isImage                          = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$imagefileId));
                        if($isImage){
                            $this->common_model->updateFields('task_meta',$data_val,array('taskmetaId'=>$imagefileId));
                            $status = SUCCESS;
                            $msg    = "Layer record updated successfully.";
                        }else{
                            $data_val['taskId']     = $taskId;
                            $this->common_model->insertData('task_meta',$data_val);
                            $status = SUCCESS;
                            $msg    = "Layer record added successfully.";
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
                            $status = SUCCESS;
                            $msg    = "Layer record added successfully.";
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
                        $msg    = "Layer record added successfully.";
                    }
                    break;
            }
             $response              = array('status'=>$status,'message'=>$msg);

            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
        }
        $this->response($response);
    }//end function 
    public function list_post(){
        $this->load->helper('text');
        $this->load->model('Task_model');
        $this->Task_model->set_data();
        $list       = $this->Task_model->get_list();
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
           
            $link_url      = base_url().'admin/task-detail/'.encoding($serData->taskId);
            $row[]  = '<a href="'.$link_url.'">'.display_placeholder_text($serData->name).'</a>'; 
            $row[]      = display_placeholder_text((mb_substr($serData->description, 0,100, 'UTF-8') .((strlen($serData->description) >100) ? '...' : '')));
            $uploaded_media = '';
            foreach($serData->task_meta as $task_meta)
            {
                $uploaded_media      .= '<label> TYPE:'.$task_meta->fileType.'<br>FILENAME: '.$task_meta->file.'<br></label>';    
            }
            if($uploaded_media == "")
            {
                $uploaded_media = '<label>No Media Uploaded Yet..</label>';
            }
            $row[]  = $uploaded_media;
            if($serData->status){
            $row[]  = '<label class="label label-success">'.$serData->statusShow.'</label>';
            }else{ 
            $row[]  = '<label class="label label-danger">'.$serData->statusShow.'</label>'; 
            } 
            $link    = 'javascript:void(0)';
            $action .= "";
             if($serData->status){

                $action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->taskId).'" data-url="company/Tasksapi/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-check" aria-hidden="true"></i></a>';
            }else{
                $action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->taskId).'" data-url="company/Tasksapi/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-times" aria-hidden="true"></i></a>';
            }
            $link_url      = base_url().'admin/task-detail/'.encoding($serData->taskId);
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Detail"><i class="fa fa-eye"  aria-hidden="true"></i></a>';
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="'.encoding($serData->taskId).'" data-url="company/Tasksapi/recordDelete" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $link_url      = base_url().'company/tasks/edit/'.encoding($serData->taskId);
            
            
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Edit"><i class="fa fa-edit"  aria-hidden="true"></i></a>';
            $row[]  = $action;
            $data[] = $row;

        }
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->Task_model->count_all(),
            "recordsFiltered"   => $this->Task_model->count_filtered(),
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
            $response       = array('status'=>SUCCESS,'message'=>$showmsg." ".ResponseMessages::getStatusCodeMessage(128));
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
    public function alllist_get()
    {
        if(isset($_POST['limit']))
        {
            $limit= $this->post('limit');
            $this->db->limit($limit);    
        }
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
            $this->db->order_by('taskmetaId','desc');
            $meta_data = $this->db->get_where('task_meta',array('taskId'=>$id))->result();
            $value->id = encoding($value->taskId);
            foreach ($meta_data as $key2 => $value2) {
                $value2->file = base_url('uploads/').$value2->file;
            }
            $value->meta_data = $meta_data;
        }
        
        $task_detail              = $data[0];

        $response = array('success'=>true,'task_detail'=>$task_detail);
        echo json_encode($response);
    }
    
    // invite people 
    public function invited_task_people_post()
    {
        $id = $this->post('task_id');
        $name = $this->post('name');
        
        $this->db->where('taskId',$id);
        if($name != "")
        {
            $this->db->like('person_name', $name);
        }
        $invite_people_data = $this->db->get('invite_people')->result();
        
        $involved_people_array = array();
        $non_involved_people_array = array();
        foreach($invite_people_data as $value)
        {
            if($value->role == 'leadcontractor')
            {
                $user_data = $this->db->get_where('contractor',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->owner_last_name;
                $value->user_email = $user_data[0]->owner_first_name.' '.$user_data[0]->email;
            }else{
                $user_data = $this->db->get_where('crew_member',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->name;
                $value->user_email = $user_data[0]->owner_first_name.' '.$user_data[0]->email;
            }
            if($value->is_removed == 0)
            {
                array_push($involved_people_array,$value);
            }else{
                array_push($non_involved_people_array,$value);
            }
        }
        $response = array('success'=>true,'involved_people_list'=>$involved_people_array,'non_involved_people_list'=>$non_involved_people_array);
        echo json_encode($response);
    }
    
    //Remove Task People
    // invite people 
    public function invited_task_remove_people_post()
    {
        $id = $this->post('task_id');
        $user_id = $this->post('user_id');
        
        $array = array(
            'is_removed' => 1
        );
        $this->db->where('taskId',$id);
        $this->db->where('user_id',$user_id);
        $this->db->update('invite_people',$array);
        
        $response = array('success'=>true,'message'=>'People Removed Successfully');
        echo json_encode($response);
    }
    
    // Task Detail Api
    public function taskDetailApi_post()
    {
        $id = $this->post('task_id');
        $task_data = $this->db->get_where('tasks',array('taskId'=>$id))->result();
        
        $task_meta_data = $this->db->get_where('task_meta',array('taskId'=>$id))->result();
        
        $response = array('success'=>true,'task_data'=>$task_data,'task_meta_data'=>$task_meta_data);
        echo json_encode($response);
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}//End Class 
