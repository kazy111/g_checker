<?php
// TODO validation

include dirname(__FILE__) . '/header.php';

// edit form & register
// if no id, create new entry

function get_info($id){
  global $manager;

  $data = array();
  $tag = '';

  if($id){
    // get info from DB
    $result = $manager->get_tag($id);
    if($result){
      $tag = $result['tag'];
    }
  }

  $data['id'] = $id;
  $data['tag'] = $tag;

  return $data;
}

function register_program($arr){
  global $_POST, $manager;

  $manager->set_tag($arr);
}

$data = get_info(get_key($_GET, 'id'));
$message = '';

if ( array_key_exists('mode', $_POST) ) {
  // TODO validation

  register_program(sanitize_array($_POST));
  // TODO error check
  
  $data = get_info(get_key($_GET, 'id'));
  $message = '更新しました';
}

$data['message'] = $message;

print($page->get_once('edit_tag', $data));

?>
