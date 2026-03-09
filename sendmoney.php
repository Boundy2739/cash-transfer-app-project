<?php
require_once 'pdo.php';
require_once 'check_positive_balance.php';
session_start();
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location: login.php');
    exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    print_r("something");
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    if($amount === false || $amount < 0){
        die('Invalid amount.');
    }
    $pdo->beginTransaction();
    try{
        $sql = "SELECT * from accounts where id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id'=>$_SESSION['account_id']]);
        $sender = $stmt->fetch(PDO::FETCH_ASSOC);
        print_r($sender);
        $funds = checkFunds($sender['balance'],$amount);
        print_r($funds);
        if($funds === TRUE){
            $sender['balance'] = $sender['balance'] - $amount;
            $sql = "UPDATE accounts set balance = :balance WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':balance'=>$sender['balance'],
                ':id'=>$_SESSION['account_id'],
            ));
            $recipientID = filter_input(INPUT_POST,'accnumber',FILTER_VALIDATE_INT);
            $sql = "SELECT * from accounts where id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id'=>$recipientID]);
            print_r($sender);
            $recipient =  $stmt->fetch(PDO::FETCH_ASSOC);
            if ($recipient === false) {
                echo "Account does not exist.";
                die("Transaction failed");
            }
            print_r($recipient);
            $recipient['balance'] = $recipient['balance'] + $amount;
            $sql = "UPDATE accounts set balance = :balance WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':balance'=>$recipient['balance'],
                ':id'=>$_POST['accnumber'],
            ));
            print_r($recipient);
            $sql = "INSERT into transactions(sender_id,receiver_id,type,amount,currency,status) 
                    VALUES (:sender_id,:receiver_id,:type,:amount,:currency,:status)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':sender_id'=> $_SESSION['account_id'],
                ':receiver_id'=> $recipientID,
                ':type'=> 'transfer',
                ':amount'=> $amount,
                ':currency'=> 'EUR',
                ':status'=> 'completed'



            ));
            $pdo->commit();
        }
        else{
            die('Not enough funds!');
        }
        
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
    <link rel="stylesheet" href="style.css">
    <title>Title</title>
</head>
<body>
    <h1>Cash transfer app</h1>
    <form action="" method="POST">
        <label for="firstname">Name</label>
        <input type="text" id="firstname" name="firstname">
        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname">
        <label for="accnumber">Recipient Account number</label>
        <input type="number" id="accnumber" name="accnumber" placeholder="insert the account number">
        <label for="amount">Amount</label>
        <input type="number" id="amount" name="amount" min="1" step="0.01" placeholder="Enter amount" required>
        <input type="submit" value="send money">
    </form>
</body>
</html>