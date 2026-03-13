<?php 
session_start();
require_once 'pdo.php';
if($_SESSION['authorised']!==TRUE){
    header('Location: accountslist.php');
    exit;
}
if(!empty($_POST['old-pwd'])){
    $sql = "SELECT password_hash from users where id =:id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":id"=>$_SESSION['user_id']]);
$password = $stmt->fetch(PDO::FETCH_ASSOC);
if(password_verify($_POST['old-pwd'],$password['password_hash'])){
    header('Location: newpassword.php');
    exit;
}
else{
    $_SESSION['errorMessage'] = TRUE;
    print_r($_SESSION);
    
}

}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST" action="">
    <?php if(isset($_SESSION['errorMessage'])){
                echo "<p  class='wrong-login'>
                Wrong password!
              </p>";
              unset($_SESSION['errorMessage']);
            }
            ?>
        <label for="old-pwd">Password: </label>
        <input type="password" id="old-pwd" name="old-pwd" placeholder="Insert the current password">
        <input type="submit" value="confirm">
    </form>
</body>
</html>