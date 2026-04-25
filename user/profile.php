<?php
$title = "My profile";
require_once "../includes/init.php";
if ($_SESSION['authorised'] !== TRUE) {
    redirect('index.php');
    exit;
}
$_SESSION['last_activity'] = time();
?>






    <section class="user-options">
        <ul>
            <li><a href="<?php echo BASE_URL; ?>user/edit_profile.php" class="options">View profile</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/currentpassword.php" class="options">Change password</a></li>
            <li><a href="" class="options">Freeze account</a></li>
            <li><a href="<?php echo BASE_URL; ?>user/logout.php" class="options">Logout</a></li>

        </ul>
    </section>
</body>

</html>