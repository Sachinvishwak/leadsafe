<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notification extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
    }
    public function index(){
        $data['title']      = "Notification List";
        $count              = $this->common_model->get_total_count('task_approve_notifications');
        $count              = number_format_short($count);
		$data['company_id']         = $_SESSION['company_sess']['id'];

        $data['recordSet']  = array('<li class="sparks-info"><h5>Total Notifications <span class="txt-color-darken" id="totalCust"><i class="fa fa-lg fa-fw fa fa-tags"></i>&nbsp;'.$count.'</span></h5></li>');
           $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/task.js');
        $this->load->company_render('notification/index', $data,'');
    } //End function
  
}//End Class
