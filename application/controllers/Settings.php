<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Settings_model');
        if (!$this->session->userdata('admin_id')) {
            redirect('auth');
        }
    }

    public function index() {
        $search_user = $this->input->get('search_user');
        $search_profile = $this->input->get('search_profile');
        $status = $this->input->get('status');

        $data['users'] = $this->Settings_model->get_users($search_user, $status);
        $data['profiles'] = $this->Settings_model->get_profiles($search_profile);
        $this->load->view('settings/index', $data);
    }

    public function edit_user($username) {
        $data['user'] = $this->Settings_model->get_user_by_username($username);
        $data['profiles'] = $this->Settings_model->get_profiles();
        $data['current_profile'] = $this->Settings_model->get_user_profile($username);
        $this->load->view('settings/edit_user', $data);
    }

    public function update_user() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $profile = $this->input->post('profile');

        $data = array();
        if (!empty($password)) {
            $data['value'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $group_data = array(
            'groupname' => $profile
        );
        $this->Settings_model->update_user($username, $data, $group_data);
        $this->Settings_model->log_activity($this->session->userdata('admin_id'), $this->session->userdata('username'), 'Update User', "Mengubah data user: $username");
        $this->session->set_flashdata('success', 'Data user berhasil diperbarui.');
        redirect('settings');
    }

    public function edit_profile($groupname) {
        // Dekode groupname untuk menangani spasi atau karakter khusus
        $groupname = urldecode($groupname);
        $data['profile'] = $this->Settings_model->get_profile_by_groupname($groupname);
        if (!$data['profile']) {
            log_message('error', 'Profil tidak ditemukan untuk groupname: ' . $groupname);
            $this->session->set_flashdata('error', 'Profil "' . htmlspecialchars($groupname) . '" tidak ditemukan.');
            redirect('settings');
        }
        $data['rate_limit'] = $this->Settings_model->get_profile_attribute($groupname, 'Mikrotik-Rate-Limit');
        $data['simultan'] = $this->Settings_model->get_profile_attribute($groupname, 'Simultaneous-Use');
        $this->load->view('settings/edit_profile', $data);
    }

    public function update_profile() {
        $groupname = $this->input->post('groupname');
        $rate_limit = $this->input->post('rate_limit');
        if (!preg_match('/^\d+[kM]\/\d+[kM]$/', $rate_limit)) {
            $this->session->set_flashdata('error', 'Format rate limit tidak valid.');
            redirect('settings/edit_profile/' . urlencode($groupname));
        }
        $data = array(
            'value' => $rate_limit
        );
        $data_simultan = array(
            'value' => $this->input->post('simultan')
        );
        $this->Settings_model->update_profile($groupname, $data, $data_simultan);
        $this->Settings_model->log_activity($this->session->userdata('admin_id'), $this->session->userdata('username'), 'Update Profile', "Mengubah data profile: $groupname");
        $this->session->set_flashdata('success', 'Data profil berhasil diperbarui.');
        redirect('settings');
    }

    public function switch_theme() {
        $theme = $this->input->post('theme');
        $this->session->set_userdata('theme', $theme);
        $this->Settings_model->log_activity($this->session->userdata('admin_id'), $this->session->userdata('username'), 'Switch Theme', "Mengganti tema ke: $theme");
        redirect($this->input->server('HTTP_REFERER'));
    }

    public function delete_user($username) {
        $this->Settings_model->delete_user($username);
        $this->Settings_model->log_activity($this->session->userdata('admin_id'), $this->session->userdata('username'), 'Delete User', "Menghapus user: $username");
        $this->session->set_flashdata('success', 'User berhasil dihapus.');
        redirect('settings');
    }

    public function delete_profile($groupname) {
        $this->Settings_model->delete_profile($groupname);
        $this->Settings_model->log_activity($this->session->userdata('admin_id'), $this->session->userdata('username'), 'Delete Profile', "Menghapus profile: $groupname dan semua user terkait");
        $this->session->set_flashdata('success', 'Profil dan semua user terkait berhasil dihapus.');
        redirect('settings');
    }

    public function logs() {
        $data['logs'] = $this->Settings_model->get_logs();
        $this->load->view('settings/logs', $data);
    }
}
?>