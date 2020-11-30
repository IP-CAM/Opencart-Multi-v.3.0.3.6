<?php
// Site
$_['site_url']          = HTTP_SERVER;
$_['site_ssl']          = HTTPS_SERVER;

// Database
$_['db_autostart']      = true;
$_['db_engine']         = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_hostname']       = DB_HOSTNAME;
$_['db_username']       = DB_USERNAME;
$_['db_password']       = DB_PASSWORD;
$_['db_database']       = DB_DATABASE;
$_['db_port']           = DB_PORT;

// SSO
$_['ssodb_autostart']      = true;
$_['ssodb_engine']         = SSODB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['ssodb_hostname']       = SSODB_HOSTNAME;
$_['ssodb_username']       = SSODB_USERNAME;
$_['ssodb_password']       = SSODB_PASSWORD;
$_['ssodb_database']       = SSODB_DATABASE;
$_['ssodb_port']           = SSODB_PORT;

// Session
$_['session_autostart'] = true;

// Template
$_['template_cache']    = true;

// Actions
$_['action_pre_action'] = array(
	'startup/startup',
	'startup/error',
	'startup/event',
	'startup/sass',
	'startup/login',
	'startup/permission'
);

// Actions
$_['action_default'] = 'common/dashboard';

// Action Events
$_['action_event'] = array(
	'controller/*/before' => array(
		'event/language/before'
	),
	'controller/*/after' => array(
		'event/language/after'
	),
	'view/*/before' => array(
		999  => 'event/language',
		1000 => 'event/theme'
	),
	'view/*/before' => array(
		'event/language'
	)
);
