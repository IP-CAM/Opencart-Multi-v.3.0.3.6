<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		if (isset($data['customer_id'])) {
			$this->ssodb->query("INSERT INTO ". SSODB_PREFIX . "user_store SET user_id = ". (int)$data['customer_id']. " , store = '" . SSODB_DATABASE . "' , user_group_id = NULL, status = true");
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->ssodb->query("INSERT INTO " . SSODB_PREFIX . "user SET firstname = '" . $this->ssodb->escape($data['firstname']) . "', lastname = '" . $this->ssodb->escape($data['lastname']) . "', email = '" . $this->ssodb->escape($data['email']) . "', telephone = '" . $this->ssodb->escape($data['telephone']) . "', salt = '" . $this->ssodb->escape($salt = token(9)) . "', password = '" . $this->ssodb->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		$user_id = $this->ssodb->getLastId();

		if ($customer_group_info['approval']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$user_id . "', type = 'customer', date_added = NOW()");
		}
		
		return $user_id;
	}

	public function editCustomer($user_id, $data) {
		$this->ssodb->query("UPDATE " . SSODB_PREFIX . "user SET firstname = '" . $this->ssodb->escape($data['firstname']) . "', lastname = '" . $this->ssodb->escape($data['lastname']) . "', email = '" . $this->ssodb->escape($data['email']) . "', telephone = '" . $this->ssodb->escape($data['telephone']) . "' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function editPassword($email, $password) {
		$this->ssodb->query("UPDATE " . SSODB_PREFIX . "user SET salt = '" . $this->ssodb->escape($salt = token(9)) . "', password = '" . $this->ssodb->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE LOWER(email) = '" . $this->ssodb->escape(utf8_strtolower($email)) . "'");
	}

	public function editAddressId($user_id, $address_id) {
		$this->ssodb->query("UPDATE " . SSODB_PREFIX . "user SET ship_address_id = '" . (int)$address_id . "' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function editBillAddressId($user_id, $address_id) {
		$this->ssodb->query("UPDATE " . SSODB_PREFIX . "user SET bill_address_id = '" . (int)$address_id . "' WHERE user_id = '" . (int)$user_id . "'");
	}
	
	public function editCode($email, $code) {
		$this->ssodb->query("UPDATE `" . SSODB_PREFIX . "user` SET code = '" . $this->ssodb->escape($code) . "' WHERE LCASE(email) = '" . $this->ssodb->escape(utf8_strtolower($email)) . "'");
	}

	public function getCustomer($user_id) {
		$query = $this->ssodb->query("SELECT * FROM " . SSODB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->ssodb->query("SELECT * FROM " . SSODB_PREFIX . "user WHERE LOWER(email) = '" . $this->ssodb->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByCode($code) {
		$query = $this->ssodb->query("SELECT user_id, firstname, lastname, email FROM `" . SSODB_PREFIX . "user` WHERE code = '" . $this->ssodb->escape($code) . "' AND code != ''");

		return $query->row;
	}

	// public function getCustomerByToken($token) {
	// 	$query = $this->ssodb->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

	// 	$this->ssodb->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

	// 	return $query->row;
	// }
	
	public function getTotalCustomersByEmail($email) {
		$query = $this->ssodb->query("SELECT COUNT(*) AS total FROM " . SSODB_PREFIX . "user WHERE LOWER(email) = '" . $this->ssodb->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function addTransaction($customer_id, $description, $amount = '', $order_id = 0) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (float)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");
	}

	public function deleteTransactionByOrderId($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getTransactionTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
	
	public function getRewardTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getIps($user_id) {
		$query = $this->ssodb->query("SELECT * FROM `" . SSODB_PREFIX . "user_ip` WHERE user_id = '" . (int)$user_id . "'");

		return $query->rows;
	}

	public function addLoginAttempt($email) {
		$query = $this->ssodb->query("SELECT * FROM " . SSODB_PREFIX . "user_login WHERE email = '" . $this->ssodb->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->ssodb->escape($this->request->server['REMOTE_ADDR']) . "'");

		if (!$query->num_rows) {
			$this->ssodb->query("INSERT INTO " . SSODB_PREFIX . "user_login SET email = '" . $this->ssodb->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->ssodb->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->ssodb->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->ssodb->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->ssodb->query("UPDATE " . SSODB_PREFIX . "user_login SET total = (total + 1), date_modified = '" . $this->ssodb->escape(date('Y-m-d H:i:s')) . "' WHERE user_login_id = '" . (int)$query->row['user_login_id'] . "'");
		}
	}

	public function getLoginAttempts($email) {
		$query = $this->ssodb->query("SELECT * FROM `" . SSODB_PREFIX . "user_login` WHERE email = '" . $this->ssodb->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function deleteLoginAttempts($email) {
		$this->ssodb->query("DELETE FROM `" . SSODB_PREFIX . "user_login` WHERE email = '" . $this->ssodb->escape(utf8_strtolower($email)) . "'");
	}
	
	public function addAffiliate($customer_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_affiliate SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape($data['company']) . "', `website` = '" . $this->db->escape($data['website']) . "', `tracking` = '" . $this->db->escape(token(64)) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape($data['tax']) . "', `payment` = '" . $this->db->escape($data['payment']) . "', `cheque` = '" . $this->db->escape($data['cheque']) . "', `paypal` = '" . $this->db->escape($data['paypal']) . "', `bank_name` = '" . $this->db->escape($data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape($data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape($data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($data['bank_account_number']) . "', `status` = '" . (int)!$this->config->get('config_affiliate_approval') . "'");
		
		if ($this->config->get('config_affiliate_approval')) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'affiliate', date_added = NOW()");
		}		
	}
		
	public function editAffiliate($customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer_affiliate SET `company` = '" . $this->db->escape($data['company']) . "', `website` = '" . $this->db->escape($data['website']) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape($data['tax']) . "', `payment` = '" . $this->db->escape($data['payment']) . "', `cheque` = '" . $this->db->escape($data['cheque']) . "', `paypal` = '" . $this->db->escape($data['paypal']) . "', `bank_name` = '" . $this->db->escape($data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape($data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape($data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($data['bank_account_number']) . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}
	
	public function getAffiliate($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->row;
	}
	
	public function getAffiliateByTracking($tracking) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `tracking` = '" . $this->db->escape($tracking) . "'");

		return $query->row;
	}			
}