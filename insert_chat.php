<?php
session_start();
require_once 'functions.php';
date_default_timezone_set('Asia/Tehran');
$current_time = date('Y-m-d H:i:s');
$stmt = $con->prepare("INSERT INTO chat_message(to_user_id,from_user_id,chat_message, timestamp ,status,is_sent) VALUES(?,?,?,?,?,?)");
if($stmt->execute(array($_POST['to_user_id'], $_SESSION['user_id'], $_POST['chat_message'], $current_time , '1','1'))){
    echo fetch_chat_history($_SESSION['user_id'] , $_POST['to_user_id']);
    
}
