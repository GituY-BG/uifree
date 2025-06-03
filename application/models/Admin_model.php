<?php
class Admin_model extends CI_Model
{
	public function check_login($username, $password)
	{
		$this->db->where('username', $username);
		$query = $this->db->get('admin');
		$admin = $query->row();

		if ($admin && password_verify($password, $admin->password)) {
			return $admin;
		}
		return false;
	}


	public function insert_admin($username, $hashed_password)
	{
		$data = [
			'username' => $username,
			'password' => $hashed_password
		];
		return $this->db->insert('admin', $data);
	}
}
