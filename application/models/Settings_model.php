<?php
  class Settings_model extends CI_Model{

    function update($data){
		$this->db->truncate('settings');
		$output = false;
		if($this->db->insert('settings', $data))
			$output = $this->db->insert_id();
		return $output;
    }
	
	function get(){
      //returns false or configuration id for current probe:
      $query = $this->db->query('SELECT * FROM settings');
	  $output = false;
      if($query->num_rows() == 1)
        $output = $query->row();
      
	  return $output;
    }
  }
?>
