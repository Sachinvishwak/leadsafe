<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contractorapi extends REST_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
    }
    
    // Contractor detail api
    public function contractordetail_post()
    {
        $contractor_id = decoding($this->post('contractor_id'));
        $data = $this->db->get_where('contractor',array('id'=>$contractor_id))->result();
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'data'=>$data,'message'=>'Contractor Not Exist');
        }else{
            $data = $data[0];   
            $data->encrypt_id = encoding($data->id);
            if($data->licence != "")
            {
                $data->licence = base_url('uploads/contractor/').$data->licence;
            }
            if($data->insurence_certificate != "")
            {
                $data->insurence_certificate = base_url('uploads/contractor/').$data->insurence_certificate;
            }
            $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Project Detail Get Succesfully');
        }
        
        $this->response($response);
    }
    
    function edit_post(){
        $this->load->model('Contractor_model');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[admin.email]',
            array('is_unique' => 'Email already exist')
        );
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[10]|max_length[20]');
        $this->form_validation->set_rules('company_name', 'Name', 'trim|required|min_length[2]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
        
            $email                          =  $this->post('email');
            $company_name                       =  $this->post('company_name');
            $userData['company_name']           =   $company_name;
            $userData['email']              =   $email;
            $userData['phone_number']      =   $this->post('phone_number');
            $userData['address']      =   $this->post('address');
            // $userData['company_id']      =   decoding($this->post('company_id'));
            $userData['owner_first_name']      =   $this->post('owner_first_name');
            $userData['owner_last_name']      =   $this->post('owner_last_name');
            $userData['is_role']      =   $this->post('is_role');
            $userData['state']      =   $this->post('state');
            $userData['city']      =   $this->post('city');
            // profile pic upload
            $config['upload_path']= "./uploads/contractor/";
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
                $userData['licence']                      = $licence;
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
                $userData['insurence_certificate']        = $insurence_certificate;
            }

            $this->db->where('id',decoding($this->input->post('id')));
            $result = $this->db->update('contractor',$userData);
            $msg = 'Contractor Details Updated Successfully';

            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'admin/contractor-detail/'.$this->input->post('id'));
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
            $this->response($response);
        }
    }
    
    
    public function getallproject_post()
    {
        $contractor_id = decoding($this->post('contractor_id'));
        $role = $this->post('role');

        $data = $this->db->select('project.*, invite_people.is_removed')
         ->from('project')
         ->join('invite_people', 'project.id = invite_people.project_id')
         ->where('invite_people.user_id',$contractor_id)
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
        $contractor_id = decoding($this->post('contractor_id'));
        $data = $this->db->get_where('contractor',array('id'=>$contractor_id))->result();
        if(isset($data[0]))
        {
            $is_notify = 0;
            if($data[0]->is_notify == 0)
            {
                $is_notify = 1;
            }
            $data_val['is_notify'] = $is_notify;
            $this->db->where('id',$contractor_id);
            $result = $this->db->update('crew_member',$data_val);
            $response = array('success'=>true,'message'=>'Crew Member Notification Setting Updated Successfully');
        }else{
            $response       = array('status'=>false,'message'=>'Contractor Member Not Exist');
        }
        $this->response($response);
    }
    

    // reset password
    function resetpassword_post()
    {
        $userData['password']           =   password_hash($this->post('npassword'), PASSWORD_DEFAULT);
        $userData['status']           =   1;
        $cpassword = $_POST['cpassword'];
        $id = $_POST['id'];
        
        $check = $this->db->get_where('contractor',array('id'=>$id))->result();
        if(!isset($check[0]))
        {
            $response = array('status' => FAIL, 'message' => 'Contractor Not Found');
        }else{
            $this->db->where('id',$id);
            $result = $this->db->update('contractor',$userData);
            
            if($result)
            {
                $response = array('status' => SUCCESS, 'message' => 'Contractor Password Set Successfully');
            }else{
                $response = array('status' => FAIL, 'message' => 'Something Went Wrong');
            }    
        }
        
        $this->response($response);
    }



  
}//End Class