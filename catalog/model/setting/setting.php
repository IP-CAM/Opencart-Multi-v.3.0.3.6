<?php
class ModelSettingSetting extends Model {
	public function getSetting($code, $store_id = 0) {
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $this->db->escape($code) . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = json_decode($result['value'], true);
			}
		}

		if ($code == 'config'){
			$ssoquery = $this->ssodb->query("SELECT * FROM store WHERE store = '". DB_DATABASE ."'");
			$storeInfo = sso\store::storeToConfig($ssoquery->row);
			foreach ($storeInfo as $key => $info) {
				$data[$key] =  $info;
			}
		}

		return $data;
	}
	
	public function getSettingValue($key, $store_id = 0) {
		
		if (sso\store::checkConfigStoreSSO($key)){
			$ssoquery = $this->ssodb->query("SELECT * FROM store WHERE store = '". DB_DATABASE ."'");
			$storeInfo = sso\store::storeToConfig($ssoquery->row);
			if (isset($storeInfo[$key])){
				return $storeInfo[$key];
			}
		}

		$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `key` = '" . $this->db->escape($key) . "'");

		if ($query->num_rows) {
			return $query->row['value'];
		} else {
			return null;	
		}
	}	
}