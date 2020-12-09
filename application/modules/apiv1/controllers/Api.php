<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Api extends Common_Service_Controller{
    
    public function __construct(){
        parent::__construct();
  error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->load->model('api_model'); //load image model
    }

    // For Registration 
    function registration_post(){
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]',
            array('is_unique' => 'Email already exist')
        );
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]|max_length[20]');
        $this->form_validation->set_rules('contact', 'Contact Number', 'trim|required|min_length[10]|max_length[20]');
        $this->form_validation->set_rules('fullName', 'full Name', 'trim|required|min_length[2]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        } else{

            $email                          = $this->post('email');
            $fullName                       = $this->post('fullName');
            $authtoken                      = $this->api_model->generate_token();
            $passToken                      = $this->api_model->generate_token();
            //user info
            $userData['fullName']           =   $fullName;
            $userData['email']              =   $email;
            $userData['userType']           =   2;
            $userData['contactNumber']      =   $this->post('contact');
            $userData['authToken']          =   $authtoken;
            $userData['password']           =   password_hash($this->post('password'), PASSWORD_DEFAULT);
            $userData['authToken']          =   $authtoken;
            $userData['passToken']          =   $passToken;

            //user info
            // profile pic upload
            $this->load->model('Image_model');
          
            $image = array(); $profileImage = '';
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
            $userData['profileImage']       =   $profileImage;
            $result = $this->api_model->registration($userData);
            if(is_array($result)){

               switch ($result['regType']){
                    case "NR": // Normal registration
                    $this->StoreSession($result['returnData']);
                    //send mail
                        $maildata['title']    = $result['returnData']->fullName." been invited to join Interface service";
                        $maildata['message']  = "<table><tr><td>Name</td><td>".$result['returnData']->fullName."</td></tr><tr><td>Email</td><td>".$result['returnData']->email."</td></tr></table>";
                        $subject    = "Create customer";
                        $message    = $this->load->view('emails/email',$maildata,TRUE);
                        $emails     = $this->common_model->adminEmails();
                        if(!empty($emails)){
                       // $this->load->library('smtp_email');
                       // $this->smtp_email->send_mail_multiple($emails,$subject,$message);
                        }
                    //send mail
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
        }
        else
        {
            $authtoken              = $this->api_model->generate_token();
            $data                   = array();
            $data['email']          = $this->post('email');
            $data['password']       = $this->post('password');
            $data['deviceType']     = $this->post('deviceType');
            $data['deviceToken']    = $this->post('deviceToken');
            $data['authToken']      = $authtoken;
            $result                 = $this->api_model->login($data,$authtoken);
            if(is_array($result)){
                switch ($result['returnType']) {
                    case "SL":
                     
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
            else{
                $response = array('status' => FAIL, 'message' => ResponseMessages::getStatusCodeMessage(126));
            }    
            $this->response($response);
        }
    } //End Function
    //user forgot password
    function forgotPassword_post(){
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }
        $email      = $this->post('email');
        $response   = $this->api_model->forgotPassword($email);
        if($response['emailType'] == 'ES'){ //ES emailSend
            $response = array('status' => SUCCESS, 'message' => 'Please check your mail to reset your password.');
        }elseif($response['emailType'] == 'NS'){ //NS NotSend
            $response = array('status' => FAIL, 'message' => 'Error not able to send email');
        }
        elseif($response['emailType'] == 'NE'){ //NE Not exist
            $response = array('status' => FAIL, 'message' => 'This Email does not exist'); 
        }elseif($response['emailType'] == 'SL'){ //SL social login
            $response = array('status' => FAIL, 'message' => 'Social registered users are not allowed to access Forgot password'); 
        }
        $this->response($response);
    } //End function

    
}//End Class 