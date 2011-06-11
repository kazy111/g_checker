<?php
include 'header.php';

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
      case 2: // stickam
        $ret .= '<a href="http://www.stickam.jp/profile/'.$v['ch_name'].'">'.Jst.'</a> ';
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
  $ch_v = 1; // for multiview
  
  foreach($arrs as $k => $v){
    $programs_raw[] = array($v['type'], $v['ch_name'], $v['optional_id'], $v['thumbnail'], $v['live']);
    $chats_raw[$v['cid']] = array($v['ctype'], $v['room'], $v['topic']);
    if($v['live'] == 't'){
      $live = TRUE;
      $stime = ($stime > strtotime($v['start_time']) && $v['start_time']!='') ? strtotime($v['start_time']) : $stime;
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
        
      case 2: // stickam
        $live_thumb = $v['thumbnail'];
        $ch_skm = $v['ch_name'];
        $ch_v = 5;
        $programs .= '<a href="http://www.stickam.jp/profile/'.$v['ch_name'].'">Jst'.$thumb.'</a> ';
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
  if(!isset($ch_skm))  $ch_skm = '';
  if(!isset($ch_chat)) $ch_chat = '';
  
  $i = $arrs[0];

  $time = $live ? date('m/d H:i', $stime).'開始' : date('m/d H:i', $etime).'終了';
  
  // diff time calc
  $diff = $live ? time() - $stime : time() - $etime;
  $dday = floor($diff / (60*60*24));
  $dhour = floor($diff / (60*60) % 24);
  $dmin = floor($diff / 60 % 60);
  $diff = ($dday>0 ? $dday.'日と': '') . ( ($dhour>0 ? $dhour.'時間' :'').$dmin.'分')
          .($live ? '' : '前');

  // create data for sort
  $data = array();
  $data['sid'] = $i['sid'];
  $data['name'] = $i['name'];
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
  $data['chat'] = implode(' ', $chats);
  $data['topic'] = htmlspecialchars(implode(' ', $topics));
  $data['topic'] = preg_replace('/(https?|ftp)(:\/\/[[:alnum:]\+\$\;\?\.%,!#~*\/:@&=_-]+)/i', '<a class="urllink" href="\\0" target="_blank">&psi;</a>' , $data['topic']);

  // gokusotsu specific topic process
  if($GLOBALS['gokusotsu'] && $data['chat'] == '#tenga15ch'){
    $t = preg_replace('/(jus|17ch):「[^」]*」/us', '', $data['topic']);
    if($t != NULL && $t != '') $data['topic'] = $t;
  }
  
  $data['chats_raw'] = $chats_raw;
  $data['time'] = $time;
  $data['time_data'] = $live ? $stime : $etime;
  $data['diff'] = $diff;
  $data['live'] = $live;
  $data['description'] = $i['description'];
  $data['tag'] = format_tag($i['tag']);
  $data['wiki'] = $i['wiki'];
  $data['wiki_url'] = $GLOBALS['wiki_url'];
  $data['url'] = $i['url'];
  $data['twitter'] = $i['twitter'];
  $data['multi_data'] = '{\'name\':\''.$i['name'].'\',\'chat\':\''.$ch_chat.'\',\'ust\':\''.$ch_ust.'\',\'jus\':\''.$ch_jus.'\',\'skm\':\''.$ch_skm.'\',\'v\':'.$ch_v.'}';

  return $data;
}

function display_list($sort)
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
        return $a['time_data'] < $b['time_data'];
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
    
    global $ontop;
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
        return $a['time_data'] > $b['time_data'];
      } else {
        return FALSE;
      }
    } else {
      if( $b['live'] ){
        return TRUE;
      } else {
        return $a['time_data'] < $b['time_data'];
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
        return $a['time_data'] < $b['time_data'];
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

  $live_num += $GLOBALS['list_extra_number'];
  $st_on = array_slice($streamer_data, 0, $live_num);
  $st_off = array_slice($streamer_data, $live_num);

  // construct streamer output
  $contents_streamer = '';
  $even = FALSE;
  foreach($st_on as $data){
    $data['live_raw'] = $data['live'];
    $data['live'] =  $data['live'] ? 'online' : 'offline';
    $data['even'] = $even;
    $even = !$even;
    $contents_streamer .= $page->get_once('index_streamer'.$GLOBALS['extra'], $data);
  }

  $contents_streamer_off = '';
  foreach($st_off as $data){
    $data['live_raw'] = $data['live'];
    $data['live'] =  $data['live'] ? 'online' : 'offline';
    $data['even'] = $even;
    $even = !$even;
    $contents_streamer_off .= $page->get_once('index_streamer_offline'.$GLOBALS['extra'], $data);
  }

  $contents_theme = '<select id="theme">';
  $themes = $page->get_themes();
  foreach($themes as $t){
    $contents_theme .= '<option value="'.urlencode($t).'"'.(($t == $page->theme)?' selected="selected"':'').'>'.$t.'</option>';
  }
  $contents_theme .= '</select>'
      .'<input type="button" value="変更" onclick="WriteCookie(\'theme\', document.getElementById(\'theme\').options[document.getElementById(\'theme\').selectedIndex].value, 90);location.reload();" />';

  $contents_sort = assoc2select($GLOBALS['sort_assoc'], 'sort', $sort)
      .'<input type="button" value="変更" onclick="WriteCookie(\'sort\', document.getElementById(\'sort\').options[document.getElementById(\'sort\').selectedIndex].value, 90);location.reload();" />';

  // get article section
  $result = $manager->get_articles(5, 0);
  $contents_article = '';
  foreach($result as $arr){
    $contents_article .= $page->get_once('article_item', $arr);
  }

  // get random legend
  $result = $manager->get_random_legend();
  $legend = sanitize_html($result['body']);

  // output page contents
  $data = new Dwoo_Data();
  $data->assign('streamer_online_data', $contents_streamer);
  $data->assign('streamer_offline_data', $contents_streamer_off);
  $data->assign('theme_data', $contents_theme);
  $data->assign('sort_data', $contents_sort);
  $data->assign('link_data', $page->get_once('link', array()));
  $data->assign('site_title', $GLOBALS['site_title']);
  $data->assign('article_data', $contents_article);
  $data->assign('curr_time', time());
  $data->assign('legend', $legend);
  //TODO Update time

  return $data;
}


if(is_old_mobile()){
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: ".$site_url.'/m/');
  exit();
}


$extra = is_mobile() ? '_mobile' : '';

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

$page->set('index'.$extra, display_list($sort));

include 'footer.php';
?>
