<?php
include '../header.php';


$list = $manager->get_legends();

// construct streamer output
$contents_item = '<h1><a href="'.$site_url.'">'.$site_title.'</a> >> 伝説</h1>';
$data = new Dwoo_Data();
$data->assign('edit_php', 'edit_legend.php');
$data->assign('delete_php', 'delete_legend.php');

foreach($list as $arr){
  $data->assign('id', $arr['id']);
  $data->assign('name', $arr['body'] );
  $contents_item .= $page->get_once('list_item', $data);
}

// output page contents
$data = new Dwoo_Data();
$data->assign('data', $contents_item);
$page->set_relative_dir_to_top('..');
$page->set('list', $data);

include '../footer.php';
?>
