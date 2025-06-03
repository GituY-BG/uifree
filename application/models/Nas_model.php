<?php
class Nas_model extends CI_Model
{
    public function get_nas($search = '')
    {
        if ($search) {
            $this->db->group_start();
            $this->db->like('nasname', $search);
            $this->db->or_like('shortname', $search);
            $this->db->group_end();
        }
        $this->db->limit(10);
        return $this->db->get('nas')->result();
    }

    public function get_nas_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('nas')->row();
    }

    public function add_nas($data)
    {
        $this->db->insert('nas', $data);
    }

    public function update_nas($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('nas', $data);
    }

    public function delete_nas($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('nas');
    }
}
?>