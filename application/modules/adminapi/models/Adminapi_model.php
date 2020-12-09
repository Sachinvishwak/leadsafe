<?php
/**
* Web service model
* Handles web service request
* version: 2.0 ( 14-08-2018 )
*/
class Adminapi_model extends CI_Model {
	
    /**
     * Generate auth token for API users
     * Modified in version 2.0
    */
    function generate_token(){
        $this->load->helper('security');
        $res     = do_hash(time().mt_rand());
        $new_key = substr($res,0,config_item('rest_key_length'));
        return $new_key;

    }//End function
    /**
    * Update users deviceid and auth token while login
    */
    function checkDeviceToken($table = 'admin'){
        $sql = $this->db->select('id')->where('deviceToken', $deviceToken)->get($table);
        if($sql->num_rows()){
            $id = array();
            foreach($sql->result() as $result){
                $id[] = $result->id;
            }
            $this->db->where_in('id', $id);
            $this->db->update('users',array('deviceToken'=>''));

            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
        return true;
    }//End function
	/*
	Function for check provided token is resultid or not
	*/
	function isValidToken($authToken,$table = 'admin')
	{
        $this->db->select('*');
        $this->db->where('authToken',$authToken);
        $sql = $this->db->get($table);
        //echo $this->db->last_query();die;
        if($sql->num_rows() > 0)
        {
            return $sql->row();
        }
		return false;
	}//end function
	function registration($user)
	{	
        $checkEmail = $this->db->select('*')->where(array('email'=>$user['email']))->get(ADMIN);
        if($checkEmail->num_rows()){
            return array('regType'=>'AE'); //already exist
        }else{
            $this->db->insert(ADMIN,$user);
            $lastId = $this->db->insert_id();
            if($lastId):
                return array('regType'=>'NR','returnData'=>$this->userInfo(array('id' => $lastId)));
                // Normal registration
            endif;
        }
        return false;	
	} //End Function users Register
	function updateDeviceIdToken($id,$authToken,$table =ADMIN)
	{
		$req = $this->db->select('id')->where('id',$id)->get($table);
		if($req->num_rows())
		{
            $this->db->update($table,array('authToken'=>$authToken),array('id'=>$id));
            return TRUE;
		}
		return FALSE;
	}//End Function Update Device Token      
    //get user info
	function userInfo($where){
        $userPath    = base_url().'uploads/admin/thumb/';
        $userDefault = base_url().USER_DEFAULT_AVATAR;
        $this->db->select('id,
            id as userId,
            fullName,
            email,
            authToken,
            userType,
            (case when (profileImage = "") 
            THEN "'.$userDefault.'" ELSE
            concat("'.$userPath.'",profileImage) 
            END) as profileImage,
            (case when (userType = 1) 
            THEN "Super Admin" when (userType = 2) 
            THEN "Customer" when (userType = 3) 
            THEN "Employee" ELSE
            "Unknown" 
            END) as userRole');
        $this->db->from(ADMIN);
        $this->db->where($where);
        $sql= $this->db->get();

        if($sql->num_rows()):
            return $sql->row();
        endif;
        return false;
    } //End Function usersInfo
	function login($data,$authToken){
        $res = $this->db->select('*')->where(array('email'=>$data['email']))->get(ADMIN);
        if($res->num_rows()){
            $result = $res->row();
            if($result->status == 1)
            {
                //verify password- It is good to use php's password hashing functions so we are using password_verify fn here
                if(password_verify($data['password'], $result->password)){
                    $updateData = $this->updateDeviceIdToken($result->id,$authToken);
                    if($updateData){
                       return array('returnType'=>'SL','userInfo'=>$this->userInfo(array('id'=>$result->id)));
                    }else{
                        return FALSE;
                    } 
                }else{
                    return array('returnType'=>'WP'); // Wrong Password
                }
            }
            return array('returnType'=>'WS');
                // InActive
        }else {
            return array('returnType'=>'WE'); // Wrong Email
        }
    }//End users Login
    function forgotPassword($email)
    {
        $sql = $this->db->select('id,fullName,email,password,passToken')->where(array('email'=>$email))->get(ADMIN);
        if($sql->num_rows())
        {
            $result             = $sql->row();
            $useremail          = $result->email;
            $passToken          = $result->passToken;
            $data['full_name']  = $result->fullName;
            // Check for social id
            /*  if(!empty($result->socialId)){
                return  array('emailType'=>'SL' ); //SL social login
            }*/
            $encoding_email     = encoding($useremail);
            $data['url']        = base_url().'password/ChangePassword/change_password/'.$encoding_email.'/'.$passToken;
            $message            = $this->load->view('emails/forgot_password',$data,TRUE);
            $subject            = "Forgot Password";
             $this->load->library('smtp_email');
            $response = $this->common_model->newsendMemberMail($useremail,$subject,$message);
            //$response=$this->smtp_email->send_mail($useremail,$subject,$message); // Send email For Forgot password
            // echo "<pre>";
            // print_r($response);
            // die;
            $headers = "MIME-Version: 1.0" . "\r\n"; 
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
            $headers .= "From: rathoreankit582@gmail.com" . "\r\n" . "CC: rathoreankit582@gmail.com";
            $response = mail($useremail,$subject,$message,$headers);
            
            if ($response)
            {  
                return  array('emailType'=>'ES','link' ); //ES emailSend
            }else{ 
                return  array('emailType'=>'NS') ; //NS NotSend
            }
        }else{
            return  array('emailType'=>'NE') ; //NE Not exist
        }
    } //End funtion       
}//ENd Class
?>