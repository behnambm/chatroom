<?php session_start();
require_once 'functions.php';
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes'){
   redirect_to('login.php');    
}
if(isset($_COOKIE['logged_in'],$_COOKIE['hash']) &&  $_COOKIE['logged_in'] = 'yes'){
    if(check_cookie($_COOKIE['hash'])){
        redirect_to('login.php');        
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>
    <link rel="stylesheet" href="files/css/font-awesome.min.css">
  <link rel="stylesheet" href="files/css/bootstrap-4.1.2.min.css">
    <link rel="stylesheet" href="files/dist/cropper.css">
    <link rel="stylesheet" href="files/css/style.css">
</head>
<body>
     <div class="container">   
        <div class="wrapper reg-wrapper">
            <form action="" method="post" enctype="multipart/form-data" id="register-form" autocomplete="off" >
                <span id="register-img"><i class="fa fa-user-plus"></i><h1>ثبت نام</h1></span>
                <div class="clear"></div>
                <div class="input-div" id="user-div">
                    <span id="icon-holder-user">
                        <i class="fa fa-user"></i>
                    </span>
                    <input type="text" name="regusername" placeholder="نام کاربری " id="username-input" required >
                </div><div class="clear"></div><div class="err err-user">این نام کاربری قبلا ثبت شده است.</div>
                <div class="input-div" id="displayname-div">
                    <span id="icon-holder-displayname">
                        <i class="fa fa-eye"></i>
                    </span>
                    <input type="text" name="regdisplayname" placeholder="نام (جهت نمایش)" id="displayname-input"  required >
                </div><div class="clear"></div>
                <div class="input-div" id="email-div">
                    <span id="icon-holder-email">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <input type="email" name="regemail" placeholder="ایمیل" id="email-input" required >
                </div><div class="clear"></div><div class="err err-email">این ایمیل قبلا ثبت شده است.</div>
                <div class="input-div" id="pass-div">
                    <span id="icon-holder-pass">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" name="regpassword" placeholder="رمز عبور " id="password-input" required >
                </div><div class="clear"></div>
                <div class="input-div" id="profilepic-div">
                    <label class="label" data-toggle="tooltip">
                        <img class="rounded" id="avatar" src="files/images/user.png" alt="avatar">
                        <input type="file" class="sr-only" id="input" name="image" accept="image/*">
                    </label>
                    <label for="input" id="icon-holder-profilepic">
                            انتخاب عکس پروفایل
                    </label>
                 </div><div class="clear"></div>
                <button type="submit" class="reg-btn" id="sub-btn">ثبت نام</button>
                <div class="loading">
                    <div class="obj"></div>
                    <div class="obj"></div>
                    <div class="obj"></div>
                    <div class="obj"></div>
                    <div class="obj"></div>
                    <div class="obj"></div>
                    <div class="obj"></div>
                    <div class="obj"></div>
                </div>
            </form>
            <div class="clear"></div>
            <div class="line">
                <div class="inner-line"></div>
            </div>
            <a href="login.php" id="login">ورود به حساب کاربری</a>
        </div>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">برش عکس</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image" src="files/images/user.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">لغو</button>
                    <button type="button" class="btn btn-primary" id="crop">برش</button>
                </div>
            </div>
        </div>
    </div>
</div>  
<script src="files/js/jquery-3.1.1.js"></script>
<script src="files/js/bootstrap.bundle.min.js"></script>
<script src="files/dist/cropper.js"></script>
<script type="text/javascript">
var formDATA = new FormData();
window.addEventListener('DOMContentLoaded', function () {
    var avatar = document.getElementById('avatar');
    var image = document.getElementById('image');
    var input = document.getElementById('input');
    var $modal = $('#modal');
    var cropper;
    $('[data-toggle="tooltip"]').tooltip();
    input.addEventListener('change', function (e) {
    var files = e.target.files;
    var done = function (url) {
        input.value = '';
        image.src = url;
        $modal.modal('show');
    };
    var reader;
    var file;
    var url;
    if (files && files.length > 0) {
        file = files[0];
        if (URL) {
        done(URL.createObjectURL(file));
        } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function (e) {
            done(reader.result);
        };
        reader.readAsDataURL(file);
        }
    }
    });
    $modal.on('shown.bs.modal', function () {
    cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 3,
    });
    }).on('hidden.bs.modal', function () {
    cropper.destroy();
    cropper = null;
    });
    document.getElementById('crop').addEventListener('click', function () {
    var initialAvatarURL;
    var canvas;
    $modal.modal('hide');
    if (cropper) {
        canvas = cropper.getCroppedCanvas({
        width: 800,
        height: 800,
        });
        initialAvatarURL = avatar.src;
        avatar.src = canvas.toDataURL();
        canvas.toBlob(function (blob) {
        var formData = new FormData();
        formData.append('avatar', blob, 'avatar.png');
        formDATA.append('avatar', blob, 'avatar.png');
        });
    }
    });
});
    
