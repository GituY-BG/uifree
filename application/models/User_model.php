<?php
class User_model extends CI_Model
{
    public function get_users($search = '', $limit = 20, $offset = 0)
    {
        $this->db->select('radcheck.*, radusergroup.groupname, (SELECT COUNT(*) FROM radacct WHERE radacct.username = radcheck.username AND acctstoptime IS NULL) AS online_status');
        $this->db->from('radcheck');
        $this->db->join('radusergroup', 'radcheck.username = radusergroup.username', 'left');
        
        if (!empty($search)) {
            $this->db->like('radcheck.username', $search);
        }

        $this->db->order_by("CASE WHEN radcheck.status = 'inactive' THEN 0 ELSE 1 END, radcheck.username ASC");
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
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'status' => $data['status'],
                'temp_value' => NULL
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
        if ($status == 'inactive') {
            // Membekukan akun: simpan password di temp_value dan ubah ke Auth-Type := Reject
            $user = $this->db->get_where('radcheck', ['username' => $username])->row();
            if ($user && $user->attribute == 'Cleartext-Password') {
                $this->db->where('username', $username);
                $this->db->update('radcheck', [
                    'attribute' => 'Auth-Type',
                    'op' => ':=',
                    'value' => 'Reject',
                    'temp_value' => $user->value,
                    'status' => 'inactive'
                ]);
            } else {
                $this->db->where('username', $username);
                $this->db->update('radcheck', ['status' => 'inactive']);
            }
        } else {
            // Mengaktifkan kembali: kembalikan password dari temp_value
            $user = $this->db->get_where('radcheck', ['username' => $username])->row();
            if ($user && $user->temp_value) {
                $this->db->where('username', $username);
                $this->db->update('radcheck', [
                    'attribute' => 'Cleartext-Password',
                    'op' => ':=',
                    'value' => $user->temp_value,
                    'temp_value' => NULL,
                    'status' => 'active'
                ]);
            } else {
                $this->db->where('username', $username);
                $this->db->update('radcheck', ['status' => 'active']);
            }
        }

        return $this->db->affected_rows();
    }

    public function get_user_online_status($username)
    {
        $this->db->select('COUNT(*) as is_online');
        $this->db->where('username', $username);
        $this->db->where('acctstoptime IS NULL');
        $query = $this->db->get('radacct');
        $result = $query->row();
        return $result->is_online > 0 ? 'Online' : 'Offline';
    }
}