<?php

include 'header.php';
include_once 'classes/ircbot.php';

set_time_limit(50);


$chs;
$sid_chs;

function log_print($str)
{
  print($str."<br />\n");
}

// driver function (get channels and call check functions)
function check()
{
  global $db, $chs, $sid_chs;
  $sql = 'select s.id as sid, p.id as pid, c.id as cid, live, s.name as name, start_time, '
        .' ch_name, optional_id, room, p.type as ptype, c.type as ctype, topic'
        .' from streamer_table as s, program_table as p, chat_table as c '
        .' where s.id = p.streamer_id '
        .' and c.id = p.chat_id order by s.id;';
  $result = $db->query($sql);
  $chs = array();
  while($arr = $db->fetch($result)){
    $chs[$arr['pid']] = $arr;
  }
  $db->close();

  $sid_chs = array();
  foreach($chs as $k => $v){
    $sid = $v['sid'];
    if(!array_key_exists($sid, $sid_chs)) $sid_chs[$sid] = array();
    $sid_chs[$sid][] = $v;
  }
  
  check_irc();
}



// check irc function
function check_irc()
{
  global $db, $chs, $sid_chs;
  
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

  $db->open();
  foreach($ids as $k => $v){
    if($v == 0)
      $arr = array_key_exists($k, $ust_bot->info) ? $ust_bot->info[$k] : array(0, '');
    else if($v == 1) {
      if($GLOBALS['gokusotsu']){
        $ret = preg_match('/jus:「([^」]*)」/u', $ust_bot->info['#tenga15ch'][1], $match);
        $arr = array(0, ($ret?$match[1]:'配信者募集中！'));
      }else
        $arr = array(0, '');
    }

    log_print('room='.$arr[0].', topic=\''.$arr[1]);
    $chs[$phash[$k]]['topic'] = $arr[1];
    $sql = 'update chat_table set member='.$db->sanitize($arr[0])
          .' , topic=\''.$db->sanitize($arr[1]).'\' where id='.$hash[$k];
    log_print($sql);
    $ret = $db->query($sql);
    log_print(pg_result_error($ret));
  }
  
}

check();

include 'footer.php';

?>

