<?php
// add tag column to streamer_table

include '../header.php';


$sql = "alter table streamer_table add column temporary smallint default 0";
$manager->query($sql);

?>
