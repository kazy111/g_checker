<?php

include 'header.php';
include_once 'tweet.php';
// no cron, every 60 seconds check?
include_once 'classes/JSON.php';

$cavetube_devkey = '5C7FF292491441848B6D00FBA149DB00';
$ust_apikey = '98F257180583948FC8FAD3042D2A0E7E';
$chs;
$sid_chs;

function log_print($str)
{
  print($str."<br />\n");
}

function start_tweet($data, $topic_flag = TRUE)
{
  global $site_url, $tweet_start;
  if(! $tweet_start) return;
  
  $str = $data['name'].'配信開始！';
  $hash = $GLOBALS['tweet_footer'];
  $time = date(' [H:i]');
  if($topic_flag){
    $topic = preg_replace('/(https?|ftp)(:\/\/[[:alnum:]\+\$\;\?\.%,!#~*\/:@&=_-]+)/i', '$' , '／'
                          .(trim($data['topic'])!='' ? trim($data['topic']) : trim($data['title'])));
  }else{
    $topic = '';
  }

  $check = $topic . ' ' . $data['name'];
  if($GLOBALS['limit_keywords'] != '' && preg_match('/'.$GLOBALS['limit_keywords'].'/', $check) == 0 ) {
    // when set limit keywords, tweet only keyword including
    return;
  }

  $url = ' '.$site_url.'/view.php?id='.$data['sid'];

  $cutlen = 140-strlen($hash)-mb_strlen($str, 'UTF-8')-strlen($url)-strlen($time);
  if(mb_strlen($topic, 'UTF-8') > $cutlen)
    $topic = mb_substr($topic, 0, $cutlen-1, 'UTF-8').'…';
  
  tweet($str.$topic.$url.$time.$hash);
}

function end_tweet($data, $topic_flag = TRUE)
{
  global $tweet_end;
  if(! $tweet_end) return;
  
  $str = $data['name'].'配信終了…';
  $hash = $GLOBALS['tweet_footer'];
  $time = date(' [H:i]');
  if($topic_flag){
    $topic = preg_replace('/(https?|ftp)(:\/\/[[:alnum:]\+\$\;\?\.%,!#~*\/:@&=_-]+)/i', '$' , '／'
                          .(trim($data['topic'])!='' ? trim($data['topic']) : trim($data['title'])));
  }else{
    $topic = '';
  }

  $check = $topic . ' ' . $data['name'];
  if($GLOBALS['limit_keywords'] != '' && preg_match('/'.$GLOBALS['limit_keywords'].'/', $check) == 0 ) {
    // when set limit keywords, tweet only keyword including
    return;
  }

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

  
  // clear temporary streamers
  $manager->clear_temporary();
  
  check_ustream();
  check_justin();
  //check_twitch();
  check_stickam();
  check_nicolive_official();
  check_nicolive();
  check_twitcasting();
  check_own3d();
  check_livetube();
  check_cavetube();
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
    try{
      $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
      if(! $xml ) throw new Exception('URL open failed : '.$url);
    }catch(Exception $e){
      return 0;
    }

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

  function update_ustream($pid, $id, $login, $live_st, $title){
    global $sid_chs, $chs, $manager;
    
    $change_flag = ($chs[$pid]['live']=='t' || $chs[$pid]['live']=='1') ^ $live_st;
    $viewer = $live_st ? get_ustream_member($login, $id) : 0;
    $thumb = 'http://static-cdn2.ustream.tv/i/channel/live/1_'.$id.',192x108,b.jpg';
    $title = str_replace(array("\r\n", "\n", "\r"), ' ', $title);
    if($live_st || $change_flag)
      log_print("<b>name:</b> ".$login." / ".$viewer);
    
    if(mb_strlen($title, 'UTF-8') > 255)
      $title = mb_substr($title, 0, 255-1, 'UTF-8').'…';
    $manager->update_program($pid, $live_st, $viewer, $change_flag, $thumb, ($live_st?$title:''));
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
      if(! $xml ) throw new Exception('URL open failed : '.$url);
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
            if(! $xml ) throw new Exception('URL open failed : '.$url);
          }catch(Exception $e){
            print $e->getMessage();
            continue;
          }
          if($xml->error == ''){
            update_ustream($hash[$tv], $xml->results->id, $xml->results->user->userName,
                           live_status($xml->results->status), $xml->result->title.' / '.$xml->result->description);
          }else{
            log_print('<b>illigal channel:</b> '.$tv);
          }
        }
      } else {
        // normal loop
        foreach($xml->results->array as $res){
          $name = ''.$res->uid;
          update_ustream($hash[$name], $res->result->id, $res->result->user->userName,
                         live_status($res->result->status), $res->result->title.' / '.$res->result->description);
        }
      }
    }else{
      
      if($xml->error != ''){ // error
        log_print('<b>illigal channel:</b> '.$v[0]);
      }else{
        // normal check
        $name = $v[0];
        update_ustream($hash[$name], $xml->results->id, $xml->results->user->userName,
                       live_status($xml->results->status), $xml->result->title.' / '.$xml->result->description);
      }
    }
  }

  
  log_print('finish checking Ustream.tv.');
}

