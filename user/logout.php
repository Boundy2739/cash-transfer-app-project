<?php 
require_once "../config/config.php";
require_once "../helpers/index.php";
print_r($_SESSION);
$_SESSION = [];
print_r($_SESSION);
session_destroy();
redirect("index.php");
exit;
?>