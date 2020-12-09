<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        //$this->check_admin_user_session();
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
        $userId             = decoding(end($this->uri->segment_array()));
        $data['title']      = "Change Password";
        $where              = array('company_id'=>$userId);
        $result             = $this->common_model->getsingle('company',$where);
        $data['userData']   = $result;
        $this->load->company_render('changePassword', $data, '');
    }//End function 

    public function sendmail()
    {
        //Load email library
        $this->load->library('email');

        //SMTP & mail configuration
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'rathoreankit582@gmail.com',
            'smtp_pass' => 'A@123456a',
            'mailtype'  => 'html',
            'charset'   => 'utf-8'
        );
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        //Email content
        $htmlContent = '<h1>Sending email via SMTP server</h1>';
        $htmlContent .= '<p>This email has sent via SMTP server from CodeIgniter application.</p>';

        $this->email->to('rathoreankit582@gmail.com');
        $this->email->from('rathoreankit582@gmail.com','Bild It');
        $this->email->subject('How to send email via SMTP server in CodeIgniter');
        $this->email->message($htmlContent);

        if($this->email->send()){
            echo "success";
            echo $this->email->print_debugger();
        }else{
            echo "failed";
            echo $this->email->print_debugger();
        }

    }
  
}//End Class
