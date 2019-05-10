<?php
session_start();
ini_set('display_errors',1);
date_default_timezone_set('Asia/Tehran');
require_once 'functions.php';
$current_time = date('Y-m-d H:i:s');
if(isset($_POST['action']) || isset($_POST['group_chat_message']) && !$_SESSION['is_kicked']){
        if($_POST['action'] == 'insert'){
                $stmt = $con->prepare("SELECT id_per_msg FROM chat_message WHERE to_user_id = 0
                        ORDER BY id_per_msg DESC LIMIT 1");
                        $stmt->execute();
                        $res = $stmt->fetchAll();
                        $msg_id = null;
                        foreach($res as $row){
                                $msg_id = (int)$row['id_per_msg']+1;
                        }
                        if($msg_id == null){
                                $msg_id = 0;
                        }
                        $stmt = $con->prepare("INSERT INTO chat_message(from_user_id, to_user_id, chat_message,
                                timestamp, is_sent, is_seen,id_per_msg)
                                VALUES(?,?,?,?,?,?,?);");
                                $stmt->execute(array($_SESSION['user_id'], '0', trim($_POST['group_chat_message']),$current_time , '1', '0',$msg_id));
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
                                        echo $count;
                                }
                        }else{
                                echo '<script>window.location = "login.php"</script>';
                        }
                }else{
                        echo '<script>window.location = "login.php"</script>';
                }
