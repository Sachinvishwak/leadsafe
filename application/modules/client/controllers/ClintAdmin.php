<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ClintAdmin extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        //$this->check_admin_user_session();
      //  $this->load->model('admin_model');
      ini_set('display_errors', 1);
    }

  
}//End Class