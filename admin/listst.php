<?php
include '../header.php';

$list = $manager->get_streamers();

// construct streamer output
$contents_item = '';
$data = new Dwoo_Data();
$data->assign('edit_php', 'editst.php');
$data->assign('delete_php', 'delst.php');


foreach($list as $arr){
  $data->assign('id', $arr['id']);
  $data->assign('name', $arr['name']);
  $contents_item .= $page->get_once('list_item', $data);
}

// output page contents
$data = new Dwoo_Data();
$data->assign('data', $contents_item);
$page->set('list', $data);

include '../footer.php';
?>