// check justin function
function get_justin_viewers($id){
  $url = 'http://api.justin.tv/api/stream/summary.json?channel='.$id;
    log_print($url);
    
    try{
      $jsontxt = '';
      $json = new Services_JSON();
      // open URL
      $fp = fopen($url, 'r');
      if(! $fp ) throw new Exception('URL open failed : '.$url);
      while (! feof($fp)) {
        $jsontxt .= fread($fp, 1024) or '';
      }
      fclose($fp);

      $obj = $json->decode($jsontxt);
      return $obj->viewers_count;
    }catch(Exception $e){
      print $e->getMessage();
      return 0;
    }
}
function check_justin()
{
  global $manager, $chs, $sid_chs;

  $ids = array();
  $hash = array();
  $live = array();
  
  log_print("start checking Justin.tv...");
  
  foreach($chs as $k => $v){
    if($v['type']==1 || $v['type']==8){
      $cn = strtolower($v['ch_name']);
      $hash[$cn] = $v['pid'];
      $ids[] = $cn;
      $live[$v['pid']] = 0;
    }
  }

  if ( count($ids) > 0 ) {
    $url = 'http://api.justin.tv/api/stream/list.json?channel='.implode(',', $ids);
  
    log_print($url);
    
    try{
      $jsontxt = '';
      $json = new Services_JSON();
      // open URL
      $fp = fopen($url, 'r');
      if(! $fp ) throw new Exception('URL open failed : '.$url);
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
        $viewer = $res->channel_count;
        //$viewer = get_justin_viewers($name);
        $pid = $hash[$name];
        $change_flag = $chs[$pid]['live'] == 'f' || $chs[$pid]['live'] == '0' || $chs[$pid]['live'] == '';
        $thumb = 'http://static-cdn.jtvnw.net/previews-ttv/live_user_'.$name.'-320x200.jpg';
        log_print("<b>name:</b> ".$name." / ".$viewer);
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
  }
  
  
  log_print('finish checking Justin.tv.');
}


// check twitch function

function check_twitch()
{
  global $manager, $chs, $sid_chs;

  $ids = array();
  $hash = array();
  $live = array();
  
  log_print("start checking Twitch.tv...");
  
  foreach($chs as $k => $v){
    if($v['type']==8){
      $cn = strtolower($v['ch_name']);
      $hash[$cn] = $v['pid'];
      $ids[] = $cn;
      $live[$v['pid']] = 0;
    }
  }

  if ( count($ids) > 0 ) {
    $url = 'https://api.twitch.tv/kraken/streams?channel='.implode(',', $ids);
  
    log_print($url);
    
    try{
      $jsontxt = '';
      $json = new Services_JSON();
      // open URL
      $fp = fopen($url, 'r');
      if(! $fp ) throw new Exception('URL open failed : '.$url);
      while (! feof($fp)) {
        $jsontxt .= fread($fp, 1024) or '';
      }
      fclose($fp);

      $xml = $json->decode($jsontxt);
    }catch(Exception $e){
      print $e->getMessage();
      continue;
    }

    if($xml->streams){
      foreach($xml->streams as $res){
        //print_r($res);
        if ($res && $res->channel) {
          $name = $res->channel->name;
          $viewer = $res->viewers;
        }else{
          $name = '';
        }
        if($name == '') continue; // error
        
        $pid = $hash[$name];
        $pid = $hash[$name];
        $change_flag = $chs[$pid]['live'] == 'f' || $chs[$pid]['live'] == '0' || $chs[$pid]['live'] == '';
        $thumb = 'http://static-cdn.jtvnw.net/previews-ttv/live_user_'.$name.'-320x200.jpg';
        log_print("<b>name:</b> ".$name." / ".$viewer);
        $manager->update_program($pid, TRUE, $viewer, $change_flag, $thumb);
        
        if($change_flag && !check_prev_live($pid, $sid_chs[$chs[$pid]['sid']]))
          start_tweet($chs[$pid]);
        
        $live[$pid] = 1;
      }
    }
    
    foreach($live as $k => $v){
      if( $v == 0 && ($chs[$k]['live']=='t' || $chs[$k]['live']=='1') ){
        if(intval($chs[$k]['offline_count']) > 1){
          
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
  }
  
  
  log_print('finish checking Twitch.tv.');
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
      if(! $xml ) throw new Exception('URL open failed : '.$url);
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
      
      log_print("<b>name:</b> ".$name." / ".$viewer);
      $manager->update_program($pid, $live_st, $viewer, $change_flag, $thumb);
    if($change_flag){
      if($live_st){
        if(!check_prev_live($pid, $sid_chs[$chs[$pid]['sid']]))
          start_tweet($chs[$pid]);
      }else{
        if(intval($chs[$pid]['offline_count']) > 1){
          $start_time = $chs[$pid]['start_time'];
          $manager->add_history($pid, $start_time, date('Y-m-d H:i:s'));
          if(!check_prev_live($pid, $sid_chs[$chs[$pid]['sid']]))
            end_tweet($chs[$pid]);
        }else{ 
          $manager->increment_offline_count($pid);
        }
      }
    }
  }

  log_print('finish checking Stickam.jp.');
}

function check_nicolive()
{

  function curl_seting( $url ){
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );

    return $ch;
  }
  
  function set_opt( $ch, $cookie_file ){
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie_file );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 20 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    return $ch;
  }


  
  global $manager, $chs, $sid_chs, $file_path, $nico_loginid, $nico_loginpw, $site_url;

  $ids = array();
  $hash = array();
  
  log_print("start checking NicoLive...");
  
  foreach($chs as $k => $v){
    if($v['type']==3){
      $hash[$v['ch_name']] = $v['pid'];
      $ids[] = $v['ch_name'];
    }
  }

  if(count($ids) == 0){
    log_print('finish checking NicoLive (no check).');
    return;
  }

  $login_url = 'https://secure.nicovideo.jp/secure/login?site=niconico';
  $param = Array(
    'mail' => $nico_loginid,
    "password" => $nico_loginpw
    );
  $cookie_file = $file_path.'/classes/compiled/cookie.txt';


  // login
  $ch = curl_seting( $login_url );
  $ch = set_opt( $ch, $cookie_file );
  
  // set options
  curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
  curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
  curl_setopt( $ch, CURLOPT_POST, true );
  curl_setopt( $ch, CURLOPT_POSTFIELDS, $param );
  
  $ret = curl_exec( $ch );
  curl_close( $ch );


  foreach($ids as $name){

    // get player info
    $api_url = 'http://live.nicovideo.jp/api/getplayerstatus/' . $name; // apiのURL
    $ch = curl_seting( $api_url );

    curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie_file );
    $ch = set_opt( $ch, $cookie_file );
    
    $ret = curl_exec( $ch );
    curl_close($ch);

    log_print($api_url);
    try{
      $xml = simplexml_load_string($ret, 'SimpleXMLElement', LIBXML_NOCDATA);
      if(! $xml ) throw new Exception('URL parse failed : '.$api_url);
    }catch(Exception $e){
      print $e->getMessage();
      continue;
    }

    $live_st = FALSE;
    $viewer = 0;
    $title = null;
    $thumb = '';
    
    $pid = $hash[$name];



    // judge live status
    if(isset($xml->error)){ // error
      // if api error, then retry
      if( $xml->error->code == 'server_error' || $xml->error->code == 'notlogin'
          || $xml->error->code == 'full' || $xml->error->code == 'unknown' ){
        continue;
      }
      
      // retry 1 time
      if(intval($chs[$hash[$name]]['offline_count']) > 0){
      }else{
        $manager->increment_offline_count($hash[$name]);
        continue;
      }
    }elseif( $xml->stream->archive == 1){
      // offline
    }else{
      $live_st = TRUE;
      $title = $xml->stream->title . ' / ' . $xml->stream->description;
      $chs[$pid]['title'] = $title;
    }


    // cache image
    $cachefile = $file_path.'/classes/compiled/'.$name.'.jpg';
    
    // if not exists or 1day older
    if( !file_exists($cachefile) || ( $live_st && filectime($cachefile) + 86400 < time() ) ){

      log_print('get community image...');

      // get community image
      $api_url = 'http://com.nicovideo.jp/community/' . $name; // apiのURL
      $ch = curl_seting( $api_url );
      
      curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie_file );
      $ch = set_opt( $ch, $cookie_file );
    
      $ret = curl_exec( $ch );
      curl_close($ch);

      log_print($api_url);
      try{
        if( preg_match('/http:\/\/icon\.nimg\.jp\/community\/(.+\/)?(?P<cm>co[0-9]+\.jpg)\?[0-9]+/', $ret, $matches) ){
          $thumb = $matches[0];


          print_r($matches);
          $api_url = $matches[0];
          $ch = curl_seting( $api_url );
          curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie_file );
          $ch = set_opt( $ch, $cookie_file );
          $ret = curl_exec( $ch );
          curl_close($ch);
          log_print($api_url);

          if(strpos($ret, '<?xml') === FALSE && strlen($ret) > 0){
            $fp = fopen($cachefile, "w");
            fwrite($fp, $ret);
            fclose($fp);
          }
        }
      }catch(Exception $e){
        print $e->getMessage();
        continue;
      }
    }
    $thumb = $site_url.'/classes/compiled/'.$name.'.jpg';




    
    // save status change
    $change_flag = ($chs[$pid]['live']=='t' || $chs[$pid]['live']=='1') ^ $live_st;
    
    if($live_st || $change_flag){
      
      log_print("<b>name:</b> ".$name." / ".$viewer);
      $manager->update_program($pid, $live_st, $viewer, $change_flag, $thumb, $title);
      
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
  }
  
  
  log_print('finish checking NicoLive.');
}



