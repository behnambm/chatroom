<?php
session_start();
require_once '../functions.php';

if(isset($_POST['user_confirm'], $_POST['password_for_del'])){

}else if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] =='yes'){
    if(isset($_POST['password_for_del']) && !empty($_POST['password_for_del'])){
        if(doLogin($_SESSION['username'], $_POST['password_for_del'])){
            echo 'PASS_OK';
        }else{
            echo 'PASS_INCORRECT';
        }
    }
}
