<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_auth');
    }

    public function index()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->load->view('theme/atlantis/login');
        } else {
            $this->cek_auth();
        }
    }

    public function cek_auth()
    {
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));


        $user = $this->M_auth->get_user($username, $password);

        if ($user[0] > 0) {
            $data = array(
                'data' => $user[0]
            );
            $this->session->set_userdata($data);
            $config_alert_success = array(
                array(
                    'title'     => 'Anda Berhasil Login ',
                    'alert_type' => 'alert-success'
                ),
            );
            $allert_success = allert($config_alert_success);
            $this->session->set_flashdata('msg', $allert_success);
            redirect(base_url('Dashboard'));
        } else {
            redirect('auth/index');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('data');
        redirect('auth/index');
    }
}
