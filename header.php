<?php

include_once 'config.php';
include_once 'assoc_define.php';
include_once 'lib.php';
include_once 'classes/dwooAutoload.php';
include_once 'classes/db.php';
include_once 'classes/database/PostgreSQLDataManager.php';
include_once 'classes/database/MySQLDataManager.php';
include_once 'classes/page.php';

header('Content-type: text/html; charset=utf-8');

$db = new DB($db_host, $db_name, $db_user, $db_passwd);

$manager = new PostgreSQLDataManager($db_host, $db_name, $db_user, $db_passwd);

$page = new Page();

session_start();


if($_COOKIE['theme'])
  $page->theme = urldecode($_COOKIE['theme']);

// TODO theme ‚ð cookie‚ÉH session‚ÉH
// random = ‘¶Ý‚µ‚È‚¢theme or null‚ðÝ’è‚·‚é




?>
