<?php
require_once 'pdo.php';
session_start();
if($_SESSION ['authorised'] !== TRUE){
    header('Location:index.php');
}
$sql = "SELECT * from accounts where owner_id = :owner_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':owner_id'=>$_SESSION['user_id']]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <header>
    <nav class="navigation-bar">
        <a href="myaccount.php">Dashboard</a>
        <a href="accountslist.php">Accounts</a>
        <a href="sendmoney.php">Send Money</a></li>
        <a href="view_transactions.php">Transactions</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
    </header>
    <section class="user-options">
        <?php 
        foreach($rows as $row){
            echo '<section class="user-accounts">';
            echo '<p>name</p>';
            echo'<p>balance'.$row['balance'].'</p>';
            echo'<div class="open-acc-btn">';
            echo'<a href="walletoptions.php?account='.$row['account_id'].'">view</a>';
            echo'</div>';
            echo'</section>';
        }
        
        ?>
        
    </section>
    <section class="add-account">
            <form action="addnewwallets.php" method="post">
                <input type="submit" value="add account">
            </form>
        </section>
</body>
</html>