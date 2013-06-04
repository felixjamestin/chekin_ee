<?php

## ------------------------------------------------------------
## SETUP THE ENVIRONMENT
## ------------------------------------------------------------

if(!defined('ENV')) {

	define('THIS_SERVER_NAME', 	$_SERVER['SERVER_NAME']);
	define('THIS_SITE_URL', 	'http://' . THIS_SERVER_NAME);
  define('THIS_BASEPATH', 	realpath(dirname(__FILE__) . '/../'));

	/**************************************************
 	Customize these environments to fit your needs
 	Make sure to create a config.ENV.php file for
 	each environment you have.
	***************************************************/
	switch ($_SERVER['HTTP_HOST']) {
		case 'chekin.in' :
			define('ENV', 'live');
			define('THIS_WEB_ROOT', 'public_html');
			// define('SITE_SHORT_NAME', 'default');
			break;

		case 'dev.chekin.in' :
			define('ENV', 'dev');
			define('THIS_WEB_ROOT', 'public_dev_html');
			break;

		default :
			define('ENV', 'local');
			define('THIS_WEB_ROOT', 'public_html');
			break;
	}
}

/**************************************************
 Within the included file:
 1) SET THE ENVIRONMENTAL CONFIG VALUES
 2) SET THE ENVIRONMENTAL GLOBAL VARIABLES

 We will set the ENVIRONMENTAL DATABASE later
***************************************************/
require 'config.' . ENV . '.php';


/**************************************************
 SET DEFAULT CONFIG VALUES
 Check to make sure we have processed the default EE config.php
 If we have, set as many config values as we can in this file.
 You'll want to review all of these settings and paths and
 make sure they are appropriate
***************************************************/
if(isset($config)) {

	// General preferences
	$config['is_system_on'] 				= 'y';
	$config['license_number'] 				= '3062-4966-5957-7147';
	$config['site_index']	 				= '';
	$config['admin_session_type']		 	= 'cs';
	$config['new_version_check'] 			= 'n';
	$config['doc_url'] 						= 'http://expressionengine.com/user_guide/';
	$config['cp_theme'] 					= 'nerdery';

	$config['uri_protocol'] 				= 'AUTO';
	// Encryption / Session key
	$config['encryption_key'] 				= '';

	// Channel preferences
	$config['use_category_name'] 			= 'y';
	$config['word_separator'] 				= 'dash';
	$config['reserved_category_word'] 		= 'categories';

	// Template preferences
	$config['strict_urls']					= 'y';
	$config['save_tmpl_files'] 				= 'y';
	$config['save_tmpl_revisions'] 			= 'y';

	// Tracking preferences
	$config['enable_online_user_tracking'] 	= 'n';
	$config['dynamic_tracking_disabling'] 	= '500';
	$config['enable_hit_tracking'] 			= 'n';
	$config['enable_entry_view_tracking']	= 'n';
	$config['log_referrers'] 				= 'n';

	// Member preferences
	$config['allow_registration'] 			= 'n';
	$config['profile_trigger'] 				= '%'; // makes default member functionality inaccessible


	$config['enable_emoticons'] 			= 'n';

	$config['enable_avatars'] 				= 'n';
	$config['avatar_max_height'] 			= '100';
	$config['avatar_max_width'] 			= '100';
	$config['avatar_max_kb'] 				= '100';

	$config['enable_photos'] 				= 'n';
	$config['photo_max_height'] 			= '200';
	$config['photo_max_width'] 				= '200';
	$config['photo_max_kb'] 				= '200';

	$config['sig_allow_img_upload'] 		= 'n';
	$config['sig_img_max_height'] 			= '80';
	$config['sig_img_max_width'] 			= '480';
	$config['sig_img_max_kb'] 				= '30';
	$config['sig_maxlength'] 				= '500';

	$config['captcha_font'] 				= 'y';
	$config['captcha_rand'] 				= 'y';
	$config['captcha_require_members'] 		= 'n';

	// Database preferences
	$config['pconnect'] 					= 'n';
	$config['enable_db_caching'] 			= 'n';
	$config['enable_sql_caching']   		= 'n'; # y/n
	$config['disable_all_tracking'] 		= 'y'; # y/n

	// Debugging and performance
	$config['debug']                		= $debug;
	$config['show_profiler']        		= $show_profiler;
	$config['template_debugging']   		= $template_debugging;
	$config['email_debug']          		= $email_debug;
	$config['db_debug']						= $db_debug;

	// Stay logged in longer
	$config['cp_session_ttl']         	= 604800; // 7 days

	// Paths & URLs - CONSTANT
	// @TODO - break into two sections 1) Constant Values, 2) MSM Adjustable Values
	$config['site_url'] 					= THIS_SITE_URL 	. '/'; // trailing slash required
	$config['cp_url'] 						= THIS_SITE_URL 	. '/adminck/index.php';
	$config['third_party_path']				= THIS_BASEPATH		. '/addons/';
	$config['theme_folder_url']				= THIS_SITE_URL		. '/themes/';
	$config['theme_folder_path'] 			= THIS_BASEPATH 	. '/themes/';

	$config['captcha_path'] 				= THIS_BASEPATH 	. '/assets/captchas/';
	$config['captcha_url'] 					= THIS_SITE_URL		. '/assets/captchas/';

	// Paths & URLs - MSM Adjustable
	$config['tmpl_file_basepath'] 			= THIS_BASEPATH 	. '/templates/';

	// ??
	$config['prv_msg_upload_path'] 			= THIS_MEMBER_PATH 	. '/pm_attachments';
	$config['avatar_path'] 					= THIS_MEMBER_PATH 	. '/avatars/';
	$config['avatar_url'] 					= THIS_MEMBER_URL 	. '/avatars/';
	$config['photo_path'] 					= THIS_MEMBER_PATH 	. '/photos/';
	$config['photo_url'] 					= THIS_MEMBER_URL 	. '/photos/';
	$config['sig_img_path'] 				= THIS_MEMBER_PATH 	. '/signature_attachments/';
	$config['sig_img_url'] 					= THIS_MEMBER_URL 	. '/signature_attachments/';

} // end if $config

/**************************************************
 SET ENVIRONMENTAL DATABASE SETTINGS
 Make sure we have reached the database.php config file
 When we have, override the settings with the proper environment
***************************************************/
if(isset($db['expressionengine']))
{

	$db['expressionengine']['hostname'] = $db_hostname;
	$db['expressionengine']['username'] = $db_username;
	$db['expressionengine']['password'] = $db_password;
	$db['expressionengine']['database'] = $db_database;

	// Set the database cache for the environment we are in
    $db['expressionengine']['cachedir'] = APPPATH . "cache/db_cache/";

} // end if $db
