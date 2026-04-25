<?php
require_once "../config/config.php";
//IP based rate limit, currently only works for ipv4
function rate_limiter($key, $limit, $pdo)
{
    $ip = ip2long($key);
    $currentTime = date("Y-m-d H:i:s");
    $sql = "SELECT * from rate_limit_ips where ip_address =:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ip" => $ip]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$result) {
        //This runs only on the first login error
        $sql = "INSERT into rate_limit_ips (ip_address, attempts,last_attempt) VALUES (:ip,:attempts,:last_attempt) ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":ip" => $ip,
            "attempts" => 1,
            ":last_attempt" => $currentTime,
        ));
        return;
    }
    //Increases the attempt count each time the user makes the login mistake
    $sql = "UPDATE rate_limit_ips SET attempts = attempts + 1, last_attempt =:last_attempt where ip_address =:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":ip" => $ip, ":last_attempt" => $currentTime));

    //if the user reaches the limit, their account is locked
    if ($result['attempts'] >= $limit) {
        $sql = "UPDATE rate_limit_ips SET last_attempt = :last_attempt, lock_time = :lock_time WHERE ip_address = :ip";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":ip" => $ip,
            ":lock_time"=>$currentTime,
            ":last_attempt"=>$currentTime
        ));
        return;
    }
}
function is_locked($key, $pdo)
{   //checks if the user is locked,
    $ip = ip2long($key);
    $currentTime = time();
    print_r($currentTime);
    $sql = "SELECT lock_time from rate_limit_ips WHERE ip_address =:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ip" => $ip]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result || !$result['lock_time']) {
        // If the user doesn't have a lock time, it means they have not been locked yet
        return false;
    }
    $lockTime = strtotime($result['lock_time']);
    //If the user has a lock time, it compares it with the current time and checks if 10 minutes have passed
    if ($currentTime - $lockTime < 600) {
        //ip still locked
        return TRUE;
    }

    reset_attempts($key, $pdo);
    return false; //10 minutes timer expired attempts are reset

}
function reset_attempts($key, $pdo)
{
    $ip = ip2long($key);
    $sql = "UPDATE rate_limit_ips SET attempts = 0, lock_time = NULL WHERE ip_address=:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ip" => $ip]);
}
