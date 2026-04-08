<?php
require_once "./pdo.php";
function rate_limiter($key,$limit,$period,$pdo){

    $ip = ip2long($key);

    $sql = "SELECT * from rate_limit_ips where ip_address =:ip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":ip"=>$ip]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if(!$result){
        $sql = "INSERT into rate_limit_ips (ip_address, attempts) VALUES (:ip,:attempts) ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ":ip"=>$ip,
            "attempts"=>1,
        ));
    }

    else{
        $sql = "UPDATE rate_limit_ips SET attempts = attempts + 1 last_attempt = NOW() where ip_address =:ip";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":ip"=>$ip]);
    }
    




}
