<?php
require_once 'pdo.php';
require_once "config/config.php";
if ($_SESSION['authorised'] !== TRUE) {
    header('Location: myaccount.php');
    exit;
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

    <nav class="navigation-bar">
        <a href="myaccount.php">Dashboard</a>
        <a href="accountslist.php">Accounts</a>
        <a href="sendmoney.php">Send Money</a>
        <a href="view_transactions.php">Transactions</a>
        <a href="profile.php">Profile</a>
    </nav>

    <section class="user-options">
        <ul>
            <li><a href="edit_profile.php">View profile</a></li>
            <li><a href="currentpassword.php">Change password</a></li>
            <li><a href="freezeaccount.php">Freeze account</a></li>
            <li><a href="logout.php">Logout</a></li>

        </ul>
    </section>
</body>

</html>