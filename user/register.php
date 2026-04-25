<?php 
session_start();
require_once '../helpers/index.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Sign up page</title>
</head>

<body>
    <h1>Create account</h1>
    <form action="create_account.php" method="POST">
        <?php 
        /*Shows error message to the user*/
        if (isset($_SESSION['errorMessage'])) {
            echo "<p  class='wrong-login'>
                ".$_SESSION['errorMessage']."
              </p>";
            unset($_SESSION['errorMessage']);
        }
        ?>
        <label for="firstname">Name</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo htmlentities(restoreFormData('firstname' ?? ''))?>">
        <label for="middlename">Middle name <span class="optional">(Optional)</span></label>
        <input type="text" id="middlename" name="middlename" value="<?php echo htmlentities(restoreFormData('middlename' ?? ''))?>">
        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname" value="<?php echo htmlentities(restoreFormData('surname' ?? ''))?>">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo htmlentities(restoreFormData('username' ?? ''))?>">
        <label for="phonenumber">Phone number</label>
        <input type="tel" id="phonenumber" name="phonenumber" value="<?php echo htmlentities(restoreFormData('phonenumber' ?? ''))?>">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlentities(restoreFormData('email' ?? ''))?>">
        <label for="dob">Date of birth</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlentities(restoreFormData('dob' ?? ''))?>">
        <label for="address1">Address street name</label>
        <input type="text" id="address1" name="address1" placeholder="Address line 1" value="<?php echo htmlentities(restoreFormData('address1' ?? ''))?>">
        <label for="address2">House number</label>
        <input type="number" id="address2" name="address2" placeholder="Address line 2" value="<?php echo htmlentities(restoreFormData('address2' ?? ''))?>">
        <label for="city">City</label>
        <input type="text" id="city" name="city" value="<?php echo htmlentities(restoreFormData('city' ?? ''))?>">
        <label for="postcode">Postcode <span class="optional">(Optional)</span></label>
        <input type="text" id="postcode" name="postcode" value="<?php echo htmlentities(restoreFormData('postcode' ?? ''))?>">
        <label for="pwd">Password</label>
        <input type="password" id="pwd" name="pwd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
        <label for="confirmpwd">Confirm password</label>
        <input type="password" id="confirmpwd" name="confirmpwd">
        <input type="submit" value="create account">
    </form>
</body>

</html>