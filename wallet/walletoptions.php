<?php
require_once "../includes/init.php";
if ($_SESSION['authorised'] !== TRUE) {
    header('Location: myaccount.php');
    exit;
}
$_SESSION['last_activity'] = time();
/*Selects the wallet where both the wallet id and owner id match respectively the ID in the URL and the id of the logged user
this is to prevent the user from accessing someone elses wallet by changing the URL*/
$sql = "SELECT account_id from accounts WHERE account_id=:account_id AND owner_id=:owner_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':owner_id'=>$_SESSION['user_id'],
    ':account_id'=>$_GET['account']
));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$result){
    header('Location: myaccount.php');
    exit;
}
$_SESSION['current_account'] = $_GET['account']; /*Stores the wallet ID from the URL in the session which will be used for other transactions later */

/*Selects the infos of the wallet that will be displayed on the page */
$sql = "SELECT account_name,balance,is_default from accounts where account_id =:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $_GET['account']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            print_r($result);
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
    </nav>

    <section class="account-header">
        <?php 
        /*Displays the name of the wallet and the current balance on screen */
        echo'<h2 class="account-name">'.$result['account_name'].'</h2>';
        echo'<div class="account-info">';
        echo'<span class="balance-label">Current Balance:</span>';
        echo'<span class="balance-amount">'." £".$result['balance'].'</span></div>';
        
        ?>     
    </section>

    <section class="user-options">
        <ul>
            <li><a href="add_funds.php">Add funds</a></li>
            <li><a href="transfer.php">Transfer</a></li>
            <li><a href="changewalletname.php">Change wallet's name</a></li>
            <?php
            /*Will display the option to set the wallet as default if it is not set yet */
            if ($result['is_default'] === 0) {
                echo '<li><a href="setdefaultaccount.php?account=' . $_SESSION['current_account'] . '">Set account as default</a></li>';
            };
            ?>

        </ul>
    </section>

</body>

</html>