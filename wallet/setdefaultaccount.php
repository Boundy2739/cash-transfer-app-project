<?php
require_once '../pdo/pdo.php';
require_once "../config/config.php";
try{
$_SESSION['last_activity'] = time();
$pdo->beginTransaction();
/*This selects the current defualt wallet owned by the user and set it to not default*/
$sql = "UPDATE accounts SET is_default =:is_default where is_default = 1 AND owner_id =:id";
$stmt = $pdo->prepare($sql);

$stmt->execute(array(
    ':is_default'=>0,
    ':id'=>$_SESSION['user_id']
));

/*This will update the wallet choosen by the user and set it as the new default */
$sql = "UPDATE accounts SET is_default =:is_default where account_id = :id";
$stmt = $pdo->prepare($sql);

$stmt->execute(array(
    ':is_default'=>TRUE,
    ':id' =>$_GET['account']

));

$pdo->commit();
header('Location: accountslist.php');
exit;
}
catch(Exception $e){
    die("Transaction failed");
}

?>