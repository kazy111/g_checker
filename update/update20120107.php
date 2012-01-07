<?php
// add tag column to streamer_table

include '../header.php';


$sql = "alter table program_table add column title varchar(255) default ''";
$manager->query($sql);

?>
