<?php
require_once "../includes/init.php";
if ($_SESSION['authorised'] !== TRUE) {
    header('Location: myaccount.php');
    exit;
}
$_SESSION['last_activity'] = time();
?>


<!DOCTYPE html>
<html lang="en">


<body>



    <section class="user-options">
        <ul>
            <li><a href="edit_profile.php">View profile</a></li>
            <li><a href="currentpassword.php">Change password</a></li>
            <li><a href="freezeaccount.php">Freeze account</a></li>
            <li><a href="logout.php">Logout</a></li>

        </ul>
    </section>
</body>

</html>