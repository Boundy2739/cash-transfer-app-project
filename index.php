<?php

session_start();
if(isset($_SESSION['autorised']) && $_SESSION['authorised'] === TRUE){
    header('Location: accountslist.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Title</title>
</head>
<body>
    <h1>Cash transfer app</h1>
    <div>
        <form action="login.php" method="post">
            <?php if(isset($_SESSION['errorMessage'])){
                echo "<p  class='wrong-login'>
                Wrong email or password!
              </p>";
              unset($_SESSION['errorMessage']);
            }
            ?>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="login">
            <div class="register-btn"><a href="register.php" >Sign up</a></div>
            
        </form>
        
    </div>
</body>
</html>