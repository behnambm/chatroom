<?php
session_start();
require_once '../functions.php';

if(isset($_POST['user_confirm'], $_POST['password_for_del'])){
    if($_POST['user_confirm']=='YES' && doLogin($_SESSION['username'], $_POST['password_for_del'])){
        if(delete_account($_SESSION['username'], $_SESSION['email'], $_SESSION['user_id'])){
            unset($_SESSION['user_id'],$_SESSION['username'],$_SESSION['email'],$_SESSION['ip'],$_SESSION['profilepic'],$_SESSION['logged_in'],$_SESSION['login_details_id']);
            setcookie('logged_in','',1);
            setcookie('hash','',1);
            echo '<script>window.location = "../login.php"</script>';
        }else{
            echo 'HAVE_SOME_ERR';
        }
    }else{
        echo 'PASS_INCORRECT';
    }
}else if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] =='yes'){
    if(isset($_POST['password_for_del']) && !empty($_POST['password_for_del'])){
        if(doLogin($_SESSION['username'], $_POST['password_for_del'])){
            echo 'PASS_OK';
        }else{
            echo 'PASS_INCORRECT';
        }
    }
}
