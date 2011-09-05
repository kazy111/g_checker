<?php
// TODO sanitize, validation

// TODO dropdown box of steamer
// TODO editchat.php -> linktable dropdownbox

include '../header.php';

// edit form & register
// if no id, create new entry

function get_form($id){
  global $manager, $service_assoc, $chat_assoc;

  $type = 0;
  $ch_name = '';
  $optional_id = '';
  $streamer_id = null;
  $chat_id = null;

  if($id){
    // get info from DB
    $result = $manager->get_program($id);
    if($result){
      $type = $result['type'];
      $ch_name = $result['ch_name'];
      $optional_id = $result['optional_id'];
      $streamer_id = $result['streamer_id'];
      $chat_id = $result['chat_id'];
    }
  }

  $result = $manager->get_streamers();
  $assoc = array();
  foreach($result as $arr)
    $assoc[$arr['id']] = $arr['name'];
  asort($assoc);
  $sids_html = assoc2select($assoc, 'streamer_id', $streamer_id);

  
  $result = $manager->get_chats();
  $assoc = array();
  foreach($result as $arr)
    $assoc[$arr['id']] = $chat_assoc[$arr['type']].' '.$arr['room'];

  asort($assoc);
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
  global $_POST, $manager;

  $manager->set_program($_POST);
}

$contents = '';
if ( array_key_exists('mode', $_POST) ) {
  // TODO validation
  
  register_program();
  $contents .= '<span class="message">updated information</span>';
}

$contents .= get_form(get_key($_GET, 'id'));
$data = array();
$data['contents'] = $contents;
$page->set_relative_dir_to_top('..');
$page->set('raw', $data);

include '../footer.php';
?>
