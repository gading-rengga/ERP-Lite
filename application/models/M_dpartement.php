<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_dpartement extends CI_Model
{

    public function get_data($perpage, $start)
    {
        $this->db->select('*');
        $this->db->from('tbl_dpartement');
        $this->db->limit($perpage, $start);
        $query = $this->db->get();
        return $query;
    }

    public function get_keyword($keyword, $perpage, $start)
    {
        $this->db->select('*');
        $this->db->from('tbl_dpartement');
        $this->db->limit($perpage, $start);
        $this->db->like('nama_dpartement', $keyword);
        return $this->db->get();
    }

    public function count_data()
    {
        $this->db->from('tbl_dpartement');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function input_data($data)
    {
        $this->db->insert('tbl_dpartement', $data);
    }

    public function edit_data($where, $table)
    {
        return $this->db->get_where($table, $where);
    }

    public function delete_data($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }
    public function update_data($where, $data, $table)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
    }
}
