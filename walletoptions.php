<?php
require_once 'pdo.php';
session_start();
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
        <a href="sendmoney.php">Send Money</a></li>
        <a href="view_transactions.php">Transactions</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>

    <section class="user-options">
        <ul>
            <li><a href="add_funds.php">Add funds</a></li>
            <li><a href="transfer.php">Transfer</a></li>
            <?php 
            $sql = "SELECT is_default from accounts where account_id =:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id'=>$_GET['account']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            print_r($result);

            if($result['is_default'] === 0){
                echo'<li><a href="setdefaultaccount.php?account='.$_GET['account'].'">Set account as default</a></li>';

            };
            ?>
            
        </ul>
    </section>
    
</body>
</html>