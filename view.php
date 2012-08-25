<?php


include 'header.php';
// view channel streaming and chat per streamer.
// switch every programs


$id = number_trim($_GET['id']);
$id = validate_num($id) ? $id : 0;


$result = $manager->get_streamer_info($id);

$cids = array();
$pids = array();

$name = '';
$desc = '';
$tag = '';
$url = '';
$wiki = '';

foreach($result as $arr){
  $tmp = array();
  $tmp['id'] = $arr['cid'];
  $tmp['room'] = $arr['room'];
  $tmp['type'] = $arr['ctype'];
  $cids[$arr['cid']] = $tmp;
  
  $tmp = array();
  $tmp['id'] = $arr['pid'];
  $tmp['cid'] = $arr['cid'];
  $tmp['ch_id'] = $arr['ch_name'];
  $tmp['opt_id'] = $arr['optional_id'];
  $tmp['type'] = $arr['ptype'];
  $tmp['live'] = $arr['live'];
  $pids[$arr['pid']] = $tmp;
  $name = $arr['name'];
  $desc = $arr['description'];
  $tag = format_tag($arr['tag']);
  $url = $arr['url'];
  $wiki = $arr['wiki'];
}

// construct chat data
$chat_data = array();
foreach($cids as $a){
  $chat_data[] = ''.$a['id'].':{room:"'.$a['room'].'",type:'.$a['type'].'}';
}
$chat_data = '{'.implode(',', $chat_data).'}';

// open orignal site or view page flag
$open_original = TRUE;

// construct program data
$program_data = array();
foreach($pids as $a){
  $org_link = '<a href="'.get_service_url($a['type'], $a['ch_id'], $a['opt_id']).'">'.$service_abb_assoc[$a['type']].': '.$a['ch_id'].'</a> ';
  $program_data[] = $a['id'].':{id:'.$a['id'].', ch_id:"'.$a['ch_id'].'",opt_id:"'.$a['opt_id']
    .'",type:'.$a['type'].',cid:'.$a['cid'].',org:\''.$org_link.'\',typename:"'.$service_abb_assoc[$a['type']].'"}';
  if($a['live'] == 't' || $a['live'] == '1'){
    if( $a['type'] == 1 /*if justin*/ ||  !isset($first_id) ) {
    //if( $a['type'] == 0 /*if ust*/ ||  !isset($first_id) ) {
      $first_id = $a['id'];
    }
    $open_original &= $service_org_assoc[$a['type']];
    $open_original_url = get_service_url($a['type'], $a['ch_id'], $a['opt_id'], TRUE);
  }
}


if(!isset($first_id)){
  $tmp = 10000;
  foreach($pids as $p){
    if($p['type'] < $tmp){
      $first_id = $p['id'];
      $tmp = $p['type'];
    }
  }
  $open_original = FALSE;
}

// redirect if only 1 redirect program is registered
reset($pids);
$a = current($pids);
if(!$open_original && count($pids) === 1 && $service_org_assoc[$a['type']]){
  $open_original = TRUE;
  $open_original_url = get_service_url($a['type'], $a['ch_id'], $a['opt_id']);
}

if($open_original){
  // redirect
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: ".$open_original_url);
  exit();
}

$program_data = '{'.implode(',', $program_data).'}';


  $contents_theme = '<select id="theme">';
  $themes = $page->get_themes();
  foreach($themes as $t){
    $contents_theme .= '<option value="'.urlencode($t).'"'.(($t == $page->theme)?' selected="selected"':'').'>'.$t.'</option>';
  }
  $contents_theme .= '</select>'
      .'<input type="button" value="変更" onclick="WriteCookie(\'theme\', document.getElementById(\'theme\').options[document.getElementById(\'theme\').selectedIndex].value, 0);location.reload();" />';


$data = new Dwoo_Data();

$data->assign('name', $name);
$data->assign('description', $desc);
$data->assign('wiki_url', $GLOBALS['wiki_url']);
$data->assign('wiki', $wiki);
$data->assign('url', $url);
$data->assign('tag', $tag);

$data->assign('id', $id);
$data->assign('p_data', $program_data);
$data->assign('c_data', $chat_data);
$data->assign('first_id', $first_id);
$data->assign('theme_data', $contents_theme);

$page->add_header('<meta http-equiv="content-script-type" content="text/javascript" />');
$page->add_header('<script type="text/javascript" src="./js/swfobject.js"></script>');
$page->add_header('<script type="text/javascript" src="./js/nicoirc20111016.js"></script>');
$page->set('view', $data);
$page->set_title($name);

include 'footer.php';

?>
