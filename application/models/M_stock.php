<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_stock extends CI_Model
{
    public function get_data($perpage, $start, $dpartement)
    {
        $this->db->select('*');
        $this->db->from('tbl_product');
        $this->db->where('tbl_stock.id_dpartement', $dpartement);
        $this->db->join('tbl_stock', 'tbl_product.id=tbl_stock.product_id', 'left');
        $this->db->limit($perpage, $start);
        $query = $this->db->get();
        return $query;
    }

    public function get_product($dpartement)
    {
        $this->db->distinct();
        $this->db->select('id');
        $this->db->select('product_name');
        $this->db->from('tbl_product');
        $this->db->where('id_dpartement', $dpartement);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_keyword($keyword, $perpage, $start, $dpartement)
    {
        $this->db->select('*');
        $this->db->from('tbl_product');
        $this->db->where('tbl_stock.id_dpartement', $dpartement);
        $this->db->join('tbl_stock', 'tbl_product.id=tbl_stock.product_id', 'left');
        $this->db->limit($perpage, $start);
        $this->db->like('product_name', $keyword);
        $this->db->or_like('product_code', $keyword);
        $this->db->or_like('price', $keyword);
        return $this->db->get();
    }

    public function count_data()
    {
        $this->db->from('tbl_stock');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function input_data($data)
    {
        $this->db->insert('tbl_stock', $data);
    }

    public function update_data($where, $data, $table)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
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
}
