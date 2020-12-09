<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Client extends Common_Back_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        //$this->check_admin_user_session();
    }
    public function index(){
        $data['title']      = "Client";
        // $count              = $this->common_model->get_total_count('client',array('company_id'=>$_SESSION['company_sess']['id']));
        // $count              = number_format_short($count);
        $count = $this->db->select('company_member_relations.type, client.*')
                            ->from('company_member_relations')
                            ->join('client', 'company_member_relations.member_id = client.id')
                            ->where('company_member_relations.company_id',$_SESSION['company_sess']['id'])
                            ->where('company_member_relations.type','client')->order_by("id", "desc")->get()->num_rows();
        $link               = base_url().'company/Client/add';
        $projectList = $this->db->get_where('project',array('company_id'=>$_SESSION['company_sess']['id']))->result();
        $data['projectList'] = $projectList;
        $data['recordSet']  = array('<li class="sparks-info text-center"><h5>Invite Clients<span class="txt-color-blue"><a href="#" onclick="InviteClients()" class="anchor-btn"><i class="fa fa-arrow-up"></i></a></span></h5></li>','<li class="sparks-info text-center"><h5>Add Client<span class="txt-color-blue"><a href="'.$link.'" class="anchor-btn"><i class="fa fa-plus-square"></i></a></span></h5></li>','<li class="sparks-info"><h5>Client <span class="txt-color-darken" id="totalCust"><i class="fa fa-lg fa-fw fa fa-users"></i>&nbsp;'.$count.'</span></h5></li>');
        $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/task.js');
        $this->load->company_render('client/index', $data,'');
    } //End function

    public function add() { 
        
        $lastClient = $this->db->get_where('company_member_relations',array('company_id'=>$_SESSION['company_sess']['id'],'type'=>'client'))->result();
        $lastClientName = "";
        $lastClientEmail = "";
        $lastClientPhone = "";
        $lastClientAddress = "";
        if(isset($lastClient[0]))
        {
            $client_id = $lastClient[0]->member_id;
            $lastClient = $this->db->get_where('client',array('id'=>$client_id))->result();
            $lastClient = $lastClient[0];
            $lastClientName = $lastClient->name;
            $lastClientEmail = $lastClient->email;
            $lastClientPhone = $lastClient->phone;
            $lastClientAddress = $lastClient->address;
        }
        $data['lastClientName'] = $lastClientName ;
        $data['lastClientEmail'] = $lastClientEmail ;
        $data['lastClientPhone'] = $lastClientPhone ;
        $data['lastClientAddress'] = $lastClientAddress;
        
        $projectList = $this->db->get_where('project',array('company_id'=>$_SESSION['company_sess']['id']))->result();
        $data['projectList'] = $projectList;
        
        $data['title']              = 'Add Client';
        $data['company_id']         = $_SESSION['company_sess']['id'];
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->company_render('client/add', $data);
    } //End Function

    public function edit() {
        $id             = decoding(end($this->uri->segment_array()));
        $where              = array('id'=>$id);
        $result             = $this->common_model->getsingle('client',$where);
        $data['title']              = 'Edit Client';
        $data['company']              = $result;
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->company_render('client/edit', $data);
    } //End Function
    
    public function detail(){
        $id             = decoding(end($this->uri->segment_array()));
        $data['title']      = "Client Detail";
        $where              = array('id'=>$id);
        $result             = $this->common_model->getsingle('client',$where);
        $data['task']       = $result;
        $data['front_styles']      = array('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        $data['front_scripts']      = array('https://code.jquery.com/ui/1.12.1/jquery-ui.js','backend_assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js','backend_assets/custom/js/task.js');
        $this->load->company_render('client/detail', $data,'');
    } //End function
  
}//End Class