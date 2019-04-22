<?php
ini_set('display_errors',1);
session_start();
require_once 'functions.php';


if($_SESSION['logged_in'] == 'yes'){
        if($_POST['action'] == 'edit'){
                $msg_id =base64_decode($_POST['msg_id']);
                $stmt=$con->prepare("SELECT * from chat_message WHERE id = ?");
                $stmt->execute(array($msg_id));
                $id = null;
                if($stmt->rowCount() == 1){
                        foreach ($stmt->fetchAll() as $row) {
                                $id = $row['from_user_id'];
                                if($_SESSION['user_id'] == $id){
                                        echo json_encode($row);
                                }
                        }

                }
        }else if($_POST['action'] == 'edit-write'){
                $id = base64_decode($_POST['msg_id']);
                $stmt = $con->prepare("UPDATE chat_message SET chat_message = ? WHERE id = ?");
                $stmt->execute(array($_POST['message'],$id));
        }
}
