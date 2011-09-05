<?php
include 'header.php';


$p = (array_key_exists('p', $_GET) && validate_num($_GET['p'])) ? $_GET['p'] : 0;
$limit = (array_key_exists('limit', $_GET) && validate_num($_GET['limit'])) ? $_GET['limit'] : $page_size;
// function 

function fold_numbers($arrs, $key, $value)
{
  $hash = array();
  $ret = 0;
  foreach($arrs as $k => $v){
    if(!array_key_exists($v[$key], $hash)){
      $ret += $v[$value];
      $hash[$v[$key]] = true;
    }
  }
  return $ret;
}

function create_program_list($arrs)
{
  $ret = '';
  $live = 0;
  foreach($arrs as $k => $v){
    $live |= $v['live'] == 't';
    if($v['live'] == 't'){
      switch($v['type']){
      case 0: //ustream
        $ret .= '<a href="http://www.ustream.tv/channel/'.$v['ch_name'].'">'.Ust.'</a> ';
        break;
      case 1: // justin
        $ret .= '<a href="http://www.justin.tv/'.$v['ch_name'].'">'.Jst.'</a> ';
        break;
      }
    }
  }
  return $ret;
}

function get_streamer_data($arrs)
{
  $viewers = fold_numbers($arrs, 'pid', 'viewer');
  $members = fold_numbers($arrs, 'cid', 'member');
  
  
  $live = FALSE;
  $stime = 1000000000000;
  $etime = 0;

  $programs = '';
  $programs_raw = array();
  $chats_raw = array();

  $ch_ust;
  $ch_jus;
  $ch_chat;
  $ch_v = 1;
  
  foreach($arrs as $k => $v){
    $programs_raw[] = array($v['type'], $v['ch_name'], $v['optional_id'], $v['thumbnail'], $v['live']);
    $chats_raw[$v['cid']] = array($v['ctype'], $v['room'], $v['topic']);
    if($v['live'] == 't'){
      $live = TRUE;
      $stime = $stime > strtotime($v['start_time']) ? strtotime($v['start_time']) : $stime;
      $thumb = '<img src="'.$v['thumbnail'].'" width="320" height="240" class="thumb"/>';
      switch($v['type']){
      case 0: //ustream
        if(!isset($live_thumb)){ $live_thumb = $v['thumbnail']; }
        $ch_ust = $v['optional_id'];
        $ch_v = 1;
        $programs .= '<a href="http://www.ustream.tv/channel/'.$v['ch_name'].'">Ust'.$thumb.'</a> ';
        break;
      case 1: // justin
        $live_thumb = $v['thumbnail'];
        $ch_jus = $v['ch_name'];
        $ch_v = 3;
        $programs .= '<a href="http://www.justin.tv/'.$v['ch_name'].'">Jst'.$thumb.'</a> ';
        break;
      }
      $ch_chat = substr($v['room'],1);
    }else {
      if(!isset($live_thumb)) $live_thumb = $v['thumbnail'];
      switch($v['type']){
      case 0: //ustream
        if(!isset($ch_ust)) $ch_ust = $v['optional_id'];  break;
      case 1: // justin
        if(!isset($ch_jus)) $ch_jus = $v['ch_name']; break;
      }
      if(!isset($ch_chat)) $ch_chat = substr($v['room'],1);
      $etime = $etime < strtotime($v['end_time']) ? strtotime($v['end_time']) : $etime;
    }
  }
  if(!isset($live_thumb)) $live_thumb = '';
  if(!isset($ch_ust))  $ch_ust = '';
  if(!isset($ch_jus))  $ch_jus = '';
  if(!isset($ch_chat)) $ch_chat = '';
  
  $i = $arrs[0];

  $time = $live ? date('m/d H:i', $stime).'開始' : date('m/d H:i', $etime).'終了';
  
  // diff time calc
  $diff = $live ? time() - $stime : time() - $etime;
  $dday = floor($diff / (60*60*24));
  $dhour = floor($diff / (60*60));
  $dmin = floor($diff / 60 % 60);
  $diff = ($dday>0 ? $dday.'日': ( ($dhour>0 ? $dhour.'時間' :'').$dmin.'分'))
          .($live ? '' : '前');

  // create data for sort
  $data = array();
  $data['sid'] = $i['sid'];
  $data['name'] = addslashes($i['name']);
  $data['viewer'] = $viewers;
  $data['member'] = $members;
  $data['program'] = $programs;
  $data['program_raw'] = $programs_raw;
  $data['thumbnail'] = $live_thumb;
  $chats = array(); $topics = array();
  foreach($chats_raw as $c){
    $chats[] = $c[1];
    $topics[] = $c[2];
  }
  $data['chat'] = addslashes(implode(' ', $chats));
  $data['topic'] = htmlspecialchars(implode(' ', $topics));
  $data['topic'] = addslashes(preg_replace('/(https?|ftp)(:\/\/[[:alnum:]\+\$\;\?\.%,!#~*\/:@&=_-]+)/i', '<a class="urllink" href="\\0" target="_blank">&psi;</a>' , $data['topic']));
  
  if($GLOBALS['gokusotsu'] && $data['chat'] == '#tenga15ch'){
    $t = preg_replace('/(jus|17ch):「[^」]*」/us', '', $data['topic']);
    if($t != NULL && $t != '') $data['topic'] = $t;
  }
  $data['chats_raw'] = $chats_raw;
  $data['time'] = $time;
  $data['time_raw'] = $live ? $stime : $etime;
  $data['start_raw'] = $stime;
  $data['end_raw'] = $etime;
  $data['diff'] = $diff;
  $data['live'] = $live;
  $data['wiki'] = $i['wiki'];
  $data['twitter'] = $i['twitter'];
  $data['multi_data'] = '{\'name\':\''.$i['name'].'\',\'chat\':\''.$ch_chat.'\',\'ust\':\''.$ch_ust.'\',\'jus\':\''.$ch_jus.'\',\'v\':'.$ch_v.'}';

  return $data;
}

