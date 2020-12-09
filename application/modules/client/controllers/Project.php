<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Project extends Common_Back_Controller {
    public $data = "";
    function __construct() {
        parent::__construct();
        //$this->check_admin_user_session();
    }
    public function index(){
        $data['title']      = "Project";
        $count              = $this->common_model->get_total_count('project',array('company_id'=>$_SESSION['company_sess']['id']));
        $count              = number_format_short($count);
        $link               = base_url().'company/Project/add';
        $data['recordSet']  = array('<li class="sparks-info text-center"><h5>Add Project<span class="txt-color-blue"><a href="'.$link.'" class="anchor-btn"><i class="fa fa-plus-square"></i></a></span></h5></li>','<li class="sparks-info"><h5>Project <span class="txt-color-darken" id="totalCust"><i class="fa fa-lg fa-fw fa fa-book"></i>&nbsp;'.$count.'</span></h5></li>');
        $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/task.js');
        $this->load->company_render('project/index', $data,'');
    } //End function

    public function add() { 
        $data['title']              = 'Add Project';
        $data['crew_list']          = $this->common_model->getAll('crew_member',array('company_id'=>$_SESSION['company_sess']['id']));
        $data['contractor_list']          = $this->common_model->getAll('contractor',array('company_id'=>$_SESSION['company_sess']['id']));
        $company_id = $_SESSION['company_sess']['id'];
        $client_list = $this->db->select('company_member_relations.type, client.*')
                 ->from('company_member_relations')
                 ->join('client', 'company_member_relations.member_id = client.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->where('company_member_relations.type','client')->order_by("id", "desc")->get()->result();   
        $data['client_list'] = $client_list;
        $data['project_category_list']          = $this->common_model->getAll('project_category');
        $data['company_id']         = $_SESSION['company_sess']['id'];
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->company_render('project/add', $data);
    } //End Function

    public function edit() {
        $id             = decoding(end($this->uri->segment_array()));
        $where              = array('id'=>$id);
        $result             = $this->common_model->getsingle('project',$where);
        $data['title']              = 'Edit Project';
        $data['crew_list']          = $this->common_model->getAll('crew_member',array('company_id'=>$_SESSION['company_sess']['id']));
        $data['contractor_list']          = $this->common_model->getAll('contractor',array('company_id'=>$_SESSION['company_sess']['id']));
        $company_id = $_SESSION['company_sess']['id'];
        $client_list = $this->db->select('company_member_relations.type, client.*')
                 ->from('company_member_relations')
                 ->join('client', 'company_member_relations.member_id = client.id')
                 ->where('company_member_relations.company_id',$company_id)
                 ->where('company_member_relations.type','client')->order_by("id", "desc")->get()->result();   
        $data['client_list'] = $client_list;
        $data['project_category_list']          = $this->common_model->getAll('project_category');
        $data['company']              = $result;
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->company_render('project/edit', $data);
    } //End Function
    
    // filter docc
    public function getFileterTask()
    {
        $taskId1 = $this->input->post('taskId');
        $id = $this->input->post('id');
        if($taskId1 != -1)
        {
            $this->db->where('tag', $taskId1);   
        }
        $this->db->where('project_id', $id);
        $this->db->where('file_type', 'docs');
        $project_docs = $this->db->get('chat')->result();
        
        foreach($project_docs as $project_doc)
        {
            $project_doc->file = $project_doc->file;
            $project_doc->file_path = base_url('uploads/project/documents/').$project_doc->file;
        }
        
        $contentHtml = "";
        
        foreach($project_docs as $projectDoc)
        {
            $contentHtml .= '<div style="margin-bottom:4px;" class="col-sm-6 text-center">';
            if(@is_array(getimagesize($projectDoc->file_path))){
                $image = 1;
            } else {
                $image = 0;
            }
            $newLink = $projectDoc->file_path;
            $onclick = "window.open('".$newLink."', '_blank')";
            $contentHtml .= '<div class="custom-file" onclick="'.$onclick.'"><span class="hiddenFileInput"></span><span class="time_date">'.date('D M Y h:i:a',strtotime($projectDoc->created_at)).'</span><label>'.$projectDoc->file.'</label></div>';
            
            if($image == 0)
		    {
		        //$contentHtml .= '<object style="width:100%; overflow:hidden;" src="'.$projectDoc->file.'"><iframe style="height:400px;" src="https://docs.google.com/viewer?url='.$projectDoc->file_path.'&embedded=true"></iframe></object>';-->
		    }else{
		        //$contentHtml .= '<img style="display:block;margin:auto;height:400px;" src="'.$projectDoc->file_path.'" class="img-responsive"  alt="img">'.$projectDoc->file.'<br>'.date("M, d Y h:m A" ,strtotime($projectDoc->created_at));-->
		    }
            $contentHtml .= '</div>'; 
       }
        
       echo json_encode($contentHtml);
    }
    
    //getFileterTaskByName
    public function getTaskDocumentByName()
    {
        date_default_timezone_set("Asia/Kolkata");
        $file = $this->input->post('file');
        $id = $this->input->post('id');
        if($file !== "")
        {
            $this->db->like('file', $file);   
        }
        $this->db->where('project_id', $id);
        $this->db->where('file_type', 'docs');
        $project_docs = $this->db->get('chat')->result();
        
        foreach($project_docs as $project_doc)
        {
            $project_doc->file = $project_doc->file;
            $project_doc->file_path = base_url('uploads/project/documents/').$project_doc->file;
        }
        
        $contentHtml = "";
        
        foreach($project_docs as $projectDoc)
        {
            $contentHtml .= '<div style="margin-bottom:4px;" class="col-sm-6 text-center">';
            if(@is_array(getimagesize($projectDoc->file_path))){
                $image = 1;
            } else {
                $image = 0;
            }
            $newLink = $projectDoc->file_path;
            $onclick = "window.open('".$newLink."', '_blank')";
            $contentHtml .= '<div class="custom-file" onclick="'.$onclick.'"><span class="hiddenFileInput"></span><span class="time_date">'.date('D M Y h:i:a',strtotime($projectDoc->created_at)).'</span><label>'.$projectDoc->file.'</label></div>';
            $contentHtml .= '</div>'; 
       }
        
        echo json_encode($contentHtml);
    }
    //end
    //getFileterTaskByName
    public function getTaskDocumentByNameApi()
    {
        $file = $this->input->post('file');
        $project_id = $this->input->post('project_id');
        $taskId1 = $this->input->post('task_id');
        if($taskId1 != -1)
        {
            $this->db->where('tag', $taskId1);   
        }
        if($file != "")
        {
            $this->db->like('file', $file);   
        }
        $this->db->where('project_id', $project_id);
        $this->db->where('file_type', 'docs');
        $project_docs = $this->db->get('chat')->result();
        
        foreach($project_docs as $project_doc)
        {
            $project_doc->file = $project_doc->file;
            $project_doc->file_path = base_url('uploads/project/documents/').$project_doc->file;
        }
        $response = array('status'=>SUCESS,'message'=>'','data'=>$project_docs);
        echo json_encode($response);
    }
    // end
    
    public function detail(){
        $id             = decoding(end($this->uri->segment_array()));
        $where              = array('id'=>$id);
        $result             = $this->common_model->getsingle('project',$where);
        $data['project']       = $result;
        $data['title']      = $result['name'];
        if(isset($_POST['filter']))
        {
            if($_POST['task_status'] != -1 && $_POST['task_name'] == "")
            {
                $task_status =  $_POST['task_status'];
                $task_list = $this->common_model->getAll('tasks',array('project_id'=>$id,'task_status'=>$task_status,'created_by'=>0),'taskId','desc');
            }else if($_POST['task_status'] == -1 && $_POST['task_name'] != "")
            {
                $task_name =  $_POST['task_name'];
                $this->db->select('*');
                $this->db->from('tasks');
                $this->db->where('project_id', $id);
                $this->db->where('created_by', 0);
                $this->db->order_by('taskId','desc');
                $this->db->like('name', $task_name);
                $task_list = $this->db->get()->result();
            }else if($_POST['task_status'] == -1 && $_POST['task_name'] == "")
            {
                $task_list = $this->common_model->getAll('tasks',array('project_id'=>$id,'created_by'=>0));
            }else if($_POST['task_status'] != -1 && $_POST['task_name'] != "")
            {
                $task_status =  $_POST['task_status'];
                $task_name =  $_POST['task_name'];
                $this->db->select('*');
                $this->db->from('tasks');
                $this->db->where('project_id', $id);
                $this->db->where('created_by', 0);
                $this->db->order_by('taskId','desc');
                $this->db->where('task_status', $task_status);
                $this->db->like('name', $task_name);
                $task_list = $this->db->get()->result();
            }
        }else{
            $task_list = $this->common_model->getAll('tasks',array('project_id'=>$id,'created_by'=>0),'taskId','desc'); 
        }
        
        foreach($task_list as $value)
        {
            $taskId = $value->taskId;
            $task_meta              = $this->common_model->getAll('task_meta',array('taskId'=>$taskId),'sorting_order','asc');  
            $value->meta_data = $task_meta;
        }
        
        $pretask_list = $this->common_model->getAll('tasks',array('created_by'=>1,'status'=>1));
        
        foreach($pretask_list as $pre){
            $task_meta              = $this->common_model->getAll('task_meta',array('taskId'=>$pre->taskId),'sorting_order','asc');
            $pre->task_meta = $task_meta;
        }
        
        $all_tag_list = $this->common_model->getAll('tasks',array('project_id'=>$id,'created_by'=>0),'taskId','desc');
        foreach($all_tag_list as $value)
        {
            $taskId = $value->taskId;
            $task_meta              = $this->common_model->getAll('task_meta',array('taskId'=>$taskId),'sorting_order','asc');  
            $value->meta_data = $task_meta;
        }
        
        $data['task_list']          = $task_list;
        $data['all_tag_list']          = $all_tag_list;
        $data['pretask_list']          = $pretask_list;
        $data['crew_list']          = $this->common_model->getAll('crew_member',array('company_id'=>$_SESSION['company_sess']['id']));
        $data['contractor_list']          = $this->common_model->getAll('contractor',array('company_id'=>$_SESSION['company_sess']['id']));
        $data['client_list']          = $this->common_model->getAll('client',array('company_id'=>$_SESSION['company_sess']['id']));
        $data['project_category_list']          = $this->common_model->getAll('project_category');
        $data['company_id']         = $_SESSION['company_sess']['id'];
        
        $client_id = $result['client'];
        $client_data = $this->db->get_where('client',array('id'=>$client_id))->result();
        
        if(isset($client_data[0]))
            $client_data = $client_data[0];
        else
            $client_data = "";
        $data['client_data'] = $client_data ;
        
        
        $project_docs = $this->db->get_where('chat',array('project_id'=>$id,'file_type'=>'docs'))->result();
        foreach($project_docs as $project_doc)
        {
            $project_doc->file = $project_doc->file;
            $project_doc->file_path = base_url('uploads/project/documents/').$project_doc->file;
        }
        $data['project_docs'] = $project_docs;
    
        
        
        $this->db->select('company_member_relations.*, contractor.*')->distinct()->from('company_member_relations')
         ->join('contractor', 'company_member_relations.member_id = contractor.id')->where('company_member_relations.type','leadcontractor')->where('company_member_relations.company_id',$_SESSION['company_sess']['id']);
        $data['existing_contractor'] = $this->db->get()->result();
        
        
        $this->db->select('company_member_relations.*, crew_member.*')
         ->distinct()
         ->from('company_member_relations')
         ->join('crew_member', 'company_member_relations.member_id = crew_member.id')->where('company_member_relations.type','crew')->where('company_member_relations.company_id',$_SESSION['company_sess']['id']);
        $data['existing_crew_member'] = $this->db->get()->result();
        
        
        $data['front_styles']      = array('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        $data['front_scripts']      = array('https://code.jquery.com/ui/1.12.1/jquery-ui.js','backend_assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js','backend_assets/custom/js/task.js');
        $this->load->company_render('project/detail', $data,'');
    } //End function
    
    public function prepopulatedTask()
    {
        $checkboxVals = $this->input->post('checkboxVals');
        
    }
    
    public function gettaskMetaData(){
        $id = $this->input->post('taskId');      
        $task_meta =$this->db->get_where('task_meta',array('taskId'=>$id))->result();
        echo json_encode($task_meta);
    }
    /***********************************************/
    public function searchpeoplechat(){
        $value_search = $this->input->post('value');
        if($value_search!="")
        {
            $this->db->like('name',$value_search);
        }
        $chat_peoples = $this->db->get('crew_member')->result();
        echo json_encode(array('status'=>'success','chat_peoples_member'=>$chat_peoples));
    }
    /***********************************************/    
    public function searchmembers()
    {
        $project_id = $this->input->post('project_id');
        $value_search = $this->input->post('value');
        if($value_search != "")
        {
            $this->db->group_start();
            $this->db->like('role',$value_search);   
            $this->db->or_like('person_name',$value_search);
            $this->db->group_end();
        }
        $this->db->where('project_id',$project_id);
        $invite_peoples = $this->db->get('invite_people')->result();
        $lastquery = $this->db->last_query();
        $invite_peoples_count = 0;
        $nnoninvite_peoples_count = 0;
        // foreach($invite_peoples as $invite_people)
        // {
        //     if($invite_people->is_removed == 0)
        //     {
        //         $invite_peoples_count = $invite_peoples_count + 1;
        //     }else{
        //         $nnoninvite_peoples_count = $nnoninvite_peoples_count + 1;
        //     }
        // }
        $involved_members_html = "";
        $noninvolved_members_html = "";
        // $involved_members_html = '<div><h4 style="font-weight: 500; margin-left: 15px; margin-bottom: 10px;">'.$invite_peoples_count.' members</h4></div>';
        // $noninvolved_members_html = '<div style="margin-left: 15px;margin-top: 20px;margin-right: 15px;margin-bottom: 13px;font-size: 20px;><span style="font-weight: 500;">Removed members</span></div>';
        // $noninvolved_members_html .= '<div><h4 style="font-weight: 500; margin-left: 15px; margin-bottom: 10px;">'.$nnoninvite_peoples_count.' members</h4></div>';
        foreach($invite_peoples as $invite_people)
        {
            $people_name = "";
            $people_position = "";
            $assigned_to = "";
            $people_email = "";
            if($invite_people->role == 'leadcontractor'){
                $this->db->where('id',$invite_people->user_id);
                $this->db->where('is_role',1);
                $contractor_data = $this->db->get('contractor')->result();
                if(isset($contractor_data[0]))
                {
                    $contractor_data = $contractor_data[0];
                    $people_name = $contractor_data->owner_first_name;
                    $people_position = 'Lead Contractor';
                    $people_email = $contractor_data->email;    
                }
            }else if($invite_people->role == 'subcontractor'){
                $this->db->where('id',$invite_people->user_id);
                $this->db->where('is_role',2);
                $contractor_data = $this->db->get('contractor')->result();
                if(isset($contractor_data[0]))
                {
                    $contractor_data = $contractor_data[0];
                    $people_name = $contractor_data->owner_first_name;
                    $people_position = 'Sub Contractor';     
                    $people_email = $contractor_data->email;
                }
            }else if($invite_people->role == 'crew'){
                $this->db->where('id',$invite_people->user_id);
                $crew_data = $this->db->get('crew_member')->result();
                if(isset($crew_data[0]))
                {
                    $crew_data = $crew_data[0];
                    $people_name = $crew_data->name;
                    $people_position = 'Crew Member'; 
                    $people_email = $crew_data->email;
                }
            }
            if($people_name != "" && $people_email != "" && $people_position != "")
            {
                $onlick = "'".$people_name."',"."'".$people_position."',"."'".$people_email."',"."'".$invite_people->id."',"."'$invite_people->is_removed'";
                if($invite_people->is_removed == 0)
                {
                    $invite_peoples_count++;
                    $involved_members_html .= '<div class="col-sm-2" onclick="getprofiledetail('.$onlick.')"><div class="card" style="border: none;"><img class="card-img-top" src="https://img.icons8.com/officel/2x/user.png" alt="Card image" style="width:100%;border:none;background: #d85e5e;    border-top-left-radius: 10px;border-top-right-radius: 10px;"><div class="card-body" style="border-bottom-left-radius: 10px;border: 1px solid #eadfdf;border-bottom-right-radius: 10px;"><h4 class="card-title" style="font-weight: 500;margin-bottom: 10px;font-size:16px;">'.$people_name.'<br>'.$people_position.'</h4><a id="action_'.$invite_people->id.'" style="display:none;" href="javascript:void(0)" onclick="removePeople('.$invite_people->id.')" <i class="fa fa-trash fa-2x text-danger" aria-hidden="true"></i></a></div></div></div>';                
                }else{
                    $nnoninvite_peoples_count++;
                    $noninvolved_members_html .= '<div class="col-sm-2" onclick="getprofiledetail('.$onlick.')"><div class="card" style="border: none;"><img class="card-img-top" src="https://img.icons8.com/officel/2x/user.png" alt="Card image" style="width:100%;border:none;background: #d85e5e;    border-top-left-radius: 10px;border-top-right-radius: 10px;"><div class="card-body" style="border-bottom-left-radius: 10px;border: 1px solid #eadfdf;border-bottom-right-radius: 10px;"><h4 class="card-title" style="font-weight: 500;margin-bottom: 10px;font-size:16px;">'.$people_name.'<br>'.$people_position.'</h4><a id="action_'.$invite_people->id.'" style="display:none;" href="javascript:void(0)" onclick="addPeople('.$invite_people->id.')" <i class="fa fa-trash fa-2x text-danger" aria-hidden="true"></i></a></div></div></div>';                
                }
            }
        }
        echo json_encode(array('status'=>'success','involved_members'=>$involved_members_html,'noninvolved_members'=>$noninvolved_members_html,'invite_peoples_count'=>$invite_peoples_count,'nnoninvite_peoples_count'=>$nnoninvite_peoples_count));
    }
    
    
  
}//End Class