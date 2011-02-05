<?php

include_once 'config.php';

if($debug){
  error_reporting(E_ALL);
  ini_set('display_errors', 'On');
  ini_set('log_errors', 'Off');
}

include_once 'assoc_define.php';
include_once 'lib.php';
include_once 'classes/dwooAutoload.php';
include_once 'classes/database/PostgreSQLDataManager.php';
include_once 'classes/database/MySQLDataManager.php';
include_once 'classes/page.php';

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


if(array_key_exists('theme', $_COOKIE))
  $page->theme = urldecode($_COOKIE['theme']);

// TODO theme ‚ð cookie‚ÉH session‚ÉH
// random = ‘¶Ý‚µ‚È‚¢theme or null‚ðÝ’è‚·‚é




?>
