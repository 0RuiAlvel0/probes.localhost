<?php
  class Users_model extends CI_Model{

    function add($data){
      $output = false;
      if($this->db->insert('users', $data))
        $output = $this->db->insert_id();
      return $output;
    }

    function edit($data,$id){
      if(isset($data['team_id']))
        $this->db->set('team_id', $data['team_id']);
      if(isset($data['team_id']))
        $this->db->set('team_id', $data['team_id']);
      if(isset($data['email']))
        $this->db->set('email', $data['email']);
      if(isset($data['f_name']))
        $this->db->set('f_name', $data['f_name']);
      if(isset($data['l_name']))
        $this->db->set('l_name', $data['l_name']);
      if(isset($data['password']))
        $this->db->set('password', $data['password']);
      if(isset($data['date_register']))
        $this->db->set('date_register', $data['date_register']);
      if(isset($data['last_login']))
        $this->db->set('last_login', $data['last_login']);
      if(isset($data['disabled']))
        $this->db->set('disabled', $data['disabled']);
      if(isset($data['was_invited']))
        $this->db->set('was_invited', $data['was_invited']);
      if(isset($data['invited_by_team_id']))
        $this->db->set('invited_by_team_id', $data['invited_by_team_id']);
      if(isset($data['date_invited']))
        $this->db->set('date_invited', $data['date_invited']);
      if(isset($data['recovery_password']))
        $this->db->set('recovery_password', $data['recovery_password']);
      if(isset($data['recovery_requested_date']))
        $this->db->set('recovery_requested_date', $data['recovery_requested_date']);
      if(isset($data['send_summary']))
        $this->db->set('send_summary', $data['send_summary']);

      $this->db->where('id', $id);
      return $this->db->update('users');
    }

    function validate($u,$p){
			$query = $this->db->query('SELECT * FROM users WHERE email LIKE ? AND (password LIKE ? OR recovery_password LIKE ?) AND disabled = 0', array($u, $p, $p));
      $output = false;
			if($query->num_rows() == 1)
        foreach($query->result_array() as $row)
          $output = $row['id'];
			return $output;
    }

    function email_exists($e){
      //returns false if email doesn't exist, user_id if email exists
      $query = $this->db->query('SELECT * FROM users WHERE email LIKE ?', array($e));
      $output = false;
      if($query->num_rows() == 1){
        foreach($query->result_array() as $row)
          $output = $row['id'];
      }
      return $output;
    }

    function get_list($query_data){
      $output = false;
			$query = $this->db->query('SELECT * FROM users WHERE (team_id LIKE ? OR invited_by_team_id LIKE ?)',$query_data);
      if($query->num_rows() >= 1)
        $output = $query->result();
      return $output;
    }

    function get_user_data($user_id){
			$query = $this->db->query('SELECT * FROM users WHERE id LIKE ?', array($user_id));
      $output = false;
      if($query->num_rows() == 1)
        $output = $query->result_array();
      return $output;
    }

    function delete($id){
      $this->db->where('id', $id);
      return $this->db->delete('users');
    }

  }
?>
