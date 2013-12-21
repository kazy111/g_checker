<?php



$service_assoc = array(0 => 'ustream',
                       1 => 'justin',
                       2 => 'stickam',
                       3 => 'nicolive',
                       4 => 'twitcasting',
                       5 => 'own3d',
                       6 => 'livetube',
                       7 => 'cavetube',
                       8 => 'twitch');

$service_abb_assoc = array(0 => 'Ust',
                           1 => 'Jst',
                           2 => 'Stk',
                           3 => 'Nic',
                           4 => 'Twc',
                           5 => 'Own',
                           6 => 'Lvt',
                           7 => 'Cvt',
                           8 => 'Tch');

$service_org_assoc = array(0 => FALSE,
                           1 => FALSE,
                           2 => TRUE,
                           3 => TRUE,
                           4 => TRUE,
                           5 => FALSE,
                           6 => TRUE,
                           7 => TRUE,
                           8 => FALSE);

$chat_assoc = array(0 => 'c.ustream.tv',
                    1 => 'irc.mibbit.com',
                    2 => 'Social Stream');

$sort_assoc = array('random' => 'ランダム',
                    'viewer' => '視聴者数',
                    'time' => '配信時間',
                    'name' => '名前'
                    );

function assoc2select($assoc, $id, $selected)
{
  $html = '<select id="'.$id.'" name="'.$id.'">';
  foreach($assoc as $k => $v){
    if($selected && $selected == $k)
      $html .= '<option value="'.$k.'" selected="selected">'.$v.'</option>';
    else
      $html .= '<option value="'.$k.'">'.$v.'</option>';
  }
  $html .= '</select>';
  return $html;
}

function get_service_url($type, $ch_name, $ch_id, $live = FALSE)
{
  switch($type){
  case 0: //ustream
    $ret = 'http://www.ustream.tv/channel/'.$ch_name;
    break;
  case 1: // justin
    $ret = 'http://www.justin.tv/'.$ch_name;
    break;
  case 2: // stickam
    $ret = 'http://www.stickam.jp/profile/'.$ch_name;
    break;
  case 3: // nicolive
    if(substr($ch_name, 0, 2) == 'co' && !$live){
      $ret = 'http://com.nicovideo.jp/community/'.$ch_name;
    }else{
      $ret = 'http://live.nicovideo.jp/watch/'.$ch_name;
    }
    break;
  case 4: // twitcasting
    $ret = 'http://twitcasting.tv/'.$ch_name;
    break;
  case 5: // own3D
    $ret = 'http://www.own3d.tv/live/'.$ch_id;
    break;
  case 6: // LiveTube
    $ret = 'http://livetube.cc/'.($ch_id == '' ? $ch_name : $ch_id);
    break;
  case 7: // CaveTube
    $ret = 'http://gae.cavelis.net/live/'.$ch_name;
    break;
  case 8: // Twitch
    $ret = 'http://www.twitch.tv/'.$ch_name;
    break;
  }
  return $ret;
}

function get_archive_url($type, $ch_name, $opt_id)
{
  switch($type){
  case 0: //ustream
    $ret = 'http://lonsdaleite.jp/uarchives/?channel='.$opt_id;
    break;
  case 1: // justin
    //$ret = 'http://lonsdaleite.jp/jarchives/?channel='.$ch_name;
    $ret = 'http://www.justin.tv/'.$ch_name.'/videos?kind=past_broadcasts';
    break;
  case 2: // stickam
    $ret = 'http://www.stickam.jp/video/gallery/'.$ch_name;
    break;
  case 3: // nicolive
    if(substr($ch_name, 0, 2) == 'co'){
      $ret = 'http://com.nicovideo.jp/live_archives/'.$ch_name;
    }else{
      $ret = 'http://live.nicovideo.jp/watch/'.$ch_name;
    }
    break;
  case 4: // twitcasting
    $ret = 'http://twitcasting.tv/'.$ch_name.'/show/';
    break;
  case 5: // own3d
    $ret = '';
    break;
  case 6: // livetube
    $ret = 'http://livetube.cc/'.$ch_name;
    break;
  case 7: // cavetube
    $ret = 'http://gae.cavelis.net/user/'.$ch_name;
    break;
  case 8: // Twitch.tv
    $ret = 'http://www.twitch.tv/'.$ch_name.'/profile/pastBroadcasts';
    break;
  }
  return $ret;
}

?>
