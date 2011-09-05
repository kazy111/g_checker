<?php
// TODO sanitize, validation

include '../header.php';

// edit form & register
// if no id, create new entry

// get args from post

function view_form($id){
  global $manager;
  
  $name = '';
  $description = '';
  $twitter = '';
  $url = '';
  $wiki = '';
  $tag = '';
  
  if($id){
    // get info from DB
    $result = $manager->get_streamer($id);
    if($result){
      $name = $result['name'];
      $description = $result['description'];
      $twitter = $result['twitter'];
      $url = $result['url'];
      $wiki = $result['wiki'];
      $tag = $result['tag'];
    }
  }
  // display form

  return <<< EOD
    <form method="POST">
      <input type="hidden" name="mode" value="1" />
      <input type="hidden" name="id" value="$id" />
      <span class="form_title">Name:</span>
      <input type="edit" name="name" value="$name" /><br />
      <span class="form_title">Description:</span>
      <input type="edit" name="description" value="$description" /><br />
      <span class="form_title">Twitter ID:</span>
      <input type="edit" name="twitter" value="$twitter" /><br />
      <span class="form_title">Wiki ID:</span>
      <input type="edit" name="wiki" value="$wiki" /><br />
      <span class="form_title">URL:</span>
      <input type="url" name="url" value="$url" /><br />
      <span class="form_title">Tag:</span>
      <input type="tag" name="tag" value="$tag" /><br />
      <input type="submit" value="submit" />
    </form>
EOD;
  
}

function register_streamer(){
  global $_POST, $manager;

  $manager->set_streamer($_POST);

}

$contents = '';
if ( array_key_exists('mode', $_POST) ) {
  // TODO validation
  
  register_streamer();
  $contents .= '<span class="message">updated information</span>';
}

$contents .= view_form(get_key($_GET, 'id'));
$data = array();
$data['contents'] = $contents;
$page->set_relative_dir_to_top('..');
$page->set('raw', $data);

include '../footer.php';
?>
