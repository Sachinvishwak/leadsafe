<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Company extends Common_Admin_Controller{
    
    public function __construct(){
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        //$this->check_admin_service_auth();
    }
    
     public function list_post_test()
     {

     }

    public function list_post(){
        $this->load->helper('text');
        $this->load->model('Company_modal');
        $this->Company_modal->set_data();
        $list = $this->Company_modal->get_list();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $serData) { 
            $action = '';
            $no++;
            $row    = array();
            $row[]  = $no;
            $link_url      = base_url().'company-detail/'.encoding($serData->company_id);
            $row[]  = '<a href="'.$link_url.'">'.display_placeholder_text($serData->name).'</a>'; 
            $row[]      = display_placeholder_text((mb_substr($serData->email, 0,100, 'UTF-8') .((strlen($serData->email) >100) ? '...' : ''))); 
            if($serData->status){
            $row[]  = '<label class="label label-success">'.$serData->statusShow.'</label>';
            }else{ 
            $row[]  = '<label class="label label-danger">'.$serData->statusShow.'</label>'; 
            } 
            $link    = 'javascript:void(0)';
            $action .= "";
             if($serData->status){

                $action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->company_id).'" data-url="adminapi/company/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>';
            }else{
                $action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to change status!" data-id="'.encoding($serData->company_id).'" data-url="adminapi/company/activeInactiveStatus" data-list="1"  class="on-default edit-row table_action" title="Status"><i class="fa fa-thumbs-down" aria-hidden="true"></i></a>';
            }
            $link_url      = base_url().'company-detail/'.encoding($serData->company_id);
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Detail"><i class="fa fa-eye"  aria-hidden="true"></i></a>';
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="'.encoding($serData->company_id).'" data-url="adminapi/company/recordDelete" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $link_url      = base_url().'admin/company/edit/'.encoding($serData->company_id);
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link_url.'"  class="on-default edit-row table_action" title="Edit"><i class="fa fa-edit"  aria-hidden="true"></i></a>';
            $row[]  = $action;
            $data[] = $row;

        }
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->Company_modal->count_all(),
            "recordsFiltered"   => $this->Company_modal->count_filtered(),
            "data"              => $data,
        );
        $this->response($output);
    }//end function     
       
    function recordDelete_post(){
        $id            = decoding($this->post('id'));
        $where              = array('company_id'=>$id);
        $dataExist      = $this->common_model->is_data_exists('company',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('company',$where);
            $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }//end function
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
    public function add_post(){    
        //$this->form_validation->set_rules('name', 'name', 'trim|required|min_length[4]|alpha');
    	$this->form_validation->set_rules('name', 'name', 'trim|required|min_length[4]|regex_match[/^([a-z0-9 ])+$/i]');
        $this->form_validation->set_rules('email', 'email', 'trim|required|callback_valid_email|valid_email');
      	$this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
        $this->form_validation->set_rules('fax_number', 'fax number', 'trim|regex_match[/^[0-9]{10}$/]');
            $email = $this->post('email');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));    
        }else{
            $isExistEmail   = $this->common_model->is_data_exists('company',array('email'=>$email));



            $isExistname   = $this->common_model->is_data_exists('company',array('name'=>$this->post('name')));
            if($isExistname!=''|| !empty($isExistname))
                {
                    $response       = array('status'=>FAIL,'message'=>'Company Name Already Registered'); 
                    $this->response($response);
                    die;
                    
                    
                }  


                if(!$isExistEmail)
                {
                $config['upload_path']= "./uploads/company/";
                $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp";
                $config['encrypt_name'] = FALSE;
                $this->load->library('upload',$config);
                $licence = '';
                $insurence_certificate = '';
                
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
        
                $data_val['name']                         = $this->post('name');
                $data_val['email']                        = $this->post('email');
                $data_val['phone_number']                 = $this->post('phone_number');
                $data_val['fax_number']                   = $this->post('fax_number');
                $data_val['licence']                      = $licence;
                $data_val['insurence_certificate']        = $insurence_certificate;
                $result = $this->db->insert('company',$data_val);
                $msg = 'Company Added Successfully';
                $company_id = $this->db->insert_id();
                // invite 
                $id = $company_id;
                $link = base_url().'invite/'.base64_encode($id);
                $data1['url']        = $link;
                $data1['full_name']        = $this->post('name');
                $message            = $this->load->view('emails/company_invite',$data1,TRUE);
                $subject = 'Bild IT - Account Invitation link';
                $to = $this->post('email');
				$this->common_model->sendMemberMail($to,$link,$message,$subject);

				// end
            }
            else{
                $this->db->where('email',$this->post('email'));
                $company_data = $this->db->get('company')->result();
                $company_data = $company_data[0];
                $company_id = $company_data->company_id;
                $result = $company_data;
                $msg = 'Company Already Registered.';
                if($company_data->password == "" || $company_data->password == NULL )
                {
                    $id = $company_data->company_id;
                    // invite 
                    $link = base_url().'invite/'.base64_encode($id);
                    $data1['url']        = $link;
                    $data1['full_name']        = $company_data->name;
                    $message            = $this->load->view('emails/company_invite',$data1,TRUE);
                    $subject = 'Bild IT - Account Invitation link';
                    $to = $company_data->email;
					$this->common_model->sendMemberMail($to,$link,$message,$subject);
					


                    // end
                    $msg = 'Invite link sent successfully.';  
                }
            }
            // end
            if($result){
                if($msg == 'Company Already Registered.' || $msg == "Company Already Registered.")
                {
            $response = array('status'=>FAIL,'message'=>$msg,'url'=>base_url().'company-detail/'.encoding($company_id));   
                }
                else
                 {
        $response = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'company-detail/'.encoding($company_id));
                    }
            }
            else
            {
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
        }        $this->response($response);
    }//

    public function one_edite_post()
    {

    }
    
    public function edit_post(){
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_valid_email');
    	$this->form_validation->set_rules('name', 'name', 'trim|required|min_length[4]|regex_match[/^([a-z0-9 ])+$/i]');
        $this->form_validation->set_rules('phone_number', 'PhoneNumber', 'trim|required|regex_match[/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/]');
     
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));    
        }else{

            $config['upload_path']= "./uploads/company/";
            $config['allowed_types']="doc|docx|pdf|ppt|jpeg|jpg|png|bmp";
            $config['encrypt_name'] = FALSE;
            $this->load->library('upload',$config);

            $licence = '';
            $insurence_certificate = '';
            
            if(isset($_FILES['licence']))
            {
                $licence = $_FILES['licence']['name'];
            }
            
            if(isset($_FILES['insurence_certificate']))
            {
                $insurence_certificate = $_FILES['insurence_certificate']['name'];
            }
                
            $data_val['name']                         = $this->post('name');
            $data_val['email']                        = $this->post('email');
            $data_val['phone_number']                 = $this->post('phone_number');
            $data_val['fax_number']                   = $this->post('fax_number');


            $query1 = $this->db->get_where('company',array('company_id!='=>$this->post('company_id'),'name'=>$this->post('name')))->num_rows();
        
            if ($query1 > 0){
                $response = array('status' => FAIL, 'message' => 'Company Name already taken.');
                $this->response($response);
                die;
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

		
			// end

            $this->db->where('company_id',$this->input->post('company_id'));
            $result = $this->db->update('company',$data_val);
			$msg = 'Company Updated Successfully';
			
			// multiple document upload
			if(isset($_POST['all_docs']))
			{
				$all_docs = json_decode($_POST['all_docs']);
							
				foreach($all_docs as $all_doc)
				{
					$array = array(
						'type' => 'company',
						'user_id' => $this->input->post('company_id'),
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
						'type' => 'company',
						'user_id' => $this->input->post('company_id'),
						'file_name' => $all_doc
					);
					$this->db->insert('license_media',$array);
				}
			}
            

            if($result){
                $response              = array('status'=>SUCCESS,'message'=>$msg,'url'=>base_url().'company');
            }else{
                $response              = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }        
        }
        $this->response($response);
    }//

    function activeInactiveStatus_post(){
        $id            = decoding($this->post('id'));
        $where              = array('company_id'=>$id);
        $dataExist          = $this->common_model->is_data_exists('company',$where);
        if($dataExist){
            $status         = $dataExist->status ? 0:1;
            $dataExist      = $this->common_model->updateFields('company',array('status'=>$status),$where);
            $showmsg        = ($status==1)? 'Company active successfully' : 'Company inactive successfully';
            $response       = array('status'=>SUCCESS,'message'=>$showmsg." ".ResponseMessages::getStatusCodeMessage(128));
        }else{
            $response       = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }//
    
    function tasklist_post()
    {
        $this->load->helper('text');
        $this->load->model('Companytask_model');
        $this->Companytask_model->set_data();
        $list       = $this->Companytask_model->get_list();
        $data       = array();
        $no         = $_POST['start'];
        // tasks meta
        foreach ($list as $value3) {
            $id = $value3->taskId;      
            $task_meta =$this->db->get_where('task_meta',
                    array(
                        'taskId'=>$id,
                        'fileType !='=>'TEXT'
                    )
            )->result();
            $value3->task_meta = $task_meta;
        }
        //end
        
        foreach ($list as $serData) { 
            $action = '';
            $no++;
            $row    = array();
            $row[]  = $no;
            $link_url      = base_url().'task-detail/'.encoding($serData->taskId);
            $row[]  = '<a href="'.$link_url.'">'.display_placeholder_text($serData->name).'</a>'; 
            $row[]      = display_placeholder_text((mb_substr($serData->description, 0,100, 'UTF-8') .((strlen($serData->description) >100) ? '...' : '')));
            $this->load->database();
            $company_id = $serData->company_id;
            $query = $this->db->get_where('company',array('company_id'=>$company_id));
            $rowsCount = $query->num_rows();
         

            $role = $serData->role;
            $company_name = 'User Not Found';
            if($role == 'company')
            {
                $query = $this->db->get_where('company',array('company_id'=>$company_id));
                $rowsCount = $query->num_rows();
               
                
                if($rowsCount>0)
                {
                    $company_data = $this->db->get_where('company',array('company_id'=>$company_id))->result();
                    if(isset($company_data[0]))
                        $company_name = $company_data[0]->name;   
                }
            }


             if($role == 'leadcontractor' || $role == 'subcontractor')
            {
                $query = $this->db->get_where('contractor',array('id'=>$serData->user_id));
                $rowsCount = $query->num_rows();
               
                
                if($rowsCount>0)
                {
                    $company_data = $this->db->get_where('contractor',array('id'=>$serData->user_id))->result();
                    if(isset($company_data[0]))
                        $company_name = $company_data[0]->company_name;   
                }
            }      



             if($role == 'crew')
            {
                $query = $this->db->get_where('crew_member',array('id'=>$serData->user_id));
                $rowsCount = $query->num_rows();
               
                
                if($rowsCount>0)
                {
                    $company_data = $this->db->get_where('crew_member',array('id'=>$serData->user_id))->result();
                    if(isset($company_data[0]))
                        $company_name = $company_data[0]->name;   
                }
            }      



             if($role == 'client')
            {
                $query = $this->db->get_where('client',array('id'=>$serData->user_id));
                $rowsCount = $query->num_rows();
               
                
                if($rowsCount>0)
                {
                    $company_data = $this->db->get_where('client',array('id'=>$serData->user_id))->result();
                    if(isset($company_data[0]))
                        $company_name = $company_data[0]->name;   
                }
            }      



            $row[]  = $company_name;
            
            $uploaded_media = '';
            foreach($serData->task_meta as $task_meta)
            {
                $uploaded_media      .= '<label> TYPE:'.$task_meta->fileType.'<br>FILENAME: '.$task_meta->file.'<br></label>';    
            }
            if($uploaded_media == "")
            {
                $uploaded_media = '<label>No Media Uploaded Yet..</label>';
            }
            $row[]  = $uploaded_media;
            $link    = 'javascript:void(0)';
            $action = '<a href="'.$link.'" onclick="confirmAction1(this);" data-message="Are You Want to Import This Task From Admin!" data-id="'.encoding($serData->taskId).'" data-url="adminapi/tasks/superAdminimport" data-list="1"  class="on-default edit-row table_action" title="Import"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>';
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$link.'" onclick="confirmAction1(this);" data-message="You want to Delete this record!" data-id="'.encoding($serData->taskId).'" data-url="adminapi/tasks/recordDeleteFromTaskList" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $row[]  = $action;
            $data[] = $row;

        }
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->Companytask_model->count_all(),
            "recordsFiltered"   => $this->Companytask_model->count_filtered(),
            "data"              => $data,
            "full_data"         => $list
        );
        $this->response($output);
    }


    public function alllist_post()
    {
        if(isset($_POST['limit']))
        {
            $limit= $this->post('limit');
            $this->db->limit($limit);    
        }
        
        if(isset($_POST['company_name']))
        {
            $company_name= $this->post('company_name');
            $this->db->like('name',$company_name);
        }
        
        $this->db->order_by("company_id", "desc"); 
        $data = $this->db->get('company')->result();
        foreach ($data as $key => $value) {
            $value->id = encoding($value->company_id);
            if($value->profile_photo != "")
            {
                $value->profile_photo = base_url('uploads/company/').$value->profile_photo;    
            }
            if($value->insurence_certificate != "")
            {
                $value->insurence_certificate = base_url('uploads/company/').$value->insurence_certificate;    
            }
            if($value->licence != "")
            {
                $value->licence = base_url('uploads/company/').$value->licence;    
            }
        }
        echo json_encode(array('success'=>true,'company_list'=>$data));
    }

    // single company detail
    public function companydetail_post()
    {
        $id = decoding($this->post('company_id'));      
        $data = $this->db->get_where('company',array('company_id'=>$id))->result();
        foreach ($data as $key => $value) {
            $value->id = encoding($value->company_id);
            if($value->profile_photo != "")
            {
                $value->profile_photo = base_url('uploads/company/').$value->profile_photo;    
            }
            if($value->insurence_certificate != "")
            {
                $value->insurence_certificate = base_url('uploads/company/').$value->insurence_certificate;    
            }
            if($value->licence != "")
            {
                $value->licence = base_url('uploads/company/').$value->licence;    
            }
        }
        echo json_encode($data[0]);
    }
    
    // import
    public function updateTask($table,$taskId,$array)
    {
        $this->db->where('taskId',$taskId);
        $this->db->update($table,$array);
    }
    
    function DuplicateMySQLRecord ($table, $primary_key_field, $primary_key_val)
    {
       $this->db->where($primary_key_field, $primary_key_val); 
       $query = $this->db->get($table);
        foreach ($query->result() as $row){   
           foreach($row as $key=>$val){        
              if($key != $primary_key_field){
                $this->db->set($key, $val);               
              }           
           }
        }
        $this->db->insert($table); 
        return $this->db->insert_id();
    }
    
    public function superAdminimport_post()
    {
        $taskId         = decoding($_POST['taskId']);
        $array = array('is_deleted'=>1);
        $this->updateTask('tasks',$taskId,$array);
        
        $insertId = $this->DuplicateMySQLRecord('tasks','taskId',$taskId);
        $taskId = $insertId;
        $array = array('created_by'=>1,'is_exported'=>1);
        $this->updateTask('tasks',$taskId,$array);
        
        $response   = array('status'=>SUCCESS,'message'=>'Imported Successfully');
        $this->response($response);
    }
    
    public function deleteDocs_post()
    {
        $type = $_POST['type'];
        $company_id = $_POST['company_id'];
        $msg = "";
        if($type == "L")
        {
            $data_val['licence']                      = "";
            $msg = 'Licenense Delete Successfully';
        }
        if($type == "C")
        {
            $data_val['insurence_certificate']        = "";
            $msg = 'Licenense Delete Successfully';
        }
        $this->db->where('company_id',$this->input->post('company_id'));
        $result = $this->db->update('company',$data_val);
        $response   = array('status'=>SUCCESS,'message'=>$msg);
        $this->response($response);
            
    }
    
   
}//End Class 
