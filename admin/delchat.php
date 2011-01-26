<?php
include '../header.php';
// TOOD confirm

if(!array_key_exists('id', $_GET)) exit();
$id = $_GET['id'];

$result = $manager->get_chat($id);
if($result){
  $manager->delete_chat($id);
}

// redirect
header("HTTP/1.1 301 Moved Permanently");
header("Location: ".$site_url.'/admin/listchat.php');
exit();

?>
