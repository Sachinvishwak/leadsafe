<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->check_admin_user_session();
    }
    public function userDetail(){
      //pr('admin@admin.com');
        $userId             = decoding($this->uri->segment(2));
        $data['title']      = "Profile";
        $where              = array('id'=>$userId);
        $result             = $this->common_model->getsingle('admin',$where);
        $data['userData']   = $result;
        $this->load->admin_render('profile/userDetail', $data, '');
    } //End function
    public function changePassword(){
        
        $userId             = decoding($this->uri->segment(2));
        $data['title']      = "Change Password";
        $where              = array('id'=>$userId);
        $result             = $this->common_model->getsingle('admin',$where);
        $data['userData']   = $result;
        $this->load->admin_render('profile/changePassword', $data, '');
    }//End function   
}//End Class