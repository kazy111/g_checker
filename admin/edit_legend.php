<?php
// TODO sanitize, validation

include '../header.php';

// edit form & register
// if no id, create new entry

function get_form($id){
  global $manager;

  $body = '';

  if($id){
    // get info from DB
    $result = $manager->get_legend($id);
    if($result){
      $body = sanitize_html($result['body']);
    }
  }

  // display form

  return <<< EOD
    <form method="POST">
      <input type="hidden" name="mode" value="1" />
      <input type="hidden" name="id" value="$id" />
      <span class="form_title">body:</span>
      <textarea name="body" cols="40" rows="10">$body</textarea><br />
      <input type="submit" value="submit" />
    </form>
EOD;

}

function register(){
  global $_POST, $manager;

  $manager->set_legend($_POST);
}

$contents = '';
if ( array_key_exists('mode', $_POST) ) {
  // TODO validation
  
  register();
  $contents .= '<span class="message">updated information</span>';
}

$contents .= get_form(get_key($_GET, 'id'));
$data = array();
$data['contents'] = $contents;
$page->set_relative_dir_to_top('..');
$page->set('raw', $data);

include '../footer.php';
?>
