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
                $stmt = $con->prepare("SELECT chat_message FROM chat_message WHERE id = ?");
                $stmt->execute(array($id));
                $msg = $stmt->fetchAll();
                if($msg[0]['chat_message'] != trim($_POST['message'])){
                        $stmt2 = $con->prepare("UPDATE chat_message SET chat_message = ? , is_edited = 1 WHERE id = ?");
                        $stmt2->execute(array(trim($_POST['message']),$id));
                }

        }
}
