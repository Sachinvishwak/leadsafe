<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Common controller for service modules
* version: 2.1 (23-01-2019)
*/
require APPPATH . '/libraries/REST_Controller.php';  //load rest library
class Common_Service_Controller extends REST_Controller{
    
    public function __construct(){
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->load->model('api_model'); //load api model model
        $this->load->helper('response_message'); //load api response message helper
    }
    
    /**
     * Check auth token of request
     * Modified in ver 2.0
     */
    public function check_service_auth(){
        
        $this->authData = '';
        $header         = $this->input->request_headers();
        
        /*
         * Convert all keys to lower case as some server manipulates header keys
         * Header keys should always be treated as case insensitive
         */
        $header = array_change_key_case($header, CASE_LOWER);
       
        if(array_key_exists ('auth-token', $header )){
            $key = 'auth-token';
            
        }elseif(array_key_exists ( 'authtoken' , $header )){
            $key = 'authtoken';
            
        }else{
            $this->response($this->token_error_msg(), BAD_REQUEST); //authentication failed 
        }
       
        $authToken = $header[$key];
        if(empty($authToken)){
            $this->response($this->token_error_msg(), BAD_REQUEST); //authentication failed 
        } 
        $userAuthData =  $this->api_model->isValidToken($authToken);

        if(!$userAuthData){
            $this->response($this->token_error_msg(2), BAD_REQUEST); //authentication failed 
        }
        if($userAuthData->status != 1){
            $this->response($this->token_error_msg(1), BAD_REQUEST); //authentication successfull,but user is inactive/disabled 
        } 
        //user authenticated successfully
        $this->authData = $userAuthData; 
        return TRUE;
    }//End function
    
    /**
     * Show auth token error message
     * Added in ver 2.0
     */
    public function token_error_msg($inactive_status=1){

        $res_arr = array('message'=>ResponseMessages::getStatusCodeMessage(101),'authToken'=>'','responseCode'=>300, 'isActive'=>1);
        if($inactive_status==1){
            $res_arr['isActive'] = 0; //user is inactive
        }
        return $res_arr;
    }//End function
}//End Class 