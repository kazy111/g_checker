<?php

include dirname(__FILE__) . '/header.php';


function register($arr){
  global $manager;

  $arr['id']='';
  $manager->set_legend($arr);
}

// if error occured, return error message / if it's ok, then return ''
function validate($data){

  if(!array_key_exists('body', $data)) return 'データが不正です';
  $text = $data['body'];

  if(mb_strlen($text, 'UTF-8') == 0) return '何か入力して下さい';
  if(mb_strlen($text, 'UTF-8') > 60) return '長過ぎます、60文字以内です';
  return '';
}

$message = '';
if ( array_key_exists('mode', $_POST) ) {

  $message = validate($_POST);
  
  if($message == ''){
    register(sanitize_array($_POST));
    
    $message = '更新しました';
  }else{
    $data = $_POST;
  }
}
$data = array();
$data['message'] = $message;

print($page->get_once('post_legend', $data));

?>
