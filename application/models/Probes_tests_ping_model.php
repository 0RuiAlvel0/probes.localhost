<?php
  class Probes_tests_ping_model extends CI_Model{

    function add($data){
      $output = false;
      if($this->db->insert('probes_tests_ping', $data))
        $output = $this->db->insert_id();
      return $output;
    }
	
	function get_last_ping_test($probe_id){
		if($query = $this->db->query('SELECT * FROM probes_tests_ping WHERE probe_id = ? ORDER by server_time DESC limit 1', array($probe_id)))
			return $query->row();
		else
			return false;
	}
	
	function get_data_on_interval($probe_id, $start_date, $end_date){
		if($query = $this->db->query('SELECT * FROM probes_tests_ping WHERE probe_id LIKE ? AND server_time >= ? AND server_time <= ?', array($probe_id, $start_date, $end_date)))
			return $query;
		else
			return false;	
	}

  }
?>
