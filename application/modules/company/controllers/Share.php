<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Share extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
    }
    
    /// invite
    public function index()
    {
        $id = end($this->uri->segment_array());
        $data['id'] = base64_decode($id);
        $company_id = base64_decode($id);
        $this->load->database();
        $company_data = $this->db->get_where('company',array('company_id'=>$company_id))->result();
        $company_data = $company_data[0];
        $data['company_data'] = $company_data;
        $this->load->login_render('share/resetpasssword', $data);
    }
    
    public function updateQuery($table,$where,$array){
        $this->db->where($where);
        $this->db->update($table,$array);
    }
    
    public function insertData($table,$array){
        $this->db->insert($table,$array);
        return $this->db->insert_id();
    }
    
    public function invitation(){
        $id = end($this->uri->segment_array());
        $array = explode("&",$id);
        // link = email + type +is_for + name + project_id
        $email = decoding($array[0]);
        $type = decoding($array[1]);
        $is_for = decoding($array[2]);
        $name = decoding($array[3]);
        $invite_id = decoding($array[4]);
        $project_id = decoding($array[5]);
        $company_id = decoding($array[6]);
        
        if($type == 'leadcontractor'){
            $this->db->where('email',$email);
            $this->db->where('is_role',1);
            $data = $this->db->get('contractor');
            $count = $data->num_rows();
            if($count > 0){
                $data = $data->result();
                $insertId = $data[0]->id;
            }else{
                $array = array(
                    'email' => $email,
                    'owner_first_name' => $name,
                    'is_role' => 1
                );
                $insertId = $this->insertData('contractor',$array);
            }
        }else if($type == 'subcontractor'){
            $this->db->where('email',$email);
            $this->db->where('is_role',2);
            $data = $this->db->get('contractor');
            $count = $data->num_rows();
            if($count > 0){
                $data = $data->result();
                $insertId = $data[0]->id;
            }else{
                $array = array(
                    'email' => $email,
                    'owner_first_name' => $name,
                    'is_role' => 2
                );
                $insertId = $this->insertData('contractor',$array);
            }
        }else if($type == 'crew'){
            $this->db->where('email',$email);
            $data = $this->db->get('crew_member');
            $count = $data->num_rows();
            if($count > 0){
                $data = $data->result();
                $insertId = $data[0]->id;
            }else{
                $array = array(
                    'email' => $email,
                    'name' => $name
                );
                $insertId = $this->insertData('crew_member',$array);
            }
        }
        
        $array1 = array(
            'member_id' => $insertId,
            'type' => $type,
            'company_id' => $company_id
        );
        $this->db->insert('company_member_relations',$array1);
        
        $array = array(
            'user_id' => $insertId,
            'is_for' => $is_for,
            'role' => $type,
            'project_id' =>$project_id,
            'person_name' => $name
        );
        $this->db->insert('invite_people',$array);
        $this->db->where('id',$invite_id);
        $this->db->update('invite',array('is_accepted',1));
        redirect('/');
    }
    
    public function commonresetmemberpassword()
    {
        $full_name = "";
        $email = "";
        $role = "";
        $ispasswordset = false;
        $encrypt_id = end($this->uri->segment_array());
        $id= decoding($encrypt_id);
        $is_accepted = 0;
        $invite_data = $this->db->get_where('invite',array('id'=>$id))->result();
        if(isset($invite_data[0]))
        {
            $invite_data = $invite_data[0];
            $is_accepted = $invite_data->is_accepted;
            $is_for = $invite_data->is_for;
            $receiver_id = $invite_data->receiver_id;
            $reciever_type = $invite_data->reciever_type;
            $sender_type = $invite_data->sender_type;
            $sender_id = $invite_data->sender_id;
            $full_name = "";
            if($reciever_type == "leadcontractor" || $reciever_type == "subcontractor")
            {
                $reciever_data = $this->db->get_where('contractor',array('id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->owner_first_name;
                    $email= $reciever_data->email;
                    $ispasswordset = true;
                }
            }else if($reciever_type == "crew")
            {
                $reciever_data = $this->db->get_where('crew_member',array('id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->name;
                    $email= $reciever_data->email;
                    $ispasswordset = true;
                }
            }else if($reciever_type == "client")
            {
                $reciever_data = $this->db->get_where('client',array('id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->name;
                    $email= $reciever_data->email;
                    $ispasswordset = true;
                }
            }else if($reciever_type == "admin")
            {
                $reciever_data = $this->db->get_where('company',array('company_id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->name;
                    $email= $reciever_data->email;
                    $ispasswordset = true;
                }
            }else if($reciever_type == "company")
            {
                
                $reciever_data = $this->db->get_where('company',array('company_id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->name;
                    $email= $reciever_data->email;
                    $ispasswordset = true;
                }
            }
            
        }else{
            $message = 'Something Went Wrong .';
        }
        $data['name'] = $full_name;
        $data['email'] = $email;
        $data['role'] = $reciever_type;
        $data['ispasswordset'] = $ispasswordset;
        $data['message'] = $message;
       
        if($is_accepted == 1)
        {
            $this->load->login_render('share/alreadyusedlink', $data);
        }else{
            $this->load->login_render('share/resetmemberpassword', $data);   
        }
    }
    
    public function resetmemberpassword()
    {
        $full_name = "";
        $email = "";
        $role = "";
        $ispasswordset = false;
        $encrypt_id = end($this->uri->segment_array());
        $id= decoding($encrypt_id);
        $invite_data = $this->db->get_where('invite',array('id'=>$id))->result();
        if(isset($invite_data[0]))
        {
            $invite_data = $invite_data[0];
            $is_for = $invite_data->is_for;
            $receiver_id = $invite_data->receiver_id;
            $reciever_type = $invite_data->reciever_type;
            $sender_type = $invite_data->sender_type;
            $sender_id = $invite_data->sender_id;
            $full_name = "";
            if($reciever_type == "leadcontractor" || $reciever_type == "subcontractor")
            {
                $reciever_data = $this->db->get_where('contractor',array('id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->owner_first_name;
                    $email= $reciever_data->email;
                    if($reciever_data->password == "" || $reciever_data->password == NULL)
                    {
                        $ispasswordset = true;
                    }else{
                        $message = 'Your Password Reset Successfully.';
                    }
                }
            }else if($reciever_type == "crew")
            {
                $reciever_data = $this->db->get_where('crew_member',array('id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->name;
                    $email= $reciever_data->email;
                    if($reciever_data->password == "" || $reciever_data->password == NULL)
                    {
                        $ispasswordset = true;
                    }else{
                        $message = 'Your Password Reset Successfully.';
                    }
                }
            }else if($reciever_type == "client")
            {
                $reciever_data = $this->db->get_where('client',array('id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->name;
                    $email= $reciever_data->email;
                    if($reciever_data->password == "" || $reciever_data->password == NULL)
                    {
                        $ispasswordset = true;
                    }else{
                        $message = 'Your Password Reset Successfully.';
                    }
                }
            }
        }else{
            $message = 'Something Went Wrong .';
        }
        $data['name'] = $full_name;
        $data['email'] = $email;
        $data['role'] = $reciever_type;
        $data['ispasswordset'] = $ispasswordset;
        $data['message'] = $message;
        $this->load->login_render('share/resetmemberpassword', $data);
    }
    
    public function memeberinvitaion()
    {
        $isshow = false;
        $reseturl = "";
        $encrypt_id = end($this->uri->segment_array());
        $is_for = "";
        $id = end($this->uri->segment_array());
        $id= decoding($id);
        $invite_data = $this->db->get_where('invite',array('id'=>$id))->result();
        if(isset($invite_data[0]))
        {
            $invite_data = $invite_data[0];
            $is_for = $invite_data->is_for;
            $receiver_id = $invite_data->receiver_id;
            $reciever_type = $invite_data->reciever_type;
            $sender_type = $invite_data->sender_type;
            $sender_id = $invite_data->sender_id;
            $full_name = "";
            if($reciever_type == "leadcontractor" || $reciever_type == "subcontractor")
            {
                $reciever_data = $this->db->get_where('contractor',array('id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->owner_first_name;  
                    if($reciever_data->password == "" || $reciever_data->password == NULL)
                    {
                        $isshow = true;
                        $reseturl = base_url('resetmemberpassword/'.$encrypt_id);
                    }
                }
            }else if($reciever_type == "crew")
            {
                $reciever_data = $this->db->get_where('crew_member',array('id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->name;
                    if($reciever_data->password == "" || $reciever_data->password == NULL)
                    {
                        $isshow = true;
                        $reseturl = base_url('resetmemberpassword/'.$encrypt_id);
                    }
                }
            }else if($reciever_type == "client")
            {
                $reciever_data = $this->db->get_where('client',array('id'=>$receiver_id))->result();
                if(!isset($reciever_data[0]))
                {
                    $reciever_data = "";
                    $full_name= "";
                    $message = 'User Account Not Exist'; 
                }else{
                    $reciever_data = $reciever_data[0];
                    $full_name= $reciever_data->name;
                    if($reciever_data->password == "" || $reciever_data->password == NULL)
                    {
                        $isshow = true;
                        $reseturl = base_url('resetmemberpassword/'.$encrypt_id);
                    }
                }
            }
            if($reciever_data == "")
            {
                $data['message'] = $message;
            }else{
                if($invite_data->is_for == "account")
                {
                    if($sender_type == "company")
                    {
                        $num_rows = $this->db->get_where('company_member_relations',array('company_id'=>$sender_id,'member_id'=>$receiver_id,'type'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            $message = 'You are already Registered With This Company';
                        }else{
                            $array = array(
                                'company_id' => $sender_id,
                                'member_id' => $receiver_id,
                                'type' => $reciever_type
                            );
                            $this->db->insert('company_member_relations',$array);
                            $message = 'You are now member of this company, thanks for accepting this invitation';
                        }
                    }else if($sender_type == "leadcontractor")
                    {
                        $num_rows = $this->db->get_where('contractor_member_relationship',array('contractor_id'=>$sender_id,'contractor_type'=>'leadcontractor','member_id'=>$receiver_id,'type'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            $message = 'You are already Registered With This Lead Contractor';
                        }else{
                            $array = array(
                                'contractor_id' => $sender_id,
                                'member_id' => $receiver_id,
                                'type' => $reciever_type,
                                'contractor_type'=>'leadcontractor'
                            );
                            $this->db->insert('contractor_member_relationship',$array);
                            $message = 'You are now member of this lead contractor, thanks for accepting this invitation';
                        }
                    }else if($sender_type == "subcontractor")
                    {
                        $num_rows = $this->db->get_where('contractor_member_relationship',array('contractor_id'=>$sender_id,'contractor_type'=>'subcontractor','member_id'=>$receiver_id,'type'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            $message = 'You are already Registered With This sub contractor';
                        }else{
                            $array = array(
                                'contractor_id' => $sender_id,
                                'member_id' => $receiver_id,
                                'type' => $reciever_type,
                                'contractor_type'=>'subcontractor'
                            );
                            $this->db->insert('contractor_member_relationship',$array);
                            $message = 'You are now member of this sub contractor, thanks for accepting this invitation';
                        }
                    }
                }else if($invite_data->is_for == "project")
                {
                    if($sender_type == "company")
                    {
                        // add member 
                        $num_rows = $this->db->get_where('company_member_relations',array('company_id'=>$sender_id,'member_id'=>$receiver_id,'type'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            //$message = 'You are already Registered With This Company';
                        }else{
                            $array = array(
                                'company_id' => $sender_id,
                                'member_id' => $receiver_id,
                                'type' => $reciever_type
                            );
                            $this->db->insert('company_member_relations',$array);
                        }
                        // end
                        $num_rows = $this->db->get_where('invite_people',array('project_id'=>$invite_data->project_id,'user_id'=>$receiver_id,'role'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            $message = 'You are already Added in This Project.';
                        }else{
                            $array = array(
                                'user_id' => $receiver_id,
                                "person_name" => $full_name,
                                "is_for" => "project",
                                "taskId" => $invite_data->taskId,
                                "project_id" => $invite_data->project_id,
                                "role" => $reciever_type
                            );
                            $this->db->insert('invite_people',$array);
                            $message = 'You are now added in this project, thanks for accepting this invitation';
                        }
                    }else if($sender_type == "leadcontractor")
                    {
                        $num_rows = $this->db->get_where('contractor_member_relationship',array('contractor_id'=>$sender_id,'contractor_type'=>'leadcontractor','member_id'=>$receiver_id,'type'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                        }else{
                            $array = array(
                                'contractor_id' => $sender_id,
                                'member_id' => $receiver_id,
                                'type' => $reciever_type,
                                'contractor_type'=>'leadcontractor'
                            );
                            $this->db->insert('contractor_member_relationship',$array);
                        }
                        $num_rows = $this->db->get_where('invite_people',array('project_id'=>$invite_data->project_id,'user_id'=>$receiver_id,'role'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            $message = 'You are already Added in This Project.';
                        }else{
                            $array = array(
                                'user_id' => $receiver_id,
                                "person_name" => $full_name,
                                "is_for" => "project",
                                "taskId" => $invite_data->taskId,
                                "project_id" => $invite_data->project_id,
                                "role" => $reciever_type
                            );
                            $this->db->insert('invite_people',$array);
                            $message = 'You are now added in this project, thanks for accepting this invitation';
                        }
                    }else if($sender_type == "subcontractor")
                    {
                        $num_rows = $this->db->get_where('contractor_member_relationship',array('contractor_id'=>$sender_id,'contractor_type'=>'subcontractor','member_id'=>$receiver_id,'type'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                        }else{
                            $array = array(
                                'contractor_id' => $sender_id,
                                'member_id' => $receiver_id,
                                'type' => $reciever_type,
                                'contractor_type'=>'subcontractor'
                            );
                            $this->db->insert('contractor_member_relationship',$array);
                        }
                        $num_rows = $this->db->get_where('invite_people',array('project_id'=>$invite_data->project_id,'user_id'=>$receiver_id,'role'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            $message = 'You are already Added in This Project.';
                        }else{
                            $array = array(
                                'user_id' => $receiver_id,
                                "person_name" => $full_name,
                                "is_for" => "project",
                                "taskId" => $invite_data->taskId,
                                "project_id" => $invite_data->project_id,
                                "role" => $reciever_type
                            );
                            $this->db->insert('invite_people',$array);
                            $message = 'You are now added in this project, thanks for accepting this invitation';
                        }
                    }
                }else if($invite_data->is_for == "task")
                {
                    if($sender_type == "company")
                    {
                        // add member 
                        $num_rows = $this->db->get_where('company_member_relations',array('company_id'=>$sender_id,'member_id'=>$receiver_id,'type'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            //$message = 'You are already Registered With This Company';
                        }else{
                            $array = array(
                                'company_id' => $sender_id,
                                'member_id' => $receiver_id,
                                'type' => $reciever_type
                            );
                            $this->db->insert('company_member_relations',$array);
                            //$message = 'You are now member of this company, thanks for accepting this invitation';
                        }
                        // end
                        $num_rows = $this->db->get_where('invite_people',array('taskId'=>$invite_data->taskId,'user_id'=>$receiver_id,'role'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            $message = 'You are already Added in This Task.';
                        }else{
                            $array = array(
                                'user_id' => $receiver_id,
                                "person_name" => $full_name,
                                "is_for" => "project",
                                "taskId" => $invite_data->taskId,
                                "project_id" => $invite_data->project_id,
                                "role" => $reciever_type
                            );
                            $this->db->insert('invite_people',$array);
                            $message = 'You are now added in this task, thanks for accepting this invitation';
                        }
                    }
                    else if($sender_type == "leadcontractor")
                    {
                        $num_rows = $this->db->get_where('contractor_member_relationship',array('contractor_id'=>$sender_id,'contractor_type'=>'leadcontractor','member_id'=>$receiver_id,'type'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                        }else{
                            $array = array(
                                'contractor_id' => $sender_id,
                                'member_id' => $receiver_id,
                                'type' => $reciever_type,
                                'contractor_type'=>'leadcontractor'
                            );
                            $this->db->insert('contractor_member_relationship',$array);
                        }
                        $num_rows = $this->db->get_where('invite_people',array('taskId'=>$invite_data->taskId,'user_id'=>$receiver_id,'role'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            $message = 'You are already Added in This Task.';
                        }else{
                            $array = array(
                                'user_id' => $receiver_id,
                                "person_name" => $full_name,
                                "is_for" => "project",
                                "taskId" => $invite_data->taskId,
                                "project_id" => $invite_data->project_id,
                                "role" => $reciever_type
                            );
                            $this->db->insert('invite_people',$array);
                            $message = 'You are now added in this task, thanks for accepting this invitation';
                        }
                    }else if($sender_type == "subcontractor")
                    {
                        $num_rows = $this->db->get_where('contractor_member_relationship',array('contractor_id'=>$sender_id,'contractor_type'=>'subcontractor','member_id'=>$receiver_id,'type'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                        }else{
                            $array = array(
                                'contractor_id' => $sender_id,
                                'member_id' => $receiver_id,
                                'type' => $reciever_type,
                                'contractor_type'=>'subcontractor'
                            );
                            $this->db->insert('contractor_member_relationship',$array);
                        }
                        $num_rows = $this->db->get_where('invite_people',array('taskId'=>$invite_data->taskId,'user_id'=>$receiver_id,'role'=>$reciever_type))->num_rows();
                        if($num_rows>0){
                            $message = 'You are already Added in This Task.';
                        }else{
                            $array = array(
                                'user_id' => $receiver_id,
                                "person_name" => $full_name,
                                "is_for" => "project",
                                "taskId" => $invite_data->taskId,
                                "project_id" => $invite_data->project_id,
                                "role" => $reciever_type
                            );
                            $this->db->insert('invite_people',$array);
                            $message = 'You are now added in this task, thanks for accepting this invitation';
                        }
                    }
                }
            }
        }else{
            $message = 'Something Went Wrong.';
        }
        $data['full_name'] = $full_name;
        $data['message'] = $message;
        $data['isshow'] = $isshow;
        $data['reseturl'] = $reseturl;
        $data['isFor'] = $is_for;
        $this->load->view('invite/Resetpasswordview',$data);
    }
    
    
}//End Class