function display_list($sort, $p, $limit)
{
  global $ontop, $page;
  // streamers compare function for sort
  function cmp_viewer( $a, $b ){
    global $ontop;
    if( in_array($a['sid'], $ontop) && !in_array($b['sid'], $ontop) ){
      return FALSE;
    }else if( !in_array($a['sid'], $ontop) && in_array($b['sid'], $ontop) ){
      return TRUE;
    }else if( in_array($a['sid'], $ontop) && in_array($b['sid'], $ontop) ){
      return $a['sid'] > $b['sid'];
    }
    
    if( $a['live'] ){
      if( $b['live'] ){
        return $a['viewer'] < $b['viewer'];
      } else {
        return FALSE;
      }
    } else {
      if( $b['live'] ){
        return TRUE;
      } else {
        return $a['time_raw'] < $b['time_raw'];
      }
    }
  }
  // name
  function cmp_name( $a, $b ){
    global $ontop;
    if( in_array($a['sid'], $ontop) && !in_array($b['sid'], $ontop) ){
      return FALSE;
    }else if( !in_array($a['sid'], $ontop) && in_array($b['sid'], $ontop) ){
      return TRUE;
    }else if( in_array($a['sid'], $ontop) && in_array($b['sid'], $ontop) ){
      return $a['sid'] > $b['sid'];
    }
    
    if( in_array($a['sid'], $ontop) && !in_array($a['sid'], $ontop) ){
      return FALSE;
    }
    if( !in_array($a['sid'], $ontop) && in_array($a['sid'], $ontop) ){
      return TRUE;
    }
    
    if( $a['live'] ){
      if( $b['live'] ){
        return strcmp($a['name'], $b['name']);
      } else {
        return FALSE;
      }
    } else {
      if( $b['live'] ){
        return TRUE;
      } else {
        return strcmp($a['name'], $b['name']);
      }
    }
  }
  // time
  function cmp_time( $a, $b ){
    global $ontop;
    if( in_array($a['sid'], $ontop) && !in_array($b['sid'], $ontop) ){
      return FALSE;
    }else if( !in_array($a['sid'], $ontop) && in_array($b['sid'], $ontop) ){
      return TRUE;
    }else if( in_array($a['sid'], $ontop) && in_array($b['sid'], $ontop) ){
      return $a['sid'] > $b['sid'];
    }
    
    global $ontop;
    if( in_array($a['sid'], $ontop) && !in_array($a['sid'], $ontop) ){
      return FALSE;
    }
    if( !in_array($a['sid'], $ontop) && in_array($a['sid'], $ontop) ){
      return TRUE;
    }
    
    if( $a['live'] ){
      if( $b['live'] ){
        return $a['start_raw'] > $b['start_raw'];
      } else {
        return FALSE;
      }
    } else {
      if( $b['live'] ){
        return TRUE;
      } else {
        return $a['time_raw'] < $b['time_raw'];
      }
    }
  }
  // random
  srand(time());
  function cmp_random( $a, $b ){
    global $ontop;
    if( in_array($a['sid'], $ontop) && !in_array($b['sid'], $ontop) ){
      return FALSE;
    }else if( !in_array($a['sid'], $ontop) && in_array($b['sid'], $ontop) ){
      return TRUE;
    }else if( in_array($a['sid'], $ontop) && in_array($b['sid'], $ontop) ){
      return $a['sid'] > $b['sid'];
    }

    if( $a['live'] ){
      if( $b['live'] ){
        return rand()%2;
      } else {
        return FALSE;
      }
    } else {
      if( $b['live'] ){
        return TRUE;
      } else {
        return $a['time_raw'] < $b['time_raw'];
      }
    }
  }

  global $manager;
  $list = $manager->get_index_datas();

  $streamer_data = array();
  $live_num  = 0;
  foreach($list as $k => $v){
    $temp_d = get_streamer_data($v);
    $streamer_data[] = $temp_d;
    if($temp_d['live'] || in_array($temp_d['sid'], $ontop) ) $live_num++;
  }
  usort($streamer_data, 'cmp_'.$sort);

  if($p < 1){
    $st = array_slice($streamer_data, 0, $live_num);
  }else{
    $st = array_slice($streamer_data, $live_num + (($p-1) * $limit), $limit);
  }

  // construct streamer output
  $contents_streamer = array();
  $even = FALSE;
  foreach($st as $data){
    $data['live_raw'] = $data['live'];
    $data['live'] =  $data['live'] ? 'online' : 'offline';
    $data['even'] = $even;
    $even = !$even;
    $contents_streamer[] = $page->get_once('json_item', $data);
  }
  $contents_streamer = implode(',', $contents_streamer);


  // output page contents
  $data = new Dwoo_Data();
  $data->assign('item_data', $contents_streamer);
  $data->assign('site_title', $GLOBALS['site_title']);

  return $data;
}


$sort = 'random'; // viewer, time
if(array_key_exists('sort', $_COOKIE) && array_key_exists($_COOKIE['sort'], $sort_assoc)){
  $sort = $_COOKIE['sort'];
}

print($page->get_once('json', display_list($sort, $p, $limit)));


//include 'footer.php';
?>
