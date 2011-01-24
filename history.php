<?php

// view channel histroy

include 'header.php';

$id = (array_key_exists('id', $_GET) && validate_num($_GET['id'])) ? $_GET['id'] : 0;

function get_streamer_name($id){
  global $manager;
  if($id){
    $result = $manager->get_streamer($id);
    return $result['name'];
  }
}

function get_list($id){
  global $manager, $service_assoc, $chat_assoc;

  $type = 0;
  $room = '';
  $program_id = null;

  $ret = '<ul>';
  if(!is_null($id)){
    $result = $manager->get_histories($id);
    foreach($result as $arr){
      $start_time = $arr['stime'];
      $end_time = $arr['etime'];

      // diff time calc
      $diff = strtotime($end_time) - strtotime($start_time);
      $dday = floor($diff / (60*60*24));
      $dhour = floor($diff / (60*60) % 24);
      $dmin = floor($diff / 60 % 60);
      $diff = ($dday>0 ? $dday.'日と':''). ($dhour>0 ? $dhour.'時間' :'').$dmin.'分';
      
      $ret .= '<li>start: '.$start_time.', end: '.$end_time.' ('.$diff.')</li>';
    }
  }
  
  $ret .= '</ul>';

  return $ret;
}

$contents = '';

$contents .= get_list($id);

$data = array();
$data['name'] = get_streamer_name($id);
$data['site_title'] = $site_title;
$data['contents'] = $contents;
$page->set('history', $data);

include 'footer.php';

?>
