<?php

session_start();
require_once '../functions.php';
if(!empty($_POST['old_password']) && !empty($_POST['new_password']) && !empty($_POST['re_new_password'])){
    $user = get_user_info($_SESSION['username']);
    if($user['password'] == encrypt_pass($_POST['old_password'])){
        if($_POST['new_password'] == $_POST['re_new_password']){
            if($_POST['new_password'] != $_POST['old_password']){
                $stmt = $con->prepare("UPDATE users SET password = ? WHERE username = ? AND id = ?");
                $stmt->execute(array(encrypt_pass($_POST['new_password']), $_SESSION['username'], $_SESSION['user_id']));
                echo 'OK';
            }else{
                echo 'ERR_OLD_EQUAL_WITH_NEW';
            }
        }else{
            echo 'ERR_DATA_NOT_EQUAL';
        }
    }else{
        echo 'ERR_OLD_PASS';
    }
}else{
    echo 'ERR_NO_DATA_SENT';
}

