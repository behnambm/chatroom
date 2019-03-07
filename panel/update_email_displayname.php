<?php

require_once '../functions.php';
session_start();
$user = get_user_info($_SESSION['username']);
if($user['display_name'] == $_POST['up_display_name']){
    if($user['email'] == $_POST['up_email']){
        echo 'OK';
    }else{
        $stmt = $con->prepare("UPDATE users SET email = ? WHERE id = ? AND username = ?");
        $stmt->execute(array($_POST['up_email'], $_SESSION['user_id'], $_SESSION['username']));
        $_SESSION['email'] = $_POST['up_email'];
        echo 'OK_EMAIL';
    }
}else{
    if($user['email'] == $_POST['up_email']){
        $stmt = $con->prepare("UPDATE users SET display_name = ? WHERE id = ? AND username = ?");
        $stmt->execute(array($_POST['up_display_name'], $_SESSION['user_id'], $_SESSION['username']));
        $_SESSION['displayname'] = $_POST['up_display_name'];
        echo 'OK_DISPLAYNAME';
    }else{
        $stmt = $con->prepare("UPDATE users SET email = ? , display_name = ? WHERE id = ? AND username = ?");
        $stmt->execute(array($_POST['up_email'], $_POST['up_display_name'] , $_SESSION['user_id'], $_SESSION['username']));
        $_SESSION['email'] = $_POST['up_email'];
        $_SESSION['displayname'] = $_POST['up_display_name'];
        echo 'OK_NAME_EMAIL';
    }
}

