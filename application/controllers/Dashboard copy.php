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
        $profile = $this->input->get('profile'); // Ambil parameter profile dari URL
        $config['base_url'] = site_url('dashboard/index' . ($profile ? '?profile=' . urlencode($profile) : ''));
        $config['total_rows'] = $this->Dashboard_model->get_total_sessions($profile);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['profiles'] = $this->Dashboard_model->get_profiles(); // Ambil daftar profil
        $data['selected_profile'] = $profile; // Simpan profil yang dipilih
        $data['active_users'] = $this->Dashboard_model->get_active_users_count($profile);
        $data['total_upload'] = $this->Dashboard_model->get_total_bandwidth('upload', $profile);
        $data['total_download'] = $this->Dashboard_model->get_total_bandwidth('download', $profile);
        $data['sessions'] = $this->Dashboard_model->get_active_sessions($config['per_page'], $page, $profile);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('dashboard/index', $data);
    }

    public function search_user() {
        $username = $this->input->post('username', TRUE);
        if (empty($username)) {
            $this->session->set_flashdata('error', 'Username tidak boleh kosong');
            redirect('dashboard');
        }
        $profile = $this->input->get('profile');
        $data['profiles'] = $this->Dashboard_model->get_profiles();
        $data['selected_profile'] = $profile;
        $data['user_status'] = $this->Dashboard_model->check_user_status($username);
        $data['active_users'] = $this->Dashboard_model->get_active_users_count($profile);
        $data['total_upload'] = $this->Dashboard_model->get_total_bandwidth('upload', $profile);
        $data['total_download'] = $this->Dashboard_model->get_total_bandwidth('download', $profile);
        $data['sessions'] = $this->Dashboard_model->get_active_sessions(10, 0, $profile);
        $data['pagination'] = '';

        // Tambahkan flashdata untuk status "Tidak Ditemukan"
        if ($data['user_status']['status'] == 'Tidak Ditemukan') {
            $this->session->set_flashdata('error', 'Pengguna ' . htmlspecialchars($username) . ' tidak ditemukan');
        }

        $this->load->view('dashboard/index', $data);
    }

    public function active_sessions() {
        $profile = $this->input->get('profile');
        $config['base_url'] = site_url('dashboard/active_sessions' . ($profile ? '?profile=' . urlencode($profile) : ''));
        $config['total_rows'] = $this->Dashboard_model->get_total_sessions($profile);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['profiles'] = $this->Dashboard_model->get_profiles();
        $data['selected_profile'] = $profile;
        $data['sessions'] = $this->Dashboard_model->get_active_sessions($config['per_page'], $page, $profile);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('dashboard/active_sessions', $data);
    }
}