<?php
session_start();
require_once 'functions.php';
// ini_set('display_errors',1);
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes'){
        $stmt = $con->prepare("UPDATE login_details SET last_activity = now() WHERE login_details_id = ?");
        $stmt->execute(array($_SESSION['login_details_id']));
        $stmt2 = $con->prepare("SELECT * FROM users WHERE id = ?");
        $stmt2->execute(array($_SESSION['user_id']));
        $res = $stmt2->fetchAll();
        $_SESSION['is_kicked'] = false;
        if(empty($res[0])){
                $_SESSION['is_kicked'] = true;
                logout();
        }else{
                echo 'OK';
        }
}else{
        echo false;
}
