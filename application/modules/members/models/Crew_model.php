<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crew_model extends CI_Model {
     
    //var $table , $column_order, $column_search , $order =  '';
    var $table          = 'crew_member';
    var $column_order   = array('id','name','email','address'); //set column field database for datatable orderable
    
    var $column_sel     = array('*',
        'status',
        '(case when (status = 0) 
        THEN "Inactive" when (status = 1) 
        THEN "Active" ELSE
        "Unknown" 
        END) as statusShow'); 

    var $column_search = array('name','email','address','phone_number'); //set column field database for datatable searchable 
    var $order         = array('id' => 'desc');  // default order
    var $where         = array();
    var $group_by      = 'id'; 

    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($where=''){
        $this->where = $where; 
    }//End function

    private function _get_query()
    {
        $sel_fields = array_filter($this->column_sel); 
        // $this->db->select($sel_fields);
        // $this->db->from($this->table);
        
        
        $this->db->select('company_member_relations.type, crew_member.*')
                 ->from('company_member_relations')
                 ->join('crew_member', 'company_member_relations.member_id = crew_member.id')
                 ->where('company_member_relations.company_id',$_SESSION['company_sess']['id'])
                 ->where('company_member_relations.type','crew')->order_by("crew_member.id", "desc");
        
        $i = 0;
        foreach ($this->column_search as $emp) // loop column 
        {
            if(isset($_POST['search']['value']) && !empty($_POST['search']['value'])){
            $_POST['search']['value'] = $_POST['search']['value'];
            } else
            $_POST['search']['value'] = '';
            if($_POST['search']['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->db->group_start();
                    $this->db->like(($emp), $_POST['search']['value']);
                }else{
                    $this->db->or_like(($emp), $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        // if(!empty($this->where))
        //     $this->db->where($this->where); 

        // if(!empty($this->group_by)){
        //     $this->db->group_by($this->group_by);
        // }
         
        if(isset($_POST['order'])) // here order processing
        { 
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order)){ 
            $order = $this->order; 
            $this->db->order_by(key($order), $order[key($order)]);
        }
       
    }//End function

    function get_list()
    {
        $this->_get_query();
        if(isset($_POST['length']) && $_POST['length'] < 1) {
            $_POST['length'] = '10';
        }else{
        	$_POST['length'] = isset($_POST['length']) ? $_POST['length'] :10;
        }
        if(isset($_POST['start']) && $_POST['start'] > 1) {
            $_POST['start'] = $_POST['start'];
        }
        $_POST['start'] = isset($_POST['start']) ? $_POST['start']:0;
        $this->db->limit($_POST['length'], $_POST['start']);
        // $this->db->where('company_id',$_SESSION['company_sess']['id']);
        $query = $this->db->get(); //lq();
        return $query->result();
    }//End function
    function count_filtered()
    {
        $this->_get_query();
        $query = $this->db->get();
        return $query->num_rows();
    }//End function
    public function count_all()
    {
        $this->db->from($this->table);
        if(!empty($this->where))
            $this->db->where($this->where); 
        return $this->db->count_all_results();
    }//End function
    
    public function registration($user)
    {   
        $checkEmail = $this->db->select('*')->where(array('email'=>$user['email']))->get('crew_member');
        if($checkEmail->num_rows()){
            $existId = $checkEmail->result();
            $existId = $existId[0]->id;
            return array('regType'=>'AE','existId'=>$existId);
        }else{
            $this->db->insert('crew_member',$user);
            $lastId = $this->db->insert_id();
            if($lastId):
                $returnData = $this->db->get_where('crew_member',array('id'=>$lastId))->result();
                return array('regType'=>'NR','returnData'=>$returnData);
                // Normal registration
            endif;
        }
        return false;   
    }
    
    
    
    
    
   
}//End Class