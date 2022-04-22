<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_dashboard extends CI_Model
{
    public function get_transaction()
    {
        $this->db->select('*');
        $this->db->from('tbl_transaction');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_most_sales($month)
    {

        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];
        $this->db->select('sales_id');
        $this->db->select('tbl_transaction_meta.product_id');
        $this->db->select('sum(qty)');
        $this->db->where('DATE_FORMAT(tanggal,"%Y-%m")', $month);
        $this->db->where('tbl_transaction.id_dpartement', $id_dpartement);
        $this->db->group_by('sales_id');
        $this->db->from('tbl_transaction');
        $this->db->join('tbl_transaction_meta', 'tbl_transaction_meta.id_transaction=tbl_transaction.id', 'left');
        $this->db->order_by('sum(qty)', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_sales_name($id = '')
    {
        $this->db->select('employe_name');
        $this->db->from('tbl_employe');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_product($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_product');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
}
