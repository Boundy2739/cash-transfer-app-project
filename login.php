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

$sql = "SELECT * from users where email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([':email'=>$email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

/*verifies that the password given the matches the hash*/
if(password_verify($password, $row['password_hash'])){
    $_SESSION['authorised'] = TRUE; /*This will variable will be used to check if the user logged in before doing any action */
    $_SESSION['user_id'] = $row['id'];/*This will hold the user's id and it will be used for furthermore verification */
    header('Location: myaccount.php');
    exit;
}
else{
    $_SESSION['errorMessage'] = TRUE;
    header('Location: index.php');
    exit;
}

?>