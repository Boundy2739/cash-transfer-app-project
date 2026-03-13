<?php 
session_start();
require_once 'pdo.php';
if($_SESSION['authorised']!==TRUE){
    header('Location: accountslist.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(empty($_POST['new-pwd']) || empty($_POST['confirm-pwd'])){
        $_SESSION['errorMessage'] = "All field are required!";
    }
    elseif($_POST['new-pwd'] != $_POST['confirm-pwd']){
        $_SESSION['errorMessage'] = "Passwords dont match!";
    
    }
    else{
        $newpwd = password_hash($_POST['new-pwd'],PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash=:password_hash where id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":id"=>$_SESSION['user_id'],
            ":password_hash"=>$newpwd
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
    <form action="" method="POST">
    <?php if(isset($_SESSION['errorMessage'])){
                echo "<p  class='wrong-login'>
                ".$_SESSION['errorMessage']."
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