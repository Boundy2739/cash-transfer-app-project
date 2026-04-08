<?php

function rate_limiter($key,$limit,$pdo)
{

    $ip = ip2long($key);

    $sql = "SELECT * from rate_limit_ips where ip_address =:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ip" => $ip]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$result) {
        //First login error
        $sql = "INSERT into rate_limit_ips (ip_address, attempts,last_attempt) VALUES (:ip,:attempts,NOW()) ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":ip" => $ip,
            "attempts" => 1,
        ));
        return;
    }

    $sql = "UPDATE rate_limit_ips SET attempts = attempts + 1, last_attempt = NOW() where ip_address =:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ip" => $ip]);

    if ($result['attempts'] >= $limit) {
        $sql = "UPDATE rate_limit_ips SET last_attempt = NOW(), lock_time = NOW() WHERE ip_address = :ip";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":ip" => $ip]);
        return "Too many attempts, retry later";
    }
}
function is_locked($key,$pdo){
    $ip = ip2long($key);
    $currentTime = time();

    $sql = "SELECT lock_time from rate_limit_ips WHERE ip_address =:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ip"=>$ip]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result || !$result['lock_time']) {
        // ip not locked yet
        return false;
    }
    $lockTime = strtotime($result['lock_time']);

    if ($currentTime - $lockTime < 3600){
        //ip still locked
        return TRUE;
    }

    return false; //timer expired

}
?>