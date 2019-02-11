<?php
session_start();
require_once 'functions.php';
if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != 'yse'){
    header("Location:login.php");
}
$stmt = $con->prepare("UPDATE login_details SET last_activity = now() WHERE login_details_id = ?");
$stmt->execute(array($_SESSION['login_details_id']));


