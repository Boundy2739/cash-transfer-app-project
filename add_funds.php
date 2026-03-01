<?php 
require_once 'pdo.php';


session_start();
if($_SESSION['authorized']!==TRUE){
    header('Location: loggedin.php');
    exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    if($amount === false || $amount < 0){
        die('Invalid amount.');
    }
    $pdo->beginTransaction();
    try{

    
    $sql = "SELECT * from accounts where id =:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id'=>$_SESSION['account_id']]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($account);
    $account['balance'] = $account['balance'] + $amount;
    $sql = "UPDATE accounts set balance = :balance WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':balance'=>$account['balance'],
        ':id'=>$_SESSION['account_id'],
    ));
    $sql = "INSERT into transactions(sender_id,receiver_id,type,amount,currency,status) 
    VALUES (:sender_id,:receiver_id,:type,:amount,:currency,:status)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':sender_id'=> null,
        ':receiver_id'=> $_SESSION['account_id'],
        ':type'=> 'Deposit',
        ':amount'=> $amount,
        ':currency'=> 'Euro',
        ':status'=> 'Successful'



    ));
    $pdo->commit();
    }
    catch (Exception $e) {
        $pdo->rollBack();
        die("Transaction failed.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add funds</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Add funds</h1>
    <form action="" method="post">
        <label for="amount">Amount</label>
        <input type="number" step="0.01" id="amount" name="amount">
        <input type="submit" value="add funds">
    </form>
</body>
</html>