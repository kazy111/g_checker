<?php
include 'header.php';

$data = array();
$data['site_title'] = $site_title;
//$data['contents'] = $contents;
$page->set('about', $data);

include 'footer.php';

?>

