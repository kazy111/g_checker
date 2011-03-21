<?php
// add tag column to streamer_table

include '../header.php';


$sql = "alter table streamer_table add column tag TEXT default ''";
$manager->query($sql);

?>
