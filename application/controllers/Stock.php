<?php
defined('BASEPATH') or exit('No direct script access allowed');

class stock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('M_stock');
        $this->load->model('M_auth');
    }

    public function default_data()
    {
        $data = array(
            'id'            =>  '',
            'product_id'    => '',
            'qty'           => '',
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

        $pagination =  $this->pagination->create_links();
        $content = array(
            $search,
            $table,
            $pagination
        );

        $data['card'] = card($config_card, $content);
        $data['sidebar'] = config_sidebar();
        $data['title'] = 'Data stock';
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
                'title'    => 'Data stock',
                'action'    => 'stock',
                // Optional Button
                'button' => array(
                    'button_link'      => 'stock/form',
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
        $data_search = $this->M_stock->get_keyword($keyword, $perpage, $start,  $id_dpartement)->result_array();
        $data_stock = $this->M_stock->get_data($perpage, $start, $id_dpartement)->result_array();
        $config_pagination = $this->config_pagination();
        $config = pagination($config_pagination);
        $this->pagination->initialize($config);


        $data['t_head'] = array(
            array(
                'NO',
                'Image',
                'Nama',
                'Kode',
                'Qty',
                'Edit',
                'Hapus'
            )
        );
        if ($data_search == '') {
            $data_table = $data_stock;
        } elseif ($data_search !== '') {
            $data_table = $data_search;
        }

        if (isset($data)) {
            foreach ($data_table as $index => $key) {
                if ($key['id_dpartement'] == $id_dpartement) {
                    $config_button_edit = array(
                        array(
                            'button' => array(
                                'button_link'      => 'stock/form/' . $key['id'],
                                'button_title'     => 'Edit',
                                'button_color'     => 'primary'
                            ),
                        )
                    );
                    $config_button_delete = array(
                        array(
                            'button' => array(
                                'button_link'      => 'stock/delete_data/' . $key['id'],
                                'button_title'     => 'Hapus',
                                'button_color'     => 'primary'
                            ),
                        )
                    );
                    $button_edit = button_edit($config_button_edit);
                    $button_delete = button_delete($config_button_delete);

                    $data['t_body'][$index] = array(
                        ++$start,
                        $key['product_image'],
                        $key['product_name'],
                        $key['product_code'],
                        $key['qty'],
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
        $total_row = $this->M_stock->count_data();
        $data = array(
            array(
                'base_url'   => base_url('stock/index'),
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

        $data['title'] = "Form stock";
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
                'title'    => 'Form stock',
                'action'    =>  $id == '' ? 'stock/add_data' : 'stock/update_data',
                'button_save' => array(
                    'button_title'    => 'Save',
                    'button_color'     => 'success',
                    'button_action'      => '#',
                ),
                'button_cancel' => array(
                    'button_title'    => 'Cancel',
                    'button_color'     => 'danger',
                    'button_action'      => 'stock',
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


        $data_stock =  $this->M_stock->edit_data($where, 'tbl_stock')->result_array();
        if (is_array($data_stock) && isset($data_stock) && empty($data_stock)) {
            $query = $default_data;
        } else {
            $query = $data_stock;
        }
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];
        $get_product = $this->M_stock->get_product($id_dpartement);
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
                            'value'         =>  @$key['id'],
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
                            'value'         =>  @$key['product_name'],
                            'content_id'    => 'id',
                            'content'       => 'product_name',
                            'data'          => $get_product,
                            'input-type'    => 'select'
                        ),
                        array(
                            'form_title'    => 'Jumlah Produk', // Judul Form
                            'place_holder'  => 'Silahkan isi jumlah Produk', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => '',
                            'id'            => 'stock_product',
                            'name'          => 'stock_product',
                            'validation'    =>  'false',
                            'value'         => @$key['qty'],
                            'data'          => '',
                            'input-type'    => 'form'
                        ),
                    ),
                ),
            );
        }
        return $data;
    }

    public function add_data()
    {
        $product_name = $this->input->post('product_id');
        $qty = $this->input->post('stock_product');
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];


        $data = array(
            'product_id'   => $product_name,
            'qty'   => $qty,
            'id_dpartement' => $id_dpartement
        );


        $this->M_stock->input_data($data, 'tbl_stock');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Simpan ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('stock/index');
    }

    public function update_data()
    {
        $id = $this->input->post('id');
        $product_name = $this->input->post('product_id');
        $qty = $this->input->post('stock_product');
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];


        $data = array(
            'product_id'   => $product_name,
            'qty'   => $qty,
            'id_dpartement' => $id_dpartement
        );

        $where = array(
            'id'    => $id
        );

        $this->M_stock->update_data($where, $data, 'tbl_stock');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Edit ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('stock/index');
    }

    public function delete_data($id)
    {
        $where = array('id' => $id);
        $this->M_stock->delete_data($where, 'tbl_stock');
        $config_alert_danger = array(
            array(
                'title'     => 'Data Berhasil di Hapus ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_danger = allert($config_alert_danger);
        $this->session->set_flashdata('msg', $allert_danger);
        redirect('stock/index');
    }
}
