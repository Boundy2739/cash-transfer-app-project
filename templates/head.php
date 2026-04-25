<?php 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <title><?php echo $title ?? "Document" ?></title>
</head>
<body>
<nav class="navigation-bar">
        <a href="<?php echo BASE_URL; ?>user/dashboard.php">Dashboard</a>
        <a href="<?php echo BASE_URL; ?>wallet/walletslist.php">Wallets</a>
        <a href="<?php echo BASE_URL; ?>transaction/sendmoney.php">Send Money</a>
        <a href="<?php echo BASE_URL; ?>transaction/view_transactions.php">Transactions</a>
        <a href="<?php echo BASE_URL; ?>user/profile.php">Profile</a>
</nav>