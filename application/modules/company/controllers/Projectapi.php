<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Projectapi extends REST_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
    }
    
    // api for project list
    public function projectlist_post()
    {
        $this->db->where('company_id',$_POST['company_id']);
        $this->db->order_by("id","desc");
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $name = $_POST['name'];
            $this->db->like('name', $name);
        }
        if(isset($_POST['status']) && $_POST['status'] != -1 && $_POST['status'] != "")
        {
            $status = $_POST['status'];
            $this->db->where('status', $status);
        }
        $data = $this->db->get('project')->result();
        foreach($data as $value)
        {
            $value->encrypt_id = encoding($value->id);
            if($value->document != null)
            {
                $value->document = base_url('uploads/project/').$value->document;
			}
		}
		
		if(isset($_POST['projectFilter']))
		{
			$array = array();
			$object = new stdClass();
			$object->id = '-1';
			$object->name = 'Select Project';
			$object->project_description = '';
			$object->status = '';
			$object->client = '';
			$object->project_category = '';
			$object->document = '';
			$object->company_id = '';
			$object->start_date = '';
			$object->end_date = '';
			$object->created_at = '';
			$object->updated_at = '';
			$object->encrypt_id = '';
			array_push($array,$object);
			array_push($array,$data);
			$response       = array('status'=>SUCCESS,'data'=>$array,'message'=>'Project List Get Succesfully');
			
		}else{
			$response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Project List Get Succesfully');
		}

        
        $this->response($response);
    }
    // project detail api
    public function projectdetail_post()
    {
        $company_id = $this->post('company_id');
        $project_id = $this->post('project_id');
        $data = $this->db->get_where('project',array('id'=>$project_id,'company_id'=>$company_id))->result();
        if(!isset($data[0]))
        {     
            $response       = array('status'=>FAIL,'data'=>$data,'message'=>'Project Not Exist');
        }else{
            $data = $data[0];   
            $client_id = $data->client;
            $clientData = $this->db->get_where('client',array('id'=>$client_id))->result();
            if(isset($clientData[0]))
                $data->client_data = $clientData[0];
            else
            {
                $obj1 = new \stdClass;
                $obj1->id= "";
                $obj1->name= "";
                $obj1->email= "";
                $obj1->profile_photo= "";
                $obj1->password= "";
                $obj1->address= "";
                $obj1->phone_number= "";
                $obj1->document= "";
                $obj1->status = "";
                $obj1->company_id = "";
                $obj1->is_notify = "";
                $obj1->created_at = "";
                $obj1->updated_at = "";
                $obj1->passToken = "";
                $obj1->authToken = "";
                $data->client_data = $obj1;
            }

            $data->encrypt_id = encoding($data->id);
            if($data->document != null)
            {
                $data->document = base_url('uploads/project/').$data->document;
            }
            
            $data->is_exit = "1";
            
            $dataCheck = $this->db->select('company_member_relations.type, client.*')
                 ->from('company_member_relations')
                 ->join('client', 'company_member_relations.member_id = client.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->where('company_member_relations.type','client')
                 ->where('client.id',$data->client)->get()->num_rows();
            if($dataCheck == 0)
            {
                $data->is_exit = "0";
            }
            
            $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Project Detail Get Succesfully');
        }
        
        $this->response($response);
    }

    public function list_post(){
        $this->load->helper('text');
        $this->load->model('Project_model');
        $this->Project_model->set_data();
        $list       = $this->Project_model->get_list();
        $data       = array();
        $no         = $_POST['start'];
        foreach ($list as $serData) { 
            $action = '';
            $no++;
            $row    = array();
            $row[]  = $no;
            $link_url      = base_url().'admin/project-detail/'.encoding($serData->id);
            $link_url_detail = base_url().'admin/project-detail/'.encoding($serData->id).'?type=view';
            
            $row[]  = '<a href="'.$link_url.'">'.  mb_strimwidth($serData->name, 0, 60, "...") .'</a>'; 
            if($serData->status == 0)
            {
                $row[]      = 'In-progress';
            }else{
                $row[]      = 'Completed';   
            }
            
            $row[]      = date('d M Y',strtotime($serData->start_date));
            
            $link    = 'javascript:void(0)';
            $action .= "";
             if($serData->status){

                $action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->id).'" data-url="company/Projectapi/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-check" aria-hidden="true"></i></a>';
            }else{
                $action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->id).'" data-url="company/Projectapi/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-times" aria-hidden="true"></i></a>';
            }
            $link_url      = base_url().'admin/project-detail/'.encoding($serData->id);
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url_detail.'"  class="on-default edit-row table_action" title="Detail"><i class="fa fa-eye"  aria-hidden="true"></i></a>';
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="'.encoding($serData->id).'" data-url="company/Projectapi/recordDelete" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $link_url      = base_url().'company/project/edit/'.encoding($serData->id);
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Edit"><i class="fa fa-edit"  aria-hidden="true"></i></a>';
            $row[]  = $action;
            $data[] = $row;

        }
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->Project_model->count_all(),
            "recordsFiltered"   => $this->Project_model->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        $this->response($output);
    }
    
    function activeInactiveStatus_post(){
        $id            = decoding($this->post('id'));
        $where              = array('id'=>$id);
        $dataExist          = $this->common_model->is_data_exists('project',$where);
        if($dataExist){
            $status         = $dataExist->status ? 0:1;
            $showmsg        = ($status==1)? 'Project Completed Successfully' : 'Project In-Progress Successfully';
            $changeStatus = 0;
            if($dataExist->status == 0)
            {
                $changeStatus = 1;
                $allprojectTaskCheck = $this->db->get_where('tasks',array('project_id'=>$id))->num_rows();
                if($allprojectTaskCheck > 0)
                {
                    $allprojectTask = $this->db->get_where('tasks',array('task_status'=>0,'project_id'=>$id))->num_rows();
                    $showmsg = $allprojectTask;
                    if($allprojectTask > 0)
                    {
                        $showmsg = "Project Can not be change please check task status.";
                        $response       = array('status'=>FAIL,'message'=>$showmsg." ".ResponseMessages::getStatusCodeMessage(128));
                    }else{
                        $dataExist      = $this->common_model->updateFields('project',array('status'=>$status),$where);
                        $response       = array('status'=>SUCCESS,'message'=>$showmsg." ".ResponseMessages::getStatusCodeMessage(128));
                    }
                }else{
                    $dataExist      = $this->common_model->updateFields('project',array('status'=>$status),$where);
                    $response       = array('status'=>SUCCESS,'message'=>$showmsg." ".ResponseMessages::getStatusCodeMessage(128));
                }
            }else{
                $changeStatus = 0;
                $allprojectTaskCheck = $this->db->get_where('tasks',array('project_id'=>$id))->num_rows();
                if($allprojectTaskCheck > 0)
                {
                    $allprojectTask = $this->db->get_where('tasks',array('task_status'=>0,'project_id'=>$id))->num_rows();
                    $showmsg = $allprojectTask;
                    if($allprojectTask > 0)
                    {
                       $dataExist      = $this->common_model->updateFields('project',array('status'=>$status),$where);
                       $response       = array('status'=>SUCCESS,'message'=>$showmsg." ".ResponseMessages::getStatusCodeMessage(128));
                    }else{
                        $showmsg = "project status changed.";
                        $response       = array('status'=>FAIL,'message'=>$showmsg." ".ResponseMessages::getStatusCodeMessage(128));
                    }
                }else{
                    $dataExist      = $this->common_model->updateFields('project',array('status'=>$status),$where);
                    $response       = array('status'=>SUCCESS,'message'=>$showmsg." ".ResponseMessages::getStatusCodeMessage(128));
                }
            }
        }else{
            $response       = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }//

    public function recordDelete_post()
    {
        $id            = decoding($this->post('id'));
        $where              = array('id'=>$id);
        $dataExist      = $this->common_model->is_data_exists('project',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('project',$where);
            $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }
    
    function add_post(){
        $myCheckboxes = $this->input->post('myCheckboxes');
        $this->load->model('Project_model');
        // $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
   		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z0-9_ ])+$/i]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            //die;
            $email                          =  $this->post('email');
            $name                       =  $this->post('name');
            $userData['name']           =   $name;
            $userData['project_description']      =   $this->post('project_description');
            $userData['client']      =   $this->post('client');
            $userData['company_id']      =   $this->post('id');
            $userData['end_date']      =   $this->post('end_date');
            $userData['status']      =   $this->post('status');
            $userData['start_date']      =   $this->post('start_date');
            // profile pic upload
            $config['upload_path']= "./uploads/project/";
            $config['allowed_types']='*';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            
            
            $licence = $_FILES['licence']['name'];
            if($licence != "")
            {
                if($this->upload->do_upload('licence'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $licence= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
                $userData['document']                      = $licence;
            }
            $result = $this->Project_model->registration($userData);
            $project_id = $result['returnData'][0]->id;
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                    
                        $client_id = $this->post('client');
                        $client_data = $this->db->get_where('client',array('id'=>$client_id))->result();
                        $client_data = $client_data[0];
                        $client_email = $client_data->email;
                        $array = array(
                            'sender_id' => $this->post('id'),
                            'sender_type' => 'company',
                            'receiver_id' => $this->post('client'),
                            'reciever_type' => 'client',
                            'is_for' => 'project',
                            'project_id'=>$project_id
                        );
                        $project_data = $this->db->get_where('project',array('id'=>$project_id))->result();
                        $project_data = $project_data[0];
                        $this->db->insert('invite',$array);
                        $id = $this->db->insert_id();
                        $link = base_url().'invitation/'.encoding($id);
                        $data1['full_name']  = $client_data->name;
                        $data1['url']        = $link;
                        $data1['sender_type']  = 'company';
                        $data1['is_role']  = 'project';
                        $data1['title']  = $project_data->name;
                        $message            = $this->load->view('emails/work_invite',$data1,TRUE);
                        $subject = 'Bild It - Project Invitation link';
                        $to = $client_email;
                        $this->common_model->sendMemberMail($to,$link,$message,$subject);
                    
                    
                        $response = array('status'=>SUCCESS,'message'=>'Project Added Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/project');
                    break;
                    case "AE": // User already registered
                        $response = array('status'=>FAIL,'message'=>'Project Already Registered','users'=>array());
                    break;
                    default:
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'userDetail'=>array());
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'userDetail'=>array());
            }   
            $this->response($response);
        }
    }
    
    function edit_post(){
        $this->load->model('Project_model');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z0-9_ ])+$/i]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            $email                          =  $this->post('email');
            $name                       =  $this->post('name');
            $userData['name']           =   $name;
            $userData['project_description']      =   $this->post('project_description');
            
            
            $userData['client']      =   $this->post('client');
            
            // invitation
            // if(isset($_POST['company_id']))
            // {
            //     $client_id = $this->post('client');
            //     $client_data = $this->db->get_where('client',array('id'=>$client_id))->result();
            //     $client_data = $client_data[0];
            //     $client_email = $client_data->email;
            //     $array = array(
            //         'sender_id' => $_POST['company_id'],
            //         'sender_type' => 'company',
            //         'receiver_id' => $this->post('client'),
            //         'reciever_type' => 'client',
            //         'is_for' => 'project',
            //         'project_id'=>decoding($this->input->post('id'))
            //     );
            //     $project_data = $this->db->get_where('project',array('id'=>decoding($this->input->post('id'))))->result();
            //     $project_data = $project_data[0];
            //     $this->db->insert('invite',$array);
            //     $id = $this->db->insert_id();
            //     $link = base_url().'invitation/'.encoding($id);
            //     $data1['full_name']  = $client_data->name;
            //     $data1['url']        = $link;
            //     $data1['sender_type']  = 'company';
            //     $data1['is_role']  = 'project';
            //     $data1['title']  = $project_data->name;
            //     $message            = $this->load->view('emails/work_invite',$data1,TRUE);
            //     $subject = 'Leadsafe - Project Invitation link';
            //     $to = $client_email;
            //     $this->common_model->sendMemberMail($to,$link,$message,$subject);
            // }
            // end
            
            
            $userData['end_date']      =   $this->post('end_date');
            $userData['status']      =   $this->post('status');
            $userData['start_date']      =   $this->post('start_date');
            
            // profile pic upload
            $config['upload_path']= "./uploads/project/";
            $config['allowed_types']='*';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            $licence = $_FILES['licence']['name'];
            if($licence != "")
            {
                if($this->upload->do_upload('licence'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $licence= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
                $userData['document']                      = $licence;
            }
            
            // project invitaion
            $project_id = decoding($this->input->post('id'));
            $client_id = $this->post('client');
            $client_data = $this->db->get_where('client',array('id'=>$client_id))->result();
            $client_data = $client_data[0];
            $client_email = $client_data->email;
            $array = array(
                'sender_id' => $this->post('company_id'),
                'sender_type' => 'company',
                'receiver_id' => $this->post('client'),
                'reciever_type' => 'client',
                'is_for' => 'project',
                'project_id'=>$project_id
            );
            $project_data = $this->db->get_where('project',array('id'=>$project_id))->result();
            $project_data = $project_data[0];
            $this->db->insert('invite',$array);
            $id = $this->db->insert_id();
            $link = base_url().'invitation/'.encoding($id);
            $data1['full_name']  = $client_data->name;
            $data1['url']        = $link;
            $data1['sender_type']  = 'company';
            $data1['is_role']  = 'project';
            $data1['title']  = $project_data->name;
            $message            = $this->load->view('emails/work_invite',$data1,TRUE);
            $subject = 'Bild It - Project Invitation link';
            $to = $client_email;
            $this->common_model->sendMemberMail($to,$link,$message,$subject);
            //end

			$query = $this->db->get_where('project',array('name'=>$this->post('name'),'id!='=> decoding($this->input->post('id'))))->num_rows();
           
            if($query > 0)
            {
                 $response              = array('status'=>FAIL,'message'=>' Project Name Already Taken');   
            }
            else    
                {

            $this->db->where('id',decoding($this->input->post('id')));
            $result = $this->db->update('project',$userData);
            $msg = 'Project Details Updated Successfully';

            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'admin/project-detail/'.$this->input->post('id'));
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
           		 }   
               }
            $this->response($response);   

              }

    }
    
    function ChangeTaskStatus_post()
    {
        $taskId         = $_POST['taskId'];
        $this->db->where('taskId',$taskId);
        $tasks_data = $this->db->get('tasks')->result();
        $tasks_data = $tasks_data[0];
        $projectwhere              = array('id'=>$tasks_data->project_id);
        $code = "0";
        if($tasks_data->task_status == 0)
        {
            $task_status = "1";
            $allprojectTask = $this->db->get_where('tasks',array('task_status'=>0,'taskId !='=>$taskId,'project_id'=>$tasks_data->project_id))->num_rows();
            if($allprojectTask <= 0)
            {
                $where              = array('id'=>$tasks_data->project_id);
                $dataExist          = $this->common_model->is_data_exists('project',$where);
                if($dataExist->status == 0)
                {
                    $code = encoding($tasks_data->project_id);
                }
                $this->common_model->updateFields('project',array('status'=>1),$projectwhere);                
            }
        }else{
            $task_status = "0";
            $where              = array('id'=>$tasks_data->project_id);
            $dataExist          = $this->common_model->is_data_exists('project',$where);
            if($dataExist->status == 1)
            {
                $code = encoding($tasks_data->project_id);
            }
            $this->common_model->updateFields('project',array('status'=>$task_status),$projectwhere);
        }
        $set        = array('task_status'=> $task_status); 
        $where          = array('taskId' => $taskId);
        $update     = $this->common_model->updateFields('tasks', $set, $where);
        
        $data = array('status'=>'success','data'=>$task_status,'code'=>$code);
        echo json_encode($data);
	}
	
	function ChangeTaskApprovel_post()
    {
        $taskId         = $_POST['taskId'];
        $this->db->where('taskId',$taskId);
        $tasks_data = $this->db->get('tasks')->result();
        $tasks_data = $tasks_data[0];
        $projectwhere              = array('project_id'=>$tasks_data->project_id);
		$code = "0";
		
		if($tasks_data->task_approved == 0)
        {	
            $task_status = "1";
            $allprojectTask = $this->db->get_where('tasks',array('task_approved'=>0,'project_id'=>$tasks_data->project_id))->num_rows();
            if($allprojectTask <= 0)
            {
                $where              = array('project_id'=>$tasks_data->project_id);
				$dataExist          = $this->common_model->is_data_exists('tasks',$where);
				
                if($dataExist->task_approved == 0)
                {
                    $code = encoding($tasks_data->project_id);
                }
                $this->common_model->updateFields('tasks',array('task_approved'=>1),$projectwhere);                
            }
        }else{
			$task_status = "0";
            $allprojectTask = $this->db->get_where('tasks',array('task_approved'=>1,'project_id'=>$tasks_data->project_id))->num_rows();
            if($allprojectTask <= 0)
            {
                $where              = array('project_id'=>$tasks_data->project_id);
				$dataExist          = $this->common_model->is_data_exists('tasks',$where);
				
                if($dataExist->task_approved == 1)
                {
                    $code = encoding($tasks_data->project_id);
                }
                $this->common_model->updateFields('tasks',array('task_approved'=>0),$projectwhere);                
            }
        }
        $set        = array('task_approved'=>$task_status); 
        $where          = array('taskId' => $taskId);
        $update     = $this->common_model->updateFields('tasks',$set, $where);
        
        $data = array('status'=>'success','data'=>$task_status,'code'=>$code);
        echo json_encode($data);
       
    }
    
    function removeContractor_post()
    {
        $taskId         = $_POST['taskId'];
        $contractor   = $_POST['ContractorId'];
        $insert_array = array('task_id'=>$taskId,'person_id'=>$contractor,'type'=>'contractor');
        $this->db->insert('removed_people',$insert_array);
        $this->db->where('taskId',$taskId);
        $this->db->update('tasks',array('contractor'=>0));
        $data = array('status'=>'success','data'=>"");
        echo json_encode($data);
    }
    
    function removeCrewMember_post()
    {
        $taskId         = $_POST['taskId'];
        $CrewMemberId   = $_POST['CrewMemberId'];
        $insert_array = array('task_id'=>$taskId,'person_id'=>$CrewMemberId,'type'=>'crew');
        $this->db->insert('removed_people',$insert_array);
        $this->db->where('taskId',$taskId);
        $tasks_data = $this->db->get('tasks')->result();
        $tasks_data = $tasks_data[0];
        $crew_member = json_decode($tasks_data->crew_member);
        $crew_member=array_diff($crew_member,[$CrewMemberId]);
        $crew_member = array_values($crew_member);
        if(count($crew_member) == 0)
        {
            $crew_member = null;
        }else{
            $crew_member = json_encode($crew_member);    
        }
        $this->db->where('taskId',$taskId);
        $this->db->update('tasks',array('crew_member'=>$crew_member));
        $data = array('status'=>'success','data'=>"");
        echo json_encode($data);
    }
    
    function removePeople_post()
    {
        $invite_id = $this->input->post('inviteId');
        $this->db->where('id',$invite_id);
        $this->db->update('invite_people',array('is_removed'=>1));
        $data = array('status'=>'success','data'=>"","message"=>"People Removed Successfully");
        echo json_encode($data);
    } //addPeople
    
    function addPeople_post()
    {
        $invite_id = $this->input->post('inviteId');
        $this->db->where('id',$invite_id);
        $this->db->update('invite_people',array('is_removed'=>0));
        $data = array('status'=>'success','data'=>"","message"=>"People Added Successfully");
        echo json_encode($data);
    }
    
    function inviteNewPeople_post()
    {
        $invitenewpeoplename  = "";
        $invitenewpeopleemail  = "";
        $invitenewpeopleposition  = "";
        
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
        $is_for = $_POST['is_for'];
        $project_id = $_POST['project_id'];
        $company_id = $_POST['company_id'];
        $last_id = $_POST['userid'];
        $num_rows = $this->db->get_where('invite_people',array('project_id'=>$project_id,'user_id'=>$last_id,'role'=>$_POST['role']))->num_rows();
        if($num_rows>0){
            $message = 'You are already Added in This Project.';
            $response = array('status'=>FAIL,'message'=>$message);
        }else{    
            // send client invitaion
            $array = array(
                'sender_id' => $company_id,
                'sender_type' => $_POST['sender_type'],
                'receiver_id' => $last_id,
                'reciever_type' => $_POST['role'],
                'is_for' => 'project',
                'project_id'=>$project_id
            );
            $project_data = $this->db->get_where('project',array('id'=>$project_id))->result();
            $project_data = $project_data[0];
            $this->db->insert('invite',$array);
            $id = $this->db->insert_id();
            $link = base_url().'invitation/'.encoding($id);
            $data1['full_name']  = $invitenewpeoplename;
            $data1['url']        = $link;
            $data1['sender_type']  = 'company';
            $data1['is_role']  = 'project';
            $data1['title']  = $project_data->name;
            $message            = $this->load->view('emails/work_invite',$data1,TRUE);
            $subject = 'Bild It - Project Invitation link';
            $to = $invitenewpeopleemail;
            $this->common_model->sendMemberMail($to,$link,$message,$subject);
            $response = array('status'=>SUCCESS,'message'=>'People Invited Successfully');
            // end
        }
        echo json_encode($response);
    }
    
    // Create People and invite 
    public function inviteNewPeopleCreateandSend_post()
    {
        $is_for = $_POST['is_for'];
        $project_id = $_POST['project_id'];
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
        
        // project invitaion
        $array = array(
            'sender_id' => $company_id,
            'sender_type' => $_POST['sender_type'],
            'receiver_id' => $last_id,
            'reciever_type' => $reciever_type,
            'is_for' => 'project',
            'project_id'=>$project_id
        );
        $project_data = $this->db->get_where('project',array('id'=>$project_id))->result();
        $project_data = $project_data[0];
        $this->db->insert('invite',$array);
        $id = $this->db->insert_id();
        $link = base_url().'invitation/'.encoding($id);
        $data1['full_name']  = $name;
        $data1['url']        = $link;
        $data1['sender_type']  = 'company';
        $data1['is_role']  = 'project';
        $data1['title']  = $project_data->name;
        $message            = $this->load->view('emails/work_invite',$data1,TRUE);
        $subject = 'Bild It - Project Invitation link';
        $to = $email;
        $this->common_model->sendMemberMail($to,$link,$message,$subject);
        $response = array('status'=>SUCCESS,'message'=>'People Invited Successfully');
        // end
        $response = array('status'=>SUCCESS,'message'=>'People Invited Successfully');
        echo json_encode($response);
    }
    
    //project search
    public function searchapi_post()
    {
        $this->db->where('company_id',$_POST['company_id']);
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $name = $_POST['name'];
            $this->db->like('name', $_POST['name']);
            $data = $this->db->get('project')->result();
        }else{
            $data = $this->db->get('project')->result();
        }
        echo json_encode($data);
    }
    
    // 0 => pending 1=> completed -1=> All
    public function filterapi_post()
    {
        $this->db->where('company_id',$_POST['company_id']);
        if(isset($_POST['status']) && $_POST['status'] != -1 && $_POST['status'] != "")
        {
            $name = $_POST['name'];
            $this->db->like('status', $_POST['status']);
            $data = $this->db->get('project')->result();
        }else{
            $data = $this->db->get('project')->result();
        }
        foreach($data as $value)
        {
            $value->encrypt_id = encoding($value->id);
        }
        echo json_encode($data);
    }
    
    // Task List APi
    public function taskalllist_post()
    {
        $projectid = $_POST['project_id'];
        $this->db->where('project_id',$projectid);
        $this->db->where('created_by',0);
        $this->db->order_by('taskId','desc');
        if(isset($_POST['status']) && $_POST['status'] != -1 && $_POST['status'] != "")
        {
            $this->db->where('task_status', $_POST['status']);
        }
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $this->db->like('name', $_POST['name']);
        }
        $task_list = $this->db->get('tasks')->result();
        foreach($task_list as $value)
        {
            $value->encrypt_id = encoding($value->taskId);
            $taskId = $value->taskId;
            $task_meta = $this->common_model->getAll('task_meta',array('taskId'=>$taskId),'sorting_order','desc');  
            $task_meta =  array();
            $value->meta_data = $task_meta;
        }
        echo json_encode(array('success'=>true,'task_list'=>$task_list));
    }
    
    // new api
    public function taskalllistnew_post()
    {
        $projectid = $_POST['project_id'];
        $this->db->order_by('taskId');
        $this->db->where('project_id',$projectid);
        $this->db->where('created_by',0);
        $task_list = $this->db->get('tasks')->result();
        $initial = array();
        $initial[0] = array(
            "taskId" => "-1",
            "name" => "All",
            "description" => "fdfsafsafda",
            "status" => "0",
            "crd" => "2020-08-05 23:18:16",
            "upd" => "2020-08-23 10:21:35",
            "contractor" => "0",
            "project_id" => "96",
            "crew_member" => null,
            "company_id" => "114",
            "created_by" => "0",
            "is_exported" =>"0",
            "is_deleted" => "1",
            "task_status" => "0",
            "encrypt_id" => "",
            "meta_data" => array()
        );
        $i = 1;
        foreach($task_list as $value)
        {
            $value->encrypt_id = "";
            $value->meta_data = array();
            $initial[$i] = $value;
            $i ++;
        }
        echo json_encode(array('success'=>true,'task_list'=>$initial));
    }
    
    // prepopulated task
    public function prepopulatedtasklist_post()
    {
        $name = $_POST['name'];
        $pretask_list = $this->common_model->getAll('tasks', array('created_by'=>1,'status'=>1), '', '', 'all', '', '', '', $name);
        foreach($pretask_list as $pre){
            $pre->encrypt_id = encoding($pre->taskId);
            $task_meta              = $this->common_model->getAll('task_meta',array('taskId'=>$pre->taskId),'sorting_order','desc');
            
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
            
            $pre->task_meta = $task_meta;
        }
        echo json_encode(array('success'=>true,'task_list'=>$pretask_list));
    }
    
    // people list api
    public function peopleListApi_post()
    {
        $project_id = $_POST['project_id'];
        $this->db->where('project_id',$project_id);
        $value_search = $this->input->post('value');
        if($value_search != "")
        {
            $this->db->group_start();
            $this->db-> like('role',$value_search);   
            $this->db->or_like('person_name',$value_search);
            $this->db->group_end();
        }
        $invite_peoples = $this->db->get('invite_people')->result();
        $involvedPeople = array();
        $noninvolvedPeople = array();
        foreach($invite_peoples as $invite_people)
        {
            $people_name = "";
            $people_position = "";
            $assigned_to = "";
            $people_email = "";
            if($invite_people->role == 'leadcontractor'){
                $this->db->where('id',$invite_people->user_id);
                $this->db->where('is_role',1);
                $contractor_data = $this->db->get('contractor')->result();
                if(isset($contractor_data[0]))
                {
                    $contractor_data = $contractor_data[0];
                    $object = (object) [
                        'invite_id' => $invite_people->id,
                        'id' => $contractor_data->id,
                        'name' => $contractor_data->owner_first_name,
                        'email' => $contractor_data->email,
                        'role' => 'leadcontractor'
                    ];
                    if($contractor_data->licence != "")
                        $contractor_data->licence = base_url('uploads/contractor/'). $contractor_data->licence;
                    if($contractor_data->insurence_certificate != "")
                        $contractor_data->insurence_certificate = base_url('uploads/contractor/').$contractor_data->insurence_certificate;  
                    if($invite_people->is_removed == 0)
                    {
                        array_push($involvedPeople,$object);
                    }else{
                        array_push($noninvolvedPeople,$object);
                    }
                }
            }else if($invite_people->role == 'subcontractor'){
                $this->db->where('id',$invite_people->user_id);
                $this->db->where('is_role',2);
                $contractor_data = $this->db->get('contractor')->result();
                if(isset($contractor_data[0]))
                {
                    $contractor_data = $contractor_data[0];
                    $object = (object) [
                        'invite_id' => $invite_people->id,
                        'id' => $contractor_data->id,
                        'name' => $contractor_data->owner_first_name,
                        'email' => $contractor_data->email,
                        'role' => 'subcontractor'
                    ];
                    $contractor_data->licence = base_url('uploads/contractor/'). $contractor_data->licence;   
                    $contractor_data->insurence_certificate = base_url('uploads/contractor/').$contractor_data->insurence_certificate;   
                    if($invite_people->is_removed == 0)
                    {
                        array_push($involvedPeople,$object);
                    }else{
                        array_push($noninvolvedPeople,$object);
                    }
                }
            }else if($invite_people->role == 'crew'){
                $this->db->where('id',$invite_people->user_id);
                $crew_data = $this->db->get('crew_member')->result();
                if(isset($crew_data[0]))
                {
                    $crew_data = $crew_data[0];
                    $object = (object) [
                        'invite_id' => $invite_people->id,
                        'id' => $crew_data->id,
                        'name' => $crew_data->name,
                        'email' => $crew_data->email,
                        'role'  => 'crew'
                    ];
                    if($crew_data->licence != "")
                    $crew_data->licence = base_url('uploads/crew/'). $crew_data->licence;   
                    if($crew_data->insurence_certificate != "")
                    $crew_data->insurence_certificate = base_url('uploads/crew/').$crew_data->insurence_certificate;   
                    if($invite_people->is_removed == 0)
                    {
                        array_push($involvedPeople,$object);
                    }else{
                        array_push($noninvolvedPeople,$object);
                    }   
                }
            }
        }
        echo json_encode(array('success'=>true,'involvedPeople_list'=>$involvedPeople,'noninvolvedPeople_list'=>$noninvolvedPeople));
	}
	
	// get membersof the peoples
	public function getMembersOfThePeoples_post()
    {
        $project_id = $_POST['project_id'];
        $this->db->where('project_id',$project_id);
        $value_search = $this->input->post('value');
        if($value_search != "")
        {
            $this->db->group_start();
            $this->db-> like('role',$value_search);   
            $this->db->or_like('person_name',$value_search);
            $this->db->group_end();
        }
        $invite_peoples = $this->db->get('invite_people')->result();
        $involvedPeople = array();
		$noninvolvedPeople = array();
		$contractorList = array();
		$crewList = array();
        foreach($invite_peoples as $invite_people)
        {
            $people_name = "";
            $people_position = "";
            $assigned_to = "";
            $people_email = "";
            if($invite_people->role == 'leadcontractor'){
                $this->db->where('id',$invite_people->user_id);
                $this->db->where('is_role',1);
                $contractor_data = $this->db->get('contractor')->result();
                if(isset($contractor_data[0]))
                {
                    $contractor_data = $contractor_data[0];
                    $object = (object) [
                        'invite_id' => $invite_people->id,
                        'id' => $contractor_data->id,
                        'name' => $contractor_data->owner_first_name,
                        'email' => $contractor_data->email,
                        'role' => 'leadcontractor'
                    ];
                    if($contractor_data->licence != "")
                        $contractor_data->licence = base_url('uploads/contractor/'). $contractor_data->licence;
                    if($contractor_data->insurence_certificate != "")
                        $contractor_data->insurence_certificate = base_url('uploads/contractor/').$contractor_data->insurence_certificate;  
                    if($invite_people->is_removed == 0)
                    {
                        array_push($involvedPeople,$object);
                    }else{
                        array_push($noninvolvedPeople,$object);
					}
					array_push($contractorList,$object);
                }
            }else if($invite_people->role == 'subcontractor'){
                $this->db->where('id',$invite_people->user_id);
                $this->db->where('is_role',2);
                $contractor_data = $this->db->get('contractor')->result();
                if(isset($contractor_data[0]))
                {
                    $contractor_data = $contractor_data[0];
                    $object = (object) [
                        'invite_id' => $invite_people->id,
                        'id' => $contractor_data->id,
                        'name' => $contractor_data->owner_first_name,
                        'email' => $contractor_data->email,
                        'role' => 'subcontractor'
                    ];
                    $contractor_data->licence = base_url('uploads/contractor/'). $contractor_data->licence;   
                    $contractor_data->insurence_certificate = base_url('uploads/contractor/').$contractor_data->insurence_certificate;   
                    if($invite_people->is_removed == 0)
                    {
                        array_push($involvedPeople,$object);
                    }else{
                        array_push($noninvolvedPeople,$object);
					}
					array_push($contractorList,$object);
                }
            }else if($invite_people->role == 'crew'){
                $this->db->where('id',$invite_people->user_id);
                $crew_data = $this->db->get('crew_member')->result();
                if(isset($crew_data[0]))
                {
                    $crew_data = $crew_data[0];
                    $object = (object) [
                        'invite_id' => $invite_people->id,
                        'id' => $crew_data->id,
                        'name' => $crew_data->name,
                        'email' => $crew_data->email,
                        'role'  => 'crew'
                    ];
                    if($crew_data->licence != "")
                    $crew_data->licence = base_url('uploads/crew/'). $crew_data->licence;   
                    if($crew_data->insurence_certificate != "")
                    $crew_data->insurence_certificate = base_url('uploads/crew/').$crew_data->insurence_certificate;   
                    if($invite_people->is_removed == 0)
                    {
                        array_push($involvedPeople,$object);
                    }else{
                        array_push($noninvolvedPeople,$object);
					}   
					array_push($crewList,$object);
                }
            }
		}
		$ClassObject = new stdClass();
		$ClassObject->contractorList = $contractorList;
		$ClassObject->crewList = $crewList; 
        echo json_encode(array('success'=>true,'data'=>$ClassObject));
	}
    
    // Document APi
    public function DocumentApi_post()
    {
        $project_id = $_POST['project_id'];
        $this->db->where('project_id',$project_id);
        $this->db->where('file_type','docs');
        $document_list = $this->db->get('chat')->result();
        foreach($document_list as $value)
        {
            $value->file = base_url('uploads/project/documents/').$value->file; 
        }
        echo json_encode(array('success'=>true,'document_list'=>$document_list));
    }
    
    // Message List
    public function AllChatMessage_post()
    {
        $project_id = $_POST['project_id'];
        $this->db->where('project_id',$project_id);
        $allmessage_list = $this->db->get('chat')->result();
        foreach($allmessage_list as $value)
        {
            if($value->file_type == "docs")
            {
                $value->file = base_url('uploads/project/documents/').$value->file;
            }
        }
        echo json_encode(array('success'=>true,'allmessage_list'=>$allmessage_list));
    }
    
}//End Class
