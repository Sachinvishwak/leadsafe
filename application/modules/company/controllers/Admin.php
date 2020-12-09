<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Common_Back_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        //$this->check_admin_user_session();
      //  $this->load->model('admin_model');
      ini_set('display_errors', 1);
    }//End Function
    
    public function check_profile()
    {
        $session_u_id              = $_SESSION['company_sess']['id'];
        $uData                          = $this->db->get_where('company',array('company_id'=>$session_u_id))->result();
        $uData = $uData[0];
        if($uData->phone_number == "" || $uData->phone_number == NULL || $uData->profile_photo == "" || $uData->profile_photo == NULL   )
        {
            redirect('admin/complete_profile');
        }
        return true;
    }
    
   
    
    //complete profile
    public function complete_profile()
    {
        $session_u_id              = $_SESSION['company_sess']['id'];
        $uData                          = $this->db->get_where('company',array('company_id'=>$session_u_id))->result_array();
        $uData = $uData[0];
        $data['title'] = "Complete Profile";
        $data['userData'] = $uData;
        $this->load->login_render('share/company_profile', $data);
    }

    public function index() { 
        $data['title'] = "Login";
        $this->load->login_render('login', $data);
    }//End Function
    //testing my
    public function signup() {
        $data['title'] = "Sign up";
        $this->load->login_render('signup', $data);
    }//End Function
    
    public function forgot() {
        $data['title'] = "forgotpassword";
        $this->load->login_render('forgotpassword', $data);
    }//End Function
    
     public function myforgot() {
        $data['title'] = "myforgot";
        $this->load->login_render('admin/myforgot', $data);
    }//End Function

    public function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Sign out successfully done! ');
        $response = array('status' => 1);
        redirect(base_url('/'));
        echo json_encode($response);
        die;
    }//End Function


    public function admin_logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Sign out successfully done! ');
        $response = array('status' => 1);
        redirect(base_url('admin/login'));
        echo json_encode($response);
        die;
    }//End Function
    
    public function check_compnay_user_session()
    {
        if(isset($_SESSION['company_sess']))
        {
            return TRUE;
        }else{
            $this->logout();
        }
    }

    public function dashboard() {
        $this->check_profile();
        $this->check_compnay_user_session();
        $data['parent']     = "Dashboard";
        $data['title']      = '<i class="fa-fw fa fa-home"></i> Dashboard';
        $user_sess_data                 = $_SESSION['company_sess']; 
        $session_u_id                   = $user_sess_data['id']; //user ID
        $where                          = array('id'=>$session_u_id,'status'=>1);//status:0 means active 
        $uData                          = $this->db->get_where('company',array('company_id'=>$session_u_id))->result();
        $uData = $uData[0];
        $data['user']                   =  $uData;
        $this->load->view('backend_includes/company_header', $data);
        $this->load->view('dashboard', $data);
        $this->load->view('backend_includes/company_footer', $data);        
    
    
    }//End Function
    
    public function searchpeoplechat()
    {
		$searchvalue = strtoupper($_POST['value']);
		$projectId = $_POST['project_id'];

        $user_sess_data                 = $_SESSION['company_sess']; 
        $session_u_id = $user_sess_data['id']; //user ID
        $peopleList = $this->db->get_where('company_member_relations',array('company_id'=>$session_u_id))->result();
        $allpeopleList = array();
        foreach($peopleList as $people)
        {
            if($people->type == 'leadcontractor' || $people->type == 'subcontractor')
            {
                $userData = $this->db->get_where('contractor',array('id'=>$people->member_id))->result();
                if(isset($userData[0]))
                {
                    if($searchvalue != "")
                    {
                        $userData = $userData[0];
                        $mystring = strtoupper($userData->owner_first_name.' '.$userData->owner_last_name);
                        if(strpos($mystring, $searchvalue) !== false)
                        {
                            $userData->name = $userData->owner_first_name.' '.$userData->owner_last_name;
							$userData->reciever_type = $people->type;
							$userData->group_id = 0;
                            if($projectId != 0)
							{
								$memeber_check = $this->db->get_where('invite_people',array('role'=>$people->type,'user_id'=>$people->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
								if($memeber_check > 0)
								{
									array_push($allpeopleList,$userData);
								}
							}else{
								array_push($allpeopleList,$userData);
							} 
                        }
                    }else{
                        $userData = $userData[0];
                        $userData->name = $userData->owner_first_name.' '.$userData->owner_last_name;
						$userData->reciever_type = $people->type;
						$userData->group_id = 0;
                        if($projectId != 0)
						{
							$memeber_check = $this->db->get_where('invite_people',array('role'=>$people->type,'user_id'=>$people->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
							if($memeber_check > 0)
							{
								array_push($allpeopleList,$userData);
							}
						}else{
							array_push($allpeopleList,$userData);
						}   
                    }
                }
            }else if($people->type == 'crew')
            {
                $userData = $this->db->get_where('crew_member',array('id'=>$people->member_id))->result();
                if(isset($userData[0]))
                {
                    if($searchvalue != "")
                    {
                        $userData = $userData[0];
                        $mystring = strtoupper($userData->name);
                        if(strpos($mystring, $searchvalue) !== false)
                        {
							$userData->reciever_type = $people->type;
							$userData->group_id = 0;
                            if($projectId != 0)
							{
								$memeber_check = $this->db->get_where('invite_people',array('role'=>$people->type,'user_id'=>$people->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
								if($memeber_check > 0)
								{
									array_push($allpeopleList,$userData);
								}
							}else{
								array_push($allpeopleList,$userData);
							} 
                        }
                    }else{
                        $userData = $userData[0];
						$userData->reciever_type = $people->type;
						$userData->group_id = 0;
                        if($projectId != 0)
						{
							$memeber_check = $this->db->get_where('invite_people',array('role'=>$people->type,'user_id'=>$people->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
							if($memeber_check > 0)
							{
								array_push($allpeopleList,$userData);
							}
						}else{
							array_push($allpeopleList,$userData);
						}
                    }
                }
            }
            else if($people->type == 'client')
            {
                $userData = $this->db->get_where('client',array('id'=>$people->member_id))->result();
                if(isset($userData[0]))
                {
                    if($searchvalue != "")
                    {
                        $userData = $userData[0];
                        $mystring = strtoupper($userData->name);
                        if(strpos($mystring, $searchvalue) !== false)
                        {
							$userData->reciever_type = $people->type;
							$userData->group_id = 0;
                            if($projectId != 0)
							{
								$memeber_check = $this->db->get_where('invite_people',array('role'=>$people->type,'user_id'=>$people->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
								if($memeber_check > 0)
								{
									array_push($allpeopleList,$userData);
								}
							}else{
								array_push($allpeopleList,$userData);
							} 
                        }
                    }else{
                        $userData = $userData[0];
						$userData->reciever_type = $people->type;
						$userData->group_id = 0;
                        if($projectId != 0)
						{
							$memeber_check = $this->db->get_where('invite_people',array('role'=>$people->type,'user_id'=>$people->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
							if($memeber_check > 0)
							{
								array_push($allpeopleList,$userData);
							}
						}else{
							array_push($allpeopleList,$userData);
						} 
                    }
                }
            }
		}

		if($projectId != 0)
		{
			$groupList = $this->db->get_where('chat_group',array('created_by'=>$session_u_id,'project_id'=>$projectId))->result();
		}else{
			$groupList = $this->db->get_where('chat_group',array('created_by'=>$session_u_id))->result();
		}

		foreach($groupList as $group)
		{
			$object = new stdClass();
			$object->group_id = $group->id;
			$object->id = "";
			$object->company_name = '';
			$object->owner_first_name = '';
			$object->owner_last_name = "";
			$object->city = '';
			$object->state = '';
			$object->address = "";
			$object->phone_number = '';
			$object->email = '';
			$object->password = "";
			$object->pwd = '';
			$object->licence = '';
			$object->company_id = "";
			$object->is_role = '';
			$object->profile_photo = '';
			$object->insurence_certificate = "";
			$object->is_notify = '';
			$object->passToken = '';
			$object->created_at = $group->created_at;
			$object->updated_at = $group->updated_at;
			$object->owner_first_name = '';
			$object->name = $group->name;
			$object->reciever_type = '';
			$object->countnotification = 0;
			$groupName = strtoupper($group->name);
			if($searchvalue != "" )
			{
				if(strpos($groupName, $searchvalue) !== false)
				{
					array_push($allpeopleList,$object);
				}
			}else{
				array_push($allpeopleList,$object);
			}
		}
        
		$chatpeoplehtml = '<div  class="container"><mark class="container">Members</mark></div>';
		$labelCount = 0;
        
        foreach($allpeopleList as $key=>$people)
        {  
			if($people->group_id == 0)
			{
				$chat_dp = $people->profile_photo;
				if($people->reciever_type =="crew")
				{
					$img = 'https://ptetutorials.com/images/user-profile.png';
					if($chat_dp!= NULL)
					{
						$img = base_url('uploads/crew/').$chat_dp;
					} 
				}
				if($people->reciever_type =="client")
				{
					$img = 'https://ptetutorials.com/images/user-profile.png';
					if($chat_dp!= NULL)
					{
						$img = base_url('uploads/client/').$chat_dp;
					} 
				}
				if($people->reciever_type =="leadcontractor" || $people->reciever_type =="subcontractor")
				{
					$img = 'https://ptetutorials.com/images/user-profile.png';
					if($chat_dp != NULL)
					{
						$img = base_url('uploads/contractor/').$chat_dp;
					}  
				}
				$clickaction = "doActive("."'".$people->id."'".","."'".$people->reciever_type."'".")";
				$chatpeoplehtml .= '<div  class="chat_list" id="'.$key.'" onclick="'.$clickaction.'"><div class="chat_people"><div  class="chat_img"> <img style="height: 37px;width: 50px;border-radius: 50%;" src="'.$img.'" alt="'.$people->name.'"></div><div class="chat_ib"><h5 >'.$people->name.'<span class="chat_date">position :'.$people->reciever_type.'</span></h5></div></div></div>'; 
			} else{
				$labelCount++;
				if($labelCount == 1)
				{
					$chatpeoplehtml .= '<div  class="container"><mark>Groups</mark></div>';
				}
				$img = "https://www.tirpude.edu.in/wp-content/plugins/profilegrid-user-profiles-groups-and-communities/public/partials/images/default-group.png";
				$clickaction = "getGroupMessage("."'".$people->group_id."'".","."'".$people->reciever_type."'".")";
				$chatpeoplehtml .= '<div  class="chat_list" id="'.$key.'" onclick="'.$clickaction.'"><div class="chat_people"><div  class="chat_img"> <img style="height: 37px;width: 50px;border-radius: 50%;" src="'.$img.'" alt="'.$people->name.'"></div><div class="chat_ib"><h5 >'.$people->name.'<span class="chat_date">position :'.$people->reciever_type.'</span></h5></div></div></div>'; 
			}
        }
        
        
        echo json_encode($chatpeoplehtml);
    }
    
    public function chat() {
        $this->check_profile();
        $this->check_compnay_user_session();
        $data['parent']     = "Dashboard";
		$data['title']      = '<img style="max-width: 31px;"src="'.base_url().'/backend_assets/img/send-message.png" alt="Girl in a jacket"> Messages';

        $user_sess_data                 = $_SESSION['company_sess']; 
        $session_u_id                   = $user_sess_data['id']; //user ID
        $where                          = array('id'=>$session_u_id,'status'=>1);//status:0 means active 
        $uData                          = $this->db->get_where('company',array('company_id'=>$session_u_id))->result();
        $uData = $uData[0];
		$data['user']                   =  $uData;
		
		// $this->db->where('company_id',$session_u_id);
		// $this->db->order_by("id","desc");
		$data['project_list'] = $this->db->get_where('project',array('company_id'=>$session_u_id))->result();

        $peopleList = $this->db->get_where('company_member_relations',array('company_id'=>$session_u_id))->result();
        $allpeopleList = array();
        foreach($peopleList as $people)
        {
            if($people->type == 'leadcontractor' || $people->type == 'subcontractor')
            {
                $userData = $this->db->get_where('contractor',array('id'=>$people->member_id))->result();
                if(isset($userData[0]))
                {
                    $userData = $userData[0];
                    $userData->name = $userData->owner_first_name.' '.$userData->owner_last_name;
					$userData->reciever_type = $people->type;
					$userData->group_id = 0;
                    array_push($allpeopleList,$userData);
                }
            }else if($people->type == 'crew')
            {
                $userData = $this->db->get_where('crew_member',array('id'=>$people->member_id))->result();
                if(isset($userData[0]))
                {
                    $userData = $userData[0];
					$userData->reciever_type = $people->type;
					$userData->group_id = 0;
                    array_push($allpeopleList,$userData);
                }
            }
            else if($people->type == 'client')
            {
                $userData = $this->db->get_where('client',array('id'=>$people->member_id))->result();
                if(isset($userData[0]))
                {
                    $userData = $userData[0];
					$userData->reciever_type = $people->type;
					$userData->group_id = 0;
                    array_push($allpeopleList,$userData);
                }
            }
		}
		
		$groupList = $this->db->get_where('chat_group',array('created_by'=>$session_u_id))->result();
		foreach($groupList as $group)
		{
			$object = new stdClass();
			$object->group_id = $group->id;
			$object->id = "";
			$object->company_name = '';
			$object->owner_first_name = '';
			$object->owner_last_name = "";
			$object->city = '';
			$object->state = '';
			$object->address = "";
			$object->phone_number = '';
			$object->email = '';
			$object->password = "";
			$object->pwd = '';
			$object->licence = '';
			$object->company_id = "";
			$object->is_role = '';
			$object->profile_photo = '';
			$object->insurence_certificate = "";
			$object->is_notify = '';
			$object->passToken = '';
			$object->created_at = $group->created_at;
			$object->updated_at = $group->updated_at;
			$object->owner_first_name = '';
			$object->name = $group->name;
			$object->reciever_type = '';
			$object->countnotification = 0;
			array_push($allpeopleList,$object);  
		}
		$data['peopleList']                   =  $allpeopleList;
		
		$this->load->model('Group_model');
		$grouplist       = $this->db->get_where('chat_group',array('created_by'=>$_SESSION['company_sess']['id']))->result();
		$data['grouplist']                   =  $grouplist;

        $this->load->view('backend_includes/company_header', $data);
        $this->load->view('chat', $data);
        $this->load->view('backend_includes/company_footer', $data);        
    }//End Function

    //view admin profile
     public function profile(){
        $data['title']      = "Company profile";
        $where              = array('company_id'=>$_SESSION['company_sess']['id']);
        $result             = $this->common_model->getsingle('company',$where);
        $data['userData']   = $result;
        $user_sess_data                 = $_SESSION['company_sess']; 
        $session_u_id                   = $user_sess_data['id']; //user ID
        $where                          = array('id'=>$session_u_id,'status'=>1);//status:0 means active 
        $uData                          = $this->db->get_where('company',array('company_id'=>$session_u_id))->result();
        $uData = $uData[0];
		$data['user']                   =  $uData;
		
		$license_media = $this->db->get_where('license_media',array('type'=>'company','user_id'=>$_SESSION['company_sess']['id']))->result();
		foreach($license_media as $licenses)
		{
			$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
		}
	
		$data['license_media'] = $license_media;

        $this->load->view('backend_includes/company_header', $data);
        $this->load->view('company_profile', $data);
        $this->load->view('backend_includes/company_footer', $data);
    }
  
}//End Class
