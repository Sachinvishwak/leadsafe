<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notificationapi extends REST_Controller {

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

    public function list_post(){
        $this->load->helper('text');
        $this->load->model('Notification_model');
        $this->Notification_model->set_data();
        $list       = $this->Notification_model->get_list();
        $data       = array();
        $no         = $_POST['start'];
        foreach ($list as $serData) { 
            $action = '';
            $no++;
            $row    = array();
            $row[]  = $no;
            $link_url      = base_url().'admin/crew-detail/'.encoding($serData->id);
            $row[]  = display_placeholder_text($serData->message); 
            
            $link    = 'javascript:void(0)';
            $action .= "";
            if($serData->is_approve == 1){
				$row[]  = "Approved"; 
            }else{
                $row[]  = "Not Approved"; 
			}
			$row[]  = $serData->created_at;
            $row[]  = $serData->created_at; 
			$action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to Delete this record!" data-id="'.encoding($serData->id).'" data-url="company/Notificationapi/recordDelete" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a> &nbsp| ';
			if($serData->is_approve == 0)
			{
				$action .= '&nbsp';
            	$action .= '<a href="'.$link.'" onclick="confirmAction(this);" data-message="You want to Accept this Request!" data-id="'.encoding($serData->id).'" data-url="company/Notificationapi/recordUpdate" data-list="1"  class="on-default edit-row table_action" title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
			}
            $row[]  = $action;
            $data[] = $row;
        }
        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->Notification_model->count_all(),
            "recordsFiltered"   => $this->Notification_model->count_filtered(),
            "data"              => $data,
        );
        //output to json format
        $this->response($output);
    }
    
    public function recordDelete_post()
    {
        $id            = decoding($this->post('id'));
        $where              = array('id'=>$id);
        $dataExist  = $this->common_model->deleteData('task_approve_notifications',$where);
        $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        $this->response($response);
	}
	
	public function recordUpdate_post()
    {
		$id            = decoding($this->post('id'));
		$task_approve_notifications = $this->db->get_where('task_approve_notifications',array('id'=>$id))->result();
		$task_approve_notifications = $task_approve_notifications[0];
		if($task_approve_notifications->type == 'task')
		{
			if($task_approve_notifications->action == 'add')
			{
				$this->db->where('id',$task_approve_notifications->task_id);
				$array1 = array('task_approved'=>1);
				$this->db->update('tasks',$array1);	
			}else if($task_approve_notifications->action == 'delete')
			{
				$this->db->where('id',$task_approve_notifications->task_id);
				$this->db->delete('tasks');
			}
			else if($task_approve_notifications->action == 'edit')
			{
				$this->db->where('id',$task_approve_notifications->task_id);
				$array1 = array(
					'name'=>$task_approve_notifications->name,
					'description'=>$task_approve_notifications->description
				);
				$this->db->update('tasks',$array1);
			}
		}else if($task_approve_notifications->type == 'tasksteps')
		{
			if($task_approve_notifications->action == 'add')
			{
				$this->db->where('taskmetaId',$task_approve_notifications->task_setps_id);
				$array1 = array('task_approved'=>1);
				$this->db->update('task_meta',$array1);
			}else if($task_approve_notifications->action == 'delete')
			{
				$this->db->where('taskmetaId',$task_approve_notifications->task_setps_id);
				$this->db->delete('task_meta');
			}else if($task_approve_notifications->action == 'edit')
			{
				$this->db->where('taskmetaId',$task_approve_notifications->task_setps_id);
				$array1 = array(
					'description'=>$task_approve_notifications->description
				);
				$this->db->update('task_meta',$array1);
			}
		}
		$array = array(
			'is_approve' => 1
		);
		$this->db->where('id',$id);
		$this->db->update('invite_people',$array);
        $response   = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(124));
        $this->response($response);
    }
 
}//End Class
