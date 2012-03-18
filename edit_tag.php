<?php
// TODO validation

include dirname(__FILE__) . '/header.php';

// edit form & register
// if no id, create new entry

function get_info($id){
  global $manager;

  $data = array();
  $tag = '';
  $name = '';

  if($id){
    // get info from DB
    $result = $manager->get_streamer($id);
    if($result){
      $tag = $result['tag'];
      $name = $result['name'];
    }
  }

  $data['id'] = $id;
  $data['tag'] = $tag;
  $data['name'] = $name;

  return $data;
}

function register_program($arr){
  global $_POST, $manager;

  $manager->set_tag($arr);
}

// if error occured, return error message / if it's ok, then return ''
function validate($data){

  if(!array_key_exists('tag', $data)) return 'データが不正です';
  $tags = explode(',', $data['tag']);
  if(count($tags) > 8) return 'タグが多過ぎます、8個までです';
  foreach($tags as $tag){
    if(mb_strlen($tag, 'UTF-8') > 20) return '長過ぎるタグがあります、20文字以内です';
  }
  return '';
}

$data = get_info(get_key($_GET, 'id'));
$message = '';

if ( array_key_exists('mode', $_POST) ) {
  // TODO validation

  $message = validate($_POST);
  
  if($message == ''){
    register_program(sanitize_array($_POST));
    // TODO error check
    
    $data = get_info(get_key($_GET, 'id'));
    $message = '更新しました';
  }else{
    $data = $_POST;
  }
}

$data['message'] = $message;
$data['name'] = 

print($page->get_once('edit_tag', $data));

?>
