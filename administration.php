<?php
    session_start();
    require_once 'functions.php';
    if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != 'yse'){
        header("Location:../login.php");
    }
    if(!isset($_SESSION['privilage'])){
        header("Location:../login.php");
    }
    if(isset($_GET['logout']) && $_GET['logout']==1){
        logout();
        echo '<script>window.location = "index.php";</script>';
     
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="files/css/font-awesome.min.css">
    <link rel="stylesheet" href="files/css/bootstrap-4.1.2.min.css">
    <link rel="stylesheet" href="files/css/style.css">
    <title>پنل مدیریت</title>
</head>
<body  style="padding: 10px 0 0 0;text-align:right;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-12 ">
                <ul class="list-group">
                    <li class="list-group-item active"><i class="fa fa-dashboard icon"></i>داشبورد</li>
                    <li class="list-group-item "><a href="javascript:;"><i class="fa fa-"></i>دسترسی</a></li>
                    <li class="list-group-item "><a href="?logout=1"><i class="fa fa-sign-out icon"></i>خروج</a></li>
                </ul>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-8 col-sm-8 ccol-xs-12" id="admin-access-page">
                <ul class="list-group">
                    <li class="list-group-item active">fsdf</li>
                    <li class="list-group-item">sdfs</li>
                    <li class="list-group-item">sdf</li>
                </ul>
            </div>
        </div>
    </div>

<script src="files/js/jquery-3.1.1.js"></script>
<script src="files/js/bootstrap.bundle.min.js"></script>
<script src="files/dist/cropper.js"></script>
</body>
</html>