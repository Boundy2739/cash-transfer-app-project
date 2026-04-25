<?php
ini_set("session.use_only_cookies", true);


session_set_cookie_params([
    'lifetime' => 1800,
    'domain' => 'localhost',
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
$isLocal = ($_SERVER['HTTP_HOST'] === 'localhost');

// Base URL
if ($isLocal) {
    define('BASE_URL', 'http://localhost/cash-transfer-app/');
} else {
    define('BASE_URL', 'https://money-transfer-app.free.nf/');
}
session_start();

if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} else {
    $timer = 60 * 30;
    if (time() - $_SESSION['last_regeneration'] >= $timer) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    };
}

/*Destroys session if someone tries to access from different device or with a different ip address */
if (isset($_SESSION['ip'], $_SESSION['user_agent'])) {
    if (
        $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] ||
        $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']
    ) {

        session_unset();
        session_destroy();
        header('Location: ../index.php');
        exit;
    }
}
$idleTimer = 600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $idleTimer) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}