// check nicolive(official) function
//  ** register programs to temporary
function check_nicolive_official()
{
  

  // when set  no limit_keywords, return this function
  if ($GLOBALS['limit_keywords'] == '') {
    return;
  }

  function inner_check($url){
    global $manager;
    log_print($url);
    try{
      $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
      if (! $xml ) throw new Exception('URL open failed : '.$url);
      
      // add to temporary
      $items = $xml->channel->item;
      if ($items) {
        for ($i = 0; $i < count($items); $i++) {
          
          
          // if already added, then skip
          if ($manager->check_already_registered(3, $items[$i]->guid, NULL)) {
            continue;
          }
          // if program type is community, then skip
          $nicolive = $items[$i]->children('http://live.nicovideo.jp/');
          if ($nicolive->type == 'community') {
            continue;
          }
        
          // now add streamer as templrary
          $desc = addslashes(strip_tags($items[$i]->description));
          $check = $items[$i]->title . ' ' . $desc;
          if (preg_match('/'.$GLOBALS['limit_keywords'].'/', $check) > 0){
            log_print('register to temporary '.$items[$i]->title);
            $manager->register($items[$i]->title, $desc,
                               2, '', 3, $items[$i]->guid, NULL, 1);
          }
        }
      }
    }catch(Exception $e){
      print $e->getMessage();
      continue;
    }
  }

  log_print('start checking nicolive(official).');

  inner_check('http://live.nicovideo.jp/rss');
  inner_check('http://live.nicovideo.jp/recent/rss');
  
  log_print('finish checking nicolive(official).');
}



