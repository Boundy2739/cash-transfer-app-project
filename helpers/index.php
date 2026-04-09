<?php 
function userError($message) {
    $_SESSION['errorMessage'] = $message;
}

function redirect($location="../index.php") {
    header("Location: $location");
    exit;
}