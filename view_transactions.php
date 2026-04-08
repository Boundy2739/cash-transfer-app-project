<?php
require_once 'pdo.php';
session_start();

if ($_SESSION['authorised'] !== TRUE) {
    header('Location: index.php');
    exit;
}
$sql = "SELECT t.sender_id, t.receiver_id, t.type, t.amount, t.currency, t.transaction_date,s.firstname as sender_name,r.firstname as receiver_name from transactions t
INNER JOIN users s ON t.sender_id = s.id
INNER JOIN users r ON t.receiver_id = r.id
WHERE t.sender_id = :id or t.receiver_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$rows = $stmt->fetchall(PDO::FETCH_ASSOC);
print_r($rows);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
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
    <h1>Viewing transactions</h1>
    <section class="transactions-container">
        <?php
        foreach ($rows as $row) {
            echo '<section class="transaction-record"><div>';
            if ($row['sender_id'] == $_SESSION['user_id']) {
                echo '<p>' . htmlentities($row['receiver_name']) . '</p>';
            } elseif ($row['receiver_id'] == $_SESSION['user_id']) {
                echo '<p>' . htmlentities($row['sender_name']) . '</p>';
            };

            echo '<p>' . htmlentities($row['transaction_date']) . '</p>';
            echo '<p>' . htmlentities($row['type']) . '</p>';
            echo '</div>';
            echo '<div id="amount-received-sent">';
            if ($row['sender_id'] == $_SESSION['user_id']) {
                echo '<p>-' . htmlentities($row['amount']) . '</p>';
            }
            elseif ($row['receiver_id'] == $_SESSION['user_id']) {
                echo '<p>+' . htmlentities($row['amount']) . '</p>';
            }
            echo '</div>';
            echo '</section>';
        }

        ?>
        
    </section>
</body>

</html>





<table border="1">
    <tr>
        <th>Type</th>
        <th>From</th>
        <th>To</th>
        <th>Amount</th>
        <th>Currency</th>
        <th>Date</th>
    </tr>
    <?php
    foreach ($rows as $row) {
        echo '<tr>';
        echo '<td>';
        echo htmlentities($row['type']);
        echo '</td>';
        echo '<td>';
        echo htmlentities($row['sender_name']);
        echo '</td>';
        echo '<td>';
        echo htmlentities($row['receiver_name']);
        echo '</td>';
        echo '<td>';
        echo htmlentities($row['amount']);
        echo '</td>';
        echo '<td>';
        echo htmlentities($row['currency']);
        echo '</td>';
        echo '<td>';
        echo htmlentities($row['transaction_date']);
        echo '</td></tr>';
    }

    ?>
</table>