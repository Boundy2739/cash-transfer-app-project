<?php
require_once 'pdo.php';
session_start();
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== TRUE) {
    header('Location: myaccount.php');
    exit;
}
$sql = "SELECT firstname,middlename,lastname,email,phone,address_street_name,address_house_number,city,postcode from users where id=:id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header('Location: myaccount.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        header('Location: myaccount.php');
        exit;
    }
    $fname = trim($_POST['firstname'] ?? '');
    $mname = trim($_POST['middlename'] ?? '');
    $lname = trim($_POST['lastname'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $number = filter_input(INPUT_POST, 'phonenumber', FILTER_VALIDATE_INT);
    $street = $_POST['address1'];
    $houseNumber = filter_input(INPUT_POST, 'address2', FILTER_VALIDATE_INT);
    $city = preg_replace('/[^a-zA-Z\s]/', '', $_POST['city'] ?? '');
    $city = preg_replace('/\s+/', ' ', trim($city));
    $postcode = preg_replace('/[^a-zA-Z0-9\s]/', '', $_POST['postcode']);
    $postcode = preg_replace('/\s+/', ' ', trim($postcode));


    $fields = [];/* Stores validated column assignments that will be dynamically added 
    to the UPDATE query (e.g., "firstname = :firstname"). */
    $params = [':id' => $_SESSION['user_id']];/*Initialise an array of parameters that will be used in the UPDATE query*/

    /*Validate each input field and dynamically build an UPDATE query.
   Only fields that are not empty and pass format validation are added 
   to the $fields array along with their corresponding parameters.
   The query is then constructed using prepared statements and executed 
   to update only the modified user details.*/
    if (!empty($_POST['firstname']) && preg_match('/^[a-zA-Z]+$/', $fname)) {
        $fields[] = "firstname = :firstname";
        $params[':firstname'] = $fname;
    }

    if (!empty($_POST['middlename']) && preg_match('/^[a-zA-Z]+$/', $mname)) {
        $fields[] = "middlename = :middlename";
        $params[':middlename'] = $mname;
    }

    if (!empty($_POST['lastname']) && preg_match('/^[a-zA-Z]+$/', $lname)) {
        $fields[] = "lastname = :lastname";
        $params[':lastname'] = $lname;
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


    if (!empty($city)) {
        $fields[] = "city = :city";
        $params[':city'] = $city;
    }

    if (!empty($postcode)) {
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
    <nav class="navigation-bar">
        <a href="myaccount.php">Dashboard</a>
        <a href="accountslist.php">Accounts</a>
        <a href="sendmoney.php">Send Money</a>
        <a href="view_transactions.php">Transactions</a>
        <a href="profile.php">Profile</a>
    </nav>
    <h1>Edit your profile</h1>
    <form action="" method="POST" onsubmit="return confirmChanges()">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <section class="profile-item">
            <label for="firstname">First Name</label>
            <?php echo '<p>' . $user['firstname'] . '</p>' ?>
            <input type="text" id="firstname" name="firstname" class="names-input">
            <label for="middlename">Middle name</label>
            <?php echo '<p>' . $user['middlename'] . '</p>' ?>
            <input type="text" id="middlename" name="middlename" class="names-input">
            <label for="surname">Surname</label>
            <?php echo '<p>' . $user['lastname'] . '</p>' ?>
            <input type="text" id="lastname" name=";astname" class="names-input">
            <button id="edit-names" type="button" onclick="enableEdit('names-input','submit-names','edit-names','cancel-btn-names')">Edit</button>
            <button id="cancel-btn-names" type="button" onclick="disableEdit('names-input active','submit-names','edit-names','cancel-btn-names')">Cancel</button>
            <input type="submit" value="apply changes" id="submit-names">
        </section>
        <section class="profile-item">
            <label for="phonenumber">Phone number</label>
            <?php echo '<p>' . $user['phone'] . '</p>' ?>
            <input type="tel" id="phonenumber" name="phonenumber" class="phone-input">
            <button id="edit-phone" type="button" onclick="enableEdit('phone-input','submit-phone','edit-phone','cancel-btn-phone')">Edit</button>
            <button id="cancel-btn-phone" type="button" onclick="disableEdit('phone-input active','submit-phone','edit-phone','cancel-btn-phone')">Cancel</button>
            <input type="submit" value="apply changes" id="submit-phone">
        </section>
        <section class="profile-item">
            <label for="email">Email</label>
            <?php echo '<p>' . $user['email'] . '</p>' ?>
            <input type="email" id="email" name="email" class="email-input">
            <button id="edit-email" type="button" onclick="enableEdit('email-input','submit-email','edit-email','cancel-btn-email')">Edit</button>
            <button id="cancel-btn-email" type="button" onclick="disableEdit('email-input active','submit-email','edit-email','cancel-btn-email')">Cancel</button>
            <input type="submit" value="apply changes" id="submit-email">
        </section>


        <section class="profile-item">
            <label for="address1">Address line 1</label>
            <?php echo '<p>' . $user['address_street_name'] . '</p>' ?>
            <input type="text" id="address1" name="address1" placeholder="Address line 1" class="address-input">
            <label for="address2">House number</label>
            <?php echo '<p>' . $user['address_house_number'] . '</p>' ?>
            <input type="number" id="address2" name="address2" placeholder="Address line 2" class="address-input">
            <label for="city">City</label>
            <?php echo '<p>' . $user['city'] . '</p>' ?>
            <input type="text" id="city" name="city" class="address-input">
            <label for="postcode">Postcode</label>
            <?php echo '<p>' . $user['postcode'] . '</p>' ?>
            <input type="text" id="postcode" name="postcode" class="address-input">
            <button id="edit-address" type="button" onclick="enableEdit('address-input','submit-address','edit-address','cancel-btn-address')">Edit</button>
            <button id="cancel-btn-address" type="button" onclick="disableEdit('address-input active','submit-address','edit-address','cancel-btn-address')">Cancel</button>
            <input type="submit" value="apply changes" id="submit-address">
        </section>
    </form>
    <script>
        /*Display input fields for editing user peronal details*/
        function enableEdit(field, submitBtn, editBtn, cancelBtn) {
            const elements = document.getElementsByClassName(field);

            for (let i = 0; i < elements.length; i++) {
                elements[i].classList.add('active');
            }
            document.getElementById(submitBtn).style.display = "inline-block";
            document.getElementById(cancelBtn).style.display = "inline-block";
            document.getElementById(editBtn).style.display = "none";
        }
        /*Hides input fields */
        function disableEdit(field, submitBtn, editBtn, cancelBtn) {
            const elements = document.getElementsByClassName(field);

            for (let i = 0; i < elements.length; i++) {
                elements[i].classList.remove('active');
            }
            document.getElementById(submitBtn).style.display = "none";
            document.getElementById(cancelBtn).style.display = "none";
            document.getElementById(editBtn).style.display = "inline-block";
        }

        function confirmChanges() {
            return confirm("Are you sure you want to apply these changes?");
        }
    </script>
</body>

</html>