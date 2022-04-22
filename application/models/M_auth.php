<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_auth extends CI_Model
{
    public function get_user($username, $password)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $this->db->join('tbl_employe', 'tbl_user.employe_id = tbl_employe.id', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_divisi($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_divisi');
        $this->db->where('divisi_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_dpartement($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_dpartement');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
}
