<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('M_karyawan');
        $this->load->model('M_auth');
        $this->load->model('M_auth');
    }

    public function default_data()
    {
        $data = array(
            'id' =>  '',
            'employe_name' =>  '',
            'no_telp' =>  '',
            'email' =>  '',
            'no_ktp' =>  '',
            'no_kk' =>  '',
            'status' =>  '',
            'ijasah' =>  '',
            'jurusan' =>  '',
            'no_rek' =>  '',
            'divisi' =>  '',
            'id_dpartement' =>  '',
            'alamat' =>  '',
            'provinsi' =>  '',
            'kota' =>  '',
            'kecamatan' =>  '',
            'kode_pos' =>  '',
        );
        return $data;
    }
    public function index()
    {
        $data_user = $this->session->userdata('data');
        $config_search = $this->config_search();
        $config_card = $this->card_table();
        $config_table = $this->config_table();
        $table = data_table($config_table);
        $search = search($config_search);

        $pagination =  $this->pagination->create_links();
        $content = array(
            $search,
            $table,
            $pagination
        );

        $data['controller'] = 'Karyawan';
        $data['card'] = card($config_card, $content);
        $data['sidebar'] = config_sidebar();
        $data['title'] = 'Data Karyawan';
        $this->load->view('theme/metronic/header');
        $this->load->view('theme/metronic/sidebar', $data);
        $this->load->view('theme/metronic/topbar');
        $this->load->view('theme/metronic/content', $data);
        $this->load->view('theme/metronic/footer');
    }

    public function card_table()
    {
        $data = array(
            array(
                'title'    => 'Data Karyawan',
                'action'    => 'Karyawan',
                // Optional Button
                'button' => array(
                    'button_link'      => 'Karyawan/form',
                    'button_title'    => 'Tambah Data',
                    'button_color'     => 'primary'
                ),
            )
        );
        return $data;
    }

    public function config_table()
    {
        $this->load->library('pagination');

        $start = $this->uri->segment(3);
        $perpage  = 10;
        $keyword = $this->input->post('search');
        $data_search = $this->M_karyawan->get_keyword($keyword, $perpage, $start)->result_array();
        $data_karyawan = $this->M_karyawan->get_employe($perpage, $start)->result_array();
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
            $data_table = $data_karyawan;
        } elseif ($data_search !== '') {
            $data_table = $data_search;
        }

        if (isset($data)) {
            foreach ($data_table as $index => $key) {
                $get_province = $this->M_karyawan->get_province_name($key['provinsi']);
                foreach ($get_province as $val) {
                    $province = $val['provinsi'];
                }
                $get_city = $this->M_karyawan->get_city_name($key['kota']);
                foreach ($get_city as $val) {
                    $city = $val['kabupaten'];
                }
                $get_district = $this->M_karyawan->get_district_name($key['kecamatan']);
                foreach ($get_district as $val) {
                    $district = $val['kecamatan'];
                }
                $address = $key['alamat'] . ',' . $district . ',' . $city . ',' . $province;
                $config_button_edit = array(
                    array(
                        'button' => array(
                            'button_link'      => 'Karyawan/form/' . $key['id'],
                            'button_title'    => 'Edit',
                            'button_color'     => 'primary'
                        ),
                    )
                );
                $config_button_delete = array(
                    array(
                        'button' => array(
                            'button_link'      => 'Karyawan/delete_data/' . $key['id'],
                            'button_title'    => 'Hapus',
                            'button_color'     => 'primary'
                        ),
                    )
                );
                $button_edit = button_edit($config_button_edit);
                $button_delete = button_delete($config_button_delete);

                $data['t_body'][$index] = array(
                    ++$start,
                    $key['employe_name'],
                    $key['no_telp'],
                    $key['email'],
                    $address,
                    $button_edit,
                    $button_delete,
                );
            }
        }
        return $data;
    }

    public function config_pagination()
    {
        $total_row = $this->M_karyawan->count_employe();
        $data = array(
            array(
                'base_url'   => base_url('Karyawan/index'),
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

        $data['title'] = "Form Karyawan";
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
                'title'    => 'Form Karyawan',
                'action'    =>  $id == '' ? 'Karyawan/add_data' : 'Karyawan/update_data',
                'button_save' => array(
                    'button_title'    => 'Save',
                    'button_color'     => 'success',
                    'button_action'      => '#',
                ),
                'button_cancel' => array(
                    'button_title'    => 'Cancel',
                    'button_color'    => 'danger',
                    'button_action'   => 'Karyawan',
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

        $status = array(
            array(
                'id_status'     => 1,
                'name_status'   => 'Active'
            ),
            array(
                'id_status'     => 2,
                'name_status'   => 'Resign'
            ),
        );

        $data_employe =  $this->M_karyawan->edit_data($where, 'tbl_employe')->result_array();

        if (is_array($data_employe) && isset($data_employe) && empty($data_employe)) {
            $query = $default_data;
        } else {
            $query = $data_employe;
        }

        $get_province = $this->M_karyawan->get_province();
        $get_city = $this->M_karyawan->get_city();
        $get_kecamatan = $this->M_karyawan->get_kecamatan();
        $get_divisi = $this->M_karyawan->get_divisi();
        $get_dpartement = $this->M_karyawan->get_dpartement();
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
                            'input-type'     => 'form',
                        ),
                        array(
                            'form_title'   => 'Nama', // Judul Form
                            'place_holder'  => 'Silahkan isi nama', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'text',
                            'id'            => 'employe_name',
                            'name'          => 'employe_name',
                            'validation'    =>  'false',
                            'value'         =>  @$key['employe_name'],
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
                            'form_title'   => 'No KTP', // Judul Form
                            'place_holder'  => 'Silahkan isi KTP', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'text',
                            'id'            => 'ktp',
                            'name'          => 'ktp',
                            'validation'    =>  'false',
                            'value'         => @$key['no_ktp'],
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'No KK', // Judul Form
                            'place_holder'  => 'Silahkan isi KK', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'text',
                            'id'            => 'kk',
                            'name'          => 'kk',
                            'validation'    =>  'false',
                            'value'         => @$key['no_ktp'],
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'Status',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'status',
                            'name'          => 'status',
                            'value'         =>  @$key['status'],
                            'content_id'    => 'id_status',
                            'validation'    =>  'false',
                            'content'       => 'name_status',
                            'data'          => $status,
                            'input-type'    => 'select'
                        ),
                        array(
                            'form_title'   => 'Divisi',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'divisi',
                            'name'          => 'divisi',
                            'value'         =>  @$key['divisi'],
                            'validation'    =>  'false',
                            'content_id'    => 'divisi_id',
                            'content'       => 'name_divisi',
                            'data'          => $get_divisi,
                            'input-type'    => 'select'
                        ),
                    ),
                ),
                array(
                    'column'    => 'col-lg-6',
                    'form' => array(
                        array(
                            'form_title'   => 'Dpartement',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'dpartement',
                            'name'          => 'dpartement',
                            'value'         =>  @$key['id_dpartement'],
                            'validation'    =>  'false',
                            'content_id'    => 'id',
                            'content'       => 'nama_dpartement',
                            'data'          => $get_dpartement,
                            'input-type'    => 'select'
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
                        array(
                            'form_title'   => 'Provinsi',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'provinsi',
                            'name'          => 'provinsi',
                            'validation'    =>  'false',
                            'value'         =>  @$key['provinsi'],
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
                            'validation'    =>  'false',
                            'name'          => 'kecamatan',
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
                            'validation'    =>  'false',
                            'name'          => 'alamat',
                            'value'         => @$key['alamat'],
                            'input-type'     => 'text-area'
                        ),
                    ),
                ),
            );
        }
        return $data;
    }

    public function config_form_repeater()
    {
        $data = array(
            array(
                'form_title'   => 'Nama', // Judul Form
                'place_holder'  => 'Silahkan isi nama', // Isi PlaceHolder
                'note'          => '', // Note form
                'type'          => 'text',
                'id'            => 'employe_name',
                'name'          => 'employe_name',
                'validation'    =>  'false',
                'value'         => '',
                'input-type'     => 'form'
            ),
        );
    }
    public function add_data()
    {
        $id = $this->input->post('id_data');
        $employe_name = $this->input->post('employe_name');
        $no_telp = $this->input->post('telp');
        $no_ktp = $this->input->post('ktp');
        $no_kk = $this->input->post('kk');
        $divisi = $this->input->post('divisi');
        $dpartement = $this->input->post('dpartement');
        $status = $this->input->post('status');
        $email = $this->input->post('email');
        $no_rek = $this->input->post('rek');
        $provinsi = $this->input->post('provinsi');
        $kabupaten = $this->input->post('kabupaten');
        $kecamatan = $this->input->post('kecamatan');
        $alamat = $this->input->post('alamat');


        $data = array(
            'employe_name'      => $employe_name,
            'no_telp'   => $no_telp,
            'no_ktp'   => $no_ktp,
            'no_kk'   => $no_kk,
            'email'     => $email,
            'divisi'     => $divisi,
            'id_dpartement'     => $dpartement,
            'status'     => $status,
            'no_rek'     => $no_rek,
            'provinsi'  => $provinsi,
            'kota'      => $kabupaten,
            'kecamatan' => $kecamatan,
            'alamat'    => $alamat == null ? '' : $alamat,
        );


        $this->M_karyawan->input_employe($data, 'tbl_employe');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Simpan ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('Karyawan/index');
    }

    public function update_data()
    {
        $id = $this->input->post('id_data');
        $employe_name = $this->input->post('employe_name');
        $no_telp = $this->input->post('telp');
        $no_ktp = $this->input->post('ktp');
        $no_kk = $this->input->post('kk');
        $divisi = $this->input->post('divisi');
        $dpartement = $this->input->post('dpartement');
        $status = $this->input->post('status');
        $email = $this->input->post('email');
        $no_rek = $this->input->post('rek');
        $provinsi = $this->input->post('provinsi');
        $kabupaten = $this->input->post('kabupaten');
        $kecamatan = $this->input->post('kecamatan');
        $alamat = $this->input->post('alamat');

        $data = array(
            'employe_name'      => $employe_name,
            'no_telp'   => $no_telp,
            'no_ktp'   => $no_ktp,
            'no_kk'   => $no_kk,
            'email'     => $email,
            'divisi'     => $divisi,
            'id_dpartement'     => $dpartement,
            'status'     => $status,
            'no_rek'     => $no_rek,
            'provinsi'  => $provinsi,
            'kota'      => $kabupaten,
            'kecamatan' => $kecamatan,
            'alamat'    => $alamat == null ? '' : $alamat,
        );

        $where = array(
            'id'    => $id
        );
        $this->M_karyawan->update_employe($where, $data, 'tbl_employe');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Edit ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('Karyawan/index');
    }

    public function delete_data($id)
    {
        $where = array('id' => $id);
        $this->M_karyawan->delete_employe($where, 'tbl_employe');
        $config_alert_danger = array(
            array(
                'title'     => 'Data Berhasil di Hapus',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_danger = allert($config_alert_danger);
        $this->session->set_flashdata('msg', $allert_danger);
        redirect('Karyawan/index');
    }
}
