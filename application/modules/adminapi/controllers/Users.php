<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Users extends Common_Admin_Controller{
    
    public function __construct(){
        parent::__construct();
    }

    public function changePassword_post()
    {
        $authCheck  = $this->check_admin_service_auth();
        $authToken  = $this->authData->authToken;
        $userId     = $this->authData->id;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('npassword', 'new password', 'trim|required|matches[rnpassword]|min_length[6]');
        $this->form_validation->set_rules('rnpassword', 'retype new password ','trim|required|min_length[6]');
       if($this->form_validation->run($this) == FALSE){

			$messages       = 'New password and confirm password  does not match';
			
            $messages       =  str_replace("\n","",$messages);
            $messages       =  str_replace("<p>","",$messages);
            $messages       =  str_replace("</p>","",$messages);
            $response       = array('status' => FAIL, 'message' => $messages);
        }else{
            $password       = $this->input->post('password');
            $npassword      = $this->input->post('npassword');
            $select         = "password";
            $where          = array('id' => $userId); 
            $admin          = $this->common_model->getsingle('admin', $where,'password');
            if(password_verify($password, $admin['password'])){
                $set        = array(
                                'password'=> password_hash($this->input->post('npassword') , PASSWORD_DEFAULT),
                                'pwd'=> $this->input->post('npassword') 
                                );             
                $update     = $this->common_model->updateFields('admin', $set, $where);
                if($update){
                    $res = array();
                    if($update){
                        $response = array('status' =>SUCCESS, 'message' => 'Successfully Updated', 'url' => base_url('users/userDetail'));
                    }else{
                        $response = array('status' =>FAIL, 'message' => 'Failed! Please try again', 'url' => base_url('users/userDetail'));
                    }    
                } 
            }else{
                $response = array('status' =>FAIL, 'message' => 'Your Current Password is Wrong !', 'url' => base_url('users/userDetail','data'),'test'=>$admin);                 
            }
        }
       $this->response($response);
    }//End Function
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
    function updateUser_post(){
        $authCheck  = $this->check_admin_service_auth();
        $authToken  = $this->authData->authToken;
        $userId     = $this->authData->id;
        $this->form_validation->set_rules('email', 'Email', 'trim|callback_valid_email|required|valid_email');
     $this->form_validation->set_rules('contact', 'Contact Number', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
$this->form_validation->set_rules('fullName', 'full Name', 'trim|required|regex_match[/^([a-z ])+$/i]|min_length[2]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));  
        }else{
            $userid             =  $this->post('userauth');
            $userauth           =  decoding($userid);
            $email              =  $this->post('email');
            $fullName           =  $this->post('fullName');
         //   $weatherTemperature           =  $this->post('weatherTemperature');
            $isExist            =  $this->common_model->is_data_exists('admin',array('id'=>$userauth));
            if($isExist){
                $isExistEmail   = $this->common_model->is_data_exists('admin',array('id  !='=>$userauth,'email'=>$email));
                if(!$isExistEmail){
                    //update
                    //user info
                    $userData['fullName']           =   $fullName;
                    $userData['email']              =   $email;
                    $userData['contactNumber']      =   $this->post('contact');
                   // $userData['weatherTemperature'] =   $this->post('weatherTemperature');
                    //user info
                    // profile pic upload
                    $this->load->model('Image_model');

                    $image          = array(); $profileImage = '';
                    if (!empty($_FILES['profileImage']['name'])) {
                        $folder     = 'admin';
                        $image      = $this->Image_model->upload_image('profileImage',$folder); //upload media of Seller
                        //check for error
                        if(array_key_exists("error",$image) && !empty($image['error'])){
                            $response = array('status' => FAIL, 'message' => strip_tags($image['error'].'(In user Image)'));
                           $this->response($response);die;
                        }
                        //check for image name if present
                        if(array_key_exists("image_name",$image)):
                            $profileImage = $image['image_name'];
                            if(!empty($isExist->profileImage)){
                                $this->Image_model->delete_image('uploads/admin/',$isExist->profileImage);
                            }
                        endif;
                        }
                        if(!empty($profileImage)){
                            $userData['profileImage']           =   $profileImage;
                        } 
                    //update
                    $result = $this->common_model->updateFields('admin',$userData,array('id'=>$userauth));
                    if($result){
                        $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(123),'url'=>$userid);
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
    
    // super admin logout
    function superadminlogout_post(){
        $userid             =  $this->post('userauth');
        $userauth           =  decoding($userid);
        //$userData['authToken']              =   '';
        // $result = $this->common_model->updateFields('admin',$userData,array('id'=>$userauth));
        $response   = array('status'=>SUCCESS,'message'=>'Logout Successfully','url'=>$userid);
        $this->response($response);
    }
    
    // generic login
    function login_post(){
        $this->form_validation->set_rules('email','Email','trim|required|valid_email');
        $this->form_validation->set_rules('password','Password','trim|required');
        if($this->form_validation->run() == FALSE)
        {
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            $authtoken              = $this->adminapi_model->generate_token();
            $data                   = array();
            $data['email']          = $this->post('email');
            $data['password']       = $this->post('password');
            $data['authToken']      = $authtoken;
            $result                 = $this->adminapi_model->login($data,$authtoken);
         
            if(is_array($result)){
                if($result['returnType'] != "SL")
                {
                    
                   $result = $this->company_login($data,$authtoken);
                  //  print_r($result);
                $pic=base_url('uploads/company/').$result['userInfo']->profile_photo;
              
                  
                    if(is_array($result)){
                        switch ($result['returnType']) {
                            case "SL":
                                $this->Company_StoreSession($result['userInfo']);
                                $result['userInfo']->id = encoding($result['userInfo']->id) ;
                                $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(106), 'users' => $result['userInfo'],'url' => 'admin/dashboard','profile'=>$pic);
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
                }else{
                    switch ($result['returnType']) {
                        case "SL":
                            $this->StoreSession($result['userInfo']);
                            $result['userInfo']->id = encoding($result['userInfo']->id) ;
                            $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(106), 'users' => $result['userInfo']);
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
                            $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(106), 'users' => $result['userInfo']);
                    }
                }
            }else{
                $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(126));
            } 
            $this->response($response);
        }
    } //End Function
    
    function updateDeviceIdToken($id,$authToken,$table ='company')
    {
        $req = $this->db->select('company_id')->where('company_id',$id)->get($table);
        if($req->num_rows())
        {
            $this->db->update($table,array('authToken'=>$authToken),array('company_id'=>$id));
            return TRUE;
        }
        return FALSE;
    }//End Function Update Device Token
    
    function company_login($data,$authToken){
        $res = $this->db->select('*')->where(array('email'=>$data['email']))->get('company');
        if($res->num_rows()){
            $result = $res->row();
            if(password_verify($data['password'], $result->password)){
                $updateData = $this->updateDeviceIdToken($result->company_id,$authToken);
                if($updateData){
                    $userInfo= $this->db->get_where('company',array('company_id'=>$result->company_id))->result();
                   return array('returnType'=>'SL','userInfo'=>$userInfo[0]);
                }else{
                    return FALSE;
                }
            }else{
                    return array('returnType'=>'WP'); // Wrong Password
            }
        }else {
            return array('returnType'=>'WE'); // Wrong Email
        }
    }//End users Login
    
    function Company_StoreSession($userData){
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
    
    function StoreSession($userData){
        $session_data['id']             = $userData->userId;
        $session_data['userId']         = $userData->userId;
        $session_data['fullName']       = $userData->fullName;
        $session_data['email']          = $userData->email;
        $session_data['userType']       = $userData->userType;
        $session_data['userRole']       = $userData->userRole;
        $session_data['image']          = $userData->profileImage;
        $session_data['isLogin']        = TRUE ;
      //  pr( $session_data);
        $_SESSION[ADMIN_USER_SESS_KEY]        = $session_data;   
        return TRUE;
    }// End Function  
    
    
}//End Class 
