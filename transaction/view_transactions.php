<?php
require_once '../pdo/pdo.php';
require_once "../config/config.php";

if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== TRUE) {
    header('Location: index.php');
    exit;
}
$_SESSION['last_activity'] = time();
$sql = "SELECT t.sender_id, t.receiver_id, t.type, t.amount, t.currency, t.transaction_date,s.firstname as sender_name,
        r.firstname as receiver_name 
        from transactions t
        INNER JOIN users s ON t.sender_id = s.id
        INNER JOIN users r ON t.receiver_id = r.id
        WHERE t.sender_id = :id or t.receiver_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$rows = $stmt->fetchall(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Document</title>
</head>

<body>
    <?php echo navBar(); ?>
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
                echo '<p class="sent">-£' . htmlentities($row['amount']) . '</p>';
            } elseif ($row['receiver_id'] == $_SESSION['user_id']) {
                echo '<p class="received">+£' . htmlentities($row['amount']) . '</p>';
            }
            echo '</div>';
            echo '</section>';
        }

        ?>

    </section>
</body>

</html>