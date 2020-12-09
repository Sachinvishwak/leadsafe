<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Companyapi_modal extends CI_Model {
     
    //var $table , $column_order, $column_search , $order =  '';
    var $table          = 'company';
    
    function registration($user)
    {   
        $checkEmail = $this->db->select('*')->where(array('email'=>$user['email']))->get('company');
        if($checkEmail->num_rows()){
            return array('regType'=>'AE'); //already exist
        }else{
            $this->db->insert('company',$user);
            $lastId = $this->db->insert_id();
            if($lastId):
                $returnData = $this->db->get_where('company',array('company_id'=>$lastId))->result();
                return array('regType'=>'NR','returnData'=>$returnData);
                // Normal registration
            endif;
        }
        return false;   
    } //

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

    function login($data,$authToken){
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

    function generate_token(){
        $this->load->helper('security');
        $res = do_hash(time().mt_rand());
        $new_key = substr($res,0,config_item('rest_key_length'));
        return $new_key;

    }


}//End Class