<?php
namespace SSO;
class Store {
	
	public static function storeToConfig($storeInfos = []){
		$config = [];
    if (sizeof($storeInfos)){
      $config['config_logo']       = $storeInfos['logo'] ?? '';
			$config['config_telephone']  = $storeInfos['telephone'] ?? '';
			$config['config_email']      = $storeInfos['email'] ?? '';
			$config['config_geocode']    = $storeInfos['geocode'] ?? '';
			$config['config_address']    = $storeInfos['address'] ?? '';
			$config['config_owner']      = $storeInfos['store_owner'] ?? '';
			$config['config_name']       = $storeInfos['store_name'] ?? '';
			$config['config_facebook']   = $storeInfos['facebook'] ?? '';
			$config['config_instagram']  = $storeInfos['instagram'] ?? '';
			$config['config_whatsapp']   = $storeInfos['whatsapp'] ?? '';
			$config['config_comment']    = $storeInfos['about'] ?? '';
		}
		return $config;
	}
	
	public static function configToStore($config = []){
		$store = [];
    if (sizeof($config)){
      $store['logo']       		= $config['config_logo'] ?? '';
			$store['telephone']  		= $config['config_telephone'] ?? '';
			$store['email']      		= $config['config_email'] ?? '';
			$store['geocode']    		= $config['config_geocode'] ?? '';
			$store['address']    		= $config['config_address'] ?? '';
			$store['store_owner']   = $config['config_owner'] ?? '';
			$store['store_name']    = $config['config_name'] ?? '';
			$store['facebook']   		= $config['config_facebook'] ?? '';
			$store['instagram']  		= $config['config_instagram'] ?? '';
			$store['whatsapp']   		= $config['config_whatsapp'] ?? '';
			$store['about']    			= $config['config_comment'] ?? '';
		}
		return $store;
	}

	public static function checkConfigStoreSSO($key){
		$configFieldsSSO = 'config_logo,config_telephone,
		config_email,config_geocode,config_address,config_owner,config_name,
		config_facebook,config_instagram,config_whatsapp,config_comment';

		if (strpos($configFieldsSSO, $key) === false) return false;
		return true;
	}
}