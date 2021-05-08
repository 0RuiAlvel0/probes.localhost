<?php
  class Probes_config_tests_model extends CI_Model{

    function add($data){
      $output = false;
      if($this->db->insert('probes_config_tests', $data))
        $output = $this->db->insert_id();
      return $output;
    }
	
	function get_last_checkin($probe_id){
      if($query = $this->db->query('SELECT * FROM probes_config_tests WHERE probe_id LIKE ? ORDER BY check_in_date DESC limit 1', array($probe_id)))
			return $query->row();
		else
			return false;	
    }
	
	function get_data_on_interval($probe_id, $start_date, $end_date){
		if($query = $this->db->query('SELECT * FROM probes_config_tests WHERE probe_id LIKE ? AND check_in_date >= ? AND check_in_date <= ?', array($probe_id, $start_date, $end_date)))
			return $query;
		else
			return false;	
	}

  }
?>
