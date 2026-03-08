<?php
require 'pdo.php';
session_start();
require_once 'guid_generator.php';

if($_SESSION['authorised'] !== TRUE){
    header('Location: accountlist.php');
    exit;
}
$accountId = guidv4();
$sql = "INSERT into accounts (account_id,owner_id,balance,currency,status) VALUES (:account_id,:owner_id,:balance,:currency,:status)";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':account_id'=>$accountId,
    ':owner_id'=>$_SESSION['user_id'],
    ':balance'=>0.00,
    ':currency'=>'EUR',
    ':status'=>'active',
));
header('Location: accountslist.php');
exit;
?>