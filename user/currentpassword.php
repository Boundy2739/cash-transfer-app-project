<?php
require_once "../includes/init.php";
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== true) {
    userError("You need to login first");
    redirect('index.php');
}
$_SESSION['last_activity'] = time();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!csrfCheck() ){
        userError('Invalid request.');
        redirect('user/currentpassword.php');
    }
    if (!empty($_POST['old-pwd'])) {
        /*Selects the row with the password hash of the logged user */
        $sql = "SELECT password_hash from users where id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":id" => $_SESSION['user_id']]);
        $password = $stmt->fetch(PDO::FETCH_ASSOC);
    
        /*Verifies that the password submitted matches the current password before changing */
        if (password_verify($_POST['old-pwd'], $password['password_hash'])) {
            redirect(' user/newpassword.php');
            exit;
        }  {
           userError("Wrong password");
        }
    }

}



?>
    <h1>Change password</h1>
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