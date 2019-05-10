<?php
session_start();
require_once 'functions.php';
date_default_timezone_set('Asia/Tehran');
ini_set('display_errors',1);
if (isset($_SESSION['logged_in'], $_POST['to_user_id'], $_POST['chat_message']) && !$_SESSION['is_kicked']) {
        $stmt = $con->prepare("SELECT id_per_msg FROM chat_message WHERE (from_user_id = ? AND to_user_id = ?) OR (from_user_id = ? AND to_user_id = ?) ORDER BY id_per_msg DESC LIMIT 1");
        $stmt->execute(array($_SESSION['user_id'], $_POST['to_user_id'], $_POST['to_user_id'], $_SESSION['user_id']));
        $res = $stmt->fetchAll();
        $msg_id = null;
        foreach($res as $row){
                $msg_id = (int)$row['id_per_msg']+1;
        }
        if($msg_id == null){
                $msg_id = 0;
        }
        $current_time = date('Y-m-d H:i:s');
        $stmt = $con->prepare("INSERT INTO chat_message(to_user_id,from_user_id,chat_message, timestamp ,is_seen ,is_sent,id_per_msg) VALUES(?,?,?,?,?,?,?)");
        if ($stmt->execute(array($_POST['to_user_id'], $_SESSION['user_id'], trim($_POST['chat_message']), $current_time, '0', '1',$msg_id))) {
                echo fetch_chat_history($_SESSION['user_id'], $_POST['to_user_id']);
        }
}else{
        echo '<script>window.location = "login.php"</script>';
}
