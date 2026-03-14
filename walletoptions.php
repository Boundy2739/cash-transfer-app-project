<?php
require_once 'pdo.php';
session_start();
if ($_SESSION['authorised'] !== TRUE) {
    header('Location: index.php');
    exit;
} else {
    $_SESSION['login'] = TRUE;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<nav class="navigation-bar">
        <a href="myaccount.php">Dashboard</a>
        <a href="accountslist.php">Accounts</a>
        <a href="sendmoney.php">Send Money</a></li>
        <a href="view_transactions.php">Transactions</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>

    <section class="user-options">
        <ul>
            <li><a href="add_funds.php">Add funds</a></li>
            <li><a href="transfer.php">Transfer</a></li>
            <li><a href="setdefaultaccount.php">Set account as default/a></li>
        </ul>
    </section>
    
</body>
</html>