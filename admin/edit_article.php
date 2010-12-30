<?php
// TODO sanitize, validation

// TODO dropdown box of steamer
// TODO editchat.php -> linktable dropdownbox

include '../header.php';

// edit form & register
// if no id, create new entry

function get_form($id){
  global $db;

  $title = '';
  $body = '';
  $priority = 0;

  if($id){
    // get info from DB
    $result = $db->query('select title, body, created, priority from article_table where id = '.$id);
    if($result){
      $arr = $db->fetch($result);
      $title = sanitize_html($arr['title']);
      $body = sanitize_html($arr['body']);
      $priority = sanitize_html($arr['priority']);
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
      <textarea name="body">$body</textarea><br />
      <span class="form_title">priority:</span>
      <input type="edit" name="priority" value="$priority" /><br />
      <input type="submit" value="submit" />
    </form>
EOD;

}

function register_program(){
  global $_POST, $db;

  if($_POST['id'] && $_POST['id']!=''){
    // update
    $db->query('update article_table set title = \''.$_POST['title'].'\', body = \''
               .$_POST['body'].'\', priority = '.$_POST['priority'].' where id='.$_POST['id']);
  } else {
    // create
    $now = date('Y-m-d H:i:s');
    $sql = 'insert into article_table (title, body, priority, created) values (\''
               .$_POST['title'].'\', \''.$_POST['body'].'\', '.$_POST['priority'].', \''.$now.'\')';
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
