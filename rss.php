<?php
include 'header.php';
include 'index_content.php';

$page->theme = '_rss';
$page->set('index', display_list($GLOBALS['default_sort'], ''));



include 'footer.php';
?>
