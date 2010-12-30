<?php
// TODO sanitize, validation

// TODO dropdown box of steamer
// TODO editchat.php -> linktable dropdownbox

include '../header.php';

// edit form & register
// if no id, create new entry

function get_form($id){
  global $db, $service_assoc, $chat_assoc;

  $type = 0;
  $room = '';
  $program_id = null;

  if($id){
    // get info from DB
    $result = $db->query('select type, room from chat_table where id = '.$id);
    if($result){
      $arr = $db->fetch($result);
      $type = $arr['type'];
      $room = $arr['room'];
    }
  }

  $type_html = assoc2select($chat_assoc, 'type', $type);
  // display form

  return <<< EOD
    <form method="POST">
      <input type="hidden" name="mode" value="1" />
      <input type="hidden" name="id" value="$id" />
      <span class="form_title">Service Type:</span>
      $type_html<br />
      <span class="form_title">room:</span>
      <input type="edit" name="room" value="$room" /><br />
      <input type="submit" value="submit" />
    </form>
EOD;
  
}

function register_program(){
  global $_POST, $db;

  if($_POST['id'] && $_POST['id']!=''){
    // update
    $db->query('update chat_table set type = '.$_POST['type'].', room = \''
               .$_POST['room'].'\' where id='.$_POST['id']);
  } else {
    // create
    $sql = 'insert into chat_table (type, room, member) values ('
               .$_POST['type'].', \''.$_POST['room'].'\', 0)';
    $db->query($sql);
  }
}

$contents = '';
if ( $_POST['mode'] ) {
  // TODO validation
  
  register_program();
  $contents .= '<span class="message">updated information</span>';
}

$contents .= get_form($_GET['id']);
$data = array();
$data['contents'] = $contents;
$page->set('raw', $data);

include '../footer.php';
?>
