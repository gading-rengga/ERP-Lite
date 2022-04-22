<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dpartement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('M_dpartement');
        $this->load->model('M_auth');
    }

    public function default_data()
    {
        $data = array(
            'id' =>  '',
            'nama_dpartement' =>  '',

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

        $data['card'] = card($config_card, $content);
        $data['sidebar'] = config_sidebar();
        $data['title'] = 'Data Dpartement';
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
                'title'    => 'Data Dpartement',
                'action'    => 'Dpartement',
                // Optional Button
                'button' => array(
                    'button_link'      => 'Dpartement/form',
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
        $data_search = $this->M_dpartement->get_keyword($keyword, $perpage, $start)->result_array();
        $data_dpartement = $this->M_dpartement->get_data($perpage, $start)->result_array();
        $config_pagination = $this->config_pagination();
        $config = pagination($config_pagination);
        $this->pagination->initialize($config);

        $data['t_head'] = array(
            array(
                'NO',
                'Nama Dpartement',
                'Edit',
                'Hapus'
            )
        );
        if ($data_search == '') {
            $data_table = $data_dpartement;
        } elseif ($data_search !== '') {
            $data_table = $data_search;
        }

        if (isset($data)) {
            foreach ($data_table as $index => $key) {
                $config_button_edit = array(
                    array(
                        'button' => array(
                            'button_link'      => 'Dpartement/form/' . $key['id'],
                            'button_title'    => 'Edit',
                            'button_color'     => 'primary'
                        ),
                    )
                );
                $config_button_delete = array(
                    array(
                        'button' => array(
                            'button_link'      => 'Dpartement/delete_data/' . $key['id'],
                            'button_title'    => 'Hapus',
                            'button_color'     => 'primary'
                        ),
                    )
                );
                $button_edit = button_edit($config_button_edit);
                $button_delete = button_delete($config_button_delete);

                $data['t_body'][$index] = array(
                    ++$start,
                    $key['nama_dpartement'],
                    $button_edit,
                    $button_delete,
                );
            }
        }
        return $data;
    }

    public function config_pagination()
    {
        $total_row = $this->M_dpartement->count_data();
        $data = array(
            array(
                'base_url'   => base_url('Dpartement/index'),
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

        $data['title'] = "Form Dpartement";
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
                'title'    => 'Form Dpartement',
                'action'    =>  $id == '' ? 'Dpartement/add_data' : 'Dpartement/update_data',
                'button_save' => array(
                    'button_title'    => 'Save',
                    'button_color'     => 'success',
                    'button_action'      => '#',
                ),
                'button_cancel' => array(
                    'button_title'    => 'Cancel',
                    'button_color'     => 'danger',
                    'button_action'      => 'Dpartement',
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

        $data =  $this->M_dpartement->edit_data($where, 'tbl_dpartement')->result_array();

        if (is_array($data) && isset($data) && empty($data)) {
            $query = $default_data;
        } else {
            $query = $data;
        }

        foreach ($query as  $key) {
            $data = array(
                array(
                    'column'    => 'col-lg-12',
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
                            'form_title'   => 'Nama Dpartement', // Judul Form
                            'place_holder'  => 'Silahkan isi Nama Dpartement', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => '',
                            'id'            => 'dpartement',
                            'name'          => 'dpartement',
                            'validation'    =>  'false',
                            'value'         => @$key['nama_dpartement'],
                            'data'          => '',
                            'input-type'     => 'form'
                        ),
                    ),
                ),
            );
        }
        return $data;
    }

    public function add_data()
    {
        $id = $this->input->post('id_data');
        $name = $this->input->post('dpartement');

        $data = array(
            'id'      => $id,
            'nama_dpartement'  => $name
        );


        $this->M_dpartement->input_data($data, 'tbl_dpartement');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Simpan ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('Dpartement/index');
    }

    public function update_data()
    {
        $id = $this->input->post('id_data');
        $name = $this->input->post('dpartement');

        $data = array(
            'id'      => $id,
            'nama_dpartement'  => $name
        );

        $where = array(
            'id'    => $id
        );
        $this->M_dpartement->update_data($where, $data, 'tbl_dpartement');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Edit ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('Dpartement/index');
    }

    public function delete_data($id)
    {
        $where = array('id' => $id);
        $this->M_dpartement->delete_data($where, 'tbl_dpartement');
        $config_alert_danger = array(
            array(
                'title'     => 'Data Berhasil di Hapus ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_danger = allert($config_alert_danger);
        $this->session->set_flashdata('msg', $allert_danger);
        redirect('Dpartement/index');
    }
}
