<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_user extends CI_Model
{
    public function get_user($perpage, $start)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->limit($perpage, $start);
        $query = $this->db->get();
        return $query;
    }

    public function get_keyword($keyword, $perpage, $start)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->limit($perpage, $start);
        $this->db->like('username', $keyword);
        return $this->db->get();
    }

    public function count_user()
    {
        $this->db->from('tbl_user');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function input_employe($data)
    {
        $this->db->insert('tbl_user', $data);
    }

    public function edit_data($where, $table)
    {
        return $this->db->get_where($table, $where);
    }

    public function delete_user($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function get_employe()
    {
        $this->db->distinct();
        $this->db->select('id');
        $this->db->select('employe_name');
        $this->db->from('tbl_employe');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_name($id)
    {
        $this->db->select('employe_name');
        $this->db->where('id', $id);
        $this->db->from('tbl_employe');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_data($where, $data, $table)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
    }
}
