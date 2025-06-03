<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Settings_model');
        $this->load->library('session');
    }

    public function index() {
        if ($this->session->userdata('admin_id')) {
            redirect('dashboard');
        }
        $this->load->view('auth/login');
    }

    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $admin = $this->Admin_model->check_login($username, $password);

        if ($admin) {
            $this->session->set_userdata('admin_id', $admin->id);
            $this->session->set_userdata('username', $admin->username);
            $this->Settings_model->log_activity($admin->id, $admin->username, 'Login', 'Admin berhasil login');
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth');
        }
    }

    public function logout() {
        $admin_id = $this->session->userdata('admin_id');
        $username = $this->session->userdata('username');
        $this->Settings_model->log_activity($admin_id, $username, 'Logout', 'Admin logout');
        $this->session->unset_userdata('admin_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('theme');
        redirect('auth');
    }

	public function register()
	{
		$this->load->view('auth/register');
	}

	public function store_register()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required|is_unique[admin.username]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('auth/register');
		} else {
			$username = $this->input->post('username');
			$password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

			$this->load->model('Admin_model');
			$this->Admin_model->insert_admin($username, $password);

			$this->session->set_flashdata('success', 'Admin berhasil didaftarkan.');
			redirect('auth');
		}
	}
}
