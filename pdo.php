<?php
//use port 8889 on a mac
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=money-transfer-app', 
   'Abdoulaye', '2001');
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);