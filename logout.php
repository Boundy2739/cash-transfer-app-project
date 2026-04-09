<?php 
require_once "config/config.php";
print_r($_SESSION);
$_SESSION = [];
print_r($_SESSION);
session_destroy();
header('Location: index.php');
exit;
?>