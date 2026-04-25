<?php
$title = "Editing profile";
require_once "../includes/init.php";
if (!isset($_SESSION['authorised']) || $_SESSION['authorised'] !== TRUE) {
    header('Location: myaccount.php');
    exit;
}
$_SESSION['last_activity'] = time();
//selects the users details from the users table
$sql = "SELECT firstname,middlename,lastname,email,phone,address_street_name,address_house_number,city,postcode 
from users where id=:id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
//the data fetched will be displayed to the user, it shows the user's current personal details
if (!$user) {
    redirect('user/dashboard.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        redirect('index.php');
        
    }
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $number = filter_input(INPUT_POST, 'phonenumber', FILTER_VALIDATE_INT);
    $street = $_POST['address1'];
    $houseNumber = filter_input(INPUT_POST, 'address2', FILTER_VALIDATE_INT);
    $city = preg_replace('/[^a-zA-Z\s\'-]/', '', $_POST['city'] ?? '');
    $city = preg_replace('/\s+/', ' ', trim($city));
    $postcode = preg_replace('/[^a-zA-Z0-9\s]/', '', $_POST['postcode'] ?? '');
    $postcode = preg_replace('/\s+/', ' ', trim($postcode));


    $fields = [];/* Stores validated column assignments that will be dynamically added 
    to the UPDATE query (e.g., "firstname = :firstname"). */
    $params = [':id' => $_SESSION['user_id']];/*Initialise an array of parameters that will be used in the UPDATE query*/

    /*Validate each input field and dynamically build an UPDATE query.
   Only fields that are not empty and pass format validation are added 
   to the $fields array along with their corresponding parameters.
   The query is then constructed using prepared statements and executed 
   to update only the modified user details.*/
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

<h1>Edit your profile</h1>
<form action="" method="POST" onsubmit="return confirmChanges()">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    <section class="profile-item">
        <label for="firstname">First Name</label>
        <?php echo '<p>' . $user['firstname'] . '</p>' ?>
        <label for="middlename">Middle name</label>
        <?php echo '<p>' . $user['middlename'] . '</p>' ?>
        <label for="surname">Surname</label>
        <?php echo '<p>' . $user['lastname'] . '</p>' ?>

    </section>
    <section class="profile-item">
        <label for="phonenumber">Phone number</label>
        <?php echo '<p>' . $user['phone'] . '</p>' ?>
        <input type="tel" id="phonenumber" name="phonenumber" class="phone-input" value="<?php echo htmlentities(restoreFormData('phonenumber')) ?>">
        <button id="edit-phone" type="button" onclick="enableEdit('phone-input','submit-phone','edit-phone','cancel-btn-phone')">Edit</button>
        <button id="cancel-btn-phone" type="button" onclick="disableEdit('phone-input active','submit-phone','edit-phone','cancel-btn-phone')">Cancel</button>
        <input type="submit" value="apply changes" id="submit-phone">
    </section>
    <section class="profile-item">
        <label for="email">Email</label>
        <?php echo '<p>' . $user['email'] . '</p>' ?>
        <input type="email" id="email" name="email" class="email-input" value="<?php echo htmlentities(restoreFormData('email')) ?>">
        <button id="edit-email" type="button" onclick="enableEdit('email-input','submit-email','edit-email','cancel-btn-email')">Edit</button>
        <button id="cancel-btn-email" type="button" onclick="disableEdit('email-input active','submit-email','edit-email','cancel-btn-email')">Cancel</button>
        <input type="submit" value="apply changes" id="submit-email">
    </section>


    <section class="profile-item">
        <label for="address1">Address line 1</label>
        <?php echo '<p>' . $user['address_street_name'] . '</p>' ?>
        <input type="text" id="address1" name="address1" placeholder="Address line 1" class="address-input" value="<?php echo htmlentities(restoreFormData('address1')) ?>">
        <label for="address2">House number</label>
        <?php echo '<p>' . $user['address_house_number'] . '</p>' ?>
        <input type="number" id="address2" name="address2" placeholder="Address line 2" class="address-input" value="<?php echo htmlentities(restoreFormData('address2')) ?>">
        <label for="city">City</label>
        <?php echo '<p>' . $user['city'] . '</p>' ?>
        <input type="text" id="city" name="city" class="address-input" value="<?php echo htmlentities(restoreFormData('city')) ?>">
        <label for="postcode">Postcode</label>
        <?php echo '<p>' . $user['postcode'] . '</p>' ?>
        <input type="text" id="postcode" name="postcode" class="address-input" value="<?php echo htmlentities(restoreFormData('postcode')) ?>">
        <button id="edit-address" type="button" onclick="enableEdit('address-input','submit-address','edit-address','cancel-btn-address')">Edit</button>
        <button id="cancel-btn-address" type="button" onclick="disableEdit('address-input active','submit-address active','edit-address active','cancel-btn-address active')">Cancel</button>
        <input type="submit" value="apply changes" id="submit-address">
    </section>
</form>
<script src="<?php echo BASE_URL; ?>javaScript/app.js"></script>
</body>

</html>