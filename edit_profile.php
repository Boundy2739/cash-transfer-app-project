<?php
require_once 'pdo.php';
session_start();
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== TRUE) {
    header('Location: myaccount.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['firstname'] ?? '');
    $mname = trim($_POST['middlename'] ?? '');
    $lname = trim($_POST['lastname'] ?? '');
    $email = filter_input(INPUT_POST,$_POST['email'], FILTER_VALIDATE_EMAIL);
    $number = filter_input(INPUT_POST,$_POST['phonenumber'], FILTER_VALIDATE_INT);
    $street = $_POST['address1'];
    $houseNumber = filter_input(INPUT_POST,$_POST['address2'], FILTER_VALIDATE_INT);
    $dob = $_POST['dob'];
    $date = DateTime::createFromFormat('Y-m-d', $dob);
    $city = preg_replace('/[^a-zA-Z\s]/', '', $_POST['city'] ?? '');
    $city = preg_replace('/\s+/', ' ', trim($city));
    $postcode = preg_replace('/[^a-zA-Z0-9\s]/', '',$_POST['postcode']);
    $postcode = preg_replace('/\s+/', ' ', trim($postcode));


    $fields = [];
    $params = [':id' => $_SESSION['user_id']];

    if (!empty($_POST['firstname']) && preg_match('/^[a-zA-Z]+$/', $fname)) {
        $fields[] = "firstname = :firstname";
        $params[':firstname'] = $fname;
    }

    if (!empty($_POST['middlename']) && preg_match('/^[a-zA-Z]+$/', $mname)) {
        $fields[] = "middlename = :middlename";
        $params[':middlename'] = $mname;
    }

    if (!empty($_POST['surname']) && preg_match('/^[a-zA-Z]+$/', $lname)) {
        $fields[] = "lastname = :surname";
        $params[':surname'] = $lname;
    }

    if (!empty($email)) {
        $fields[] = "email = :email";
        $params[':email'] = $email;
    }

    if (!empty($number)) {
        $fields[] = "phone = :phone";
        $params[':phone'] = $number;
    }

    if (!empty($street) && preg_match('/^[a-zA-Z0-9\s]+$/', $street)) {
        $fields[] = "address_street_name = :address1";
        $params[':address1'] = $street;
    }

    if (!empty($houseNumber)) {
        $fields[] = "address_house_number = :address2";
        $params[':address2'] = $houseNumber;
    }

    if (!empty($dob) && $date && $date->format('Y-m-d') === $dob) {
        $fields[] = "date_of_birth = :dob";
        $params[':dob'] = $dob;
    }

    if (!empty($_POST['city'])) {
        $fields[] = "city = :city";
        $params[':city'] = $city;
    }

    if (!empty($_POST['postcode'])) {
        $fields[] = "postcode = :postcode";
        $params[':postcode'] = $postcode;
    }
    


    if (!empty($fields)) {
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <h1>Edit your profile</h1>
    <form action="" method="POST">
        <label for="firstname">Name</label>
        <input type="text" id="firstname" name="firstname">
        <label for="middlename">Middle name</label>
        <input type="text" id="middlename" name="middlename">
        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname">
        <label for="username">Username</label>
        <input type="text" id="username" name="username">
        <label for="phonenumber">Phone number</label>
        <input type="tel" id="phonenumber" name="phonenumber">
        <label for="email">Email</label>
        <input type="email" id="email" name="email">
        <label for="dob">Date of birth</label>
        <input type="date" id="dob" name="dob">
        <label for="address1">Address line 1</label>
        <input type="text" id="address1" name="address1" placeholder="Address line 1">
        <label for="address2">House number</label>
        <input type="number" id="address2" name="address2" placeholder="Address line 2">
        <label for="city">City</label>
        <input type="text" id="city" name="city">
        <label for="postcode">Postcode</label>
        <input type="text" id="postcode" name="postcode">
        <input type="submit" value="apply changes">
    </form>
</body>

</html>