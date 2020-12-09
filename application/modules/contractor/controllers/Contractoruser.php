<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contractoruser extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
    }
    
    public function test()
    {
        $this->load->library('email');   
        $config = array();
        $config['protocol']     = "smtp"; // you can use 'mail' instead of 'sendmail or smtp'
        $config['smtp_host']    = "smtp.gmail.com";// you can use 'smtp.googlemail.com' or 'smtp.gmail.com' instead of 'ssl://smtp.googlemail.com'
        $config['smtp_user']    = "rathoreankit582@gmail.com"; // client email gmail id
        $config['smtp_pass']    = "A@123456a"; // client password
        $config['smtp_port']    =  587;
        $config['smtp_crypto']  = 'tls';
        $config['smtp_timeout'] = "";
        $config['mailtype']     = "html";
        $config['charset']      = "iso-8859-1";
        $config['newline']      = "\r\n";
        $config['wordwrap']     = TRUE;
        $config['validate']     = FALSE;
        $this->load->library('email', $config); // intializing email library, whitch is defiend in system
    
        $this->email->set_newline("\r\n"); // comuplsory line attechment because codeIgniter interacts with the SMTP server with regards to line break
    
        $from_email = "rathoreankit582@gmail.com"; // sender email, coming from my view page 
        $to_email = "rathoreankit582@gmail.com"; // reciever email, coming from my view page
        //Load email library
    
        $this->email->from($from_email);
        $this->email->to($to_email);
        $this->email->subject('Send Email Codeigniter'); 
        $this->email->message('The email send using codeigniter library');  // we can use html tag also beacause use $config['mailtype'] = 'HTML'
        //Send mail
        if($this->email->send()){
            $this->session->set_flashdata("email_sent","Congragulation Email Send Successfully.");
            echo "email_sent";
        }
        else{
            echo "email_not_sent";
            echo $this->email->print_debugger();  // If any error come, its run
        }
    }

    public function index($id) { 
        $contractor_id = base64_decode($id);
        
        $result = $this->db->get_where('contractor',array('id'=>$contractor_id))->result();
        if(!isset($result[0]))
        {
            $data['title'] = 'Contractor Not Found';
            $data['status'] = false;
            $this->load->Resetpassword_render('Resetpasswordview', $data);  
        }else{
            if($result[0]->password != "" || $result[0]->password != NULL)
            {
                $data['title'] = 'Your Password is Set Please Use Email and Password to Login in App';
                $data['status'] = false;
                $this->load->Resetpassword_render('Resetpasswordview', $data);
            }else{
                $data['id'] = $contractor_id;
                $data['title'] = "Resetpassword";
                $data['status'] = true;
                $this->load->Resetpassword_render('Resetpasswordview', $data);   
            }
        }
    }//End Function


  
}//End Class