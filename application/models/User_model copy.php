<?php
class User_model extends CI_Model
{
    public function get_users($search = '', $limit = 20, $offset = 0)
    {
        $this->db->select('radcheck.*, radusergroup.groupname');
        $this->db->from('radcheck');
        $this->db->join('radusergroup', 'radcheck.username = radusergroup.username', 'left');
        if ($search) {
            $this->db->like('radcheck.username', $search);
        }
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_total_users($search = '')
    {
        if ($search) {
            $this->db->like('username', $search);
        }
        return $this->db->count_all_results('radcheck');
    }

    public function get_profiles()
    {
        $this->db->select('groupname');
        $this->db->distinct();
        return $this->db->get('radgroupreply')->result();
    }

    public function add_user($data, $group_data)
    {
        $this->db->insert('radcheck', $data);
        $this->db->insert('radusergroup', $group_data);
    }

    public function update_status($username, $status)
    {
        $this->db->where('username', $username);
        $this->db->update('radcheck', ['status' => $status]);
        return $this->db->affected_rows();
    }
}