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
    <link rel="stylesheet" href="style.css">
    <title>Title</title>
</head>

<body>
    <nav class="navigation-bar">
        <a href="myaccount.php">Dashboard</a>
        <a href="accountslist.php">Accounts</a>
        <a href="sendmoney.php">Send Money</a></li>
        <a href="view_transactions.php">Transactions</a>
        <a href="profile.php">Profile</a>
    </nav>

    <?php
    $sql = "SELECT firstname from users where id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo 'Greetings ' . $user['firstname'] . ' !';
    print_r($_SESSION);
    ?>
    
</body>

</html>