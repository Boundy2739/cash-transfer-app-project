<?php 
require_once "../includes/init.php";
userAuth();
$_SESSION['last_activity'] = time();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(csrfCheck()){
        //Select the wallet chose by the user
        $sql = "SELECT account_id,account_name from accounts where account_id =:account_id and owner_id=:owner_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":owner_id"=>$_SESSION['user_id'],
            ":account_id"=>$_SESSION['current_account']
        ));
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
        //If wallet is found rename it
        if($wallet){
            $sql ="UPDATE accounts SET account_name=:name where account_id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ":name"=>$_POST['new-wallet-name'],
                ":id"=>$wallet['account_id']
            ));

            redirect('wallet/walletoptions.php?account='.$wallet['account_id'].'');
           
        }

    }
}