<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('M_dashboard');
        $this->load->model('M_auth');
    }
    public function index($get_month = '')
    {


        if ($get_month == '' || $get_month == null) {
            $month = date('Y-m');
        } else if ($get_month > 0) {
            $month = $get_month;
        }

        $data['count'] =  $this->M_dashboard->get_transaction();
        $data['sales'] =  $this->M_dashboard->get_most_sales($month);


        $data['sidebar'] = config_sidebar();
        $data['title'] = 'Dashboard';

        $this->load->view('theme/metronic/header');
        $this->load->view('theme/metronic/sidebar', $data);
        $this->load->view('theme/metronic/topbar');
        $this->load->view('theme/metronic/dashboard', $data);
        $this->load->view('theme/metronic/footer');
    }

    public function get_most_product()
    {
        $data = $this->M_dashboard->get_transaction();
        return $data;
    }

    public function form($id = '')
    {
        $config_form = $this->config_form($id);
        $config_card = $this->card_form($id);
        $form = form($config_form);
        $data['title'] = "Seting Dashboard";
        $data['card'] = card($config_card, $form);
        $data['sidebar'] = config_sidebar();



        $this->load->view('theme/metronic/header');
        $this->load->view('theme/metronic/sidebar', $data);
        $this->load->view('theme/metronic/topbar');
        $this->load->view('theme/metronic/content', $data);
        $this->load->view('theme/metronic/footer');
    }

    public function card_form()
    {
        $data = array(
            array(
                'title'    => 'Setting Dashboard',
                'action'    =>  /*$id == '' ? 'Dashboard/add_data' :*/ 'Dashboard/update_data',
                'button_save' => array(
                    'button_title'    => 'Save',
                    'button_color'     => 'success',
                    'button_action'      => '#',
                ),
                'button_cancel' => array(
                    'button_title'    => 'Cancel',
                    'button_color'     => 'danger',
                    'button_action'      => 'Dashboard',
                ),
            )
        );
        return $data;
    }

    public function config_form()
    {
        $data = array(
            array(
                'column'    => 'col-lg-6',
                'form' => array(
                    array(
                        'form_title'   => 'Statistik Sales', // Judul Form
                        'place_holder'  => '', // Isi PlaceHolder
                        'note'          => 'Untuk Menyeting Data Statistik Sales', // Note form
                        'type'          => 'month',
                        'id'            => 'statistik_sales',
                        'name'          => 'statistik_sales',
                        'validation'    =>  'false',
                        'value'         => '',
                        'input-type'     => 'form'
                    ),
                ),
            ),
        );
        return $data;
    }

    public function update_data()
    {
        $data_sales = $this->input->post('statistik_sales');

        if (isset($data_sales)) {
            $data = $this->index($data_sales);
            return $data;
        }
    }
}
