<?php
class Api_model extends CI_Model
{
 
 function insert_api($data)
 {
  $this->db->insert('notification', $data);
  if($this->db->affected_rows() > 0)
  {
   return true;
  }
  else
  {
   return false;
  }
 }

}