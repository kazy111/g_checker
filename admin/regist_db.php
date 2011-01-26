<?php
// TODO sanitize, validation

include '../header.php';

// edit form & register
// if no id, create new entry

function display_form(){
  global $dwoo;

  $data = array();
  $data['ctype_html'] = assoc2select($chat_assoc, 'chat_type', 0);
  
  $tpl = new Dwoo_Template_File('templates/regist_db.tpl');
  $dwoo->output($tpl, $data);
  
}

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

$page->add_header('<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>');
$page->add_header('<script type="text/javascript" src="../js/jquery-ui-1.8.4.custom.min.js"></script>');
$page->add_header('<script type="text/javascript" src="../js/jquery-ui-1.8.4_autocomplete.js"></script>');
$page->add_header('<link rel="stylesheet" type="text/css" href="../css/dot-luv/jquery-ui-1.8.4.custom.css" />');

$page->set('regist', $data);

include '../footer.php';
?>
