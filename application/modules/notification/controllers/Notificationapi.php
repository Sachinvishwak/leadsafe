<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notificationapi extends REST_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
    }
    
    function notificationinsert_post()
    {
         
		$data=array( $userData['id']           =   $this->post('id'),
			         $userData['user_id']      =   $this->post('user_id'),
			         $userData['name']           =   $this->post('name'),
			         $userData['title']      =   $this->post('title'),
			         $userData['link']           =   $this->post('link'),
			         $userData['is_seen']      =   $this->post('is_seen'),
			         $userData['type']      =   $this->post('type'));

         
         	$this->db->insert('notification', $userData);
          
          if($this->db->affected_rows() > 0)
		  {
		    echo json_encode('Notification inserted', true);
		  }
		  else
		  {
		   echo json_encode('notification not insert', false);
		  }
         
    }


    function notificationupdate_post()
    {
         
					 $userData['id']           =   $this->post('id');
			         $userData['user_id']      =   $this->post('user_id');
			         $userData['name']           =   $this->post('name');
			         $userData['title']      =   $this->post('title');
			         $userData['link']           =   $this->post('link');
			         $userData['is_seen']      =   $this->post('is_seen');
			         $userData['type']      =   $this->post('type');

         	$this->db->where('id',$this->input->post('id'));
         	$result = $this->db->update('notification',$userData);
            $msg = 'Notification Details Updated Successfully';
            $error = '!!!NOT Updated !!';

            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg);
            }else{
                $response              = array('status'=>FAIL,'message'=>$error);
            }        
            $this->response($response);
         
    }

    function notificationdelete_post()

    {
    	  if($this->post('id'))
    	  {	
	    	  $this->db->where('id', $this->post('id'));
			  $result =	 $this->db->delete('notification');
			  $msg = 'Notification Delete Successfully';
              $error = '!!!Data not deleted !!';

	            if($result){
	                $response              = array('status'=>SUCCESS,'message'=>$msg);
	            }else{
	                $response              = array('status'=>FAIL,'message'=>$error);
	            }        
	            $this->response($response);

  		}
  }
  
  function listnotification_post()
  {
    

    $data = $this->db->get('notification')->result();

  	$response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Project List Get Succesfully');
    $this->response($response);
  }
  
  function notificationdetail_post()
  {
  		
  	
  		$notification_id = $this->post('id');
        $user_id = $this->post('user_id');
        $data = $this->db->get_where('notification',array('id'=>$notification_id,'user_id'=>$user_id))->result();
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'data'=>$data,'message'=>'Notification Not Exist');
        }else{
           
            $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Notification Detail Get Succesfully');
        }
        
        $this->response($response);
  }
  

}//End Class