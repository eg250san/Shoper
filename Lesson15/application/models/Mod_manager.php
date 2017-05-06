<?php

class Mod_manager extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	// 確認管理者是否登入
	function chk_login_status() {
		return $this->session->userdata('manager_login_status');
	}

	// 確認管理者信箱密碼是否存在
	function chk_login($email, $pwd) {
		$this->db->where('email', $email);
		$this->db->where('password', sha1($pwd));
		// 透過藉由信箱密碼下去比對是否存在
		if($this->db->count_all_results('manager_main') == 0) {
			// 不存在
			return false;
		}else{
			// 存在
			return true;
		}
	}

	// 管理員進行登入
	function do_login($email) {
		$manager = $this->get_once_by_email($email);

		$session_arr = array(
			'manager_name'=> $manager['nickname'],
			'manager_email'=> $manager['email'],
			'manager_id'=> $manager['id'],
			'manager_login_status'=> true
			);

		// 登入資訊保存到Session
		$this->session->set_userdata($session_arr);

		$this->set_last_login($manager['id']);

		return true;
	}

	// 取得管理員資料
	function get_once_by_email($email) {
		$this->db->where('email', $email);
		return $this->db->get('manager_main')->row_array();
	}

	function set_last_login($id) {
		$dataArray = array(
			'last_date'=> date('Y-m-d'),
			'last_time'=> date('H:i:s')
			);
		$this->db->where('id', $id);
		return $this->db->update('manager_main', $dataArray);
	}
}

?>