<?php
include '../header.php';
// TOOD confirm

$id = $_GET['id'];
if(!$id) exit();

$sql = 'select p.id as id '
      .' from program_table as p, chat_table as c '
      .' where c.id = '.$id.' and c.id = p.chat_id';
$result = $db->query($sql);

$flag = FALSE;
while($arr = $db->fetch($result)){
  $flag = TRUE;
}

if(!$flag){
  $db->query('delete from chat_table where id = '.$id);
}

// redirect
header("HTTP/1.1 301 Moved Permanently");
header("Location: ".$site_url.'/admin/listchat.php');
exit();

?>
