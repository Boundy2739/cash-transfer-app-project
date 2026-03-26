<?php
require_once 'pdo.php';
session_start();
if ($_SESSION['authorised'] !== TRUE) {
    header('Location: index.php');
    exit;
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

    <?php
    /*This will echo an heading that greets the user*/
    $sql = "SELECT firstname from users where id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo '<h1>Greetings ' . $user['firstname'] . ' ! </h1>';
    ?>
    <section>
        <h2>Account summary</h2>
        <div>
            <p>Number of wallets: </p>
            <p>Total balance: </p>
            <p>Status: </p>
        </div>
    </section>
    <section>
        <h2>Recent activity</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Actvity</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </table>
    </section>

</body>

</html>