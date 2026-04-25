<?php 
require_once "../config/config.php";

function userError($message) {
    $_SESSION['errorMessage'] = $message;
}

function redirect($location="../index.php") {
    header("Location: ".BASE_URL . $location);
    exit;
}

// Cross-site-request-forgery prevention
function csrfCheck(){
    if (!isset($_POST['csrf_token'], $_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    
}

//Stores users submitted data via form,
function saveFormData()
{
    $_SESSION['form_data'] = $_POST;
}
//Delete the user data stored, once the form has been successfully submitted
function deleteFormData()
{
    unset($_SESSION['form_data']);
}
function restoreFormData($key)
{
    return $_SESSION['form_data'][$key] ?? '';
}


function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}