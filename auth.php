<?php
session_start();
require_once 'functions.php';
    //  *********************************************************************
    //  ************************** LOGIN SECTION ****************************
if(isset($_POST['logusername'],$_POST['logpassword'],$_POST['logremember'])){
    $username = $_POST['logusername'];
    $password = $_POST['logpassword'];
    $remember = $_POST['logremember'];


    //  *********************************************************************
    //  ************************* REGISTER SECTION **************************
}else if(isset($_POST['regusername'],$_POST['regpassword'],$_POST['regdisplayname'],$_POST['regemail']) ){
    $res = register_user($_POST['regusername'], $_POST['regpassword'], $_POST['regemail'], $_POST['regdisplayname']);
    if($res == 1){
        if(isset($_FILES['avatar'])){
            $path = 'profile_img';
            $img = $_FILES['avatar']['name'];
            $tmp = $_FILES['avatar']['tmp_name'];
            if(!is_dir($path)){
                mkdir($path);
            }
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            $path = $path.'/'.$_POST['regusername'].'.'.$ext;
            if(file_exists($path)){
                unlink($path);
            }
            if(move_uploaded_file($tmp, $path)){
                add_profile_path_to_db($_POST['regusername'], $path);
                $_SESSION['logged_in'] = 'yes';
                $_SESSION['username'] = $_POST['regusername'];
                $_SESSION['profilepic'] = $path;
                $_SESSION['displayname'] = $_POST['regdisplayname'];
                echo 'OK';
                
            }else{
                echo 'ERR_MOVING_PIC';
            }
        }else{
            add_profile_path_to_db($_POST['regusername'], 'files/images/user.png');
            $_SESSION['logged_in'] = 'yes';
            $_SESSION['username'] = $_POST['regusername'];
            $_SESSION['profilepic'] = $path;
            $_SESSION['displayname'] = $_POST['regdisplayname'];
            echo 'OK';
        }

        

    }else{
        echo $res;
    }
}
