<?php
require_once "../includes/init.php";
userAuth();
$_SESSION['last_activity'] = time();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {

        $_SESSION['errorMessage'] = 'Invalid request.';
        header('Location: newpassword.php');
        exit;
    }
    /*Checks if the user is trying to submit an empty form */
    if (!empty($_POST['new-pwd']) && !empty($_POST['confirm-pwd'])) {
        if (!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/', $_POST['new-pwd'])) {
            userError("Password doesnt meet requirements");
            redirect('user/newpassword.php');
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
    } else {
        $_SESSION['errorMessage'] = "All fields are required!";
        header('Location: newpassword.php');
        exit;
    }
}

?>
    <h1>Change password</h1>
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
        <label for="new-pwd">New password: <span class="requirement">(Must contanin 8-20 characters, at least 1 uppercase and lowercase, 1 number and 1 special charcter)</span></label>
        <input type="password" id="new-pwd" name="new-pwd" placeholder="Insert the new passowrd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
        <label for="confirm-pwd">Confirm password</label>
        <input type="password" id="confirm-pwd" name="confirm-pwd" placeholder="Reinsert the new passowrd" required>
        <input type="submit" value="confirm">
    </form>
</body>

</html>