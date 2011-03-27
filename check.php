<?php

include 'header.php';
include_once 'tweet.php';
// no cron, every 60 seconds check?
include_once 'classes/JSON.php';

set_time_limit(50);

$ust_apikey = '98F257180583948FC8FAD3042D2A0E7E';
$chs;
$sid_chs;

function log_print($str)
{
  print($str."<br />\n");
}

function start_tweet($data)
{
  global $site_url, $tweet_start;
  if(! $tweet_start) return;
  
  $str = $data['name'].'配信開始！／';
  $hash = $GLOBALS['tweet_footer'];
  $time = date(' [H:i]');
  $topic = preg_replace('/(https?|ftp)(:\/\/[[:alnum:]\+\$\;\?\.%,!#~*\/:@&=_-]+)/i', '$' , $data['topic']);

  $url = ' '.$site_url.'/view.php?id='.$data['sid'];

  $cutlen = 140-strlen($hash)-mb_strlen($str, 'UTF-8')-strlen($url)-strlen($time);
  if(mb_strlen($topic, 'UTF-8') > $cutlen)
    $topic = mb_substr($topic, 0, $cutlen-1, 'UTF-8').'…';
  
  tweet($str.$topic.$url.$time.$hash);
}

function end_tweet($data)
{
  global $tweet_end;
  if(! $tweet_end) return;
  
  $str = $data['name'].'配信終了…／';
  $hash = $GLOBALS['tweet_footer'];
  $time = date(' [H:i]');
  $topic = preg_replace('/(https?|ftp)(:\/\/[[:alnum:]\+\$\;\?\.%,!#~*\/:@&=_-]+)/i', '$' , $data['topic']);

  $cutlen = 140-strlen($hash)-mb_strlen($str, 'UTF-8')-strlen($time);
  if(mb_strlen($topic, 'UTF-8') > $cutlen)
    $topic = mb_substr($topic, 0, $cutlen-1, 'UTF-8').'…';
  
  tweet($str.$topic.$time.$hash);
}


// check integrated live status of other channel
function check_prev_live($pid, $chs)
{
  $ret = FALSE;
  foreach($chs as $c){
    print("check: ".$pid." ".$c['pid']." live: ".$c['live']." cur ret:".$ret."\n");
    if($c['pid'] != $pid)
      $ret |= ($c['live'] == 't' || $c['live'] == '1');
  }
  print($ret." - end\n");
  return $ret;
}


function update_status($pid, $live, $viewer, $change_flag, $thumb)
{
  global $db;

  $sql_live = $live ? 'TRUE' : 'FALSE';
  // construct time SQL
  $sql_time = '';
  if($change_flag){
    $now = date('Y-m-d H:i:s');
    if($live){
      $sql_time = ', start_time = \''.$now.'\' ';
    }else{
      $sql_time = ', end_time = \''.$now.'\' ';
      // TODO add history
    }
  }
  $sql = 'update program_table set live = '.$sql_live.' , viewer = '.$viewer
         . $sql_time.', thumbnail = \''. $thumb .'\', offline_count = 0 where id = '.$pid;
  $db->query($sql);
  log_print($sql);
}

function add_history($pid, $start_time, $end_time)
{
  global $db;
  try{
  $sql = 'insert into  history_table (program_id, start_time, end_time) '
    .' values ('.$pid.', \''.$start_time.'\', \''.$end_time.'\')';
    log_print($sql);
    $db->query($sql);
  } catch (Exception $e) {
    print("例外キャッチ：". $e->getMessage(). "\n");
  }
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
  
  check_ustream();
  check_justin();
  check_stickam();
}


