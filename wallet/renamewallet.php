<?php 
require_once "../includes/init.php";
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location: accountslist.php');
    exit;
}
$_SESSION['last_activity'] = time();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!csrfCheck()){
        $sql = "SELECT account_id,account_name from accounts where account_id =:account_id and owner_id=:owner_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":owner_id"=>$_SESSION['user_id'],
            ":account_id"=>$_SESSION['current_account']
        ));
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
        if($wallet){
            $sql ="UPDATE accounts SET account_name=:name where account_id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ":name"=>$_POST['new-wallet-name'],
                ":id"=>$wallet['account_id']
            ));

            header('Location: walletoptions.php?account='.$wallet['account_id'].'');
            exit;
        }

    }
}