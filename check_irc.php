<?php

include 'header.php';
include_once 'classes/ircbot.php';


$chs;
$sid_chs;

function log_print($str)
{
  print($str."<br />\n");
}

// driver function (get channels and call check functions)
function check()
{
  global $manager, $chs, $sid_chs;

  $sid_chs = $manager->get_index_datas();

  $chs = array();
  foreach($sid_chs as $arr){
    foreach($arr as $p){
      $chs[$p['pid']] = $p;
    }
  }

  check_irc();
}


// check irc function
function check_irc()
{
  global $manager, $chs, $sid_chs;

  $ids = array();
  $hash = array();
  $phash = array();
  $rooms = array();
  $mib = array();
  foreach($chs as $ch){
    $r = strtolower($ch['room']);
    $rooms[$r] = 0;
    $ids[$r] = $ch['ctype'];
    $hash[$r] = $ch['cid'];
    $phash[$r] = $ch['pid'];
    if($ch['ctype'] == 1)
      $mib[] = $r;
  }

  //Sample connection data.
  $config = array(
    'server' => 'c.ustream.tv', 
    'port'   => 6667, 
    'channel' => '#kazy',
    'name'   => 'ustreamer-01949', 
    'nick'   => 'ustreamer-01949', 
    'pass'   => '', 
    );
  $ust_bot = new IRCBot($config, $rooms);

  /*
  $config = array( 
    'server' => 'irc.mibbit.com', 
    'port'   => 6667, 
    'channel' => '#kazy111',
    'name'   => 'ust-04281', 
    'nick'   => 'ust-04281', 
    'pass'   => '',
    );
  $jus_bot = new IRCBot_i($config, $mib);
    */

  foreach($ids as $k => $v){
    if($v == 0){
      $arr = array_key_exists($k, $ust_bot->info) ? $ust_bot->info[$k] : array(0, '');
    }else if($v == 1) {
      if($GLOBALS['gokusotsu']){
        $ret = preg_match('/jus:「([^」]*)」/u', $ust_bot->info['#tenga15ch'][1], $match);
        $arr = array(0, ($ret?$match[1]:'配信者募集中！'));
      }else
        $arr = array(0, '');
    }

    log_print('room='.$arr[0].', topic=\''.$arr[1]);
    $manager->update_chat($hash[$k], $manager->sanitize($arr[0]), $manager->sanitize($arr[1]));
  }
  
}

log_print('irc check start');
check();

include 'footer.php';

?>

