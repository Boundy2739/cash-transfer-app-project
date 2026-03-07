<?php
require_once 'pdo.php';
session_start();
if($_SESSION ['authorised'] === TRUE){
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <section class="account">
            <p>name</p>
            <p>balance</p>
            <a href="">open</a>
        </section>
        <section class="add-account">
            <form action="addnewwallet.php" method="post">
                <input type="submit">
            </form>
        </section>
    </div>
</body>
</html>