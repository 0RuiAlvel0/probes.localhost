<?php
  class Probes_hw_tests_model extends CI_Model{

    function add($data){
      $output = false;
      if($this->db->insert('probes_hw_tests', $data))
        $output = $this->db->insert_id();
      return $output;
    }
	
	function get_last_test($probe_id){
      if($query = $this->db->query('SELECT * FROM probes_hw_tests WHERE probe_id LIKE ? ORDER BY server_time DESC limit 1', array($probe_id)))
			return $query->row();
		else
			return false;	
    }
	
  }
?>
