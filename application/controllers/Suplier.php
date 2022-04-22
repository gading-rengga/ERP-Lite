<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Suplier extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('M_company');
        $this->load->model('M_auth');
    }

    public function default_data()
    {
        $data = array(
            'id'        =>  '',
            'company_name'      =>  '',
            'no_telp'   =>  '',
            'email'     =>  '',
            'alamat'    =>  '',
            'propinsi'  =>  '',
            'kota'      =>  '',
            'kecamatan' =>  '',
            'kelurahan' =>  '',
            'kode_pos'  =>  '',
            'type'      => 2
        );
        return $data;
    }
    public function index()
    {
        $config_search = $this->config_search();
        $config_card = $this->card_table();
        $config_table = $this->config_table();
        $table = data_table($config_table);
        $search = search($config_search);

        $config_nav = $this->nav_company();
        $nav = nav($config_nav);

        $pagination =  $this->pagination->create_links();
        $content = array(
            $search,
            $nav,
            $table,
            $pagination
        );

        $data['card'] = card($config_card, $content);
        $data['sidebar'] = config_sidebar();
        $data['title'] = 'Data Suplier';
        $this->load->view('theme/metronic/header');
        $this->load->view('theme/metronic/sidebar', $data);
        $this->load->view('theme/metronic/topbar');
        $this->load->view('theme/metronic/content', $data);
        $this->load->view('theme/metronic/footer');
    }

    public function nav_company()
    {
        $data = array(
            array(
                'nav_title'    => 'Customer',
                'nav_link'        => 'Customer',
                'nav_icon'        => 'fas fa-user-tag'
            ),
            array(
                'nav_title'    => 'Suplier',
                'nav_link'        => 'Suplier',
                'nav_icon'        => 'fas fa-truck'
            ),
        );
        return $data;
    }

    public function card_table()
    {
        $data = array(
            array(
                'title'    => 'Data Suplier',
                'action'    => 'Suplier',
                'icon'    => 'fas fa-id-card',
                // Optional Button
                'button' => array(
                    'button_link'      => 'Suplier/form',
                    'button_title'    => 'Tambah Data',
                    'button_color'     => 'primary'
                ),
            )
        );
        return $data;
    }

    public function config_table()
    {

        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];
        $this->load->library('pagination');
        $type_data = 2;
        $start = $this->uri->segment(3);
        $perpage  = 10;
        $keyword = $this->input->post('search');
        $data_search = $this->M_company->get_keyword($keyword, $perpage, $start, $type_data, $id_dpartement)->result_array();
        $data_company = $this->M_company->get_company($perpage, $start, $type_data, $id_dpartement)->result_array();
        $config_pagination = $this->config_pagination();
        $config = pagination($config_pagination);
        $this->pagination->initialize($config);

        $data['t_head'] = array(
            array(
                'NO',
                'Nama',
                'No Telephome',
                'Email',
                'Alamat',
                'Edit',
                'Hapus'
            )
        );
        if ($data_search == '') {
            $data_table = $data_company;
        } elseif ($data_search !== '') {
            $data_table = $data_search;
        }

        if (isset($data)) {
            foreach ($data_table as $index => $key) {
                if ($key['id_dpartement'] == $id_dpartement) {
                    $get_province = $this->M_company->get_province_name($key['propinsi']);
                    foreach ($get_province as $val) {
                        $province = $val['provinsi'];
                    }
                    $get_city = $this->M_company->get_city_name($key['kota']);
                    foreach ($get_city as $val) {
                        $city = $val['kabupaten'];
                    }
                    $get_district = $this->M_company->get_district_name($key['kecamatan']);
                    foreach ($get_district as $val) {
                        $district = $val['kecamatan'];
                    }
                    $address = $key['alamat'] . ',' . $district . ',' . $city . ',' . $province;
                    $config_button_edit = array(
                        array(
                            'button' => array(
                                'button_link'      => 'Suplier/form/' . $key['id'],
                                'button_title'    => 'Edit',
                                'button_color'     => 'primary'
                            ),
                        )
                    );
                    $config_button_delete = array(
                        array(
                            'button' => array(
                                'button_link'      => 'Suplier/delete_data/' . $key['id'],
                                'button_title'    => 'Hapus',
                                'button_color'     => 'primary'
                            ),
                        )
                    );
                    $button_edit = button_edit($config_button_edit);
                    $button_delete = button_delete($config_button_delete);
                    if ($key['type'] == $type_data) {
                        $data['t_body'][$index] = array(
                            ++$start,
                            $key['company_name'],
                            $key['no_telp'],
                            $key['email'],
                            $address,
                            $button_edit,
                            $button_delete,
                        );
                    } else {
                    }
                } else {
                }
            }
        }
        return $data;
    }

    public function config_pagination()
    {
        $total_row = $this->M_company->count_company();
        $data = array(
            array(
                'base_url'   => base_url('Suplier/index'),
                'total_rows' => $total_row,
                'per_page'  => 10,
            ),
        );
        return $data;
    }

    public function config_search()
    {
        $data = array(
            array(
                'id'    => 'search',
                'name'  => 'search'
            ),
        );
        return $data;
    }


    public function form($id = '')
    {
        $config_form = $this->config_form($id);
        $config_card = $this->card_form($id);

        // Function View Helper
        $form = form($config_form);

        $data['title'] = "Form Suplier";
        $data['card'] = card($config_card, $form);
        $data['sidebar'] = config_sidebar();

        // Base View
        $this->load->view('theme/metronic/header');
        $this->load->view('theme/metronic/sidebar', $data);
        $this->load->view('theme/metronic/topbar');
        $this->load->view('theme/metronic/content', $data);
        $this->load->view('theme/metronic/footer');
    }

    public function card_form($id)
    {
        $data = array(
            array(
                'title'    => 'Form Suplier',
                'action'    =>  $id == '' ? 'Suplier/add_data' : 'Suplier/update_data',
                'button_save' => array(
                    'button_title'    => 'Save',
                    'button_color'     => 'success',
                    'button_action'      => '#',
                ),
                'button_cancel' => array(
                    'button_title'    => 'Cancel',
                    'button_color'     => 'danger',
                    'button_action'      => 'Suplier',
                ),
            )
        );
        return $data;
    }

    public function config_form($id)
    {
        $default_data = $this->default_data();
        $where = array(
            'id' =>  @$id
        );

        $data_company =  $this->M_company->edit_data($where, 'tbl_company')->result_array();

        if (is_array($data_company) && isset($data_company) && empty($data_company)) {
            $query = $default_data;
        } else {
            $query = $data_company;
        }

        $get_province = $this->M_company->get_province();
        $get_city = $this->M_company->get_city();
        $get_kecamatan = $this->M_company->get_kecamatan();
        foreach ($query as  $key) {
            $data = array(
                array(
                    'column'    => 'col-lg-6',
                    'form' => array(
                        array(
                            'form_title'   => '', // Judul Form
                            'place_holder'  => '', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'hidden',
                            'id'            => 'id_data',
                            'name'          => 'id_data',
                            'validation'    =>  'false',
                            'value'         =>  @$key['id'],
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'Nama', // Judul Form
                            'place_holder'  => 'Silahkan isi Nama', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'text',
                            'id'            => 'nama',
                            'name'          => 'nama',
                            'validation'    =>  'false',
                            'value'         =>  @$key['company_name'],
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'Nomor Telephone', // Judul Form
                            'place_holder'  => 'Silahkan isi nomor Telephone', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => '',
                            'id'            => 'telp',
                            'name'          => 'telp',
                            'validation'    =>  'false',
                            'value'         => @$key['no_telp'],
                            'data'          => '',
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'Email', // Judul Form
                            'place_holder'  => 'Silahkan isi Email', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'email',
                            'id'            => 'email',
                            'name'          => 'email',
                            'validation'    =>  'false',
                            'value'         => @$key['email'],
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'No Rekening', // Judul Form
                            'place_holder'  => 'Silahkan isi Nomor Rekening', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'text',
                            'id'            => 'rek',
                            'name'          => 'rek',
                            'validation'    =>  'false',
                            'value'         => '',
                            'input-type'     => 'form'
                        ),
                    ),
                ),
                array(
                    'column'    => 'col-lg-6',
                    'form' => array(
                        array(
                            'form_title'   => 'Provinsi',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'provinsi',
                            'name'          => 'provinsi',
                            'validation'    =>  'false',
                            'value'         =>  @$key['propinsi'],
                            'content_id'    => 'id_prov',
                            'content'       => 'provinsi',
                            'data'          => $get_province,
                            'input-type'    => 'select'
                        ),
                        array(
                            'form_title'   => 'Kabupaten/Kota',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'kabupaten',
                            'name'          => 'kabupaten',
                            'validation'    =>  'false',
                            'value'         => @$key['kota'],
                            'content_id'    => 'id_kab',
                            'content'       => 'kabupaten',
                            'data'          => $get_city,
                            'input-type'    => 'select'
                        ),
                        array(
                            'form_title'   => 'Kecamatan',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'kecamatan',
                            'name'          => 'kecamatan',
                            'validation'    =>  'false',
                            'value'         => @$key['kecamatan'],
                            'content_id'    => 'id_kec',
                            'content'       => 'kecamatan',
                            'data'          => $get_kecamatan,
                            'input-type'     => 'select'
                        ),
                        array(
                            'form_title'   => 'Alamat',
                            'place_holder'  => 'Jl/Rt/Rw/Kelurahan',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'alamat',
                            'name'          => 'alamat',
                            'validation'    =>  'false',
                            'value'         => @$key['alamat'],
                            'input-type'     => 'text-area'
                        ),
                    ),
                ),
            );
        }
        return $data;
    }

    public function add_data()
    {
        $nama = $this->input->post('nama');
        $no_telp = $this->input->post('telp');
        $email = $this->input->post('email');
        $no_rek = $this->input->post('rek');
        $provinsi = $this->input->post('provinsi');
        $kabupaten = $this->input->post('kabupaten');
        $kecamatan = $this->input->post('kecamatan');
        $alamat = $this->input->post('alamat');
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];

        $data = array(
            'id_dpartement' => $id_dpartement,
            'company_name' => $nama,
            'no_telp'   => $no_telp,
            'email'     => $email,
            'propinsi'  => $provinsi,
            'kota'      => $kabupaten,
            'kecamatan' => $kecamatan,
            'alamat'    => $alamat == null ? '' : $alamat,
            'type'      => 2 // type satu adalah type Suplier
        );

        $this->M_company->input_company($data, 'tbl_company');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Simpan ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('Suplier/index');
    }

    public function update_data()
    {
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];
        $id = $this->input->post('id_data');
        $nama = $this->input->post('nama');
        $no_telp = $this->input->post('telp');
        $email = $this->input->post('email');
        $no_rek = $this->input->post('rek');
        $provinsi = $this->input->post('provinsi');
        $kabupaten = $this->input->post('kabupaten');
        $kecamatan = $this->input->post('kecamatan');
        $alamat = $this->input->post('alamat');

        $data = array(
            'id_dpartement' => $id_dpartement,
            'company_name' => $nama,
            'no_telp'   => $no_telp,
            'email'     => $email,
            'propinsi'  => $provinsi,
            'kota'      => $kabupaten,
            'kecamatan' => $kecamatan,
            'alamat'    => $alamat == null ? '' : $alamat,
            'type'      => 2 // type satu adalah type Suplier
        );

        $where = array(
            'id'    => $id
        );
        $this->M_company->update_company($where, $data, 'tbl_company');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Edit ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('Suplier/index');
    }

    public function delete_data($id)
    {
        $where = array('id' => $id);
        $this->M_company->delete_company($where, 'tbl_company');
        $config_alert_danger = array(
            array(
                'title'     => 'Data Berhasil di Hapus ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_danger = allert($config_alert_danger);
        $this->session->set_flashdata('msg', $allert_danger);
        redirect('Suplier/index');
    }
}
