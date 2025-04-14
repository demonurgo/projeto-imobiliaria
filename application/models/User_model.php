<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->table = 'users';
	}

	public function get($id)
	{
		return $this->db->get_where($this->table, ['id' => $id])->row();
	}

	public function get_by_username($username)
	{
		return $this->db->get_where($this->table, ['username' => $username])->row();
	}

	public function authenticate($username, $password)
	{
		$user = $this->get_by_username($username);

		if (!$user) {
			return false;
		}

		// Verificação adequada: texto plano da senha fornecida contra o hash armazenado
		if (!password_verify($password, $user->password)) {
			return false;
		}

		// Atualizar último login
		$this->db->where('id', $user->id);
		$this->db->update($this->table, ['last_login' => date('Y-m-d H:i:s')]);

		return $user;
	}

	public function create($data)
	{
		// Hash da senha
		if (isset($data['password'])) {
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		}

		// Certifique-se de que is_admin está definido
		if (!isset($data['is_admin'])) {
			$data['is_admin'] = 0; // Padrão para não-admin
		}

		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id, $data)
	{
		// Hash da senha apenas se estiver sendo atualizada
		if (isset($data['password']) && !empty($data['password'])) {
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		} else {
			unset($data['password']);
		}

		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function get_all()
	{
		$this->db->order_by('id', 'ASC');
		return $this->db->get($this->table)->result();
	}
}
