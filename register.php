

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Title</title>
</head>
<body>
    <h1>Create account</h1>
    <form action="create_account.php" method="POST">
        <label for="firstname">Name</label>
        <input type="text" id="firstname" name="firstname" required>
        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname" required>
        <label for="phonenumber">Phone number</label>
        <input type="tel" id="phonenumber" name="phonenumber">
        <label for="email">Email</label>
        <input type="email" id="email" name="email">
        <label for="dob">Date of birth</label>
        <input type="date" id="dob" name="dob">
        <label for="address1">Address line 1</label>
        <input type="text" id="address1" name="address1" placeholder="Address line 1" required>
        <label for="address2">Address line 2</label>
        <input type="text" id="address2" name="address2" placeholder="Address line 2">
        <label for="address3">Address line 3</label>
        <input type="text" id="address3" name="address3" placeholder="Address line 3">
        <label for="city">City</label>
        <input type="text" id="city" name="city">
        <label for="postcode">Postcode</label>
        <input type="text" id="postcode" name="postcode">
        <select id="bank">
            <option></option>
            <option>1st bank</option>
            <option>2nd bank</option>
            <option>3rd bank</option>
        </select>
        <label for="pwd">Password</label>
        <input type="password" id="pwd" name="pwd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
        <label for="confirmpwd">Confirm password</label>
        <input type="password" id="confirmpwd" name="confirmpwd" required>
        <input type="submit" value="create account">
    </form>
</body>
</html>