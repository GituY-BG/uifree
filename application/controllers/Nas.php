<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Nas_model');
        $this->load->model('Settings_model'); // Menggunakan Settings_model untuk konsistensi
        if (!$this->session->userdata('admin_id')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $search_nas = $this->input->get('search_nas');
        $data['nas_list'] = $this->Nas_model->get_nas($search_nas);
        $this->load->view('nas/index', $data);
    }

    public function add()
    {
        if ($this->input->post()) {
            $data = array(
                'nasname' => $this->input->post('nasname'),
                'shortname' => $this->input->post('shortname'),
                'type' => $this->input->post('type'),
                'ports' => $this->input->post('ports'),
                'secret' => $this->input->post('secret'),
                'server' => $this->input->post('server'),
                'community' => $this->input->post('community'),
                'description' => $this->input->post('description')
            );
            $this->Nas_model->add_nas($data);
            // Catat log aktivitas
            $this->Settings_model->log_activity(
                $this->session->userdata('admin_id'),
                $this->session->userdata('username') ?: 'Unknown',
                'Add NAS',
                'Added NAS: ' . $data['nasname']
            );
            $this->session->set_flashdata('success', 'NAS berhasil ditambahkan.');
            redirect('nas');
        }
        $this->load->view('nas/add');
    }

    public function edit($id)
    {
        $data['nas'] = $this->Nas_model->get_nas_by_id($id);
        if (!$data['nas']) {
            $this->session->set_flashdata('error', 'NAS tidak ditemukan.');
            redirect('nas');
        }
        $this->load->view('nas/edit', $data);
    }

    public function update()
    {
        $id = $this->input->post('id');
        $data = array(
            'nasname' => $this->input->post('nasname'),
            'shortname' => $this->input->post('shortname'),
            'type' => $this->input->post('type'),
            'ports' => $this->input->post('ports'),
            'secret' => $this->input->post('secret'),
            'server' => $this->input->post('server'),
            'community' => $this->input->post('community'),
            'description' => $this->input->post('description')
        );
        $this->Nas_model->update_nas($id, $data);
        // Catat log aktivitas
        $this->Settings_model->log_activity(
            $this->session->userdata('admin_id'),
            $this->session->userdata('username') ?: 'Unknown',
            'Update NAS',
            'Updated NAS: ' . $data['nasname']
        );
        $this->session->set_flashdata('success', 'NAS berhasil diperbarui.');
        redirect('nas');
    }

    public function delete($id)
    {
        $nas = $this->Nas_model->get_nas_by_id($id);
        if (!$nas) {
            $this->session->set_flashdata('error', 'NAS tidak ditemukan.');
            redirect('nas');
        }
        $this->Nas_model->delete_nas($id);
        // Catat log aktivitas
        $this->Settings_model->log_activity(
            $this->session->userdata('admin_id'),
            $this->session->userdata('username') ?: 'Unknown',
            'Delete NAS',
            'Deleted NAS: ' . $nas->nasname
        );
        $this->session->set_flashdata('success', 'NAS berhasil dihapus.');
        redirect('nas');
    }
}