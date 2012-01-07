<?php

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
  global $service_abb_assoc;
  
  $ret = '';
  $live = 0;
  foreach($arrs as $k => $v){
    $live |= $v['live'] == 't';
    if($v['live'] == 't'){
      $ret .= '<a href="'.get_service_url($v['type'], $v['ch_name'], $v['optional_id']).'">'.$service_abb_assoc[$v['type']].'</a> ';
    }
  }
  return $ret;
}

function get_streamer_data($arrs, $extra)
{
  global $service_abb_assoc;
  
  $viewers = fold_numbers($arrs, 'pid', 'viewer');
  $members = fold_numbers($arrs, 'cid', 'member');
  
  
  $live = FALSE;
  $stime = 1000000000000;
  $etime = 0;

  $programs = '';
  $programs_raw = array();
  $chats_raw = array();
  $titles_raw = array();

  // channel name array (key is type no.)
  $ch_chat;
  $ch_name = array();
  for($i = 0; $i < count($GLOBALS['service_assoc']); $i++){
    $ch_name[(string)$i] = '';
  }
  
  $ch_v = 1; // for multiview
  
  foreach($arrs as $k => $v){
    $programs_raw[] = array($v['type'], $v['ch_name'], $v['optional_id'], $v['thumbnail'], $v['live'],
                            $service_abb_assoc[$v['type']],
                            get_archive_url($v['type'],$v['ch_name'],$v['optional_id']) );
    $chats_raw[$v['cid']] = array($v['ctype'], $v['room'], $v['topic']);
    $titles_raw[] = $v['title'];
    if($v['live'] == 't'){
      $live = TRUE;
      $stime = ($stime > strtotime($v['start_time']) && $v['start_time']!='') ? strtotime($v['start_time']) : $stime;
      $thumb = '<img src="'.$v['thumbnail'].'" width="320" class="thumb"/>';
      $programs .= '<a href="'.get_service_url($v['type'], $v['ch_name'], $v['optional_id']).'">'.$service_abb_assoc[$v['type']].$thumb.'</a> ';
      $ch_name[$v['type']] = $v['ch_name'];
      switch($v['type']){
      case 0: //ustream
        if(!isset($live_thumb)){ $live_thumb = $v['thumbnail']; }
        $ch_name[$v['type']] = $v['optional_id'];
        $ch_v = 1;
        break;
      case 1: // justin
        $live_thumb = $v['thumbnail'];
        $ch_v = 3;
        break;
        
      case 2: // stickam
        $live_thumb = $v['thumbnail'];
        $ch_v = 5;
        break;
      case 3: // nicolive
        $live_thumb = $v['thumbnail'];
        break;
      case 5: // own3d
        $live_thumb = $v['thumbnail'];
        $ch_v = 7;
        break;
      }
      $ch_chat = substr($v['room'],1);
    }else {
      if(!isset($live_thumb)) $live_thumb = $v['thumbnail'];
      if(!isset($ch_chat)) $ch_chat = substr($v['room'],1);
      if($ch_name[$v['type']] == ''){
        $ch_name[$v['type']] = $v['ch_name'];
        switch($v['type']){
        case 0: //ustream
          $ch_name[$v['type']] = $v['optional_id']; break;
        }
        $ch_chat = substr($v['room'],1); // ch 上書き時は常にチャットも上書き
      }
      $etime = $etime < strtotime($v['end_time']) ? strtotime($v['end_time']) : $etime;
    }
  }

  if(!isset($live_thumb)) $live_thumb = '';
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
  // when topic is empty, alter text to program title
  if(trim($data['topic']) == ''){
    $data['topic'] = htmlspecialchars(implode(' ', $titles_raw));
  }

  // gokusotsu specific topic process
  if($GLOBALS['gokusotsu'] && $data['chat'] == '#tenga15ch'){
    $t = preg_replace('/(jus|17ch):「[^」]*」/us', '', $data['topic']);
    if($t != NULL && $t != '') $data['topic'] = $t;
  }
  
  $data['chats_raw'] = $chats_raw;
  $data['time'] = $time;
  $data['time_data'] = $live ? $stime : $etime;
  $data['time_rfc'] = date(DATE_RFC2822, $live ? $stime : $etime);
  $data['diff'] = $diff;
  $data['live'] = $live;
  $data['description'] = $i['description'];
  $data['tag'] = format_tag($i['tag']);
  $data['wiki'] = $i['wiki'];
  $data['wiki_url'] = $GLOBALS['wiki_url'];
  $data['url'] = $i['url'];
  $data['twitter'] = $i['twitter'];
  $data['multi_data'] = '{\'name\':\''.$i['name'].'\',\'chat\':\''.$ch_chat.'\',\'ust\':\''.$ch_name['0']
    .'\',\'jus\':\''.$ch_name['1'].'\',\'stk\':\''.$ch_name['2'].'\',\'nico\':\''.$ch_name['3'].'\',\'twc\':\''.$ch_name['4'].'\',\'v\':'.$ch_v.'}';

  return $data;
}

function display_list($sort, $extra)
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
    $temp_d = get_streamer_data($v, $extra);
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
    $contents_streamer .= $page->get_once($extra.'index_streamer', $data);
  }

  $contents_streamer_off = '';
  foreach($st_off as $data){
    $data['live_raw'] = $data['live'];
    $data['live'] =  $data['live'] ? 'online' : 'offline';
    $data['even'] = $even;
    $even = !$even;
    $contents_streamer_off .= $page->get_once($extra.'index_streamer_offline', $data);
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
    $contents_article .= $page->get_once($extra.'article_item', $arr);
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
  $data->assign('link_data', $page->get_once($extra.'link', array()));
  $data->assign('site_title', $GLOBALS['site_title']);
  $data->assign('article_data', $contents_article);
  $data->assign('curr_time', time());
  $data->assign('legend', $legend);
  //TODO Update time

  return $data;
}



?>
