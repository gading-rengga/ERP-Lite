<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('M_transaction');
        $this->load->model('M_company');
        $this->load->model('M_auth');
    }

    public function default_data()
    {
        $data = array(
            'id'          => '',
            'sales_id'    => '',
            'customer_id' => '',
            'product_id'  => '',
            'propinsi' =>  '',
            'kabupaten' =>  '',
            'kecamatan' =>  '',
            'alamat'  => '',
            'qty'         => '',
            'tanggal'   => '',
            'id_dpartement'   => '',
        );
        return $data;
    }
    public function index()
    {
        $config_card = $this->card_table();
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

        $data['card'] = card($config_card, $content);
        $data['sidebar'] = config_sidebar();
        $data['title'] = 'Data Penjualan';
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
                'title'    => 'Data Penjualan',
                'icon'    => 'fas fa-cash-register',
                'action'    => 'penjualan',
                // Optional Button
                'button' => array(
                    'button_link'      => 'Penjualan/callback_form',
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

        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];
        $start = $this->uri->segment(3);
        $perpage  = 10;
        $keyword = $this->input->post('search');
        $data_search = $this->M_transaction->get_keyword($keyword, $perpage, $start, $id_dpartement)->result_array();
        $data_transaction = $this->M_transaction->get_data($id_dpartement)->result_array();
        $config_pagination = $this->config_pagination();
        $config = pagination($config_pagination);
        $this->pagination->initialize($config);


        $data['t_head'] = array(
            array(
                'NO',
                'Nama Customer',
                'Nama Marketing',
                'Tanggal Transaksi',
                'Total Price',
                'Alamat Pengiriman',
                'Edit',
                'Hapus'
            )
        );
        if ($data_search == '') {
            $data_table = $data_transaction;
        } elseif ($data_search !== '') {
            $data_table = $data_search;
        }
        if (isset($data)) {
            foreach ($data_table as $index => $key) {
                if ($key['id_dpartement'] == $id_dpartement) {
                    $get_province = $this->M_transaction->get_province_name($key['propinsi']);
                    foreach ($get_province as $val) {
                        $province = $val['provinsi'];
                    }
                    $get_city = $this->M_transaction->get_city_name($key['kabupaten']);
                    foreach ($get_city as $val) {
                        $city = $val['kabupaten'];
                    }
                    $get_district = $this->M_transaction->get_district_name($key['kecamatan']);
                    foreach ($get_district as $val) {
                        $district = $val['kecamatan'];
                    }
                    $address = $key['alamat'] . ',' . $district . ',' . $city . ',' . $province;
                    $config_button_edit = array(
                        array(
                            'button' => array(
                                'button_link'      => 'Penjualan/form/' . $key['id'],
                                'button_title'     => 'Edit',
                                'button_color'     => 'primary'
                            ),
                        )
                    );
                    $config_button_delete = array(
                        array(
                            'button' => array(
                                'button_link'      => 'Penjualan/delete_data/' . $key['id'],
                                'button_title'     => 'Hapus',
                                'button_color'     => 'primary'
                            ),
                        )
                    );
                    $button_edit = button_edit($config_button_edit);
                    $button_delete = button_delete($config_button_delete);

                    $data['t_body'][$index] = array(
                        ++$start,
                        $key['company_name'],
                        $key['employe_name'],
                        $key['tanggal'],
                        $key['total_price'],
                        $address,
                        $button_edit,
                        $button_delete,
                    );
                } else {
                }
            }
        }
        return $data;
    }

    public function config_pagination()
    {
        $total_row = $this->M_transaction->count_data();
        $data = array(
            array(
                'base_url'   => base_url('Penjualan/index'),
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

    public function callback_form()
    {
        $id = -1;
        $where = array(
            'id_transaction' => -1
        );
        $get_qty_transaction = $this->M_transaction->get_qty_trans($id);
        foreach ($get_qty_transaction as $key) {
            $data_stock = $this->M_transaction->get_data_stock($key['product_id']);
            foreach ($data_stock as $val) {
                $update_stock = $key['qty'] + $val['qty'];

                $where_data = array(
                    'product_id' => $key['product_id']
                );

                $update_data = array(
                    'qty'   => $update_stock
                );

                $this->M_transaction->update_data($where_data, $update_data, 'tbl_stock');
            }
        }
        $this->M_transaction->delete_data($where, 'tbl_transaction_meta');
        redirect('Penjualan/form');
    }


    public function form($id = '')
    {
        $get_id = array(
            'post_id' => $id
        );
        $this->session->set_userdata($get_id);
        $config_form = $this->config_form($id);
        $config_card = $this->card_form($id);
        $card_table_form = $this->config_card_meta($id);
        $config_table_meta = $this->table_meta($id);
        // Function View Helper
        $form = form($config_form);
        $table_meta = data_table($config_table_meta);
        $card_table_form = card($card_table_form, $table_meta);

        $content = array(
            $form,
            $card_table_form
        );

        $data['title'] = "Form Penjualan";
        $data['card'] = card($config_card, $content);
        $data['sidebar'] = config_sidebar();

        // Base View
        $this->load->view('theme/metronic/header');
        $this->load->view('theme/metronic/sidebar', $data);
        $this->load->view('theme/metronic/topbar');
        $this->load->view('theme/metronic/content', $data);
        $this->load->view('theme/metronic/footer');
    }

    public function config_form($id)
    {
        $default_data = $this->default_data();
        $where = array(
            'id' =>  @$id
        );


        $data_transaction =  $this->M_transaction->edit_data($where, 'tbl_transaction')->result_array();
        if (is_array($data_transaction) && isset($data_transaction) && empty($data_transaction)) {
            $query = $default_data;
        } else {
            $query = $data_transaction;
        }
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];

        $get_employe = $this->M_transaction->get_employe($id_dpartement);
        $get_product = $this->M_transaction->get_product($id_dpartement);
        $get_company = $this->M_transaction->get_company($id_dpartement);
        $get_province = $this->M_transaction->get_province();
        $get_city = $this->M_transaction->get_city();
        $get_kecamatan = $this->M_transaction->get_kecamatan();
        foreach ($query as  $key) {
            $data = array(
                array(
                    'column'    => 'col-lg-6',
                    'form' => array(
                        array(
                            'form_title'    => '', // Judul Form
                            'place_holder'  => '', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'hidden',
                            'id'            => 'id',
                            'name'          => 'id',
                            'validation'    =>  'false',
                            'value'         =>  @$key['id'],
                            'input-type'    => 'form'
                        ),
                        array(
                            'form_title'   => 'Pilih Sales/Marketing',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'employe_id',
                            'name'          => 'employe_id',
                            'validation'    =>  'false',
                            'value'         =>  @$key['sales_id'],
                            'content_id'    => 'id',
                            'content'       => 'employe_name',
                            'data'          => $get_employe,
                            'input-type'    => 'select'
                        ),
                        array(
                            'form_title'   => 'Pilih Customer',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'company_id',
                            'name'          => 'company_id',
                            'validation'    =>  'false',
                            'value'         =>  @$key['customer_id'],
                            'content_id'    => 'id',
                            'content'       => 'company_name',
                            'data'          => $get_company,
                            'input-type'    => 'select'
                        ),

                        array(
                            'form_title'   => 'Tanggal', // Judul Form
                            'place_holder'  => 'Silahkan isi Tanggal', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'date',
                            'id'            => 'date',
                            'name'          => 'date',
                            'validation'    =>  'true',
                            'value'         => @$key['tanggal'],
                            'input-type'     => 'form'
                        ),
                    ),
                ),
                array(
                    'column'    => 'col-lg-6',
                    'form' => array(
                        array(
                            'form_title'   => 'Provinsi Pengiriman',
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
                            'form_title'   => 'Kabupaten/Kota Pengiriman',
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
                            'form_title'   => 'Kecamatan Pengiriman',
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
                            'form_title'   => 'Alamat Pengiriman',
                            'place_holder'  => 'Jl/Rt/Rw/Kelurahan',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'alamat',
                            'name'          => 'alamat',
                            'validation'    =>  'true',
                            'value'         => @$key['alamat'],
                            'input-type'     => 'text-area'
                        ),
                    ),
                ),
            );
        }
        return $data;
    }

    public function card_form($id)
    {
        $data = array(
            array(
                'title'    => 'Form Penjualan',
                'action'    =>  $id == '' ? 'Penjualan/add_data' : 'Penjualan/update_data',
                'button_save' => array(
                    'button_title'    => 'Save',
                    'button_color'     => 'success',
                    'button_action'      => '#',
                ),
                'button_cancel' => array(
                    'button_title'    => 'Cancel',
                    'button_color'     => 'danger',
                    'button_action'      => 'Penjualan',
                ),
            )
        );
        return $data;
    }

    public function add_data()
    {
        $sales = $this->input->post('employe_id');
        $customer = $this->input->post('company_id');
        $provinsi = $this->input->post('provinsi');
        $kabupaten = $this->input->post('kabupaten');
        $kecamatan = $this->input->post('kecamatan');
        $alamat = $this->input->post('alamat');
        $date = $this->input->post('date');
        $data_user = $this->session->userdata(['data'][0]);
        $id_user = $data_user['id'];
        $id_dpartement = $data_user['id_dpartement'];
        $post_id = $this->session->userdata(['post_id'][0]);

        $get_trans_meta = $this->M_transaction->get_qty_trans(-1);
        $tot = 0;

        foreach ($get_trans_meta as $val) {
            $sub_total =  $tot += $val['total_price'];
        }



        $data = array(
            'sales_id'      => $sales,
            'customer_id'   => $customer,
            'propinsi'  => $provinsi,
            'kabupaten'      => $kabupaten,
            'kecamatan' => $kecamatan,
            'alamat'    => $alamat == null ? '' : $alamat,
            'tanggal'   => $date,
            'total_price'   => @$sub_total,
            'id_user'   => $id_user,
            'id_dpartement'   => $id_dpartement,
        );


        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil Disimpan',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);


        // Proses Input data dan cek jumlah product terhadap data stock product
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('date', 'Tanggal', 'required');
        if ($this->form_validation->run() == false) {
            $this->form();
        } else {
            $this->session->set_flashdata('msg', $allert_success);
            $this->M_transaction->input_data($data, 'tbl_transaction');
            $last_insert_id = $this->db->insert_id();

            $data_meta = array(
                'id_transaction' => $last_insert_id
            );

            $where_meta = array(
                'id_transaction' => -1
            );
            $this->M_transaction->update_data($where_meta, $data_meta, 'tbl_transaction_meta');
            redirect('Penjualan/index');
        }
    }

    public function update_data()
    {
        $id = $this->input->post('id');
        $sales = $this->input->post('employe_id');
        $customer = $this->input->post('company_id');
        $provinsi = $this->input->post('provinsi');
        $kabupaten = $this->input->post('kabupaten');
        $kecamatan = $this->input->post('kecamatan');
        $alamat = $this->input->post('alamat');
        $date = $this->input->post('date');
        $data_user = $this->session->userdata(['data'][0]);
        $id_user = $data_user['id'];
        $id_dpartement = $data_user['id_dpartement'];

        $get_trans_meta = $this->M_transaction->get_qty_trans($id);
        $tot = 0;
        foreach ($get_trans_meta as $val) {
            $sub_total =  $tot += $val['total_price'];
        }

        $data = array(
            'sales_id'      => $sales,
            'customer_id'   => $customer,
            'total_price'   => $sub_total,
            'propinsi'  => $provinsi,
            'kabupaten'      => $kabupaten,
            'kecamatan' => $kecamatan,
            'alamat'    => $alamat == null ? '' : $alamat,
            'tanggal'   => $date,
            'id_user'   => $id_user,
            'id_dpartement'   => $id_dpartement,
        );
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil Di Edit',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);

        // Proses Input data dan cek jumlah product terhadap data stock product
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('date', 'Tanggal', 'required');
        if ($this->form_validation->run() == false) {
            $this->form();
        } else {
            $this->session->set_flashdata('msg', $allert_success);
            $where_update = array(
                'id'    => $id
            );

            $this->M_transaction->update_data($where_update, $data, 'tbl_transaction');
            redirect('Penjualan/index');
        }
    }

    public function delete_data($id)
    {
        $get_qty_transaction = $this->M_transaction->get_qty_trans($id);
        foreach ($get_qty_transaction as $key) {
            $data_stock = $this->M_transaction->get_data_stock($key['product_id']);
            foreach ($data_stock as $val) {
                $update_stock = $key['qty'] + $val['qty'];

                $where_data = array(
                    'product_id' => $key['product_id']
                );

                $update_data = array(
                    'qty'   => $update_stock
                );

                $this->M_transaction->update_data($where_data, $update_data, 'tbl_stock');
            }
        }
        $where = array('id' => $id);
        $where_meta =  array('id_transaction' => $id);
        $this->M_transaction->delete_data($where, 'tbl_transaction');
        $this->M_transaction->delete_data($where_meta, 'tbl_transaction_meta');
        $config_alert_danger = array(
            array(
                'title'     => 'Data Telah Terhapus',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_danger = allert($config_alert_danger);
        $this->session->set_flashdata('msg', $allert_danger);
        $config_alert_danger = array(
            array(
                'title'     => 'Data Berhasil di Hapus ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_danger = allert($config_alert_danger);
        $this->session->set_flashdata('msg', $allert_danger);
        redirect('Penjualan/index');
    }

    public function config_card_meta()
    {
        $data = array(
            array(
                'title'    => 'Data Pembelian',
                'action'    => 'meta',
                'icon' => 'fas fa-cart-plus',
                // Optional Button
                'button' => array(
                    'button_link'      => 'Penjualan/form_meta/',
                    'button_title'    => 'Tambah Data',
                    'button_color'     => 'primary'
                ),
            )
        );
        return $data;
    }

    public function table_meta($id)
    {
        if ($id == '') {
            $id = -1;
        } else {
            $id = $id;
        }
        $data_table = $this->M_transaction->get_transaction_meta($id);
        $no = 0;

        $data['t_head'] = array(
            array(
                'NO',
                'Nama Barang',
                'Qty',
                'Total Price',
                'Action',
            ),
        );
        foreach ($data_table as $key => $val) {
            $config_dropdown_action = array(
                array(
                    'button_link'      => 'Penjualan/form_meta/' . $val['id'],
                    'button_title'     => 'Edit',
                    'button_icon'      => 'far fa-edit'
                ),
                array(
                    'button_link'      => 'Penjualan/delete_data_meta/' . $val['id'],
                    'button_title'     => 'Hapus',
                    'button_icon'      => 'far fa-trash-alt'
                )
            );
            $dropdown_action = dropdown_action($config_dropdown_action);
            $get_name_product = $this->M_transaction->get_data_product($val['product_id']);
            foreach ($get_name_product as $var) {
                $get_product = $var['product_name'];
            }
            $data['t_body'][$key] = array(
                ++$no,
                $get_product,
                $val['qty'],
                $val['total_price'],
                $dropdown_action
            );
        }

        return $data;
    }

    public function default_data_meta()
    {
        $data = array(
            'id'          => '',
            'product_id'  => '',
            'qty'         => '',
        );
        return $data;
    }

    public function form_meta($id = '')
    {
        $config_form = $this->config_form_meta($id);
        $config_card = $this->card_form_meta($id);

        $content = form($config_form);

        $data['title'] = "Form Keranjang Barang";
        $data['sidebar'] = config_sidebar();
        $data['card'] = card($config_card, $content);

        // Base View
        $this->load->view('theme/metronic/header');
        $this->load->view('theme/metronic/sidebar', $data);
        $this->load->view('theme/metronic/topbar');
        $this->load->view('theme/metronic/content', $data);
        $this->load->view('theme/metronic/footer');
    }

    public function card_form_meta($id)
    {
        $post_id = $this->session->userdata(['post_id'][0]);
        $data = array(
            array(
                'title'    => 'Form meta',
                'action'    =>  $id == ''  ? 'Penjualan/add_data_meta' : 'Penjualan/update_data_meta',
                'button_save' => array(
                    'button_title'    => 'Save',
                    'button_color'     => 'success',
                    'button_action'      => '#',
                ),
                'button_cancel' => array(
                    'button_title'    => 'Cancel',
                    'button_color'     => 'danger',
                    'button_action'      => $post_id == '' || $post_id == null ? 'Penjualan/form' : 'Penjualan/form/' . $post_id,
                ),
            )
        );
        return $data;
    }

    public function config_form_meta($id)
    {
        $default_data = $this->default_data_meta();

        $where = array(
            'id' => @$id
        );
        $data_transaction =  $this->M_transaction->edit_data($where, 'tbl_transaction_meta')->result_array();
        if (is_array($data_transaction) && isset($data_transaction) && empty($data_transaction)) {
            $query = $default_data;
        } else {
            $query = $data_transaction;
        }
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];
        $get_product = $this->M_transaction->get_product($id_dpartement);
        foreach ($query as  $key) {
            $data = array(
                array(
                    'column'    => 'col-lg-12',
                    'form' => array(
                        array(
                            'form_title'    => '', // Judul Form
                            'place_holder'  => '', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'hidden',
                            'id'            => 'id',
                            'name'          => 'id',
                            'validation'    =>  'false',
                            'value'         =>   @$key['id'],
                            'input-type'    => 'form'
                        ),
                        array(
                            'form_title'    => '', // Judul Form
                            'place_holder'  => '', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'hidden',
                            'id'            => 'id_trans',
                            'name'          => 'id_trans',
                            'validation'    =>  'false',
                            'value'         =>   @$key['id_transaction'],
                            'input-type'    => 'form'
                        ),
                        array(
                            'form_title'   => 'Pilih Product',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'product_id',
                            'name'          => 'product_id',
                            'validation'    =>  'false',
                            'value'         => @$key['id'],
                            'content_id'    => 'id',
                            'content'       => 'product_name',
                            'data'          => $get_product,
                            'input-type'    => 'select'
                        ),
                        array(
                            'form_title'   => 'Qty/Jumlah Product', // Judul Form
                            'place_holder'  => 'Silahkan isi Jumlah Product', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'text',
                            'id'            => 'qty',
                            'validation'    =>  'true',
                            'name'          => 'qty',
                            'value'         =>  @$key['qty'],
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'Discount', // Judul Form
                            'place_holder'  => 'Silahkan isi Discount', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'text',
                            'id'            => 'discount',
                            'validation'    =>  'false',
                            'name'          => 'discount',
                            'value'         => @$key['discount'],
                            'input-type'     => 'form'
                        ),
                    ),
                ),
            );
        }
        return $data;
    }

    public function add_data_meta()
    {
        $post_id = $this->session->userdata(['post_id'][0]);
        $product = $this->input->post('product_id');
        $qty = $this->input->post('qty');
        $discount = $this->input->post('discount');
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];


        $get_product = $this->M_transaction->get_data_product($product);
        $get_stock = $this->M_transaction->get_data_stock($product);

        // Proses menghitung Discount
        $qty_product = $qty == 0 || $qty == null ? 0 : $qty;
        $disc = $discount == 0 || $discount == null ? 0 : $discount;
        foreach ($get_product as $val) {
            $count_price = $val['price'] * $qty_product;
            $count_disc = $disc > 0 ? $count_price * $disc / 100 : $count_price * 0 / 100;
            $total_price = $count_price - $count_disc;
        }
        // End Proses Discount

        $data = array(
            'id_transaction' => $post_id == '' || $post_id == null ? -1 : $post_id,
            'product_id' => $product,
            'qty'       => $qty,
            'discount'       => $discount,
            'total_price' => $total_price,
            'id_dpartement' => $id_dpartement
        );



        $config_alert_danger = array(
            array(
                'title'     => 'Stock tidak mencukupi!',
                'alert_type' => 'alert-danger'
            ),
        );
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil Di Simpan',
                'alert_type' => 'alert-success'
            ),
        );

        $allert_danger = allert($config_alert_danger);
        $allert_success = allert($config_alert_success);


        // Proses Input data dan cek jumlah product terhadap data stock product
        $this->form_validation->set_rules('qty', 'Jumlah Product', 'required');
        if ($this->form_validation->run() == false) {
            $this->form_meta();
        } else {
            foreach ($get_stock as $val) {
                if ($val['qty'] < $qty_product) {
                    $this->session->set_flashdata('msg', $allert_danger);
                    redirect('Penjualan/form_meta/');
                } else {
                    $update_stock = $val['qty'] - $qty_product;
                    $this->session->set_flashdata('msg', $allert_success);

                    $where = array(
                        'product_id'    => $product
                    );

                    $update_data = array(
                        'qty' => $update_stock
                    );
                    $this->M_transaction->update_data($where, $update_data, 'tbl_stock');
                    $this->M_transaction->input_data($data, 'tbl_transaction_meta');
                    if ($post_id == '' || $post_id == null) {
                        redirect('Penjualan/form');
                    } else {
                        redirect('Penjualan/form/' . $post_id);
                    }
                }
            }
        }
    }

    public function update_data_meta()
    {
        $id = $this->input->post('id');
        $id_trans = $this->input->post('id_trans');
        $product = $this->input->post('product_id');
        $qty = $this->input->post('qty');
        $discount = $this->input->post('discount');
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];

        $get_product = $this->M_transaction->get_data_product($product);


        $qty_product = $qty == 0 || $qty == null ? 0 : $qty;
        $disc = $discount == 0 || $discount == null ? 0 : $discount;

        // Proses Menghitung Discount
        foreach ($get_product as $val) {
            $count_price = $val['price'] * $qty_product;
            $count_disc = $disc > 0 ? $count_price * $disc / 100 : $count_price * 0 / 100;
            $total_price = $count_price - $count_disc;
        }
        // End Proses Discount


        // Proses Pengembalian data stock sebelum di edit
        $get_qty_transaction = $this->M_transaction->get_trans_meta($id);
        foreach ($get_qty_transaction as $key) {

            $data_stock = $this->M_transaction->get_data_stock($key['product_id']);
            foreach ($data_stock as $val) {
                $update_stock = $key['qty'] + $val['qty'];

                $where_data = array(
                    'product_id' => $key['product_id']
                );

                $update_data = array(
                    'qty'   => $update_stock
                );

                $this->M_transaction->update_data($where_data, $update_data, 'tbl_stock');
            }
        }
        // End Proses Pengembalian

        $data = array(
            'id_transaction' => $id_trans,
            'product_id' => $product,
            'qty'       => $qty,
            'discount'       => $discount,
            'total_price' => $total_price,
            'id_dpartement' => $id_dpartement
        );

        $config_alert_danger = array(
            array(
                'title'     => 'Stock tidak mencukupi!',
                'alert_type' => 'alert-danger'
            ),
        );
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil Di Edit',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_danger = allert($config_alert_danger);
        $allert_success = allert($config_alert_success);

        $this->form_validation->set_rules('qty', 'Jumlah Product', 'required');
        $get_stock = $this->M_transaction->get_data_stock($product);

        if ($this->form_validation->run() == false) {
            $this->form_meta();
        } else {
            foreach ($get_stock as $val) {
                if ($val['qty'] < $qty_product) {
                    $this->session->set_flashdata('msg', $allert_danger);
                    $qty_product == $qty_product;
                    redirect('Penjualan/form_meta/' . $id);
                } else {
                    $update_stock = $val['qty'] - $qty_product;
                    $this->session->set_flashdata('msg', $allert_success);

                    $where_update = array(
                        'id'    => $id
                    );

                    $where = array(
                        'product_id'    => $product
                    );

                    $update_data = array(
                        'qty' => $update_stock
                    );
                    $this->M_transaction->update_data($where_update, $data, 'tbl_transaction_meta');
                    $this->M_transaction->update_data($where, $update_data, 'tbl_stock');
                    redirect('Penjualan/form/' . $id_trans);
                }
            }
        }
    }

    public function delete_data_meta($id)
    {
        $get_qty_transaction = $this->M_transaction->get_trans_meta($id);
        foreach ($get_qty_transaction as $key) {
            $data_stock = $this->M_transaction->get_data_stock($key['product_id']);
            foreach ($data_stock as $val) {
                $update_stock = $key['qty'] + $val['qty'];

                $where_data = array(
                    'product_id' => $key['product_id']
                );

                $update_data = array(
                    'qty'   => $update_stock
                );

                $this->M_transaction->update_data($where_data, $update_data, 'tbl_stock');
            }
        }
        $post_id = $this->session->userdata(['post_id'][0]);
        $where = array('id' => $id);
        $this->M_transaction->delete_data($where, 'tbl_transaction_meta');
        if ($post_id == '' || $post_id == null) {
            redirect('Penjualan/form/');
        } else {
            redirect('Penjualan/form/' . $post_id);
        }
    }
}
