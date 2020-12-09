<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Company extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->check_admin_user_session();
    }
    public function index(){
        $data['title']      = "Company List";
        $count              = $this->common_model->get_total_count('company');
        $count              = number_format_short($count);
        $link               = base_url().'admin/company/add';
        $data['recordSet']  = array('<li class="sparks-info"><h5 style="text-align:center;"> Add Company<span class="txt-color-blue"><a href="'.$link.'" class="anchor-btn"><i class="fa fa-plus-square"></i></a></span></h5></li>','<li class="sparks-info"><h5>Total Company <span class="txt-color-darken" id="totalCust"><i style="font-size: 17px;" class="fa fa-lg fa-fw fa fa-building"></i>&nbsp;'.$count.'</span></h5></li>');
           $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/company.js');
        $this->load->admin_render('company/index', $data,'');
    } //End function

    public function add() { 
        
        $data['title']              = 'Add New Company';
        $data['front_scripts']      = array('backend_assets/custom/js/company.js');
        $this->load->admin_render('company/add', $data);
    } //

    public function detail(){
      //pr('admin@admin.com');
        $id             = decoding($this->uri->segment(2));

        $data['title']      = "Company Details";
        $where              = array('company_id'=>$id);
        $result             = $this->common_model->getsingle('company',$where);
        $data['task']       = $result;

        $data['front_styles']      = array('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        $data['front_scripts']      = array('https://code.jquery.com/ui/1.12.1/jquery-ui.js','backend_assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js','backend_assets/custom/js/company.js');
        $this->load->admin_render('company/detail', $data,'');
    } //

    public function edit() { 
        
        $id             = decoding($this->uri->segment(4));
        //pr($id);
        $where              = array('company_id'=>$id);
        $result             = $this->common_model->getsingle('company',$where);
		$data['title']              = 'Edit Company';
		
		$license_media = $this->db->get_where('license_media',array('type'=>'company','user_id'=>$id))->result();
		foreach($license_media as $licenses)
		{
			$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
		}
	
		$data['license_media'] = $license_media;

        $data['company']              = $result;
        $data['front_scripts']      = array('backend_assets/custom/js/company.js');
        $this->load->admin_render('company/edit', $data);
    } //End Function
    
    public function companytask()
    {
        $data['title']      = "Admin Tasks List";
        $count              = $this->common_model->get_total_count('tasks');
        $count              = number_format_short($count);
        $link               = base_url().'admin/tasks/add';
        $data['recordSet']  = array('<li class="sparks-info"><h5 style="text-align:center;">Add Task<span class="txt-color-blue"><a href="'.$link.'" class="anchor-btn"><i class="fa fa-plus-square"></i></a></span></h5></li>','<li class="sparks-info"><h5>Total Tasks <span class="txt-color-darken" id="totalCust"><i class="fa fa-lg fa-fw fa fa-tags"></i>&nbsp;'.$count.'</span></h5></li>');
           $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/task.js');
        $this->load->admin_render('company/tasklist', $data,'');
    }
    
    public function updateTask($table,$taskId,$array)
    {
        $this->db->where('taskId',$taskId);
        $this->db->update($table,$array);
    }
    
    function DuplicateMySQLRecord ($table, $primary_key_field, $primary_key_val)
    {
       $this->db->where($primary_key_field, $primary_key_val); 
       $query = $this->db->get($table);
        foreach ($query->result() as $row){   
           foreach($row as $key=>$val){        
              if($key != $primary_key_field){
                $this->db->set($key, $val);               
              }           
           }
        }
        $this->db->insert($table); 
        return $this->db->insert_id();
    }
    
    public function superAdminimport()
    {
        $taskId         = decoding($_POST['taskId']);
        $array = array('is_deleted'=>1);
        $this->updateTask('tasks',$taskId,$array);
        
        $insertId = $this->DuplicateMySQLRecord('tasks','taskId',$taskId);
        $taskId = $insertId;
        $array = array('created_by'=>1,'is_exported'=>1);
        $this->updateTask('tasks',$taskId,$array);
        
        $response   = array('status'=>SUCCESS,'message'=>'Imported Successfully');
        echo json_encode($response);
    }
    
    
    
}//End Class
