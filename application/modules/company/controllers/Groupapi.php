<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Groupapi extends REST_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
    }
    
    // search Api
    public function searchapi_post()
    {
        $this->db->where('company_id',$_POST['company_id']);
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $name = $_POST['name'];
            $this->db->like('name', $_POST['name']);
            $data = $this->db->get('crew_member')->result();
        }else{
            $data = $this->db->get('crew_member')->result();
        }
        echo json_encode($data);
    }
    
    public function crewlist_post()
    {
        $company_id = $this->post('company_id');
        $this->db->order_by("id", "desc");
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $data = $this->db->select('company_member_relations.type, crew_member.*')
                 ->from('company_member_relations')
                 ->join('crew_member', 'company_member_relations.member_id = crew_member.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->where('company_member_relations.type','crew')->like('name', $_POST['name'])->get()->result();
        }else{
            $data = $this->db->select('company_member_relations.type, crew_member.*')
                 ->from('company_member_relations')
                 ->join('crew_member', 'company_member_relations.member_id = crew_member.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->where('company_member_relations.type','crew')->get()->result();
        }
        foreach($data as $value)
        {
            $value->encrypt_id = encoding($value->id);
            if($value->licence != "")
            {
                $value->licence = base_url('uploads/crew/').$value->licence;   
            }
            if($value->insurence_certificate)
            {
                $value->insurence_certificate = base_url('uploads/crew/').$value->insurence_certificate;   
            }
        }
        $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Crew Member List Get Succesfully');
        $this->response($response);
    }
    
    // Client detail api
    public function crewdetail_post()
    {
        $crew_id = $this->post('crew_id');
        $data = $this->db->get_where('crew_member',array('id'=>$crew_id))->result();
        foreach($data as $value)
        {
            $value->encrypt_id = encoding($value->id);
            if($value->licence != "")
            {
                $value->licence = base_url('uploads/crew/').$value->licence;   
            }
            if($value->insurence_certificate)
            {
                $value->insurence_certificate = base_url('uploads/crew/').$value->insurence_certificate;   
			}
			
			$license_media = $this->db->get_where('license_media',array('type'=>'crew','user_id'=>$crew_id))->result();
			foreach($license_media as $licenses)
			{
				$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
			}
		
			$value->multiple_license = $license_media;
        }
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'data'=>$data,'message'=>'Crew Member Not Exist');
        }else{
            $data = $data[0];   
            $data->encrypt_id = encoding($data->id);
            $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Crew Member Detail Get Succesfully');
        }
        $this->response($response);
    }

    public function list_post(){
        $this->load->helper('text');
        $this->load->model('Group_model');
        $this->Group_model->set_data();
        $list       = $this->Group_model->get_list();
        $data       = array();
        $no         = $_POST['start'];
        foreach ($list as $serData) { 
            $action = '';
            $no++;
            $row    = array();
            $row[]  = $no;
            $link_url      = base_url().'admin/crew-detail/'.encoding($serData->id);
            $row[]  = '<a href="'.$link_url.'">'.display_placeholder_text($serData->name).'</a>'; 
			
			$projectData = $this->db->get_where('project',array('id'=>$serData->project_id))->result();
			if(isset($projectData[0]))
			{
				$project_name = $projectData[0]->name;
			}else{
				$project_name = "";
			}
			$row[]  = $project_name; 


			$member_names = "";
			$memberDatas = $this->db->get_where('chat_group_memeber',array('group_id'=>$serData->id))->result();
			foreach($memberDatas as $member)
			{
				if($member->role == "crew")
				{
					$memberDatas = $this->db->get_where('crew_member',array('id'=>$member->member_id))->result();
					if(isset($memberDatas[0]))
					{
						$member_name = $memberDatas[0]->name;
					}else{
						$member_name = "";
					}
				}else if($member->role == "contractor")
				{
					$memberDatas = $this->db->get_where('contractor',array('id'=>$member->member_id))->result();
					if(isset($memberDatas[0]))
					{
						$member_name = $memberDatas[0]->owner_first_name;
					}else{
						$member_name = "";
					}
				}else{
					$member_name = "";
				}
				$member_names .= $member_name." , ";
			}
			
			$row[]  = $member_names; 

			$row[]  = $serData->created_at;
            $row[]  = $serData->created_at; 
            // $row[]  = $serData->status;
            
            $link    = 'javascript:void(0)';
            $action .= "";
            if($serData->status){

                //$action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->id).'" data-url="adminapi/tasks/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-check" aria-hidden="true"></i></a>';
            }else{
                //$action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->id).'" data-url="adminapi/tasks/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-times" aria-hidden="true"></i></a>';
            }
            
            $action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="'.encoding($serData->id).'" data-url="company/Groupapi/recordDelete" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $link_url      = base_url().'company/group/edit/'.encoding($serData->id);
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Edit"><i class="fa fa-edit"  aria-hidden="true"></i></a>';
            $row[]  = $action;
            $data[] = $row;

        }
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->Group_model->count_all(),
            "recordsFiltered"   => $this->Group_model->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        $this->response($output);
    }
    
    
    public function recordDelete_post()
    {
        $id            = decoding($this->post('id'));
        $where              = array('id'=>$id);
        $dataExist  = $this->common_model->deleteData('chat_group',$where);
        $id            = $this->post('id');
        $where              = array('id'=>$id);
        $dataExist  = $this->common_model->deleteData('chat_group',$where);
        $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        $this->response($response);
    }
    function valid_email($str)
    {
        if ( ! preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/', $str) )
        {
            // Set the error message:
            $this->form_validation->set_message('valid_username', 'The %s field should contain only letters, numbers or periods');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    function add_post(){
		
        $this->load->model('Group_model');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
		
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            $name                       =  $this->post('name');
			$userData['name']           =   $name;
			$userData['project_id']           =   $this->post('project_id');
			$userData['created_by']           =  $this->post('company_id');
		
            $result = $this->Group_model->registration($userData);
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
						$response = array('status'=>SUCCESS,'message'=>'Group Created Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/crew_member');
						$id = $result['returnData'][0]->id;

						if(isset($_POST['contractorIds']))
						{
							$contractorIds = $_POST['contractorIds'];
							foreach($contractorIds as $contractor)
							{
								$type = 'contractor';
								$array = array(
									'member_id' => $contractor,
									'role' => $type,
									'group_id' => $id 
								);
								$this->db->insert('chat_group_memeber',$array);
							}
						}
						if(isset($_POST['crewIds']))
						{
							$crewIds = $_POST['crewIds'];
							foreach($crewIds as $crew)
							{
								$type = 'crew';
								$array = array(
									'member_id' => $crew,
									'role' => $type,
									'group_id' => $id 
								);
								$this->db->insert('chat_group_memeber',$array);
							}
						}
                        // end
                    break;
                    default:
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'userDetail'=>array());
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'userDetail'=>array());
            }   
            $this->response($response);
        }
	}

	function addnew_post(){
		
        $this->load->model('Group_model');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
		
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            $name                       =  $this->post('name');
			$userData['name']           =   $name;
			$userData['project_id']           =   $this->post('project_id');
			$userData['created_by']           =  $this->post('company_id');
		
            $result = $this->Group_model->registration($userData);
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
						$response = array('status'=>SUCCESS,'message'=>'Group Created Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/crew_member');
						$id = $result['returnData'][0]->id;

						if(isset($_POST['contractorIds']))
						{
							$contractorIds = json_decode($_POST['contractorIds']);
							foreach($contractorIds as $contractor)
							{
								$type = 'contractor';
								$array = array(
									'member_id' => $contractor,
									'role' => $type,
									'group_id' => $id
								);
								$this->db->insert('chat_group_memeber',$array);
							}
						}
						if(isset($_POST['crewIds']))
						{
							$crewIds = json_decode($_POST['crewIds']);
							foreach($crewIds as $crew)
							{
								$type = 'crew';
								$array = array(
									'member_id' => $crew,
									'role' => $type,
									'group_id' => $id 
								);
								$this->db->insert('chat_group_memeber',$array);
							}
						}
                        // end
                    break;
                    default:
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'userDetail'=>array());
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'userDetail'=>array());
            }   
            $this->response($response);
        }
	}

	public function listnew_post()
	{
		$this->db->order_by('id','desc');
		if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $this->db->like('name', $_POST['name']);
            $list = $this->db->get('chat_group')->result();
        }else{
            $list = $this->db->get('chat_group')->result();
		}
        foreach ($list as $serData) { 
			$projectData = $this->db->get_where('project',array('id'=>$serData->project_id))->result();
			if(isset($projectData[0]))
			{
				$project_name = $projectData[0]->name;
			}else{
				$project_name = "";
			}
			$serData->project_name  = $project_name; 
			$member_names = "";
            $memberDatas = $this->db->get_where('chat_group_memeber',array('group_id'=>$serData->id))->result();
            $serData->member_ids  = $memberDatas;
			foreach($memberDatas as $member)
			{
				if($member->role == "crew")
				{
					$memberDatas = $this->db->get_where('crew_member',array('id'=>$member->member_id))->result();
					if(isset($memberDatas[0]))
					{
						$member_name = $memberDatas[0]->name;
					}else{
						$member_name = "";
					}
				}else if($member->role == "contractor")
				{
					$memberDatas = $this->db->get_where('contractor',array('id'=>$member->member_id))->result();
					if(isset($memberDatas[0]))
					{
						$member_name = $memberDatas[0]->owner_first_name;
					}else{
						$member_name = "";
					}
				}else{
					$member_name = "";
				}
				$member_names .= $member_name." , ";
			}
            $serData->member_names  = $member_names;
		}
		$response = array('status'=>SUCCESS,'message'=>'Group List Get Successfully','list'=>$list);
    	$this->response($response);
	}
    
    function edit_post(){
        $this->load->model('Group_model');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            $name                       =  $this->post('name');
			$userData['name']           =   $name;
			$userData['project_id']           =   $this->post('project_id');

			$this->db->where('id',$this->input->post('id'));
            $result = $this->db->update('chat_group',$userData);
			$msg = 'Group Details Updated Successfully';

			$id = $this->input->post('id');
            
            $this->db->delete('chat_group_memeber', array('group_id' => $id)); 
            
			if(isset($_POST['contractorIds']))
			{
				$contractorIds = $_POST['contractorIds'];
				foreach($contractorIds as $contractor)
				{
					$type = 'contractor';
					$array = array(
						'member_id' => $contractor,
						'role' => $type,
						'group_id' => $id 
					);
					$this->db->insert('chat_group_memeber',$array);
				}
			}
			if(isset($_POST['crewIds']))
			{
				$crewIds = $_POST['crewIds'];
				foreach($crewIds as $crew)
				{
					$type = 'crew';
					$array = array(
						'member_id' => $crew,
						'role' => $type,
						'group_id' => $id 
					);
					$this->db->insert('chat_group_memeber',$array);
				}
			}

            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'admin/project-detail/'.$this->input->post('id'));
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
           	}
		
            $this->response($response);
        }
    }
    
    function editnew_post(){
        $this->load->model('Group_model');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            $name                       =  $this->post('name');
			$userData['name']           =   $name;
			$userData['project_id']           =   $this->post('project_id');

			$this->db->where('id',$this->input->post('id'));
            $result = $this->db->update('chat_group',$userData);
			$msg = 'Group Details Updated Successfully';

            $id = $this->input->post('id');
            
            $this->db->delete('chat_group_memeber', array('group_id' => $id)); 
			
			if(isset($_POST['contractorIds']))
			{
				$contractorIds = json_decode($_POST['contractorIds']);
				foreach($contractorIds as $contractor)
				{
					$type = 'contractor';
					$array = array(
						'member_id' => $contractor,
						'role' => $type,
						'group_id' => $id 
					);
					$this->db->insert('chat_group_memeber',$array);
				}
			}
			if(isset($_POST['crewIds']))
			{
				$crewIds = json_decode($_POST['crewIds']);
				foreach($crewIds as $crew)
				{
					$type = 'crew';
					$array = array(
						'member_id' => $crew,
						'role' => $type,
						'group_id' => $id 
					);
					$this->db->insert('chat_group_memeber',$array);
				}
			}

            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'admin/project-detail/'.$this->input->post('id'));
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
           	}
		
            $this->response($response);
        }
	}


  
}//End Class
