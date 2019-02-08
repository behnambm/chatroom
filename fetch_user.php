<?php

session_start();
require_once 'functions.php';

$stmt = $con->prepare("SELECT * FROM users WHERE id != {$_SESSION['user_id']}");
$stmt->execute();
$count = $stmt->rowCount();
$res = $stmt->fetchAll();
if($count > 0){

    $output = '<table class="table table-striped">
    <thead class="thead-light">
      <tr>
        <th scope="col">نام کاربری</th>
        <th scope="col">وضعیت</th>
        <th scope="col">چت</th>
      </tr>
    </thead>  <tbody>';
    foreach($res as $row){
        $output .= '  <tr>
        <th scope="row"><img src="'.$row['profile_pic'].'">'.$row['username'].'</th>
        <td></td>
        <td><button type="button" class="btn btn-primary btn-sm" data-to-username="'.$row['username'].'" data-to-userid="'.$row['id'].'">شروع چت</button></td>
      </tr>';
    }
    $output .= '  </tbody>
    </table>';
    echo $output;
}
