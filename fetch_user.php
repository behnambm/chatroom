<?php

session_start();
require_once 'functions.php';
date_default_timezone_set('Asia/Tehran');



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
    foreach($res as $row){
        $status ='';
        $current_stimestamp = strtotime(date('Y-m-d H:i:s').'-10 second');
        $current_stimestamp = date('Y-m-d H:i:s',$current_stimestamp);
        $user_last_activity = fetch_user_last_activity($row['id']);
        if($user_last_activity > $current_stimestamp){
          $status = '<span class="badge badge-success">آنلاین</span>';
        }else{
          $status = '<span class="badge badge-danger">آفلاین</span>';

        }
        $output .= '  <tr>
        <th scope="row"><img src="'.$row['profile_pic'].'">'.$row['display_name'].'</th>
        <td>'.$status.'</td>
        <td><button type="button" class="chat-btn btn btn-primary btn-sm" data-tousername="'.$row['username'].'" data-touserid="'.$row['id'].'">شروع چت'.fetch_unseen_chat($row['id'],$_SESSION['user_id']).'</button></td>
      </tr>';
    }
    $output .= '  </tbody>
    </table>';
    echo $output;
}

