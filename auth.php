<?php

require_once 'functions.php';


if(isset($_POST['logusername'],$_POST['logpassword'],$_POST['logremember'])){
    $username = $_POST['logusername'];
    $password = $_POST['logpassword'];
    $remember = $_POST['logremember'];

}else if(isset($_POST['regusername'],$_POST['regpassword'],$_POST['regdisplayname'],$_POST['regemail'])){
    $res = register_user($_POST['regusername'], $_POST['regpassword'], $_POST['regemail'], $_POST['regdisplayname']);
    if($res == 1){
        echo 'YES';
    }else{
        echo $res;
    }
}


// global $username, $password , $remember;
// if(doLogin($username, $password)){
//     set_session($username);
//     if($remember == true){
//         set_cookie($username);
//     }
//     header('Location:index.php');
// }else{
//     echo 0;
// }