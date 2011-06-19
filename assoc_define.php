<?php



$service_assoc = array(0 => 'ustream',
                       1 => 'justin',
                       2 => 'stickam',
                       3 => 'nicolive',
                       4 => 'twitcasting');

$service_abb_assoc = array(0 => 'Ust',
                           1 => 'Jst',
                           2 => 'Stk',
                           3 => 'Nic',
                           4 => 'Twc');

$service_org_assoc = array(0 => FALSE,
                           1 => FALSE,
                           2 => TRUE,
                           3 => TRUE,
                           4 => TRUE);

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

function get_service_url($type, $ch_name)
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
    $ret = 'http://live.nicovideo.jp/watch/'.$ch_name;
    break;
  case 4: // twitcasting
    $ret = 'http://twitcasting.tv/'.$ch_name;
    break;
  }
  return $ret;
}


?>
