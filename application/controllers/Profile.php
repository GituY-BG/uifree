<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Profile_model');
        if (!$this->session->userdata('admin_id')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['profiles'] = $this->Profile_model->get_profiles();
        $this->load->view('profile/index', $data);
    }

    public function add()
    {
        if ($this->input->post()) {
            $data = array(
                'groupname' => $this->input->post('groupname'),
                'attribute' => 'Mikrotik-Rate-Limit',
                'op' => ':=',
                'value' => $this->input->post('rate_limit')
            );
            $data_simultan = array(
                'groupname' => $this->input->post('groupname'),
                'attribute' => 'Simultaneous-Use',
                'op' => ':=',
                'value' => $this->input->post('simultan')
            );
            $this->Profile_model->add_profile($data, $data_simultan);
            redirect('profile');
        }
        $this->load->view('profile/add');
    }
}
