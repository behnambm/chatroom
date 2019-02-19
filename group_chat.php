<?php
session_start();
ini_set('display_errors',1);
date_default_timezone_set('Asia/Tehran');
require_once 'functions.php';
$current_time = date('Y-m-d H:i:s');

if(isset($_POST['action']) || isset($_POST['group_chat_message'])){
    if($_POST['action'] == 'insert'){
        $stmt = $con->prepare("INSERT INTO chat_message(from_user_id, to_user_id, chat_message, timestamp, is_sent, is_seen)
        VALUES(?,?,?,?,?,?);");
        $stmt->execute(array($_SESSION['user_id'], '0', $_POST['group_chat_message'],$current_time , '1', '0'));
        echo fetch_group_chat_history($_SESSION['user_id']);
    }elseif($_POST['action']=='fetch'){
        $stmt = $con->prepare("UPDATE chat_message SET is_seen = '1' WHERE from_user_id != ? AND to_user_id = '0'");
        $stmt->execute(array($_SESSION['user_id']));
        echo fetch_group_chat_history($_SESSION['user_id']);
        
    }elseif($_POST['action'] == 'msg_count'){
        $stmt = $con->prepare("SELECT * FROM chat_message WHERE is_seen = '0' AND to_user_id = '0' AND from_user_id != ?");
        $stmt->execute(array($_SESSION['user_id']));
        $count = $stmt->rowCount();
        if($count > 0){
            if($count > 99){
                return '99+';
            }else{
                echo $count;
            }
        }
    }else{
        echo 'ERR_INVALID_ACTION';
    }
}else{
    echo 'ERR_NO_DATA_SENT';
}
