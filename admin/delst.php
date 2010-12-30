<?php
include '../header.php';
// TOOD confirm

$id = $_GET['id'];
if(!$id) exit();

$sql = 'select c.id as id '
      .' from streamer_table as s, program_table as p, chat_table as c '
      .' where s.id = '.$id.' and  s.id = p.streamer_id and c.id = p.chat_id';
$result = $db->query($sql);
while($arr = $db->fetch($result)){
  $db->query('delete from chat_table where id = '.$arr['id']);
}

$sql = 'select p.id as id '
      .' from streamer_table as s, program_table as p '
      .' where s.id = '.$id.' and  s.id = p.streamer_id';
$result = $db->query($sql);
while($arr = $db->fetch($result)){
  $db->query('delete from program_table where id = '.$arr['id']);
}

$sql = 'select id from streamer_table where id = '.$id;
$result = $db->query($sql);
while($arr = $db->fetch($result)){
  $db->query('delete from streamer_table where id = '.$arr['id']);
}

// redirect
header("HTTP/1.1 301 Moved Permanently");
header("Location: ".$site_url.'/admin/listst.php');
exit();

?>
