<?php

// view channel histroy
// TODO sanitize, validation

// TODO dropdown box of steamer
// TODO editchat.php -> linktable dropdownbox

include 'header.php';

$id = validate_num($_GET['id']) ? $_GET['id'] : 0;

function get_name($id){
  global $db;
  if($id){
    $result = $db->query('select name from streamer_table where id = '.$id.'');
    if($arr = $db->fetch($result)){
      return $arr['name'];
    }
  }
}
function get_list($id){
  global $db, $service_assoc, $chat_assoc;

  $type = 0;
  $room = '';
  $program_id = null;

  $ret = '<ul>';
  if($id){
    // get info from DB
    $result = $db->query('select h.start_time as stime, h.end_time as etime from program_table as p, history_table as h '
                         .' where p.id = h.program_id and p.streamer_id = '.$id.' order by stime desc limit 100 ');
    while($arr = $db->fetch($result)){
      $start_time = $arr['stime'];
      $end_time = $arr['etime'];

      // diff time calc
      $diff = strtotime($end_time) - strtotime($start_time);
      $dday = floor($diff / (60*60*24));
      $dhour = floor($diff / (60*60));
      $dmin = floor($diff / 60 % 60);
      $diff = ($dday>0 ? $dday.'日': ( ($dhour>0 ? $dhour.'時間' :'').$dmin.'分'));
      
      //$ret .= '<li>start: '.date('Y-m-d H:i:s', $start_time).', end: '.date('Y-m-d H:i:s',$end_time).' ('.$diff.')</li>';
      $ret .= '<li>start: '.$start_time.', end: '.$end_time.' ('.$diff.')</li>';

    }
  }
  
  $ret .= '</ul>';

  return $ret;
}

$contents = '';

$contents .= get_list($id);

$data = array();
$data['name'] = get_name($id);
$data['site_title'] = $site_title;
$data['contents'] = $contents;
$page->set('history', $data);

include 'footer.php';

?>
