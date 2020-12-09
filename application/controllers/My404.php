<?php 
class My404 extends Common_Back_Controller
{
  function __construct()
  {
    parent::__construct();
    ini_set("display_errors", "1");
    error_reporting(E_ALL);
  }
  function index(){
    $data['title']          = "404";
    $this->load->login_render('404', $data);
  }//End function
}//End Class
?>