<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('pagination');
        if (!$this->session->userdata('admin_id')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $search = $this->input->get('search') ? $this->input->get('search') : '';
        $limit = 20;
        $offset = $this->uri->segment(3, 0);

        // Konfigurasi pagination
        $config['base_url'] = site_url('user/index');
        $config['total_rows'] = $this->User_model->get_total_users($search);
        $config['per_page'] = $limit;
        $config['uri_segment'] = 3;
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['next_link'] = '»';
        $config['prev_link'] = '«';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];
        $this->pagination->initialize($config);

        $data['users'] = $this->User_model->get_users($search, $limit, $offset);
        $data['profiles'] = $this->User_model->get_profiles();
        $data['search'] = $search;
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('user/index', $data);
    }

    public function add()
    {
        if ($this->input->post()) {
            $data = array(
                'username' => $this->input->post('username'),
                'value' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'status' => 'active'
            );
            $group_data = array(
                'username' => $this->input->post('username'),
                'groupname' => $this->input->post('profile'),
                'priority' => 1
            );
            $this->User_model->add_user($data, $group_data);
            redirect('user');
        }
        $data['profiles'] = $this->User_model->get_profiles();
        $this->load->view('user/add', $data);
    }

    public function batch_add()
    {
        if ($this->input->post()) {
            $profile = $this->input->post('profile');
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, 'r');
            fgetcsv($handle); // Skip header
            while (($row = fgetcsv($handle)) !== FALSE) {
                $data = array(
                    'username' => $row[0],
                    'value' => password_hash($row[1], PASSWORD_DEFAULT),
                    'attribute' => 'Cleartext-Password',
                    'op' => ':=',
                    'status' => 'active'
                );
                $group_data = array(
                    'username' => $row[0],
                    'groupname' => $profile,
                    'priority' => 1
                );
                $this->User_model->add_user($data, $group_data);
            }
            fclose($handle);
            redirect('user');
        }
        $data['profiles'] = $this->User_model->get_profiles();
        $this->load->view('user/batch_add', $data);
    }

//     public function toggle_status($username, $status)
//     {
//         $new_status = ($status == 'active') ? 'inactive' : 'active';
//         if ($new_status == 'inactive') {
            
//             // Disconnect user menggunakan radclient
//             $radius_server = '127.0.0.1'; // Ganti dengan IP server FreeRADIUS
//             $radius_secret = 'your_secret'; // Ganti dengan secret FreeRADIUS
//             $command = "echo 'User-Name=" . escapeshellarg($username) . "' | radclient -x $radius_server disconnect $radius_secret 2>&1";
//             exec($command, $output, $return_var);
//             if ($return_var !== 0) {
//                 $this->session->set_flashdata('error', 'Gagal disconnect user: ' . implode(', ', $output));
//             }
//         }
//         $this->User_model->update_status($username, $new_status);
//         redirect('user');
//     }
// }
public function toggle_status($username, $status)
{
    $new_status = ($status == 'active') ? 'inactive' : 'active';

    if ($new_status == 'inactive') {
        // Ambil NAS IP dari radacct untuk user tersebut
        $this->load->database();
        $query = $this->db->select('nasipaddress')
                          ->where('username', $username)
                          ->where('acctstoptime', null) // hanya yang sedang aktif
                          ->order_by('acctstarttime', 'DESC')
                          ->limit(1)
                          ->get('radacct');

        if ($query->num_rows() > 0) {
            $nas_ip = $query->row()->nasipaddress;

            $radius_secret = 'your_secret'; // Ganti dengan secret FreeRADIUS
            $command = "echo 'User-Name=" . escapeshellarg($username) . "' | radclient -x $nas_ip disconnect $radius_secret 2>&1";
            exec($command, $output, $return_var);

            if ($return_var !== 0) {
                $this->session->set_flashdata('error', 'Gagal disconnect user: ' . implode(', ', $output));
            }
        } else {
            $this->session->set_flashdata('error', 'Tidak ditemukan user aktif di radacct.');
        }
    }

    $this->User_model->update_status($username, $new_status);
    redirect('user');
}
}