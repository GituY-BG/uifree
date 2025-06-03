<?php
class Profile_model extends CI_Model
{
    public function get_profiles()
    {
        return $this->db->get('radgroupreply')->result();
    }

    public function add_profile($data, $data_simultan)
    {
        $this->db->insert('radgroupreply', $data);
        $this->db->insert('radgroupreply', $data_simultan);
    }
}
