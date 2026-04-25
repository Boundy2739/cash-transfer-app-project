<?php
require_once "../includes/init.php";
require_once "../rate-limiter/ratelimiter.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect("index.php");
}

$is_locked = is_locked($_SERVER['REMOTE_ADDR'], $pdo);
if ($is_locked === true) {
    userError("Too many attempts retry later");
    redirect("index.php");
}
if (
    empty($_POST["email"]) ||
    empty($_POST["password"])


) {
    userError("All fields are required");
    redirect("index.php");
}

$email = trim($_POST['email']);
$password = $_POST['password'];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    rate_limiter($_SERVER['REMOTE_ADDR'], 5, $pdo);
    userError("Wrong email or password");
    redirect("index.php");
    
}

$sql = "SELECT id,email,password_hash from users where email = :email";

// TODO IMPORTANT VALIDATE EMAIL FIRST
$stmt = $pdo->prepare($sql);
$stmt->execute([':email' => $email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

/*verifies that the password given the matches the hash*/
if ($row && password_verify($password, $row['password_hash'])) {
    $_SESSION['authorised'] = TRUE; /*This will variable will be used to check if the user logged in before doing any action */
    $_SESSION['user_id'] = $row['id'];/*This will hold the user's id and it will be used for furthermore verification */
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    reset_attempts($_SERVER['REMOTE_ADDR'], $pdo);
    redirect("user/dashboard.php");
} else {
    rate_limiter($_SERVER['REMOTE_ADDR'], 5, $pdo);
    userError("Wrong email or password");
    redirect("index.php");
}
