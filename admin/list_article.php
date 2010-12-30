<?php
include '../header.php';


$sql = 'select id, title, body, priority, created from article_table order by id';
$result = $db->query($sql);
$list = array();
while($arr = $db->fetch($result)){
  $list[] = $arr;
}

// construct streamer output
$contents_item = '';
$data = new Dwoo_Data();
$data->assign('edit_php', 'edit_article.php');
$data->assign('delete_php', 'delete_article.php');

foreach($list as $arr){
  $data->assign('id', $arr['id']);
  $data->assign('name', $arr['title'] );
  $contents_item .= $page->get_once('list_item', $data);
}

// output page contents
$data = new Dwoo_Data();
$data->assign('data', $contents_item);
$page->set('list', $data);

include '../footer.php';
?>
