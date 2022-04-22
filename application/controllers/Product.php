<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('M_product');
        $this->load->model('M_auth');
    }

    public function default_data()
    {
        $data = array(
            'id' =>  '',
            'product_name' =>  '',
            'product_code' =>  '',
            'price'        =>  '',
            'qty_pack'        =>  '',
            'product_image'  =>  '',
            'product_detail'  =>  '',
            'id_dpartement'  =>  '',
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
        $data['title'] = 'Data Product';
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
                'title'    => 'Data Product',
                'action'    => 'Product',
                // Optional Button
                'button' => array(
                    'button_link'      => 'Product/form',
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
        $data_search = $this->M_product->get_keyword($keyword, $perpage, $start, $id_dpartement)->result_array();
        $data_product = $this->M_product->get_data($perpage, $start, $id_dpartement)->result_array();
        $config_pagination = $this->config_pagination();
        $config = pagination($config_pagination);
        $this->pagination->initialize($config);

        $data['t_head'] = array(
            array(
                'NO',
                'Image',
                'Nama',
                'Kode',
                'isi',
                'Price',
                'Deskripsi',
                'Edit',
                'Hapus'
            )
        );
        if ($data_search == '') {
            $data_table = $data_product;
        } elseif ($data_search !== '') {
            $data_table = $data_search;
        }

        if (isset($data)) {
            foreach ($data_table as $index => $key) {
                if ($key['id_dpartement'] == $id_dpartement) {
                    $config_button_edit = array(
                        array(
                            'button' => array(
                                'button_link'      => 'Product/form/' . $key['id'],
                                'button_title'    => 'Edit',
                                'button_color'     => 'primary'
                            ),
                        )
                    );
                    $config_button_delete = array(
                        array(
                            'button' => array(
                                'button_link'      => 'Product/delete_data/' . $key['id'],
                                'button_title'    => 'Hapus',
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
                        $key['price'],
                        $key['product_detail'],
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
        $total_row = $this->M_product->count_data();
        $data = array(
            array(
                'base_url'   => base_url('Product/index'),
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

        $data['title'] = "Form Product";
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
                'title'    => 'Form Product',
                'action'    =>  $id == '' ? 'Product/add_data' : 'Product/update_data',
                'button_save' => array(
                    'button_title'    => 'Save',
                    'button_color'     => 'success',
                    'button_action'      => '#',
                ),
                'button_cancel' => array(
                    'button_title'    => 'Cancel',
                    'button_color'     => 'danger',
                    'button_action'      => 'Product',
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


        $data_product =  $this->M_product->edit_data($where, 'tbl_product')->result_array();

        if (is_array($data_product) && isset($data_product) && empty($data_product)) {
            $query = $default_data;
        } else {
            $query = $data_product;
        }
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
                            'form_title'    => 'Nama Produk', // Judul Form
                            'place_holder'  => 'Silahkan isi Nama Produk', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => '',
                            'id'            => 'product_name',
                            'name'          => 'product_name',
                            'validation'    =>  'false',
                            'value'         => @$key['product_name'],
                            'data'          => '',
                            'input-type'    => 'form'
                        ),
                        array(
                            'form_title'   => 'Kode Produk', // Judul Form
                            'place_holder'  => 'Silahkan isi Kode Produk', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => '',
                            'id'            => 'product_code',
                            'name'          => 'product_code',
                            'validation'    =>  'false',
                            'value'         => @$key['product_code'],
                            'data'          => '',
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'Jumlah isi', // Judul Form
                            'place_holder'  => 'Silahkan isi Jumlah', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => '',
                            'id'            => 'qty',
                            'name'          => 'qty',
                            'validation'    =>  'false',
                            'value'         => @$key['qty'],
                            'data'          => '',
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'Harga Produk', // Judul Form
                            'place_holder'  => 'Silahkan isi Harga Produk', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => '',
                            'id'            => 'price',
                            'validation'    =>  'false',
                            'name'          => 'price',
                            'value'         => @$key['price'],
                            'data'          => '',
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'Deskripsi Produk', // Judul Form
                            'place_holder'  => 'Silahkan isi Deskripis Produk', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => '',
                            'id'            => 'product_detail',
                            'validation'    =>  'false',
                            'name'          => 'product_detail',
                            'value'         => @$key['product_detail'],
                            'data'          => '',
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
        $product_name = $this->input->post('product_name');
        $product_code = $this->input->post('product_code');
        $price = $this->input->post('price');
        $qty = $this->input->post('qty');
        $product_detail = $this->input->post('product_detail');
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];
        $data_user = $this->session->userdata(['data'][0]);
        $id_user = $data_user['id'];
        $data = array(
            'product_name'   => $product_name,
            'product_code'   => $product_code,
            'price'          => $price,
            'qty'          => $qty,
            'product_detail' => $product_detail,
            'id_user'   => $id_user,
            'id_dpartement'   => $id_dpartement,
        );


        $this->M_product->input_data($data, 'tbl_product');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Simpan ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('Product/index');
    }

    public function update_data()
    {
        $id = $this->input->post('id');
        $product_name = $this->input->post('product_name');
        $product_code = $this->input->post('product_code');
        $price = $this->input->post('price');
        $qty = $this->input->post('qty');
        $product_detail = $this->input->post('product_detail');
        $data_user = $this->session->userdata(['data'][0]);
        $id_dpartement = $data_user['id_dpartement'];

        $data = array(
            'product_name'   => $product_name,
            'product_code'   => $product_code,
            'price'          => $price,
            'qty'          => $qty,
            'product_detail' => $product_detail,
            'id_dpartement' => $id_dpartement,
        );

        $where = array(
            'id'    => $id
        );

        $this->M_product->update_data($where, $data, 'tbl_product');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Edit ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('Product/index');
    }

    public function delete_data($id)
    {
        $where = array('id' => $id);
        $this->M_product->delete_data($where, 'tbl_product');
        $config_alert_danger = array(
            array(
                'title'     => 'Data Berhasil di Hapus ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_danger = allert($config_alert_danger);
        $this->session->set_flashdata('msg', $allert_danger);
        redirect('Product/index');
    }
}
