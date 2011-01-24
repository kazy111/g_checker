<?php
include 'header.php';

$p = validate_num(array_key_exists('p', $_GET)) ? $_GET['p'] : 0;

function get_list($p, $pagesize){
  global $manager, $page;

  $result = $manager->get_articles($pagesize, $p);
  
  $ret = '';
  foreach($result as $arr){
    $ret .= $page->get_once('article_item', $arr);
  }

  return $ret;
}

$contents = '';

$contents .= get_list($p, $page_size);

$data = array();
$data['page'] = $p;
$data['contents'] = $contents;
$page->set('article', $data);

include 'footer.php';

?>

