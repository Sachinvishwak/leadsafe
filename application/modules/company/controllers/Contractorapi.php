<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contractorapi extends REST_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        ini_set('display_errors', 1);
    }
    
    public function getsate_get()
    {
        $this->db->where('country_id','231');
        $data = $this->db->get('states')->result();
        $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Contractor List Get Succesfully');
        $this->response($response);
    }

    public function cityapi_post()
    {
        $state_id = $this->post('state_id');
        $data = $this->db->get_where('cities',array('state_id'=>$state_id))->result();
        $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Contractor List Get Succesfully');
        $this->response($response);
    }
    public function searchapi_post()
    {
        $this->db->where('company_id',$_POST['company_id']);
        if(isset($_POST['name']) && $_POST['name'] != "")
        {
            $name = $_POST['name'];
            $this->db->like('company_name', $_POST['name']);
            $data = $this->db->get('contractor')->result();
        }else{
            $data = $this->db->get('contractor')->result();
        }
        foreach($data as $value)
        {
            $value->encrypt_id = encoding($value->id);
        }
        echo json_encode($data);
    }
    
    public function contractorlist_post()
    {
        $company_id = $this->post('company_id');
        $this->db->order_by("id", "desc");
        if(isset($_POST['name']) && $_POST['name'] != ""){
            $data = $this->db->select('company_member_relations.type, contractor.*')
                 ->from('company_member_relations')
                 ->join('contractor', 'company_member_relations.member_id = contractor.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->like('contractor.owner_first_name',$_POST['name'])
                 ->or_like('contractor.owner_last_name',$_POST['name'])
                 ->or_like('contractor.company_name',$_POST['name'])
                 ->where('company_member_relations.type','leadcontractor')->get()->result();
        }else{
            $data = $this->db->select('company_member_relations.type, contractor.*')
                 ->from('company_member_relations')
                 ->join('contractor', 'company_member_relations.member_id = contractor.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->where('company_member_relations.type','leadcontractor')->get()->result();
        }
        foreach($data as $value)
        {
            $value->encrypt_id = encoding($value->id);
            if($value->licence != "")
            {
                $value->licence = base_url('uploads/contractor/').$value->licence;
            }
            if($value->insurence_certificate != "")
            {
                $value->insurence_certificate = base_url('uploads/contractor/').$value->insurence_certificate;
            }
        }
        $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Contractor List Get Succesfully');
        $this->response($response);
    }
    
    // Client detail api
    public function contractordetail_post()
    {
        $contractor_id = $this->post('contractor_id');
        $data = $this->db->get_where('contractor',array('id'=>$contractor_id))->result();
        $states = $this->db->get_where('states',array('id'=>$data[0]->state))->result();
        $city = $this->db->get_where('cities',array('id'=>$data[0]->city))->result();
        if(!isset($data[0]))
        {
            $response       = array('status'=>FAIL,'data'=>$data,'message'=>'Project Not Exist');
        }else{
            $data = $data[0];   
            $data->encrypt_id = encoding($data->id);
            if($data->licence != "")
            {
                $data->licence = base_url('uploads/contractor/').$data->licence;
            }
            if($data->insurence_certificate != "")
            {
                $data->insurence_certificate = base_url('uploads/contractor/').$data->insurence_certificate;
            }
            if($data->state!="")
            {
                $data->statename = $states[0]->name;
            }else{
				$data->statename = "";
			}
            if($data->city!="")
            {
                $data->cityname = $city[0]->name;
			}else{
				$data->cityname = "";
			}
			
			$license_media = $this->db->get_where('license_media',array('type'=>'contractor','user_id'=>$contractor_id))->result();
			foreach($license_media as $licenses)
			{
				$licenses->file_path = base_url('uploads/crew/').$licenses->file_name;
			}
		
			$data->multiple_license = $license_media;
            
            $response       = array('status'=>SUCCESS,'data'=>$data,'message'=>'Project Detail Get Succesfully');
        }
        
        $this->response($response);
    }

    public function list_post(){
        $this->load->helper('text');
        $this->load->model('Contractor_model');
        $this->Contractor_model->set_data();
        $list       = $this->Contractor_model->get_list();
        $data       = array();
        $no         = $_POST['start'];
        foreach ($list as $serData) { 
            $action = '';
            $no++;
            $row    = array();
            $row[]  = $no;
            $link_url      = base_url().'admin/contractor-detail/'.encoding($serData->id);
            //$row[]  = '<a href="'.$link_url.'">'.display_placeholder_text($serData->company_name).'</a>'; 
            
            $row[]      = display_placeholder_text((mb_substr($serData->company_name, 0,30, 'UTF-8') .((strlen($serData->company_name) >30) ? '...' : '')));
            
            // $row[]  = $serData->owner_first_name;
            // $row[]  = $serData->owner_last_name;
            $row[]  = $serData->owner_first_name.' '.$serData->owner_last_name;
            $row[]      = display_placeholder_text((mb_substr($serData->email, 0,100, 'UTF-8') .((strlen($serData->email) >100) ? '...' : '')));
            
            //$row[]  = $serData->address; 
            
            $row[]      = display_placeholder_text((mb_substr($serData->address, 0,30, 'UTF-8') .((strlen($serData->address) >30) ? '...' : '')));
            
            $row[]  = $serData->phone_number;
            // if($serData->is_role == 2)
            // {
            //     $row[]  = 'Sub Contractor';    
            // }else{
            //     $row[]  = 'Lead Contractor';
            // }
            
            
            $link    = 'javascript:void(0)';
            $action .= "";
             if($serData->status){

                //$action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->id).'" data-url="adminapi/tasks/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-check" aria-hidden="true"></i></a>';
            }else{
                //$action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->id).'" data-url="adminapi/tasks/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-times" aria-hidden="true"></i></a>';
            }
            $link_url      = base_url().'admin/contractor-detail/'.encoding($serData->id);
            $action .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Detail"><i class="fa fa-eye"  aria-hidden="true"></i></a>';
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="'.encoding($serData->id).'" data-url="company/Contractorapi/recordDelete" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $link_url      = base_url().'company/contractor/edit/'.encoding($serData->id);
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Edit"><i class="fa fa-edit"  aria-hidden="true"></i></a>';
            $row[]  = $action;
            $data[] = $row;

        }
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->Contractor_model->count_all(),
            "recordsFiltered"   => $this->Contractor_model->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        $this->response($output);
    }
    
    
    public function recordDelete_post()
    {
        $id            = decoding($this->post('id'));
        // $where              = array('id'=>$id);
        $where = array('type'=>'leadcontractor','member_id'=>$id);
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
        $this->load->model('Contractor_model');
        // $this->form_validation->set_rules('phone_number', 'Phone Number','trim|required|min_length[10]|max_length[20]');
        if(isset($_POST['phone_number']) && $_POST['phone_number'] != ""){
            $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        }
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_valid_email');
        $this->form_validation->set_rules('company_name', 'company_name', 'trim|required|min_length[2]|regex_match[/^([a-z0-9_ ])+$/i]');
        $this->form_validation->set_rules('owner_first_name', 'Contractor Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        $this->form_validation->set_rules('owner_last_name', 'Owner Last Name', 'trim|min_length[2]|regex_match[/^([a-z ])+$/i]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
        
            $email                          =  $this->post('email');
            $company_name                       =  $this->post('company_name');
            $userData['company_name']           =   $company_name;
            $userData['email']              =   $email;
            $userData['phone_number']      =   $this->post('phone_number');
            $userData['address']      =   $this->post('address');
            $userData['company_id']      =   $this->post('company_id');
            $userData['owner_first_name']      =   $this->post('owner_first_name');
            $userData['owner_last_name']      =   $this->post('owner_last_name');
            $userData['is_role']      =   $this->post('is_role');
            $userData['state']      =   $this->post('state');
            $userData['city']      =   $this->post('city');
            
            // profile pic upload
            $config['upload_path']= "./uploads/contractor/";
            $config['allowed_types']='doc|docx|pdf|ppt|jpeg|jpg|png|bmp';
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);

            if($this->upload->do_upload('licence'))
            {
                $data = array('upload_data' => $this->upload->data());
                $licence= $data['upload_data']['file_name'];
            }
            if($this->upload->do_upload('insurence_certificate'))
            {
                $data = array('upload_data' => $this->upload->data());
                $insurence_certificate= $data['upload_data']['file_name'];
            }

            $userData['licence']               =   $licence;
            $userData['insurence_certificate'] =   $insurence_certificate;

            $result = $this->Contractor_model->registration($userData);
            if(is_array($result)){
                switch ($result['regType']){
                    case "NR": // Normal registration
                        $response = array('status'=>SUCCESS,'message'=>'Contractor Invitation Send Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/contractor');
                        // send contractor invitaion
                        
                        $array = array(
                            'sender_id' => $this->post('company_id'),
                            'sender_type' => 'company',
                            'receiver_id' => $result['returnData'][0]->id,
                            'reciever_type' => 'leadcontractor',
                            'is_for' => 'account'
                        );
                        $this->db->insert('invite',$array);
                        $id = $this->db->insert_id();
                        $link = base_url().'invitation/'.encoding($id);
                        $data1['full_name']  = $result['returnData'][0]->owner_first_name;
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
									'type' => 'contractor',
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
									'type' => 'contractor',
									'user_id' => $result['returnData'][0]->id,
									'file_name' => $all_doc
								);
								$this->db->insert('license_media',$array);
							}
						}

                    break;
                    case "AE": // User already registered
                        $num_rows = $this->db->get_where('company_member_relations',array('company_id'=>$this->post('company_id'),'member_id'=>$result['existId'],'type'=>'leadcontractor'))->num_rows();
                        if($num_rows>0){
                            $response = array('status'=>FAIL,'message'=>'Contractor Already Registered With Your Company','users'=>array());
                        }else{
                            // send contractor invitaion
                            $reciever_data = $this->db->get_where('contractor',array('id'=>$result['existId']))->result();
                            $reciever_data = $reciever_data[0];
                            $full_name= $reciever_data->owner_first_name;
                            $array = array(
                                'sender_id' => $this->post('company_id'),
                                'sender_type' => 'company',
                                'receiver_id' => $result['existId'],
                                'reciever_type' => 'leadcontractor',
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
                            $response = array('status'=>SUCCESS,'message'=>'Contractor Invitation Send Successfully', 'messageCode'=>'normal_reg','users'=>$result['returnData'],'url' => 'admin/contractor');
                            //end
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
    
    function sendContractorMail($to,$link,$message,$subject)
    {
        $this->load->library('email');
        $this->email->from('rathoreankit582@gmail.com', 'Bild It');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->set_mailtype("html");
        $this->email->send();
    }
    
    function edit_post(){
		// echo "<pre>";
		// print_r($_POST);
		// die;
        $this->load->model('Crew_model');
        // $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_valid_email|is_unique[admin.email]',
        //     array('is_unique' => 'Email already exist')
        // );
        if(isset($_POST['phone_number']) && $_POST['phone_number'] != ""){
            $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        }
        $this->form_validation->set_rules('company_name', 'company_name', 'trim|required|min_length[2]|regex_match[/^([a-z0-9_ ])+$/i]');
        $this->form_validation->set_rules('owner_first_name', ' Contractor Name', 'trim|required|min_length[2]|regex_match[/^([a-z ])+$/i]');
        $this->form_validation->set_rules('owner_last_name', 'Owner Last Name', 'trim|min_length[2]|regex_match[/^([a-z ])+$/i]');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }else{
            $email                          =  $this->post('email');
            $company_name                       =  $this->post('company_name');
            $userData['company_name']           =   $company_name;
            $userData['email']              =   $email;
            $userData['phone_number']      =   $this->post('phone_number');
            $userData['address']      =   $this->post('address');
            // $userData['company_id']      =   decoding($this->post('company_id'));
            $userData['owner_first_name']      =   $this->post('owner_first_name');
            $userData['owner_last_name']      =   $this->post('owner_last_name');
            $userData['is_role']      =   $this->post('is_role');
            $userData['state']      =   $this->post('state');
            $userData['city']      =   $this->post('city');
            // profile pic upload
            $config['upload_path']= "./uploads/contractor/";
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
                $userData['licence']                      = $licence;
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
                $userData['insurence_certificate']        = $insurence_certificate;
            }

            $this->db->where('id',decoding($this->input->post('id')));
            $result = $this->db->update('contractor',$userData);
			$msg = 'Contractor Details Updated Successfully';
			
			// multiple document upload
			if(isset($_POST['all_docs']))
			{
				$all_docs = json_decode($_POST['all_docs']);
							
				foreach($all_docs as $all_doc)
				{
					$array = array(
						'type' => 'contractor',
						'user_id' => decoding($this->input->post('id')),
						'file_name' => $all_doc
					);
					$this->db->insert('license_media',$array);
				}
			}
			if(isset($_POST['all_docs1']))
			{
				$all_docs = explode(",",$_POST['all_docs1']);
				foreach($all_docs as $all_doc)
				{
					$array = array(
						'type' => 'contractor',
						'user_id' => decoding($this->input->post('id')),
						'file_name' => $all_doc
					);
					$this->db->insert('license_media',$array);
				}
			}

            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'admin/contractor-detail/'.$this->input->post('id'));
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
            $this->response($response);
        }
    }
    





  
}//End Class
