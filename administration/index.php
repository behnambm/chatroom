<?php
session_start();
require_once '../functions.php';
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
        <link rel="icon" href="../files/images/tab-icon.png">
        <link rel="stylesheet" href="../files/css/font-awesome.min.css">
        <link rel="stylesheet" href="../files/css/bootstrap-4.1.2.min.css">
        <link rel="stylesheet" href="../files/css/style.css">
        <title>پنل مدیریت</title>
</head>
<body  style="padding: 10px 0 0 0;text-align:right;">
        <div class="container-fluid">
                <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-12 ">
                                <ul class="list-group">
                                        <li class="list-group-item active"><i class="fa fa-dashboard icon"></i>داشبورد</li>
                                        <li class="list-group-item "><a href="javascript:;"><i class="fa fa-"></i>آمار</a></li>
                                        <li class="list-group-item "><a href="?logout=1"><i class="fa fa-sign-out icon"></i>خروج</a></li>
                                </ul>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-xs-12" id="admin-access-page">
                                <ul class="list-group">
                                        <li class="list-group-item active">آمار<em><a href="../index.php" class="go-back-lg">برگشت   </a></em></li>
                                        <li class="list-group-item">
                                                <div class="form-group admin-form-group">
                                                        <label for="username-id">نام کاربری : </label>
                                                        <input type="text" name="username" id="username-id" class="form-control" autocomplete="off">
                                                        <ul id="search-res" class="list-group"></ul>
                                                        <br>
                                                        <span id="no-username">نام کاربری وارد شده وجود ندارد.</span>
                                                </div>
                                                <div class="form-group">
                                                        <label for="statistics-operation">عملیات : </label>
                                                        <select class="form-control" name="statistics_operation" id="statistics-operation">
                                                                <option value="none" id="none-operation">هیچکدام</option>
                                                                <option value="login">ورود های کاربر</option>
                                                                <option value="messages">چت های کاربر در گروه</option>

                                                        </select>
                                                </div>
                                                <div class="statistics-result"></div>
                                        </li>
                                </ul>
                        </div>
                </div>
        </div>

<script src="../files/js/jquery-3.1.1.js"></script>
<script src="../files/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(()=>{
        var keyupCount = 0;
        let id = null;



        //--------------------------------------------------------------
        // update last activity
        setInterval(function updateActivity() {
                $.ajax({
                        url: '../update_last_activity.php',
                        type: 'POST',
                        success: () => {}
                });
        }, 4000);




        $('#username-id').keyup((e)=>{
                if(keyupCount != 0){ // This is for a bug that makes many times <li>
                        $('#search-res li').remove();
                }
                keyupCount++;
                let data = $('#username-id').val().trim();
                if(data == ''){
                        return 0;
                }
                $.ajax({
                        url:'statistics.php',
                        type:'POST',
                        data:{
                                username:data
                        },
                        success:(responce)=>{
                                if(responce != 0){ // We have one or more user
                                        $('#no-username').fadeOut('fast');
                                        var len = JSON.parse(responce).length; // Length of users
                                        var res = JSON.parse(responce);
                                        if(len > 1){
                                                res.forEach((val)=>{
                                                        $('#search-res').append('<li class="list-group-item" data-id="'+val['id']+'">'+val['username']+'</li>').delay(300).slideDown('slow');
                                                        $('#search-res li').click((e)=>{
                                                                var username = $(e.target).html();
                                                                $('#username-id').addClass('verified');
                                                                $('#username-id').val(username);
                                                                $('#search-res').slideUp('slow');
                                                                $('#search-res li').remove();
                                                                id = $(e.target).data('id');
                                                                $('#none-operation').prop('selected','selected');
                                                                $('.statistics-result').fadeOut('slow');
                                                        });
                                                });
                                        }else{
                                                $('#search-res').append('<li class="list-group-item" data-id="'+res[0]['id']+'">'+res[0]['username']+'</li>').slideDown('slow');
                                                $('#search-res li').click((e)=>{
                                                        $('#username-id').addClass('verified');
                                                        $('#username-id').val(res[0]['username']);
                                                        $('#search-res').slideUp('slow');
                                                        $('#search-res li').remove();
                                                        id = res[0]['id'];
                                                        $('#none-operation').prop('selected','selected');
                                                        $('.statistics-result').fadeOut('slow');
                                                });
                                        }

                                }else{ // There is no user
                                        $('#no-username').fadeIn('slow');
                                }
                        }
                });
        });
        //--------------------------------------------------------------
        // event for when selecet changes
        $('#statistics-operation').change((e)=>{
                let operation = $(e.target).children('option:selected').val();
                if($('#username-id').hasClass('verified')){
                        if(operation != 'none'){
                                $.ajax({
                                        url:'statistics.php',
                                        type:'POST',
                                        data:{
                                                id:id,
                                                operation:operation
                                        },
                                        success:(res)=>{
                                                let data = JSON.parse(res);
                                                if(data != 'none'){
                                                        let output = null;
                                                        if(operation == 'login'){
                                                                output = '<table class="table table-striped">';
                                                                output += '<thead>';
                                                                output += '<tr>';
                                                                output += '<td> ردیف </td><td> تاریخ </td>';
                                                                output += '</tr>';
                                                                output += '</thead>';
                                                                output += '<tbody>';
                                                                let num = 1;
                                                                for(let i = 0; i < data.length; i++){
                                                                        output += '<tr>';
                                                                        output += '<td>'+num+'</td> <td style="direction: ltr;">'+data[i]['last_activity']+'</td> ';
                                                                        output += '</tr>';
                                                                        num++;
                                                                }
                                                                output += '</tbody>';
                                                                output += '</table>';
                                                                $('.statistics-result').html(output).removeClass('no-scroll').show();
                                                        }else if(operation == 'messages'){ // if operation = messges

                                                        }
                                                }else{
                                                        $('.statistics-result').html('تاریخچه ورود کاربر وجود ندارد.').addClass('no-scroll').show();
                                                }


                                        }
                                });
                        }else{ // if user choose هیچکدام in SELECT
                                $('.statistics-result').fadeOut('slow');
                        }
                }
        });
}); // ready {}
</script>
</body>
</html>
