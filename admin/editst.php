<?php
// TODO sanitize, validation

include '../header.php';

// edit form & register
// if no id, create new entry

// get args from post

function view_form($id){
  global $db;
  
  $name = '';
  $description = '';
  $twitter = '';
  $url = '';
  $wiki = '';
  
  if($id){
    // get info from DB
    // $name $descriptioin, twitter,
    $result = $db->query('select id, name, description, twitter, url, wiki from streamer_table where id = '.$id);
    if($result){
      $arr = $db->fetch($result);
      $name = $arr['name'];
      $description = $arr['description'];
      $twitter = $arr['twitter'];
      $url = $arr['url'];
      $wiki = $arr['wiki'];
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
      <input type="submit" value="submit" />
    </form>
EOD;
  
}

function register_streamer(){
  global $_POST, $db;

  if($_POST['id'] && $_POST['id']!=''){
    // update
    $db->query('update streamer_table set name = \''
               .$_POST['name'].'\', description = \''.$_POST['description']
               .'\', twitter = \''.$_POST['twitter'].'\', url = \''
               .$_POST['url'].'\', wiki = '.($_POST['wiki']&&$_POST['wiki']!='' ?$_POST['wiki']:'NULL' ).' where id='.$_POST['id']);
  } else {
    // create
    $sql = 'insert into streamer_table (name, description, twitter, url, wiki) values (\''
               .$_POST['name'].'\', \''.$_POST['description'].'\', \''
               .$_POST['twitter'].'\', \''.$_POST['url'].'\', '.$_POST['wiki'].')';
    $db->query($sql);
  }
}

$contents = '';
if ( $_POST['mode'] ) {
  // TODO validation
  
  register_streamer();
  $contents .= '<span class="message">updated information</span>';
}

$contents .= view_form($_GET['id']);
$data = array();
$data['contents'] = $contents;
$page->set('raw', $data);

include '../footer.php';
?>
