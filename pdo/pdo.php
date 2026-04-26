<?php
require_once "dbcredentials.php";
//use port 8889 on a mac
$pdo = new PDO(
   "mysql:host=$host;port=$port;dbname=$name;charset=utf8",
   $username,
   $password
);
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);