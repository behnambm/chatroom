<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>
    <link rel="stylesheet" href="files/css/font-awesome.min.css">
    <link rel="stylesheet" href="files/css/bootstrap.min.css">
    <script src="files/js/jquery-3.1.1.js"></script>
    <link rel="stylesheet" href="files/css/style.css">
</head>
<body>
    <div class="container">
        <div class="wrapper reg-wrapper">
            <form action="" method="post" id="register-form">
                <span id="register-img"><i class="fa fa-user-plus"></i><h1>ثبت نام</h1></span>
                <div class="clear"></div>

                <div class="input-div" id="user-div">
                    <span id="icon-holder-user">
                        <i class="fa fa-user"></i>
                    </span>
                    <input type="text" name="regusername" placeholder="نام کاربری " id="username-input" required >
                </div><div class="clear"></div>


                <div class="input-div" id="displayname-div">
                    <span id="icon-holder-displayname">
                        <i class="fa fa-eye"></i>
                    </span>
                    <input type="text" name="regdisplayname" placeholder="نام (جهت نمایش)" id="displayname-input" required >
                </div><div class="clear"></div>


                <div class="input-div" id="email-div">
                    <span id="icon-holder-email">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <input type="email" name="regemail" placeholder="ایمیل" id="email-input" required >
                </div><div class="clear"></div>

                
                <div class="input-div" id="pass-div">
                    <span id="icon-holder-pass">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" name="regpassword" placeholder="رمز عبور " id="password-input" required >
                </div><div class="clear"></div>



                <button type="submit" class="reg-btn" id="sub-btn">ثبت نام</button>
            </form><div class="clear"></div>
            <div class="line">
                <div class="inner-line"></div>
            </div>

            <a href="login.php" id="login">ورود به حساب کاربری</a>

        </div>
    </div>
    <script src="files/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(()=>{
            $('#register-form input').focus((e)=>{
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
            // codes for ajax request
            $('#register-form').submit((e)=>{
                e.preventDefault();
                let username = $('#username-input').val();
                let password = $('#password-input').val();
                let email = $('#email-input').val();
                let displayname = $('#displayname-input').val();
                $.ajax({
                    url:'auth.php',
                    type:'POST',
                    data:{regusername:username,regpassword:password,regemail:email,regdisplayname:displayname},
                    success:(responce)=>{
                        if(responce == 'ERR_DUP_USERNAME'){
                            alert('این نام کاربری قبلا ثبت شده است.');
                        }else if(responce == 'ERR_DUP_EMAIL'){
                            alert('این ایمیل قبلا ثبت شده است.');
                        }
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