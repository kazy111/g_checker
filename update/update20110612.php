<?php
// add tag column to streamer_table

include '../header.php';


$sql = "alter table streamer_table add column enable SMALLINT default 1";
$manager->query($sql);

?>
