<?php
include '../header.php';
// TOOD confirm

if(!array_key_exists('id', $_GET)) exit();
$id = $_GET['id'];

$result = $manager->get_legend($id);
if($result){
  $manager->delete_legend($id);
}

// redirect
header("HTTP/1.1 301 Moved Permanently");
header("Location: ".$site_url.'/admin/list_legend.php');
exit();

?>
