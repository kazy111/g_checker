<?php

include 'header.php';
include 'index_content.php';

if(is_old_mobile()){
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: ".$site_url.'/m/');
  exit();
}

$GLOBALS['extra'] = is_mobile() ? 'mobile_' : '';

$sort = 'random'; // viewer, time
if(array_key_exists('default_sort', $GLOBALS) && array_key_exists($GLOBALS['default_sort'], $sort_assoc)){
  $sort = $GLOBALS['default_sort'];
}
if(array_key_exists('sort', $_COOKIE) && array_key_exists($_COOKIE['sort'], $sort_assoc)){
  $sort = $_COOKIE['sort'];
}

// play sound (probability originally 1/350)
if(mt_rand(1, 128) == 11){
  $page->add_header('<script type="text/javascript" src="'.$site_url.'/js/swfobject.js"></script>');
  $page->add_header('<script type="text/javascript" src="'.$site_url.'/js/playsound.js"></script>');
}

$page->set($GLOBALS['extra'].'index', display_list($sort, $GLOBALS['extra']));


include 'footer.php';

?>
