<?php

include_once 'config.php';
include_once 'assoc_define.php';
include_once 'lib.php';
include_once 'classes/dwooAutoload.php';
include_once 'classes/db.php';
include_once 'classes/page.php';

header('Content-type: text/html; charset=utf-8');

$db = new DB($db_host, $db_name, $db_user, $db_passwd);

$page = new Page();

session_start();


if($_COOKIE['theme'])
  $page->theme = urldecode($_COOKIE['theme']);

// TODO theme ‚ð cookie‚ÉH session‚ÉH
// random = ‘¶Ý‚µ‚È‚¢theme or null‚ðÝ’è‚·‚é


  function is_mobile () {
    $useragents = array(
      'iPhone',         // Apple iPhone
      'iPod',           // Apple iPod touch
      'Android',        // 1.5+ Android
      'dream',          // Pre 1.5 Android
      'CUPCAKE',        // 1.5+ Android
      'blackberry9500', // Storm
      'blackberry9530', // Storm
      'blackberry9520', // Storm v2
      'blackberry9550', // Storm v2
      'blackberry9800', // Torch
      'webOS',          // Palm Pre Experimental
      'incognito',      // Other iPhone browser
      'webmate'         // Other iPhone browser
    );
    $pattern = '/'.implode('|', $useragents).'/i';
    return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
  }

?>
