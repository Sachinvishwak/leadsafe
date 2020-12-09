<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Common Model
 * version: 2.0 (14-08-2018)
 * Common DB queries used in app
 */
class Common_model extends CI_Model {
    
    /* INSERT RECORD FROM SINGLE TABLE */
    function insertData($table, $dataInsert) {
        $this->db->insert($table, $dataInsert);
        return $this->db->insert_id();
    }//end function
    /* INSERT RECORD FROM batch TABLE */
    function insertBatch($table, $dataInsert) {
        $insert = $this->db->insert_batch($table, $dataInsert);
        return $insert;
    }//end function
    /* UPDATE RECORD FROM SINGLE TABLE */
    function updateFields($table, $data, $where){
        $update = $this->db->update($table, $data, $where);
    //    if($this->db->affected_rows() > 0){
        if($update){
            return true;
        }else{
            return false;
        }
    }//end function
    /* UPDATE RECORD FROM TABLE */
    function deleteData($table,$where){
        $this->db->where($where);
        $this->db->delete($table); 
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }   
    }//end function
    /* Delete RECORD FROM TABLE */
    function deleteDataTaskMeta($table,$where,$where_in){
        $this->db->where($where);
        $this->db->where_not_in('taskmetaId',$where_in);
        $this->db->delete($table); 
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }   
    }//end function
    /* GET SINGLE RECORD 
     * Modified in ver 2.0
     */
    function getsingle($table, $where = '', $fld = NULL, $order_by = '', $order = '') {
        if ($fld != NULL) {
            $this->db->select($fld);
        }
        $this->db->limit(1);

        if ($order_by != '') {
            $this->db->order_by($order_by, $order);
        }
        if ($where != '') {
            $this->db->where($where);
        }

        $q = $this->db->get($table);
        return $q->row_array();
    }//end function
    /* GET MULTIPLE RECORD 
     * Modified in ver 2.0
     */
     function getAll($table, $where = '', $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '', $group_by='', $like='') {
        $data = array();
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        if($group_by !=''){
            $this->db->group_by($group_by);
        }
        $this->db->from($table);
        if ($where != '') {
            $this->db->where($where);
        }
        if ($like != '') {
            $this->db->like('name', $like);
        }
        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        return $q->result(); //return multiple records
    }//end function

    /* get single record using join 
     * Modified in ver 2.0
     */
    function GetSingleJoinRecord($table, $field_first, $tablejointo, $field_second,$field_val='',$where="") {
        $data = array();
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first","inner");
        if(!empty($where)){
            $this->db->where($where);
        }
        $q = $this->db->get();
        return $q->row();  //return only single record
    }//end function
    /* Get mutiple records using join 
     * Modified in ver 2.0
     */ 
    function GetJoinRecord($table, $field_first, $tablejointo, $field_second,$field_val='',$where="",$group_by='',$order_fld='',$order_type='', $limit = '', $offset = '') {
        $data = array();
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first","inner");
        if(!empty($where)){
            $this->db->where($where);
        }
        if(!empty($group_by)){
            $this->db->group_by($group_by);
        }

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if(!empty($order_fld) && !empty($order_type)){
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        return $q->result();
    }//end function
    /* Get records joining 3 tables 
     * Modified in ver 2.0
     */
    function GetJoinRecordThree($table, $field_first, $tablejointo, $field_second,$tablejointhree,$field_three,$table_four,$field_four,$field_val='',$where="" ,$group_by="",$order_fld='',$order_type='', $limit = '', $offset = '') {
        $data = array();
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first",'inner');
        $this->db->join("$tablejointhree", "$tablejointhree.$field_three = $table_four.$field_four",'inner');
        if(!empty($where)){
            $this->db->where($where);
        }

        if(!empty($group_by)){
            $this->db->group_by($group_by);
        }
        
        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        
        if(!empty($order_fld) && !empty($order_type)){
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        return $q->result();
    }//end function
    /* Exceute a custom build query by query binding- Useful when we are not able to build queries using CI DB methods
     * Prefreably to be used in SELECT queries
     * The main advantage of building query this way is that the values are automatically escaped which produce safe queries.
     * See example here: https://www.codeigniter.com/userguide3/database/queries.html#query-bindings
     * Modified in ver 2.0
     */
    public function custom_query($myquery, $bind_data=array()){
        $query = $this->db->query($myquery, $bind_data);
        return $query->result();
    }//end function

    /* check if any value exists in and return row if found */
    public function is_id_exist($table,$key,$value){
        $this->db->select("*");
        $this->db->from($table);
        $this->db->where($key,$value);
        $ret = $this->db->get()->row();
        if(!empty($ret)){
            return $ret;
        }
        else
            return FALSE;
    }//end function
    
    /* Get single value based on table field */
    public function get_field_value($table, $where, $key){ 
        $this->db->select($key);
        $this->db->from($table);
        $this->db->where($where);
        $ret = $this->db->get()->row();
        if(!empty($ret)){
            return $ret->$key;
        }
        else
            return FALSE;
    }//end function
    
    /* Get total records of any table */
    function get_total_count($table, $where=''){
        $this->db->from($table);
        if(!empty($where))
            $this->db->where($where);
        
        $query = $this->db->get();
        return $query->num_rows(); //total records
    }//end function 
    /* Check if given data exists in table and return record if exist- Very useful fn
     * Modified in ver 2.0
     */
    function is_data_exists($table, $where){
        $this->db->from($table);
        $this->db->where($where);
        $query      = $this->db->get();
        $rowcount   = $query->num_rows();
        if($rowcount==0){
            return FALSE; //record not found
        }else{
            return $query->row(); //return record if found (In preveious versions, this use to return TRUE(bool) only)
        }
    } //end function
    function userInfo($where){
        $userPath    = base_url().USER_AVATAR_PATH.'thumb/';
        $userDefault = base_url().USER_DEFAULT_AVATAR;
        $this->db->select('id,
            id as userId,
            fullName,
            email,
            authToken,
            userType,
            (case when (profileImage = "") 
            THEN "'.$userDefault.'" ELSE
            concat("'.$userPath.'",profileImage) 
            END) as profileImage,
            (case when (userType = 1) 
            THEN "Super Admin" when (userType = 2) 
            THEN "Customer" when (userType = 3) 
            THEN "Employee" ELSE
            "Unknown" 
            END) as userRole');
        $this->db->from(USERS);
        $this->db->where($where);
        $sql = $this->db->get();

        if($sql->num_rows()):
            return $sql->row_array();
        endif;
        return false;
    } //End Function usersInfo 
    function adminInfo($where){
        $userPath    = base_url().ADMIN_AVATAR_PATH.'thumb/';
        $userDefault = base_url().ADMIN_DEFAULT_AVATAR;
        $this->db->select('id,
            id as userId,
            fullName,
            email,
            authToken,
            userType,
            (case when (profileImage = "") 
            THEN "'.$userDefault.'" ELSE
            concat("'.$userPath.'",profileImage) 
            END) as profileImage,
            (case when (userType = 1) 
            THEN "Super Admin" when (userType = 2) 
            THEN "Customer" when (userType = 3) 
            THEN "Employee" ELSE
            "Unknown" 
            END) as userRole');
        $this->db->from(ADMIN);
        $this->db->where($where);
        $sql= $this->db->get();

        if($sql->num_rows()):
            return $sql->row_array();
        endif;
        return false;
    } 
    
    //End Function usersInfo
    
    /**
     * Generate auth token for API users
     * Modified in version 2.0
    */
    function generate_token(){
        $this->load->helper('security');
        $res = do_hash(time().mt_rand());
        $new_key = substr($res,0,config_item('rest_key_length'));
        return $new_key;

    }//end function
    /**
    *
    *
    ****/
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }//end function
    function adminEmails(){
        $email = array();
        $this->db->select('email');
        $this->db->from('admin');
        $sql= $this->db->get();
        if($sql->num_rows()):
            foreach ($sql->result() as $k => $v) {
              $email[] = $v->email;
            }
        endif;
        return $email;
    } //End Function usersInfo 
    function roadRouteDistance($lat1,$long1,$lat2,$long2){
        $origins        = $lat1.','.$long1;
        $destinations   =  $lat2.','.$long2;
       // echo $origins." ".$destinations;
        $mile   =  0;
        $url    = "https://maps.googleapis.com/maps/api/distancematrix/json?key=AIzaSyDjhKBJtoevmCuR5iD1El6cuDHTMByw9Co&origins=".$origins."&destinations=".$destinations."&mode=driving&mode=driving&language=pl-PL";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        pr( $response_a);
        $distance = (isset($response_a['rows'][0]['elements'][0]['distance']['text']) && !empty($response_a['rows'][0]['elements'][0]['distance']['text'])) ?$response_a['rows'][0]['elements'][0]['distance']['text'] :0;
        if(!empty($distance)){
            //$dis =  trim(str_replace(array(",","km"),array(".",""),$distance));

            $dis = round($response_a['rows'][0]['elements'][0]['distance']['value'] / 1000);

            //$mile = ceil($dis* 0.62137);
            $mile= $dis*0.62137;
            $mile = round($mile,2);
        }
        return $mile; 
    }//ENd FUnction
    /**
    * find address using lat long
    */
    public static function geolocationaddress($lat,$long)
    {
       // $geolocation = $lat.','.$long;
        $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=AIzaSyDjhKBJtoevmCuR5iD1El6cuDHTMByw9Co";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geocode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response   = curl_exec($ch);
        curl_close($ch);
        $output     = json_decode($response);
        pr($output);
        $dataarray  = get_object_vars($output);
        if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
            if (isset($dataarray['results'][0]->formatted_address)) {

                $address = $dataarray['results'][0]->formatted_address;

            } else {
                $address = 'Not Found';

            }
        } else {
            $address = 'Not Found';
        }
        return $address;
    }//End Function
    function customerAddresses(){
        $addresses = $this->db->select('*')->from('customerAddress')->get();
        if($addresses->num_rows()){
        
            return $addresses->result();
           
        }
/*        $this->db->select('users.id as custId');
        $this->db->from('users');
        $this->db->where(array('users.userType'=>1));
        $this->db->order_by('custId','asc');
        $sql= $this->db->get();
        if($sql->num_rows()):
            $customers = $sql->result();
            $address_array =array();
            $i=0;
            foreach ($customers as $k => $v) {
                $addresses = $this->db->select('*')->from('customerAddress')->where(array('customerAddress.customerId'=>$v->custId))->get();
                if($addresses->num_rows()){
                    $address_array[$i]['customerId'] = $v->custId;
                    $address_array[$i]['addresses'] = $addresses->result();
                    $i++; 
                }
               
            }
            return $address_array;
        endif;*/
        return false;
    }//End Function
    function customerNotification($where=array()){
        
        $this->db->select('users.id as custId,users.fullName as customerName,users.email');
        $this->db->from('users');
        $this->db->where(array('users.userType'=>1));
        !empty($where) ?$this->db->where($where):"";
        $this->db->order_by('custId','asc');
        $sql= $this->db->get();
        if($sql->num_rows()):
            $customers = $sql->result();
            $address_array =array();
            $i=0;
            foreach ($customers as $k => $v) {
                $addresses = $this->db->select('ca.*,wn.temperature,wn.alertDate,wn.alertTime,wn.notificationId')->from('customerAddress as ca')->join('weatherNotification as wn','wn.addressId =ca.addressId')->where(array('ca.customerId'=>$v->custId))->get();
                if($addresses->num_rows()){
                    $address_array[$i]['customerId']    = $v->custId;
                    $address_array[$i]['customerName']  = $v->customerName;
                    $address_array[$i]['email']         = $v->email;
                    $address_array[$i]['addresses']     = $addresses->result();
                    $i++; 
                }
               
            }
            return $address_array;
        endif;
        return array();
    }//end function
    
    
    
    // mail
    function sendMemberMail($to,$link,$message,$subject)
    {
        $this->load->library('email');
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'rathoreankit582@gmail.com',
            'smtp_pass' => 'A@123456a',
            'mailtype'  => 'html',
            'charset'   => 'utf-8'
        );
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");
        $this->email->to($to);
        $this->email->from('rathoreankit582@gmail.com','Bild It');
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
    }
    function newsendMemberMail($useremail,$subject,$message)
    {
        $this->load->library('email');
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'rathoreankit582@gmail.com',
            'smtp_pass' => 'A@123456a',
            'mailtype'  => 'html',
            'charset'   => 'utf-8'
        );
        $this->email->initialize($config);
        $this->load->library('email');
        $this->email->from('rathoreankit582@gmail.com', 'Bild It');
        $this->email->to($useremail);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->set_mailtype("html");
        $this->email->send();
    }

    // function sendMemberMail($to,$link,$message,$subject)
    // {
    //     $this->load->library('email');
    //     $this->email->from('rathoreankit582@gmail.com', 'Bild It');
    //     $this->email->to($to);
    //     $this->email->subject($subject);
    //     $this->email->message($message);
    //     $this->email->set_mailtype("html");
    //     $this->email->send();
    // }
    //new mail superadmin
    // function newsendMemberMail($useremail,$subject,$message)
    // {
    //     $this->load->library('email');
    //     $this->email->from('rathoreankit582@gmail.com', 'Bild It');
    //     $this->email->to($useremail);
    //     $this->email->subject($subject);
    //     $this->email->message($message);
    //     $this->email->set_mailtype("html");
    //     $this->email->send();
    // }
    
    
    
    
    
} //end of class
/* Do not close php tags */
/* IMP: Do not add any new method in this file */