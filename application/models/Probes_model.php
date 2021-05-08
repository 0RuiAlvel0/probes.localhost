<?php
  class Probes_model extends CI_Model{

    function add($data){
      $output = false;
      if($this->db->insert('probes', $data))
        $output = $this->db->insert_id();
      return $output;
    }

    function probe_is_registered($mac, $write_key){
      //returns false or configuration id for current probe:
      $query = $this->db->query('SELECT * FROM probes WHERE (w_mac LIKE ? AND w_write_key LIKE ?) OR (wl_mac LIKE ? AND wl_write_key LIKE ?)', array($mac, $write_key, $mac, $write_key));
      if($query->num_rows() == 1)
        return $query->row()->id;
      else
        return false;
    }

    function mac_exists($mac){
      //returns false if email doesn't exist, user_id if email exists
      $query = $this->db->query('SELECT * FROM probes WHERE (w_mac LIKE ? OR wl_mac LIKE ?)', array($mac, $mac));
      if($query->num_rows() == 1)
        return true;
      else
        return false;
    }

    function get_list_for_team($team_id){
      $query = $this->db->query('SELECT * FROM probes WHERE team_id LIKE ?', array($team_id));
      if($query->num_rows() >= 1){
        return $query->result();
      }
      else
        return false;
    }

    function probe_data($id){
      //returns false or configuration id for current probe:
      $query = $this->db->query('SELECT * FROM probes WHERE id LIKE ?', array($id));
      if($query->num_rows() == 1)
        return $query->row();
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

      $this->db->where('id', $id);
      return $this->db->update('probes');
    }

    function delete($id){
      $this->db->where('id', $id);
      return $this->db->delete('probes');
    }

  }
?>
