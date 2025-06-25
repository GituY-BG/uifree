<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
    public function get_profiles() {
        $this->db->select('groupname');
        $this->db->distinct();
        $this->db->from('radgroupreply');
        $query = $this->db->get();
        $result = $query->result_array();
        
        if (empty($result)) {
            log_message('debug', 'Tidak ada profil ditemukan di tabel radgroupreply');
        } else {
            log_message('debug', 'Profil ditemukan: ' . json_encode($result));
        }
        
        return $result;
    }

    public function get_total_sessions($profile = NULL) {
        if ($profile) {
            $this->db->select('radacct.*');
            $this->db->from('radacct');
            $this->db->join('radusergroup', 'radacct.username = radusergroup.username', 'left');
            $this->db->where('radusergroup.groupname', $profile);
            return $this->db->count_all_results();
        }
        return $this->db->count_all('radacct');
    }

    public function get_active_users_count($profile = NULL) {
        $this->db->where('acctstoptime IS NULL', NULL, FALSE);
        if ($profile) {
            $this->db->join('radusergroup', 'radacct.username = radusergroup.username', 'left');
            $this->db->where('radusergroup.groupname', $profile);
        }
        return $this->db->count_all_results('radacct');
    }

    public function get_total_bandwidth($type, $profile = NULL) {
        $column = ($type == 'upload') ? 'acctinputoctets' : 'acctoutputoctets';
        $this->db->select_sum($column, 'total');
        if ($profile) {
            $this->db->join('radusergroup', 'radacct.username = radusergroup.username', 'left');
            $this->db->where('radusergroup.groupname', $profile);
        }
        $query = $this->db->get('radacct');
        $result = $query->row();
        return $result->total ? round($result->total / (1024 * 1024 * 1024), 2) : 0;
    }

    public function get_top_users($limit = 10, $offset = 0, $start_date = NULL, $end_date = NULL, $profile = NULL) {
        $this->db->select('radacct.username, framedipaddress, callingstationid, SUM(acctinputoctets) / (1024 * 1024 * 1024) as total_upload, SUM(acctoutputoctets) / (1024 * 1024 * 1024) as total_download, radusergroup.groupname as profile');
        $this->db->from('radacct');
        $this->db->join('radusergroup', 'radacct.username = radusergroup.username', 'left');
        if ($profile) {
            $this->db->where('radusergroup.groupname', $profile);
        }
        if ($start_date && $end_date) {
            $this->db->where('acctstarttime >=', $start_date);
            $this->db->where('acctstarttime <=', $end_date);
        }
        $this->db->group_by('radacct.username, radacct.callingstationid, framedipaddress, radusergroup.groupname');
        $this->db->order_by('(SUM(acctinputoctets) + SUM(acctoutputoctets))', 'DESC', FALSE);
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $result = $query->result();
        
        if (empty($result)) {
            log_message('debug', 'Tidak ada data pengguna ditemukan untuk profil: ' . ($profile ?: 'Semua') . ', tanggal: ' . ($start_date ?: 'N/A') . ' - ' . ($end_date ?: 'N/A'));
        }
        
        return $result;
    }

    public function get_auth_history($username = NULL, $limit = 10, $offset = 0) {
        $this->db->select('username, authdate, reply');
        $this->db-group_by('radacct.username, radacct.callingstationid, framedipaddress, radusergroup.groupname');
        $this->db->from('radpostauth');
        if ($username) {
            $this->db->where('username', $username);
        }
        $this->db->order_by('authdate', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    public function check_user_status($username) {
        $this->db->select('username');
        $this->db->where('username', $username);
        $query = $this->db->get('radusergroup');
        if ($query->num_rows() == 0) {
            return ['username' => $username, 'status' => 'Pengguna Tidak Terdaftar'];
        }

        $this->db->select('username, framedipaddress, callingstationid, acctstarttime, acctstoptime');
        $this->db->where('username', $username);
        $this->db->order_by('acctstarttime', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('radacct');
        $result = $query->row_array();
        if ($result) {
            $result['status'] = is_null($result['acctstoptime']) ? 'Online' : 'Offline';
            return $result;
        }
        return ['username' => $username, 'status' => 'Offline'];
    }

    public function get_profile_summary($profile) {
        $this->db->select('radusergroup.groupname as profile, COUNT(DISTINCT radacct.username) as user_count, SUM(acctinputoctets) / (1024 * 1024 * 1024) as total_upload, SUM(acctoutputoctets) / (1024 * 1024 * 1024) as total_download');
        $this->db->from('radacct');
        $this->db->join('radusergroup', 'radacct.username = radusergroup.username', 'left');
        $this->db->where('radusergroup.groupname', $profile);
        $this->db->group_by('radusergroup.groupname');
        $query = $this->db->get();
        $result = $query->row();
        
        if (!$result) {
            log_message('debug', 'Tidak ada data ringkasan untuk profil: ' . $profile);
        } else {
            log_message('debug', 'Ringkasan profil: ' . json_encode($result));
        }
        
        return $result ? [
            'profile' => $result->profile,
            'user_count' => $result->user_count,
            'total_upload' => round($result->total_upload, 2),
            'total_download' => round($result->total_download, 2)
        ] : null;
    }

    // Method baru untuk total bandwidth di tabel Top User pada Profil
    public function get_total_bandwidth_per_profile($profile, $start_date = NULL, $end_date = NULL) {
        $this->db->select('SUM(acctinputoctets) / (1024 * 1024 * 1024) as total_upload, SUM(acctoutputoctets) / (1024 * 1024 * 1024) as total_download');
        $this->db->from('radacct');
        $this->db->join('radusergroup', 'radacct.username = radusergroup.username', 'left');
        $this->db->where('radusergroup.groupname', $profile);
        if ($start_date && $end_date) {
            $this->db->where('acctstarttime >=', $start_date);
            $this->db->where('acctstarttime <=', $end_date);
        }
        $query = $this->db->get();
        $result = $query->row();
        
        return [
            'total_upload' => $result->total_upload ? round($result->total_upload, 2) : 0,
            'total_download' => $result->total_download ? round($result->total_download, 2) : 0
        ];
    }
}