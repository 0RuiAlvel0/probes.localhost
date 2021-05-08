<?php
  class Users_teams_model extends CI_Model{

    function add($data){
      $output = false;
      if($this->db->insert('users_teams', $data))
        $output = $this->db->insert_id();
      return $output;
    }

    function get_list(){
      $output = false;
			$query = $this->db->query('SELECT * FROM users_teams');
      if($query->num_rows() >= 1)
        $output = $query->result();
      return $output;
    }

    function edit($data,$id){
      if(isset($data['timezone_id']))
        $this->db->set('timezone_id', $data['timezone_id']);
      $this->db->where('id', $id);
      return $this->db->update('users_teams');
    }

    function get_data($id){
			$query = $this->db->query('SELECT * FROM users_teams WHERE id LIKE ?', array($id));
      $output = false;
      if($query->num_rows() == 1)
        $output = $query->result_array();
      return $output;
    }

    function delete($id){
      $this->db->where('id', $id);
      return $this->db->delete('users_teams');
    }

  }
?>
