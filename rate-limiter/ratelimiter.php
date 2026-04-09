<?php
require_once "../config/config.php";
function rate_limiter($key, $limit, $pdo)
{

    $ip = ip2long($key);
    $currentTime = date("Y-m-d H:i:s");
    $sql = "SELECT * from rate_limit_ips where ip_address =:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ip" => $ip]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$result) {
        //First login error
        $sql = "INSERT into rate_limit_ips (ip_address, attempts,last_attempt) VALUES (:ip,:attempts,:last_attempt) ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":ip" => $ip,
            "attempts" => 1,
            ":last_attempt" => $currentTime,
        ));
        return;
    }

    $sql = "UPDATE rate_limit_ips SET attempts = attempts + 1, last_attempt =:last_attempt where ip_address =:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":ip" => $ip, ":last_attempt" => $currentTime));

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
{
    $ip = ip2long($key);
    $currentTime = time();
    print_r($currentTime);
    $sql = "SELECT lock_time from rate_limit_ips WHERE ip_address =:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ip" => $ip]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result || !$result['lock_time']) {
        // ip not locked yet
        return false;
    }
    $lockTime = strtotime($result['lock_time']);

    if ($currentTime - $lockTime < 60) {
        $_SESSION['time'] = $currentTime;
        $_SESSION['time2'] = $lockTime;
        //ip still locked
        return TRUE;
    }

    reset_attempts($key, $pdo);
    return false; //timer expired attempts are reset

}
function reset_attempts($key, $pdo)
{
    $ip = ip2long($key);
    $sql = "UPDATE rate_limit_ips SET attempts = 0, lock_time = NULL WHERE ip_address=:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ip" => $ip]);
}
