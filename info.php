<?php
include 'header.php';

$p = validate_num($_GET['p']) ? $_GET['p'] : 0;

function get_list($p, $pagesize){
  global $db, $page;

  $program_id = null;

  $sql = 'select id, title, body, priority, created from article_table '
        .' order by priority desc, created desc limit '.$pagesize.' offset '.($pagesize * $p).';';
  $result = $db->query($sql);
  $ret = '';
  while(($arr = $db->fetch($result)) != NULL ){
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

