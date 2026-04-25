<?php 
function failedTransaction($pdo,$sender,$receiver,$message,$amount,$type){
    if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
        redirect('index.php');
        
    }
    $sql ="INSERT INTO transactions (sender_wallet_id,receiver_wallet_id,type,amount,currency,status,fail_reason) 
    VALUES (:sender_wallet_id,:receiver_wallet_id,:type,:amount,:currency,:status,:fail_reason)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":sender_wallet_id" => $sender,
        ":receiver_wallet_id" => $receiver,
        ":type" => $type,
        ":amount" => $amount,
        ":status" => 'failed',
        ":currency" => 'GBP',
        ":fail_reason" => $message
    ));
}