// check ustream function
function check_ustream()
{
  global $manager, $chs, $sid_chs, $ust_apikey;
  
  function live_status($xml_node){
    return $xml_node == 'live' ? TRUE : FALSE;
  }
  function get_ustream_member($login, $chid){
    global $ust_apikey;
    
    $url = 'http://api.ustream.tv/xml/user/'
      .$login.'/listAllChannels?key='.$ust_apikey;
    
    log_print($url);
    $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);

    foreach($xml->results->array as $k => $v){
      if(strcmp($v->id, $chid) == 0)
        return $v->viewersNow;
    }
    return 0;
    /*
    $url = 'http://www.ustream.tv/discovery/live/all?q='.$name;
    $file = file_get_contents($url);
    $pattern = '/<span class="liveViewers">[\s]+([0-9]+) [^<]+<\/span><br\/>[\s]+<span class="totalViews">[^<]+<br \/>[\s]+<\/span>[\s]+<a href="\/user\/'.$name.'"/i';
    $st = preg_match($pattern, $file, $match);
    return $match[1] ? $match[1] : 0;
      */
  }

  function update_ustream($pid, $id, $login, $live_st){
    global $sid_chs, $chs, $manager;
    
    $change_flag = ($chs[$pid]['live']=='t' || $chs[$pid]['live']=='1') ^ $live_st;
    $viewer = $live_st ? get_ustream_member($login, $id) : 0;
    $thumb = 'http://static-cdn2.ustream.tv/livethumb/1_'.$id.'_160x120_b.jpg';
    if($live_st || $change_flag)
      $manager->update_program($pid, $live_st, $viewer, $change_flag, $thumb);
    if($change_flag){
      if($live_st){
        if(!check_prev_live($pid, $sid_chs[$chs[$pid]['sid']]))
          start_tweet($chs[$pid]);
      }else{
        $start_time = $chs[$pid]['start_time'];
        $manager->add_history($pid, $start_time, date('Y-m-d H:i:s'));
        if(!check_prev_live($pid, $sid_chs[$chs[$pid]['sid']]))
          end_tweet($chs[$pid]);
      }
    }
  }
  
  $ids = array();
  $hash = array();
  
  log_print("start checking Ustream.tv...");
  
  foreach($chs as $k => $v){
    if($v['type']==0){
      $hash[$v['ch_name']] = $v['pid'];
      $ids[] = $v['ch_name'];
    }
  }
  $ids = array_chunk($ids, 10);

  foreach($ids as $v){
    
    $url = 'http://api.ustream.tv/xml/channel/'
      .implode(';', $v).'/getInfo?key='.$ust_apikey;
    
    log_print($url);
    try{
      $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
    }catch(Exception $e){
      print $e->getMessage();
      continue;
    }

    if(count($v) > 1){
      if($xml->error != ''){ // error
        log_print('error: '. implode(', ', $v));
        
        // fallback check
        foreach($v as $tv){
          $url = 'http://api.ustream.tv/xml/channel/'.$tv.'/getInfo?key='.$ust_apikey;
          try{
            $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
          }catch(Exception $e){
            print $e->getMessage();
            continue;
          }
          if($xml->error == ''){
            update_ustream($hash[$tv], $xml->results->id, $xml->results->user->userName,
                           live_status($xml->results->status));
          }else{
            log_print('<b>illigal channel:</b> '.$tv);
          }
        }
      } else {
        // normal loop
        foreach($xml->results->array as $res){
          $name = ''.$res->uid;
          update_ustream($hash[$name], $res->result->id, $res->result->user->userName,
                         live_status($res->result->status));
        }
      }
    }else{
      
      if($xml->error != ''){ // error
        log_print('<b>illigal channel:</b> '.$v[0]);
      }else{
        // normal check
        $name = $v[0];
        update_ustream($hash[$name], $xml->results->id, $xml->results->user->userName,
                       live_status($xml->results->status));
      }
    }
  }

  
  log_print('finish checking Ustream.tv.');
}

// check justin function

