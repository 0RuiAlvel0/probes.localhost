<?php
  class Probes_tests_ping_details_model extends CI_Model{

    function add($data){
      $output = false;
      if($this->db->insert('probes_tests_ping_details', $data))
        $output = $this->db->insert_id();
      return $output;
    }
	
	function get_ping_server_for_test($test_id){
		if($query = $this->db->query('SELECT * FROM probes_tests_ping_details WHERE test_id = ? ORDER by icmp_seq DESC limit 1', array($test_id)))
			return $query->row()->from_server;
		else
			return false;
	}
	
  }
?>
