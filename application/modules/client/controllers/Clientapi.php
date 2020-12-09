<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Clientapi extends REST_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
        $this->load->model('Client_model');
        $check = $this->Client_model->checkAuthToken();
        if(!$check)
        {
            $response       = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(101),'data'=>array());
            $this->response($response);
        }
    }
    
    // Client detail api
    public function clientdetail_post()
    {
        $client_id = $this->post('client_id');
        $data = $this->db->get_where('client',array('id'=>$client_id))->result();
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'message'=>'Clieent Not Exist','data'=>$data);
        }else{
            $data = $data[0];   
            $data->encrypt_id = encoding($data->id);
            if($data->document != null)
            {
                $data->document = base_url('uploads/client/').$data->document;
            }
            if($data->profile_photo != null)
            {
                $data->profile_photo = base_url('uploads/client/').$data->profile_photo;
            }
            $response       = array('status'=>SUCCESS,'message'=>'Client Detail Get Succesfully','data'=>$data);
        }
        $this->response($response);
    }
    
    function edit_post(){
        $this->load->model('Client_model');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[admin.email]',
            array('is_unique' => 'Email already exist')
        );
        if(isset($_POST['phone_number']) && $_POST['phone_number'] != ""){
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[10]|max_length[20]');
        }
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
        
            $email                          =  $this->post('email');
            $name                       =  $this->post('name');
            $data_val['name']           =   $name;
            $data_val['email']              =   $email;
            if(isset($_POST['phone_number'])){
                $data_val['phone_number']      =   $this->post('phone_number');
            }
            if(isset($_POST['address'])){
                $data_val['address']      =   $this->post('address');
            }
            
            $config['upload_path']= "./uploads/client/";
            $config['allowed_types']='*';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);

            $document = "";
            
            if(isset($_FILES['document']))
            {
                $document = $_FILES['document']['name'];    
            }
            if($document != "")
            {
                if($this->upload->do_upload('document'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $document= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
                $data_val['document']                      = $document;
            }
            $profile_photo = "";
            if(isset($_FILES['profile_photo']))
            {
                $profile_photo = $_FILES['profile_photo']['name'];    
            }
            if($profile_photo != "")
            {
                if($this->upload->do_upload('profile_photo'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $document= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
                $data_val['profile_photo']                      = $profile_photo;
            }
            $this->db->where('id',$this->input->post('id'));
            $result = $this->db->update('client',$data_val);
            $data = $this->db->get_where('client',array('id'=>$this->input->post('id')))->result();
            $data = $data[0];
            $msg = 'Client Details Updated Successfully';
            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg,'data'=>$data);
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'data'=>array());
            }        
            $this->response($response);
        }
    }
    
    public function getallproject_post()
    {
        $client_id = $this->post('client_id');
        //$data = $this->db->get_where('project',array('client'=>$client_id))->result();
        $role = "client";
        $data = $this->db->select('project.*, invite_people.is_removed')
         ->from('project')
         ->join('invite_people', 'project.id = invite_people.project_id')
         ->where('invite_people.user_id',$client_id)
         ->where('invite_people.role',$role);
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

        $response = array('success'=>true,'message'=>'Task Details retrived successfully.','data'=>$task_detail);
        echo json_encode($response);
    }
    
    
  
}//End Class