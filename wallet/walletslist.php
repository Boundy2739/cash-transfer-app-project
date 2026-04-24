<?php
require_once "../includes/init.php";
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location:index.php');
    exit;
}
$_SESSION['last_activity'] = time();
$sql = "SELECT owner_id,account_name,balance,account_id from accounts where owner_id = :owner_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':owner_id' => $_SESSION['user_id']]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
unset($_SESSION['current_account']);
?>

<!DOCTYPE html>
<html lang="en">


<body>
    <section class="wallets-list">
        <?php
        foreach ($rows as $row) {
            echo '<section class="user-accounts">';
            echo '<p>' . $row['account_name'] . '</p>';
            echo '<p>balance: £' . $row['balance'] . '</p>';
            echo '<div class="open-acc-btn">';
            echo '<a href="walletoptions.php?account=' . $row['account_id'] . '" class="buttons">view</a>';
            echo '</div>';
            echo '</section>';
        }

        ?>

    </section>
    <div id="new-wallet-btn"><button id="open-modal" class="buttons" onclick="openModal()">Add new wallet</button></div>
    <div class="modal-container" id="modal_container">
        
            <form action="addnewwallets.php" method="post" class="wallet-name-form">
                <input type="hidden" name="csrf_token" <?php echo 'value=' . htmlspecialchars($_SESSION['csrf_token']) . '' ?>>
                <label>Wallet name:</label>
                <input type="text" name="wallet-name" placeholder="Insert wallet name" required>
                <button type="submit" class="buttons">Add wallet</button>
                <button id="close-modal" class="cancel-buttons" onclick="closeModal()">Cancel</button>
            </form>
        
    </div>
    <script src="../javaScript/app.js"></script>
</body>

</html>