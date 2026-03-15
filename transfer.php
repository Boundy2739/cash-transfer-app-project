<?php
require_once 'pdo.php';
session_start();
if ($_SESSION['authorised'] !== TRUE || empty($_SESSION['current_account'])) {
    header('Location: walletoptions.php');
    exit;
}
print_r($_SESSION);
$sql = "SELECT * from accounts where owner_id =:id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        if ($amount === false || $amount < 0) {
            die('Invalid amount.');
        }


        $sql = "SELECT * from accounts where account_id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_SESSION['current_account']]);
        $currentAcc = $stmt->fetch(PDO::FETCH_ASSOC);


        $sql = "SELECT * from accounts where account_id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_POST['chosen-account']]);
        $chosenAcc = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($currentAcc['account_id'] === $chosenAcc['account_id']) {
            throw new Exception("You cant transfer to the same account");
        }

        if ($ammount > $currentAcc['balance']) {
            throw new Exception("Not enough funds!");
        }


        $currentAcc['balance'] = $currentAcc['balance'] - $amount;

        $chosenAcc['balance'] = $chosenAcc['balance'] + $amount;

        $sql = "UPDATE accounts SET balance =:balance where account_id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":balance" => $currentAcc['balance'],
            ":id" => $currentAcc['account_id']
        ));

        $sql = "UPDATE accounts SET balance =:balance where account_id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":balance" => $chosenAcc['balance'],
            ":id" => $chosenAcc['account_id']
        ));
        $pdo->commit();
        print_r("Transaction successful");
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Transaction failed: " . $e->getMessage());
    }
};
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="POST">
        <label for="chosen-account">Choose an account</label>
        <select name="chosen-account" id="chosen-account">
            <option value="">Choose an account</option>
            <?php
            foreach ($rows as $row) {
                if ($row['account_id'] != $_SESSION['current_account']) {
                    echo "<option value='" . htmlspecialchars($row['account_id']) . "'>" . htmlspecialchars($row['account_name']) . "</option>";
                }
            }

            ?>

        </select>
        <label for="amount">Amount</label>
        <input type="number" id="amount" name="amount" min="1" step="0.01" placeholder="Enter amount" required>
        <input type="submit" value="transfer money">
    </form>

</body>

</html>