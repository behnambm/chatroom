<?php
require_once 'db_config.php';

try{
    $con = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8",'root','');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'BM_Error ::'.$e->getMessage();
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

function get_user_by_username($username){
    global $con;
    $stmt = $con->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute(array($username));
    $count = $stmt->rowCount();
    if($count == 0 ){
        return true;
    }else{
        return false;
    }
}

function get_user_by_email($email){
    global $con ;
    $stmt = $con->prepare("SELECT * FROM users WHERE email = ? ;");
    $stmt->execute(array(strtolower($email)));
    $count = $stmt->rowCount();
    if($count == 0){
        return true;
    }else{
        return false;
    }
}

function register_user($user, $pass, $email, $displayname){
    global $con;
    if(get_user_by_username($user)){
        if(get_user_by_email($email)){
            $stmt = $con->prepare("INSERT INTO users VALUES(null,?,?,?,?);");
            $stmt->execute(array(strtolower($user), encrypt_pass($pass), $displayname, strtolower($email) ));
            $count = $stmt->rowCount();
            if($count > 0 ){
                return true;
            }else{
                return false;
            }
        }else{
            return 'ERR_DUP_EMAIL';
        }
    }else{
        return 'ERR_DUP_USERNAME';
    }
}


