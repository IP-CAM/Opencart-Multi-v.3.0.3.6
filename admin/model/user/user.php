<?php
class ModelUserUser extends Model {
	public function addUser($data) {
		$this->ssodb->query("INSERT INTO `user` SET email = '" . $this->ssodb->escape($data['email']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', salt = '" . $this->ssodb->escape($salt = token(9)) . "', password = '" . $this->ssodb->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . $this->ssodb->escape($data['firstname']) . "', lastname = '" . $this->ssodb->escape($data['lastname']) . "', email = '" . $this->ssodb->escape($data['email']) . "', image = '" . $this->ssodb->escape($data['image']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	
		return $this->ssodb->getLastId();
	}

	public function editUser($user_id, $data) {
		$this->ssodb->query("UPDATE `user` SET email = '" . $this->ssodb->escape($data['email']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', firstname = '" . $this->ssodb->escape($data['firstname']) . "', lastname = '" . $this->ssodb->escape($data['lastname']) . "', email = '" . $this->ssodb->escape($data['email']) . "', image = '" . $this->ssodb->escape($data['image']) . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");

		if ($data['password']) {
			$this->ssodb->query("UPDATE `user` SET salt = '" . $this->ssodb->escape($salt = token(9)) . "', password = '" . $this->ssodb->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}

	public function editPassword($user_id, $password) {
		$this->ssodb->query("UPDATE `user` SET salt = '" . $this->ssodb->escape($salt = token(9)) . "', password = '" . $this->ssodb->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function editCode($email, $code) {
		$this->ssodb->query("UPDATE `user` SET code = '" . $this->ssodb->escape($code) . "' WHERE LCASE(email) = '" . $this->ssodb->escape(utf8_strtolower($email)) . "'");
	}

	public function deleteUser($user_id) {
		$this->ssodb->query("DELETE FROM `user` WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getUser($user_id) {
		$query = $this->ssodb->query("SELECT *, (SELECT ug.name FROM `user_group` ug WHERE ug.user_group_id = us.user_group_id) AS user_group FROM user u JOIN user_store us ON us.user_id = u.user_id WHERE u.user_id = '" . (int)$user_id . "' AND us.store = '" . DB_DATABASE. "'");

		return $query->row;
	}

	// public function getUserByUsername($username) {
	// 	$query = $this->ssodb->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->ssodb->escape($username) . "'");

	// 	return $query->row;
	// }

	public function getUserByEmail($email) {
		$query = $this->ssodb->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->ssodb->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getUserByCode($code) {
		$query = $this->ssodb->query("SELECT * FROM `user` WHERE code = '" . $this->ssodb->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function getUsers($data = array()) {
		$sql = "SELECT * FROM `user`";

		$sort_data = array(
			'email',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY email";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->ssodb->query($sql);

		return $query->rows;
	}

	public function getTotalUsers() {
		$query = $this->ssodb->query("SELECT COUNT(*) AS total FROM `user`");

		return $query->row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id) {
		$query = $this->ssodb->query("SELECT COUNT(*) AS total FROM `user` WHERE user_group_id = '" . (int)$user_group_id . "'");

		return $query->row['total'];
	}

	public function getTotalUsersByEmail($email) {
		$query = $this->ssodb->query("SELECT COUNT(*) AS total FROM `user` WHERE LCASE(email) = '" . $this->ssodb->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}
}