<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
    public function get_profiles() {
        $this->db->select('groupname');
        $this->db->distinct();
        $this->db->from('radgroupreply');
        $query = $this->db->get();
        return $query->result_array();
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
        return $result->total ? round($result->total / (1024 * 1024 * 1024), 2) : 0; // Konversi ke GB
    }

    public function get_top_users($limit = 10, $offset = 0, $start_date = NULL, $end_date = NULL) {
        $this->db->select('username, framedipaddress, callingstationid, SUM(acctinputoctets) / (1024 * 1024 * 1024) as total_upload, SUM(acctoutputoctets) / (1024 * 1024 * 1024) as total_download');
        $this->db->from('radacct');
        if ($start_date && $end_date) {
            $this->db->where('acctstarttime >=', $start_date);
            $this->db->where('acctstarttime <=', $end_date);
        }
        $this->db->group_by('username, framedipaddress, callingstationid');
        $this->db->order_by('(SUM(acctinputoctets) + SUM(acctoutputoctets))', 'DESC', FALSE);
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_auth_history($username = NULL, $limit = 10, $offset = 0) {
        $this->db->select('username, authdate, reply');
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
        // Cek apakah pengguna ada di tabel radusergroup
        $this->db->select('username');
        $this->db->where('username', $username);
        $query = $this->db->get('radusergroup');
        if ($query->num_rows() == 0) {
            return ['username' => $username, 'status' => 'Pengguna Tidak Terdaftar'];
        }

        // Jika pengguna ada, cek status online/offline di tabel radacct
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
}