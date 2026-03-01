<?php

require_once "pdo.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}
if (
    empty($_POST["email"]) ||
    empty($_POST["password"])


){
    die("All fields are required.");
}
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * from accounts where email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([':email'=>$email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(password_verify($password, $row['password'])){
    $_SESSION['authorized'] = TRUE;
    $_SESSION['account_id'] = $row['id'];
    header('Location: myaccount.php');
    exit;
}
else{
    header('Location: index.php?error=1');
    exit;
}

?>