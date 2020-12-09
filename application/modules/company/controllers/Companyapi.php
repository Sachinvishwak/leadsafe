<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Companyapi extends REST_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('Companyapi_modal'); //load image model
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    // For Registration 
    function registration_post(){
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_valid_email|valid_email|is_unique[admin.email]',
            array('is_unique' => 'Email already exist')
        );
        $this->form_validation->set_rules('contact', 'contact number', 'trim|required|min_length[10]|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]|max_length[20]');
$this->form_validation->set_rules('fullName', 'full Name', 'trim|required|regex_match[/^([a-z ])+$/i]|min_length[2]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
        
            $email                          =  $this->post('email');
            $fullName                       =  $this->post('fullName');
            $authtoken                      = $this->Companyapi_modal->generate_token();
            $passToken                      = $this->Companyapi_modal->generate_token();
            //user info
            $userData['name']           =   $fullName;
            $userData['email']              =   $email;
            $userData['phone_number']      =   $this->post('contact');
            $userData['fax_number']      =   $this->post('fax_number');
            $userData['authToken']          =   $authtoken;
            $userData['password']           =   password_hash($this->post('npassword'), PASSWORD_DEFAULT);
            $userData['authToken']          =   $authtoken;
            $userData['passToken']          =   $passToken;
            //user info
            // profile pic upload
            $config['upload_path']= "./uploads/company/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = TRUE;
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

            $userData['licence']                         =   $licence;
            $userData['insurence_certificate']           =   $insurence_certificate;

            $result = $this->Companyapi_modal->registration($userData);
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                        $response = array('status'=>SUCCESS,'message'=>'Company Registered Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/login');
                    break;
                    case "AE": // User already registered
                        $response = array('status'=>FAIL,'message'=>'Company Already Registered','users'=>array());
                    break;
                    default:
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'userDetail'=>array());
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'userDetail'=>array());
            }   
            $this->response($response);
        }
    } //End Function

    function login_post(){
        $this->form_validation->set_rules('email','Email','trim|required|valid_email');
        if($this->form_validation->run() == FALSE)
        {
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);

        }else{
            $authtoken              = $this->Companyapi_modal->generate_token();
            $data                   = array();
            $data['email']          = $this->post('email');
            $data['authToken']      = $authtoken;
            $data['password']       = $this->post('npassword');
            $result                 = $this->Companyapi_modal->login($data,$authtoken);
            if(is_array($result)){
                switch ($result['returnType']) {
                    case "SL":
                        $this->StoreSession($result['userInfo']);
                        $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(106), 'users' => $result['userInfo'],'url' => 'admin/dashboard');
                    break;
                    case "WP":
                        $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(102));
                    break;
                    case "WE":
                        $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(126));
                    break;
                    case "IU":
                        $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(118));
                    break;
                    case "WS":
                        $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(118));
                    break;
                    default:
                        $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(106), 'users' => $result['userInfo'],'url' => 'admin/dashboard');
                }
            }else{
                $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(126));
            } 
            $this->response($response);
        }
    } 

    function StoreSession($userData){
        
        $session_data['email']          = $userData->email;
        $session_data['id']          = $userData->company_id;
        $session_data['fullname']          = $userData->name;
        $session_data['authToken']          = $userData->authToken;
        $session_data['company_id']          = $userData->company_id;
        $session_data['fax_number']          = $userData->fax_number;
        $session_data['phone_number']          = $userData->phone_number;
        $session_data['status']          = $userData->status;
        $session_data['insurence_certificate']          = $userData->insurence_certificate;
        $session_data['licence']          = $userData->licence;
        $session_data['isLogin']        = TRUE;
      //  pr( $session_data);
        $_SESSION['company_sess']        = $session_data;   
        return TRUE;
    }//
    
    //image preview
    public function imagepreview_post()
    {
        $config['upload_path']= "./uploads/preview/";
        $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload',$config);

        if(isset($_FILES['licence_media']))
        {
            if($this->upload->do_upload('licence_media'))
            {
                $data = array('upload_data' => $this->upload->data());
                $licence= $data['upload_data']['file_name'];
            }
        }else if(isset($_FILES['insurence_certificate_media'])){
            if($this->upload->do_upload('insurence_certificate_media'))
            {
                $data = array('upload_data' => $this->upload->data());
                $licence= $data['upload_data']['file_name'];
            }
        }else if(isset($_FILES['profileImage_media'])){
            if($this->upload->do_upload('profileImage_media'))
            {
                $data = array('upload_data' => $this->upload->data());
                $licence= $data['upload_data']['file_name'];
            }
		}else if(isset($_FILES['filedata'])){
			if($this->upload->do_upload('filedata'))
			{
				$data = array('upload_data' => $this->upload->data());
				$licence= $data['upload_data']['file_name'];
				echo json_encode(array(
					'name' => $licence
				));
				die;
			}
		}
        
        $mediapath = base_url('uploads/preview/').$licence;
	    if(@is_array(getimagesize($mediapath))){
            $image = 1;
        } else {
            $image = 0;
        }
										
		if($image == 0)
		{ 
		$chromePath = 'https://docs.google.com/viewer?url='.$mediapath.'&embedded=true';
        $data = '<div>
            <object style="width:225px; overflow:hidden;" src="'.$mediapath.'">
                <iframe style="width:450px;height:400px;" src="'.$chromePath.'">
                </iframe>
            </object>
        </div>';
        echo $data;
        
        
        }else {
            $location = base_url().'uploads/preview/'.$licence;
            echo '<img src="'.$location.'" height="150" width="175" class="img   -thumbnail" />';
        }
        
        
    }
    
    //profile update
    function updateCompany_post(){
        $this->form_validation->set_rules('email', 'Company Email', 'trim|required|callback_valid_email|valid_email');
        $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        // $this->form_validation->set_rules('fax_number', 'fax number', 'trim|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('name', 'full Name', 'trim|required|regex_match[/^([a-z0-9 ])+$/i]|min_length[2]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));  
        }else{
            
            if(!isset($_FILES['profileImage']))
            {
                 $clientmemberprofile = $this->db->get_where('company',array('company_id'=>$this->post('userauth')))->result();
                 $clientmemberprofile = $clientmemberprofile[0];
                 if($clientmemberprofile->profile_photo == NULL || $clientmemberprofile->profile_photo == "" || $clientmemberprofile->profile_photo == "")
                 {
                    $response = array('status' => FAIL, 'message' => 'Please Select Profile Image.','data'=>array());
                    $this->response($response);
                    die;
                 }
            }
        
            $userid             =  $this->post('userauth');
            $userauth           =  $userid;
            $email              =  $this->post('email');
            $fullName           =  $this->post('name');
            $isExist            =  $this->common_model->is_data_exists('company',array('company_id'=>$userauth));
            if($isExist){
                $isExistEmail   = $this->common_model->is_data_exists('company',array('company_id  !='=>$userauth,'email'=>$email));
                if(!$isExistEmail){
                    $userData['name']          =   $fullName;
                    $userData['email']              =   $email;
                    $userData['phone_number']      =   $this->post('phone_number');
                    $userData['fax_number']      =   $this->post('fax_number');
                    
                    $config['upload_path']= "./uploads/company/";
                    $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp";
                    $config['encrypt_name'] = FALSE;
                    $this->load->library('upload',$config);
                    
                    $licence = "";
                    $insurence_certificate = "";
                    $profileImage = "";
                    
                    if(isset($_FILES['licence']))
                    {
                        $licence = $_FILES['licence']['name'];
                    }
                    
                    if(isset($_FILES['insurence_certificate']))
                    {
                        $insurence_certificate = $_FILES['insurence_certificate']['name'];
                    }
                    
                    if(isset($_FILES['profileImage']))
                    {
                        $profileImage = $_FILES['profileImage']['name'];
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
                        $userData['licence']                      = $licence;
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
                        $userData['insurence_certificate']        = $insurence_certificate;
                    }
                    if($profileImage != "")
                    {
                        if($this->upload->do_upload('profileImage'))
                        {
                            $data = array('upload_data' => $this->upload->data());
                            $profileImage= $data['upload_data']['file_name'];
                        }else{
                            $error = $this->upload->display_errors(); 
                            $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp format files.';
                            $response              = array('status'=>FAIL,'message'=>$error);
                            $this->response($response);
                            die;
                        }
                        $userData['profile_photo']        = $profileImage;
                    }
                    
                    $this->db->where('company_id',$userauth);
					$result = $this->db->update('company',$userData);
					
					// multiple document upload
					if(isset($_POST['all_docs']))
					{
						$all_docs = json_decode($_POST['all_docs']);
									
						foreach($all_docs as $all_doc)
						{
							$array = array(
								'type' => 'company',
								'user_id' => $userauth,
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
								'type' => 'company',
								'user_id' => $userauth,
								'file_name' => $all_doc
							);
							$this->db->insert('license_media',$array);
						}
					}
                    
                    if($result){
                        $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(123),'url'=>'admin/profile');
                    }else{
                        $response   = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'userDetail'=>array());
                    }  
                }else{
                    $response       = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(117),'userDetail'=>array());
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(126),'userDetail'=>array()); 
            }
        }
        $this->response($response);
    }//end function
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
  

