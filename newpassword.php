<?php
session_start();
require_once 'pdo.php';
if ($_SESSION['authorised'] !== TRUE) {
    header('Location: accountslist.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    /*Checks if the user is trying to submit an empty form */
    if (empty($_POST['new-pwd']) || empty($_POST['confirm-pwd'])) {
        $_SESSION['errorMessage'] = "All field are required!";
    }
    /*Checks if the passwords given mathch */ elseif ($_POST['new-pwd'] != $_POST['confirm-pwd']) {
        $_SESSION['errorMessage'] = "Passwords dont match!";
    }
    /*If the password is correct the update will occur*/ else {
        $newpwd = password_hash($_POST['new-pwd'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash=:password_hash where id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":id" => $_SESSION['user_id'],
            ":password_hash" => $newpwd
        ));
        header('Location: passwordchangesuccess.php');
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
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
    <form action="" method="POST">
        <?php
        /*Shows error message to the user*/
        if (isset($_SESSION['errorMessage'])) {
            echo "<p  class='wrong-login'>
                " . $_SESSION['errorMessage'] . "
              </p>";
            unset($_SESSION['errorMessage']);
        }
        ?>
        <label for="new-pwd">New password</label>
        <input type="password" id="new-pwd" name="new-pwd" placeholder="Insert the new passowrd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
        <label for="confrim-pwd">Confirm password</label>
        <input type="password" id="confirm-pwd" name="confirm-pwd" placeholder="Reinsert the new passowrd">
        <input type="submit" value="confirm">
    </form>
</body>

</html>