<?php
session_start();
require_once '../functions.php';
if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != 'yse'){
        header("Location:../login.php");
}
if(isset($_GET['logout']) && $_GET['logout']==1){
        logout();
        redirect_to('../login.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo $_SESSION['username'];?></title>
        <link rel="icon" href="../files/images/tab-icon.png">
        <link rel="stylesheet" href="../files/css/font-awesome.min.css">
        <link rel="stylesheet" href="../files/css/bootstrap-4.1.2.min.css">
        <link rel="stylesheet" href="../files/dist/cropper.css">
        <link rel="stylesheet" href="../files/css/style.css">
</head>

<body style="text-align: right;">
        <div class="container-fluid">
                <div class="row">
                        <div class="header header-responsive">
                                <button id="btn-show-menu"><i class="fa fa-bars"></i></button>
                                <a href="?logout=1">خروج</a>
                                <a href="../index.php" id="go-back" style="color: #007bff">برگشت</a>
                        </div>
                        <div class="menu-holder">
                                <ul class="list-group">
                                        <li class="list-group-item" id="personal-li-link">مشخصات شخصی</li>
                                        <li class="list-group-item" id="delete-li-link">حذف حساب کاربری</li>
                                </ul>
                        </div>
                        <div class="left-group col-xl-3 col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <ul class="list-group">
                                        <li class="list-group-item head">
                                                <img src="../<?php echo $_SESSION['profilepic'];?>"
                                                alt="<?php echo $_SESSION['displayname'];?>">
                                                <span class="display-name"><?php echo $_SESSION['displayname'];?></span>
                                                <span class="username">نام کاربری : <?php echo $_SESSION['username'];?></span>
                                        </li>
                                        <!-- IF UESR IS ADMIN HERE GONNA SHOW "  مدیریت  " -->
                                        <li class="list-group-item" id="personal-details">مشخصات شخصی</li>
                                        <li class="list-group-item" id="delete-account-link">حذف حساب کاربری</li>
                                        <li class="list-group-item"><a href="?logout=1">خروج</a></li>
                                </ul>
                        </div>
                        <div class="right-group personal-detail col-xl-9 col-lg-9 col-md-9 col-sm-8 col-xs-12">
                                <ul class="list-group">
                                        <li class="list-group-item active"><img class="small-profile-pic-for-ul" src="../<?php echo $_SESSION['profilepic'];?>" style="width: 32px;border-radius: 20px;">  ویرایش مشخصات فردی<em><a href="../index.php" class="go-back-lg">برگشت   </a></em></li>
                                        <li class="list-group-item">
                                                <fieldset>
                                                        <legend>جزئیات حساب</legend>
                                                        <form action="" method="post" id="email-displayname-change">
                                                                <div class="form-group" style="margin-top:10px">
                                                                        <label for="up-displayname">نام نمایشی:</label><small id="display-change"><i
                                                                                class="fa fa-check"></i>تغییر نام با موفقیت انجام شد.</small>
                                                                                <input type="text" class="form-control" name="updisplayname" id="up-displayname"
                                                                                value="<?php echo $_SESSION['displayname'];?>">
                                                                                <small class="form-text text-muted">این فیلد نام کاربری نیست و فقط جهت نمایش در سایت
                                                                                        است.</small>
                                                                                </div>

                                                                                <div class="form-group" style="margin-top:10px">
                                                                                        <label for="up-email">ایمیل:</label><small id="email-change"><i
                                                                                                class="fa fa-check"></i>تغییر ایمیل با موفقیت انجام شد.</small>
                                                                                                <input type="email" class="form-control" name="upemail" id="up-email"
                                                                                                value="<?php echo $_SESSION['email'];?>">
                                                                                        </div>
                                                                                        <button class="btn btn-primary">بروز رسانی</button>
                                                                                        <small id="all-change"><i class="fa fa-check"></i>تغییرات با موفقیت انجام شد.</small>
                                                                                </form>
                                                                                <hr>
                                                                                <form action="" method="post" id="profilepic-change" enctype="multipart/form-data">
                                                                                        <label class="label panel-profile-pic" data-toggle="tooltip">
                                                                                                <img class="rounded" id="avatar" src="../files/images/user.png" alt="avatar">
                                                                                                <input type="file" class="sr-only" id="input" name="image" accept="image/*">
                                                                                        </label>
                                                                                        <label for="input" id="panel-holder-profilepic">
                                                                                                تغییر عکس پروفایل
                                                                                        </label>
                                                                                        <button class="btn btn-primary" id="profile-pic-change-btn">تغییر</button>
                                                                                </form>
                                                                                <div class="clear"></div>
                                                                                <div class="progress">
                                                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                                                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                                                                </div>
                                                                                <hr>
                                                                                <form action="" id="change-pass-form">
                                                                                        <div class="form-group">
                                                                                                <label for="old-password">رمز عبور قبلی :</label>
                                                                                                <input type="password" class="form-control" name="oldpassword" id="old-password">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                                <label for="new-password">رمز عبور جدید :</label>
                                                                                                <input type="password" class="form-control" name="newpassword" id="new-password">
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                                <label for="re-new-password">تکرار رمز عبور :</label>
                                                                                                <input type="password" class="form-control" name="renewpassword"
                                                                                                id="re-new-password">
                                                                                        </div>
                                                                                        <button class="btn btn-success" id="change-pass-btn">تایید</button>
                                                                                        <small id="change-pass-alert"></small>
                                                                                </form>

                                                                        </fieldset>
                                                                </li>
                                                        </ul>
                                                </div>
                                                <div class="right-group delete-account col-xl-9 col-lg-9 col-md-9 col-sm-8">
                                                        <ul class="list-group">
                                                                <li class="list-group-item active"><img class="small-profile-pic-for-ul" src="../<?php echo $_SESSION['profilepic'];?>" style="width: 32px;border-radius: 20px;">  حذف حساب کاربری<em><a href="../index.php" class="go-back-lg">برگشت   </a></em></li>
                                                                <li class="list-group-item">
                                                                        <em id="delete-account-msg"><strong>توجه : </strong>باحذف حساب کاربری تمام اطلاعات شما از بین خواهد رفت.</em>
                                                                        <div class="form-group"> <br>
                                                                                <form action="">
                                                                                        <input type="password" id="delete-account-inp" placeholder="رمز حساب کاربری" name="deleteaccount" class="form-control col-xl-4 col-lg-5 col-md-6 col-sm-8 col-xs-9" >
                                                                                        <button id="delete-account-btn" class="btn btn-danger">حذف حساب</button>
                                                                                </form>
                                                                        </div>
                                                                        <div class="no-pass-enter">لطفا رمز را وارد کنید.</div>
                                                                        <div class="incorrect-pass">رمز وارد شده اشتباه است.</div>
                                                                </li>
                                                        </ul>

                                                </div>
                                        </div>




                                </div>







                                <!-- modal for delete account -->
                                <div class="modal-bg">
                                        <ul class="list-group">
                                                <li class="list-group-item">آیا مطمئن هستید که میخواهید حساب خود را حذف کنید؟</li>
                                                <li class="list-group-item accept"><a href="javascript:;" id="accept-delete">تایید</a></li>
                                                <li class="list-group-item"><a href="javascript:;" id="deny-delete">لغو</a></li>

                                        </ul>
                                </div>



                                <!-- cropper  -->

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
                                                                        <img id="image" src="../files/images/user.png">
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">لغو</button>
                                                                <button type="button" class="btn btn-primary" id="crop">برش</button>
                                                        </div>
                                                </div>
                                        </div>
                                </div>

                                <!-- cropper -->




                                <div class="res"></div>

                                <script src="../files/js/jquery-3.1.1.js"></script>
                                <script src="../files/js/bootstrap.bundle.min.js"></script>
                                <script src="../files/dist/cropper.js"></script>


                                <script type="text/javascript">
                                var formDATA = new FormData();
                                let changeState = false;
                                window.addEventListener('DOMContentLoaded', function() {
                                        var avatar = document.getElementById('avatar');
                                        var image = document.getElementById('image');
                                        var input = document.getElementById('input');
                                        var $modal = $('#modal');
                                        var cropper;
                                        $('[data-toggle="tooltip"]').tooltip();
                                        input.addEventListener('change', function(e) {
                                                var files = e.target.files;
                                                var done = function(url) {
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
                                                                reader.onload = function(e) {
                                                                        done(reader.result);
                                                                };
                                                                reader.readAsDataURL(file);
                                                        }
                                                }
                                        });
                                        $modal.on('shown.bs.modal', function() {
                                                cropper = new Cropper(image, {
                                                        aspectRatio: 1,
                                                        viewMode: 3,
                                                });
                                        }).on('hidden.bs.modal', function() {
                                                cropper.destroy();
                                                cropper = null;
                                        });
                                        document.getElementById('crop').addEventListener('click', function() {
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
                                                        canvas.toBlob(function(blob) {
                                                                var formData = new FormData();
                                                                formData.append('avatar', blob, 'avatar.png');
                                                                formDATA.append('avatar', blob, 'avatar.png');
                                                        });
                                                }
                                        });
                                });


                                $(document).ready(() => {
                                        function checkWidth() {
                                                if ($(window).width() <= 576) {
                                                        $('.left-group').hide();
                                                        $('.header').show();
                                                } else {
                                                        $('.left-group').show();
                                                        $('.header').hide();
                                                }
                                        }

                                        $('#btn-show-menu').click((e) => {
                                                if ($('.menu-holder').hasClass('open')) {
                                                        $('#btn-show-menu').css('color', '#000');
                                                        $('#btn-show-menu').css('background-color', '#fff');
                                                        $('.menu-holder').slideUp('slow').removeClass('open');

                                                } else {
                                                        $('.menu-holder').slideDown('slow').addClass('open');
                                                        $('#btn-show-menu').css('background-color', '#007bff');
                                                        $('#btn-show-menu').css('color', '#fff');
                                                }
                                        });
                                        $('#personal-li-link').click((e) => {
                                                $('.personal-detail').fadeIn(700);
                                                $('.delete-account').hide(10);
                                                $('.menu-holder').slideUp('fast').removeClass('open');
                                                $('#btn-show-menu').css('color', '#000');
                                                $('#btn-show-menu').css('background-color', '#fff');
                                        });
                                        $('#delete-li-link').click((e) => {
                                                $('.personal-detail').hide(10);
                                                $('.delete-account').fadeIn(700);
                                                $('.menu-holder').slideUp('fast').removeClass('open');
                                                $('#btn-show-menu').css('color', '#000');
                                                $('#btn-show-menu').css('background-color', '#fff');
                                        });

                                        checkWidth();
                                        $(window).resize(() => {
                                                checkWidth();
                                        });
                                        $('#delete-account-link').click((e) => {
                                                $('.personal-detail').css('display', 'none');
                                                $('.delete-account').fadeIn(700);
                                        });
                                        $('#personal-details').click((e) => {
                                                $('.personal-detail').fadeIn(700);
                                                $('.delete-account').css('display', 'none');

                                        });

                                        // update last activity
                                        setInterval(function updateActivity() {
                                                $.ajax({
                                                        url: '../update_last_activity.php',
                                                        type: 'POST',
                                                        success: () => {}
                                                });
                                        }, 3000);


                                        $('#input').change((e) => {
                                                changeState = true;
                                                console.log(changeState);
                                        });
                                        $('#profilepic-change').submit((e) => {
                                                e.preventDefault();
                                                if (changeState) {
                                                        $.ajax({
                                                                url: 'change_profile_pic.php',
                                                                type: 'POST',
                                                                data: formDATA,
                                                                processData: false,
                                                                contentType: false,
                                                                xhr: () => {
                                                                        let xhr = new XMLHttpRequest();
                                                                        xhr.upload.addEventListener('progress', (e) => {
                                                                                if (e.lengthComputable) {
                                                                                        let percentComplete = Math.round((e.loaded * 100) /
                                                                                        e.total);
                                                                                        $('.progress').css('display', 'block').delay(2000)
                                                                                        .fadeOut('slow');
                                                                                        $(".progress-bar").css('width', percentComplete +
                                                                                        '%');
                                                                                        $('.progress-bar').text(percentComplete + '%');
                                                                                }
                                                                        });
                                                                        return xhr;
                                                                },
                                                                success: (responce) => {

                                                                }
                                                        });
                                                }

                                        });

                                        $('#email-displayname-change').submit((e) => {
                                                e.preventDefault();
                                                let up_display_name = $('#up-displayname').val();
                                                let up_email = $('#up-email').val();
                                                $.ajax({
                                                        url: 'update_email_displayname.php',
                                                        type: "POST",
                                                        data: {
                                                                up_display_name: up_display_name,
                                                                up_email: up_email
                                                        },
                                                        success: (responce) => {
                                                                if (responce == 'OK_EMAIL') {
                                                                        $('#up_email').val(up_email);
                                                                        $('#email-change').fadeIn().delay(3000).fadeOut('slow');
                                                                } else if (responce == 'OK_DISPLAYNAME') {
                                                                        $('#up_displayname').val(up_display_name);
                                                                        $('#display-change').fadeIn().delay(3000).fadeOut('slow');
                                                                } else if (responce == 'OK_NAME_EMAIL') {
                                                                        $('#all-change').fadeIn().delay(3000).fadeOut('slow');
                                                                }

                                                        }
                                                });
                                        });

                                        $('#change-pass-form').submit((e) => {
                                                e.preventDefault();
                                                let oldPass = $('#old-password').val();
                                                let newPass = $('#new-password').val();
                                                let reNewPass = $('#re-new-password').val();

                                                $.ajax({
                                                        url: 'change_password.php',
                                                        type: 'POST',
                                                        data: {
                                                                old_password: oldPass,
                                                                new_password: newPass,
                                                                re_new_password: reNewPass
                                                        },
                                                        success: (responce) => {
                                                                if (responce == 'OK') {
                                                                        $('#change-pass-alert').html(
                                                                                '<i class="fa fa-check"></i>رمز شما با موفقیت تغییر کرد.')
                                                                                .fadeIn().addClass('success').removeClass('danger', 'warning')
                                                                                .delay(2000).fadeOut('slow');
                                                                        } else if (responce == 'ERR_DATA_NOT_EQUAL') {
                                                                                $('#change-pass-alert').html(
                                                                                        '<i class="fa fa-times"></i>رمز جدید با تکرار مطابقت ندارد.'
                                                                                ).fadeIn().addClass('danger').removeClass('warning',
                                                                                'success').delay(2000).fadeOut('slow');
                                                                        } else if (responce == 'ERR_OLD_PASS') {
                                                                                $('#change-pass-alert').html(
                                                                                        '<i class="fa fa-times"></i>رمز قبلی شما درست نمی باشد.')
                                                                                        .fadeIn().addClass('danger').removeClass('warning', 'success')
                                                                                        .delay(2000).fadeOut('slow');
                                                                                } else if (responce == 'ERR_NO_DATA_SENT') {
                                                                                        $('#change-pass-alert').html(
                                                                                                '<i class="fa fa-exclamation-triangle"></i>لطفا فیلد ها را پر کنید.'
                                                                                        ).fadeIn().addClass('warning').removeClass('danger',
                                                                                        'success').delay(2000).fadeOut('slow');
                                                                                } else if (responce == 'ERR_OLD_EQUAL_WITH_NEW') {
                                                                                        $('#change-pass-alert').html(
                                                                                                '<i class="fa fa-exclamation-triangle"></i>لطفا یک رمز جدید انتخاب کنید.'
                                                                                        ).fadeIn().addClass('warning').removeClass('danger',
                                                                                        'success').delay(2000).fadeOut('slow');
                                                                                }
                                                                        }
                                                                });
                                                        });
                                                        // click on حذف btn       delete account SECTION
                                                        $('#delete-account-btn').click((e)=>{
                                                                e.preventDefault();
                                                                let userData = $('#delete-account-inp').val();
                                                                if(userData == ''){
                                                                        $('.no-pass-enter').css('display','block');
                                                                        $('.incorrect-pass').css('display','none');

                                                                }else{
                                                                        $('.no-pass-enter').css('display','none');
                                                                        $('.incorrect-pass').css('display','none');
                                                                        $.ajax({
                                                                                url:'delete_account.php',
                                                                                type:'POST',
                                                                                data:{
                                                                                        password_for_del:userData
                                                                                },
                                                                                success:(responce)=>{
                                                                                        if(responce == 'PASS_OK'){
                                                                                                $('.modal-bg').show();
                                                                                        }else if(responce == 'PASS_INCORRECT'){
                                                                                                $('.incorrect-pass').css('display','block');
                                                                                        }
                                                                                }
                                                                        });
                                                                }
                                                        });


                                                        // click on  تایید      delete account SECTION
                                                        $('#accept-delete').click((e)=>{
                                                                let userData = $('#delete-account-inp').val();

                                                                $.ajax({
                                                                        url:'delete_account.php',
                                                                        type:'POST',
                                                                        data:{
                                                                                user_confirm:'YES',
                                                                                password_for_del:userData
                                                                        },
                                                                        success:(responce)=>{
                                                                                $('.res').html(responce); // for redirect => putting JS code in html doc
                                                                        }
                                                                });
                                                        });
                                                        // click on deny btn   delete account SECTION
                                                        $('#deny-delete').click((e)=>{
                                                                $('.modal-bg').hide();
                                                                $('#delete-account-inp').val('');
                                                        });


                                                });// ready {}
                                                </script>
                                        </body>

                                        </html>
