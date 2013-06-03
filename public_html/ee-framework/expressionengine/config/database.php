<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$active_group = 'expressionengine';
$active_record = TRUE;

$db['expressionengine']['hostname'] = 'localhost';
$db['expressionengine']['username'] = 'arunhone_chekdev';
$db['expressionengine']['password'] = 'chekDev!';
$db['expressionengine']['database'] = 'arunhone_chekin';
$db['expressionengine']['dbdriver'] = 'mysql';
$db['expressionengine']['pconnect'] = FALSE;
$db['expressionengine']['dbprefix'] = 'exp_';
$db['expressionengine']['swap_pre'] = 'exp_';
$db['expressionengine']['db_debug'] = TRUE;
$db['expressionengine']['cache_on'] = FALSE;
$db['expressionengine']['autoinit'] = FALSE;
$db['expressionengine']['char_set'] = 'utf8';
$db['expressionengine']['dbcollat'] = 'utf8_general_ci';
$db['expressionengine']['cachedir'] = 'E:\\Work\\www\\chekin\\system/expressionengine/cache/db_cache/';

/*
|--------------------------------------------------------------------------
| EE Config Override
|--------------------------------------------------------------------------
|
| pull in a customized config bootstrap file
| 
*/
require(realpath(dirname(__FILE__) . '/../../../config/config.php'));


/* End of file database.php */
/* Location: ./system/expressionengine/config/database.php */