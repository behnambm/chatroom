<?php

require_once 'functions.php';
    //  *********************************************************************
    //  ************************** LOGIN SECTION ****************************
if(isset($_POST['logusername'],$_POST['logpassword'],$_POST['logremember'])){
    $username = $_POST['logusername'];
    $password = $_POST['logpassword'];
    $remember = $_POST['logremember'];


    //  *********************************************************************
    //  ************************* REGISTER SECTION **************************
}else if(isset($_POST['regusername'],$_POST['regpassword'],$_POST['regdisplayname'],$_POST['regemail'], $_FILES['avatar']) ){
    $res = register_user($_POST['regusername'], $_POST['regpassword'], $_POST['regemail'], $_POST['regdisplayname']);
    if($res == 1){
        $path = 'images';
        $img = $_FILES['avatar']['name'];
        $tmp = $_FILES['avatar']['tmp_name'];
        if(!is_dir($path)){
            mkdir($path);
        }
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        $path = $path.'/'.$_POST['regusername'].'.'.$ext;
        if(move_uploaded_file($tmp, $path)){
            add_profile_path_to_db($_POST['regusername'], $path);
            $_SESSION['logged_in'] = 'yes';
            $_SESSION['username'] = $_POST['regusername'];
            header("Location:index.php");
        }else{
            echo 'ERR_MOVING_PIC';
        }
        

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