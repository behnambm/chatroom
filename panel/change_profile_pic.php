<?php
ini_set('display_errors',1);
require_once '../functions.php'; 
session_start(); 
if (isset($_FILES['avatar'])) {
    $img = $_FILES['avatar']['name']; 
    $tmp = $_FILES['avatar']['tmp_name']; 
    $path = '../profile_img'; 
    if ( ! is_dir($path)) {
        mkdir($path); 
    }
    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION)); 
    $path = $path . '/' . $_SESSION['username'] . '.' . $ext; 
    if (file_exists($path)) {
        unlink($path); 
    }
        if (move_uploaded_file($tmp, $path)) {

        add_profile_path_to_db($_SESSION['username'], 'profile_img/' . $_SESSION['username'] . '.' . $ext); 
        $_SESSION['profilepic'] = 'profile_img/' . $_SESSION['username'] . '.' . $ext; 
        echo 'OK'; 
    }else {
        echo 'ERR_ON_MOVE'; 
    }
}else {
    echo 'ERR_NO_FILE_SEND'; 
}