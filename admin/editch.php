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
  $ch_name = '';
  $optional_id = '';
  $streamer_id = null;
  $chat_id = null;

  if($id){
    // get info from DB
    $result = $db->query('select type, ch_name, optional_id, streamer_id, chat_id from program_table where id = '.$id);
    if($result){
      $arr = $db->fetch($result);
      $type = $arr['type'];
      $ch_name = $arr['ch_name'];
      $optional_id = $arr['optional_id'];
      $streamer_id = $arr['streamer_id'];
      $chat_id = $arr['chat_id'];
    }
  }

  $result = $db->query('select id, name from streamer_table order by name');
  $assoc = array();
  if($result){
    while($arr = $db->fetch($result))
      $assoc[$arr['id']] = $arr['name'];
  }
  $sids_html = assoc2select($assoc, 'streamer_id', $streamer_id);

  
  $result = $db->query('select id, type, room from chat_table order by room');
  $assoc = array();
  if($result){
    while($arr = $db->fetch($result))
      $assoc[$arr['id']] = $chat_assoc[$arr['type']].' '.$arr['room'];
  }
  $cids_html = assoc2select($assoc, 'chat_id', $chat_id);
  
  $type_html = assoc2select($service_assoc, 'type', $type);
  // display form

  return <<< EOD
    <form method="POST">
      <input type="hidden" name="mode" value="1" />
      <input type="hidden" name="id" value="$id" />
      <span class="form_title">Streamer:</span>
      $sids_html<br />
      <span class="form_title">Chat:</span>
      $cids_html<br />
      <span class="form_title">Service Type:</span>
      $type_html<br />
      <span class="form_title">Channel ID:</span>
      <input type="edit" name="ch_name" value="$ch_name" /><br />
      <span class="form_title">Optional ID:</span>
      <input type="edit" name="optional_id" value="$optional_id" /><br />
      <input type="submit" value="submit" />
    </form>
EOD;
  
}

function register_program(){
  global $_POST, $db;

  if($_POST['id'] && $_POST['id']!=''){
    // update
    $db->query('update program_table set type = '.$_POST['type'].', ch_name = \''
               .$_POST['ch_name'].'\', optional_id = \''.$_POST['optional_id']
               .'\', streamer_id = '.$_POST['streamer_id']
               .', chat_id = '.$_POST['chat_id']
               .' where id='.$_POST['id']);
  } else {
    // create
    $sql = 'insert into program_table (type, ch_name, optional_id, streamer_id, chat_id, viewer) values ('
               .$_POST['type'].', \''.$_POST['ch_name'].'\', \''
               .$_POST['optional_id'].'\', '.$_POST['streamer_id'].', '.$_POST['chat_id'].', 0)';
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
