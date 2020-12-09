<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Group extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
    }
    public function index(){
        $data['title']      = "Group List";
        $count              = $this->common_model->get_total_count('chat_group');
        $count              = number_format_short($count);
		$link               = base_url().'company/tasks/add';
		$data['company_id']         = $_SESSION['company_sess']['id'];
		

        $data['recordSet']  = array('<li class="sparks-info"><h5 style="text-align:center;">Add Group<span class="txt-color-blue"><a href="#" class="anchor-btn" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus-square"></i></a></span></h5></li>','<li class="sparks-info"><h5>Total Group <span class="txt-color-darken" id="totalCust"><i class="fa fa-lg fa-fw fa fa-tags"></i>&nbsp;'.$count.'</span></h5></li>');
           $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/task.js');
        $this->load->company_render('group/index', $data,'');
    } //End function

    public function add() { 
        $data['title']              = 'Add Group';
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->company_render('group/add', $data);
    } //End Function

    public function edit() { 
        $id             = decoding($this->uri->segment(4));
        $where              = array('id'=>$id);
		$result             = $this->common_model->getsingle('chat_group',$where);

		$project_name = "";
		if($result['project_id'] == "")
		{
			$project_name == "";
		}else{
			$projectData = $this->db->get_where('project',array('id'=>$result['project_id']))->result();
			if(isset($projectData[0]))
			{
				$project_name = $projectData[0]->name;
			}else{
				$project_name = "";
			}
		}
		$result['project_name'] = $project_name;

        $data['title']              = 'Edit Group';
		$data['group']              = $result;
		
		$data['company_id']         = $_SESSION['company_sess']['id'];
        
		$chat_group_memebers              = $this->common_model->getAll('chat_group_memeber',array('group_id'=>$id));

		$crew_group_memeber = array();
		$contractor_group_memeber = array();


		foreach($chat_group_memebers as $chat_group_memeber)
		{
			if($chat_group_memeber->role == 'contractor')
			{
				$member_id = $chat_group_memeber->member_id;
				array_push($contractor_group_memeber,$member_id);		
			}
			if($chat_group_memeber->role == 'crew')
			{
				$member_id = $chat_group_memeber->member_id;
				array_push($crew_group_memeber,$member_id);
			}
		}

		// members data
		$this->db->where('project_id',$result['project_id']);
		$invite_peoples = $this->db->get('invite_people')->result();
        $involvedPeople = array();
		$noninvolvedPeople = array();
		$contractorList = array();
		$crewList = array();
        foreach($invite_peoples as $invite_people)
        {
            $people_name = "";
            $people_position = "";
            $assigned_to = "";
            $people_email = "";
            if($invite_people->role == 'leadcontractor'){
                $this->db->where('id',$invite_people->user_id);
                $this->db->where('is_role',1);
                $contractor_data = $this->db->get('contractor')->result();
                if(isset($contractor_data[0]))
                {
                    $contractor_data = $contractor_data[0];
                    $object = (object) [
                        'invite_id' => $invite_people->id,
                        'id' => $contractor_data->id,
                        'name' => $contractor_data->owner_first_name,
                        'email' => $contractor_data->email,
                        'role' => 'leadcontractor'
                    ];
                    if($contractor_data->licence != "")
                        $contractor_data->licence = base_url('uploads/contractor/'). $contractor_data->licence;
                    if($contractor_data->insurence_certificate != "")
                        $contractor_data->insurence_certificate = base_url('uploads/contractor/').$contractor_data->insurence_certificate;  
                    if($invite_people->is_removed == 0)
                    {
                        array_push($involvedPeople,$object);
                    }else{
                        array_push($noninvolvedPeople,$object);
					}
					array_push($contractorList,$object);
                }
            }else if($invite_people->role == 'subcontractor'){
                $this->db->where('id',$invite_people->user_id);
                $this->db->where('is_role',2);
                $contractor_data = $this->db->get('contractor')->result();
                if(isset($contractor_data[0]))
                {
                    $contractor_data = $contractor_data[0];
                    $object = (object) [
                        'invite_id' => $invite_people->id,
                        'id' => $contractor_data->id,
                        'name' => $contractor_data->owner_first_name,
                        'email' => $contractor_data->email,
                        'role' => 'subcontractor'
                    ];
                    $contractor_data->licence = base_url('uploads/contractor/'). $contractor_data->licence;   
                    $contractor_data->insurence_certificate = base_url('uploads/contractor/').$contractor_data->insurence_certificate;   
                    if($invite_people->is_removed == 0)
                    {
                        array_push($involvedPeople,$object);
                    }else{
                        array_push($noninvolvedPeople,$object);
					}
					array_push($contractorList,$object);
                }
            }else if($invite_people->role == 'crew'){
                $this->db->where('id',$invite_people->user_id);
                $crew_data = $this->db->get('crew_member')->result();
                if(isset($crew_data[0]))
                {
                    $crew_data = $crew_data[0];
                    $object = (object) [
                        'invite_id' => $invite_people->id,
                        'id' => $crew_data->id,
                        'name' => $crew_data->name,
                        'email' => $crew_data->email,
                        'role'  => 'crew'
                    ];
                    if($crew_data->licence != "")
                    $crew_data->licence = base_url('uploads/crew/'). $crew_data->licence;   
                    if($crew_data->insurence_certificate != "")
                    $crew_data->insurence_certificate = base_url('uploads/crew/').$crew_data->insurence_certificate;   
                    if($invite_people->is_removed == 0)
                    {
                        array_push($involvedPeople,$object);
                    }else{
                        array_push($noninvolvedPeople,$object);
					}   
					array_push($crewList,$object);
                }
            }
		}
		$data['contractorList']      = $contractorList;
		$data['crewList']      = $crewList;
		// end
		$data['chat_group_memeber']      = $chat_group_memeber;
		$data['crew_group_memeber']      = $crew_group_memeber;
		$data['contractor_group_memeber']      = $contractor_group_memeber;
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
    
        $this->load->company_render('group/edit', $data);
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
