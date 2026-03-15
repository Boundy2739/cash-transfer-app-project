<?php
require_once 'pdo.php';
session_start();
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== TRUE) {
    header('Location: myaccount.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [];
    $params = [':id' => $_SESSION['user_id']];

    if (!empty($_POST['firstname'])) {
        $fields[] = "firstname = :firstname";
        $params[':firstname'] = $_POST['firstname'];
    }

    if (!empty($_POST['middlename'])) {
        $fields[] = "middlename = :middlename";
        $params[':middlename'] = $_POST['middlename'];
    }

    if (!empty($_POST['surname'])) {
        $fields[] = "lastname = :surname";
        $params[':surname'] = $_POST['surname'];
    }

    if (!empty($_POST['email'])) {
        $fields[] = "email = :email";
        $params[':email'] = $_POST['email'];
    }

    if (!empty($_POST['number'])) {
        $fields[] = "phone = :phone";
        $params[':phone'] = $_POST['number'];
    }

    if (!empty($_POST['address1'])) {
        $fields[] = "address_street_name = :address1";
        $params[':address1'] = $_POST['address1'];
    }

    if (!empty($_POST['address2'])) {
        $fields[] = "address_house_number = :address2";
        $params[':address2'] = $_POST['address2'];
    }

    if (!empty($_POST['dob'])) {
        $fields[] = "date_of_birth = :dob";
        $params[':dob'] = $_POST['dob'];
    }

    if (!empty($_POST['city'])) {
        $fields[] = "city = :city";
        $params[':city'] = $_POST['city'];
    }

    if (!empty($_POST['postcode'])) {
        $fields[] = "postcode = :postcode";
        $params[':postcode'] = $_POST['postcode'];
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