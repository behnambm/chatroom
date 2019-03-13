<?php
session_start();
require_once 'functions.php';
ini_set('display_errors',1);

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes'){
    $stmt = $con->prepare("UPDATE login_details SET last_activity = now() WHERE login_details_id = ?");
    $stmt->execute(array($_SESSION['login_details_id']));
}else{
    echo false;
}