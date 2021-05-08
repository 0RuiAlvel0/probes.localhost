<?php
  class Probes_config_model extends CI_Model{

    function add($data){
      $output = false;
      if($this->db->insert('probes_config', $data))
        $output = $this->db->insert_id();
      return $output;
    }

    function probe_config_data($probe_id){
      //returns false or configuration id for current probe:
      $query = $this->db->query('SELECT * FROM probes_config WHERE probe_id LIKE ?', array($probe_id));
      if($query->num_rows() == 1)
        return $query->row();
      else
        return false;
    }

    function mac_exists($mac_address, $is_wired){
      //returns false if email doesn't exist, user_id if email exists
      if($is_wired)
        $query = $this->db->query('SELECT * FROM probes_config WHERE w_mac LIKE ?', array($mac_address));
      else
        $query = $this->db->query('SELECT * FROM probes_config WHERE wl_mac LIKE ?', array($mac_address));

      if($query->num_rows() == 1)
        return true;
      else
        return false;
    }

    function get_list_for_team($team_id){
      $query = $this->db->query('SELECT * FROM probes_config WHERE team_id LIKE ?', array($team_id));
      if($query->num_rows() >= 1){
        return $query->result();
      }
      else
        return false;
    }

    function edit($data,$id){
		if(isset($data['cat_id']))
			$this->db->set('cat_id', $data['cat_id']);
		if(isset($data['team_id']))
			$this->db->set('team_id', $data['team_id']);
		if(isset($data['w_mac']))
			$this->db->set('w_mac', $data['w_mac']);
		if(isset($data['wl_mac']))
			$this->db->set('wl_mac', $data['wl_mac']);
		if(isset($data['w_write_key']))
			$this->db->set('w_write_key', $data['w_write_key']);
		if(isset($data['wl_write_key']))
			$this->db->set('wl_write_key', $data['wl_write_key']);
		if(isset($data['name']))
			$this->db->set('name', $data['name']);
		if(isset($data['server_1']))
			$this->db->set('server_1', $data['server_1']);
		if(isset($data['config_freq']))
			$this->db->set('config_freq', $data['config_freq']);
		if(isset($data['ping_server']))
			$this->db->set('ping_server', $data['ping_server']);
		if(isset($data['speed_test_server']))
			$this->db->set('speed_test_server', $data['speed_test_server']);
		if(isset($data['test_2']))
			$this->db->set('test_2', $data['test_2']);
		if(isset($data['test_5']))
			$this->db->set('test_5', $data['test_5']);
		if(isset($data['channel_test_freq']))
			$this->db->set('channel_test_freq', $data['channel_test_freq']);
		if(isset($data['wifiun']))
			$this->db->set('wifiun', $data['wifiun']);
		if(isset($data['wifipw']))
			$this->db->set('wifipw', $data['wifipw']);
		if(isset($data['last_config_contact']))
			$this->db->set('last_config_contact', $data['last_config_contact']);
		if(isset($data['sent_offline_email']))
			$this->db->set('sent_offline_email', $data['sent_offline_email']);
		if(isset($data['sent_online_email']))
			$this->db->set('sent_online_email', $data['sent_online_email']);

		$this->db->where('id', $id);
		return $this->db->update('probes_config');
    }

    function delete($id){
      $this->db->where('id', $id);
      return $this->db->delete('probes_config');
    }

  }
?>
