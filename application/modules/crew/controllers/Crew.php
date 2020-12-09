<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Crew extends Common_Back_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        //$this->check_admin_user_session();
    }
    public function index(){
        $data['title']      = "Crew";
        $count              = $this->common_model->get_total_count('crew_member',array('company_id'=>$_SESSION['company_sess']['id']));
        $count              = number_format_short($count);
        $link               = base_url().'company/Crew/add';
        $data['recordSet']  = array('<li class="sparks-info text-center"><h5>Add Crew<span class="txt-color-blue"><a href="'.$link.'" class="anchor-btn"><i class="fa fa-plus-square"></i></a></span></h5></li>','<li class="sparks-info"><h5>Crew Member<span class="txt-color-darken" id="totalCust"><i class="fa fa-lg fa-fw fa fa-bank"></i>&nbsp;'.$count.'</span></h5></li>');
        $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/task.js');
        $this->load->company_render('crew/index', $data,'');
    } //End function

    public function add() { 
        
        $data['title']              = 'Add Crew';
        $data['company_id']         = $_SESSION['company_sess']['id'];
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->company_render('crew/add', $data);
    } //End Function

    public function edit() {
        $id             = decoding(end($this->uri->segment_array()));
        $where              = array('id'=>$id);
        $result             = $this->common_model->getsingle('crew_member',$where);
        $data['title']              = 'Edit Crew';
        $data['company']              = $result;
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->company_render('crew/edit', $data);
    } //End Function
    
    public function detail(){
        $id             = decoding(end($this->uri->segment_array()));
        $data['title']      = "Crew detail";
        $where              = array('id'=>$id);
        $result             = $this->common_model->getsingle('crew_member',$where);
        $data['task']       = $result;
        $data['front_styles']      = array('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        $data['front_scripts']      = array('https://code.jquery.com/ui/1.12.1/jquery-ui.js','backend_assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js','backend_assets/custom/js/task.js');
        $this->load->company_render('crew/detail', $data,'');
    } //End function
  
}//End Class