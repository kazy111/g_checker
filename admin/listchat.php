<?php
include '../header.php';


$list = $manager->get_chats();

// construct streamer output
$contents_item = '';
$data = new Dwoo_Data();
$data->assign('edit_php', 'editchat.php');
$data->assign('delete_php', 'delchat.php');

foreach($list as $arr){
  $data->assign('id', $arr['id']);
  $data->assign('name', $chat_assoc[$arr['type']] .' - '. $arr['room']);
  $contents_item .= $page->get_once('list_item', $data);
}

// output page contents
$data = new Dwoo_Data();
$data->assign('data', $contents_item);
$page->set_relative_dir_to_top('..');
$page->set('list', $data);

include '../footer.php';
?>
