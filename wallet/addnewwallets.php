<?php
require_once "../includes/init.php";
require_once '../guid_generator.php';

if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location: accountslist.php');
    exit;
}
$_SESSION['last_activity'] = time();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $_SESSION['errorMessage'] = 'Invalid request.';
        header('Location: walletslist.php');
        exit;
    }
    
    $sql = "SELECT COUNT(*) from accounts where owner_id =:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id"=>$_SESSION['user_id']]);
    $numOfWallets = $stmt->fetchColumn(PDO::FETCH_ASSOC);

    if($numOfWallets >= 8 ){
        $_SESSION['errorMessage'] = 'You can have only up to 8 wallets.';
        header('Location: walletslist.php');
        exit;
    }
    $isDefault = ($walletCount === 0) ? 1 : 0;

    $accountId = guidv4(); /*Creates an unique identifier for the wallet */
    $sql = "INSERT into wallets (account_id,owner_id,balance,currency,status,is_default) VALUES (:account_id,:owner_id,:balance,:currency,:status,:is_default)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':account_id' => $accountId,
        ':owner_id' => $_SESSION['user_id'],
        ':balance' => 0.00,
        ':currency' => 'EUR',
        ':status' => 'active',
        ':is_default' => $isDefault
    ));
    header('Location: accountslist.php');
    exit;
}
?>