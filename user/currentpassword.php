<?php
require_once "config/config.php";
require_once 'pdo.php';
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    header('Location: index.php');
    exit;
}
$_SESSION['last_activity'] = time();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if( !isset($_POST['csrf_token'], $_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
        $_SESSION['errorMessage'] = 'Invalid request.';
        header('Location: currentpassword.php');
        exit;
    }
    if (!empty($_POST['old-pwd'])) {
        /*Selects the row with the password hash of the logged user */
        $sql = "SELECT password_hash from users where id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":id" => $_SESSION['user_id']]);
        $password = $stmt->fetch(PDO::FETCH_ASSOC);
    
        /*Verifies that the password submitted matches the current password before changing */
        if (password_verify($_POST['old-pwd'], $password['password_hash'])) {
            header('Location: newpassword.php');
            exit;
        } else {
            $_SESSION['errorMessage'] = 'Wrong password';
        }
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
        <?php if (isset($_SESSION['errorMessage'])) {
            echo "<p  class='wrong-login'>
                " . $_SESSION['errorMessage'] . "
              </p>";
            unset($_SESSION['errorMessage']);
        }
        ?>
        <input type="hidden" name="csrf_token" <?php echo 'value=' . htmlspecialchars($_SESSION['csrf_token']) . '' ?>>
        <label for="old-pwd">Password: </label>
        <input type="password" id="old-pwd" name="old-pwd" placeholder="Insert the current password">
        <input type="submit" value="confirm">
    </form>
</body>

</html>