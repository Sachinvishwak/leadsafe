<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Adminapi extends Common_Admin_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('adminapi_model'); //load image model
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
    // For Registration 
    function registration_post(){
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_valid_email|valid_email|is_unique[admin.email]',
            array('is_unique' => 'Email already exist')
        );
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]|max_length[20]');
        //$this->form_validation->set_rules('contact', 'Contact Number', 'trim|required|min_length[10]|regex_match[/^[0-9]{10}$/]|max_length[20]');
$this->form_validation->set_rules('fullName', 'full Name', 'trim|required|regex_match[/^([a-z ])+$/i]|min_length[2]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
        
            $email                          =  $this->post('email');
            $fullName                       =  $this->post('fullName');
            $authtoken                      = $this->adminapi_model->generate_token();
            $passToken                      = $this->adminapi_model->generate_token();
            //user info
            $userData['fullName']           =   $fullName;
            $userData['email']              =   $email;
            $userData['userType']           =   1;
            $userData['contactNumber']      =   $this->post('contact');
            $userData['authToken']          =   $authtoken;
            $userData['password']           =   password_hash($this->post('password'), PASSWORD_DEFAULT);
            $userData['authToken']          =   $authtoken;
            $userData['passToken']          =   $passToken;
            //user info
            // profile pic upload
            $this->load->model('Image_model');
            $image          = array(); 
            $profileImage   = '';
            if (!empty($_FILES['profileImage']['name'])) {
                $folder     = 'users';
                $image      = $this->Image_model->upload_image('profileImage',$folder); //upload media of Seller
                //check for error
                if(array_key_exists("error",$image) && !empty($image['error'])){
                    $response = array('status' => FAIL, 'message' => strip_tags($image['error'].'(In user Image)'));
                   $this->response($response);
                }  
                //check for image name if present
                if(array_key_exists("image_name",$image)):
                    $profileImage = $image['image_name'];
                endif;
            }
            $userData['profileImage']           =   $profileImage;

            $result = $this->adminapi_model->registration($userData);
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                        $this->StoreSession($result['returnData']);
                        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(110), 'messageCode'=>'normal_reg','users'=>$result['returnData']);
                    break;
                    case "AE": // User already registered
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(117),'users'=>array());
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
                switch ($result['returnType']) {
                    case "SL":
                        $this->StoreSession($result['userInfo']);
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
            }else{
                $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(126));
            } 
            $this->response($response);
        }
    } //End Function
    
    function superadminlogin_post(){
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
                switch ($result['returnType']) {
                    case "SL":
                        $result['userInfo']->id = encoding($result['userInfo']->id);
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
                        $result['userInfo']->id = encoding($result['userInfo']->id);
                        $response = array('status' => SUCCESS, 'message' => ResponseMessages::getStatusCodeMessage(106), 'users' => $result['userInfo']);
                }
            }else{
                $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(126));
            } 
            $this->response($response);
        }
    }
    
    //user forgot password
    function forgotPassword_post(){

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }

        $email      = $this->post('email');
        $response   = $this->adminapi_model->forgotPassword($email);
        if($response['emailType'] == 'ES'){ //ES emailSend
            $response = array('status' => SUCCESS, 'message' => 'Please check your mail to reset your password.');
        }elseif($response['emailType'] == 'NS'){ //NS NotSend
            $response = array('status' => FAIL, 'message' => 'Error not able to send email');
        }elseif($response['emailType'] == 'NE'){ //NE Not exist
            $response = array('status' => FAIL, 'message' => 'This Email does not exist'); 
        }elseif($response['emailType'] == 'SL'){ //SL social login
            $response = array('status' => FAIL, 'message' => 'Social registered users are not allowed to access Forgot password'); 
        }
        $this->response($response);
    } //End function

    // Session store value for frontEnd
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

    //dashboard api
    function dashboard_get()
    {
        // $res_auth = $this->api_check_admin_service_auth();
        // if($res_auth == false)
        // {
        //     $response = array('success'=>false,'taskCount'=>'','companyCount'=>+'','message'=>'Invalid Token');
        //     echo json_encode($response);
        // }else{
        //
            $taskCount = $this->db->get_Where('tasks',array('created_by'=>1));
            $taskCount = $taskCount->num_rows();
            $companyCount = $this->db->get('company');
            $companyCount = $companyCount->num_rows();
            $response = array('success'=>true,'taskCount'=>$taskCount,'companyCount'=>$companyCount,'message'=>'Data Retrieve Successfully');
            echo json_encode($response);   
        // }
    }

    // profile api
    function profile_post()
    {
        $userId = decoding($this->post('id'));
        $where              = array('id'=>$userId);
        $result[0]             = $this->common_model->getsingle('admin',$where);
        $result[0]['profileImage']  = base_url('uploads/admin/').$result[0]['profileImage'];
        echo json_encode(array('success'=>true,'data'=>$result[0],'test'=>$userId));
    }


    // ENd Session store value for frontEnd
}//End Class 