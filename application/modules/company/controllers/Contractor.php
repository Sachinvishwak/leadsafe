<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contractor extends Common_Back_Controller {

    public $data = "";
    function __construct() {
        parent::__construct();
        //$this->check_admin_user_session();
    }
    public function index(){
        $data['title']      = "Lead Contractor";
        // $count              = $this->common_model->get_total_count('contractor',array('company_id'=>$_SESSION['company_sess']['id']));
        // $count              = number_format_short($count);
        $count                 = $this->db->select('company_member_relations.type, contractor.*')
                                ->from('company_member_relations')
                                ->join('contractor', 'company_member_relations.member_id = contractor.id')
                                ->where('company_member_relations.company_id',$_SESSION['company_sess']['id'])
                                ->where('contractor.is_role',1)
                                ->where('company_member_relations.type','leadcontractor')->distinct()->get()->num_rows(); 
        $link               = base_url().'company/Contractor/add';
        $data['recordSet']  = array('<li class="sparks-info text-center"><h5>Add  Contractor<span class="txt-color-blue"><a href="'.$link.'" class="anchor-btn"><i class="fa fa-plus-square"></i></a></span></h5></li>','<li class="sparks-info"><h5>Contractor<span class="txt-color-darken" id="totalCust"><i class="fa fa-lg fa-fw fa fa-graduation-cap"></i>&nbsp;'.$count.'</span></h5></li>');
        $data['front_scripts']  = array('backend_assets/custom/js/common_datatable.js',
            'backend_assets/custom/js/task.js');
        $this->load->company_render('contractor/index', $data,'');
    } //End function
    
    function getCity()
    {
        $state_id = $_POST['state_id'];
        //$cities = $this->db->get_where('cities',array('state_id'=>$state_id))->result();
        $cities             = $this->common_model->getAll('cities',array('state_id'=>$state_id));
        $html ='';
        foreach($cities as $value)
        {
            $html .= '<option value="'.$value->id.'">'.$value->name.'</option>';
        }
        echo json_encode($html);
    }

    public function add() { 
        $data['category']             = $this->common_model->getAll('category');
        $data['states']             = $this->common_model->getAll('states',array('country_id'=>'231'));
        $data['title']              = 'Add Contractor';
        $data['company_id']         = $_SESSION['company_sess']['id'];
        $data['front_scripts']      = array('backend_assets/custom/js/task.js');
        $this->load->company_render('contractor/add', $data);
    } //End Function

    public function edit() {
        $data['category']             = $this->common_model->getAll('category');
        $data['states']             = $this->common_model->getAll('states');
        $data['cities']             = $this->common_model->getAll('cities');
        $data['company_id'] = $_SESSION['company_sess']['id'];
		$id = decoding(end($this->uri->segment_array()));
		

		$license_media = $this->db->get_where('license_media',array('type'=>'contractor','user_id'=>$id))->result();
		
		$data['license_media'] = $license_media;

        $where = array('id'=>$id);
        $result = $this->common_model->getsingle('contractor',$where);
        $data['title'] = 'Edit Contractor';
        $data['company'] = $result;
        $data['front_scripts']= array('backend_assets/custom/js/task.js');
        $this->load->company_render('contractor/edit', $data);
    } //End Function
    
    public function detail(){
        $id             = decoding(end($this->uri->segment_array()));
        $data['title']      = "Contractor Detail";
        $where              = array('id'=>$id);
        $result             = $this->common_model->getsingle('contractor',$where);
        $data['task']       = $result;
        $where              = array('id'=>$data['task']['state']);
        $data['states']            = $this->common_model->getsingle('states',$where);
        $where              = array('id'=>$data['task']['city']);
        $data['cities']            = $this->common_model->getsingle('cities',$where);
        $data['front_styles']      = array('http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
		$data['front_scripts']      = array('https://code.jquery.com/ui/1.12.1/jquery-ui.js','backend_assets/js/plugin/bootstrapvalidator/bootstrapValidator.min.js','backend_assets/custom/js/task.js');
		
		$license_media = $this->db->get_where('license_media',array('type'=>'contractor','user_id'=>$id))->result();
		
		$data['license_media'] = $license_media;

        $this->load->company_render('contractor/detail', $data,'');
    } //End function
  
}//End Class
