<?php 
function userError($message) {
    $_SESSION['errorMessage'] = $message;
}

function redirect($location="../index.php") {
    header("Location: $location");
    exit;
}

function csrfCheck(){
       return (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']));
    
}