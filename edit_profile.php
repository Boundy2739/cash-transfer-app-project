<?php 
require_once 'pdo.php';
session_start();
if(!isset($_SESSION['autorised']) || $_SESSION['authorised'] !== TRUE){
    header('Location: myaccount.php');
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
        <input type="text" id="firstname" name="firstname" required>
        <label for="middlename">Middle name</label>
        <input type="text" id="middlename" name="middlename" required>
        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname" required>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="phonenumber">Phone number</label>
        <input type="tel" id="phonenumber" name="phonenumber">
        <label for="email">Email</label>
        <input type="email" id="email" name="email">
        <label for="dob">Date of birth</label>
        <input type="date" id="dob" name="dob">
        <label for="address1">Address line 1</label>
        <input type="text" id="address1" name="address1" placeholder="Address line 1" required>
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