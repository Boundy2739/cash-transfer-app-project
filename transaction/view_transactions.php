<?php
$title = "Transactions history";
require_once "../includes/init.php";

if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== TRUE) {
    userError("You need to login first");
    redirect('index.php');
    exit;
}
$_SESSION['last_activity'] = time();
//Select and fetch all the wallets owned by the user
$stmt = $pdo->prepare("SELECT account_id from accounts WHERE owner_id =:id ");
$stmt->execute([":id" => $_SESSION['user_id']]);
$wallets = $stmt->fetchall(PDO::FETCH_ASSOC);
//Returns the ids of all the wallets found
$walletIds = array_column($wallets, 'account_id');
//creates an array of placeholders based on the numbers of ids fetched
$placeholders = implode(',', array_fill(0, count($walletIds), '?'));


$stmt = $pdo->prepare("SELECT 
t.*,
u1.firstname AS sender_firstname,
u1.lastname AS sender_lastname,
u2.firstname AS receiver_firstname,
u2.lastname AS receiver_lastname
FROM transactions t

JOIN accounts w1 ON t.sender_wallet_id = w1.account_id
JOIN users u1 ON w1.owner_id = u1.id

JOIN accounts w2 ON t.receiver_wallet_id = w2.account_id
JOIN users u2 ON w2.owner_id = u2.id

WHERE t.sender_wallet_id IN ($placeholders)
OR t.receiver_wallet_id IN ($placeholders)

ORDER BY t.transaction_date DESC;");
$stmt->execute(array_merge($walletIds, $walletIds));

$rows = $stmt->fetchall(PDO::FETCH_ASSOC);
?>

<h1>Viewing transactions</h1>
<section class="transactions-container">
    <?php
    //create a box that displays info about each transaction made by the user
    foreach ($rows as $row) {
        echo '<section class="transaction-record" onclick=\'showTransactionDetails(' . json_encode($row) . ')\'><div>';
        // Displays receiver's name, if the user was the sender
        if (in_Array($row['sender_wallet_id'], $walletIds)) {
            echo '<p>' . htmlentities($row['receiver_firstname']) . '</p>';
        } 
        // Displays sender's name, if the user was the receiver
        elseif (in_Array($row['receiver_wallet_id'], $walletIds)) {
            echo '<p>' . htmlentities($row['sender_firstname']) . '</p>';
        };

        echo '<p>' . htmlentities($row['transaction_date']) . '</p>';
        echo '<p>' . htmlentities($row['type']) . '</p>';
        echo '</div>';
        echo '<div id="amount-received-sent">';
        if (in_Array($row['sender_wallet_id'], $walletIds) && $row['type'] !== 'Deposit') {
            echo '<p class="sent">-£' . htmlentities($row['amount']) . '</p>';
        } elseif (in_Array($row['receiver_wallet_id'], $walletIds)) {
            echo '<p class="received">+£' . htmlentities($row['amount']) . '</p>';
        }
        echo '</div>';
        echo '</section>';
    }

    ?>

</section>
<!--Popup that shows a little bit more details about the transaction -->
<div class="modal-container" id="modal_container">
    <div id="transaction-details">
        <p>Status: <span id="transaction-status"></span></p>
        <p>Transaction type: <span id="transaction-type"></span></p>
        <p>Amount: £<span id="transaction-amount"></span></p>
        <p>From: <span id="transaction-from"></span></p>
        <p>To: <span id="transaction-to"></span></p>
        <p>Fail reason: <span id="fail-reason"></span></p>
        <p>Date: <span id="transaction-date"></span></p>

        <button onclick="closeModal()" class="buttons">Close</button>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>javaScript/app.js"></script>
</body>

</html>