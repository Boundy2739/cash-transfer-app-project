<?php

require_once 'pdo.php';
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit();
}
if (
    empty($_POST["firstname"]) ||
    empty($_POST["surname"])||
    empty($_POST["email"]) ||
    empty($_POST["password"])||
    empty($_POST["dob"])||
    empty($_POST["phonenumber"])||
    empty($_POST["address1"])||
    empty($_POST["city"])||
    empty($_POST["postcode"])


){
    die("All fields are required.");
} 
$fname = $_POST['firstname'];
$lname = $_POST['surname'];
$phone = $_POST['phonenumber'];
$email = $_POST['email'];
$address = $_POST['address1'];
$city = $_POST['city'];
$dob= $_POST['dob'];
$postcode = $_POST['postcode'];
$password = password_hash($_POST['password'],PASSWORD_DEFAULT);
if(filter_var($email,FILTER_VALIDATE_EMAIL)){
    $sql = "INSERT INTO accounts (firstname, lastname,phone,email,date_of_birth,address,city,postcode,password) 
                VALUES (:firstname,:lastname,:phone,:email,:date_of_birth,:address,:city,:postcode,:password)";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
    ':firstname'=>$fname,
    ':lastname'=>$lname,
    ':phone'=>$phone,
    ':email'=>$email,
    ':date_of_birth'=>$dob,
    ':address'=>$address,
    ':city'=> $city,
    ':postcode'=>$postcode,
    ':password'=>$password,


));

header('Location: acccreationsuccess.html');
        exit;

}

?>

