<?php
/**
* Web service model
* Handles web service request
* version: 2.0 ( 14-08-2018 )
*/
class Api_model extends CI_Model {
	
    /**
     * Generate auth token for API users
     * Modified in version 2.0
    */
    function generate_token(){
        $this->load->helper('security');
        $res = do_hash(time().mt_rand());
        $new_key = substr($res,0,config_item('rest_key_length'));
        return $new_key;
    }//End Function
    /**
    * Update users deviceid and auth token while login
    */
    function checkDeviceToken($deviceToken,$table = 'users'){
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
    }//End Function
	/*
	Function for check provided token is resultid or not
	*/
	function isValidToken($authToken,$table = 'users')
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
	}//End Function
	function registration($user)
	{	
        $checkEmail = $this->db->select('*')->where(array('email'=>$user['email']))->get(USERS);
        if($checkEmail->num_rows()){
            return array('regType'=>'AE'); //already exist
        }else{
            $this->db->insert(USERS,$user);
            $lastId = $this->db->insert_id();
            if($lastId):
                return array('regType'=>'NR','returnData'=>$this->userInfo(array('id' => $lastId)));
                // Normal registration
            endif;
        }
        return false;	
	} //End Function users Register
	function updateDeviceIdToken($id,$deviceType,$deviceToken,$authToken,$table = 'users')
	{
		$req = $this->db->select('id')->where('id',$id)->get($table);
		if($req->num_rows())
		{
            $this->checkDeviceToken($deviceToken);
            $this->db->update($table,array('authToken'=>$authToken,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken),array('id'=>$id));
            return TRUE;
		}
		return FALSE;
	}//End Function Update Device Token  
    //get user info
	function userInfo($where){
        $userPath    = base_url().USER_AVATAR_PATH;
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
                        THEN "Customer" when (userType = 2) 
                        THEN "Driver" when (userType = 3) 
                        THEN "Employee" ELSE
                        "Unknown" 
                        END) as userRole');
        $this->db->from(USERS);
        $this->db->where($where);
        $sql= $this->db->get();

        if($sql->num_rows()):
            return $sql->row();
        endif;
        return false;
    } //End Function usersInfo
	function login($data,$authToken){
        $res = $this->db->select('*')->where(array('email'=>$data['email']))->get('users');
        //lq();
        if($res->num_rows()){
            $result = $res->row();
            if($result->status == 1)
            {
                //verify password- It is good to use php's password hashing functions so we are using password_verify fn here
                if(password_verify($data['password'], $result->password)){
                    $deviceType         = $data['deviceType'];
                    $deviceToken        = $data['deviceToken'];
                    $updateData         = $this->updateDeviceIdToken($result->id,$deviceType,$deviceToken,$authToken);
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
        }else{
            return array('returnType'=>'WE'); // Wrong Email
        }
    }//End users Login
    function forgotPassword($email)
    {
        $sql = $this->db->select('id,fullName,email,password,passToken')->where(array('email'=>$email))->get(USERS);
        if($sql->num_rows())
        {
            $result             = $sql->row();
            $useremail          = $result->email;
            $passToken          = $result->passToken;
            $data['full_name']  = $result->fullName;
            $encoding_email     = encoding($useremail);
            $data['url']        = base_url().'password/ChangePassword/change_password/'.$encoding_email.'/'.$passToken;
            $message            = $this->load->view('emails/forgot_password',$data,TRUE);
            $subject            = "Forgot Password";
            $this->load->library('smtp_email');
            $response=$this->smtp_email->send_mail($useremail,$subject,$message); // Send email For Forgot password
            if ($response)
            {  
                return  array('emailType'=>'ES' ); //ES emailSend
            }else{ 
                return  array('emailType'=>'NS') ; //NS NotSend
            }
        }else{
            return  array('emailType'=>'NE') ; //NE Not exist
        }
    } //End funtion       
    
    
    // login for all users
    function user_login($table_name,$data,$authToken,$role,$foldername){
        $res = $this->db->select('*')->where(array('email'=>$data['email']))->get($table_name);
        //lq();
        if($res->num_rows()){
            $result = $res->row();
            if(password_verify($data['password'], $result->password)){
                //$deviceType         = $data['deviceType'];
                //$deviceToken        = $data['deviceToken'];
                //$updateData         = $this->updateDeviceIdToken($result->id,$deviceType,$deviceToken,$authToken);
                // if($updateData){
				$licenseId = 0;
                $personName = "";
                if(isset($result->company_id))
                {
					$licenseId = $result->company_id;
                    $result->company_id = $result->company_id;
                    $this->db->update($table_name,array('authToken'=>$authToken),array('company_id'=>$result->company_id));
                }else{
					$result->company_id = 0;
					$licenseId = $result->id;
                }
                if(isset($result->id))
                {
                    $this->db->update($table_name,array('authToken'=>$authToken),array('id'=>$result->id));
                    $result->id = $result->id;
                }else{
                    $result->id = "0";
                }
                if(isset($result->company_name))
                {
                    $result->company_name = $result->company_name;
                }else{
                    $result->company_name = "";
                }
                if(isset($result->owner_first_name))
                {
                    $result->owner_first_name = $result->owner_first_name;
                    $personName = $result->owner_first_name;
                }else{
                    $result->owner_first_name = "";
                }
                if(isset($result->owner_last_name))
                {
                    $result->owner_last_name = $result->owner_last_name;
                }else{
                    $result->owner_last_name = "";
                }
                if(isset($result->name))
                {
                    $result->name = $result->name;
                }else{
                    $result->name = $personName;
                }
                if(isset($result->profile_photo))
                {
                    $result->profileImage = base_url('uploads/').$foldername.'/'.$result->profile_photo;
                }else{
                    $result->profileImage = "";
                }
                if(isset($result->pwd))
                {
                    $result->pwd = $result->pwd;
                }else{
                    $result->pwd = "";
                }
                if(isset($result->address))
                {
                    $result->address = $result->address;
                }else{
                    $result->address = "";
                }
                if(isset($result->userRole))
                {
                    $result->userRole = $result->userRole;
                }else{
                    $result->userRole = $role;
                }
                if(isset($result->document))
                {
                    $result->document = $result->document;
                }else{
                    $result->document = "";
                }
                if(isset($result->city))
                {
                    $result->city = $result->city;
                }else{
                    $result->city = "0";
                }
                if(isset($result->state))
                {
                    $result->state = $result->state;
                }else{
                    $result->state = "0";
                }
                if(isset($result->fax_number))
                {
                    $result->fax_number = $result->fax_number;
                }else{
                    $result->fax_number = "";
                }
                if(isset($result->licence))
                {
                    $result->licence = base_url('uploads/').$table_name.'/'.$result->licence;
                }else{
                    $result->licence = "";
				}
				
				$license_media = $this->db->get_where('license_media',array('type'=>$foldername,'user_id'=>$licenseId))->result();
				foreach($license_media as $licenses)
				{
					$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
				}
			
				$result->multiple_license = $license_media;

                if(isset($value->insurence_certificate))
                {
                    $result->insurence_certificate = base_url('uploads/').$table_name.'/'.$result->insurence_certificate;
                }else{
                    $result->insurence_certificate = "";
                }
                $result->authToken = $authToken;
                
                
                
                return array('returnType'=>'SL','userInfo'=>$result);
                // }else{
                //     return FALSE;
                // }     
            }else{
                return array('returnType'=>'WP'); // Wrong Password
            }
        }else{
            return array('returnType'=>'WE'); // Wrong Email
        }
    }//End users Login
    
    
}//ENd Class
?>
