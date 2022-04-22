<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_company extends CI_Model
{
    public function get_company($start, $perpage, $type_data = '', $id_dpartement)
    {
        $this->db->select('*');
        $this->db->where('type', $type_data);
        $this->db->where('id_dpartement', $id_dpartement);
        $this->db->limit($perpage, $start);
        $this->db->from('tbl_company');
        $query = $this->db->get();
        return $query;
    }

    public function get_keyword($keyword, $perpage, $start, $type_data,  $id_dpartement)
    {
        $this->db->select('*');
        $this->db->where('type', $type_data);
        $this->db->where('id_dpartement', $id_dpartement);
        $this->db->from('tbl_company');
        $this->db->limit($perpage, $start);
        $this->db->like('company_name', $keyword);
        $this->db->or_like('no_telp', $keyword);
        $this->db->or_like('email', $keyword);
        $this->db->or_like('alamat', $keyword);
        return $this->db->get();
    }

    public function input_company($data)
    {
        $this->db->insert('tbl_company', $data);
    }

    public function update_company($where, $data, $table)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
    }

    public function delete_company($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function count_company()
    {
        $this->db->from('tbl_company');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function edit_data($where, $table)
    {
        return $this->db->get_where($table, $where);
    }

    public function get_province()
    {
        $this->db->distinct();
        $this->db->select('id_prov');
        $this->db->select('provinsi');
        $this->db->from('tbl_wilayah');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_city()
    {
        $this->db->distinct();
        $this->db->select('id_kab');
        $this->db->select('kabupaten');
        $this->db->from('tbl_wilayah');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_kecamatan()
    {
        $this->db->distinct();
        $this->db->select('id_kec');
        $this->db->select('kecamatan');
        $this->db->from('tbl_wilayah');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_kelurahan()
    {
        $this->db->distinct();
        $this->db->select('kelurahan');
        $this->db->from('tbl_wilayah');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_province_name($id = '')
    {
        $this->db->select('provinsi');
        $this->db->from('tbl_wilayah');
        $this->db->where('id_prov', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_city_name($id = '')
    {
        $this->db->select('kabupaten');
        $this->db->from('tbl_wilayah');
        $this->db->where('id_kab', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_district_name($id = '')
    {
        $this->db->select('kecamatan');
        $this->db->from('tbl_wilayah');
        $this->db->where('id_kec', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
}
