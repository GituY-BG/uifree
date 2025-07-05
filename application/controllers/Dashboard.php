<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->library('pagination');
        if (!$this->session->userdata('admin_id')) {
            redirect('auth');
        }
    }

    public function index() {
        $profile = $this->input->get('profile');
        $username = $this->input->post('username', TRUE);
        $config['base_url'] = site_url('dashboard/index' . ($profile ? '?profile=' . urlencode($profile) : ''));
        $config['total_rows'] = $this->Dashboard_model->get_total_sessions($profile);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['profiles'] = $this->Dashboard_model->get_profiles();
        $data['selected_profile'] = $profile;
        $data['active_users'] = $this->Dashboard_model->get_active_users_count($profile);
        $data['total_users'] = $this->Dashboard_model->get_total_users($profile);
        $data['total_profiles'] = $this->Dashboard_model->get_total_profiles();
        $data['sessions'] = $this->Dashboard_model->get_top_users($config['per_page'], $page);
        $data['auth_history'] = $this->Dashboard_model->get_auth_history($username, $config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();

        if (!empty($username)) {
            $data['user_status'] = $this->Dashboard_model->check_user_status($username);
            if ($data['user_status']['status'] == 'Akun Tidak Ditemukan') {
                $this->session->set_flashdata('error', 'Akun ' . htmlspecialchars($username) . ' tidak ditemukan');
            }
        }

        $this->load->view('dashboard/index', $data);
    }

    public function active_sessions() {
        $profile = $this->input->get('profile');
        $username = $this->input->post('username', TRUE);
        $start_date = $this->input->get('start_date', TRUE);
        $end_date = $this->input->get('end_date', TRUE);

        $config['base_url'] = site_url('dashboard/active_sessions' . ($profile ? '?profile=' . urlencode($profile) : '') . ($start_date ? '&start_date=' . urlencode($start_date) : '') . ($end_date ? '&end_date=' . urlencode($end_date) : ''));
        $config['total_rows'] = $this->Dashboard_model->get_total_sessions($profile);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['profiles'] = $this->Dashboard_model->get_profiles();
        $data['selected_profile'] = $profile;
        if ($profile) {
            $data['profile_summary'] = $this->Dashboard_model->get_profile_summary($profile);
            $data['total_bandwidth'] = $this->Dashboard_model->get_total_bandwidth_per_profile($profile, $start_date, $end_date);
        }
        $data['sessions'] = $this->Dashboard_model->get_top_users($config['per_page'], $page, $start_date, $end_date, $profile);
        $data['pagination'] = $this->pagination->create_links();
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        if (!empty($username)) {
            $data['user_status'] = $this->Dashboard_model->check_user_status($username);
            if ($data['user_status']['status'] == 'Akun Tidak Ditemukan') {
                $this->session->set_flashdata('error', 'Akun ' . htmlspecialchars($username) . ' tidak ditemukan');
            }
        }

        $this->load->view('dashboard/active_sessions', $data);
    }
}