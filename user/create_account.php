<?php
require_once "../config/config.php";
require_once "../helpers/index.php";
require_once '../pdo/pdo.php';
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit;
}
saveFormData();
if (
    empty($_POST["firstname"]) ||
    empty($_POST["surname"]) ||
    empty($_POST["username"]) ||
    empty($_POST["email"]) ||
    empty($_POST["pwd"]) ||
    empty($_POST["confirmpwd"]) ||
    empty($_POST["dob"]) ||
    empty($_POST["phonenumber"]) ||
    empty($_POST["address1"]) ||
    empty($_POST["address2"]) ||
    empty($_POST["city"])



) {
    userError("Fill the required fields");
    redirect("user/register.php");
}

if($_POST['pwd'] !== $_POST['confirmpwd']){
    userError("Passwords don't match");
    redirect("register.php");
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
    userError('Invalid email'); /*This ensures that the password meets the requirements*/
    redirect("register.php");
}


$password = password_hash($_POST['pwd'], PASSWORD_DEFAULT);/*Creates an hash of the password and store it*/
$mname = $_POST['middlename'];
$username = $_POST['username'];
$isValidEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
$isUsernameValid = preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]{8,20}$/', $username);;
$isPhoneNumValid = preg_match('/^[0-9+\s()-]+$/', $phone);
$isHouseNumValid = preg_match('/^[0-9a-zA-Z\s\-\/,]+$/', $houseNumber); 
$isFNameValid = preg_match('/^[a-zA-Z\s\'-]+$/', $fname);
$isMNameValid = (empty($mname) || preg_match('/^[a-zA-Z\s\'-]+$/', $mname));
$isLNameValid = preg_match('/^[a-zA-Z\s\'-]+$/', $lname);
$isCityNameValid = preg_match('/^[a-zA-Z\s\'-]+$/', $city);
$isDateValid =  $date && $date->format('Y-m-d') === $dob;
$isStreetNameValid = preg_match('/^[a-zA-Z\s\'-]+$/', $street); 

if (!$isPhoneNumValid) {
    userError('Invalid phone number');
    redirect("register.php");
}

if (!$isValidEmail) {
    userError('Invalid email');
    redirect("register.php");
}
if (!$isUsernameValid) {
    userError('Invalid username');
    redirect("register.php");
}
if (!$isHouseNumValid) {
    userError('Invalid house number');
    redirect("register.php");
}
if (!$isFNameValid) {
    userError('Invalid First name');
    redirect("register.php");
}
if (!$isMNameValid) {
    userError('Invalid middle name');
    redirect("register.php");
}
if (!$isLNameValid) {
    userError('Invalid last name');
    redirect("register.php");
}
if (!$isCityNameValid) {
    userError('Invalid city name');
    redirect("register.php");
}
if (!$isDateValid) {
    userError('Invalid date');
    redirect("register.php");
}
if (!$isStreetNameValid) {
    userError('Invalid street name');
    redirect("register.php");
}




$sql = "INSERT INTO users (id,firstname,middlename,lastname,username,phone,email,password_hash,date_of_birth,address_street_name,address_house_number,city,postcode) 
                VALUES (:id,:firstname,:middlename,:lastname,:username,:phone,:email,:password_hash,:date_of_birth,:address_street_name,:address_house_number,:city,:postcode)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':id' => $uuid,
        ':firstname' => $fname,
        ':middlename' => $mname ?? '',
        ':lastname' => $lname,
        ':username' => $username,
        ':phone' => $phone,
        ':email' => $email,
        ':date_of_birth' => $dob,
        ':address_street_name' => $street,
        ':address_house_number' => $houseNumber,
        ':city' => $city,
        ':postcode' => $postcode ?? '',
        ':password_hash' => $password,


    ));
    deleteFormData();
    redirect('index.php');
    exit;

