<?php

require_once 'pdo.php';
require_once 'guid_generator.php';
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit();
}
if (
    empty($_POST["firstname"]) ||
    empty($_POST["surname"])||
    empty($_POST["username"])||
    empty($_POST["email"]) ||
    empty($_POST["pwd"])||
    empty($_POST["dob"])||
    empty($_POST["phonenumber"])||
    empty($_POST["address1"])||
    empty($_POST["address2"])||
    empty($_POST["city"])||
    empty($_POST["postcode"])


){
    print_r($_POST);
    die("All fields are required.");
}
$uuid = guidv4(); 
$fname = $_POST['firstname'];
$lname = $_POST['surname'];
$phone = $_POST['phonenumber'];
$email = $_POST['email'];
$address = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$dob= $_POST['dob'];
$postcode = $_POST['postcode'];
$password = password_hash($_POST['pwd'],PASSWORD_DEFAULT);
$mname = $_POST['middlename'];
$username = $_POST['username'];
if(filter_var($email,FILTER_VALIDATE_EMAIL)){
    $sql = "INSERT INTO users (id,firstname,middlename,lastname,username,phone,email,password_hash,date_of_birth,address_street_name,address_house_number,city,postcode) 
                VALUES (:id,:firstname,:middlename,:lastname,:username,:phone,:email,:password_hash,:date_of_birth,:address_street_name,:address_house_number,:city,:postcode)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':id'=> $uuid,
        ':firstname'=>$fname,
        ':middlename'=>$mname,
        ':lastname'=>$lname,
        ':username'=>$username,
        ':phone'=>$phone,
        ':email'=>$email,
        ':date_of_birth'=>$dob,
        ':address_street_name'=>$address,
        ':address_house_number'=>$address2,
        ':city'=> $city,
        ':postcode'=>$postcode,
        ':password_hash'=>$password,


    ));

header('Location: acccreationsuccess.html');
        exit;

}

?>


