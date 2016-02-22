<?php

/*
	Plugin Name: POPUP AD
	Plugin URI: 
	Plugin Description: popup add
	Plugin Version: 0.1
	Plugin Date: 2015-09-11
	Plugin Author:yshiga
	Plugin Author URI:https://github.com/yshiga
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.7
	Plugin Update Check URI: 
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

qa_register_plugin_layer('qa-popup-ad-layer.php', 'POPUP ADD');	
qa_register_plugin_module('module', 'q2a-popup-ad-admin.php', 'q2a_popup_ad_admin', 'popup_ad');
