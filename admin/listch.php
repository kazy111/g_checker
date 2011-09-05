<?php
include '../header.php';


$list = $manager->get_programs();

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
$page->set_relative_dir_to_top('..');
$page->set('list', $data);

include '../footer.php';
?>
