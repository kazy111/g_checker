<?php
include '../header.php';


$sql = 'select id, ch_name, type, streamer_id, chat_id from program_table order by id';
$result = $db->query($sql);
$list = array();
while($arr = $db->fetch($result)){
  $list[] = $arr;
}

// construct streamer output
$contents_item = '';
$data = new Dwoo_Data();
$data->assign('edit_php', 'editch.php');
$data->assign('delete_php', 'delch.php');

foreach($list as $arr){
  $data->assign('id', $arr['id']);
  $data->assign('name', $service_assoc[$arr['type']] .' - '. $arr['ch_name']);
  $contents_item .= $page->get_once('list_item', $data);
}

// output page contents
$data = new Dwoo_Data();
$data->assign('data', $contents_item);
$page->set('list', $data);

include '../footer.php';
?>
