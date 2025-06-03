<?php
class Graph_model extends CI_Model
{
    public function get_profiles()
    {
        $this->db->select('groupname');
        $this->db->distinct();
        return $this->db->get('radgroupreply')->result();
    }

    public function get_graph_data($type, $id, $start_date, $end_date)
    {
        $this->db->select('DATE(acctstarttime) as date, SUM(acctinputoctets) as upload, SUM(acctoutputoctets) as download, COUNT(DISTINCT username) as active_users');
        if ($type == 'user') {
            $this->db->where('username', $id);
        } else {
            $this->db->where('username IN (SELECT username FROM radusergroup WHERE groupname = ' . $this->db->escape($id) . ')', NULL, FALSE);
        }
        $this->db->where('acctstarttime >=', $start_date);
        $this->db->where('acctstarttime <=', $end_date);
        $this->db->group_by('DATE(acctstarttime)');
        $query = $this->db->get('radacct');
        return $query->result();
    }
}
