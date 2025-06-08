<?php
class User_model extends CI_Model
{
    public function get_users($search = '', $limit = 20, $offset = 0)
    {
        $this->db->select('radcheck.*, radusergroup.groupname');
        $this->db->from('radcheck');
        $this->db->join('radusergroup', 'radcheck.username = radusergroup.username', 'left');
        
        if (!empty($search)) {
            $this->db->like('radcheck.username', $search);
        }

        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_total_users($search = '')
    {
        $this->db->from('radcheck');

        if (!empty($search)) {
            $this->db->like('username', $search);
        }

        return $this->db->count_all_results();
    }

    public function get_profiles()
    {
        return $this->db->select('DISTINCT(groupname)')
                        ->get('radgroupreply')
                        ->result();
    }

    public function add_user($data, $group_data)
    {
        // Cek apakah user sudah ada di radcheck
        $exists = $this->db->get_where('radcheck', ['username' => $data['username']])->row();

        if ($exists) {
            // Jika user sudah ada, update saja password dan status
            $this->db->where('username', $data['username']);
            $this->db->update('radcheck', [
                'value' => $data['value'],
                'status' => $data['status']
            ]);
        } else {
            // Insert user baru
            $this->db->insert('radcheck', $data);
        }

        // Selalu update atau insert ke radusergroup
        $group_exists = $this->db->get_where('radusergroup', ['username' => $group_data['username']])->row();

        if ($group_exists) {
            $this->db->where('username', $group_data['username']);
            $this->db->update('radusergroup', ['groupname' => $group_data['groupname']]);
        } else {
            $this->db->insert('radusergroup', $group_data);
        }
    }

    public function update_status($username, $status)
    {
        $this->db->where('username', $username);
        $this->db->update('radcheck', ['status' => $status]);

        return $this->db->affected_rows();
    }
}
