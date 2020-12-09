<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Crewapi extends REST_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
    }
    
    // search Api
    public function searchapi_post()
    {
        $this->db->where('company_id',$_POST['company_id']);
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $name = $_POST['name'];
            $this->db->like('name', $_POST['name']);
            $data = $this->db->get('crew_member')->result();
        }else{
            $data = $this->db->get('crew_member')->result();
        }
        echo json_encode($data);
    }
    
    public function crewlist_post()
    {
        $company_id = $this->post('company_id');
        $this->db->order_by("id", "desc");
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $data = $this->db->select('company_member_relations.type, crew_member.*')
                 ->from('company_member_relations')
                 ->join('crew_member', 'company_member_relations.member_id = crew_member.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->where('company_member_relations.type','crew')->like('name', $_POST['name'])->get()->result();
        }else{
            $data = $this->db->select('company_member_relations.type, crew_member.*')
                 ->from('company_member_relations')
                 ->join('crew_member', 'company_member_relations.member_id = crew_member.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->where('company_member_relations.type','crew')->get()->result();
        }
        foreach($data as $value)
        {
            $value->encrypt_id = encoding($value->id);
            if($value->licence != "")
            {
                $value->licence = base_url('uploads/crew/').$value->licence;   
            }
            if($value->insurence_certificate)
            {
                $value->insurence_certificate = base_url('uploads/crew/').$value->insurence_certificate;   
            }
        }
        $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Crew Member List Get Succesfully');
        $this->response($response);
    }
    
    // Client detail api
    public function crewdetail_post()
    {
        $crew_id = $this->post('crew_id');
        $data = $this->db->get_where('crew_member',array('id'=>$crew_id))->result();
        foreach($data as $value)
        {
            $value->encrypt_id = encoding($value->id);
            if($value->licence != "")
            {
                $value->licence = base_url('uploads/crew/').$value->licence;   
            }
            if($value->insurence_certificate)
            {
                $value->insurence_certificate = base_url('uploads/crew/').$value->insurence_certificate;   
			}
			
			$license_media = $this->db->get_where('license_media',array('type'=>'crew','user_id'=>$crew_id))->result();
			foreach($license_media as $licenses)
			{
				$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
			}
		
			$value->multiple_license = $license_media;
        }
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'data'=>$data,'message'=>'Crew Member Not Exist');
        }else{
            $data = $data[0];   
            $data->encrypt_id = encoding($data->id);
            $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Crew Member Detail Get Succesfully');
        }
        $this->response($response);
    }

    public function list_post(){
        $this->load->helper('text');
        $this->load->model('Crew_model');
        $this->Crew_model->set_data();
        $list       = $this->Crew_model->get_list();
        $data       = array();
        $no         = $_POST['start'];
        foreach ($list as $serData) { 
            $action = '';
            $no++;
            $row    = array();
            $row[]  = $no;
            $link_url      = base_url().'admin/crew-detail/'.encoding($serData->id);
            $row[]  = '<a href="'.$link_url.'">'.display_placeholder_text($serData->name).'</a>'; 
            $row[]      = display_placeholder_text((mb_substr($serData->email, 0,100, 'UTF-8') .((strlen($serData->email) >100) ? '...' : '')));
            
            $row[]  = $serData->address; 
            $row[]  = $serData->phone_number;
            
            $link    = 'javascript:void(0)';
            $action .= "";
            if($serData->status){

                //$action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->id).'" data-url="adminapi/tasks/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-check" aria-hidden="true"></i></a>';
            }else{
                //$action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->id).'" data-url="adminapi/tasks/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-times" aria-hidden="true"></i></a>';
            }
            $link_url      = base_url().'admin/crew-detail/'.encoding($serData->id);
            $action .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Detail"><i class="fa fa-eye"  aria-hidden="true"></i></a>';
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="'.encoding($serData->id).'" data-url="company/Crewapi/recordDelete" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $link_url      = base_url().'company/crew/edit/'.encoding($serData->id);
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Edit"><i class="fa fa-edit"  aria-hidden="true"></i></a>';
            $row[]  = $action;
            $data[] = $row;

        }
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->Crew_model->count_all(),
            "recordsFiltered"   => $this->Crew_model->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        $this->response($output);
    }
    
    
    public function recordDelete_post()
    {
        $id            = decoding($this->post('id'));
        // $where              = array('id'=>$id);
        $where = array('type'=>'crew','member_id'=>$id);
        $dataExist      = $this->common_model->is_data_exists('company_member_relations',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('company_member_relations',$where);
            $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
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
    
    function add_post(){
        $this->load->model('Crew_model');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_valid_email');
        
        if(isset($_POST['phone_number']) && $_POST['phone_number'] != ""){
            $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        }
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            $email                          =  $this->post('email');
            $name                       =  $this->post('name');
            $userData['name']           =   $name;
            $userData['email']              =   $email;
            $userData['phone_number']      =   $this->post('phone_number');
            $userData['address']      =   $this->post('address');
            $userData['company_id']      =   $this->post('company_id');
            // profile pic upload
			//$config['upload_path']= "./uploads/crew/";
			$config['upload_path']= "./uploads/crew/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
			$this->load->library('upload',$config);
            
            $licence = "";
			$insurence_certificate = "";
			
            if(isset($_FILES['licence']))
            {
                $licence = $_FILES['licence']['name'];
            }
            if(isset($_FILES['insurence_certificate']))
            {
                $insurence_certificate = $_FILES['insurence_certificate']['name'];   
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
            }

            $userData['licence']               =   $licence;
            $userData['insurence_certificate'] =   $insurence_certificate;

            $result = $this->Crew_model->registration($userData);
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                        $response = array('status'=>SUCCESS,'message'=>'Crew Member Added Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/crew_member');
                        // send crew invitaion
                        $array = array(
                            'sender_id' => $this->post('company_id'),
                            'sender_type' => 'company',
                            'receiver_id' => $result['returnData'][0]->id,
                            'reciever_type' => 'crew',
                            'is_for' => 'account'
                        );
                        $this->db->insert('invite',$array);
						$id = $this->db->insert_id();
						
                        $link = base_url().'invitation/'.encoding($id);
                        $data1['full_name']  = $result['returnData'][0]->name;
                        $data1['url']        = $link;
                        $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                        $subject = 'Bild It - Account Invitation link';
                        $to = $result['returnData'][0]->email;
						$this->common_model->sendMemberMail($to,$link,$message,$subject);

						// multiple document upload
						if(isset($_POST['all_docs']))
						{
							$all_docs = json_decode($_POST['all_docs']);
							foreach($all_docs as $all_doc)
							{
								$array = array(
									'type' => 'crew',
									'user_id' => $result['returnData'][0]->id,
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
									'type' => 'crew',
									'user_id' => $result['returnData'][0]->id,
									'file_name' => $all_doc
								);
								$this->db->insert('license_media',$array);
							}
						}
                        // end
                    break;
                    case "AE": // User already registered
                        $num_rows = $this->db->get_where('company_member_relations',array('company_id'=>$this->post('company_id'),'member_id'=>$result['existId'],'type'=>'crew'))->num_rows();
                        if($num_rows>0){
                            $response = array('status'=>FAIL,'message'=>'Crew Member Already Registered','users'=>array());
                        }else{
                            $response = array('status'=>SUCCESS,'message'=>'Crew Member Invited Successfully','users'=>array(),'url'=>'admin/crew_member');
                            // send crew invitaion
                            $reciever_data = $this->db->get_where('crew_member',array('id'=>$result['existId']))->result();
                            $reciever_data = $reciever_data[0];
                            $full_name= $reciever_data->name;
                            $array = array(
                                'sender_id' => $this->post('company_id'),
                                'sender_type' => 'company',
                                'receiver_id' => $result['existId'],
                                'reciever_type' => 'crew',
                                'is_for' => 'account'
                            );
                            $this->db->insert('invite',$array);
                            $id = $this->db->insert_id();
                            $link = base_url().'invitation/'.encoding($id);
                            $data1['full_name']  = $full_name;
                            $data1['url']        = $link;
                            $message            = $this->load->view('emails/member_invite',$data1,TRUE);
                            $subject = 'Bild It - Account Invitation link';
                            $to = $email;
                            $this->common_model->sendMemberMail($to,$link,$message,$subject);
                            // end
                        }
                    break;
                    default:
                        $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'userDetail'=>array());
                }
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118),'userDetail'=>array());
            }   
            $this->response($response);
        }
	}
    
    function edit_post(){
        $this->load->model('Crew_model');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_valid_email|is_unique[admin.email]',
            array('is_unique' => 'Email already exist')
        );
        if(isset($_POST['phone_number']) && $_POST['phone_number'] != ""){
            $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        }
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
        
            $email                          =  $this->post('email');
            $name                       =  $this->post('name');
            $data_val['name']           =   $name;
            $data_val['email']              =   $email;
            $data_val['phone_number']      =   $this->post('phone_number');
            $data_val['address']      =   $this->post('address');
            // profile pic upload
            $config['upload_path']= "./uploads/crew/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
			$this->load->library('upload',$config);						

            $licence = $_FILES['licence']['name'];
            $insurence_certificate = $_FILES['insurence_certificate']['name'];
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
                $data_val['licence']                      = $licence;
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
                $data_val['insurence_certificate']        = $insurence_certificate;
            }

            $this->db->where('id',decoding($this->input->post('id')));
            $result = $this->db->update('crew_member',$data_val);
			$msg = 'Crew Member Details Updated Successfully';
			
			// multiple document upload
			if(isset($_POST['all_docs']))
			{
				$all_docs = json_decode($_POST['all_docs']);
							
				foreach($all_docs as $all_doc)
				{
					$array = array(
						'type' => 'crew',
						'user_id' => decoding($this->input->post('id')),
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
						'type' => 'crew',
						'user_id' => decoding($this->input->post('id')),
						'file_name' => $all_doc
					);
					$this->db->insert('license_media',$array);
				}
			}

            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'admin/crew-detail/'.$this->input->post('id'));
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
            $this->response($response);
        }
	}
	
	//deleteLicense
	public function deleteLicense_post()
	{
		$this->db->where('id',$this->input->post('id'));
		$this->db->delete('license_media');
		echo json_encode(array('status'=>'success','message'=>'Delete License Successfully.'));
	}

  
}//End Class
