<?php

require_once 'functions.php';


if(isset($_POST['username'],$_POST['password'],$_POST['remember'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = $_POST['remember'];

}
if(doLogin($username, $password)){
    set_session($username);
    if($remember == true){
        set_cookie($username);
    }
    header('Location:index.php');
}else{
    echo 0;
}