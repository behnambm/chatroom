<?php
session_start();
require_once 'functions.php';

if(isset($_SESSION['logged_in'])){
    if(isset($_SESSION['confirm_kick'], $_SESSION['username'] , $_SESSION['id'])){
        if(delete_account($_POST['username'] , null, $_POST['id'])){
            echo 'DONE';
        }else{
            echo 'ERR_SOMETHING_WRONG';
        }
    }else if(isset($_POST['username'], $_POST['id'])){
        if(get_user_info($_POST['username'])){
            echo 'OK';
        }else{
            echo 'ERR_USER_NOT_FOUND';
        }
    }
}