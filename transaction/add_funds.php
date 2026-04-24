<?php
require_once "../includes/init.php";
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location: myaccount.php');
    exit;
}
$_SESSION['last_activity'] = time();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $_SESSION['errorMessage'] = 'Invalid request.';
        header('Location: add_funds.php');
        exit;
    }
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    if ($amount === false || $amount <= 0) {
        $_SESSION['errorMessage'] = 'Invalid amount.';
        header('Location: add_funds.php');
        exit;
    }

    try {
        $pdo->beginTransaction();


        $sql = "SELECT balance from accounts where account_id =:id and owner_id=:owner_id FOR UPDATE"; /*Selects the row that contains the correct wallet and locks it to prevent race conditions */
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':id' => $_SESSION['current_account'], ':owner_id' => $_SESSION['user_id']));
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        $account['balance'] = $account['balance'] + $amount;
        $sql = "UPDATE accounts set balance = :balance WHERE account_id=:id and owner_id=:owner_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':balance' => $account['balance'],
            ':id' => $_SESSION['current_account'],
            ':owner_id' => $_SESSION['user_id']
        ));
        $sql = "INSERT into transactions(sender_wallet_id,receiver_wallet_id,type,amount,currency,status) 
    VALUES (:sender_id,:receiver_id,:type,:amount,:currency,:status)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':sender_id' => $_SESSION['current_account'],
            ':receiver_id' => $_SESSION['current_account'],
            ':type' => 'Deposit',
            ':amount' => $amount,
            ':currency' => 'Euro',
            ':status' => 'Successful',



        ));
        $pdo->commit();
    } catch (Exception $e) {
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
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h1>Add funds</h1>
    <form action="" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label for="amount">Amount</label>
        <input type="number" step="0.01" id="amount" name="amount">
        <input type="submit" value="add funds">
    </form>
</body>

</html>