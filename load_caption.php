<?php 
require_once('../../config.php');
$file = $_GET['file'];
$content = file_get_contents($file);
echo $content;