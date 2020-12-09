<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Crewapi extends REST_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
    }
    
    // Client detail api
    public function crewdetail_post()
    {
        $crew_id = decoding($this->post('crew_id'));
        $data = $this->db->get_where('crew_member',array('id'=>$crew_id))->result();
        foreach($data as $value)
        {
            $value->encrypt_id = encoding($value->id);
            if($value->licence != "")
            {
                $value->licence = base_url('uploads/crew/').$value->licence;   
            }
            if($value->insurence_certificate)
            {
                $value->insurence_certificate = base_url('uploads/crew/').$value->insurence_certificate;   
            }
        }
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'data'=>$data,'message'=>'Crew Member Not Exist');
        }else{
            $data = $data[0];   
            $data->encrypt_id = encoding($data->id);
            $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Crew Member Detail Get Succesfully');
        }
        $this->response($response);
    }
    
    function edit_post(){
        $this->load->model('Crew_model');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[admin.email]',
            array('is_unique' => 'Email already exist')
        );
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[10]|max_length[20]');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
        
            $email                          =  $this->post('email');
            $name                       =  $this->post('name');
            $data_val['name']           =   $name;
            $data_val['email']              =   $email;
            $data_val['phone_number']      =   $this->post('phone_number');
            $data_val['address']      =   $this->post('address');
            // profile pic upload
            $config['upload_path']= "./uploads/crew/";
            $config['allowed_types']='*';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);


            $licence = $_FILES['licence']['name'];
            $insurence_certificate = $_FILES['insurence_certificate']['name'];
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
                $data_val['licence']                      = $licence;
            }
            if($insurence_certificate != "")
            {
                if($this->upload->do_upload('insurence_certificate'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $insurence_certificate= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
                $data_val['insurence_certificate']        = $insurence_certificate;
            }

            $this->db->where('id',decoding($this->input->post('id')));
            $result = $this->db->update('crew_member',$data_val);
            $msg = 'Crew Member Details Updated Successfully';

            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'admin/crew-detail/'.$this->input->post('id'));
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
            $this->response($response);
        }
    }
    
    public function getallproject_post()
    {
        $crew_id = decoding($this->post('crew_id'));

        $data = $this->db->select('project.*, invite_people.is_removed')
         ->from('project')
         ->join('invite_people', 'project.id = invite_people.project_id')
         ->where('invite_people.user_id',$crew_id)
         ->where('invite_people.role','crew');
        $this->db->distinct();
        $data = $this->db->get()->result();
        
        $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Project List Get Succesfully');
        $this->response($response);
    }
    
    public function projectdetail_post()
    {
        $id = $this->post('id');
        $data = $this->db->get_where('project',array('id'=>$id))->result();
        $data = $data[0];
        $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Project Detail Get Succesfully');
        $this->response($response);
    }
    
    public function projecttasklist_post()
    {
        $id = $this->post('id');
        if(isset($_POST['filter']))
        {
            if($_POST['task_status'] != -1 && $_POST['task_name'] == "")
            {
                $task_status =  $_POST['task_status'];
                $task_list = $this->common_model->getAll('tasks',array('project_id'=>$id,'task_status'=>$task_status,'created_by'=>0));
            }else if($_POST['task_status'] == -1 && $_POST['task_name'] != "")
            {
                $task_name =  $_POST['task_name'];
                $this->db->select('*');
                $this->db->from('tasks');
                $this->db->where('project_id', $id);
                $this->db->where('created_by', 0);
                $this->db->like('name', $task_name);
                $task_list = $this->db->get()->result();
            }else if($_POST['task_status'] == -1 && $_POST['task_name'] == "")
            {
                $task_list = $this->common_model->getAll('tasks',array('project_id'=>$id,'created_by'=>0));
            }else if($_POST['task_status'] != -1 && $_POST['task_name'] != "")
            {
                $task_status =  $_POST['task_status'];
                $task_name =  $_POST['task_name'];
                $this->db->select('*');
                $this->db->from('tasks');
                $this->db->where('project_id', $id);
                $this->db->where('created_by', 0);
                $this->db->where('task_status', $task_status);
                $this->db->like('name', $task_name);
                $task_list = $this->db->get()->result();
            }
        }else{
            $task_list = $this->common_model->getAll('tasks',array('project_id'=>$id,'created_by'=>0));    
        }
        
        foreach($task_list as $value)
        {
            $taskId = $value->taskId;
            $task_meta              = $this->common_model->getAll('task_meta',array('taskId'=>$taskId),'sorting_order','asc');  
            $value->meta_data = $task_meta;
            $value->task_id = encoding($taskId);
        }
        
        $response       = array('status'=>SUCCESS,'data'=>$task_list,'message'=>'Task List Get Succesfully');
        $this->response($response);
    }
    
    public function projecttasklistdetail_post()
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
    
    public function projecttaskstatuschange_post()
    {
        $id = decoding($this->post('task_id'));      
        $data = $this->db->get_where('tasks',array('taskId'=>$id))->result();
        
        if(isset($data[0]))
        {
            $task_status = 0;
            if($data[0]->task_status == 0)
            {
                $task_status = 1;
            }
            $data_val['task_status'] = $task_status;
            $this->db->where('taskId',$id);
            $result = $this->db->update('tasks',$data_val);
            $response = array('success'=>true,'message'=>'Task Status Changes Successfully');
        }else{
            $response = array('success'=>false,'message'=>'Task Not Exist');
        }
        echo json_encode($response);
    }
    
    public function updatenotification_post()
    {
        $crew_id = decoding($this->post('crew_id'));
        $data = $this->db->get_where('crew_member',array('id'=>$crew_id))->result();
        if(isset($data[0]))
        {
            $is_notify = 0;
            if($data[0]->is_notify == 0)
            {
                $is_notify = 1;
            }
            $data_val['is_notify'] = $is_notify;
            $this->db->where('id',$crew_id);
            $result = $this->db->update('crew_member',$data_val);
            $response = array('success'=>true,'message'=>'Crew Member Notification Setting Updated Successfully');
        }else{
            $response       = array('status'=>false,'message'=>'Crew Member Not Exist');
        }
        $this->response($response);
    }
}//End Class