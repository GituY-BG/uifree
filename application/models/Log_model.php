<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model {

    public function log_activity($admin_id, $action, $details) {
        // Ambil username dari session (misalnya dari admin_id atau field lain)
        $username = $this->session->userdata('username') ?: 'Unknown';

        $data = [
            'admin_id' => $admin_id,
            'username' => $username,
            'action' => $action,
            'details' => $details
            // created_at akan diisi otomatis oleh database
        ];
        $this->db->insert('admin_logs', $data);
        if ($this->db->affected_rows() == 0) {
            log_message('error', 'Failed to insert log: ' . print_r($data, true) . ' - ' . $this->db->error()['message']);
        }
    }
}