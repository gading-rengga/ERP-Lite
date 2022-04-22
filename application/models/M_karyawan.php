<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_karyawan extends CI_Model
{
    public function get_employe($perpage, $start)
    {
        $this->db->select('*');
        $this->db->limit($perpage, $start);
        $this->db->from('tbl_employe');
        $query = $this->db->get();
        return $query;
    }

    public function get_keyword($keyword, $perpage, $start)
    {
        $this->db->select('*');
        $this->db->from('tbl_employe');
        $this->db->limit($perpage, $start);
        $this->db->like('employe_name', $keyword);
        $this->db->or_like('no_telp', $keyword);
        $this->db->or_like('email', $keyword);
        $this->db->or_like('alamat', $keyword);
        return $this->db->get();
    }

    public function input_employe($data)
    {
        $this->db->insert('tbl_employe', $data);
    }

    public function update_employe($where, $data, $table)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
    }

    public function delete_employe($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function count_employe()
    {
        $this->db->from('tbl_employe');
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

    public function get_divisi()
    {
        $this->db->distinct();
        $this->db->select('divisi_id');
        $this->db->select('name_divisi');
        $this->db->from('tbl_divisi');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_dpartement()
    {
        $this->db->distinct();
        $this->db->select('id');
        $this->db->select('nama_dpartement');
        $this->db->from('tbl_dpartement');
        $query = $this->db->get();
        return $query->result_array();
    }
}
