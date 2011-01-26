<?php
// TODO sanitize, validation

// TODO dropdown box of steamer
// TODO editchat.php -> linktable dropdownbox

include '../header.php';

// edit form & register
// if no id, create new entry

function get_form($id){
  global $manager, $chat_assoc;

  $type = 0;
  $room = '';
  $program_id = null;

  if($id){
    // get info from DB
    $result = $manager->get_chat($id);
    if($result){
      $type = $result['type'];
      $room = $result['room'];
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
  global $_POST, $manager;

  $manager->set_chat($_POST);

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
$page->set('raw', $data);

include '../footer.php';
?>
