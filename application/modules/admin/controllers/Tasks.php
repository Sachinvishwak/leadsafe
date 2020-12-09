<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tasks extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->check_admin_user_session();
    }
    public function index(){
     
        $data['title']      = "Tasks List";
        $count              = $this->common_model->get_total_count('tasks',array('created_by'=>1));
        $count              = number_format_short($count);
        $link               = base_url().'admin/tasks/add';
        $data['recordSet']  = array('<li class="sparks-info"><h5 style="text-align:center;">Add Task<span class="txt-color-blue"><a href="'.$link.'" class="anchor-btn"><i class="fa fa-plus-square"></i></a></span></h5></li>','<li class="sparks-info"><h5>Total Tasks <span class="txt-color-darken" id="totalCust"><i style="font-size: 17px;" class="fa fa-lg fa-fw fa fa-tags"></i>&nbsp;'.$count.'</span></h5></li>');
           $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/task.js');
        $this->load->admin_render('tasks/index', $data,'');
    } //End function

    public function add() { 
        
        $data['title']              = 'Add Task';
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->admin_render('tasks/add', $data);
    } //End Function

    public function edit() { 
        $id             = decoding($this->uri->segment(4));
        //pr($id);
         $where              = array('taskId'=>$id);
        $result             = $this->common_model->getsingle('tasks',$where);
        $data['title']              = 'Edit Task';
        $data['task']              = $result;
        $task_meta              = $this->common_model->getAll('task_meta',array('taskId'=>$id),'sorting_order','asc');
        $data['task_meta']      = $task_meta;
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->admin_render('tasks/add', $data);
    } //End Function
    
    public function detail(){
        
      //pr('admin@admin.com');
        $id             = decoding($this->uri->segment(2));

        $data['title']      = "Task detail";
        $where              = array('taskId'=>$id);
        $result             = $this->common_model->getsingle('tasks',$where);
        $task_meta              = $this->common_model->getAll('task_meta',array('taskId'=>$id),'sorting_order','asc');
        $data['task']       = $result;
        $data['task_meta']      = $task_meta;

        $data['front_styles']      = array('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        $data['front_scripts']      = array('https://code.jquery.com/ui/1.12.1/jquery-ui.js','backend_assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js','backend_assets/custom/js/task.js');
        $this->load->admin_render('tasks/detail', $data,'');
    } //End function
  
}//End Class