function check_twitcasting()
{
    
  global $manager, $chs, $sid_chs;

  $ids = array();
  $hash = array();
  
  log_print("start checking TwitCasting...");
  
  foreach($chs as $k => $v){
    if($v['type']==4){
      $hash[$v['ch_name']] = $v['pid'];
      $ids[] = $v['ch_name'];
    }
  }

  foreach($ids as $name){
    
    $url = 'http://api.twitcasting.tv/api/livestatus?type=json&user=' . $name;
    
    log_print($url);
    try{
      $jsontxt = '';
      $json = new Services_JSON();
      // open URL
      $fp = fopen($url, 'r');
      if(! $fp ) throw new Exception('URL open failed : '.$url);
      while (! feof($fp)) {
        $jsontxt .= fread($fp, 1024) or '';
      }
      fclose($fp);

      $xml = $json->decode($jsontxt);
    }catch(Exception $e){
      print $e->getMessage();
      return;
    }

    $live_st = FALSE;
    $viewer = 0;
    $thumb = '';

    // status judge
    if($xml->islive){
      $live_st = TRUE;
    }
    $viewer = $xml->viewers;

    // save status change
    $pid = $hash[$name];
    $change_flag = ($chs[$pid]['live']=='t' || $chs[$pid]['live']=='1') ^ $live_st;
    
    if($live_st || $change_flag){
      
      log_print("<b>name:</b> ".$name." / ".$viewer);
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
  }

  log_print('finish checking TwitCasting.');
}



function check_own3d()
{
    
  global $manager, $chs, $sid_chs;

  $ids = array();
  $hash = array();
  
  log_print("start checking own3D...");
  
  foreach($chs as $k => $v){
    if($v['type']==5){
      $hash[$v['ch_name']] = $v['pid'];
      $ids[] = $v['ch_name'];
    }
  }

  foreach($ids as $name){
    
    $url = 'http://api.own3d.tv/liveCheck.php?live_id=' . $chs[$hash[$name]]['optional_id'];
    
    log_print($url);
    try{
      $xml = simplexml_load_file($url, 'SimpleXMLElement');
      if(! $xml ) throw new Exception('URL parse failed : '.$url);
    }catch(Exception $e){
      print $e->getMessage();
      continue;
    }

    $live_st = FALSE;
    $viewer = 0;
    $thumb = '';

    // status judge
    if(strtolower($xml->liveEvent->isLive) == 'true'){
      $live_st = TRUE;
    }
    $viewer = $xml->liveEvent->liveViewers;

    // save status change
    $pid = $hash[$name];
    $change_flag = ($chs[$pid]['live']=='t' || $chs[$pid]['live']=='1') ^ $live_st;
    $thumb = 'http://img.own3d.tv/live/live_tn_'.$chs[$hash[$name]]['optional_id'].'_.jpg?'.time();

    if($live_st || $change_flag){
      
      log_print("<b>name:</b> ".$name." / ".$viewer);
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
  }

  log_print('finish checking own3D.');
}



// check LiveTube function
function check_livetube()
{
  
  global $manager, $chs, $sid_chs;

  $ids = array();
  $hash = array();
  $live = array();

  log_print('start checking LiveTube.');

  foreach($chs as $k => $v){
    if($v['type']==6){
      $hash[$v['ch_name']] = $v['pid'];
      $ids[] = $v['ch_name'];
      $live[$v['pid']] = 0;
    }
  }

  $url = 'http://livetube.cc/index.live.xml';
  
  log_print($url);
  try{
    $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
    if (! $xml ) throw new Exception('URL open failed : '.$url);
    
    foreach($xml->entry as $ch){
      //print_r($ch);
      
      $name = (string)$ch->author->name;
      if(!$name || $name == '' || !array_key_exists($name, $hash)) continue; // error
      
      $title = $ch->title;
      $live_id = urldecode(substr($ch->id, 19));
      $viewer = 0;
      $pid = $hash[$name];
      $change_flag = $chs[$pid]['live'] == 'f' || $chs[$pid]['live'] == '0' || $chs[$pid]['live'] == '';
      $thumb = '';
      
      log_print("<b>name:</b> ".$name." / ".$viewer);
      $manager->update_program($pid, TRUE, $viewer, $change_flag, $thumb, $title, $live_id);
      
      if($change_flag && !check_prev_live($pid, $sid_chs[$chs[$pid]['sid']]))
        start_tweet($chs[$pid]);
      
      $live[$pid] = 1;
    }

    foreach($live as $k => $v){
      if( $v == 0 && ($chs[$k]['live']=='t' || $chs[$k]['live']=='1') ){
        
        $manager->update_program($k, FALSE, 0, 1, '');
        
        $start_time = $chs[$k]['start_time'];
        $manager->add_history($k, $start_time, date('Y-m-d H:i:s'));
        if(!check_prev_live($k, $sid_chs[$chs[$k]['sid']]))
          end_tweet($chs[$k]);

      }
    }
  }catch(Exception $e){
    print $e->getMessage();
    continue;
  }

  log_print('finish checking LiveTube.');
}



// check CaveTube function
function get_cavetube_stream($id){
  $url = 'http://gae.cavelis.net//live_url?user='.$id;
  log_print($url);
    
  try{
    $jsontxt = '';
    $json = new Services_JSON();
    // open URL
    $fp = fopen($url, 'r');
    if(! $fp ) throw new Exception('URL open failed : '.$url);
    while (! feof($fp)) {
      $jsontxt .= fread($fp, 1024) or '';
    }
    fclose($fp);
    
    $obj = $json->decode($jsontxt);
    return $obj->stream_name;
  }catch(Exception $e){
    print $e->getMessage();
    return 0;
  }
}
function get_cavetube_summary($stream_name){
  global $cavetube_devkey;
  
  $url = 'http://gae.cavelis.net/api/summary?devkey='.$cavetube_devkey.'&stream_name='.$stream_name;
  log_print($url);
    
  try{
    $jsontxt = '';
    $json = new Services_JSON();
    // open URL
    $fp = fopen($url, 'r');
    if(! $fp ) throw new Exception('URL open failed : '.$url);
    while (! feof($fp)) {
      $jsontxt .= fread($fp, 1024) or '';
    }
    fclose($fp);
    
    $obj = $json->decode($jsontxt);
    return $obj;
  }catch(Exception $e){
    print $e->getMessage();
    return 0;
  }
}

function check_cavetube()
{
  
  global $manager, $chs, $sid_chs;

  $ids = array();
  $hash = array();
  $live = array();

  log_print('start checking CaveTube.');

  foreach($chs as $k => $v){
    if($v['type']==7){
      $hash[$v['ch_name']] = $v['pid'];
      $ids[] = $v['ch_name'];
      $live[$v['pid']] = 0;
    }
  }

  $url = 'http://rss.cavelis.net/index_live.xml';
  
  log_print($url);
  try{
    $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
    if (! $xml ) throw new Exception('URL open failed : '.$url);
    
    foreach($xml->entry as $ch){
      //print_r($ch);
      
      $name = (string)$ch->author->name;
      if(!$name || $name == '' || !array_key_exists($name, $hash)) continue; // error
      
      $title = $ch->title . ' / ' . $ch->content;
      $live_id = substr($ch->id, 28);

      $pid = $hash[$name];
      $change_flag = $chs[$pid]['live'] == 'f' || $chs[$pid]['live'] == '0' || $chs[$pid]['live'] == '';
      
      $stream_name = get_cavetube_stream($name);
      $summary = get_cavetube_summary($stream_name);
      $viewer = $summary->listener;
      //$thumb = 'http://img.cavelis.net/userthumbnails/m/'.$name.'.jpg';
      $thumb = 'http://ss.cavelis.net:3001/?url=rtmp://nwa2.cavelis.net/live/'.$stream_name.'?'.time();
      
      log_print("<b>name:</b> ".$name." / ".$viewer);

      $manager->update_program($pid, TRUE, $viewer, $change_flag, $thumb, $title, $live_id);
      
      if($change_flag && !check_prev_live($pid, $sid_chs[$chs[$pid]['sid']]))
        start_tweet($chs[$pid]);
      
      $live[$pid] = 1;
    }

    foreach($live as $k => $v){
      if( $v == 0 && ($chs[$k]['live']=='t' || $chs[$k]['live']=='1') ){
        
        $manager->update_program($k, FALSE, 0, 1, '');
        
        $start_time = $chs[$k]['start_time'];
        $manager->add_history($k, $start_time, date('Y-m-d H:i:s'));
        if(!check_prev_live($k, $sid_chs[$chs[$k]['sid']]))
          end_tweet($chs[$k]);

      }
    }
  }catch(Exception $e){
    print $e->getMessage();
    continue;
  }

  log_print('finish checking CaveTube.');
}



check();

include 'footer.php';

?>
