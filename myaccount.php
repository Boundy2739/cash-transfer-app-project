<?php
require_once 'pdo.php';
session_start();
if($_SESSION['authorized'] !== TRUE){
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Title</title>
</head>
<body>
    <h1>Cash transfer app</h1>
    <h2>Options menu</h2>
    <?php
    $sql = "SELECT * from accounts where id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id'=>$_SESSION['account_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo 'Greetings '. $user['firstname'] .' !';
    ?>
    <section class="user-options">
        <ul>
            <li><a href="add_funds.php">Add funds</a></li>
            <li><a href="sendmoney.php">Send money</a></li>
            <li><a href="view_transactions.php">View transactions</a></li>
            <li><a href="sendmoney.php">Add funds</a></li>
            <li><a href="sendmoney.php">Change password</a></li>
            <li><a href="sendmoney.php">Freeze account</a></li>
        </ul>
    </section>
</body>
</html>