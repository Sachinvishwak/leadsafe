<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Clientapi extends REST_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
    }
    
    public function clientlist_post()
    {
        $company_id = $this->post('company_id');
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $data = $this->db->select('company_member_relations.type, client.*')
                 ->from('company_member_relations')
                 ->join('client', 'company_member_relations.member_id = client.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->like('client.name',$_POST['name'])
                 ->where('company_member_relations.type','client')->order_by("id", "desc")->get()->result();   
        }else{
            $data = $this->db->select('company_member_relations.type, client.*')
                 ->from('company_member_relations')
                 ->join('client', 'company_member_relations.member_id = client.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->where('company_member_relations.type','client')
                 ->order_by("id", "desc")->get()->result();     
        }
        foreach($data as $value)
        {
            $value->encrypt_id = encoding($value->id);
            if($value->document != null)
            {
                $value->document = base_url('uploads/client/').$value->document;
            }
        }
        $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Client List Get Succesfully');
        $this->response($response);
    }
    
    // Client detail api
    public function clientdetail_post()
    {
        $client_id = $this->post('client_id');
        $data = $this->db->get_where('client',array('id'=>$client_id))->result();
        
        $projectIds = $this->db->get_where('project',array('client'=>$client_id))->result();
        
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'data'=>$data,'message'=>'Project Not Exist');
        }else{
            $data = $data[0];   
            $data->encrypt_id = encoding($data->id);
            $data->projectIds = $projectIds;
            if($data->document != null)
            {
                $data->document = base_url('uploads/client/').$data->document;
            }
            $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Project Detail Get Succesfully');
        }
    
        $this->response($response);
    }

    public function list_post(){
        $this->load->helper('text');
        $this->load->model('Client_model');
        $this->Client_model->set_data();
        $list       = $this->Client_model->get_list();
        $data       = array();
        $no         = $_POST['start'];
        foreach ($list as $serData) { 
            $action = '';
            $no++;
            $row    = array();
            $row[]  = $no;
            $link_url      = base_url().'admin/client-detail/'.encoding($serData->id);
            $row[]  = '<a href="'.$link_url.'">'.display_placeholder_text($serData->name).'</a>'; 
            $row[]      = display_placeholder_text((mb_substr($serData->email, 0,100, 'UTF-8') .((strlen($serData->email) >100) ? '...' : '')));
            
            $row[]  = $serData->address; 
            $row[]  = $serData->phone_number;
            
            // $projectList = $this->db->get_where('project',array('client'=>$serData->id))->result();
            
            $sql = $this->db->select('project.*, invite_people.is_removed')
                 ->from('project')
                 ->join('invite_people', 'project.id = invite_people.project_id')
                 ->where('invite_people.user_id',$serData->id)
                 ->where('invite_people.role','client')
                 ->where('project.company_id',$_SESSION['company_sess']['id']);
            $this->db->distinct();
            $projectList = $this->db->get()->result();
            
            $projectNames = "";
            foreach($projectList as $project)
            {
                $projectNames = $projectNames.$project->name.",";
            }
            
            $row[]  = $projectNames;
            
            $link    = 'javascript:void(0)';
            $action .= "";
             if($serData->status){

                //$action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->id).'" data-url="adminapi/tasks/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-check" aria-hidden="true"></i></a>';
            }else{
                //$action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->id).'" data-url="adminapi/tasks/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-times" aria-hidden="true"></i></a>';
            }
            $link_url      = base_url().'admin/client-detail/'.encoding($serData->id);
            $action .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Detail"><i class="fa fa-eye"  aria-hidden="true"></i></a>';
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="'.encoding($serData->id).'" data-url="company/Clientapi/recordDelete" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $link_url      = base_url().'company/client/edit/'.encoding($serData->id);
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Edit"><i class="fa fa-edit"  aria-hidden="true"></i></a>';
            $row[]  = $action;
            $data[] = $row;

        }
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->Client_model->count_all(),
            "recordsFiltered"   => $this->Client_model->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        $this->response($output);
    }
    
    
    public function recordDelete_post()
    {
        $id            = decoding($this->post('id'));
        // $where              = array('id'=>$id);
        $where = array('type'=>'client','member_id'=>$id);
        $dataExist      = $this->common_model->is_data_exists('company_member_relations',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('company_member_relations',$where);
            $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }
    
    // multiple Client Invite Web
    function inviteClientMultiple_post()
    {
        $this->load->model('Client_model');
        $client_name = $_POST['client_name'];
        $client_email = $_POST['client_email'];
        $projectId = $_POST['projectId'];
        

     if(!isset($_POST['projectId'])){
         
            $project_id = $_POST['projectId'];
            if($projectId == "" || $projectId == null || $projectId == "null")
            {
                $response = array('status'=>FAIL,'message'=>'Please Select Project.','userDetail'=>array());
                $this->response($response);
                die;
            }
                    
     }



        $dataArray = [];
        foreach($client_name as $key=>$value)
        {
            $cName = $value;
            $cEmail = $client_email[$key];
            $userData['name']           =   $cName;
            $userData['email']              =   $cEmail;
            $result = $this->Client_model->registration($userData);
            $company_id = $_SESSION['company_sess']['id'];
            $email = $cEmail;
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                        $response = array('status'=>SUCCESS,'message'=>'Client Invitation Send Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/client');
                        $last_id = $result['returnData'][0]->id;
                        // add project
                        $projectId                  =  $projectId[$key];
                        $array = array(
                            'sender_id' => $company_id,
                            'sender_type' => 'company',
                            'receiver_id' => $last_id,
                            'reciever_type' => 'client',
                            'is_for' => 'project',
                            'project_id'=>$projectId
                        );
                        $project_data = $this->db->get_where('project',array('id'=>$projectId))->result();
                        $project_data = $project_data[0];
                        $this->db->insert('invite',$array);
                        $id = $this->db->insert_id();
                        $link = base_url().'invitation/'.encoding($id);
                        $data1['full_name']  = $cName;
                        $data1['url']        = $link;
                        $data1['sender_type']  = 'client';
                        $data1['is_role']  = 'project';
                        $data1['title']  = $project_data->name;
                        $message            = $this->load->view('emails/work_invite',$data1,TRUE);
                        $subject = 'Bild It - Project Invitation link';
                        $to = $email;
                        $this->common_model->sendMemberMail($to,$link,$message,$subject);
                        $response = array('status'=>SUCCESS,'message'=>$message);
                        
                        // send client invitaion
                        // $array = array(
                        //     'sender_id' => $company_id,
                        //     'sender_type' => 'company',
                        //     'receiver_id' => $last_id,
                        //     'reciever_type' => 'client',
                        //     'is_for' => 'account'
                        // );
                        // $this->db->insert('invite',$array);
                        // $id = $this->db->insert_id();
                        // $link = base_url().'invitation/'.encoding($id);
                        // $data1['full_name']  = $result['returnData'][0]->name;
                        // $data1['url']        = $link;
                        // $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                        // $subject = 'Bild It - Account Invitation link';
                        // $to = $result['returnData'][0]->email;
                        // $this->common_model->sendMemberMail($to,$link,$message,$subject);
                        // end
                    break;
                    case "AE": // User already registered
                        $num_rows = $this->db->get_where('company_member_relations',array('company_id'=>$this->post('company_id'),'member_id'=>$result['existId'],'type'=>'client'))->num_rows();
                        if($num_rows>0){
                            $response = array('status'=>FAIL,'message'=>'Client Already Registered With Your Company','users'=>$result['existId']);
                        }else{
                            $response = array('status'=>SUCCESS,'message'=>'Client Invitation Send Successfully','users'=>array(),'url' => 'admin/client');
                            
                            $projectId                  =  $projectId[$key];
                            $last_id = $result['existId'];
                            $array = array(
                                'sender_id' => $company_id,
                                'sender_type' => 'company',
                                'receiver_id' => $last_id,
                                'reciever_type' => 'client',
                                'is_for' => 'project',
                                'project_id'=>$projectId
                            );
                            $project_data = $this->db->get_where('project',array('id'=>$projectId))->result();
                            $project_data = $project_data[0];
                            $this->db->insert('invite',$array);
                            $id = $this->db->insert_id();
                            $link = base_url().'invitation/'.encoding($id);
                            $data1['full_name']  = $cName;
                            $data1['url']        = $link;
                            $data1['sender_type']  = 'client';
                            $data1['is_role']  = 'project';
                            $data1['title']  = $project_data->name;
                            $message            = $this->load->view('emails/work_invite',$data1,TRUE);
                            $subject = 'Bild It - Project Invitation link';
                            $to = $email;
                            $this->common_model->sendMemberMail($to,$link,$message,$subject);
                            $response = array('status'=>SUCCESS,'message'=>$message);
                            
                            // send client invitaion
                            // $reciever_data = $this->db->get_where('contractor',array('id'=>$result['existId']))->result();
                            // $reciever_data = $reciever_data[0];
                            // $full_name= $reciever_data->name;
                            // $array = array(
                            //     'sender_id' => $company_id,
                            //     'sender_type' => 'company',
                            //     'receiver_id' => $result['existId'],
                            //     'reciever_type' => 'client',
                            //     'is_for' => 'account'
                            // );
                            // $this->db->insert('invite',$array);
                            // $id = $this->db->insert_id();
                            // $link = base_url().'invitation/'.encoding($id);
                            // $data1['full_name']  = $full_name;
                            // $data1['url']        = $link;
                            // $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                            // $subject = 'Bild It - Account Invitation link';
                            // $to = $email;
                            // $this->common_model->sendMemberMail($to,$link,$message,$subject);
                            // end
                        }
                    break;
                    default:
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'userDetail'=>array());
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'userDetail'=>array());
            }
        }
        $response       = array('status'=>SUCCESS,'message'=>'Clients Invited Successfully');
        $this->response($response);
    }
    
    // Invite Multiple CLient App
    function inviteClientMultipleApi_post()
    {
        $this->load->model('Client_model');
        $data            = $this->post();
        $data = isset($data[0]) ?  json_decode($data[0],true) : array();
        
        foreach($data as $value)
        {
            $projectId                  =  $value['projectId'];
            if($projectId == "" || $projectId == null || $projectId == "null")
            {
                $response = array('status'=>FAIL,'message'=>'Please Select Project.','userDetail'=>array());
                $this->response($response);
                die;
            }
        }
        
        foreach($data as $value)
        {
            $userData['name']           =   $value['name'];
            $userData['email']              =   $value['email'];
            $result = $this->Client_model->registration($userData);
            $company_id = $value['company_id'];
            $email = $value['email'];
    
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                        $response = array('status'=>SUCCESS,'message'=>'Client Invitation Send Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/client');
                        $last_id = $result['returnData'][0]->id;
                        // add project 
                        $projectId                  =  $value['projectId'];
                        $array = array(
                            'sender_id' => $company_id,
                            'sender_type' => 'company',
                            'receiver_id' => $last_id,
                            'reciever_type' => 'client',
                            'is_for' => 'project',
                            'project_id'=>$project_id
                        );
                        $project_data = $this->db->get_where('project',array('id'=>$projectId))->result();
                        $project_data = $project_data[0];
                        $this->db->insert('invite',$array);
                        $id = $this->db->insert_id();
                        $link = base_url().'invitation/'.encoding($id);
                        $data1['full_name']  = $value['name'];
                        $data1['url']        = $link;
                        $data1['sender_type']  = 'client';
                        $data1['is_role']  = 'project';
                        $data1['title']  = $project_data->name;
                        $message            = $this->load->view('emails/work_invite',$data1,TRUE);
                        $subject = 'Bild It - Project Invitation link';
                        $to = $email;
                        $this->common_model->sendMemberMail($to,$link,$message,$subject);
                        $response = array('status'=>SUCCESS,'message'=>$message);
                        
                        // send client invitaion
                        // $array = array(
                        //     'sender_id' => $company_id,
                        //     'sender_type' => 'company',
                        //     'receiver_id' => $last_id,
                        //     'reciever_type' => 'client',
                        //     'is_for' => 'account'
                        // );
                        // $this->db->insert('invite',$array);
                        // $id = $this->db->insert_id();
                        // $link = base_url().'invitation/'.encoding($id);
                        // $data1['full_name']  = $result['returnData'][0]->name;
                        // $data1['url']        = $link;
                        // $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                        // $subject = 'Bild It - Account Invitation link';
                        // $to = $result['returnData'][0]->email;
                        // $this->common_model->sendMemberMail($to,$link,$message,$subject);
                        // end
                    break;
                    case "AE": // User already registered
                        $num_rows = $this->db->get_where('company_member_relations',array('company_id'=>$this->post('company_id'),'member_id'=>$result['existId'],'type'=>'client'))->num_rows();
                        if($num_rows>0){
                            $response = array('status'=>FAIL,'message'=>'Client Already Registered With Your Company','users'=>$result['existId']);
                        }else{
                            $response = array('status'=>SUCCESS,'message'=>'Client Invitation Send Successfully','users'=>array(),'url' => 'admin/client');
                            // send client invitaion
                            // $reciever_data = $this->db->get_where('contractor',array('id'=>$result['existId']))->result();
                            // $reciever_data = $reciever_data[0];
                            // $full_name= $reciever_data->name;
                            // $array = array(
                            //     'sender_id' => $company_id,
                            //     'sender_type' => 'company',
                            //     'receiver_id' => $result['existId'],
                            //     'reciever_type' => 'client',
                            //     'is_for' => 'account'
                            // );
                            // $this->db->insert('invite',$array);
                            // $id = $this->db->insert_id();
                            // $link = base_url().'invitation/'.encoding($id);
                            // $data1['full_name']  = $full_name;
                            // $data1['url']        = $link;
                            // $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                            // $subject = 'Bild It - Account Invitation link';
                            // $to = $email;
                            // $this->common_model->sendMemberMail($to,$link,$message,$subject);
                            // end
                            
                            $projectId                  =  $value['projectId'];
                            $last_id = $result['existId'];
                            
                            $array = array(
                                'sender_id' => $company_id,
                                'sender_type' => 'company',
                                'receiver_id' => $last_id,
                                'reciever_type' => 'client',
                                'is_for' => 'project',
                                'project_id'=>$projectId
                            );
                            $project_data = $this->db->get_where('project',array('id'=>$projectId))->result();
                            $project_data = $project_data[0];
                            $this->db->insert('invite',$array);
                            $id = $this->db->insert_id();
                            $link = base_url().'invitation/'.encoding($id);
                            $data1['full_name']  = $value['name'];
                            $data1['url']        = $link;
                            $data1['sender_type']  = 'client';
                            $data1['is_role']  = 'project';
                            $data1['title']  = $project_data->name;
                            $message            = $this->load->view('emails/work_invite',$data1,TRUE);
                            $subject = 'Bild It - Project Invitation link';
                            $to = $email;
                            $this->common_model->sendMemberMail($to,$link,$message,$subject);
                            $response = array('status'=>SUCCESS,'message'=>$message);
                        }
                    break;
                    default:
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'userDetail'=>array());
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'userDetail'=>array());
            }
            
        }
        
        $response       = array('status'=>SUCCESS,'message'=>'Clients Invited Successfully');
        $this->response($response);
    }
     function valid_email($str)
    {
        if ( ! preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/', $str) )
        {
            // Set the error message:
            $this->form_validation->set_message('valid_username', 'The %s field should contain only letters, numbers or periods');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    function add_post(){
        
        $this->load->model('Client_model');
        if(isset($_POST['phone_number']) && $_POST['phone_number'] != ""){
            // $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[10]|max_length[20]');
            $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        }
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
         $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_valid_email');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{

            if(!empty($_POST['sendmailone'])){
                
            }
            else
            {

                if(!isset($_POST['unused_param']))
                {  
                     if(!isset($_POST['projectId'])){
                         
                        $project_id = $_POST['projectId'];
                        if($projectId == "" || $projectId == null || $projectId == "null")
                        {
                            $response = array('status'=>FAIL,'message'=>'Please Select Project.','userDetail'=>array());
                            $this->response($response);
                            die;
                        }
                                    
                     }
                }
              
            }   
            $email                          =  $this->post('email');
            $name                       =  $this->post('name');
            
            $userData['name']           =   $name;
            $userData['email']              =   $email;
            if(isset($_POST['phone_number'])){
                $userData['phone_number']      =   $this->post('phone_number');
            }
            if(isset($_POST['address'])){
                $userData['address']      =   $this->post('address');
            }
            $userData['company_id']      =  $this->post('company_id');
            // profile pic upload
            $config['upload_path']= "./uploads/client/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);

            $company_id = $this->post('company_id') ;

            $document = $_FILES['document']['name'];
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
                $userData['document']                      = $document;
            }

            $result = $this->Client_model->registration($userData);
            
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                        $last_id = $result['returnData'][0]->id;
                        $dataClients = $this->db->select('company_member_relations.type,client.*')
                             ->from('company_member_relations')
                             ->join('client', 'company_member_relations.member_id = client.id')
                             ->where('company_member_relations.company_id',$company_id)
                             ->where('company_member_relations.type','client')
                             ->order_by("id", "desc")->get()->result();
                        foreach($dataClients as $value)
                        {
                            $value->encrypt_id = encoding($value->id);
                            if($value->document != null)
                            {
                                $value->document = base_url('uploads/client/').$value->document;
                            }
                        }
                        $singleClientData = $this->db->get_where('client',array('id'=>$last_id))->result();
                        $singleClientData = $singleClientData[0];
                        $sinelClientDataObj = array(
                            'id' => $singleClientData->id,
                            'name' => $singleClientData->name
                        );
                        $index = 1;
                        $clientDataArray = array();
                        $clientDataArray[0] = $sinelClientDataObj;
                        foreach($dataClients as $dataClient)
                        {
                            $clientDataArray[$index] = $dataClient;
                            $index++;
                        }
                        $response = array('status'=>SUCCESS,'message'=>'Client Invitation Send Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/client');
                        
                        // send client invitaion
                        if(!isset($_POST['projectId']))
                        {
                            $array = array(
                                'sender_id' => $this->post('company_id'),
                                'sender_type' => 'company',
                                'receiver_id' => $last_id,
                                'reciever_type' => 'client',
                                'is_for' => 'account'
                            );
                            $this->db->insert('invite',$array);
                            $id = $this->db->insert_id();
                            $link = base_url().'invitation/'.encoding($id);
                            $data1['full_name']  = $result['returnData'][0]->name;
                            $data1['url']        = $link;
                            $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                            $subject = 'Bild It - Account Invitation link';
                            $to = $result['returnData'][0]->email;
                            $this->common_model->sendMemberMail($to,$link,$message,$subject);   
                        }
                        // end
                        // add project
                       
                        if(isset($_POST['projectId']))
                        {
                            $project_id = $_POST['projectId'];
                            
                             if($projectId == "" || $projectId == null || $projectId == "null")
                            {
                                $response = array('status'=>FAIL,'message'=>'Please Select Project.','userDetail'=>array());
                                $this->response($response);
                                die;
                            }
                            $array = array(
                                'sender_id' => $this->post('company_id'),
                                'sender_type' => 'company',
                                'receiver_id' => $last_id,
                                'reciever_type' => 'client',
                                'is_for' => 'project',
                                'project_id'=>$project_id
                            );
                            $project_data = $this->db->get_where('project',array('id'=>$project_id))->result();
                            $project_data = $project_data[0];
                            $this->db->insert('invite',$array);
                            $id = $this->db->insert_id();
                            $link = base_url().'invitation/'.encoding($id);
                            $data1['full_name']  = $result['returnData'][0]->name;
                            $data1['url']        = $link;
                            $data1['sender_type']  = 'company';
                            $data1['is_role']  = 'project';
                            $data1['title']  = $project_data->name;
                            $message            = $this->load->view('emails/work_invite',$data1,TRUE);
                            $subject = 'Bild It - Project Invitation link';
                            $to = $result['returnData'][0]->email;
                            $this->common_model->sendMemberMail($to,$link,$message,$subject);
                            //$response = array('status'=>SUCCESS,'message'=>'People Invited Successfully');
                        }
                        // end
                    break;
                    case "AE": // User already registered
                        $clientDate = $this->db->get_where('client',array('id'=>$result['existId']))->result();
                        // list for client
                        $dataClients = $this->db->select('company_member_relations.type,client.*')
                             ->from('company_member_relations')
                             ->join('client', 'company_member_relations.member_id = client.id')
                             ->where('company_member_relations.company_id',$company_id)
                             ->where('company_member_relations.type','client')
                             ->order_by("id", "desc")->get()->result();
                        foreach($dataClients as $value)
                        {
                            $value->encrypt_id = encoding($value->id);
                            if($value->document != null)
                            {
                                $value->document = base_url('uploads/client/').$value->document;
                            }
                        }
                        $singleClientData = $this->db->get_where('client',array('id'=>$result['existId']))->result();
                        $singleClientData = $singleClientData[0];
                        $sinelClientDataObj = array(
                            'id' => $singleClientData->id,
                            'name' => $singleClientData->name
                        );
                        $index = 0;
                        $clientDataArray = array();
                        $clientDataArray[0] = $sinelClientDataObj;
                        foreach($dataClients as $dataClient)
                        {
                            if($index != $result['existId'])
                            {   
                                $clientDataArray[$index] = $dataClient;
                                $index++;
                            }
                        }
                        // end
                        $num_rows = $this->db->get_where('company_member_relations',array('company_id'=>$this->post('company_id'),'member_id'=>$result['existId'],'type'=>'client'))->num_rows();
                        if($num_rows>0){
                            $response = array('status'=>FAIL,'message'=>'Client Already Registered With Your Company','users'=>$clientDate,'url' => 'admin/client');
                        }else{
                            $response = array('status'=>SUCCESS,'message'=>'Client Invitation Send Successfully','users'=>$clientDate,'url' => 'admin/client');
                            if(!isset($_POST['projectId']))
                            {
                                // send client invitaion
                                $reciever_data = $this->db->get_where('client',array('id'=>$result['existId']))->result();
                                $reciever_data = $reciever_data[0];
                                $full_name= $reciever_data->name;
                                $array = array(
                                    'sender_id' => $this->post('company_id'),
                                    'sender_type' => 'company',
                                    'receiver_id' => $result['existId'],
                                    'reciever_type' => 'client',
                                    'is_for' => 'account'
                                );
                                $this->db->insert('invite',$array);
                                $id = $this->db->insert_id();
                                $link = base_url().'invitation/'.encoding($id);
                                $data1['full_name']  = $full_name;
                                $data1['url']        = $link;
                                $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                                $subject = 'Bild It - Account Invitation link';
                                $to = $email;
                                $this->common_model->sendMemberMail($to,$link,$message,$subject);   
                            }
                            // end
                            
                            // add project 
                            if(isset($_POST['projectId']))
                            {
                                $project_id = $_POST['projectId'];
                                $array = array(
                                    'sender_id' => $this->post('company_id'),
                                    'sender_type' => 'company',
                                    'receiver_id' => $result['existId'],
                                    'reciever_type' => 'client',
                                    'is_for' => 'project',
                                    'project_id'=>$project_id
                                );
                                $project_data = $this->db->get_where('project',array('id'=>$project_id))->result();
                                $project_data = $project_data[0];
                                $this->db->insert('invite',$array);
                                $id = $this->db->insert_id();
                                $link = base_url().'invitation/'.encoding($id);
                                $data1['full_name']  = $full_name;
                                $data1['url']        = $link;
                                $data1['sender_type']  = 'company';
                                $data1['is_role']  = 'project';
                                $data1['title']  = $project_data->name;
                                $message            = $this->load->view('emails/work_invite',$data1,TRUE);
                                $subject = 'Bild It - Project Invitation link';
                                $to = $email;
                                $this->common_model->sendMemberMail($to,$link,$message,$subject);
                                //$response = array('status'=>SUCCESS,'message'=>'People Invited Successfully');
                            }
                            // end
                        }
                        //$response = array('status'=>FAIL,'message'=>'Client Already Registered','users'=>array());
                    break;
                    default:
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'users'=>array(),'userDetail'=>array(),'url' => 'admin/client');
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'users'=>array(),'userDetail'=>array(),'url' => 'admin/client');
            }   
            $this->response($response);
        }
    }
    
    function edit_post(){
        $this->load->model('Client_model');
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_valid_email|is_unique[admin.email]',
            array('is_unique' => 'Email already exist')
        );
        if(isset($_POST['phone_number']) && $_POST['phone_number'] != ""){
            $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        }
        $this->form_validation->set_rules('name', 'name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
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
            
            //$data_val['company_id']      =   decoding($this->post('company_id'));
            // profile pic upload
            $config['upload_path']= "./uploads/client/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);


            $document = $_FILES['document']['name'];
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

            $this->db->where('id',decoding($this->input->post('id')));
            $result = $this->db->update('client',$data_val);
            $msg = 'Client Details Updated Successfully';

            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'admin/client-detail/'.$this->input->post('id'));
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
            $this->response($response);
        }
    }

    function addprojectclient_post(){
        $this->load->model('Client_model');
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
            $userData['name']           =   $name;
            $userData['email']              =   $email;
            $userData['phone_number']      =   $this->post('phone_number');
            $userData['address']      =   $this->post('address');
            $userData['company_id']      =  $this->post('company_id');

            $result = $this->Client_model->registration($userData);
            $lastId = $this->db->insert_id();
            $company_id        = $_SESSION['company_sess']['id'];
            $this->db->where('company_id',$company_id);
            $client_list = $this->db->get('client')->result();
            $html_list = "";
            foreach($client_list as $value)
            {
                if($value->id == $lastId)
                {
                    $html_list .= "<option selected value=".$value->id.">".$value->name."</option>";
                }else{
                    $html_list .= "<option value=".$value->id.">".$value->name."</option>";   
                }
            }
            
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                        $response = array('status'=>SUCCESS,'message'=>'Client Added Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/client','list'=>$html_list);
                    break;
                    case "AE": // User already registered
                        $response = array('status'=>FAIL,'message'=>'Client Already Registered','users'=>array());
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
  
}//End Class