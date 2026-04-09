<?php
require_once 'pdo.php';
require_once "config/config.php";
if ($_SESSION['authorised'] !== TRUE || empty($_SESSION['current_account'])) {
    header('Location: walletoptions.php');
    exit;
}
print_r($_SESSION);
/*This selects all the rows where the wallet's owner id mathces the id of the logged in user */
$sql = "SELECT * from accounts where owner_id =:id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'], $_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    try {
        /*Checks if the amount submitted is valid */
        $pdo->beginTransaction();
        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        if ($amount === false || $amount < 0) {
            throw new Exception('Invalid amount.');
        }

        /*selects the row where the wallet id matches the id of the wallet the user has currently open*/
        $sql = "SELECT * from accounts where account_id =:id and owner_id =:owner_id FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':id' => $_SESSION['current_account'],':owner_id'=>$_SESSION['user_id']));
        $currentAcc = $stmt->fetch(PDO::FETCH_ASSOC);

        /*selects the row where the wallet id matches the id of the wallet the user chose from the drop down list*/
        $sql = "SELECT * from accounts where account_id =:id and owner_id =:owner_id FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':id' => $_POST['chosen-account'],':owner_id'=>$_SESSION['user_id']));
        $chosenAcc = $stmt->fetch(PDO::FETCH_ASSOC);

        /*Prevent the user from transfering money to the same wallet they have currently opened*/
        if ($currentAcc['account_id'] === $chosenAcc['account_id']) {
            throw new Exception("You cant transfer to the same account");
        }

        if ($amount > $currentAcc['balance']) {
            throw new Exception("Not enough funds!");
        }


        $currentAcc['balance'] = $currentAcc['balance'] - $amount;

        $chosenAcc['balance'] = $chosenAcc['balance'] + $amount;
        /*Updates the balance of the wallet the user has currently open*/
        $sql = "UPDATE accounts SET balance =:balance where account_id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":balance" => $currentAcc['balance'],
            ":id" => $currentAcc['account_id']
        ));
        /*Updates the balance of the wallet the user choose to transfer money to */
        $sql = "UPDATE accounts SET balance =:balance where account_id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":balance" => $chosenAcc['balance'],
            ":id" => $chosenAcc['account_id']
        ));
        $pdo->commit();
    } 
    catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Transaction failed: " . $e->getMessage());
    }
}
else{
    header('Location: index.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <nav class="navigation-bar">
        <a href="myaccount.php">Dashboard</a>
        <a href="accountslist.php">Accounts</a>
        <a href="sendmoney.php">Send Money</a>
        <a href="view_transactions.php">Transactions</a>
        <a href="profile.php">Profile</a>
    </nav>
    <form action="" method="POST">
        <label for="chosen-account">Choose an account</label>
        <select name="chosen-account" id="chosen-account">
            <option value="">Choose an account</option>
            <?php
            foreach ($rows as $row) {
                /*Will display the names of all wallets owned by the user except the one that is opened for transfer money to the others
                ,the id of the wallets will be set as the value for each option */
                if ($row['account_id'] != $_SESSION['current_account']) {
                    echo "<option value='" . htmlspecialchars($row['account_id']) . "'>" . htmlspecialchars($row['account_name']) . "</option>";
                }
            }

            ?>

        </select>
        <input type="hidden" name="csrf_token" <?php echo 'value=' . htmlspecialchars($_SESSION['csrf_token']) . '' ?>>
        <label for="amount">Amount</label>
        <input type="number" id="amount" name="amount" min="1" step="0.01" placeholder="Enter amount" required>
        <input type="submit" value="transfer money">
    </form>

</body>

</html>