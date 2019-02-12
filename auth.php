<?php
session_start();
date_default_timezone_set('Asia/Tehran');
require_once 'functions.php';
//  *********************************************************************
//  ************************** LOGIN SECTION ****************************
if(isset($_POST['logusername'],$_POST['logpassword'],$_POST['logremember'])){
    $username = $_POST['logusername'];
    $password = $_POST['logpassword'];
    $remember = $_POST['logremember'];

    if(doLogin($username, $password)){
        $user = get_user_info($_POST['logusername']);
        set_id_to_login_details($user['id']);
        $log_det = get_login_details($user['id']);
        $_SESSION['login_details_id'] = $log_det['login_details_id'];
        set_session($user['username'], $user['profile_pic'], $user['display_name'], get_real_ip(),$user['id'],$user['email']);
        
        // echo $remember, gettype($remember);
        if($remember == 'true'){
            $now = time();
            $tmp = 60*60*24*30;
            setcookie('logged_in','yes',$now+$tmp);
            $cookie_info = get_cookie_info_by_user($user['username']);
            setcookie('hash',$cookie_info['cookie_hash'],$now+$tmp);
        }
        echo 'OK';
        
    }else{
        echo 'ERR_USER_PASS';
    }

//  *********************************************************************
//  ************************* REGISTER SECTION **************************
}else if(isset($_POST['regusername'],$_POST['regpassword'],$_POST['regdisplayname'],$_POST['regemail']) ){
    $res = register_user($_POST['regusername'], $_POST['regpassword'], $_POST['regemail'], $_POST['regdisplayname']);
    if($res == 1){
        $user = get_user_info($_POST['regusername']);

        set_id_to_login_details($user['id']);
        $log_det = get_login_details($user['id']);
        $_SESSION['login_details_id'] = $log_det['login_details_id'];
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
                set_session($_POST['regusername'], $path, $_POST['regdisplayname'], get_real_ip(),$user['id'], $user['email']);
                echo 'OK';
                
            }else{
                echo 'ERR_MOVING_PIC';
            }
        }else{
            set_session($_POST['regusername'], 'files/images/user.png', $_POST['regdisplayname'], get_real_ip(),$user['id'], $user['email']);
            echo 'OK';
        }

        

    }else{
        echo $res;
    }
}