//      plugin for crop ^^^^
//******************************************************************************************************
$(document).ready(()=>{
    console.log(formDATA.get('avatar'));
    let fixContainer = ()=>{
        let ww = $(window).width();
        $('.container').width(ww);
    }
    fixContainer();
    $(window).resize(fixContainer);
    $('#register-form input').focus((e)=>{   // this code makes inputs animated
        let id = $(e.target).attr('id');
        if(id == 'username-input'){
            $('#icon-holder-user').css('top','-25px');
            $('#user-div').addClass('opacity-fill');
        }else if(id == 'password-input'){
            $('#icon-holder-pass').css('top','-25px');
            $('#pass-div').addClass('opacity-fill');      
        }else if(id == 'email-input'){
            $('#icon-holder-email').css('top','-25px');
            $('#email-div').addClass('opacity-fill');
        }else if(id == 'displayname-input'){
            $('#icon-holder-displayname').css('top','-25px');
            $('#displayname-div').addClass('opacity-fill');
        }
    });
    $('#register-form input').focusout((e)=>{
        let id = $(e.target).attr('id');
        if(id == 'username-input'){
            $('#icon-holder-user').css('top','0');
            $('#user-div').removeClass('opacity-fill'); 
        }else if(id == 'password-input'){
            $('#icon-holder-pass').css('top','0');
            $('#pass-div').removeClass('opacity-fill');        
        }else if(id == 'email-input'){
            $('#icon-holder-email').css('top','0');
            $('#email-div').removeClass('opacity-fill');        
        }else if(id == 'displayname-input'){
            $('#icon-holder-displayname').css('top','0');
            $('#displayname-div').removeClass('opacity-fill');        
        }
    });
    $('#register-form').submit((e)=>{      // when user clicked on the submit btn this will sends all informations to auth.php
        e.preventDefault();
        let username = $('#username-input').val();
        let password = $('#password-input').val();
        let email = $('#email-input').val();
        let displayname = $('#displayname-input').val();
        formDATA.append('regusername',username);
        formDATA.append('regpassword',password);
        formDATA.append('regemail',email);
        formDATA.append('regdisplayname',displayname);
        $.ajax({
            url:'auth.php',
            type:'POST',
            chace:false,        // this is necessary for uploading files to SERVER
            contentType:false,  // also this 
            processData:false,  // also this 
            data:formDATA,
            beforeSend:()=>{
                $('.err-user').css('visibility','hidden');
                $('.err-email').css('visibility','hidden');
                $('.loading').css('visibility','visible');
            },
            success:(responce)=>{
                $('.loading').css('visibility','hidden');
                if(responce == 'ERR_DUP_USERNAME'){
                    $('.err-user').css('visibility','visible');
                }else if(responce == 'ERR_DUP_EMAIL'){
                    $('.err-email').css('visibility','visible');                    
                }else if(responce == 'OK'){
                    window.location.replace("index.php");
                }
            },
            error:(err)=>{
                alert("Error : ".err);
            }
        });
    }); 
    $('#profilepic-input').change((e)=>{
        let fileName = (e.target.files[0].name);
        $('#profilepic-div label ').text(fileName);
    });
});
</script>
</body>
</html>