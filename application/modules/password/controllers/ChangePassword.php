<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ChangePassword extends Common_Back_Controller {

    public $data = array();
    public $file_data = "";

    public function __construct() {
        parent::__construct();
        //$this->form_validation->CI =& $this;  //required for form validation callbacks in HMVC
        $this->validation_rules = array();
        //$this->load->model('common_model');
    }
    // Change Password  From email Template url
    public function change_password(){
       
         //$data['title']         = "";
         $data['email']         = decoding($this->uri->segment(4));
         $data['passToken']     = $this->uri->segment(5);
       //  $data['check_send']=0;
         $data1['changetype']   = "";
        
         $response  = $this->common_model->is_data_exists('admin',$data);// check user valid or Invalid
        
         if($response){
            $data1['encode_email']=$this->uri->segment(4);
           $this->load->login_render('change_password',$data1);
         }else{
            $this->load->login_render('link_expired');
         }       
    }//End Function 
    // Update Password 
    public function update_password(){

       $this->form_validation->set_rules('password', 'New Password', 'required|trim|min_length[6]',array('min_length' => 'Password should have minimum 6 character.'));
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]',array('matches' => 'Confirm password does not match.'));
        
        if ($this->form_validation->run() == FALSE){
            $messages       = (validation_errors()) ? validation_errors() : '';
            $response       = array('status' => 0, 'message' => $messages);
        }else{
            $changetype     = $this->input->post('changetype');
            $update_data    = array(
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'pwd' => $this->input->post('password'),
                'passToken' => "",
            );
            $table          = 'admin';
            $where_email    = array('email'=>decoding($this->input->post('e'))); // decode email id
            $response       = $this->common_model->getsingle($table, $where_email);    
            if($response){
              $rurl         = base_url().'password/ChangePassword/success';  // For Redirect after change password
              $this->common_model->updateFields($table, $update_data, $where_email);  //update Password
              $response     = array('status' =>SUCCESS, 'message' =>'Password Change successfully.', 'url'=>$rurl); //success msg
            }else{
              $response     = array('status' =>FAIL, 'message' => 'Email id does not exist.');  
            }
            //print_r($response); die();
        }
        echo json_encode($response);
    }//End Function
    // Change Password Success Template url
    public function change_password_suucess(){
       $this->load->view('passwordsuccess');      
    } //End Function
    
    
    /// member password reset successfully
    public function meber_password_reset()
    {
        $this->form_validation->set_rules('npassword', 'New Password', 'required|trim|min_length[6]',array('min_length' => 'Password should have minimum 6 character.'));
        $this->form_validation->set_rules('rnpassword', 'Confirm Password', 'trim|required|matches[npassword]',array('matches' => 'Confirm password does not match.'));
        
        if ($this->form_validation->run() == FALSE){
            $messages       = (validation_errors()) ? validation_errors() : '';
            $response       = array('status' => 0, 'message' => $messages);
        }else{
            $update_data    = array(
                'password' => password_hash($this->input->post('npassword'), PASSWORD_DEFAULT),
                'pwd' => $this->input->post('npassword')
            );
            $role = $this->input->post('role');
            if($role == 'client')
            {
                $table = 'client';
            }else if($role == 'leadcontractor' || $role == 'subcontractor')
            {
                $table = 'contractor';
            }else if($role == 'crew')
            {
                $table = 'crew_member';
            }else if($role == 'admin')
            {
                $table = 'company';
            }else if($role == 'company')
            {
                $table = 'company';
            }
            
            
            $where_email    = array('email'=>$this->input->post('email')); // decode email id
            $response       = $this->common_model->getsingle($table, $where_email);    
            if($response){
              $rurl         = 'password/ChangePassword/change_password_suucess';  // For Redirect after change password
              $this->common_model->updateFields($table, $update_data, $where_email);  //update Password
              $response     = array('status' =>SUCCESS, 'message' =>'Password Change successfully.', 'url'=>$rurl); //success msg
            }else{
              $response     = array('status' =>FAIL, 'message' => 'Email id does not exist.');  
            }
        }
        echo json_encode($response);
    }
    
    
    
    
    
} //End class