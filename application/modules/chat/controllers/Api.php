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
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_valid_email|valid_email|is_unique[users.email]',
            array('is_unique' => 'Email already exist')
        );
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]|max_length[20]');
        $this->form_validation->set_rules('contact', 'Contact Number', 'trim|required|min_length[10]|max_length[20]');
    $this->form_validation->set_rules('fullName', 'full Name', 'trim|required|regex_match[/^([a-z ])+$/i]|min_length[2]');
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
    
    
    function message_post(){
       
        if(isset($_POST['message']) && $_POST['message']!=""){
            $message = $this->input->post('message');
            $array = array(
                'message' => $message,
                'user_id' => $_SESSION['company_sess']['id'],
                'project_id' => $this->input->post('project_hidden_id'),
                'user_type' => $this->input->post('user_type'),
                'tag' => $this->input->post('tags_selected')
                
            );
            $this->db->insert('chat',$array);
            $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$_SESSION['company_sess']['id']);     
        }else{
            $attachment = $_FILES['attachment']['name'];
            $config['upload_path']= "./uploads/project/documents/";
            $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp|mp4";
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($attachment != "")
            {
                if($this->upload->do_upload('attachment'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $attachment= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, mp4 bmp format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
                
                $array = array(
                    'message' => '',
                    'user_id' => $_SESSION['company_sess']['id'],
                    'project_id' => $this->input->post('project_hidden_id'),
                    'user_type' => $this->input->post('user_type'),
                    'file_type' => 'docs',
                    'file' => $attachment,
                    'tag' => $this->input->post('tags_selected'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated-at' => date('Y-m-d H:i:s')                    
                );
                $this->db->insert('chat',$array);
                $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$_SESSION['company_sess']['id']); 
                
            }
        }
        
        $this->response($response);
    }
    //Notification
    function getNotificationcount_post(){
        $array = array(
            'reciever_id' => $this->input->post('reciever_id'),
            'is_seen' => 0
        );
        $count = $this->db->get_where('chat',$array)->num_rows();
        //$count = $this->db->get_where('chat',$array)->result();
        //$count = array_unique(array_column($count, 'user_id'));
        //$count = count($count);
        $response = array('status' => SUCCESS, 'message' => 'Message Get Successfully','data'=>$count); 
        $this->response($response);
    }
    function isseenNotification_post(){
        $id = $this->post('reciever_id');
      
        $array = array(
            'is_seen' => 1
        );
        $this->db->where('reciever_id',$id);
        
        $this->db->update('chat',$array);
        
        $response = array('success'=>true,'message'=>' Successfully');
        $this->response($response);
    }
    //end Notificaiton
    //getmessages
    function getmessagesold_post(){
        //die;
        $array = array(
            'project_id' => $this->input->post('project_hidden_id')
        );
        $response = $this->db->get_where('chat',$array)->result();
	    $message_html = '';
        foreach($response as $value){
            if($value->user_id == $_SESSION['company_sess']['id']){
                $class = 'sent';
            }else{
                $class = 'replies';
            }
            $tag_html = "";
            if($value->tag !== NULL)
            {
                $tasks_data = $this->db->get_where('tasks',array('taskId'=>$value->tag))->result();
                if(!isset($tasks_data[0])){
                    
                }else{
                    $tag_name = $tasks_data[0]->name;
                    $tag_html = '<span class="badge badge-success">'.$tag_name.'</span>';    
                }
                
            }
            if($value->file_type=="docs")
            {
                $mediapath = base_url('uploads/project/documents/').$value->file;
			    if(@is_array(getimagesize($mediapath))){
                    $image = 1;
                } else {
                    $image = 0;
                }
                
                
                if($image == 0)
			    {
			        $message_html .= '<li class="sent">
					<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" /><object style="width:100%; overflow:hidden;" src="'.$mediapath.'"><iframe style="width:450px;height:400px;" src="https://docs.google.com/viewer?url='.$mediapath.'&embedded=true"></iframe></object>
					    '.$tag_html.'
					</li>';
					
			    }else{
			        $message_html .= '<li class="sent">
					<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" /><img style="width:300px;" src="'.$mediapath.'"/>
					    '.$tag_html.'
					</li>';
			        
			    }
                
            }else{
                $message_html .= '<li class="sent">
					<img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" /><p>'.$value->message.'</p>'.$tag_html.'</li>';    
            }
            
        }
        $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','messages'=>$message_html); 
        $this->response($response);
    }
    //get message new
    function getmessages_post(){
        //die;
        $array = array(
            'project_id' => $this->input->post('project_hidden_id')
        );
        $response = $this->db->get_where('chat',$array)->result();
	    $message_html = '';
	    $top = 18;
        foreach($response as $value){
            $tag_html = "";
            if($value->tag !== NULL)
            {
                $tasks_data = $this->db->get_where('tasks',array('taskId'=>$value->tag))->result();
                if(!isset($tasks_data[0])){
                    
                }else{
                    $tag_name = $tasks_data[0]->name;
                    $tag_html = '<span class="badge badge-success">'.$tag_name.'</span>';    
                }
                
            }
            if($value->file_type=="docs")
            {
                $mediapath = base_url('uploads/project/documents/').$value->file;
			    if(@is_array(getimagesize($mediapath))){
                    $image = 1;
                    $link = $mediapath;
                } else {
                    $image = 0;
                    $link = 'https://docs.google.com/viewer?url='.$mediapath.'&embedded=true';
                }
                $message_html .= '<div style="top:'.$top.'%" id="message-area"><div id="message-one"><div id="container"><div class="row"><div class="col-xs-6 col-md-3"><a href="#" class="person-one thumbnail"><img class="person-img-one" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/827672/pexels-photo-119705%20(1).jpeg"></a></div></div></div><div id="container"><h4 id="person-name"> Jsmith <span id="time">'.date('D M Y h:i:a',strtotime($value->created_at)).'</span> </h4><h5 id="person-text"><a  target="_blank" href="'.$link.'">'.$tag_html.'attahment file<span style="color:black;">'.$value->file.'</span></a></h5></div></div></div>';
                $top = $top + 12;
                
            }else{
                $message_html .= '<div style="top:'.$top.'%" id="message-area"><div id="message-one"><div id="container"><div class="row"><div class="col-xs-6 col-md-3"><a href="#" class="person-one thumbnail"><img class="person-img-one" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/827672/pexels-photo-119705%20(1).jpeg"></a></div></div></div><div id="container"><h4 id="person-name"> Jsmith <span id="time">'.date('D M Y h:i:a',strtotime($value->created_at)).'</span> </h4><h5 id="person-text">'.$value->message.'</h5></div></div></div>';
                $top = $top + 12;
            }
        }
        $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','messages'=>$message_html); 
        $this->response($response);
    }
    
    
    
    // latest
    function sendMessageToDatabase_post(){
        
        if(isset($_POST['message']) && $_POST['message']!=""){
            $message = $this->input->post('message');
            $array = array(
                'message' => $message,
                'user_id' => $_SESSION['company_sess']['id'],
                'project_id' => $this->input->post('project_hidden_id'),
                'user_type' => $this->input->post('user_type'),
                'tag' => $this->input->post('tags_selected'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated-at' => date('Y-m-d H:i:s')                
            );
            $this->db->insert('chat',$array);
            $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$_SESSION['company_sess']['id']);     
        }else{
            $attachment = $_FILES['attachment']['name'];
            $config['upload_path']= "./uploads/project/documents/";
            $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp|mp4";
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($attachment != "")
            {
                if($this->upload->do_upload('attachment'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $attachment= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp, mp4 format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
                
                $array = array(
                    'message' => '',
                    'user_id' => $_SESSION['company_sess']['id'],
                    'project_id' => $this->input->post('project_hidden_id'),
                    'user_type' => $this->input->post('user_type'),
                    'file_type' => 'docs',
                    'file' => $attachment,
                    'tag' => $this->input->post('tags_selected'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated-at' => date('Y-m-d H:i:s')                    
                );
                $this->db->insert('chat',$array);
                $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$_SESSION['company_sess']['id']); 
                
            }
        }
        
        $this->response($response);
    }
    
    // latest task caht
    function sendMessageToDatabaseTask_post(){
        
        if(isset($_POST['message']) && $_POST['message']!=""){
            $message = $this->input->post('message');
            $array = array(
                'message' => $message,
                'user_id' => $_SESSION['company_sess']['id'],
                'task_id' => $this->input->post('task_hidden_id'),
                'user_type' => $this->input->post('user_type'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated-at' => date('Y-m-d H:i:s')            );
            $this->db->insert('chat',$array);
        }
        else{
            $attachment = $_FILES['attachment']['name'];
            $config['upload_path']= "./uploads/project/documents/";
            $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp|mp4";
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($attachment != "")
            {
                if($this->upload->do_upload('attachment'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $attachment= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp, mp4 format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
                $array = array(
                    'message' => '',
                    'user_id' => $_SESSION['company_sess']['id'],
                    'user_type' => $this->input->post('user_type'),
                    'file_type' => 'docs',
                    'file' => $attachment,
                    'task_id' => $this->input->post('task_hidden_id'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated-at' => date('Y-m-d H:i:s')                    
                );
                $this->db->insert('chat',$array);
                $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$_SESSION['company_sess']['id']); 
            }
        }
        $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$_SESSION['company_sess']['id']);     
        $this->response($response);
	}

	// multiple docs upload
    public function multiTaskDocsUpload_post()
    {
        $allfilenameArr = array();
        $imageArr =  $_POST['attachment'];
        foreach($imageArr as $imageAr)
        {
            $main = json_decode($imageAr);
            foreach($main as $mains)
            {
                $imgdata = base64_decode($mains->encoded);
                $f = finfo_open();
                $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
                $split = explode( '/', $mime_type);
                $type = $split[1]; 
                $image = base64_decode($mains->encoded);
                $image_name = "myfile_".time().'-'.mt_rand();
                $filename = $image_name . '.' . $type;
                $path = "uploads/project/documents/".$filename;
                file_put_contents($path.$filename, $image);
                array_push($allfilenameArr,$filename.$filename);
            }
        }
        $user_id = $this->input->post('user_id');
        foreach($allfilenameArr as $allfilename)
        {
            $filename = $allfilename;
            $array = array(
				'message' => '',
				'user_id' => $user_id,
				'task_id' => $this->input->post('task_hidden_id'),
				'user_type' => $this->input->post('user_type'),
				'file_type' => 'docs',
				'file' => $allfilename,
				'created_at' => date('Y-m-d H:i:s'),
				'updated-at' => date('Y-m-d H:i:s')                
			);
			$this->db->insert('chat',$array);
        }
        $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully');
        $this->response($response);
    }
	
	function sendMessageToDatabaseTaskMultiDocs_post(){
		$countfiles = count($_FILES['attachment']['name']);
		for($i=0;$i<$countfiles;$i++){
			if(!empty($_FILES['attachment']['name'][$i])){
			  $_FILES['file']['name'] = $_FILES['attachment']['name'][$i];
			  $_FILES['file']['type'] = $_FILES['attachment']['type'][$i];
			  $_FILES['file']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
			  $_FILES['file']['error'] = $_FILES['attachment']['error'][$i];
			  $_FILES['file']['size'] = $_FILES['attachment']['size'][$i];
			  $config['upload_path']= "./uploads/project/documents/";
              $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp|mp4";
              $config['encrypt_name'] = FALSE;
			  $this->load->library('upload',$config);
			  if($this->upload->do_upload('file')){
				$uploadData = $this->upload->data();
				$filename = $uploadData['file_name'];
				$array = array(
					'message' => '',
					'user_id' => $_SESSION['company_sess']['id'],
					'user_type' => $this->input->post('user_type'),
					'file_type' => 'docs',
					'file' => $filename,
					'task_id' => $this->input->post('task_hidden_id'),
					'created_at' => date('Y-m-d H:i:s'),
					'updated-at' => date('Y-m-d H:i:s')                    
				);
				$this->db->insert('chat',$array);
			  }
			}
		}

        $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$_SESSION['company_sess']['id']);     
        $this->response($response);
    }
    
    function getmessagesFromDatabase_post()
    {
        $array = array(
            'project_id' => $this->input->post('project_hidden_id')
        );
        $response = $this->db->get_where('chat',$array)->result();
        $this->response($response);
    }
    
    function to_time_ago( $time ) { 
      
    // Calculate difference between current 
    // time and given timestamp in seconds 
    $diff = time() - $time; 
      
    if( $diff < 1 ) {  
        return 'less than 1 second ago';  
    } 
      
    $time_rules = array (  
                12 * 30 * 24 * 60 * 60 => 'year', 
                30 * 24 * 60 * 60       => 'month', 
                24 * 60 * 60           => 'day', 
                60 * 60                   => 'hour', 
                60                       => 'minute', 
                1                       => 'second'
    ); 
  
    foreach( $time_rules as $secs => $str ) { 
          
        $div = $diff / $secs; 
  
        if( $div >= 1 ) { 
              
            $t = round( $div ); 
              
            return $t . ' ' . $str .  
                ( $t > 1 ? 's' : '' ) . ' ago'; 
        } 
    } 
} 
    
    // get message for final design
    function getsentmessages_post(){
        $array = array(
            'project_id' => $this->input->post('project_hidden_id')
        );
        $search_for_task_chat_docs = $this->input->post('search_for_task_chat_docs');
        $search_for_task_chat = $this->input->post('search_for_task_chat');
        if($search_for_task_chat_docs != -1 && $search_for_task_chat == ""){
            $array = array(
                'project_id' => $this->input->post('project_hidden_id'),
                'tag' => $search_for_task_chat_docs
            );
            $response = $this->db->order_by('created_at','asc')->get_where('chat',$array)->result();
        }else if($search_for_task_chat_docs == -1 && $search_for_task_chat != ""){
            $keyword = $search_for_task_chat;
            $this->db->select('*');
            $this->db->from('chat');
            $this->db->where('project_id',$this->input->post('project_hidden_id'));
            $this->db->group_start();
            $this->db->like('file', $keyword);
            $this->db->or_like('message', $keyword);
            $this->db->group_end();
            $response = $this->db->get()->result();
        }else if($search_for_task_chat_docs != -1 && $search_for_task_chat != "")
        {
            $keyword = $search_for_task_chat;
            $this->db->select('*');
            $this->db->from('chat');
            $this->db->where('project_id',$this->input->post('project_hidden_id'));
            $this->db->where('tag',$search_for_task_chat_docs);
            $this->db->like('file', $keyword);
            $response = $this->db->get()->result();
        }else
        {
            $response = $this->db->order_by('created_at','asc')->get_where('chat',$array)->result();
        }
	    $message_html = '';
	    $top = 18;
	    $z=1;
	    $x=1;
        foreach($response as $key =>$value){
            $tag_html = "";
            if($value->tag !== NULL)
            {
                $tasks_data = $this->db->get_where('tasks',array('taskId'=>$value->tag))->result();
                if(!isset($tasks_data[0])){
                    
                }else{
                    $tag_name = $tasks_data[0]->name;
                    $tag_html = '<span class="badge badge-success" style="background:green;">'.$tag_name.'</span>';    
                }
                
            }
            $user_image = 'https://ptetutorials.com/images/user-profile.png';
            $user_name = "";
            if($value->user_type == "company")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('company',array('company_id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/company/').$companyData->profile_photo;
                        $user_name = $companyData->name;
                    }
                }
            }else if($value->user_type == "leadcontractor" || $value->user_type == "subcontractor"){
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('contractor',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $user_name = $companyData->owner_first_name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/contractor/').$companyData->profile_photo;
                    }
                }
            }else if($value->user_type == "crew")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('crew_member',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/crew/').$companyData->profile_photo;
                    }
                }
            }else if($value->user_type == "client")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('client',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/client/').$companyData->profile_photo;
                    }
                }
            }
            
            
            if($value->file_type=="docs")
            {
                $mediapath = base_url('uploads/project/documents/').$value->file;
			    if(@is_array(getimagesize($mediapath))){
                    $image = 1;
                    $link = $mediapath;
                } else {
                    $image = 0;
                    $link = 'https://docs.google.com/viewer?url='.$mediapath.'&embedded=true';
                }
                $newLink = $mediapath;
                $time= $this->time_elapsed_string($value->created_at);
                if ($key > 0) {
                    $prev_time= $this->time_elapsed_string($response[$key-1]->created_at);
                    if($time==$prev_time){
                        $x=0;
                    }else{
                       $x=1;        
                    }
                }
                
                if($x==1){
                    $z++;
                    $message_html.='<div class="col-md-12"><div class="col-md-5"><hr style="border-top: 1px solid #5a4a4a;"></div><div class="col-md-1" style="padding: 4px 0px 0px 0px;margin-left: 65px;">'.$time.'</div><div class="col-md-5"><hr style="border-top: 1px solid #5a4a4a;"></div></div>';
                   
                        }
                        
                $onclick = "window.open('".$newLink."', '_blank')";
                $message_html .= '<div class="custom-file" onclick="'.$onclick.'"><span><img style="max-width:100px;;"src="'.base_url().'/backend_assets/img/upload.png" alt="jacket"></span><span class="time_date">'.date('D M Y h:i:a',strtotime($value->created_at)).'</span><label >'.$value->file.'</label><label> &nbsp; '.$tag_html.'</label></div>';
            }else{
                if($value->user_id == $_SESSION['company_sess']['id'])
                {
                    $time = $this->time_elapsed_string($value->created_at);
                    if ($key > 0) {
                    $prev_time= $this->time_elapsed_string($response[$key-1]->created_at);
                    if($time==$prev_time){
                        $x=0;
                    }else{
                       $x=1;        
                    }
                }
                
                if($x==1){
                    $z++;
                    $message_html.='<div class="col-md-12"><div class="col-md-5"><hr style="border-top: 1px solid #5a4a4a;"></div><div class="col-md-1" style="padding: 4px 0px 0px 0px;margin-left: 65px;">'.$time.'</div><div class="col-md-5"><hr style="border-top: 1px solid #5a4a4a;"></div></div>';
                   
                        }
                    
                    $message_html .= '<div class="outgoing_msg_img"> <img style="width: 50px;height: 50px;border-radius: 50%;" src="'.$user_image.'" alt="'.$user_name.'">  <label class="Name_font" style="display: block;">'.$user_name.'</label></div><div class="outgoing_msg"><div class="outgoing_withd_msg"><div class="sent_msg"><p>'.$value->message.'</p></div><span class="time_date">'.date('D M Y h:i:a',strtotime($value->created_at)).'</span></div></div></div>';
                }else{
                    $message_html .= '<div class="incoming_msg"><div class="incoming_msg_img"> <img style="width: 50px;height: 50px;border-radius: 50%;margin-top: 26px;" src="'.$user_image.'" alt="'.$user_name.'"> <label class="Name_font" style="display: block;">'.$user_name.'</label> </div><div class="received_msg"><div class="received_withd_msg"><p>'.$value->message.'</p><span class="time_date">'.date('D M Y h:i:a',strtotime($value->created_at)).'</span></div></div></div>';
                }        
            }
        }
        $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','messages'=>$message_html); 
        $this->response($response);
    }
    
    
    //// get message for final design task
    function getsentmessagestask_post(){
        
        $search_for_task_chat = $this->input->post('search_for_task_chat');
        if($search_for_task_chat != "")
        {
            $keyword = $search_for_task_chat;
            $this->db->select('*');
            $this->db->from('chat');
            $this->db->where('task_id',$this->input->post('task_hidden_id'));
            $this->db->group_start();
            $this->db->like('file', $keyword);
            $this->db->or_like('message', $keyword);
            $this->db->group_end();
            $response = $this->db->get()->result();
        }else{
            $array = array(
                'task_id' => $this->input->post('task_hidden_id')
            );
            $response = $this->db->order_by('created_at','asc')->get_where('chat',$array)->result();
        }
        
        
	    $message_html = '';
	    $x=1;
	    $z=1;
        foreach($response as $key =>$value){
            $time = $this->time_elapsed_string($value->created_at);
            $user_image = 'https://ptetutorials.com/images/user-profile.png';
            $user_name = "";
            if($value->user_type == "company")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('company',array('company_id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/company/').$companyData->profile_photo;
                        $user_name = $companyData->name;
                    }
                }
            }
            $tag_html = "";
            
            if($value->file_type=="docs")
            {
                $mediapath = base_url('uploads/project/documents/').$value->file;
			    if(@is_array(getimagesize($mediapath))){
                    $image = 1;
                    $link = $mediapath;
                } else {
                    $image = 0;
                    $link = 'https://docs.google.com/viewer?url='.$mediapath.'&embedded=true';
                }
                $newLink = $mediapath;
                $onclick = "window.open('".$newLink."', '_blank')";
                if ($key > 0) {
                    $prev_time= $this->time_elapsed_string($response[$key-1]->created_at);
                    if($time==$prev_time){
                        $x=0;
                    }else{
                       $x=1;
                    }
                }
                 if($x==1){
                $z++;

                    $message_html .= '<div class="col-md-12"><div class="col-md-5"><hr style="border-top: 1px solid #5a4a4a;"></div><div class="col-md-1" style="padding: 4px 0px 0px 9px;">'.$time.'</div><div class="col-md-5"><hr style="border-top: 1px solid #5a4a4a;"></div></div>';

                }
                $message_html .= '<div class="custom-file" onclick="'.$onclick.'"><span><img style="max-width:100px;;"src="'.base_url().'/backend_assets/img/upload.png" alt="jacket"></span><span class="time_date">'.date('D M Y h:i:a',strtotime($value->created_at)).'</span><label >'.$value->file.'</label><label> &nbsp; '.$tag_html.'</label></div>';
                
            }else{        
                if($value->user_id == $_SESSION['company_sess']['id'])
                {
                    if ($key > 0) {
                    $prev_time= $this->time_elapsed_string($response[$key-1]->created_at);
                    if($time==$prev_time){
                        $x=0;
                    }else{
                       $x=1;
                    }
                }
                 if($x==1){
                $z++;

                    $message_html .= '<div class="col-md-12"><div class="col-md-5"><hr style="border-top: 1px solid #5a4a4a;"></div><div class="col-md-1" style="padding: 4px 0px 0px 9px;">'.$time.'</div><div class="col-md-5"><hr style="border-top: 1px solid #5a4a4a;"></div></div>';

                }
                    $message_html .= '<div class="outgoing_msg"><div class="outgoing_msg_img"> <img style="border-radius:50%;width:50px;height:50px;" src="'.$user_image.'" alt="'.$user_name.'">  <label class="Name_font" style="display: block";>'.$user_name.'</label></div><div class="outgoing_msg"><div class="outgoing_withd_msg"><div class="sent_msg"><p>'.$value->message.'</p></div><span class="time_date">'.date('D M Y h:i:a',strtotime($value->created_at)).'</span></div></div></div>';
                }else{
                    $message_html .= '<div class="incoming_msg"><div class="incoming_msg_img"> <img style="border-radius:50%;width: 50px;height: 50px;" src="'.$user_image.'" alt="'.$user_name.'"> <label class="Name_font" style="display: block";>'.$user_name.'</label> </div><div class="received_msg"><div class="received_withd_msg"><p>'.$value->message.'</p><span class="time_date">'.date('D M Y h:i:a',strtotime($value->created_at)).'</span></div></div></div>';
                }
            }
        
        }
        $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','messages'=>$message_html); 
        $this->response($response);
    }
    
    
    // This is for Api Project
    function getsentmessagesApi_post(){
        $array = array(
            'project_id' => $this->input->post('project_hidden_id')
        );
        
        $search_for_task_chat_docs = $this->input->post('search_for_task_chat_docs');
        $search_for_task_chat = $this->input->post('search_for_task_chat');
        
        if($search_for_task_chat_docs != -1 && $search_for_task_chat == ""){
            $array = array(
                'project_id' => $this->input->post('project_hidden_id'),
                'tag' => $search_for_task_chat_docs
            );
            $response = $this->db->order_by('created_at','asc')->get_where('chat',$array)->result();
        }else if($search_for_task_chat_docs == -1 && $search_for_task_chat != ""){
            $keyword = $search_for_task_chat;
            $this->db->select('*');
            $this->db->from('chat');
            $this->db->where('project_id',$this->input->post('project_hidden_id'));
            $this->db->group_start();
            $this->db->like('file', $keyword);
            $this->db->or_like('message', $keyword);
            $this->db->group_end();
            $response = $this->db->get()->result();
        }else if($search_for_task_chat_docs != -1 && $search_for_task_chat != "")
        {
            $keyword = $search_for_task_chat;
            $this->db->select('*');
            $this->db->from('chat');
            $this->db->where('project_id',$this->input->post('project_hidden_id'));
            $this->db->where('tag',$search_for_task_chat_docs);
            $this->db->group_start();
            $this->db->like('file', $keyword);
            $this->db->or_like('message', $keyword);
            $this->db->group_end();
            $response = $this->db->get()->result();
        }else
        {
            $response = $this->db->order_by('created_at','asc')->get_where('chat',$array)->result();
        }
        
        foreach($response as $value)
        {
            $tag_name = "";
            if($value->tag !== NULL)
            {
                $tasks_data = $this->db->get_where('tasks',array('taskId'=>$value->tag))->result();
                if(!isset($tasks_data[0])){
                    
                }else{
                    $tag_name = $tasks_data[0]->name;
                }
                
            }
            $value->tag_name = $tag_name;
            $user_image = 'https://ptetutorials.com/images/user-profile.png';
            if($value->user_type == "company")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('company',array('company_id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/company/').$companyData->profile_photo;
                    }
                    $value->user_image = $user_image;
                }
            }else if($value->user_type == "leadcontractor" || $value->user_type == "subcontractor"){
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('contractor',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->owner_first_name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/contractor/').$companyData->profile_photo;
                    }
                    $value->user_image = $user_image;
                }
            }else if($value->user_type == "crew")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('crew_member',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image  = base_url('uploads/crew/').$companyData->profile_photo;
                    }
                    $value->user_image = $user_image;
                }
            }else if($value->user_type == "client")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('client',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image  = base_url('uploads/client/').$companyData->profile_photo;
                    }
                    $value->user_image = $user_image;
                }
            }
            
            if($value->file_type == "docs")
            {
                $value->file_name = $value->file;
                $value->file = base_url('uploads/project/documents/').$value->file;
            }else{
                $value->file_name = "";
            }
        }
        
        $response = array('status' => SUCCESS, 'message' => 'Message Retrive Successfully','chat'=>$response); 
        $this->response($response);
    }
    
    // This is For Api Task
    function getsentmessagestaskApi_post(){
        $array = array(
            'task_id' => $this->input->post('task_hidden_id')
        );
        
        $search_for_task_chat = $this->input->post('search_for_task_chat');
        
        if($search_for_task_chat != ""){
            $keyword = $search_for_task_chat;
            $this->db->select('*');
            $this->db->from('chat');
            $this->db->where('task_id',$this->input->post('task_hidden_id'));
            $this->db->group_start();
            $this->db->like('file', $keyword);
            $this->db->or_like('message', $keyword);
            $this->db->group_end();
            $response = $this->db->get()->result();
        }else
        {
            $response = $this->db->order_by('created_at','asc')->get_where('chat',$array)->result();
        }
        
        foreach($response as $value)
        {
            $tag_name = "";
            if($value->tag !== NULL)
            {
                $tasks_data = $this->db->get_where('tasks',array('taskId'=>$value->tag))->result();
                if(!isset($tasks_data[0])){
                    
                }else{
                    $tag_name = $tasks_data[0]->name;
                }
                
            }
            $value->tag_name = $tag_name;
            $user_image = 'https://ptetutorials.com/images/user-profile.png';
            if($value->user_type == "company")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('company',array('company_id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    if($companyData->profile_photo != NULL)
                    {
                        $value->user_image = base_url('uploads/company/').$companyData->profile_photo;
                        $value->user_name = $companyData->name;
                    }
                }
            }else if($value->user_type == "leadcontractor" || $value->user_type == "subcontractor"){
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('contractor',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->owner_first_name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/contractor/').$companyData->profile_photo;
                    }
                    $value->user_image = $user_image;
                }
            }else if($value->user_type == "crew")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('crew_member',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image  = base_url('uploads/crew/').$companyData->profile_photo;
                    }
                    $value->user_image = $user_image;
                }
            }else if($value->user_type == "client")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('client',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image  = base_url('uploads/client/').$companyData->profile_photo;
                    }
                    $value->user_image = $user_image;
                }
            }

           if($value->file_type == "docs")
            {
                $value->file_name = $value->file;
                $value->file = base_url('uploads/project/documents/').$value->file;
            }else{
                $value->file_name = "";
            }
        }
        
        $response = array('status' => SUCCESS, 'message' => 'Message Retrive Successfully','chat'=>$response); 
        $this->response($response);
    }
    
    // Api For Send Message For Project
    function sendMessageToDatabaseApi_post(){
      
        $user_id = $_POST['user_id'];
        $attachment = "";
        if(isset($_FILES['attachment']))
        {
            $attachment = $_FILES['attachment']['name'];   
        }
        if($attachment != "")
        {
            $attachment = $_FILES['attachment']['name'];
            $config['upload_path']= "./uploads/project/documents/";
            $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp|mp4";
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($this->upload->do_upload('attachment'))
            {
                $data = array('upload_data' => $this->upload->data());
                $attachment= $data['upload_data']['file_name'];
            }else{
                $error = $this->upload->display_errors(); 
                $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp, mp4 format files.';
                $response              = array('status'=>FAIL,'message'=>$error);
                $this->response($response);
                die;
            }
            $array = array(
                    'message' => '',
                    'user_id' => $user_id,
                    'project_id' => $this->input->post('project_hidden_id'),
                    'user_type' => $this->input->post('user_type'),
                    'file_type' => 'docs',
                    'file' => $attachment,
                    'tag' => $this->input->post('tags_selected'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated-at' => date('Y-m-d H:i:s')                );
            $this->db->insert('chat',$array);
            $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$user_id);
        }else{
            $message = $this->input->post('message');
            $array = array(
                'message' => $message,
                'user_id' => $user_id,
                'project_id' => $this->input->post('project_hidden_id'),
                'user_type' => $this->input->post('user_type'),
                'tag' => $this->input->post('tags_selected'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated-at' => date('Y-m-d H:i:s')            );
            $this->db->insert('chat',$array);
            $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$user_id);     
        }
        $this->response($response);
    }
    
    // Api For SEnd Task
    function sendMessageToDatabaseTaskApi_post(){
     
        $user_id = $_POST['user_id'];
        $attachment = "";
        if(isset($_FILES['attachment']))
        {
            $attachment = $_FILES['attachment']['name'];
        }else{
            $attachment = "";
        }
        if($attachment != "")
        {
            $attachment = $_FILES['attachment']['name'];
            $config['upload_path']= "./uploads/project/documents/";
            $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp|mp4";
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            if($this->upload->do_upload('attachment'))
            {
                $data = array('upload_data' => $this->upload->data());
                $attachment= $data['upload_data']['file_name'];
            }else{
                $error = $this->upload->display_errors(); 
                $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp, mp4 format files.';
                $response              = array('status'=>FAIL,'message'=>$error);
                $this->response($response);
                die;
            }
            $array = array(
                    'message' => '',
                    'user_id' => $user_id,
                    'task_id' => $this->input->post('task_hidden_id'),
                    'user_type' => $this->input->post('user_type'),
                    'file_type' => 'docs',
                    'file' => $attachment,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated-at' => date('Y-m-d H:i:s')                );
            $this->db->insert('chat',$array);
            $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$user_id);
        }else{
            $message = $this->input->post('message');
            $array = array(
                'message' => $message,
                'user_id' => $user_id,
                'task_id' => $this->input->post('task_hidden_id'),
                'user_type' => $this->input->post('user_type'),
                'file_type' => '',
                'file' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated-at' => date('Y-m-d H:i:s')            );
            $this->db->insert('chat',$array);
            $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully','session'=>$user_id);     
        }
        $this->response($response);
    }
    
    // Api for single message send
    
    function sendMessageToDatabaseSingle_post(){
       
        $user_id = $_POST['user_id'];
		$reciever_id = $_POST['reciever_id'];
		
		$group_id = $_POST['group_id'];

        $message = $this->input->post('message');
        $reciever_type = $this->input->post('reciever_type');
        $file = "";
        $file_type = "";
        
        if(isset($_POST['file_status']))
        {
            $attachment = $_FILES['attachment']['name'];
            $config['upload_path']= "./uploads/project/documents/";
            $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp|mp4";
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            $file_type = 'docs';
            
            if(isset($_FILES['attachment']))
            {
                if($this->upload->do_upload('attachment'))
                {
                    $data = array('upload_data' => $this->upload->data());
                    $file= $data['upload_data']['file_name'];
                }else{
                    $error = $this->upload->display_errors(); 
                    $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp, mp4 format files.';
                    $response              = array('status'=>FAIL,'message'=>$error);
                    $this->response($response);
                    die;
                }
            }else{
                $file = "";
            }
        }
        
        $array = array(
            'message' => $message,
            'user_id' => $user_id,
            'reciever_id' => $this->input->post('reciever_id'),
            'user_type' => "company",
            'chat_type' => 1,
            'reciever_type' => $reciever_type,
            'file' => $file,
			'file_type' => $file_type,
			'group_id' => $group_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated-at' => date('Y-m-d H:i:s')            
            
        );
        
        $this->db->insert('chat',$array);
        $response = array('status' => SUCCESS, 'message' => '');     
        $this->response($response);
	}
	
	// Api web multiple file upload
	function sendMessageToDatabaseMultiWeb_post(){
        $user_id = $_POST['user_id'];
		$group_id = $_POST['group_id'];
        $message = $this->input->post('message');
        $reciever_type = $this->input->post('reciever_type');
        $file_type = "docs";
        $countfiles = count($_FILES['attachment']['name']);
		for($i=0;$i<$countfiles;$i++){
			if(!empty($_FILES['attachment']['name'][$i])){
			  $_FILES['file']['name'] = $_FILES['attachment']['name'][$i];
			  $_FILES['file']['type'] = $_FILES['attachment']['type'][$i];
			  $_FILES['file']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
			  $_FILES['file']['error'] = $_FILES['attachment']['error'][$i];
			  $_FILES['file']['size'] = $_FILES['attachment']['size'][$i];
			  $config['upload_path']= "./uploads/project/documents/";
              $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp|mp4";
              $config['encrypt_name'] = FALSE;
			  $this->load->library('upload',$config);
			  if($this->upload->do_upload('file')){
				$uploadData = $this->upload->data();
				$filename = $uploadData['file_name'];
				$array = array(
					'message' => $message,
					'user_id' => $user_id,
					'reciever_id' => $this->input->post('reciever_id'),
					'user_type' => "company",
					'chat_type' => 1,
					'reciever_type' => $reciever_type,
					'file' => $filename,
					'file_type' => $file_type,
					'group_id' => $group_id,
					'created_at' => date('Y-m-d H:i:s'),
					'updated-at' => date('Y-m-d H:i:s')            
				);
				$this->db->insert('chat',$array);
			  }
			}
		}
        $response = array('status' => SUCCESS, 'message' => '');     
        $this->response($response);
    }
    
    // Api for single message get
    
   function getMessageToDatabaseSingle_post(){
        $user_id = $_POST['user_id'];
        $reciever_id = $_POST['reciever_id'];
        $reciever_type = $_POST['reciever_type'];
		$user_type = $_POST['user_type'];
		
		$group_id = $_POST['group_id'];
        
        $sendmessagearray = array();
        $recivemessagearray = array();
        $responseArray = array();
		
		if($group_id == "")
			$response = $this->db->select('*')->from('chat')->where('user_id',$user_id)->where("reciever_id", $reciever_id)->where('user_type',$user_type)->where('reciever_type',$reciever_type)->get()->result();
		else
			$response = $this->db->select('*')->from('chat')->where('group_id',$group_id)->get()->result();

        $i = 0;
        foreach($response as $sendmessage)
        {
            $responseArray[$i] = $sendmessage;
            $i++;
        }
        $response = $this->db->select('*')->from('chat')->where('user_id',$reciever_id)->where("reciever_id", $user_id)->where('user_type',$reciever_type)->where('reciever_type',$user_type)->get()->result();
        foreach($response as $recivemessage)
        {
            $responseArray[$i] = $recivemessage;
            $i++;
        }
        array_multisort(array_column($responseArray, 'created_at'), SORT_ASC, $responseArray);
        $message_html = "";
        // foreach($responseArray as $value){
        //     $dm['created_date']=$value->craeted_at();
        // }
        $z=1;
        $x=1;
        foreach($responseArray as $key =>$value){
            $user_image = 'https://ptetutorials.com/images/user-profile.png';
            $user_name = "";
            if($value->user_type == "company")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('company',array('company_id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/company/').$companyData->profile_photo;
                        $user_name = $companyData->name;
                    }
                }
            }else if($value->user_type == "leadcontractor" || $value->user_type == "subcontractor"){
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('contractor',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->owner_first_name;
                   // $this->response($value->user_name);
                    //die;
                    
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/contractor/').$companyData->profile_photo;
                         $user_name = $value->user_name;
                    }
                }
            }else if($value->user_type == "crew")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('crew_member',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image  = base_url('uploads/crew/').$companyData->profile_photo;
                        $user_name = $value->user_name;
                    }
                }
            }else if($value->user_type == "client")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('client',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image  = base_url('uploads/client/').$companyData->profile_photo;
                        $user_name = $value->user_name;
                    }
                }
            }
            $mainlink = "";
            $tag_html = "";
            if($value->file_type=="docs")
            {
                $mediapath = base_url('uploads/project/documents/').$value->file;
                if(@is_array(getimagesize($mediapath))){
                    $image = 1;
                    $link = $mediapath;
                } else {
                    $image = 0;
                    $link = 'https://docs.google.com/viewer?url='.$mediapath.'&embedded=true';
                }
                $newLink = $mediapath;
                $onclick = "window.open('".$newLink."', '_blank')";
                $mainlink = '<div class="custom-file" onclick="'.$onclick.'"><span></span><img style="max-width:100px;"src="'.base_url().'/backend_assets/img/upload.png" alt="Girl in a jacket"><label style="display: block;" >'.$value->file.'</label><label> &nbsp; '.$tag_html.'</label></div>';
            }
            if($value->user_id == $user_id)
            {
                $time= $this->time_elapsed_string($value->created_at);
                if ($key > 0) {
                    $prev_time= $this->time_elapsed_string($responseArray[$key-1]->created_at);
                    if($time==$prev_time){
                        $x=0;
                    }else{
                       $x=1;        
                    }
                }
               if(empty(!$value->message))
               {
                    $message='<p>'.$value->message.'</p>';
               } 
               else
               {
                    $message='';
               }

               if($x==1){
                    $z++;
                    $message_html .= '<div class="outgoing_msg"><div class="col-md-12" style="padding: 0px 34px 0px 35px;"><div class="col-md-4"><hr style="border-top: 1px solid #5a4a4a;"></div><div class="col-md-3" style="padding: 9px 0px 0px 9px;">'.$time.'</div><div class="col-md-4"><hr style="border-top: 1px solid #5a4a4a;"></div></div>';
                }
                $message_html .= '<div class="outgoing_msg_img"> <img style="border-radius:50%; width:50px; height:50px;" src="'.$user_image.'" alt="'.$user_name.'">  <label class="Name_font" style="display: block;">'.$user_name.'</label></div><div class="outgoing_msg"><div class="outgoing_withd_msg"><div class="sent_msg">'.$message.'<label>'.$mainlink.'</label></div></div></div><label class="time_date">'.date('D M Y h:i:a',strtotime($value->created_at)).'</label></div>';
            }else{
                
                $message_html .= '<div class="incoming_msg"><div class="incoming_msg_img"> <img style="border-radius:50%; width:50px; height:50px;" src="'.$user_image.'" alt="'.$user_name.'"> <label class="Name_font" style="display: block;">'.$user_name.'</label> </div><div class="received_msg"><div class="received_withd_msg"><p>'.$mainlink.$value->message.'</p><span class="time_date">'.date('D M Y h:i:a',strtotime($value->created_at)).'</span></div></div></div>';
            }
          
        }

        $response = array('status' => SUCCESS,'messages'=>$message_html,'responseArray'=>$responseArray,'count'=>count($responseArray));     
        $this->response($response);
    }
    
    
    // Final Api For Single to Single Wall
    function sendMessageToDatabaseSingleApi_post()
    {
        $user_id = $_POST['user_id'];
        $reciever_id = $_POST['reciever_id'];
        $message = $this->input->post('message');
		$reciever_type = $this->input->post('reciever_type');
		$group_id = 0;
		if(isset($_POST['group_id']))
		{
			$group_id = $_POST['group_id'];
		}
        $file = "";
        $file_type = "";
        $attachment = "";
        if(isset($_FILES['attachment']))
        {
            $attachment = $_FILES['attachment']['name'];
        }else{
            $attachment = "";
        }
        if($attachment != "")
        {
            $attachment = $_FILES['attachment']['name'];
            $config['upload_path']= "./uploads/project/documents/";
            $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp|mp4";
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);
            $file_type = 'docs';
            if($this->upload->do_upload('attachment'))
            {
                $data = array('upload_data' => $this->upload->data());
                $file= $data['upload_data']['file_name'];
            }else{
                $error = $this->upload->display_errors(); 
                $error = 'It will accept only doc, docx, pdf, ppt, jpeg, jpg, png, bmp, mp4 format files.';
                $response              = array('status'=>FAIL,'message'=>$error);
                $this->response($response);
                die;
            }
        }
        $array = array(
            'message' => $message,
            'user_id' => $user_id,
            'reciever_id' => $this->input->post('reciever_id'),
			'group_id' => $group_id,
            'user_type' => $this->input->post('user_type'),
            'chat_type' => 1,
            'reciever_type' => $reciever_type,
            'file' => $file,
            'file_type' => $file_type,
            'created_at' => date('Y-m-d H:i:s'),
            'updated-at' => date('Y-m-d H:i:s')            
        );
        $this->db->insert('chat',$array);
        $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully');     
        $this->response($response);
	}
	
	// group multiple upload facilities
	function sendMessageToDatabaseMultipleUploadApi_post()
    {
        $user_id = $_POST['user_id'];
        $message = $this->input->post('message');
		$reciever_type = $this->input->post('reciever_type');
		$group_id = 0;
		if(isset($_POST['group_id']))
		{
			$group_id = $_POST['group_id'];
		}
        $file_type = "docs";
        $allfilenameArr = array();
		$imageArr =  $_POST['attachment'];
		foreach($imageArr as $imageAr)
		{
			$main = json_decode($imageAr);
			foreach($main as $mains)
			{
				$imgdata = base64_decode($mains->encoded);
				$f = finfo_open();
				$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
				$split = explode( '/', $mime_type);
				$type = $split[1]; 
				$image = base64_decode($mains->encoded);
				$image_name = "myfile_".time().'-'.mt_rand();
				$filename = $image_name . '.' . $type;
				$path = "uploads/project/documents/".$filename;
				file_put_contents($path.$filename, $image);
				array_push($allfilenameArr,$filename.$filename);
			}
		}
		foreach($allfilenameArr as $allfilename)
		{
			$filename = $allfilename;
			$array = array(
				'message' => $message,
				'user_id' => $user_id,
				'reciever_id' => $this->input->post('reciever_id'),
				'group_id' => $group_id,
				'user_type' => $this->input->post('user_type'),
				'chat_type' => 1,
				'reciever_type' => $reciever_type,
				'file' => $filename,
				'file_type' => $file_type,
				'created_at' => date('Y-m-d H:i:s'),
				'updated-at' => date('Y-m-d H:i:s')            
			);
			$this->db->insert('chat',$array);
		}
        $response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully');     
        $this->response($response);
    }
    
    
    function getMessageToDatabaseSingleApi_post()
    {
        $user_id = $_POST['user_id'];
        $reciever_id = $_POST['reciever_id'];
        $reciever_type = $_POST['reciever_type'];
        $user_type = $_POST['user_type'];
        
        $sendmessagearray = array();
        $recivemessagearray = array();
		$responseArray = array();
		
		if(isset($_POST['group_id']) && $_POST['group_id'] != 0)
		{
			$group_id = $_POST['group_id'];
			$response = $this->db->select('*')->from('chat')->where('group_id',$group_id)->get()->result();
		}else{
			$response = $this->db->select('*')->from('chat')->where('user_id',$user_id)->where("reciever_id", $reciever_id)->where('user_type',$user_type)->where('reciever_type',$reciever_type)->get()->result();
		}
        
        $i = 0;
        foreach($response as $sendmessage)
        {
            $responseArray[$i] = $sendmessage;
            $i++;
        }
        $response = $this->db->select('*')->from('chat')->where('user_id',$reciever_id)->where("reciever_id", $user_id)->where('user_type',$reciever_type)->where('reciever_type',$user_type)->get()->result();
        foreach($response as $recivemessage)
        {
            $responseArray[$i] = $recivemessage;
            $i++;
        }
        
        array_multisort(array_column($responseArray, 'created_at'), SORT_ASC, $responseArray);

        $message_html = "";
        foreach($responseArray as $value){
            $user_image = 'https://ptetutorials.com/images/user-profile.png';
            $user_name = "";
            if($value->user_type == "company")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('company',array('company_id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    if($companyData->profile_photo != NULL)
                    {
                        $value->user_image = base_url('uploads/company/').$companyData->profile_photo;
                        $value->user_name = $companyData->name;
                    }
                }
            }else if($value->user_type == "leadcontractor" || $value->user_type == "subcontractor"){
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('contractor',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->owner_first_name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image = base_url('uploads/contractor/').$companyData->profile_photo;
                    }
                    $value->user_image = $user_image;
                }
            }else if($value->user_type == "crew")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('crew_member',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image  = base_url('uploads/crew/').$companyData->profile_photo;
                    }
                    $value->user_image = $user_image;
                }
            }else if($value->user_type == "client")
            {
                $user_id = $value->user_id;
                $companyData = $this->db->get_where('client',array('id'=>$user_id))->result();
                if(isset($companyData[0]))
                {
                    $companyData = $companyData[0];
                    $value->user_name = $companyData->name;
                    if($companyData->profile_photo != NULL)
                    {
                        $user_image  = base_url('uploads/client/').$companyData->profile_photo;
                    }
                    $value->user_image = $user_image;
                }
            }

            $filename = "";
            if($value->file_type=="docs")
            {
                $filename = $value->file;
                $value->file = base_url('uploads/project/documents/').$value->file;
            }
            $value->filename = $filename;
        }
        
        $array = array(
            'is_seen' => 1
        );
        $this->db->where('reciever_id',$_POST['user_id']);
        $this->db->where('user_id',$_POST['reciever_id']);
        $this->db->update('chat',$array);
        
        $response = array('status' => SUCCESS,'messages'=>$message_html,'responseArray'=>$responseArray,'count'=>count($responseArray));     
        $this->response($response);
    }
    function time_elapsed_string($timestamp)
    {
       if ($timestamp) {

        $currentTime=date('Y-m-d H:i:s');
        $days=round((strtotime($timestamp)-strtotime($currentTime))/86400);

        switch($days) {
            case '0';
                return 'Today';
                break;
            case '-1';
                return 'Yesterday';
                break;
            default:
                if ($days > 0) {
                    return 'In '.$days.' days';
                } else {
                    return date('Y-m-d',strtotime($timestamp));
                }
                break;
            }
        }
	}
	
	// multiple docs upload
	public function multiDocsUpload_post()
	{
		$allfilenameArr = array();
		$imageArr =  $_POST['attachment'];
		foreach($imageArr as $imageAr)
		{
			$main = json_decode($imageAr);
			foreach($main as $mains)
			{
				$imgdata = base64_decode($mains->encoded);
				$f = finfo_open();
				$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
				$split = explode( '/', $mime_type);
				$type = $split[1]; 
				$image = base64_decode($mains->encoded);
				$image_name = "myfile_".time().'-'.mt_rand();
				$filename = $image_name . '.' . $type;
				$path = "uploads/project/documents/".$filename;
				file_put_contents($path.$filename, $image);
				array_push($allfilenameArr,$filename.$filename);
			}
		}
		$user_id = $this->input->post('user_id');
		foreach($allfilenameArr as $allfilename)
		{
			$filename = $allfilename;
			$array = array(
				'message' => '',
				'user_id' => $user_id,
				'project_id' => $this->input->post('project_hidden_id'),
				'user_type' => $this->input->post('user_type'),
				'file_type' => 'docs',
				'file' => $filename,
				 'tag' => $this->input->post('tags_selected'),
				'created_at' => date('Y-m-d H:i:s'),
				'updated-at' => date('Y-m-d H:i:s')                
			);
			$this->db->insert('chat',$array);
		}
		$response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully');
        $this->response($response);
	}

	public function multiDocsUpload_web_post()
	{
		$user_id = $this->input->post('user_id');
		$countfiles = count($_FILES['attachment']['name']);
		for($i=0;$i<$countfiles;$i++){
			if(!empty($_FILES['attachment']['name'][$i])){
			  $_FILES['file']['name'] = $_FILES['attachment']['name'][$i];
			  $_FILES['file']['type'] = $_FILES['attachment']['type'][$i];
			  $_FILES['file']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
			  $_FILES['file']['error'] = $_FILES['attachment']['error'][$i];
			  $_FILES['file']['size'] = $_FILES['attachment']['size'][$i];
			  $config['upload_path']= "./uploads/project/documents/";
              $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp|mp4";
              $config['encrypt_name'] = FALSE;
			  $this->load->library('upload',$config);
			  if($this->upload->do_upload('file')){
				$uploadData = $this->upload->data();
				$filename = $uploadData['file_name'];
				$array = array(
					'message' => '',
					'user_id' => $user_id,
					'project_id' => $this->input->post('project_hidden_id'),
					'user_type' => $this->input->post('user_type'),
					'file_type' => 'docs',
					'file' => $filename,
					'tag' => $this->input->post('tags_selected'),
					'created_at' => date('Y-m-d H:i:s'),
					'updated-at' => date('Y-m-d H:i:s')                
				);
				$this->db->insert('chat',$array);
			  }
			}
		}
		$response = array('status' => SUCCESS, 'message' => 'Message Sent Successfully');
        $this->response($response);
	}

    
}//End Class 
