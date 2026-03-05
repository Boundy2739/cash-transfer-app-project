<?php 
session_start();
print_r($_SESSION);
$_SESSION = [];
print_r($_SESSION);
session_destroy();
header('Location: index.php');
exit;
?>