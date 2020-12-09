<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Crewuser extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        
      ini_set('display_errors', 1);
    }

     public function index() { 
        $data['title'] = "Resetpassword";
        $this->load->Resetpassword_render('Resetpasswordview', $data);
    }//End Function

  
}//End Class