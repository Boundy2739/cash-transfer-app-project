<?php
require_once "config/config.php";
require_once 'pdo.php';
require_once 'guid_generator.php';
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit();
}
if (
    empty($_POST["firstname"]) ||
    empty($_POST["surname"]) ||
    empty($_POST["username"]) ||
    empty($_POST["email"]) ||
    empty($_POST["pwd"]) ||
    empty($_POST["dob"]) ||
    empty($_POST["phonenumber"]) ||
    empty($_POST["address1"]) ||
    empty($_POST["address2"]) ||
    empty($_POST["city"]) ||
    empty($_POST["postcode"])


) {
    print_r($_POST);
    $_SESSION['errorMessage'] = 'All fields are required';
    header("Location: register.php");
    exit;
}
$uuid = guidv4();
$fname = $_POST['firstname'];
$lname = $_POST['surname'];
$phone = $_POST['phonenumber'];
$email = $_POST['email'];
$street = $_POST['address1'];
$houseNumber = $_POST['address2'];
$city = $_POST['city'];
$dob = $_POST['dob'];
$date = DateTime::createFromFormat('Y-m-d', $dob);
$postcode = preg_replace('/[^a-zA-Z0-9\s]/', '', $_POST['postcode']);
$postcode = preg_replace('/\s+/', ' ', trim($postcode));
if (!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/', $_POST['pwd'])) {
    $_SESSION['errorMessage'] = 'Invalid password'; /*This ensures that the password meets the requirements*/
    header('Location: register.php');
    exit;
}

$password = password_hash($_POST['pwd'], PASSWORD_DEFAULT);/*Creates an hash of the password and store it*/
$mname = $_POST['middlename'];
$username = $_POST['username'];
if (
    filter_var($email, FILTER_VALIDATE_EMAIL) && /*Checks if the user submitted an email*/
    preg_match('/^[0-9a-zA-Z\s-]+$/', $houseNumber) &&
    preg_match('/^[0-9+\s()-]+$/', $phone) && /*Check if phonenumber contains letters */
    preg_match('/^[a-zA-Z]+$/', $fname) &&
    empty($mname) || preg_match('/^[a-zA-Z]+$/', $mname) &&
    preg_match('/^[a-zA-Z]+$/', $lname) && /*Checks that person's name and surname do not contain numbers*/
    preg_match('/^[a-zA-Z\s]+$/', $city) &&
    $date && $date->format('Y-m-d') === $dob && /*Checks that the data submitted is in the correct format */
    preg_match('/^[a-zA-Z\s]+$/', $street) &&
    preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]{8,20}$/', $username)/*Checks if the username submitted meets the lenght requiremnts */




) {
    $sql = "INSERT INTO users (id,firstname,middlename,lastname,username,phone,email,password_hash,date_of_birth,address_street_name,address_house_number,city,postcode) 
                VALUES (:id,:firstname,:middlename,:lastname,:username,:phone,:email,:password_hash,:date_of_birth,:address_street_name,:address_house_number,:city,:postcode)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':id' => $uuid,
        ':firstname' => $fname,
        ':middlename' => $mname,
        ':lastname' => $lname,
        ':username' => $username,
        ':phone' => $phone,
        ':email' => $email,
        ':date_of_birth' => $dob,
        ':address_street_name' => $street,
        ':address_house_number' => $houseNumber,
        ':city' => $city,
        ':postcode' => $postcode,
        ':password_hash' => $password,


    ));
    header('Location: acccreationsuccess.html');
    exit;
} else {
    $_SESSION['errorMessage'] = 'Invalid format';
    header('Location: register.php');
    exit;
}
