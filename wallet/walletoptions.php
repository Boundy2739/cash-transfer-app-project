<?php
$title = "Wallets";
require_once "../includes/init.php";
userAuth();
$_SESSION['last_activity'] = time();
/*Selects the wallet where both the wallet id and owner id match respectively the ID in the URL and the id of the logged user
this is to prevent the user from accessing someone elses wallet by changing the URL*/
$sql = "SELECT account_id from accounts WHERE account_id=:account_id AND owner_id=:owner_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':owner_id' => $_SESSION['user_id'],
    ':account_id' => $_GET['account'] 
));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$result) {
    header('Location: myaccount.php');
    exit;
}
$_SESSION['current_account'] = $_GET['account']; /*Stores the wallet ID from the URL in the session which will be used for other transactions later */

/*Selects the infos of the wallet that will be displayed on the page */
$sql = "SELECT account_name,balance,is_default from accounts where account_id =:id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_GET['account']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<section class="account-header">
    <?php
    /*Displays the name of the wallet and the current balance on screen */
    echo '<h1 class="account-name">' . $result['account_name'] . '</h1>';
    echo '<div class="account-info">';
    echo '<span class="balance-label">Current Balance:</span>';
    echo '<span class="balance-amount">' . " £" . $result['balance'] . '</span></div>';

    ?>
</section>

<div class="user-options">
    <ul>
        <li><a href="<?php echo BASE_URL; ?>transaction/add_funds.php" class="options">Add funds</a></li>
        <li><a href="<?php echo BASE_URL; ?>transaction/transfer.php" class="options">Transfer to another wallet</a></li>
        <?php
        /*Will display the option to set the wallet as default if it is not set yet */
        if ($result['is_default'] === 0) {
            echo '<li><a href="setdefaultaccount.php?account=' . $_SESSION['current_account'] . '" class="options">Set account as default</a></li>';
        };

        ?>
        <button id="open-modal" class="options" onclick="openModal()">Change wallet's name</button>
    </ul>
</div>
<div class="modal-container" id="modal_container">

    <form action="renamewallet.php" method="post" class="wallet-name-form">
        <input type="hidden" name="csrf_token" <?php echo 'value=' . htmlspecialchars($_SESSION['csrf_token']) . '' ?>>
        <label>Wallet name:</label>
        <input type="text" name="new-wallet-name" placeholder="Insert wallet name" required>
        <button type="submit" class="buttons">Add wallet</button>
        <button id="close-modal" class="cancel-buttons" onclick="closeModal()">Cancel</button>
    </form>

</div>
<script src="../javaScript/app.js"></script>
</body>

</html>