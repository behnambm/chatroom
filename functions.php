<?php
require_once 'db_config.php';

try{
    $con = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8",'root','');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'Error ::'.$e->getMessage();
}

function encrypt_pass($pass){
    $salt = 'X`Gg.):#@BM_';
    $res = md5(sha1($salt.$pass).md5($pass));
    return $res;
}


function check_cookie($cook){
    global $con;
    $stmt = $con->prepare("SELECT * FROM cookie_id WHERE cookie_hash = '?' ");
    $stmt->execute(array($cook));
    $count = $stmt->rowCount();
    if($count > 0){
        return true;
    }else{
        return false;
    }
}



function doLogin($user, $pass){
    global $con;
    $stmt = $con->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->execute(array(strtolower($user), encrypt_pass($pass)));
    $count = $stmt->rowCount();
    if($count > 0 ){
        return true;
    }else{
        return false;
    }
}
