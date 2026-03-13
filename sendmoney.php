<?php
require_once 'pdo.php';
require_once 'check_positive_balance.php';
session_start();
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location: login.php');
    exit;
}
$sql = "SELECT * from accounts where owner_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ":id"=>$_SESSION['user_id']
));
$rows = $stmt->fetchall(PDO::FETCH_ASSOC);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    if($amount === false || $amount < 0){
        die('Invalid amount.');
    }
    
    try{
        $pdo->beginTransaction();
        $sql = "SELECT * from accounts where account_id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id'=>$_POST['chosen-account']]);
        $sender = $stmt->fetch(PDO::FETCH_ASSOC);
        $funds = checkFunds($sender['balance'],$amount);
        
        if($funds === TRUE){
            
            $recipientUsername = str_replace(" ","",$_POST['recipient-username']);
            $sql = "SELECT id from users where username=:username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':username'=>$recipientUsername]);
            $recipientID =  $stmt->fetch(PDO::FETCH_ASSOC);
            print_r($recipientID);
            if ($recipientID === false) {
                throw new Exception ("Account does not exist.");
            }
            print_r("hi");
            $sql ="SELECT * from accounts where owner_id=:owner_id and is_default = TRUE";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':owner_id'=>$recipientID['id']]);
            $recipientAcc = $stmt->fetch(PDO::FETCH_ASSOC);

            $sender['balance'] = $sender['balance'] - $amount;
            $sql = "UPDATE accounts set balance = :balance WHERE account_id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':balance'=>$sender['balance'],
                ':id'=>$_POST['chosen-account'],
            ));
            

            
            print_r("hi");
            $recipientAcc['balance'] = $recipientAcc['balance'] + $amount;
            $sql = "UPDATE accounts set balance = :balance WHERE account_id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':balance'=>$recipientAcc['balance'],
                ':id'=>$recipientAcc['account_id'],
            ));
            print_r($recipientAcc);
            $sql = "INSERT into transactions(sender_id,receiver_id,type,amount,currency,status) 
                    VALUES (:sender_id,:receiver_id,:type,:amount,:currency,:status)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':sender_id'=> $_SESSION['user_id'],
                ':receiver_id'=> $recipientID['id'],
                ':type'=> 'transfer',
                ':amount'=> $amount,
                ':currency'=> 'EUR',
                ':status'=> 'completed'



            ));
            $pdo->commit();
        }
        else{
            throw new Exception("Not enough funds!");
        }
        
    }
    catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Transaction failed: " . $e->getMessage());
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
        <select name="chosen-account" id="chosen-account">
            <option value="none"></option>
            <?php
            foreach($rows as $row){
                echo "<option value='".htmlspecialchars($row['account_id'])."'>".htmlspecialchars($row['account_name'])."</option>";
            }
            
            ?>
        </select>
        <label for="recipient-username">Recipient's username</label>
        <input type="text" id="recipient-username" name="recipient-username" placeholder="insert the recipient's">
        <label for="amount">Amount</label>
        <input type="number" id="amount" name="amount" min="1" step="0.01" placeholder="Enter amount" required>
        <input type="submit" value="send money">
    </form>
</body>
</html>