function check_justin()
{
  global $manager, $chs, $sid_chs;

  $ids = array();
  $hash = array();
  $live = array();
  
  log_print("start checking Justin.tv...");
  
  foreach($chs as $k => $v){
    if($v['type']==1){
      $cn = strtolower($v['ch_name']);
      $hash[$cn] = $v['pid'];
      $ids[] = $cn;
      $live[$v['pid']] = 0;
    }
  }
  $url = 'http://api.justin.tv/api/stream/list.json?channel='.implode(',', $ids);
  
  log_print($url);
  
  try{
    $jsontxt = '';
    $json = new Services_JSON();
    // open URL
    $fp = fopen($url, 'r');
    while (! feof($fp)) {
      $jsontxt .= fread($fp, 1024) or '';
    }
    fclose($fp);

    $xml = $json->decode($jsontxt);
  }catch(Exception $e){
    print $e->getMessage();
    return;
  }

  
  if($xml){
    foreach($xml as $res){
      //print_r($res);
      $ch = $res->channel;
      $name = ''.$ch->login;
      if($name == '') $name = ''.$ch->channel;
      if($name == '') continue; // error
      $viewer = $res->stream_count;
      $pid = $hash[$name];
      $change_flag = $chs[$pid]['live'] == 'f' || $chs[$pid]['live'] == '0' || $chs[$pid]['live'] == '';
      $thumb = 'http://static-cdn.justin.tv/previews/live_user_'.$name.'-320x240.jpg';
      
      print "\nname: ".$name." - ".$change_flag."\n";
      $manager->update_program($pid, TRUE, $viewer, $change_flag, $thumb);
      
      if($change_flag && !check_prev_live($pid, $sid_chs[$chs[$pid]['sid']]))
        start_tweet($chs[$pid]);
      
      $live[$pid] = 1;
    }
  }
  
  foreach($live as $k => $v){
    if( $v == 0 && ($chs[$k]['live']=='t' || $chs[$k]['live']=='1') ){
      if(intval($chs[$k]['offline_count']) > 1){
        //$thumb = 'http://static-cdn.justin.tv/previews/live_user_'.$name.'-320x240.jpg';
        
        $manager->update_program($k, FALSE, 0, 1, '');
        
        $start_time = $chs[$k]['start_time'];
        $manager->add_history($k, $start_time, date('Y-m-d H:i:s'));
        if(!check_prev_live($k, $sid_chs[$chs[$k]['sid']]))
          end_tweet($chs[$k]);
      }else{
        $manager->increment_offline_count($k);
      }
    }
  }
  
  log_print('finish checking Justin.tv.');
}


// check stickam function
function check_stickam()
{
  global $manager, $chs, $sid_chs;

  $ids = array();
  $hash = array();
  
  log_print("start checking Stickam.jp...");
  
  foreach($chs as $k => $v){
    if($v['type']==2){
      $hash[$v['ch_name']] = $v['pid'];
      $ids[] = $v['ch_name'];
    }
  }

  foreach($ids as $name){
    
    $url = 'http://api.stickam.jp/api/search/user?live=1&name=' . $name;
    
    log_print($url);
    try{
      $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
    }catch(Exception $e){
      print $e->getMessage();
      continue;
    }

    $live_st = FALSE;
    $viewer = 0;
    $thumb = '';

    // status judge
    switch($xml->all){
    case 0:
      break;
    case 1:
      if($xml->user->user_name == $name){
        //set online
        $live_st = TRUE;
        $viewer = $xml->user->live_current_views;
        $thumb = $xml->user->profile_image;
      }
      break;
    default:
      for($i = 0; $i < $xml->all; $i++){
        if($xml->user[$i]->user_name == $name){
          //set online
          $live_st = TRUE;
          $viewer = $xml->user[$i]->live_current_views;
          $thumb = $xml->user[$i]->profile_image;
        }
      }
    }

    // save status change
    $pid = $hash[$name];
    $change_flag = ($chs[$pid]['live']=='t' || $chs[$pid]['live']=='1') ^ $live_st;
    
    if($live_st || $change_flag)
      $manager->update_program($pid, $live_st, $viewer, $change_flag, $thumb);
    if($change_flag){
      if($live_st){
        if(!check_prev_live($pid, $sid_chs[$chs[$pid]['sid']]))
          start_tweet($chs[$pid]);
      }else{
        $start_time = $chs[$pid]['start_time'];
          $manager->add_history($pid, $start_time, date('Y-m-d H:i:s'));
        if(!check_prev_live($pid, $sid_chs[$chs[$pid]['sid']]))
          end_tweet($chs[$pid]);
      }
    }
  }

  log_print('finish checking Stickam.jp.');
}

check();

include 'footer.php';

?>
