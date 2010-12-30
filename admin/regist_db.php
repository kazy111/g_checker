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
  global $_POST, $db;
  
  //** register user at once
  $name = $_POST['name'];
  $room = $_POST['room'];
  $chat_type = $_POST['chat_type'];
  $ust_id = $_POST['ust_id'];
  $jus_id = $_POST['jus_id'];
  $ust_no = $_POST['ust_no'];
  $desc = $_POST['desc'];
  
  // TODO lock transaction
  
  // TODO
  $db->begin();
  
  // PostgreSQL
  $sql = 'select nextval(\'streamer_table_id_seq\')';
  $arr = $db->fetch($db->query($sql));
  $sid = $arr['nextval']; // TODO => create get_sequence_id?
  // -- PostgreSQL
  
  $sql = 'insert into streamer_table (id, name, description) values'
        .'('.$sid.', \''.$name.'\', \''.$desc.'\')';
  $db->query($sql);
  //$sid = mysql_insert_id();// MySQL
  
  if(!$sid)
    $db->rollback();
  else
    $db->commit();


  // TODO
  $db->begin();
  // PostgreSQL
  $sql = 'select nextval(\'chat_table_id_seq\')';
  $arr = $db->fetch($db->query($sql));
  $cid = $arr['nextval']; // TODO => create get_sequence_id?
  // -- PostgreSQL
  $sql = 'insert into chat_table (id, room, type) values ('.$cid.', \''.$room.'\', '.$chat_type.')';
  $db->query($sql);
  //$sid = mysql_insert_id();// MySQL
  
  // TODO
  if(!$cid)
    $db->rollback();
  else
    $db->commit();
  
  
  if($ust_id){
    $sql = 'insert into program_table (streamer_id, chat_id, type, ch_name, optional_id)'
          .' values ('.$sid.', '.$cid.', 0, \''.$ust_id.'\',\''.$ust_no.'\')';
    $db->query($sql);
  }
  if($jus_id){
    $sql = 'insert into program_table (streamer_id, chat_id, type, ch_name)'
          .' values ('.$sid.', '.$cid.', 1, \''.$jus_id.'\')';
    $db->query($sql);
  }
  
}

if ( $_POST['mode'] ) {
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
