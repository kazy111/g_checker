<?php

include_once(dirname(__FILE__) . '/config.php');

if($debug){
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
  ini_set('log_errors', 'Off');
}

include_once(dirname(__FILE__) . '/assoc_define.php');
include_once(dirname(__FILE__) . '/lib.php');
include_once(dirname(__FILE__) . '/classes/dwooAutoload.php');
include_once(dirname(__FILE__) . '/classes/database/PostgreSQLDataManager.php');
include_once(dirname(__FILE__) . '/classes/database/MySQLDataManager.php');
include_once(dirname(__FILE__) . '/classes/page.php');

header('Content-type: text/html; charset=utf-8');

$_dbserver = $db_host . ($db_port ? ':'.$db_port : '');
switch($db_type){
case 1: 
  $manager = new PostgreSQLDataManager($_dbserver, $db_name, $db_user, $db_passwd);
  break;
default:
  $manager = new MySQLDataManager($_dbserver, $db_name, $db_user, $db_passwd);
  break;
}

$page = new Page();

session_start();

// テーマの設定
if(array_key_exists('default_theme', $GLOBALS)){
  $page->theme = $GLOBALS['default_theme'];
}
if(array_key_exists('theme', $_COOKIE)){
  $page->theme = urldecode($_COOKIE['theme']);
}
// TODO theme を cookieに？ sessionに？
// random = 存在しないtheme or nullを設定する




?>
