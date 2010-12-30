<?php
include '../header.php';
// TOOD confirm

$id = $_GET['id'];
if(!$id) exit();

$sql = 'select id from program_table where id = '.$id;
$result = $db->query($sql);
$pids = array();
while($arr = $db->fetch($result)){
$pids[$arr['id']] = 1;
}
foreach($pids as $k => $v){
  $db->query('delete from program_table where id = '.$k);
}

// redirect
header("HTTP/1.1 301 Moved Permanently");
header("Location: ".$site_url.'/admin/listch.php');
exit();

?>
