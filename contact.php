<?php

// send mail

include 'header.php';

mb_language('ja'); // for mail encoding

function get_form(){
  global $db;

  $title   = sanitize_html(get_key($_POST, 'title'));
  $name    = sanitize_html(get_key($_POST, 'name'));
  $contact = sanitize_html(get_key($_POST, 'contact'));
  $body    = sanitize_html(get_key($_POST, 'body'));
  $now     = time();

  // display form

  return <<< EOD
    <form method="POST">
      <table><tbody>
      <tr><td><span class="form_title">name:</span></td>
          <td><input type="edit" name="name" value="$name" /></td></tr>
      <tr><td><span class="form_title">email:</span></td>
          <td><input type="edit" name="contact" value="$contact" /></td></tr>
      <tr><td><span class="form_title">title:</span></td>
          <td><input type="edit" name="title" value="$title" /></td></tr>
      <tr><td><span class="form_title">body:</span></td>
          <td><textarea name="body" cols="50" rows="10">$body</textarea></td></tr>
      </tbody></table>
      <input type="submit" value="submit" />
      <input type="hidden" name="test" value="$now" />
    </form>
EOD;

}

function check_and_submit(){
  global $_POST;

  $title   = '['.$GLOBALS['site_title'] .'] '. $_POST['title'];
  $name    = $_POST['name'];
  $contact = $_POST['contact'];
  $body    = $_POST['body'];
  $time    = $_POST['test'];
  
  if(time() - intval($time) < 5){
    return 'あなたはBOTですね？';
  }
  if($body == ''){
    return 'メッセージを入力して下さい';
  }

  mb_internal_encoding('UTF-8');
  mb_send_mail($GLOBALS['admin_mail'], $title, 'sender: ' . $name.' <'.$contact.">\n\n".$body);
  $_POST['title'] = '';
  $_POST['body']  = '';
  return '';
}

$msg = '';
if ( array_key_exists('test', $_POST) ) {
  // TODO validation

  $ret = check_and_submit();
  if($ret == ''){
    $msg = '投稿完了しました！';
  }else{
    $msg = '投稿失敗しました: '.$ret;
  }
}

$contents = get_form();
$data = array();
$data['contact_form'] = $contents;
$data['message'] = $msg;
$page->set('contact', $data);

include 'footer.php';
?>
