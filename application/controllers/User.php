<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('M_user');
        $this->load->model('M_auth');
    }

    public function default_data()
    {
        $data = array(
            'id' =>  '',
            'employe_id' =>  '',

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
        $data['title'] = 'Data User';
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
                'title'    => 'Data User',
                'action'    => 'User',
                // Optional Button
                'button' => array(
                    'button_link'      => 'User/form',
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
        $data_search = $this->M_user->get_keyword($keyword, $perpage, $start)->result_array();
        $data_User = $this->M_user->get_user($perpage, $start)->result_array();
        $config_pagination = $this->config_pagination();
        $config = pagination($config_pagination);
        $this->pagination->initialize($config);

        $data['t_head'] = array(
            array(
                'NO',
                'employe_name',
                'Username',
                'Password',
                'Edit',
                'Hapus'
            )
        );
        if ($data_search == '') {
            $data_table = $data_User;
        } elseif ($data_search !== '') {
            $data_table = $data_search;
        }

        if (isset($data)) {
            foreach ($data_table as $index => $key) {
                $get_employe_name = $this->M_user->get_name($key['employe_id']);
                foreach ($get_employe_name as $val) {
                    $employe_name = $val['employe_name'];
                }
                $config_button_edit = array(
                    array(
                        'button' => array(
                            'button_link'      => 'User/form/' . $key['id'],
                            'button_title'    => 'Edit',
                            'button_color'     => 'primary'
                        ),
                    )
                );
                $config_button_delete = array(
                    array(
                        'button' => array(
                            'button_link'      => 'User/delete_data/' . $key['id'],
                            'button_title'    => 'Hapus',
                            'button_color'     => 'primary'
                        ),
                    )
                );
                $button_edit = button_edit($config_button_edit);
                $button_delete = button_delete($config_button_delete);

                $data['t_body'][$index] = array(
                    ++$start,
                    $employe_name,
                    $key['username'],
                    $key['password'],
                    $button_edit,
                    $button_delete,
                );
            }
        }
        return $data;
    }

    public function config_pagination()
    {
        $total_row = $this->M_user->count_user();
        $data = array(
            array(
                'base_url'   => base_url('User/index'),
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



        $data['title'] = "Form User";
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
                'title'    => 'Form User',
                'action'    =>  $id == '' ? 'User/add_data' : 'User/update_data',
                'button_save' => array(
                    'button_title'    => 'Save',
                    'button_color'     => 'success',
                    'button_action'      => '#',
                ),
                'button_cancel' => array(
                    'button_title'    => 'Cancel',
                    'button_color'     => 'danger',
                    'button_action'      => 'User',
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

        $data_employe =  $this->M_user->edit_data($where, 'tbl_user')->result_array();

        if (is_array($data_employe) && isset($data_employe) && empty($data_employe)) {
            $query = $default_data;
        } else {
            $query = $data_employe;
        }

        $get_employe = $this->M_user->get_employe();
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
                            'form_title'   => 'Pilih Karyawan',
                            'place_holder'  => '',
                            'note'          => '',
                            'type'          => 'select',
                            'id'            => 'employe_id',
                            'name'          => 'employe_id',
                            'validation'    =>  'false',
                            'value'         =>  @$key['employe_name'],
                            'content_id'    => 'id',
                            'content'       => 'employe_name',
                            'data'          => $get_employe,
                            'input-type'    => 'select'
                        ),
                        array(
                            'form_title'   => 'Username', // Judul Form
                            'place_holder'  => 'Silahkan isi Username', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => '',
                            'id'            => 'username',
                            'name'          => 'username',
                            'validation'    =>  'false',
                            'value'         => @$key['username'],
                            'data'          => '',
                            'input-type'     => 'form'
                        ),
                        array(
                            'form_title'   => 'Password', // Judul Form
                            'place_holder'  => 'Silahkan isi Email', // Isi PlaceHolder
                            'note'          => '', // Note form
                            'type'          => 'text',
                            'id'            => 'password',
                            'name'          => 'password',
                            'validation'    =>  'false',
                            'value'         => @$key['password'],
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
        $employ_id = $this->input->post('employe_id');
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));

        $data = array(
            'employe_id'      => $employ_id,
            'username'  => $username,
            'password'  => $password
        );


        $this->M_user->input_employe($data, 'tbl_user');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Simpan ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('User/index');
    }

    public function update_data()
    {
        $id = $this->input->post('id_data');
        $employ_id = $this->input->post('employe_id');
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));

        $data = array(
            'employe_id'      => $employ_id,
            'username'  => $username,
            'password'  => $password
        );

        $where = array(
            'id'    => $id
        );
        $this->M_user->update_data($where, $data, 'tbl_user');
        $config_alert_success = array(
            array(
                'title'     => 'Data Berhasil di Edit ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_success = allert($config_alert_success);
        $this->session->set_flashdata('msg', $allert_success);
        redirect('User/index');
    }

    public function delete_data($id)
    {
        $where = array('id' => $id);
        $this->M_user->delete_user($where, 'tbl_user');
        $config_alert_danger = array(
            array(
                'title'     => 'Data Berhasil di Hapus ',
                'alert_type' => 'alert-success'
            ),
        );
        $allert_danger = allert($config_alert_danger);
        $this->session->set_flashdata('msg', $allert_danger);
        redirect('User/index');
    }



    public function config_table_form()
    {
    }
}
