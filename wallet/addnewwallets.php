<?php
require_once "../includes/init.php";


if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    redirect('index.php');
}
$_SESSION['last_activity'] = time();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        userError("Invalid request");
        redirect('wallet/walletslist.php');
        }
    
    // Get the number of wallets currently owned by the user
    $sql = "SELECT COUNT(*) from accounts where owner_id =:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id"=>$_SESSION['user_id']]);
    $numOfWallets = $stmt->fetchColumn();
    
    //The user will be blocked from adding more wallets if they have already 8
    if($numOfWallets >= 8 ){
        userError("You can have only up to 8 wallets");
        redirect('wallet/walletslist.php');
        
    }
    //This sets the first wallet ever created as primary
    $isDefault = ($numOfWallets === 0) ? 1 : 0;

    $accountId = guidv4(); /*Creates an unique identifier for the wallet */
    $sql = "INSERT into accounts (account_id,account_name,owner_id,balance,currency,status,is_default) VALUES (:account_id,:account_name,:owner_id,:balance,:currency,:status,:is_default)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':account_id' => $accountId,
        ':account_name'=> $_POST['wallet-name'],
        ':owner_id' => $_SESSION['user_id'],
        ':balance' => 0.00,
        ':currency' => 'GBP',
        ':status' => 'active',
        ':is_default' => $isDefault
    ));
    redirect('wallet/walletslist.php');
    exit;
}
?>