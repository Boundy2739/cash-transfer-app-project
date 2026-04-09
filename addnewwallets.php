<?php
require 'pdo.php';
require_once "config/config.php";
require_once 'guid_generator.php';

if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location: accountslist.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $_SESSION['errorMessage'] = 'Invalid request.';
        header('Location: accountslist.php');
        exit;
    }
    
    $accountId = guidv4(); /*Creates an unique identifier for the wallet */
    $sql = "INSERT into accounts (account_id,owner_id,balance,currency,status) VALUES (:account_id,:owner_id,:balance,:currency,:status)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':account_id' => $accountId,
        ':owner_id' => $_SESSION['user_id'],
        ':balance' => 0.00,
        ':currency' => 'EUR',
        ':status' => 'active',
    ));
    header('Location: accountslist.php');
    exit;
}
?>