<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="POST">
        <label for="chosen-account">Choose an account</label>
        <select name="chosen-account" id="chosen-account">
            <option value="">Choose an account</option>
            
        </select>
        <label for="amount">Amount</label>
        <input type="number" id="amount" name="amount" min="1" step="0.01" placeholder="Enter amount" required>
        <input type="submit" value="transfer money">
    </form>

</body>

</html>