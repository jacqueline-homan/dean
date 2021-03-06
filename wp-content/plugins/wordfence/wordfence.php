<?php
/*
Plugin Name: Wordfence Security
Plugin URI: http://www.wordfence.com/
Description: Wordfence Security - Anti-virus, Firewall and real-time WordPress security Network
Author: Wordfence
Version: 5.0.3
Author URI: http://www.wordfence.com/
*/
if(defined('WP_INSTALLING') && WP_INSTALLING){
	return;
}
define('WORDFENCE_VERSION', '5.0.3');
if(get_option('wordfenceActivated') != 1){
	add_action('activated_plugin','wordfence_save_activation_error'); function wordfence_save_activation_error(){ update_option('wf_plugin_act_error',  ob_get_contents()); }
}
if(! defined('WORDFENCE_VERSIONONLY_MODE')){
	if((int) @ini_get('memory_limit') < 64){
		if(strpos(ini_get('disable_functions'), 'ini_set') === false){
			@ini_set('memory_limit', '64M'); //Some hosts have ini set at as little as 32 megs. 64 is the min sane amount of memory.
		}
	}
	require_once('lib/wordfenceConstants.php');
	require_once('lib/wordfenceClass.php');
	wordfence::install_actions();
}

?>
