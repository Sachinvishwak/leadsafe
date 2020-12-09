<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Company extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->check_admin_user_session();
    }
    public function index(){
        $data['title']      = "Company";
        $count              = $this->common_model->get_total_count('company');
        $count              = number_format_short($count);
        $link               = base_url().'admin/company/add';
        $data['recordSet']  = array('<li class="sparks-info"><h5>Company<span class="txt-color-blue"><a href="'.$link.'" class="anchor-btn"><i class="fa fa-plus-square"></i></a></span></h5></li>','<li class="sparks-info"><h5>Total Company <span class="txt-color-darken" id="totalCust"><i class="fa fa-lg fa-fw fa fa-tags"></i>&nbsp;'.$count.'</span></h5></li>');
        $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/company.js');
        $this->load->admin_render('company/index', $data,'');
    } //End function

    public function add() { 
        
        $data['title']              = 'Company Task';
        $data['front_scripts']      = array('backend_assets/custom/js/company.js');
        $this->load->admin_render('company/add', $data);
    } //

    public function detail(){
      //pr('admin@admin.com');
        $id             = decoding($this->uri->segment(2));

        $data['title']      = "Company detail";
        $where              = array('company_id'=>$id);
        $result             = $this->commhaton_model->getsingle('company',$where);
		$data['task']       = $result;
		
		$license_media = $this->db->get_where('license_media',array('type'=>'company','user_id'=>$id))->result();
		foreach($license_media as $licenses)
		{
			$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
		}
	
		$data['license_media'] = $license_media;

        $data['front_styles']      = array('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        $data['front_scripts']      = array('https://code.jquery.com/ui/1.12.1/jquery-ui.js','backend_assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js','backend_assets/custom/js/company.js');
        $this->load->admin_render('company/detail', $data,'');
    } //

    public function edit() { 
        
        $id             = decoding($this->uri->segment(4));
        //pr($id);
        $where              = array('company_id'=>$id);
        $result             = $this->common_model->getsingle('company',$where);
        $data['title']              = 'Edit Task';
        $data['company']              = $result;
        $data['front_scripts']      = array('backend_assets/custom/js/company.js');
        $this->load->admin_render('company/edit', $data);
    } //End Function
    
    
    
}//End Class
