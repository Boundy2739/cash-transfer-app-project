<?php
require_once 'pdo.php';
require_once "config/config.php";
/*Ensures that the user is logged before accessing this page*/
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location: index.php');
    exit;
}
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
    /*Checks if the user has submitted a float value and the amount submitted is not less than 0*/
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    if ($amount === false || $amount < 0) {
        die('Invalid amount.');
    }

    try {
        $pdo->beginTransaction();
        /*Selects the wallet that matches the wallet_id given by the user*/
        $sql = "SELECT account_id, balance from accounts where account_id =:id AND owner_id = :owner_id FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':id' => $_POST['chosen-account'], ':owner_id' => $_SESSION['user_id']));
        $sender = $stmt->fetch(PDO::FETCH_ASSOC);
        print_r($sender);
        var_dump($sender['balance'], $amount);
        if (!$sender) {
            throw new Exception("Wallet doesn't exist");
        }
        if ($sender['balance'] < $amount) {
            throw new Exception('Not enough founds');
        };


        /*Removes white spaces in the recipient's username submitted by the user*/
        $recipientUsername = str_replace(" ", "", $_POST['recipient-username']);
        /*Selects the recipient's id from the row that matches the username given by the user*/
        $sql = "SELECT id from users where username=:username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $recipientUsername]);
        $recipientID =  $stmt->fetch(PDO::FETCH_ASSOC);
        print_r($recipientID);

        /*stops transaction if no matching usernames where found */
        if ($recipientID === false) {
            throw new Exception("Account does not exist.");
        }
        print_r("hi");
        /*This selects the default wallet of the recipient */
        $sql = "SELECT * from accounts where owner_id=:owner_id and is_default = TRUE FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':owner_id' => $recipientID['id']]);
        $recipientAcc = $stmt->fetch(PDO::FETCH_ASSOC);

        /*This updates the sender's wallet balance*/
        $sender['balance'] = $sender['balance'] - $amount;
        $sql = "UPDATE accounts set balance = :balance WHERE account_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':balance' => $sender['balance'],
            ':id' => $_POST['chosen-account'],
        ));



        print_r("hi");
        /*This updates the recipient's default wallet balance*/
        $recipientAcc['balance'] = $recipientAcc['balance'] + $amount;
        $sql = "UPDATE accounts set balance = :balance WHERE account_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':balance' => $recipientAcc['balance'],
            ':id' => $recipientAcc['account_id'],
        ));
        print_r($recipientAcc);
        $sql = "INSERT into transactions(sender_id,receiver_id,type,amount,currency,status) 
                    VALUES (:sender_id,:receiver_id,:type,:amount,:currency,:status)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':sender_id' => $_SESSION['user_id'],
            ':receiver_id' => $recipientID['id'],
            ':type' => 'transfer',
            ':amount' => $amount,
            ':currency' => 'EUR',
            ':status' => 'completed'



        ));
        $pdo->commit();
    } catch (Exception $e) {
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
    <nav class="navigation-bar">
        <a href="myaccount.php">Dashboard</a>
        <a href="accountslist.php">Accounts</a>
        <a href="sendmoney.php">Send Money</a></li>
        <a href="view_transactions.php">Transactions</a>
        <a href="profile.php">Profile</a>
    </nav>
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
</body>

</html>