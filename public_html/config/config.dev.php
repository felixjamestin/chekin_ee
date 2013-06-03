<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

## ------------------------------------------------------------
## DEBUG VARIABLES
## ------------------------------------------------------------

# debug values
# 0 = no errors 
# 1 = errors for Super Admins 
# 2 = errors shown to everyone in the world
$debug 				= '1'; 
$show_profiler 		= 'n'; # y/n
$template_debugging = 'n'; # y/n
$email_debug 		= 'n'; # y/n
$db_debug 			= 'n'; # y/n

## ------------------------------------------------------------
## DATABASE INFO
## ------------------------------------------------------------

$db_hostname = '';
$db_username = '';
$db_password = '';
$db_database = '';

## ------------------------------------------------------------
## GLOBAL VARIABLES - For this environment
## ------------------------------------------------------------

$env_global_vars = array();
// $env_global_vars['global:varaible_name'] = '';
// $env_global_vars['global:varaible_name'] = '';



/* End of file config.dev.php */