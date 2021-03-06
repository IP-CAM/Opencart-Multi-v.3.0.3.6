<?php
class ModelAccountAddress extends Model {
	public function addAddress($user_id, $data) {
		$this->ssodb->query("INSERT INTO " . SSODB_PREFIX . "address SET user_id = '" . (int)$user_id . "', firstname = '" . $this->ssodb->escape($data['firstname']) . "', lastname = '" . $this->ssodb->escape($data['lastname']) . "', company = '" . $this->ssodb->escape($data['company']) . "', address_1 = '" . $this->ssodb->escape($data['address_1']) . "', address_2 = '" . $this->ssodb->escape($data['address_2']) . "', postcode = '" . $this->ssodb->escape($data['postcode']) . "', city = '" . $this->ssodb->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', custom_field = '" . $this->ssodb->escape(isset($data['custom_field']['address']) ? json_encode($data['custom_field']['address']) : '') . "'");

		$address_id = $this->ssodb->getLastId();

		if (!empty($data['default'])) {
			$this->ssodb->query("UPDATE " . SSODB_PREFIX . "user SET ship_address_id = '" . (int)$address_id . "' WHERE user_id = '" . (int)$user_id . "'");
		}

		return $address_id;
	}

	public function editAddress($address_id, $data) {
		$this->ssodb->query("UPDATE " . SSODB_PREFIX . "address SET firstname = '" . $this->ssodb->escape($data['firstname']) . "', lastname = '" . $this->ssodb->escape($data['lastname']) . "', company = '" . $this->ssodb->escape($data['company']) . "', address_1 = '" . $this->ssodb->escape($data['address_1']) . "', address_2 = '" . $this->ssodb->escape($data['address_2']) . "', postcode = '" . $this->ssodb->escape($data['postcode']) . "', city = '" . $this->ssodb->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', custom_field = '" . $this->ssodb->escape(isset($data['custom_field']['address']) ? json_encode($data['custom_field']['address']) : '') . "' WHERE address_id  = '" . (int)$address_id . "' AND user_id = '" . (int)$this->customer->getId() . "'");

		if (!empty($data['default'])) {
			$this->ssodb->query("UPDATE " . SSODB_PREFIX . "user SET ship_address_id = '" . (int)$address_id . "' WHERE user_id = '" . (int)$this->customer->getId() . "'");
		}
	}

	public function deleteAddress($address_id) {
		$this->ssodb->query("DELETE FROM " . SSODB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND user_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getAddress($address_id) {
		$address_query = $this->ssodb->query("SELECT DISTINCT * FROM " . SSODB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND user_id = '" . (int)$this->customer->getId() . "'");

		if ($address_query->num_rows) {
			$country_query = $this->ssodb->query("SELECT * FROM `" . SSODB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->ssodb->query("SELECT * FROM `" . SSODB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id'     => $address_query->row['address_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($address_query->row['custom_field'], true)
			);

			return $address_data;
		} else {
			return false;
		}
	}

	public function getAddresses() {
		$address_data = array();

		$query = $this->ssodb->query("SELECT * FROM " . SSODB_PREFIX . "address WHERE user_id = '" . (int)$this->customer->getId() . "'");

		foreach ($query->rows as $result) {
			$country_query = $this->ssodb->query("SELECT * FROM `" . SSODB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->ssodb->query("SELECT * FROM `" . SSODB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data[$result['address_id']] = array(
				'address_id'     => $result['address_id'],
				'firstname'      => $result['firstname'],
				'lastname'       => $result['lastname'],
				'company'        => $result['company'],
				'address_1'      => $result['address_1'],
				'address_2'      => $result['address_2'],
				'postcode'       => $result['postcode'],
				'city'           => $result['city'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $result['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($result['custom_field'], true)

			);
		}

		return $address_data;
	}

	public function getTotalAddresses() {
		$query = $this->ssodb->query("SELECT COUNT(*) AS total FROM " . SSODB_PREFIX . "address WHERE user_id = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
}
