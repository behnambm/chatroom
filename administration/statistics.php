<?php
session_start();
require_once '../functions.php';
ini_set('display_errors',1);
date_default_timezone_set('Asia/Tehran');
if(isset($_SESSION['logged_in'], $_SESSION['privilage']) && $_SESSION['privilage'] == 'owner'){
        if(isset($_POST['username'])){  // This request comes from user finding section
                $stmt = $con->prepare("SELECT username,id FROM users WHERE username LIKE ?; ");
                $stmt->execute(array('%'.$_POST['username'].'%'));
                $count = $stmt->rowCount();
                $res = $stmt->fetchAll(2);
                if($count > 0){
                        foreach ($res as $key => $value)
                                $data[$key] = $value;
                        echo json_encode($data);
                }else
                        echo 0;
        }else if(isset($_POST['id'], $_POST['operation'])){
                $data = null;
                switch ($_POST['operation']) {
                        case 'login':
                                $stmt = $con->prepare("SELECT last_activity FROM `login_details` where user_id = ? ORDER BY last_activity DESC");
                                $stmt->execute(array($_POST['id']));
                                $ignore_num = 0;
                                if($stmt->rowCount() > 0){
                                        foreach ($stmt->fetchAll(2) as $key => $value)
                                                $data[$key]=$value;
                                        $data_dup = $data; // A copy  of $data
                                        $shifted_row = array_shift($data_dup);  // To ignore first element of array ( Beacuse first element is the Date that we use for check Online/Offline)
                                        $data_dup = $shifted_row['last_activity'];
                                        $shifted_stamp = strtotime($data_dup);
                                        $minus_of_stamps = strtotime(date('Y-m-d H:i:s')) - $shifted_stamp;
                                        if($minus_of_stamps < 6 ) // if this uesr is online the minus must be less that 4
                                                array_shift($data);
                                }else
                                        $data = 'none';
                                break;
                        case 'messages':
                                        $stmt = $con->prepare("SELECT from_user_id,to_user_id,chat_message,timestamp,is_sent,is_sent,is_edited
                                                FROM `chat_message` WHERE from_user_id = ? ORDER BY `timestamp` DESC");
                                        $stmt->execute(array($_POST['id']));
                                        if($stmt->rowCount() > 0){
                                                foreach($stmt->fetchAll(2) as $i=>$j){
                                                        foreach ($j as $key => $value) {
                                                                if($key == 'to_user_id'){
                                                                        if($value == 0)
                                                                                $data[$i]['to_user'] = 'group';
                                                                        else{
                                                                                $user = get_user_info(null,$value);
                                                                                $data[$i]['to_user'] = $user['username'];
                                                                                $data[$i]['to_display_name'] = $user['display_name'];
                                                                        }
                                                                }else if($key == 'from_user_id') {
                                                                        $user = get_user_info(null,$value);
                                                                        $data[$i]['from_username'] = $user['username'];
                                                                        $data[$i]['from_display_name'] = $user['display_name'];
                                                                }else
                                                                        $data[$i][$key] = $value;
                                                        }
                                                }
                                        }else{
                                                $data = 'none';
                                        }
                                break;
                        default:
                                $data = 0;
                                break;
                }
                echo json_encode($data);
        }
}
