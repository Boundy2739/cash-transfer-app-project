<?php
session_start();
require_once 'pdo.php';
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location: accountslist.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if( !isset($_POST['csrf_token'], $_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){

        $_SESSION['errorMessage'] = 'Invalid request.';
        header('Location: newpassword.php');
        exit;
    }
    /*Checks if the user is trying to submit an empty form */
    if (!empty($_POST['new-pwd']) && !empty($_POST['confirm-pwd'])) {
        if(!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/',$_POST['new-pwd'])){
            $_SESSION['errorMessage'] = "Password doesn't meet requirements";
            header('Location: newpassword.php');
            exit;
        }
        /*Checks if the passwords given mathch */ elseif ($_POST['new-pwd'] != $_POST['confirm-pwd']) {
            $_SESSION['errorMessage'] = "Passwords don't match!";
            header('Location: newpassword.php');
            exit;
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
            exit;
        }
    }
    else{
        $_SESSION['errorMessage'] = "All fields are required!";
        header('Location: newpassword.php');
        exit;
    }
    
}

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
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label for="new-pwd">New password</label>
        <input type="password" id="new-pwd" name="new-pwd" placeholder="Insert the new passowrd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
        <label for="confirm-pwd">Confirm password</label>
        <input type="password" id="confirm-pwd" name="confirm-pwd" placeholder="Reinsert the new passowrd" required>
        <input type="submit" value="confirm">
    </form>
</body>

</html>