<?php
session_start();
require_once 'functions.php';

if(isset($_SESSION['logged_in'], $_SESSION['privilage'])){
    if(isset($_POST['confirm_kick'], $_POST['username'] , $_POST['id']) && $_POST['confirm_kick'] == true){
        // this code will execute when admin/owner confirm USER_KICK
        if(delete_account($_POST['username'] , null, $_POST['id'])){
            echo 'DONE';
        }else{
            echo 'ERR_SOMETHING_WRONG';
        }
    }else if(isset($_POST['username'], $_POST['id'])){
        if(get_user_info($_POST['username'])){
            // this code check username and user id for existing 
            echo 'OK';
        }else{
            echo 'ERR_USER_NOT_FOUND';
        }
    }
}