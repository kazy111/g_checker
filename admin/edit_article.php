<?php
// TODO sanitize, validation

// TODO dropdown box of steamer

include '../header.php';

// edit form & register
// if no id, create new entry

function get_form($id){
  global $manager;

  $title = '';
  $body = '';
  $priority = 0;

  if($id){
    // get info from DB
    $result = $manager->get_article($id);
    if($result){
      $title = sanitize_html($result['title']);
      $body = sanitize_html($result['body']);
      $priority = sanitize_html($result['priority']);
    }
  }

  // display form

  return <<< EOD
    <form method="POST">
      <input type="hidden" name="mode" value="1" />
      <input type="hidden" name="id" value="$id" />
      <span class="form_title">title:</span>
      <input type="edit" name="title" value="$title" /><br />
      <span class="form_title">body:</span>
      <textarea name="body" cols="40" rows="10">$body</textarea><br />
      <span class="form_title">priority:</span>
      <input type="edit" name="priority" value="$priority" /><br />
      <span class="form_title">update time:</span>
      <input type="checkbox" name="update_time[]" value="on" /><br />
      <input type="submit" value="submit" />
    </form>
EOD;

}

function register_program(){
  global $_POST, $manager;

  $manager->set_article($_POST);
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
