<?php
namespace Cart;
class Customer {
	private $user_id;
	private $firstname;
	private $lastname;
	// private $customer_group_id;
	private $email;
	private $telephone;
	private $address_id;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->ssodb = $registry->get('ssodb');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['customer_id'])) {
			$user_query = $this->ssodb->query("SELECT * FROM " . SSODB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['customer_id'] . "' AND status = '1'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->firstname = $user_query->row['firstname'];
				$this->lastname = $user_query->row['lastname'];
				// $this->customer_group_id = $user_query->row['customer_group_id'];
				$this->email = $user_query->row['email'];
				$this->telephone = $user_query->row['telephone'];
				$this->address_id = $user_query->row['ship_address_id'];

				$query = $this->ssodb->query("SELECT * FROM " . SSODB_PREFIX . "user_ip WHERE user_id = '" . (int)$this->session->data['customer_id'] . "' AND ip = '" . $this->ssodb->escape($this->request->server['REMOTE_ADDR']) . "'");

				if (!$query->num_rows) {
					$this->ssodb->query("INSERT INTO " . SSODB_PREFIX . "user_ip SET user_id = '" . (int)$this->session->data['customer_id'] . "', ip = '" . $this->ssodb->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
				}
			} else {
				$this->logout();
			}
		}
	}

  public function login($email, $password, $override = false) {
		if ($override) {
			$user_query = $this->ssodb->query("SELECT * FROM " . SSODB_PREFIX . "user WHERE LOWER(email) = '" . $this->ssodb->escape(utf8_strtolower($email)) . "' AND status = '1'");
		} else {
			$user_query = $this->ssodb->query("SELECT * FROM " . SSODB_PREFIX . "user WHERE LOWER(email) = '" . $this->ssodb->escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->ssodb->escape($password) . "'))))) OR password = '" . $this->ssodb->escape(md5($password)) . "') AND status = '1'");
		}

		if ($user_query->num_rows) {
			$this->session->data['customer_id'] = $user_query->row['user_id'];

			$this->user_id = $user_query->row['user_id'];
			$this->firstname = $user_query->row['firstname'];
			$this->lastname = $user_query->row['lastname'];
			// $this->customer_group_id = $user_query->row['customer_group_id'];
			$this->email = $user_query->row['email'];
			$this->telephone = $user_query->row['telephone'];
			$this->address_id = $user_query->row['ship_address_id'];

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		unset($this->session->data['customer_id']);

		$this->user_id = '';
		$this->firstname = '';
		$this->lastname = '';
		// $this->customer_group_id = '';
		$this->email = '';
		$this->telephone = '';
		$this->address_id = '';
	}

	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getFirstName() {
		return $this->firstname;
	}

	public function getLastName() {
		return $this->lastname;
	}

	// public function getGroupId() {
	// 	return $this->customer_group_id;
	// }

	public function getEmail() {
		return $this->email;
	}

	public function getTelephone() {
		return $this->telephone;
	}

	public function getAddressId() {
		return $this->address_id;
	}

	public function getBalance() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$this->user_id . "'");

		return $query->row['total'];
	}

	public function getRewardPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->user_id . "'");

		return $query->row['total'];
	}
}
