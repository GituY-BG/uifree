<?php
class Settings_model extends CI_Model {
    public function get_users($search = '', $status = '') {
        if ($search) {
            $this->db->like('username', $search);
        }
        if ($status == 'active') {
            $this->db->where('username IN (SELECT username FROM radacct WHERE acctstoptime IS NULL)', NULL, FALSE);
        } elseif ($status == 'inactive') {
            $this->db->where('username NOT IN (SELECT username FROM radacct WHERE acctstoptime IS NULL)', NULL, FALSE);
        }
        $this->db->limit(10);
        return $this->db->get('radcheck')->result();
    }

    public function get_profiles($search = '') {
        $this->db->select('r1.groupname, r1.value as rate_limit, r2.value as simultan');
        $this->db->from('radgroupreply r1');
        $this->db->join('radgroupreply r2', 'r1.groupname = r2.groupname AND r2.attribute = "Simultaneous-Use"', 'left');
        $this->db->where('r1.attribute', 'Mikrotik-Rate-Limit');
        if ($search) {
            $this->db->like('r1.groupname', $search);
        }
        $this->db->group_by('r1.groupname');
        $this->db->limit(10);
        return $this->db->get()->result();
    }

    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        return $this->db->get('radcheck')->row();
    }

    public function get_user_profile($username) {
        $this->db->where('username', $username);
        $group = $this->db->get('radusergroup')->row();
        return $group ? $group->groupname : '';
    }

    public function get_profile_by_groupname($groupname) {
        $this->db->where('groupname', $groupname);
        $query = $this->db->get('radgroupreply');
        $result = $query->row();
        
        if (!$result) {
            log_message('error', 'Profil tidak ditemukan di radgroupreply untuk groupname: ' . $groupname);
        } else {
            log_message('debug', 'Profil ditemukan: ' . json_encode($result));
        }
        
        return $result;
    }

    public function get_profile_attribute($groupname, $attribute) {
        $this->db->where('groupname', $groupname);
        $this->db->where('attribute', $attribute);
        $row = $this->db->get('radgroupreply')->row();
        return $row ? $row->value : '';
    }

    public function update_user($username, $data, $group_data) {
        if (!empty($data)) {
            $this->db->where('username', $username);
            $this->db->update('radcheck', $data);
        }
        $this->db->where('username', $username);
        $this->db->update('radusergroup', $group_data);
    }

    public function update_profile($groupname, $data, $data_simultan) {
        $this->db->where('groupname', $groupname);
        $this->db->where('attribute', 'Mikrotik-Rate-Limit');
        $existing = $this->db->get('radgroupreply')->row();
        if ($existing) {
            $this->db->where('groupname', $groupname);
            $this->db->where('attribute', 'Mikrotik-Rate-Limit');
            $this->db->update('radgroupreply', $data);
        } else {
            $data['groupname'] = $groupname;
            $data['attribute'] = 'Mikrotik-Rate-Limit';
            $data['op'] = ':=';
            $this->db->insert('radgroupreply', $data);
        }

        $this->db->where('groupname', $groupname);
        $this->db->where('attribute', 'Simultaneous-Use');
        $existing_simultan = $this->db->get('radgroupreply')->row();
        if ($existing_simultan) {
            $this->db->where('groupname', $groupname);
            $this->db->where('attribute', 'Simultaneous-Use');
            $this->db->update('radgroupreply', $data_simultan);
        } else {
            $data_simultan['groupname'] = $groupname;
            $data_simultan['attribute'] = 'Simultaneous-Use';
            $data_simultan['op'] = ':=';
            $this->db->insert('radgroupreply', $data_simultan);
        }
    }

    public function delete_user($username) {
        $this->db->trans_start();
        
        $this->db->where('username', $username);
        $this->db->delete('radcheck');
        $affected_radcheck = $this->db->affected_rows();
        log_message('debug', 'Menghapus pengguna ' . $username . ' dari radcheck: ' . $affected_radcheck . ' baris terhapus');

        $this->db->where('username', $username);
        $this->db->delete('radusergroup');
        $affected_radusergroup = $this->db->affected_rows();
        log_message('debug', 'Menghapus pengguna ' . $username . ' dari radusergroup: ' . $affected_radusergroup . ' baris terhapus');

        $this->db->where('username', $username);
        $this->db->delete('radacct');
        $affected_radacct = $this->db->affected_rows();
        log_message('debug', 'Menghapus sesi pengguna ' . $username . ' dari radacct: ' . $affected_radacct . ' baris terhapus');

        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Gagal menghapus pengguna ' . $username . ': Transaksi gagal');
            return FALSE;
        } else {
            log_message('info', 'Berhasil menghapus pengguna ' . $username . ' dari semua tabel terkait');
            return TRUE;
        }
    }

    public function delete_profile($groupname) {
        $this->db->trans_start();
        
        $this->db->where('groupname', $groupname);
        $users = $this->db->get('radusergroup')->result();
        log_message('debug', 'Menemukan ' . count($users) . ' pengguna untuk profil ' . $groupname);

        foreach ($users as $user) {
            if (!$this->delete_user($user->username)) {
                $this->db->trans_rollback();
                log_message('error', 'Gagal menghapus pengguna ' . $user->username . ' untuk profil ' . $groupname);
                return FALSE;
            }
        }

        $this->db->where('groupname', $groupname);
        $this->db->delete('radgroupreply');
        $affected_radgroupreply = $this->db->affected_rows();
        log_message('debug', 'Menghapus profil ' . $groupname . ' dari radgroupreply: ' . $affected_radgroupreply . ' baris terhapus');

        $this->db->where('groupname', $groupname);
        $this->db->delete('radgroupcheck');
        $affected_radgroupcheck = $this->db->affected_rows();
        log_message('debug', 'Menghapus profil ' . $groupname . ' dari radgroupcheck: ' . $affected_radgroupcheck . ' baris terhapus');

        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Gagal menghapus profil ' . $groupname . ': Transaksi gagal');
            return FALSE;
        } else {
            log_message('info', 'Berhasil menghapus profil ' . $groupname . ' dan semua data terkait');
            return TRUE;
        }
    }

    public function log_activity($admin_id, $username, $action, $details) {
        $data = array(
            'admin_id' => $admin_id,
            'username' => $username,
            'action' => $action,
            'details' => $details
        );
        $this->db->insert('admin_logs', $data);
        if ($this->db->affected_rows() == 0) {
            log_message('error', 'Failed to insert log: ' . print_r($data, true) . ' - ' . $this->db->error()['message']);
        }
    }

    public function get_logs() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('admin_logs')->result();
    }
}