<?php session_start();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link rel="stylesheet" href="files/css/font-awesome.min.css">
    <link rel="stylesheet" href="files/css/bootstrap.min.css">
    <script src="files/js/jquery-3.1.1.js"></script>
    <link rel="stylesheet" href="files/css/style.css">
</head>
<body>
    <div class="container" style="background-color:#e6e6e6;width:100%;height:100%;position:fixed">
        <div class="wrapper">
            <form action="" method="post" id="login-form">
                <span id="login-img"><i class="fa fa-user-circle"></i><h1>ورود</h1></span>
                <div class="clear"></div>
                <div class="input-div" id="user-div">
                    <span id="icon-holder-user">
                        <i class="fa fa-user"></i>
                    </span>
                    <input type="text" name="logusername" placeholder="نام کاربری " id="username-input">
                </div><div class="clear"></div>
                <div class="input-div" id="pass-div">
                    <span id="icon-holder-pass">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" name="logpassword" placeholder="رمز عبور " id="password-input">
                </div><div class="clear"></div>
                <div class="remember">
                    <input type="checkbox" name="rememberme" id="remember-me">
                    <label for="remember-me">مرا به خاطر بسپار</label>
                </div><div class="clear"></div>
                <button type="submit" id="sub-btn">ورود</button>
            </form>
        </div>
    </div>
    <script src="files/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(()=>{
            $('#login-form input').focus((e)=>{
                let id = $(e.target).attr('id');
                if(id == 'username-input'){
                    $('#icon-holder-user').css('top','-25px');
                    $('#user-div').addClass('opacity-fill');
                }else if(id == 'password-input'){
                    $('#icon-holder-pass').css('top','-25px');
                    $('#pass-div').addClass('opacity-fill');      
                }
            });
            $('#login-form input').focusout((e)=>{
                let id = $(e.target).attr('id');
                if(id == 'username-input'){
                    $('#icon-holder-user').css('top','0');
                    $('#user-div').removeClass('opacity-fill'); 
                }else if(id == 'password-input'){
                    $('#icon-holder-pass').css('top','0');
                    $('#pass-div').removeClass('opacity-fill');        
                }
            });

            // codes for ajax request
            $('#login-form').submit((e)=>{
                e.preventDefault();
                let username = $('#username-input').val();
                let password = $('#password-input').val();
                
                $.ajax({
                    url:'auth.php',
                    type:'POST',
                    data:{username:username,password:password},
                    success:(responce)=>{
                        alert(responce);
                    },
                    error:(err)=>{
                        alert("Error : ".err);
                    }
                });
            });


        });



    </script>
</body>
</html>