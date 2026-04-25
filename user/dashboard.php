<?php
$title="Dashboard";
require_once "../includes/init.php";
if ($_SESSION['authorised'] !== TRUE) {
    redirect('index.php');
}
$_SESSION['last_activity'] = time();

?>
<?php
    
    /*This will echo an heading that greets the user*/
    $sql = "SELECT firstname, lastname from users where id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo '<h1>Greetings ' . $user['firstname'] .' '. $user['lastname'] . ' ! </h1>';
    ?>
    <!-- This is placeholder-->
    <section class="summary-section">
        <h2>Account summary</h2>
        <div>
            <p>Number of wallets: 1 </p>
            <p>Total balance: £999999</p>
            <p>Status: active </p>
        </div>
    </section>
    <section class="activity-section">
        <h2>Recent activity</h2>
        <table class="activity-table">
            <tr class="headers-row">
                <th>Date</th>
                <th>Actvity</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
            <tr class="activity-row">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </section>

</body>

</html>