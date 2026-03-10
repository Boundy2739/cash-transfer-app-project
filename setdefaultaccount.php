<?php
require_once "pdo.php";
session_start();
try{
print_r("hi");
$pdo->beginTransaction();
$sql = "UPDATE accounts SET is_default =:is_default where is_default = 1 AND owner_id =:id";
$stmt = $pdo->prepare($sql);
print_r("hi");
$stmt->execute(array(
    ':is_default'=>0,
    ':id'=>$_SESSION['user_id']
));
print_r("hi");
$sql = "UPDATE accounts SET is_default =:is_default where account_id = :id";
$stmt = $pdo->prepare($sql);
print_r("hi");
$stmt->execute(array(
    ':is_default'=>TRUE,
    ':id' =>$_GET['account']

));
print_r("hi");
$pdo->commit();
header('Location: accountslist.php');
exit;
}
catch(Exception $e){
    die("Transaction failed");
}

?>