<?php

session_start();
require_once 'functions.php';
date_default_timezone_set('Asia/Tehran');
// ini_set('display_errors',1);
if(!isset($_SESSION['logged_in'])){
        echo '<script>window.location = "login.php"</script>';
}
$stmt = $con->prepare("SELECT * FROM users WHERE id != {$_SESSION['user_id']}");
$stmt->execute();
$count = $stmt->rowCount();
$res = $stmt->fetchAll();
if($count > 0){
        $output = '<table class="table table-striped">
        <thead class="thead-light">
        <tr>
        <th scope="col">نام</th>
        <th scope="col">وضعیت</th>
        <th scope="col">چت</th>
        </tr>
        </thead>  <tbody>';
        $priv_flag = null;
        if(isset($_SESSION['privilage'])){
                if($_SESSION['privilage'] == 'owner'){
                        $priv_flag ='owner';
                }else if($_SESSION['privilage'] == 'admin'){
                        $priv_flag = 'admin';
                }
        }
        foreach($res as $row){
                $status ='';
                $current_stimestamp = strtotime(date('Y-m-d H:i:s').'-4 second');
                $current_stimestamp = date('Y-m-d H:i:s',$current_stimestamp);
                $user_last_activity = fetch_user_last_activity($row['id']);

                if($user_last_activity > $current_stimestamp){
                        $status = '<span class="badge badge-success">آنلاین</span>';
                }else{
                        $status = '<span class="badge badge-danger">آفلاین</span>';
                }
                $privilage = null;
                $del_user = null;
                if(check_admin($row['username'])){
                        $data = check_admin($row['username']);
                        if($data['privilage'] == 'admin'){
                                if($priv_flag == 'owner'){
                                        $del_user = '&nbsp;<a href="javascript:;" data-username="'.$row['username'].'" data-id="'.$row['id'].'" class="badge badge-danger del-uesr-link">حذف کاربر</a>';
                                }
                                $data = 'مدیر';
                        }else if($data['privilage'] == 'owner'){
                                $data = 'مدیر اصلی';
                        }
                        $privilage = '&nbsp;<span class="badge badge-success">'.$data.'</span>';
                }else{
                        if($priv_flag == 'admin' || $priv_flag == 'owner'){
                                $del_user = '&nbsp;<a href="javascript:;" data-username="'.$row['username'].'" data-id="'.$row['id'].'" class="badge badge-danger del-uesr-link">حذف کاربر</a>';
                        }
                }
                $output .= '  <tr>
                <th scope="row"><img src="'.$row['profile_pic'].'"><span>'.$row['display_name'].'</span><small class="is-typing" style="display:'.fetch_is_type($row['id']).'">در حال نوشتن ...</small></th>
                <td>'.$status.$privilage.$del_user.'</td>
                <td><button type="button" class="chat-btn btn btn-primary btn-sm" data-tousername="'.$row['username'].'" data-touserid="'.$row['id'].'">شروع چت'.fetch_unseen_chat($row['id'],$_SESSION['user_id']).'</button></td>
                </tr>';
        }
        $output .= '  </tbody>
        </table>';
        echo $output;
}
