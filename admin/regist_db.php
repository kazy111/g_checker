<?php
// TODO sanitize, validation

include '../header.php';

// edit form & register
// if no id, create new entry


function register_program(){
  global $_POST, $manager;
  
  //** register user at once
  $name = get_key($_POST, 'name');
  $room = get_key($_POST, 'room');
  $chat_type = get_key($_POST, 'chat_type');
  $ust_id = get_key($_POST, 'ust_id');
  $jus_id = get_key($_POST, 'jus_id');
  $ust_no = get_key($_POST, 'ust_no');
  $desc = get_key($_POST, 'desc');

  $manager->register_onece($name, $room, $chat_type, $ust_id, $jus_id, $ust_no, $desc);
  
}

$message = '';
if ( array_key_exists('mode', $_POST) ) {
  // TODO validation
  
  register_program();
  $message = '<span class="message">updated information</span>';
}

$data = array();
$data['ctype_html'] = assoc2select($chat_assoc, 'chat_type', 0);
$data['message'] = $message;

$page->set_relative_dir_to_top('..');
$page->set('regist', $data);

include '../footer.php';
?>
