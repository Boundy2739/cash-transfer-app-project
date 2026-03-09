<?php
require_once 'pdo.php';
session_start();

if($_SESSION['authorised'] !== TRUE){
    header('Location: index.php');
    exit;

}
$sql = "SELECT t.sender_id, t.receiver_id, t.type, t.amount, t.currency, t.transaction_date,u.firstname from transactions t
inner join users u
where t.sender_id =:sender_id and u.id = :sender_id or t.receiver_id=:receiver_id and u.id = :receiver_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':sender_id'=> null,
    ':receiver_id'=> $_SESSION['user_id'],
));
$rows = $stmt->fetchall(PDO::FETCH_ASSOC);
print_r($rows);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Viewing transactions</h1>
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
        foreach($rows as $row){
            echo'<tr>';
            echo '<td>';
            echo htmlentities($row['type']);
            echo'</td>';
            echo '<td>';
            echo htmlentities($row['firstname']);
            echo'</td>';
            echo '<td>';
            echo htmlentities($row['firstname']);
            echo'</td>';
            echo '<td>';
            echo htmlentities($row['amount']);
            echo'</td>';
            echo '<td>';
            echo htmlentities($row['currency']);
            echo'</td>';
            echo '<td>';
            echo htmlentities($row['transaction_date']);
            echo'</td></tr>';

        }
        
        ?>
    </table>
</body>
</html>