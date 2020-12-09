<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Tasksapi extends REST_Controller{
    public function __construct(){
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->load->model('Client_model');
        $check = $this->Client_model->checkAuthToken();
        if(!$check)
        {
            $response       = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(101),'data'=>array());
            $this->response($response);
        }
    }
    
    public function detail_post()
    {
        $id = decoding($this->post('task_id'));      
        $data = $this->db->get_where('tasks',array('taskId'=>$id))->result();
        foreach ($data as $key => $value) {
            $this->db->order_by('sorting_order','asc');
            $meta_data = $this->db->get_where('task_meta',array('taskId'=>$id))->result();
            $value->id = encoding($value->taskId);
            foreach ($meta_data as $key2 => $value2) {
                $value2->newId = encoding($value2->taskmetaId);
                if($value2->file != "")
                {
                    if($value2->fileType=='IMAGE')
                    {
                        $value2->file = base_url('uploads/task_image/').$value2->file;
                    }else if($value2->fileType=='VIDEO'){
                        $value2->file = base_url('uploads/task_video/').$value2->file;    
                    }
                        
                }
            }
            $value->meta_data = $meta_data;
        }
        
        $task_detail              = $data[0];
        
        $involved_people = array();
        $noninvolved_people = array();
        $invite_people_data = $this->db->get_where('invite_people',array('taskId'=>$id))->result();
        foreach($invite_people_data as $value)
        {
            if($value->role == 'leadcontractor' || $value->role == 'subcontractor')
            {
                $user_data = $this->db->get_where('contractor',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->owner_last_name;
                $value->user_email = $user_data[0]->email;
            }else if($value->role == 'crew'){
                $user_data = $this->db->get_where('crew_member',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->name;
                $value->user_email = $user_data[0]->email;
            }
            if($value->is_removed == 0)
            {
                array_push($involved_people,$value);
            }else{
                array_push($noninvolved_people,$value);
            }
        }

        $response = array('success'=>true,'data'=>$task_detail,'involved_people'=>$involved_people,'noninvolved_people'=>$noninvolved_people);
        echo json_encode($response);
    }
    
    public function updateTask($table,$taskId,$array)
    {
        $this->db->where('taskId',$taskId);
        $this->db->update($table,$array);
    }
        
    function activeInactiveStatus_post(){
        $id            = decoding($this->post('id'));
        $where              = array('taskId'=>$id);
        $dataExist          = $this->common_model->is_data_exists('tasks',$where);
        if($dataExist){
            $status         = $dataExist->status ? 0:1;
            $dataExist      = $this->common_model->updateFields('tasks',array('status'=>$status),$where);
            $showmsg        = ($status==1)? 'Task published successfully' : 'Task Unpublished successfully';
            $response       = array('status'=>SUCCESS,'message'=>$showmsg." ".ResponseMessages::getStatusCodeMessage(128));
        }else{
            $response       = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }//end function
    function recordDelete_post(){
          $id            = decoding($this->post('id'));
        $where              = array('taskId'=>$id);
        $dataExist      = $this->common_model->is_data_exists('tasks',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('tasks',$where);
            $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }//end function
    function recordDeleteMeta_post(){
          $id            = decoding($this->post('id'));
        $where              = array('taskmetaId'=>$id);
        ///pr($where);
        $dataExist      = $this->common_model->is_data_exists('task_meta',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('task_meta',$where);
            $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }
        $this->response($response);
    }//end function
    function recordorderMeta_post(){
          $data            = $this->post();
          $orders = isset($data[0]) ?  json_decode($data[0],true) : array();
          $i=1;
          foreach ($orders as $k => $v) {
             $task_metaId = isset($v['metaid']) ? $v['metaid'] :0;
             if(!empty($task_metaId)){
                $where = array();
                $where              = array('taskmetaId'=>$task_metaId);
                $dataExist      = $this->common_model->is_data_exists('task_meta',$where);
                if($dataExist){
                        $this->common_model->updateFields('task_meta',array('sorting_order'=>$i),$where);
                        $i++;
                    $response   = array('status'=>SUCCESS,'message'=>'Record has re-arrange successfully.');
                }else{
                    $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
                }
             }
          }
        /*$where              = array('taskmetaId'=>$id);
        ///pr($where);
        $dataExist      = $this->common_model->is_data_exists('task_meta',$where);
        if($dataExist){
            $dataExist  = $this->common_model->deleteData('task_meta',$where);
            $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        }else{
            $response  = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));  
        }*/
        $this->response($response);
    }//end function
    // Compress image
    function compressedImage($source, $path, $quality) 
    {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);

        // Save image 
        imagejpeg($image, $path, $quality);
        // sReturn compressed image 
        return $path;

    }//End function
    public function alllist_get()
    {
        if(isset($_POST['limit']))
        {
            $limit= $this->post('limit');
            $this->db->limit($limit);    
        }
        $data = $this->db->get('tasks')->result();
        foreach ($data as $key => $value) {
           $value->id = encoding($value->taskId);
        }
        
        echo json_encode(array('success'=>true,'task_list'=>$data));
    }
    // single tasks detail
    public function taskdetail_post()
    {
        $id = decoding($this->post('task_id'));      
        $data = $this->db->get_where('tasks',array('taskId'=>$id))->result();
        foreach ($data as $key => $value) {
            $this->db->order_by('taskmetaId','desc');
            $meta_data = $this->db->get_where('task_meta',array('taskId'=>$id))->result();
            $value->id = encoding($value->taskId);
            foreach ($meta_data as $key2 => $value2) {
                $value2->file = base_url('uploads/').$value2->file;
            }
            $value->meta_data = $meta_data;
        }
        
        $task_detail              = $data[0];

        $response = array('success'=>true,'task_detail'=>$task_detail);
        echo json_encode($response);
    }
    
    // invite people 
    public function invited_task_people_post()
    {
        $id = $this->post('task_id');
        $name = $this->post('name');
        
        $this->db->where('taskId',$id);
        if($name != "")
        {
            $this->db->like('person_name', $name);
        }
        $invite_people_data = $this->db->get('invite_people')->result();
        
        $involved_people_array = array();
        $non_involved_people_array = array();
        foreach($invite_people_data as $value)
        {
            if($value->role == 'leadcontractor')
            {
                $user_data = $this->db->get_where('contractor',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->owner_last_name;
                $value->user_email = $user_data[0]->owner_first_name.' '.$user_data[0]->email;
            }else{
                $user_data = $this->db->get_where('crew_member',array('id'=>$value->user_id))->result();
                $value->user_name = $user_data[0]->owner_first_name.' '.$user_data[0]->name;
                $value->user_email = $user_data[0]->owner_first_name.' '.$user_data[0]->email;
            }
            if($value->is_removed == 0)
            {
                array_push($involved_people_array,$value);
            }else{
                array_push($non_involved_people_array,$value);
            }
        }
        $response = array('success'=>true,'involved_people_list'=>$involved_people_array,'non_involved_people_list'=>$non_involved_people_array);
        echo json_encode($response);
    }
    
    //Remove Task People
    // invite people 
    public function invited_task_remove_people_post()
    {
        $id = $this->post('task_id');
        $user_id = $this->post('user_id');
        
        $array = array(
            'is_removed' => 1
        );
        $this->db->where('taskId',$id);
        $this->db->where('user_id',$user_id);
        $this->db->update('invite_people',$array);
        
        $response = array('success'=>true,'message'=>'People Removed Successfully');
        echo json_encode($response);
    }
    
    // Task Detail Api
    public function taskDetailApi_post()
    {
        $id = $this->post('task_id');
        $task_data = $this->db->get_where('tasks',array('taskId'=>$id))->result();
        
        $task_meta_data = $this->db->get_where('task_meta',array('taskId'=>$id))->result();
        
        $response = array('success'=>true,'task_data'=>$task_data,'task_meta_data'=>$task_meta_data);
        echo json_encode($response);
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}//End Class 