function invitecompany_post()
    {
        $this->load->model('Companyapi_modal');
        $email = $this->input->post('email');
        $name = $this->input->post('name');
        
  $this->form_validation->set_rules('name', 'name', 'trim|required|min_length[4]|regex_match[/^([a-z0-9 ])+$/i]');
        $this->form_validation->set_rules('email', 'email', 'trim|required|callback_valid_email|valid_email');
        
        
        if($name == "" || $email == "")
        {
            $response       = array('status'=>FAIL,'message'=>'Please Enter Name And Email First','userDetail'=>array());
        }else{
            if($this->form_validation->run() == FALSE){
                $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));    
            }else{
                $isExistEmail   = $this->common_model->is_data_exists('company',array('email'=>$email));


                $isExistname   = $this->common_model->is_data_exists('company',array('name'=>$name));
                
                if($isExistname!=''|| !empty($isExistname))
                {
                    $response       = array('status'=>FAIL,'message'=>'Company Name Already Registered'); 
                    $this->response($response);
                    die;
                    
                } 

                if(!$isExistEmail)
                {
                    $userData['name']               =   $name;
                    $userData['email']              =   $email;
                    $userData['phone_number']       =   '';
                    $userData['fax_number']         =   '';
                    $userData['authToken']          =   '';
                    $userData['password']           =   '';
                    $userData['authToken']          =   '';
                    $userData['passToken']          =   '';
                    $licence = '';
                    $insurence_certificate = '';
                    $userData['licence']                         =   $licence;
                    $userData['insurence_certificate']           =   $insurence_certificate;
                    //$result = $this->Companyapi_modal->registration($userData);

                    $result = $this->db->insert('company',$userData);
                    $id = $this->db->insert_id();
                    $link = base_url().'invite/'.base64_encode($id);
                    $data1['url']        = $link;
                    $data1['full_name']        = $name;

                    $message            = $this->load->view('emails/company_invite',$data1,TRUE);
                    $subject = 'Bild It - Account Invitation link';
                    $to = $email;
                    $this->common_model->sendMemberMail($to,$link,$message,$subject);
                    // end
                    $response       = array('status'=>SUCCESS,'message'=>'Invite link sent successfully.');    
                }else{
                    $this->db->where('email',$email);
                    $company_data = $this->db->get('company')->result();
                    $company_data = $company_data[0];
                    if($company_data->password == "" || $company_data->password == NULL )
                    {
                        $id = $company_data->company_id;
                        // invite 
                        $link = base_url().'invite/'.base64_encode($id);
                        $data1['url']        = $link;
                        $data1['full_name']        = $company_data->name;
                        $message            = $this->load->view('emails/company_invite',$data1,TRUE);
                        $subject = 'Bild It - Account Invitation link';
                        $to = $company_data->email;
                        $this->common_model->sendMemberMail($to,$link,$message,$subject);
                        // end
                        $response       = array('status'=>SUCCESS,'message'=>'Invite link sent successfully.');  
                    }else{
                        $response       = array('status'=>FAIL,'message'=>'Company Already Registered','userDetail'=>array(),'company_data'=>$company_data);    
                    }
                }    
            }
        }
        $this->response($response);
    }    // invite client multiple
    function inviteClientMultiple_post()
    {
        $client_name = $_POST['client_name'];
        $client_email = $_POST['client_email'];
        $projectId = $_POST['projectId'];
        $dataArray = [];
        foreach($client_name as $key=>$value)
        {
            $cName = $value;
            $cEmail = $client_email[$key];
            $company_id = 0;
            if(isset($_POST['company_id']))
            {
                $dataArray = array('name'=>$cName,'email'=>$cEmail,'company_id'=>$_POST['company_id']);
                $company_id = $_POST['company_id'];
            }else{
                $dataArray = array('name'=>$cName,'email'=>$cEmail,'company_id'=>$_SESSION['company_sess']['id']);
                $company_id = $_SESSION['company_sess']['id'];
            }
            $this->db->insert('client',$dataArray);
            $id = $this->db->insert_id();
            $link = base_url().'inviteClient/'.base64_encode($id);
            $this->load->library('email');
            $this->email->from('rathoreankit582@gmail.com', 'Bild It');
            $this->email->to($cEmail);
            $this->email->subject('Invitaion Links For Client');
            $this->email->message($link);
            $this->email->send();
            
            //project invitation
            $project_id = $projectId[$key];
            $array = array(
                'sender_id' => $company_id,
                'sender_type' => 'company',
                'receiver_id' => $id,
                'reciever_type' => 'client',
                'is_for' => 'project',
                'project_id'=>$project_id
            );
            $project_data = $this->db->get_where('project',array('id'=>$project_id))->result();
            $project_data = $project_data[0];
            $this->db->insert('invite',$array);
            $id = $this->db->insert_id();
            $link = base_url().'invitation/'.encoding($id);
            $data1['full_name']  = $result['returnData'][0]->name;
            $data1['url']        = $link;
            $data1['sender_type']  = 'company';
            $data1['is_role']  = 'project';
            $data1['title']  = $project_data->name;
            $message            = $this->load->view('emails/work_invite',$data1,TRUE);
            $subject = 'Bild It - Project Invitation link';
            $to = $result['returnData'][0]->email;
            $this->common_model->sendMemberMail($to,$link,$message,$subject);
            //end
            
        }
        $response       = array('status'=>SUCCESS,'message'=>'Clients Invited Successfully','userDetail'=>array());
        $this->response($response);
    }
    
    // set password
    function resetpassword_post()
    {
        $userData['password']           =   password_hash($this->post('npassword'), PASSWORD_DEFAULT);
        $userData['status']           =   1;
        $rnpassword = $_POST['rnpassword'];
        $id = $_POST['id'];
        
        $this->db->where('company_id',$id);
        $result = $this->db->update('company',$userData);
        
        if($result)
        {
            $response = array('status' => SUCCESS, 'message' => 'Password Created Successfully','url'=>'admin/login');
        }else{
            $response = array('status' => FAIL, 'message' => 'Something Went Wrong');
        }
        
        $this->response($response);
    }
    
    // chnage password
    function changepassword_post()
    {
        $userId     = $_POST['company_id'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('npassword', 'new password', 'trim|required|matches[rnpassword]|min_length[6]');
        $this->form_validation->set_rules('rnpassword', 'Please Enter Correct Password','trim|required|min_length[6]');
       if($this->form_validation->run($this) == FALSE){
            $messages       = (validation_errors()) ? validation_errors() : '';
            $messages       =  str_replace("\n","",$messages);
            $messages       =  str_replace("<p>","",$messages);
            $messages       =  str_replace("</p>","",$messages);
            $messages       =  str_replace("the","",$messages);
            $response       = array('status' => FAIL, 'message' => $messages);
        }else{
            $password       = $this->input->post('password');
            $npassword      = $this->input->post('npassword');
            $select         = "password";
            $where          = array('company_id' => $userId); 
            $admin          = $this->common_model->getsingle('company', $where,'password');
            if(password_verify($password, $admin['password'])){
                $set        = array(
                    'password'=> password_hash($this->input->post('npassword') , PASSWORD_DEFAULT),
                    'pwd'=> $this->input->post('npassword') 
                    ); 
                
                    
                $update     = $this->common_model->updateFields('company', $set, $where);
                if($update){
                    $res = array();
                    if($update){
                        $response = array('status' =>SUCCESS, 'message' => 'Successfully Updated', 'url' => base_url('admin/profile'));
                    }else{
                        $response = array('status' =>FAIL, 'message' => 'Failed! Please try again', 'url' => base_url('admin/profile'));
                    }    
                } 
            }else{
                $response = array('status' =>FAIL, 'message' => 'Your Current Password is Wrong !', 'url' => base_url('admin/profile','data'));                 
            }
        }
       $this->response($response);
    }
    
    
    public function profile_post(){
      //pr('admin@admin.com');
        $userId             = $this->input->post('id');
        $where              = array('company_id'=>$userId);
        $data             = $this->common_model->getsingle('company',$where);
        if($data == null){
            $response       = array('status'=>FAIL,'message'=>'No Data Found','userDetail'=>$userId);
        }else{
            if($data['licence'] != "")
            {
                $data['licence'] = base_url('uploads/company/').$data['licence'];
            }
            if($data['insurence_certificate'] != "")
            {
                $data['insurence_certificate'] = base_url('uploads/company/').$data['insurence_certificate'];
            }
            if($data['profile_photo'] != "")
            {
                $data['profile_photo'] = base_url('uploads/company/').$data['profile_photo'];
			}
			
			$license_media = $this->db->get_where('license_media',array('type'=>'company','user_id'=>$userId))->result();
			foreach($license_media as $licenses)
			{
				$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
			}
		
			$data['multiple_license'] = $license_media;
            
            $response       = array('status'=>SUCCESS,'message'=>'Profile Fetched Successfully','userDetail'=>$data);    
        }
        
        $this->response($response);
    } //End function


    // get people list
    public function getpeoplelist_post()
    {
        $searchvalue = strtoupper($_POST['value']);
        $company_id = $this->input->post('company_id');
		$peopleList = $this->db->get_where('company_member_relations',array('company_id'=>$company_id))->result();
		
		if(isset($_POST['project_id']) && $_POST['project_id'] != 0 && $_POST['project_id'] != "" && $_POST['project_id'] != null && $_POST['project_id'] != "null" && $_POST['project_id'] != NULL && $_POST['project_id'] != "NULL" && $_POST['project_id'] != -1)
		{
			$projectId = $_POST['project_id'];
		}else{
			$projectId = 0;
		}
        
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
							$userData->group_id = "0";
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
						$userData->group_id = "0";
    
                        $array = array(
                            'reciever_id' => $company_id,
                            'user_id' => $people->member_id,
                            'reciever_type' => 'company',
                            'user_type' => $people->type,
                            'is_seen' => 0
                        );
                        $count = $this->db->get_where('chat',$array)->num_rows();
                        $userData->countnotification = $count;
                       
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
							$userData->group_id = "0";
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
						$userData->group_id = "0";
                        
                        $array = array(
                            'reciever_id' => $company_id,
                            'user_id' => $people->member_id,
                            'reciever_type' => 'company',
                            'user_type' => $people->type,
                            'is_seen' => 0
                        );
                        $count = $this->db->get_where('chat',$array)->num_rows();
                        $userData->countnotification = $count;
                        
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
							$userData->group_id = "0";
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
						$userData->group_id = "0";
                        
                        $array = array(
                            'reciever_id' => $company_id,
                            'user_id' => $people->member_id,
                            'reciever_type' => 'company',
                            'user_type' => $people->type,
                            'is_seen' => 0
                        );
                        $count = $this->db->get_where('chat',$array)->num_rows();
                        $userData->countnotification = $count;
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
			$groupList = $this->db->get_where('chat_group',array('created_by'=>$company_id,'project_id'=>$projectId))->result();
		}else{
			$groupList = $this->db->get_where('chat_group',array('created_by'=>$company_id))->result();
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
			if($searchvalue != "" )
			{
				if(strpos($group->name, $searchvalue) !== false)
				{
					array_push($allpeopleList,$object);
				}
			}else{
				array_push($allpeopleList,$object);
			}
		}

        $data['peopleList']                   =  $allpeopleList;
        $response       = array('status'=>SUCCESS,'message'=>'Profile Fetched Successfully','people_list'=>$allpeopleList);    
        
        $this->response($response);
    }


    // ENd Session store value for frontEnd
}//End Class 
