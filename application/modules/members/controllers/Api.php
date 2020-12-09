<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends REST_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
        $this->load->model('User_model');
        // $check = $this->User_model->checkAuthToken();
        // if($check == false)
        // {
        //     $response       = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(101),'data'=>array());
        //     $this->response($response);
        // }
    }
    
    public function forgotpassword_post()
    {
        $email = $this->post('email');
        $role = $this->post('role');
        
        if($role == 'company' || $role == 'admin')
        {
            $table = 'company';
        }
        else if($role == 'crew')
        {
            $table = 'crew_member';
        }else if($role == 'leadcontractor' || $role == 'subcontractor')
        {
            $table = 'contractor';
        }else if($role = 'client')
        {
            $table = 'client';
        }
        $data = $this->db->get_where($table,array('email'=>$email))->result();
        if(isset($data[0]))
        {
            $data = $data[0];
            if($role == 'company' || $role == 'admin')
            {
                $full_name = $data->name;
                $id = $data->company_id;
            }
            else if($role == 'crew')
            {
                $full_name = $data->name;
                $id = $data->id;
            }else if($role == 'leadcontractor' || $role == 'subcontractor')
            {
                $full_name = $data->owner_first_name;
                $id = $data->id;
            }else if($role = 'client')
            {
                $full_name = $data->name;
                $id = $data->id;
            }
            $array = array(
                'sender_id' => 0,
                'reciever_type' => '',
                'receiver_id' => $id,
                'reciever_type' => $role,
                'is_for' => 'resetpassword'
            );
            $this->db->insert('invite',$array);
            $id = $this->db->insert_id();
            $link = base_url().'commonresetmemberpassword/'.encoding($id);
            $messageshow = 'Please Click On Below Icon ';
            $data1['isshow']  = true;
            $data1['reseturl']        = $link;
            $data1['is_for']  = 'account';
            $data1['message'] = $messageshow;
            $data1['full_name'] = $full_name;
            $message            = $this->load->view('invite/commonmemberresetpassword',$data1,TRUE);
            //$message            = 'Test';
            $subject = 'Reset Password';
            $to = $email;
            $this->common_model->sendMemberMail($to,$link,$message,$subject);
            $response       = array('status'=>SUCCESS,'message'=>'Reset Password Link Send Successfully.','data'=>array());
        }else{
            $response       = array('status'=>FAIL,'message'=>'No User Found With This Email','data'=>array());
        }
        $this->response($response);
    }
    
    public function getPeopleList_post()
    {
		if(isset($_POST['project_id']) && $_POST['project_id'] != 0 && $_POST['project_id'] != "" && $_POST['project_id'] != null && $_POST['project_id'] != "null" && $_POST['project_id'] != NULL && $_POST['project_id'] != "NULL" && $_POST['project_id'] != -1)
		{
			$projectId = $_POST['project_id'];
		}else{
			$projectId = 0;
		}

        $user_id = $this->post('user_id');
        $role = $this->post('role');
        $searchvalue = strtoupper($_POST['value']);
        $companyList = $this->db->get_where('company_member_relations',array('member_id'=>$user_id,'type'=>$role))->result();
        $peopleListArray = array();
        foreach($companyList as $key=>$value)
        {
            $personName = "";
            $person=new stdClass();
            $person->id = $value->company_id;
            $person->type = 'company';
			$companyData = $this->db->get_where('company',array('company_id'=>$value->company_id))->result();
			if(isset($companyData[0]))
			{
				$companyData = $companyData[0];
				$person->email = $companyData->email;
				$person->name = $companyData->name;
				$personName = $companyData->name;
				$profile_photo = 'https://ptetutorials.com/images/user-profile.png';
				if($companyData->profile_photo != NULL)
				{
					$profile_photo = base_url('uploads/company/').$companyData->profile_photo;
				}
				$person->profile_photo = $profile_photo;
				
				$array = array(
					'reciever_id' => $user_id,
					'user_id' => $value->company_id,
					'reciever_type' => $role,
					'user_type' => 'company',
					'is_seen' => 0
				);
				$count = $this->db->get_where('chat',$array)->num_rows();
				$person->countnotification = $count;
				$person->group_id = "0";
				
				if($searchvalue != "")
				{
					$mystring = strtoupper($personName);
					if(strpos($mystring, $searchvalue) !== false)
					{
						if($projectId != 0)
						{
							$memeber_check = $this->db->get_where('invite_people',array('role'=>$value->type,'user_id'=>$value->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
							if($memeber_check > 0)
							{
								array_push($peopleListArray,$person);
							}
						}else{
							array_push($peopleListArray,$person);
						} 
						//array_push($peopleListArray,$person);
					}
				}else{
					// array_push($peopleListArray,$person); 
					if($projectId != 0)
					{
						$memeber_check = $this->db->get_where('invite_people',array('role'=>$value->type,'user_id'=>$value->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
						if($memeber_check > 0)
						{
							array_push($peopleListArray,$person);
						}
					}else{
						array_push($peopleListArray,$person);
					}   
				}
			}
        }
        // leadcontractor and subcontractor
        if($role == 'leadcontractor' || $role == 'subcontractor')
        {
			$memberList = $this->db->get_where('contractor_member_relationship',array('contractor_id'=>$user_id,'contractor_type'=>$role))->result();
            foreach($memberList as $key=>$value)
            {
                $person=new stdClass();
                $person->id = $value->member_id;
                $person->type = $value->type;
				$companyData = $this->db->get_where('contractor',array('id'=>$value->contractor_id))->result();
				if(isset($companyData[0]))
				{
					$companyData = $companyData[0];
					$foldername = "";
					if($value->type == 'crew')
					{
						$foldername = 'crew';
						$table = 'crew_member';
					}else if($value->type == 'leadcontractor' || $value->type == 'subcontractor')
					{
						$foldername = 'contractor';
						$table = 'contractor';
					}else if($value->type = 'client')
					{
						$foldername = 'client';
						$table = 'client';
					}
					$this->db->select('*');
					$this->db->where('id',$value->member_id);
					$sql = $this->db->get($table)->result();
					$sql = $sql[0];
					$person->email = $sql->email;
					$personName = "";
					if($value->type == 'crew')
					{
						$person->name = $sql->name;
						$personName = $sql->name;
					}else if($value->type == 'leadcontractor' || $value->type == 'subcontractor')
					{
						$person->name = $sql->owner_first_name;
						$personName = $sql->owner_first_name;
					}else if($value->type = 'client')
					{
						$person->name = $sql->name;
						$personName = $sql->name;
					}
					$profile_photo = 'https://ptetutorials.com/images/user-profile.png';
					if($sql->profile_photo != NULL)
					{
						$profile_photo = base_url('uploads/').$foldername.'/'.$sql->profile_photo;
					}
					$person->profile_photo = $profile_photo;
					
					$array = array(
						'reciever_id' => $user_id,
						'user_id' => $value->member_id,
						'reciever_type' => $role,
						'user_type' => $value->type,
						'is_seen' => 0
					);
					$count = $this->db->get_where('chat',$array)->num_rows();
					$person->countnotification = $count;
					$person->group_id = "0";
					
					if($searchvalue != "")
					{
						$mystring = strtoupper($personName);
						if(strpos($mystring, $searchvalue) !== false)
						{
							// array_push($peopleListArray,$person);
							if($projectId != 0)
							{
								$memeber_check = $this->db->get_where('invite_people',array('role'=>$value->type,'user_id'=>$value->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
								if($memeber_check > 0)
								{
									array_push($peopleListArray,$person);
								}
							}else{
								array_push($peopleListArray,$person);
							}
						}
					}else
					{
						// array_push($peopleListArray,$person);
						if($projectId != 0)
						{
							$memeber_check = $this->db->get_where('invite_people',array('role'=>$value->type,'user_id'=>$value->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
							if($memeber_check > 0)
							{
								array_push($peopleListArray,$person);
							}
						}else{
							array_push($peopleListArray,$person);
						}    
					}
				}
            }
        }
        
        if($role == 'subcontractor' || $role == 'crew')
        {
            $memberList = $this->db->get_where('contractor_member_relationship',array('member_id'=>$user_id,'type'=>$role))->result();
            foreach($memberList as $key=>$value)
            {
                $person=new stdClass();
                $person->id = $value->contractor_id;
                $person->type = $value->contractor_type;
				$companyData = $this->db->get_where('contractor',array('id'=>$value->contractor_id))->result();
				if(isset($companyData[0]))
				{
					$companyData = $companyData[0];
					$foldername = "";
					if($value->contractor_type == 'crew')
					{
						$foldername = 'crew';
						$table = 'crew_member';
					}else if($value->contractor_type == 'leadcontractor' || $value->contractor_type == 'subcontractor')
					{
						$foldername = 'contractor';
						$table = 'contractor';
					}else if($value->contractor_type = 'client')
					{
						$foldername = 'client';
						$table = 'client';
					}
					$this->db->select('*');
					$this->db->where('id',$value->contractor_id);
					$sql = $this->db->get($table)->result();
					$sql = $sql[0];
					$person->email = $sql->email;
					$personName = "";
					if($value->contractor_type == 'crew')
					{
						$person->name = $sql->name;
						$personName = $sql->name;
					}else if($value->contractor_type == 'leadcontractor' || $value->contractor_type == 'subcontractor')
					{
						$person->name = $sql->owner_first_name;
						$personName = $sql->owner_first_name;
					}else if($value->contractor_type = 'client')
					{
						$person->name = $sql->name;
						$personName = $sql->name;
					}
					$profile_photo = 'https://ptetutorials.com/images/user-profile.png';
					if($sql->profile_photo != NULL)
					{
						$profile_photo = base_url('uploads/').$foldername.'/'.$sql->profile_photo;
					}
					$person->profile_photo = $profile_photo;
					
					$array = array(
						'reciever_id' => $user_id,
						'user_id' => $value->contractor_id,
						'reciever_type' => $role,
						'user_type' => $value->contractor_type,
						'is_seen' => 0
					);
					$count = $this->db->get_where('chat',$array)->num_rows();
					$person->countnotification = $count;
					$person->group_id = "0";
					
					if($searchvalue != "")
					{
						$mystring = strtoupper($personName);
						if(strpos($mystring, $searchvalue) !== false)
						{
							// array_push($peopleListArray,$person);
							if($projectId != 0)
							{
								$memeber_check = $this->db->get_where('invite_people',array('role'=>$value->type,'user_id'=>$value->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
								if($memeber_check > 0)
								{
									array_push($allpeopleList,$person);
								}
							}else{
								array_push($allpeopleList,$person);
							}
						}
					}else{
						// array_push($peopleListArray,$person);
						if($projectId != 0)
						{
							$memeber_check = $this->db->get_where('invite_people',array('role'=>$value->type,'user_id'=>$value->member_id,'project_id'=>$projectId,'is_removed'=>0))->num_rows();
							if($memeber_check > 0)
							{
								array_push($peopleListArray,$person);
							}
						}else{
							array_push($peopleListArray,$person);
						}   
					}
				}
            }
		}

		$role1 = $role;

		if($role == 'leadcontractor' || $role == 'subcontractor')
        {
			$role1 = 'contractor';
		}

		$this->db->select('a.*,b.*');
		$this->db->from('chat_group a');
		$this->db->join('chat_group_memeber b', 'b.group_id = a.id');
		if($projectId != 0)
		{
			$this->db->where('chat_group.project_id', $projectId);
		}
		$this->db->where('b.member_id', $user_id);
		$this->db->where('b.role', $role1);
		$groupList = $this->db->get()->result();
		foreach($groupList as $group)
		{
			$object = new stdClass();
			$object->group_id = $group->id;
			$object->id = "";
			$object->type = '';
			$object->name = $group->name;
			$object->profile_photo = '';
			$object->countnotification = 0;
			if($searchvalue != "")
			{
				if(strpos($group->name, $searchvalue) !== false)
				{
					array_push($peopleListArray,$object);
				}
			}else{
				array_push($peopleListArray,$object);
			}
		}
        $response       = array('status'=>SUCCESS,'message'=>'People List Get Successfully.','data'=>$peopleListArray);
        $this->response($response);
    }
    
    public function getProfile_post()
    {
        $authData = '';
        $header         = $this->input->request_headers();
        $header = array_change_key_case($header, CASE_LOWER);
        if(array_key_exists ('auth-token', $header )){
            $key = 'auth-token';
        }elseif(array_key_exists ( 'authtoken' , $header )){
            $key = 'authtoken';
        }elseif(array_key_exists ( 'authToken' , $header )){
            $key = 'authToken';
        }else{
            return FALSE; //authentication failed 
        }
        $authToken = $header[$key];
        $role = $header['role'];
        if($role == 'crew')
        {
            $role = 'crew_member';
        }else if($role == 'leadcontractor' || $role == 'subcontractor')
        {
            $role = 'contractor';
        }else if($role = 'client')
        {
            $role = 'client';
        }
        $user_id = $this->post('user_id');
        $this->db->select('*');
        $this->db->where('id',$user_id);
        $sql = $this->db->get($role)->result();
        $data = $sql[0];
        $object = new stdClass();
        $name = "";
        $email = "";
        $name = "";
		$profile_url = NULL;
		$license_media = array();
        if($role == "crew")
        {
            $name = $data->name;
			$profile_url = base_url('uploads/crew/');
			
			$license_media = $this->db->get_where('license_media',array('type'=>'crew','user_id'=>$user_id))->result();
			foreach($license_media as $licenses)
			{
				$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
			}

        }else if($role == 'leadcontractor' || $role == 'subcontractor')
        {
            $name = $data->owner_first_name;
			$profile_url = base_url('uploads/contractor/');
			
			$license_media = $this->db->get_where('license_media',array('type'=>'contractor','user_id'=>$user_id))->result();
			foreach($license_media as $licenses)
			{
				$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
			}

        }else if($role = 'client')
        {
            $name = $data->name;
            $profile_url = base_url('uploads/client/');
        }
        if($data->profile_photo != null)
        {
            $profile_photo = $profile_url.$data->profile_photo;
        }
        $email = $data->email;
        $object->name = $name;
        $object->email = $email;
		$object->profile_photo = $profile_photo;
		
		$object->multiple_license = $license_media;

        $response   = array('status'=>SUCCESS,'message'=>'Profile Get Succesfully.','data'=>$object);
        $this->response($response);
    }
    
    // Client detail api
    public function clientdetail_post()
    {
        $user_id = $this->post('user_id');
        $data = $this->db->get_where('client',array('id'=>$user_id))->result();
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'message'=>'Client Not Exist','data'=>$data);
        }else{
            $data = $data[0];   
            $data->encrypt_id = encoding($data->id);
            if($data->document != null)
            {
                $data->document = base_url('uploads/client/').$data->document;
            }
            if($data->profile_photo != null)
            {
                $data->profile_photo = base_url('uploads/client/').$data->profile_photo;
			}

            $response       = array('status'=>SUCCESS,'message'=>'Client Detail Get Succesfully','data'=>$data);
        }
        $this->response($response);
    }
    // crew detail
    public function crewdetail_post()
    {
        $user_id = $this->post('user_id');
        $data = $this->db->get_where('crew_member',array('id'=>$user_id))->result();
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'message'=>'Crew Not Exist','data'=>$data);
        }else{
            $data = $data[0];   
            $data->encrypt_id = encoding($data->id);
            if($data->licence != null)
            {
                $data->licence = base_url('uploads/crew/').$data->licence;
            }
            if($data->insurence_certificate != null)
            {
                $data->insurence_certificate = base_url('uploads/crew/').$data->insurence_certificate;
            }
            if($data->profile_photo != null)
            {
                $data->profile_photo = base_url('uploads/crew/').$data->profile_photo;
			}
			
			$license_media = $this->db->get_where('license_media',array('type'=>'crew','user_id'=>$data->id))->result();
			foreach($license_media as $licenses)
			{
				$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
			}
			$data->multiple_license = $license_media;

            $response       = array('status'=>SUCCESS,'message'=>'Crew Detail Get Succesfully','data'=>$data);
        }
        $this->response($response);
    }
    // contractor details
    public function contractordetail_post()
    {
        $user_id = $this->post('user_id');
        $data = $this->db->get_where('contractor',array('id'=>$user_id))->result();
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'message'=>'Clieent Not Exist','data'=>$data);
        }else{
            $data = $data[0];   
            $data->encrypt_id = encoding($data->id);
            
            $satateData = $this->db->get_where('states',array('id'=>$data->state))->result();
            $data->state_name = $satateData[0]->name;
            
            $satateData = $this->db->get_where('cities',array('id'=>$data->city))->result();
            $data->city_name = $satateData[0]->name;
            if($data->licence != null)
            {
                $data->licence = base_url('uploads/contractor/').$data->licence;
            }
            if($data->insurence_certificate != null)
            {
                $data->insurence_certificate = base_url('uploads/contractor/').$data->insurence_certificate;
            }
            if($data->profile_photo != null)
            {
                $data->profile_photo = base_url('uploads/contractor/').$data->profile_photo;
			}
			
			$license_media = $this->db->get_where('license_media',array('type'=>'contractor','user_id'=>$data->id))->result();
			foreach($license_media as $licenses)
			{
				$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
			}
			$data->multiple_license = $license_media;

            $response       = array('status'=>SUCCESS,'message'=>'Contractor Detail Get Succesfully','data'=>$data);
        }
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
    // client update profile
    public function clientupdate_post()
    {
       // die;
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_valid_email|valid_email');
        $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()),'data'=>array());
            $this->response($response);
        }else{
            
             if(!isset($_FILES['profile_photo']))
            {
                 $clientmemberprofile = $this->db->get_where('client',array('id'=>$this->post('user_id')))->result();
                 $clientmemberprofile = $clientmemberprofile[0];
                 if($clientmemberprofile->profile_photo == NULL || $clientmemberprofile->profile_photo == "" || $clientmemberprofile->profile_photo == "")
                 {
                    $response = array('status' => FAIL, 'message' => 'Please Select Profile Image.','data'=>array());
                    $this->response($response);
                    die;
                 }
            }
            // if(!isset($_FILES['profile_photo']))
            // {
            //     $response = array('status' => FAIL, 'message' => 'Please Select Profile Image.','data'=>array());
            //     $this->response($response);
            //     die;
            // }
            $user_id = $this->post('user_id');
            $name = $this->post('name');
            $email = $this->post('email');
            $address = $this->post('address');
            $phone_number = $this->post('phone_number');
            $document = $this->post('document');
            $data_val['name']           =   $name;
            $data_val['email']          =   $email;
            $data_val['address']          =   $address;
            $data_val['phone_number']          =   $phone_number;
            $document = "";
            if(isset($_FILES['document']))
            {
                $document = $_FILES['document']['name'];
            }
            $profile_photo = "";
            if(isset($_FILES['profile_photo']))
            {   
                
                $profile_photo = $_FILES['profile_photo']['name'];
            }


            $config['upload_path']= "./uploads/client/";
            $config['allowed_types']='*';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($profile_photo != "")
            {
                if($this->upload->do_upload('profile_photo'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $profile_photo = $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only  jpeg, jpg, png, gif format files.'.$this->upload->display_errors();
                    $response              = array('status'=>FAIL,'message'=>$error,'data'=>'');
                    $this->response($response);
                    die;
                }
                $data_val['profile_photo']                      = $profile_photo;
            }

            $config['upload_path']= "./uploads/client/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($document != "")
            {
                if($this->upload->do_upload('document'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $document= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error,'data'=>'');
                    $this->response($response);
                    die;
                }
                $data_val['document']                      = $document;
            }
            $this->db->where('id',$user_id);
            $result = $this->db->update('client',$data_val);
            $msg = 'client Data Updated Successfully';
            $clientData = $this->db->get_where('client',array('id'=>$user_id))->result();
            $clientData = $clientData[0];
            if($result){
                $person=new stdClass();
                $person->name = $name;
                $person->email = $email;
                $profile_photo = $clientData->profile_photo;
                $person->profile_photo = base_url('uploads/client/').$profile_photo;
                $response              = array('status'=>SUCCESS,'message'=>$msg,'data'=>$person);
            }else{
                $response              = array('status'=>FAIL,'message'=>'Something Went Wrong.','data'=>'');
            }        
            $this->response($response);
        }
    }
    // crew member update profile
    public function crewupdate_post()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_valid_email|valid_email');
        $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()),'data'=>array());
            $this->response($response);
        }else{
            
            if(!isset($_FILES['profile_photo']))
            {
                 $crew_memberprofile = $this->db->get_where('crew_member',array('id'=>$this->post('user_id')))->result();
                 $crew_memberprofile = $crew_memberprofile[0];
                 if($crew_memberprofile->profile_photo == NULL || $crew_memberprofile->profile_photo == "" || $crew_memberprofile->profile_photo == "")
                 {
                    $response = array('status' => FAIL, 'message' => 'Please Select Profile Image.','data'=>array());
                    $this->response($response);
                    die;
                 }
            }
            // if(!isset($_FILES['profile_photo']))
            // {
            //     $response = array('status' => FAIL, 'message' => 'Please Select Profile Image.','data'=>array());
            //     $this->response($response);
            //     die;
            // }
            $user_id = $this->post('user_id');
            $name = $this->post('name');
            $email = $this->post('email');
            $address = $this->post('address');
            $phone_number = $this->post('phone_number');
            $data_val['name']           =   $name;
            $data_val['email']          =   $email;
            $data_val['address']          =   $address;
            $data_val['phone_number']          =   $phone_number;
            $licence = "";
            if(isset($_FILES['licence']))
            {
                $licence = $_FILES['licence']['name'];
            }
            $insurence_certificate = "";
            if(isset($_FILES['insurence_certificate']))
            {
                $insurence_certificate = $_FILES['insurence_certificate']['name'];
            }
            $profile_photo = "";
            if(isset($_FILES['profile_photo']))
            {
                $profile_photo = $_FILES['profile_photo']['name'];
            }


            $config['upload_path']= "./uploads/crew/";
            $config['allowed_types']='*';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($profile_photo != "")
            {
                if($this->upload->do_upload('profile_photo'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $profile_photo = $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only  jpeg, jpg, png, gif format files.';
                    $response              = array('status'=>FAIL,'message'=>$error,'data'=>'');
                    $this->response($response);
                    die;
                }
                $data_val['profile_photo']                      = $profile_photo;
            }


            $config['upload_path']= "./uploads/crew/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($licence != "")
            {
                if($this->upload->do_upload('licence'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $licence= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error,'data'=>'');
                    $this->response($response);
                    die;
                }
                $data_val['licence']                      = $licence;
            }
            $config['upload_path']= "./uploads/crew/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($insurence_certificate != "")
            {
                if($this->upload->do_upload('insurence_certificate'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $insurence_certificate= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error,'data'=>'');
                    $this->response($response);
                    die;
                }
                $data_val['insurence_certificate'] = $insurence_certificate;
            }
            
            $this->db->where('id',$user_id);
            $result = $this->db->update('crew_member',$data_val);
			$msg = 'Crew Data Updated Successfully';
			
			// multiple document upload
			if(isset($_POST['all_docs']))
			{
				$all_docs = json_decode($_POST['all_docs']);
							
				foreach($all_docs as $all_doc)
				{
					$array = array(
						'type' => 'crew',
						'user_id' => $user_id,
						'file_name' => $all_doc
					);
					$this->db->insert('license_media',$array);
				}
			}
			if(isset($_POST['all_docs1']))
			{
				$all_docs = explode(" ",$_POST['all_docs1']);
				foreach($all_docs as $all_doc)
				{
					$array = array(
						'type' => 'crew',
						'user_id' => $user_id,
						'file_name' => $all_doc
					);
					$this->db->insert('license_media',$array);
				}
			}

            $crewData = $this->db->get_where('crew_member',array('id'=>$user_id))->result();
            $crewData = $crewData[0];
            if($result){
                $person=new stdClass();
                $person->name = $name;
                $person->email = $email;
                $profile_photo = $crewData->profile_photo;
                $person->profile_photo = base_url('uploads/crew/').$profile_photo;
                $response              = array('status'=>SUCCESS,'message'=>$msg,'data'=>$person);
                //$response              = array('status'=>SUCCESS,'message'=>$msg,'data'=>'');
            }else{
                $response              = array('status'=>FAIL,'message'=>'Something Went Wrong.','data'=>'');
            }        
            $this->response($response);
        }
    }
    
    // contractor update profile
    public function contractorupdate_post()
    {
        //die;
        $this->form_validation->set_rules('owner_first_name', 'Contractor Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        $this->form_validation->set_rules('owner_last_name', 'OwnerLastName', 'trim|min_length[2]|regex_match[/^([a-z0-9 ])+$/i]');
        $this->form_validation->set_rules('company_name', 'company_name', 'trim|required|min_length[2]|regex_match[/^([a-z0-9 ])+$/i]');

        //$this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[10]|max_length[20]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_valid_email|valid_email');
        $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()),'data'=>array());
            $this->response($response);
        }else{
             if(!isset($_FILES['profile_photo']))
            {
                 $contractorprofile = $this->db->get_where('contractor',array('id'=>$this->post('user_id')))->result();
                 $contractorprofile = $contractorprofile[0];
                 if($contractorprofile->profile_photo == NULL || $contractorprofile->profile_photo == "" || $contractorprofile->profile_photo == "")
                 {
                    $response = array('status' => FAIL, 'message' => 'Please Select Profile Image.','data'=>array());
                    $this->response($response);
                    die;
                 }
            }
             
            // if(!isset($_FILES['profile_photo']))
            // {
            //     $response = array('status' => FAIL, 'message' => 'Please Select Profile Image.','data'=>array());
            //     $this->response($response);
            //     die;
            // }
            $user_id = $this->post('user_id');
            $company_name = $this->post('company_name');
            $owner_first_name = $this->post('owner_first_name');
            $owner_last_name = $this->post('owner_last_name');
            $city = $this->post('city');
            $state = $this->post('state');
            $email = $this->post('email');
            $address = $this->post('address');
            $phone_number = $this->post('phone_number');
            $data_val['company_name']           =   $company_name;
            $data_val['owner_first_name']           =   $owner_first_name;
            $data_val['owner_last_name']           =   $owner_last_name;
            $data_val['city']           =   $city;
            $data_val['state']           =   $state;
            $data_val['email']          =   $email;
            $data_val['address']          =   $address;
            $data_val['phone_number']          =   $phone_number;
            
            $licence = "";
            if(isset($_FILES['licence']))
            {
                $licence = $_FILES['licence']['name'];
            }
            $insurence_certificate = "";
            if(isset($_FILES['insurence_certificate']))
            {
                $insurence_certificate = $_FILES['insurence_certificate']['name'];
            }
            $profile_photo = "";
            if(isset($_FILES['profile_photo']))
            {
                $profile_photo = $_FILES['profile_photo']['name'];
            }


            $config['upload_path']= "./uploads/contractor/";
            $config['allowed_types']='*';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($profile_photo != "")
            {
                
                if($this->upload->do_upload('profile_photo'))
                {   
                    $data = array('upload_data' => $this->upload->data());
                    $profile_photo = $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only  jpeg, jpg, png, gif format files.';
                    $response              = array('status'=>FAIL,'message'=>$error,'data'=>'');
                    $this->response($response);
                    die;
                }
                $data_val['profile_photo']                      = $profile_photo;
            }



            $config['upload_path']= "./uploads/contractor/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($licence != "")
            {
                
                if($this->upload->do_upload('licence'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $licence= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error,'data'=>'');
                    $this->response($response);
                    die;
                }
                $data_val['licence']                      = $licence;
            }
            $config['upload_path']= "./uploads/contractor/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($insurence_certificate != "")
            {
                if($this->upload->do_upload('insurence_certificate'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $insurence_certificate= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error,'data'=>'');
                    $this->response($response);
                    die;
                }
                $data_val['insurence_certificate'] = $insurence_certificate;
            }
            $this->db->where('id',$user_id);
            $result = $this->db->update('contractor',$data_val);
			$msg = 'contractor Data Updated Successfully';
			

			// multiple document upload
			if(isset($_POST['all_docs']))
			{
				$all_docs = json_decode($_POST['all_docs']);
							
				foreach($all_docs as $all_doc)
				{
					$array = array(
						'type' => 'contractor',
						'user_id' => $user_id,
						'file_name' => $all_doc
					);
					$this->db->insert('license_media',$array);
				}
			}
			if(isset($_POST['all_docs1']))
			{
				$all_docs = explode(" ",$_POST['all_docs1']);
				foreach($all_docs as $all_doc)
				{
					$array = array(
						'type' => 'contractor',
						'user_id' => $user_id,
						'file_name' => $all_doc
					);
					$this->db->insert('license_media',$array);
				}
			}

            $contractorData = $this->db->get_where('contractor',array('id'=>$user_id))->result();
            $contractorData = $contractorData[0];
            if($result){
                $person=new stdClass();
                $person->name = $owner_first_name;
                $person->email = $email;
                $profile_photo = $contractorData->profile_photo;
                $person->profile_photo = base_url('uploads/contractor/').$profile_photo;
                $response              = array('status'=>SUCCESS,'message'=>$msg,'data'=>$person);
                //$response              = array('status'=>SUCCESS,'message'=>$msg,'data'=>'');
            }else{
                $response              = array('status'=>FAIL,'message'=>'Something Went Wrong.','data'=>'');
            }        
            $this->response($response);
        }
    }
    
    public function getallproject_post()
    {
        $user_id = $this->post('user_id');
        $role = $this->post('role');
        $sql = $this->db->select('project.*, invite_people.is_removed')
         ->from('project')
         ->join('invite_people', 'project.id = invite_people.project_id')
         ->where('invite_people.user_id',$user_id)
         ->where('invite_people.role',$role);
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $name = $_POST['name'];
            $sql = $sql->like('name', $name);
        }
        if(isset($_POST['status']) && $_POST['status'] != -1 && $_POST['status'] != "")
        {
            $status = $_POST['status'];
            $sql = $sql->like('status', $status);
        }
        $this->db->distinct();
        $data = $this->db->get()->result();
        $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Project List Get Succesfully');
        $this->response($response);
    }
    
    public function projectdetail_post()
    {
        $project_id = $this->post('project_id');
        $data = $this->db->get_where('project',array('id'=>$project_id))->result();
        if(!isset($data[0]))
        {     
            $response       = array('status'=>FAIL,'data'=>$data,'message'=>'Project Not Exist');
        }else{
            $data = $data[0];   
            $client_id = $data->client;
            $clientData = $this->db->get_where('client',array('id'=>$client_id))->result();
            if(isset($clientData[0]))
                $data->client_data = $clientData[0];
            else
            {
                $client_data = new stdClass();
                $client_data->id = "";
                $client_data->name = "";
                $client_data->email = "";
                $client_data->profile_photo = "";
                $client_data->password = "";
                $client_data->address = "";
                $client_data->phone_number = "";
                $client_data->document = "";
                $client_data->company_id = "";
                $client_data->created_at = "";
                $client_data->updated_at = "";
                $client_data->passToken = "";
                $client_data->authToken = "";
                $data->client_data = $client_data;
            }
                
            $data->encrypt_id = encoding($data->id);
            if($data->document != null)
            {
                $data->document = base_url('uploads/project/').$data->document;
            }
            $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Project Detail Get Succesfully');
        }
        $this->response($response);
    }
    
    public function projecttasklist_post()
    {
        $projectid = $_POST['project_id'];
        
        $this->db->where('project_id',$projectid);
        $this->db->where('created_by',0);
        $this->db->order_by('taskId','desc');
        
        if(isset($_POST['status']) && $_POST['status'] != -1 && $_POST['status'] != "")
        {
            $this->db->where('task_status', $_POST['status']);
        }
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $this->db->like('name', $_POST['name']);
        }
        $task_list = $this->db->get('tasks')->result();
        foreach($task_list as $value)
        {
            $value->encrypt_id = encoding($value->taskId);
            $taskId = $value->taskId;
            $task_meta = $this->common_model->getAll('task_meta',array('taskId'=>$taskId),'sorting_order','desc');  
            $value->meta_data = $task_meta;
        }
        echo json_encode(array('success'=>true,'task_list'=>$task_list));
    }
    
    public function projecttasklistdetail_post()
    {
        $id = $this->post('task_id');      
        $data = $this->db->get_where('tasks',array('taskId'=>$id))->result();
        foreach ($data as $key => $value) {
            $this->db->order_by('sorting_order','asc');
            $meta_data = $this->db->get_where('task_meta',array('taskId'=>$id))->result();
            $value->id = encoding($value->taskId);
            foreach ($meta_data as $key2 => $value2) {
                $value2->newId = encoding($value2->taskmetaId);
                if($value2->file != "")
                {
                    if($value2->fileType=='IMAGE')
                    {
                        $value2->file = base_url('uploads/task_image/').$value2->file;
                    }else if($value2->fileType=='VIDEO'){
                        $value2->file = base_url('uploads/task_video/').$value2->file;    
                    }
                }
            }
            $value->meta_data = $meta_data;
            
            
            
            //
            $involved_people = array();
            $noninvolved_people = array();
            $invite_people_data = $this->db->get_where('invite_people',array('taskId'=>$id))->result();
            foreach($invite_people_data as $value2)
            {
                if($value2->role == 'leadcontractor' || $value2->role == 'subcontractor')
                {
                    $user_data = $this->db->get_where('contractor',array('id'=>$value2->user_id))->result();
                    $value2->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->owner_last_name;
                    $value2->user_email = $user_data[0]->email;
                }else if($value2->role == 'crew'){
                    $user_data = $this->db->get_where('crew_member',array('id'=>$value2->user_id))->result();
                    $value2->user_name = $user_data[0]->name;
                    $value2->user_email = $user_data[0]->email;
                }
                if($value2->is_removed == 0)
                {
                    array_push($involved_people,$value2);
                }else{
                    array_push($noninvolved_people,$value2);
                }
            }
            $value->involved_people = $involved_people;
            $value->noninvolved_people = $noninvolved_people;
            //
        }
        $task_detail              = $data[0];
        $response = array('success'=>true,'message'=>'Task Details retrived successfully.','data'=>$task_detail);
        echo json_encode($response);
    }
    
    // change password
    public function changepassword_post()
    {
        $user_id = $this->post('user_id');
        $old_pass = $this->post('old_pass');
        $new_pass = $this->post('new_pass');
        $confirm_pass = $this->post('confirm_pass');
        $role = $this->post('role');
        if($role == 'crew')
        {
            $role = 'crew_member';
        }else if($role == 'leadcontractor' || $role == 'subcontractor')
        {
            $role = 'contractor';
        }else if($role == 'client')
        {
            $role = 'client';
        } 

        $where          = array('id' => $user_id);
        $user_data          = $this->common_model->getsingle($role, $where,'password');
        if($new_pass != $confirm_pass)
        {
            $response = array('status' =>FAIL, 'message' => 'Failed! New And Confirm Pasword must be same.','data'=>array());
        }else{
            if(password_verify($old_pass, $user_data['password'])){
                $set = array(
                    'password'=> password_hash($this->input->post('new_pass'),PASSWORD_DEFAULT),
                    'pwd'=> $this->input->post('new_pass')
                    );
                $update = $this->common_model->updateFields($role, $set, $where);
                if($update){
                    $res = array();
                    if($update){
                        $response = array('status' =>SUCCESS, 'message' => 'Successfully Updated','data'=>array());
                    }else{
                        $response = array('status' =>FAIL, 'message' => 'Failed! Please try again','data'=>array());
                    }
                }
            }else{
                $response = array('status' =>FAIL, 'message' => 'Your Current Password is Wrong !','data'=>array());
            }
        }
        $this->response($response);
    }
    
    // Add Task Stesp
    public function addTaskStep_post(){
        $this->form_validation->set_rules('id', 'id', 'trim|required');
        $this->form_validation->set_rules('taskstepId', 'task step', 'trim|required');
     
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));    
        }else{
            $taskId                  = decoding($this->post('id'));
            $where                      = array('taskId'=>$taskId);
            $isExist                    = $this->common_model->is_data_exists('tasks',$where);

            if($isExist){
            $taskstep = $this->post('taskstepId');
            switch ($taskstep) {
                case 'image':
                      $this->load->model('Image_model');
                    $imagefileId              = $this->post('imagefileId_1');
                    if (!empty($_FILES['fileImage_1']['name'])) {
                    $imageF = $this->Image_model->updateDocument('fileImage_1','task_image');
                 
                    if(array_key_exists("image_name",$imageF)):
                        
                        $file             =  $imageF['image_name'];

                        $data_val['fileType']              = 'IMAGE';
                        $data_val['file']              = $file;

                        $isImage                          = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$imagefileId));
                        if($isImage){
                            $this->common_model->updateFields('task_meta',$data_val,array('taskmetaId'=>$imagefileId));
                            $status = SUCCESS;
                            $msg    = "Layer record updated successfully.";
                        }else{
                            $data_val['taskId']     = $taskId;
                            $this->common_model->insertData('task_meta',$data_val);
                            $status = SUCCESS;
                            $msg    = "Layer record added successfully.";
                        }

                    endif;

                    } 
                    break;
                case 'video':
                      $this->load->model('Image_model');
                    $videofileId              = $this->post('videofileId_1');
                    if (!empty($_FILES['videofile_1']['name'])) {
                    $videoF=$this->Image_model->updateDocument('videofile_1','task_video');
                    //check for image name if present
                        if(array_key_exists("image_name",$videoF)):
                        $file            =  $videoF['image_name'];
                        $data_val['fileType']              = 'VIDEO';
                        $data_val['file']              = $file;

                        $isVideo                          = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$videofileId));
                        if($isVideo){
                            $this->common_model->updateFields('task_meta',$data_val,array('taskmetaId'=>$videofileId));
                            $status = SUCCESS;
                            $msg    = "Layer record updated successfully.";
                        }else{
                            $data_val['taskId']     = $taskId;
                            $this->common_model->insertData('task_meta',$data_val);
                            $status = SUCCESS;
                            $msg    = "Layer record added successfully.";
                        }

                        endif;
                    }
                    break;
                
                default:
                    $textfile                = $this->post('textfile_1');
                    $textfileId              = $this->post('textfileId_1');
                    $data_val['description']       = $textfile;
                    $data_val['fileType']              = 'TEXT';
                    
                    $isText                          = $this->common_model->is_data_exists('task_meta',array('taskmetaId'=>$textfileId));
                    if($isText){
                        $this->common_model->updateFields('task_meta',$data_val,array('taskmetaId'=>$textfileId));
                        $status = SUCCESS;
                        $msg    = "Layer record updated successfully.";
                    }else{
                        $data_val['taskId']     = $taskId;
                        $this->common_model->insertData('task_meta',$data_val);
                         $status = SUCCESS;
                        $msg    = "Layer record added successfully.";
                    }
                    break;
            }
             $response              = array('status'=>$status,'message'=>$msg);

            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
        }
        $this->response($response);
    }//end function 
    
    // Create New Task
    public function addnew_post(){
        
        $data_val['name']         = $this->post('name');
        $data_val['description']        = $this->post('description');
        $data_val['project_id'] = $this->post('project_id');
        $data_val['company_id'] = $this->post('company_id');
        $data_val['created_by'] = 0;
        $result   = $this->common_model->insertData('tasks',$data_val);
        $taskId   = $result;
        $company_id = $this->post('company_id');
        if(isset($_POST['preopulated_ids']) && $_POST['preopulated_ids'] != 0)
        {
            $importedid = $this->post('preopulated_ids');
            $ALlData = $this->db->get_where('task_meta',array('taskId'=>$importedid))->result();
            foreach($ALlData as $ALL){
                $InsertArray = array(
                    'taskId' => $taskId,
                    'fileType' => $ALL->fileType,
                    'file' => $ALL->file,
                    'description' => $ALL->description,
                    'sorting_order' => $ALL->sorting_order
                );
                $this->db->insert('task_meta',$InsertArray);
            }
                
        }else{
            $textData = $this->input->post('textfile_1');
            $textData = json_decode($textData);
            foreach($textData as $key=>$value)
            {
              $array = array(
                  'fileType' => 'TEXT',
                  'description' => $value,
                  'taskId' => $taskId
              );
              $result = $this->common_model->insertData('task_meta',$array);
            }
                $textData = $this->input->post('inserted_steps');
            $textData = json_decode($textData);
            foreach($textData as $key=>$value)
            {
                $this->db->where('taskmetaId',$value);
                    $result = $this->db->update('task_meta',array('taskId'=>$taskId));
            }
        }
        if(isset($_POST['contractorIds']))
        {
            $contractorIds = json_decode($_POST['contractorIds']);
            foreach($contractorIds as $contractor)
            {
                $type = 'subcontractor';
                $contractorData = $this->db->get_where('contractor',array('id'=>$contractor))->result();
                if(isset($contractorData[0]))
                {
                    $contractorData = $contractorData[0];
                    $invitenewpeoplename = $contractorData->owner_first_name;
                    $invitenewpeopleemail = $contractorData->email;
                    $last_id = $contractorData->id;
                    $this->inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for='task',$taskId,$type,$company_id,$last_id);    
                }
            }
        }
        if(isset($_POST['crewIds']))
        {
            $crewIds = json_decode($_POST['crewIds']);
            foreach($crewIds as $crew)
            {
                $type = 'crew';
                $crewData = $this->db->get_where('crew_member',array('id'=>$crew))->result();
                if(isset($crewData[0])){
                    $crewData = $crewData[0];
                    $invitenewpeoplename = $crewData->name;
                    $invitenewpeopleemail = $crewData->email;
                    $last_id = $crewData->id;
                    $this->inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for='task',$taskId,$type,$company_id,$last_id);   
                }
            }
        }
        $msg = 'Task Added Successfully';
        if($result){
            $response = array('status'=>SUCCESS,'data'=>$_POST,'message'=>$msg,'url'=>base_url().'task-detail/'.encoding($taskId));
        }else{
            $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
        $this->response($response);
    }
    
    function ChangeTaskStatus_post()
    {
        $taskId         = $_POST['taskId'];
        $this->db->where('taskId',$taskId);
        $tasks_data = $this->db->get('tasks')->result();
        $tasks_data = $tasks_data[0];
        if($tasks_data->task_status == 0)
        {
            $task_status = "1";
        }else{
            $task_status = "0";
        }
        $set        = array('task_status'=> $task_status); 
        $where          = array('taskId' => $taskId);
        $update     = $this->common_model->updateFields('tasks', $set, $where);
        
        $data = array('status'=>'success','message'=>'Task Status Changes Succesfully','data'=>"");
        echo json_encode($data);
    }
    
    // add Crew Member
    function addcrewmember_post(){
        $this->load->model('Crew_model');
        
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_valid_email|valid_email');
        $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()),'data'=>array());
            $this->response($response);
        }else{
            $email                          =  $this->post('email');
            $name                       =  $this->post('name');
            $userData['name']           =   $name;
            $userData['email']              =   $email;
            $userData['phone_number']      =   $this->post('phone_number');
            $userData['address']      =   $this->post('address');
            $userData['company_id']      =   0;
            $role                     =  $this->post('role');
            // profile pic upload
            $config['upload_path']= "./uploads/crew/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            $licence = "";
            $insurence_certificate = "";
            if(isset($_FILES['licence']))
            {
                $licence = $_FILES['licence']['name'];
            }
            if(isset($_FILES['insurence_certificate']))
            {
                $insurence_certificate = $_FILES['insurence_certificate']['name'];   
            }
            if($licence != "")
            {
                if($this->upload->do_upload('licence'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $licence= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
            }
            if($insurence_certificate != "")
            {
                if($this->upload->do_upload('insurence_certificate'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $insurence_certificate= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
            }
            $userData['licence']               =   $licence;
            $userData['insurence_certificate'] =   $insurence_certificate;
            $result = $this->Crew_model->registration($userData);
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                        $response = array('status'=>SUCCESS,'message'=>'Crew Member Added Successfully', 'data'=>array());
                        // send crew invitaion
                        $array = array(
                            'sender_id' => $this->post('user_id'),
                            'sender_type' => $role,
                            'receiver_id' => $this->db->insert_id(),
                            'reciever_type' => 'crew',
                            'is_for' => 'account'
                        );
                        $this->db->insert('invite',$array);
                        $id = $this->db->insert_id();
                        $link = base_url().'invitation/'.encoding($id);
                        $data1['full_name']  = $result['returnData'][0]->name;
                        $data1['url']        = $link;
                        $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                        $subject = 'Bild It - Account Invitation link';
                        $to = $result['returnData'][0]->email;
						$this->common_model->sendMemberMail($to,$link,$message,$subject);
						



						// multiple document upload
						if(isset($_POST['all_docs']))
						{
							$all_docs = json_decode($_POST['all_docs']);
										
							foreach($all_docs as $all_doc)
							{
								$array = array(
									'type' => 'crew',
									'user_id' => $result['returnData'][0]->id,
									'file_name' => $all_doc
								);
								$this->db->insert('license_media',$array);
							}
						}
                        // end
                    break;
                    case "AE": // User already registered
                        $num_rows = $this->db->get_where('contractor_member_relationship',array('contractor_id'=>$this->post('user_id'),'member_id'=>$result['existId'],'type'=>'crew'))->num_rows();
                        if($num_rows>0){
                            $response = array('status'=>FAIL,'message'=>'Crew Member Already Registered','users'=>array());
                        }else{
                            $response = array('status'=>SUCCESS,'message'=>'Crew Member Invited Successfully','data'=>array());
                            // send crew invitaion
                            $reciever_data = $this->db->get_where('crew_member',array('id'=>$result['existId']))->result();
                            $reciever_data = $reciever_data[0];
                            $full_name= $reciever_data->name;
                            $array = array(
                                'sender_id' => $this->post('user_id'),
                                'sender_type' => $role,
                                'receiver_id' => $result['existId'],
                                'reciever_type' => 'crew',
                                'is_for' => 'account'
                            );
                            $this->db->insert('invite',$array);
                            $id = $this->db->insert_id();
                            $link = base_url().'invitation/'.encoding($id);
                            $data1['full_name']  = $full_name;
                            $data1['url']        = $link;
                            $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                            $subject = 'Bild It - Account Invitation link';
                            $to = $email;
                            $this->common_model->sendMemberMail($to,$link,$message,$subject);
                            // end
                        }
                    break;
                    default:
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'data'=>array());
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'data'=>array());
            }   
            $this->response($response);
        }
    }
    
    // add Contractor 
    function addsubcontractor_post(){
        $this->load->model('Contractor_model');
        
        $this->form_validation->set_rules('owner_first_name', 'Contractor Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        $this->form_validation->set_rules('company_name', 'company_name', 'trim|required|min_length[2]|regex_match[/^([a-z0-9 ])+$/i]');
        $this->form_validation->set_rules('owner_last_name', 'OwnerLastName', 'trim|min_length[2]|regex_match[/^([a-z ])+$/i]');
        $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_valid_email|valid_email');
        
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()),'data'=>array());
            $this->response($response);
        }else{
            $email                          =  $this->post('email');
            $company_name                       =  $this->post('company_name');
            $userData['company_name']           =   $company_name;
            $userData['email']              =   $email;
            $userData['phone_number']      =   $this->post('phone_number');
            $userData['address']      =   $this->post('address');
            $userData['company_id']      =   0;
            $userData['owner_first_name']      =   $this->post('owner_first_name');
            $userData['owner_last_name']      =   $this->post('owner_last_name');
            $userData['is_role']      =   1;
            $userData['state']      =   $this->post('state');
            $userData['city']      =   $this->post('city');
            
            $role = $this->post('role');
            
            // profile pic upload
            $config['upload_path']= "./uploads/contractor/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);

            if($this->upload->do_upload('licence'))
            {
                $data = array('upload_data' => $this->upload->data());
                $licence= $data['upload_data']['file_name'];
            }
            if($this->upload->do_upload('insurence_certificate'))
            {
                $data = array('upload_data' => $this->upload->data());
                $insurence_certificate= $data['upload_data']['file_name'];
            }

            $userData['licence']               =   $licence;
            $userData['insurence_certificate'] =   $insurence_certificate;

            $result = $this->Contractor_model->registration($userData);
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                        $response = array('status'=>SUCCESS,'message'=>'Contractor Invitation Send Successfully', 'data'=>array());
                        // send contractor invitaion
                        $array = array(
                            'sender_id' => $this->post('user_id'),
                            'sender_type' => $role,
                            'receiver_id' => $this->db->insert_id(),
                            'reciever_type' => 'subcontractor',
                            'is_for' => 'account'
                        );
                        $this->db->insert('invite',$array);
                        $id = $this->db->insert_id();
                        $link = base_url().'invitation/'.encoding($id);
                        $data1['full_name']  = $result['returnData'][0]->owner_first_name;
                        $data1['url']        = $link;
                        $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                        $subject = 'Bild It - Account Invitation link';
                        $to = $result['returnData'][0]->email;
						$this->common_model->sendMemberMail($to,$link,$message,$subject);
						
						// multiple document upload
						if(isset($_POST['all_docs']))
						{
							$all_docs = json_decode($_POST['all_docs']);
										
							foreach($all_docs as $all_doc)
							{
								$array = array(
									'type' => 'crew',
									'user_id' => $result['returnData'][0]->id,
									'file_name' => $all_doc
								);
								$this->db->insert('license_media',$array);
							}
						}
                        // end
                    break;
                    case "AE": // User already registered
                        $num_rows = $this->db->get_where('company_member_relations',array('company_id'=>$this->post('company_id'),'member_id'=>$result['existId'],'type'=>'leadcontractor'))->num_rows();
                        if($num_rows>0){
                            $response = array('status'=>FAIL,'message'=>'Contractor Already Registered With Your Company','data'=>array());
                        }else{
                            // send contractor invitaion
                            $reciever_data = $this->db->get_where('contractor',array('id'=>$result['existId']))->result();
                            $reciever_data = $reciever_data[0];
                            $full_name= $reciever_data->owner_first_name;
                            $array = array(
                                'sender_id' => $this->post('user_id'),
                                'sender_type' => $role,
                                'receiver_id' => $result['existId'],
                                'reciever_type' => 'subcontractor',
                                'is_for' => 'account'
                            );
                            $this->db->insert('invite',$array);
                            $id = $this->db->insert_id();
                            $link = base_url().'invitation/'.encoding($id);
                            $data1['full_name']  = $full_name;
                            $data1['url']        = $link;
                            $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                            $subject = 'Bild It - Account Invitation link';
                            $to = $email;
                            $this->common_model->sendMemberMail($to,$link,$message,$subject);
                            $response = array('status'=>SUCCESS,'message'=>'Contractor Invitation Send Successfully', 'data'=>array());
                            //end
                        }
                    break;
                    default:
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'data'=>array());
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'data'=>array());
            }   
            $this->response($response);
        }
    }
    
    // delete crew 
    function deletecrew_post()
    {
        $id = $this->input->post('id');
        $where = array('type'=>'crew','member_id'=>$id);
        $dataExist      = $this->common_model->is_data_exists('contractor_member_relationship',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('contractor_member_relationship',$where);
            $response   = array('status'=>SUCCESS,'message'=>'Crew Deleted Successfully','data'=>array());
        }else{
            $response  = array('status'=>FAIL,'message'=>'Crew Not Exist','data'=>array());  
        }
        $this->response($response);
    }
    
    // delete contractor
    function deletecontractor_post()
    {
        $id = $this->input->post('id');
        $where = array('type'=>'subcontractor','member_id'=>$id);
        $dataExist      = $this->common_model->is_data_exists('contractor_member_relationship',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('contractor_member_relationship',$where);
            $response   = array('status'=>SUCCESS,'message'=>'Contractor Deleted Successfully','data'=>array());
        }else{
            $response  = array('status'=>FAIL,'message'=>'Contractor Not Exist','data'=>array());  
        }
        $this->response($response);
    }
    
    // invite For Project
    function inviteExistingPeopleForProject_post()
    {
        $invitenewpeoplename  = "";
        $invitenewpeopleemail  = "";
        $reciever_type = $_POST['reciever_type'];
        $receiver_id = $_POST['receiver_id'];
        
        if($reciever_type == "leadcontractor" || $reciever_type == "subcontractor")
        {
            $reciever_data = $this->db->get_where('contractor',array('id'=>$receiver_id))->result();
            $reciever_data = $reciever_data[0];
            $invitenewpeoplename= $reciever_data->owner_first_name;
            $invitenewpeopleemail= $reciever_data->email;
        }else if($reciever_type == "crew")
        {
            $reciever_data = $this->db->get_where('crew_member',array('id'=>$receiver_id))->result();
            $reciever_data = $reciever_data[0];
            $invitenewpeoplename= $reciever_data->name;
            $invitenewpeopleemail= $reciever_data->email;
        }
        $is_for = 'project';
        $project_id = $_POST['project_id'];
        $user_id = $_POST['user_id'];
        $last_id = $receiver_id;
        $num_rows = $this->db->get_where('invite_people',array('project_id'=>$project_id,'user_id'=>$last_id,'role'=>$reciever_type))->num_rows();
        if($num_rows>0){
            $message = 'You are already Added in This Project.';
            $response = array('status'=>FAIL,'message'=>$message,'data'=>"");
        }else{    
            // send client invitaion
            $array = array(
                'sender_id' => $user_id,
                'sender_type' => $_POST['role'],
                'receiver_id' => $last_id,
                'reciever_type' => $reciever_type,
                'is_for' => 'project',
                'project_id'=>$project_id
            );
            $project_data = $this->db->get_where('project',array('id'=>$project_id))->result();
            $project_data = $project_data[0];
            $this->db->insert('invite',$array);
            $id = $this->db->insert_id();
            $link = base_url().'invitation/'.encoding($id);
            $data1['full_name']  = $invitenewpeoplename;
            $data1['url']        = $link;
            $data1['sender_type']  = $_POST['role'];
            $data1['is_role']  = 'project';
            $data1['title']  = $project_data->name;
            $message            = $this->load->view('emails/work_invite',$data1,TRUE);
            $subject = 'Bild It - Project Invitation link';
            $to = $invitenewpeopleemail;
            $this->common_model->sendMemberMail($to,$link,$message,$subject);
            $response = array('status'=>SUCCESS,'message'=>'People Invited Successfully','data'=>"");
            // end
        }
        echo json_encode($response);
    }
    
    // invite for task
    function inviteExistingPeopleForTask_post()
    {
        $reciever_type = $_POST['reciever_type'];
        $sender_type = $_POST['role'];
        $receiver_id = $_POST['receiver_id'];
        if($reciever_type == "leadcontractor" || $reciever_type == "subcontractor")
        {
            $reciever_data = $this->db->get_where('contractor',array('id'=>$receiver_id))->result();
            $reciever_data = $reciever_data[0];
            $invitenewpeoplename= $reciever_data->owner_first_name;
            $invitenewpeopleemail= $reciever_data->email;
        }else if($reciever_type == "crew")
        {
            $reciever_data = $this->db->get_where('crew_member',array('id'=>$receiver_id))->result();
            $reciever_data = $reciever_data[0];
            $invitenewpeoplename= $reciever_data->name;
            $invitenewpeopleemail= $reciever_data->email;
        }
        $is_for ='task';
        $taskId = $_POST['task_id'];
        $last_id = $receiver_id;
        $user_id = $_POST['user_id'];
        $this->inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for,$taskId,$reciever_type,$user_id,$last_id,$sender_type);
        $msg = 'People Invited Successfulyy';
        $response              = array('status'=>SUCCESS,'message'=>$msg,'data'=>"");
        $this->response($response);
    }
    
    function inviteNewPeople($invitenewpeoplename,$invitenewpeopleemail,$is_for,$taskId,$type,$user_id,$last_id,$sender_type)
    {
        $array = array(
            'sender_id' => $user_id,
            'sender_type' => $sender_type,
            'receiver_id' => $last_id,
            'reciever_type' => $type,
            'is_for' => $is_for,
            'taskId'=>$taskId
        );
        $project_data = $this->db->get_where('tasks',array('taskId'=>$taskId))->result();
        $project_data = $project_data[0];
        $this->db->insert('invite',$array);
        $id = $this->db->insert_id();
        $link = base_url().'invitation/'.encoding($id);
        $data1['full_name']  = $invitenewpeoplename;
        $data1['url']        = $link;
        $data1['sender_type']  = $sender_type;
        $data1['is_role']  = $is_for;
        $data1['title']  = $project_data->name;
        $message            = $this->load->view('emails/work_invite',$data1,TRUE);
        $subject = 'Bild It - Task Invitation link';
        $to = $invitenewpeopleemail;
        $this->common_model->sendMemberMail($to,$link,$message,$subject);
    }
    
    // create and invite for project
    public function inviteNewPeopleCreateandSend_post()
    {
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_valid_email|valid_email');
     
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()),'data'=>array());
            $this->response($response);
        }else{
            $is_for = 'project';
            $project_id = $_POST['project_id'];
            $reciever_type = $_POST['reciever_type'];
            $sender_type = $_POST['role'];
            $user_id = $_POST['user_id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $last_id = 0;
            if($reciever_type == "leadcontractor" || $reciever_type == "subcontractor")
            {
                $reciever_data = $this->db->get_where('contractor',array('email'=>$email))->result();
                if(isset($reciever_data[0]))
                {
                    $reciever_data = $reciever_data[0];
                    $last_id = $reciever_data->id;
                    $name = $reciever_data->owner_first_name;
                }else{
                    $datainsert = array('owner_first_name'=>$name,'email'=>$email);
                    $this->db->insert('contractor',$datainsert);
                    $last_id = $this->db->insert_id();
                }
            }else if($reciever_type == "crew")
            {
                $reciever_data = $this->db->get_where('crew_member',array('email'=>$email))->result();
                if(isset($reciever_data[0]))
                {
                    $reciever_data = $reciever_data[0];
                    $last_id = $reciever_data->id;
                    $name = $reciever_data->name;
                }else{
                    $datainsert = array('name'=>$name,'email'=>$email);
                    $this->db->insert('crew_member',$datainsert);
                    $last_id = $this->db->insert_id();
                }
            }
            // project invitaion
            $array = array(
                'sender_id' => $user_id,
                'sender_type' => $sender_type,
                'receiver_id' => $last_id,
                'reciever_type' => $reciever_type,
                'is_for' => 'project',
                'project_id'=>$project_id
            );
            $project_data = $this->db->get_where('project',array('id'=>$project_id))->result();
            $project_data = $project_data[0];
            $this->db->insert('invite',$array);
            $id = $this->db->insert_id();
            $link = base_url().'invitation/'.encoding($id);
            $data1['full_name']  = $name;
            $data1['url']        = $link;
            $data1['sender_type']  = 'company';
            $data1['is_role']  = 'project';
            $data1['title']  = $project_data->name;
            $message            = $this->load->view('emails/work_invite',$data1,TRUE);
            $subject = 'Bild It - Project Invitation link';
            $to = $email;
            $this->common_model->sendMemberMail($to,$link,$message,$subject);
            $response = array('status'=>SUCCESS,'message'=>'People Invited Successfully','data'=>array());
            // end
            echo json_encode($response);
        }
    }
    
    // people list api
    public function peopleListApi_post()
    {
        $user_id = $this->post('user_id');
        $role = $this->post('role');
        $type = $this->post('type');
        $value = "";
        if(isset($_POST['value']) && $_POST['value'] != "")
        {
            $value = $this->post('value');    
        }
        if($type=='crew')
        {
            $peopleList = array();
            if($value != "")
            {
                $data = $this->db->select('contractor_member_relationship.type, crew_member.*')
                 ->from('contractor_member_relationship')
                 ->join('crew_member', 'contractor_member_relationship.member_id = crew_member.id')
                 ->where('contractor_member_relationship.contractor_id',$user_id)
                 ->where('contractor_member_relationship.type',$type)
                 ->where('contractor_member_relationship.contractor_type',$role)
                 ->like('crew_member.name',$value,'both')
                 ->order_by("id", "desc")->get()->result();
            }else{
                $data = $this->db->select('contractor_member_relationship.type, crew_member.*')
                 ->from('contractor_member_relationship')
                 ->join('crew_member', 'contractor_member_relationship.member_id = crew_member.id')
                 ->where('contractor_member_relationship.contractor_id',$user_id)
                 ->where('contractor_member_relationship.type',$type)
                 ->where('contractor_member_relationship.contractor_type',$role)
                 ->order_by("id", "desc")->get()->result();
            }
            foreach($data as $key=>$value)
            {
                $x = new stdClass();
                $x->id = $value->id;
                $x->name = $value->name;
                $x->email = $value->email;
                $x->type = "crew";
                array_push($peopleList,$x);
            }
        }else if($type=='subcontractor')
        {
            $peopleList = array();
            if($value != "")
            {
                $data = $this->db->select('contractor_member_relationship.type, contractor.*')
                     ->from('contractor_member_relationship')
                     ->join('contractor', 'contractor_member_relationship.member_id = contractor.id')
                     ->where('contractor_member_relationship.contractor_id',$user_id)
                     ->where('contractor_member_relationship.type',$type)
                     ->where('contractor_member_relationship.contractor_type',$role)
                     ->like('contractor.owner_first_name',$value,'both')
                     ->order_by("id", "desc")->get()->result();  
            }else{
                $data = $this->db->select('contractor_member_relationship.type, contractor.*')
                 ->from('contractor_member_relationship')
                 ->join('contractor', 'contractor_member_relationship.member_id = contractor.id')
                 ->where('contractor_member_relationship.contractor_id',$user_id)
                 ->where('contractor_member_relationship.type',$type)
                 ->where('contractor_member_relationship.contractor_type',$role)
                 ->order_by("id", "desc")->get()->result();
            }
            foreach($data as $key=>$value)
            {
                $x = new stdClass();
                $x->id = $value->id;
                $x->name = $value->owner_first_name;
                $x->email = $value->email;
                $x->type = "subcontractor";
                array_push($peopleList,$x);
            }
        }
        $response       = array('status'=>SUCCESS,'data'=>$peopleList,'message'=>'People List Get Succesfully');
        $this->response($response);
    }
    
    // people list for project
    public function searchmembers_post()
    {
        $project_id = $_POST['project_id'];
        $this->db->where('project_id',$project_id);
        $value_search = $this->input->post('value');
        if($value_search != "")
        {
            $this->db->group_start();
            $this->db-> like('role',$value_search);   
            $this->db->or_like('person_name',$value_search);
            $this->db->group_end();
        }
        $invite_peoples = $this->db->get('invite_people')->result();
        $involvedPeople = array();
        $noninvolvedPeople = array();
        foreach($invite_peoples as $invite_people)
        {
            $people_name = "";
            $people_position = "";
            $assigned_to = "";
            $people_email = "";
            if($invite_people->role == 'leadcontractor'){
                $this->db->where('id',$invite_people->user_id);
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
                }
            }else if($invite_people->role == 'subcontractor'){
                $this->db->where('id',$invite_people->user_id);
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
                }
            }
        }
        $person=new stdClass();
        $person->involvedPeople_list    = $involvedPeople;
        $person->noninvolvedPeople_list = $noninvolvedPeople;
        echo json_encode(array('success'=>true,'data'=>$person,'message'=>'Project People List Retrieve Successfully.'));
    }
    

  
}//End Class
