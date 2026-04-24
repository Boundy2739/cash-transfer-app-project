<?php
require_once "../includes/init.php";
require_once "failedtransaction.php";
/*Ensures that the user is logged before accessing this page*/
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location: ../index.php');
    exit;
}
$_SESSION['last_activity'] = time();
$recipientAcc['account_id'] = null;
$isTransactionStarted = false;
/*Selects all the wallets where the owner's id matches the logged user id*/
$sql = "SELECT * from accounts where owner_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ":id" => $_SESSION['user_id']
));
$rows = $stmt->fetchall(PDO::FETCH_ASSOC);
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['csrf_token'], $_SESSION['csrf_token']) &&
    hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {


    try {
        $pdo->beginTransaction();
        /*Selects the wallet that matches the wallet_id given by the user*/
        $sql = "SELECT account_id, balance from accounts where account_id =:id AND owner_id = :owner_id FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':id' => $_POST['chosen-account'], ':owner_id' => $_SESSION['user_id']));
        $sender = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$sender) {
            throw new Exception("Wallet doesn't exist");
        }

        $isTransactionStarted = true;
        /*Removes white spaces in the recipient's username submitted by the user*/
        $recipientUsername = str_replace(" ", "", $_POST['recipient-username']);
        /*Selects the recipient's id from the row that matches the username given by the user*/
        $sql = "SELECT id,firstname,lastname from users where username=:username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $recipientUsername]);
        $recipientID =  $stmt->fetch(PDO::FETCH_ASSOC);


        /*stops transaction if no matching usernames where found */
        if ($recipientID === false) {
            throw new Exception("Non existent recipient.");
        }
        
        
        /*This selects the default wallet of the recipient */
        $sql = "SELECT * from accounts where owner_id=:owner_id and is_default = TRUE FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':owner_id' => $recipientID['id']]);
        $recipientAcc = $stmt->fetch(PDO::FETCH_ASSOC);
        /*Checks if the user has submitted a float value and the amount submitted is not less than 0*/
        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        if ($amount === false || $amount < 0) {
            throw new Exception('Invalid amount.');
        }

        if ($sender['balance'] < $amount) {
            throw new Exception('Not enough founds');
        };


        /*This updates the sender's wallet balance*/
        $sender['balance'] = $sender['balance'] - $amount;
        $sql = "UPDATE accounts set balance = :balance WHERE account_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':balance' => $sender['balance'],
            ':id' => $_POST['chosen-account'],
        ));




        /*This updates the recipient's default wallet balance*/
        $recipientAcc['balance'] = $recipientAcc['balance'] + $amount;
        $sql = "UPDATE accounts set balance = :balance WHERE account_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':balance' => $recipientAcc['balance'],
            ':id' => $recipientAcc['account_id'],
        ));
        $sql = "INSERT into transactions(sender_wallet_id,receiver_wallet_id,type,amount,currency,status) 
                    VALUES (:sender_wallet_id,:receiver_wallet_id,:type,:amount,:currency,:status)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':sender_wallet_id' => $sender['account_id'],
            ':receiver_wallet_id' => $recipientAcc['account_id'],
            ':type' => 'transfer',
            ':amount' => $amount,
            ':currency' => 'EUR',
            ':status' => 'successful'



        ));
        $pdo->commit();

        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            showPopup(
                "success",
                ' . json_encode($amount) . ',
                ' . json_encode($recipientID['firstname']) . ',
                ' . json_encode($recipientID['lastname']) . '
            );
        });
        </script>';
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        if($isTransactionStarted){
            failedTransaction($pdo, $sender['account_id'], $recipientAcc['account_id'], $e->getMessage(), $amount, 'transfer');
        }
        
        
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Title</title>
</head>

<body>
    <h1>Send money</h1>
    <form action="" method="POST">
        <label for="chosen'account">Choose wallet:</label>
        <select name="chosen-account" id="chosen-account">
            <option value="none"></option>
            <?php
            /*This will display the name of all the wallets the user owns in the dropdown list, 
            and the id of the wallet will be set as the value of the option*/
            foreach ($rows as $row) {
                echo "<option value='" . htmlspecialchars($row['account_id']) . "'>" . htmlspecialchars($row['account_name']) . "</option>";
            }
            ?>
        </select>
        <input type="hidden" name="csrf_token" <?php echo 'value=' . htmlspecialchars($_SESSION['csrf_token']) . '' ?>>
        <label for="recipient-username">Recipient's username</label>
        <input type="text" id="recipient-username" name="recipient-username" placeholder="insert the recipient's">
        <label for="amount">Amount</label>
        <input type="number" id="amount" name="amount" min="1" step="0.01" placeholder="Enter amount" required>
        <input type="submit" value="send money">
    </form>

    <div class="overlay hidden" id="transaction-popup">
        <div class="popup-card">

            <h2 id="popup-title"></h2>

            <p id="popup-message">

            </p>

            <button onclick="closePopup()">OK</button>
        </div>
    </div>
    <script src="../javaScript/app.js"></script>
</body>

</html>