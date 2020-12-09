<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tasks extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
    }
    public function index(){
        $data['title']      = "Tasks List";
        $count              = $this->common_model->get_total_count('tasks');
        $count              = number_format_short($count);
        $link               = base_url().'company/tasks/add';
        $data['recordSet']  = array('<li class="sparks-info"><h5 style="text-align:center;">Add Task<span class="txt-color-blue"><a href="'.$link.'" class="anchor-btn"><i class="fa fa-plus-square"></i></a></span></h5></li>','<li class="sparks-info"><h5>Total Tasks <span class="txt-color-darken" id="totalCust"><i class="fa fa-lg fa-fw fa fa-tags"></i>&nbsp;'.$count.'</span></h5></li>');
           $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/task.js');
        $this->load->company_render('tasks/index', $data,'');
    } //End function

    public function add() { 
        $data['title']              = 'Add Task';
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->company_render('tasks/add', $data);
    } //End Function

    public function edit() { 
        $id             = decoding($this->uri->segment(4));
        $where              = array('taskId'=>$id);
        $result             = $this->common_model->getsingle('tasks',$where);
        $data['title']              = 'Edit Task';
        $data['task']              = $result;
        $task_meta              = $this->common_model->getAll('task_meta',array('taskId'=>$id),'sorting_order','asc');
        $data['task_meta']      = $task_meta;
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        
        $invite_people_data = $this->db->get_where('invite_people',array('taskId'=>$id))->result();
        foreach($invite_people_data as $value)
        {
            if($value->role == 'leadcontractor')
            {
                $user_data = $this->db->get_where('contractor',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->owner_last_name;
                $value->user_email = $user_data[0]->owner_first_name.' '.$user_data[0]->email;
            }else{
                $user_data = $this->db->get_where('crew_member',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->name;
                $value->user_email = $user_data[0]->owner_first_name.' '.$user_data[0]->email;
            }
        }
        
        $data['invite_people_data']      = $invite_people_data;
        
        
        $this->db->select('company_member_relations.*, contractor.*')->distinct()->from('company_member_relations')
         ->join('contractor', 'company_member_relations.member_id = contractor.id')->where('company_member_relations.type','leadcontractor')->where('company_member_relations.company_id',$_SESSION['company_sess']['id']);
        $data['existing_contractor'] = $this->db->get()->result();
        
        
        $this->db->select('company_member_relations.*, crew_member.*')
         ->distinct()
         ->from('company_member_relations')
         ->join('crew_member', 'company_member_relations.member_id = crew_member.id')->where('company_member_relations.type','crew')->where('company_member_relations.company_id',$_SESSION['company_sess']['id']);
        $data['existing_crew_member'] = $this->db->get()->result();
        
        $this->load->company_render('tasks/add', $data);
    } //End Function
    
    //task view process
    public function viewtask() { 
        $id             = decoding($this->uri->segment(4));
        $where              = array('taskId'=>$id);
        $result             = $this->common_model->getsingle('tasks',$where);
        $data['title']              = 'Task Detail';
        $data['task']              = $result;
        $task_meta              = $this->common_model->getAll('task_meta',array('taskId'=>$id),'sorting_order','asc');
        $data['task_meta']      = $task_meta;
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        
        $invite_people_data = $this->db->get_where('invite_people',array('taskId'=>$id))->result();
        foreach($invite_people_data as $value)
        {
            if($value->role == 'leadcontractor')
            {
                $user_data = $this->db->get_where('contractor',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->owner_last_name;
                $value->user_email = $user_data[0]->owner_first_name.' '.$user_data[0]->email;
            }else{
                $user_data = $this->db->get_where('crew_member',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->name;
                $value->user_email = $user_data[0]->owner_first_name.' '.$user_data[0]->email;
            }
        }
        
        $data['invite_people_data']      = $invite_people_data;
        
        
        $this->db->select('company_member_relations.*, contractor.*')->distinct()->from('company_member_relations')
         ->join('contractor', 'company_member_relations.member_id = contractor.id')->where('company_member_relations.type','leadcontractor')->where('company_member_relations.company_id',$_SESSION['company_sess']['id']);
        $data['existing_contractor'] = $this->db->get()->result();
        
        
        $this->db->select('company_member_relations.*, crew_member.*')
         ->distinct()
         ->from('company_member_relations')
         ->join('crew_member', 'company_member_relations.member_id = crew_member.id')->where('company_member_relations.type','crew')->where('company_member_relations.company_id',$_SESSION['company_sess']['id']);
        $data['existing_crew_member'] = $this->db->get()->result();
        
        $this->load->company_render('tasks/viewdetail', $data);
    } //End Function
    //end task view process
    public function addPeople()
    {
        $invite_id = $this->input->post('inviteId');
        
        $invite_people = $this->db->get_where('invite_people',array('id'=>$invite_id))->result();
        $invite_people = $invite_people[0];
        $is_removed = 0;
        $message = 'People Added Successfully';
        if($invite_people->is_removed == 0)
        {
            $is_removed = 1;
            $message = 'People Removed Successfully';
        }
        
        $this->db->where('id',$invite_id);
        $this->db->update('invite_people',array('is_removed'=>$is_removed));
        $data = array('status'=>'success','data'=>"",'message'=>$message);
        echo json_encode($data);
    }
    
    public function detail(){
        $id             = decoding($this->uri->segment(3));
        $data['title']      = "Task detail";
        $where              = array('taskId'=>$id);
        $result             = $this->common_model->getsingle('tasks',$where);
        $task_meta              = $this->common_model->getAll('task_meta',array('taskId'=>$id),'sorting_order','asc');
        $data['task']       = $result;
        $data['task_meta']      = $task_meta;
        
        $invite_people_data = $this->db->get_where('invite_people',array('taskId'=>$id))->result();
        foreach($invite_people_data as $value)
        {
            if($value->role == 'leadcontractor' || $value->role == 'subcontractor')
            {
                $user_data = $this->db->get_where('contractor',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->owner_last_name;
                $value->user_email = $user_data[0]->owner_first_name.' '.$user_data[0]->email;
            }else{
                $user_data = $this->db->get_where('crew_member',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->name;
                $value->user_email = $user_data[0]->owner_first_name.' '.$user_data[0]->email;
            }
        }
        
        
        

        $data['front_styles']      = array('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        $data['front_scripts']      = array('https://code.jquery.com/ui/1.12.1/jquery-ui.js','backend_assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js','backend_assets/custom/js/task.js');
        $this->load->company_render('tasks/detail', $data,'');
    } //End function
  
}//End Class