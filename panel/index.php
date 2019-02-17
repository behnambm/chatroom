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



// echo $_SESSION['logged_in'] ."<br>";
// echo $_SESSION['username'].'<br>';
// echo $_SESSION['user_id']."<br>";
// echo $_SESSION['profilepic']."<br>";
// echo $_SESSION['displayname']."<br>";
// echo $_SESSION['ip']."<br>";
// echo $_SESSION['login_details_id']."<br>";




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $_SESSION['username'];?></title>
    <link rel="stylesheet" href="../files/css/font-awesome.min.css">
   <link rel="stylesheet" href="../files/css/bootstrap-4.1.2.min.css">
   <link rel="stylesheet" href="../files/dist/cropper.css">
   <link rel="stylesheet" href="../files/css/style.css">
</head>
<body style="text-align: right;">
    <div class="container-fluid">
        <div class="row">

            <div class="left-group col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <ul class="list-group">
                    <li class="list-group-item head">
                        <img src="../<?php echo $_SESSION['profilepic'];?>" alt="<?php echo $_SESSION['displayname'];?>">
                        <span class="display-name"><?php echo $_SESSION['displayname'];?></span>
                        <span class="username">نام کاربری : <?php echo $_SESSION['username'];?></span>
                    </li>
                    <!-- IF UESR IS ADMIN HERE GONNA SHOW "  مدیریت  " -->
                    <li class="list-group-item" id="personal-details">مشخصات شخصی</li>
                    <li class="list-group-item" id="delete-account-link">حذف حساب کاربری</li>
                    <li class="list-group-item"><a href="?logout=1">خروج</a></li>
                </ul>
            </div>
            <div class="right-group personal-detail col-xl-9 col-lg-9 col-md-9">
                <ul class="list-group">
                    <li class="list-group-item active">ویرایش مشخصات فردی</li>
                    <li class="list-group-item">
                        <fieldset>
                            <legend>جزئیات حساب</legend>
                            <form action="" method="post" id="email-displayname-change">
                                <div class="form-group" style="margin-top:10px">
                                    <label for="up-displayname">نام نمایشی:</label><small id="display-change"><i class="fa fa-check"></i>تغییر نام با موفقیت انجام شد.</small>
                                    <input type="text" class="form-control" name="updisplayname" id="up-displayname" value="<?php echo $_SESSION['displayname'];?>">
                                    <small class="form-text text-muted">این فیلد نام کاربری نیست و فقط جهت نمایش در سایت است.</small>
                                </div>

                                <div class="form-group" style="margin-top:10px">
                                    <label for="up-email">ایمیل:</label><small id="email-change"><i class="fa fa-check"></i>تغییر ایمیل با موفقیت انجام شد.</small>
                                    <input type="email" class="form-control" name="upemail" id="up-email" value="<?php echo $_SESSION['email'];?>">
                                </div>
                                <button class="btn btn-primary">بروز رسانی</button>
                                <small id="all-change"><i class="fa fa-check"></i>تغییرات با موفقیت انجام شد.</small>
                            </form>
                            <form action="" method="post" id="profilepic-change" enctype="multipart/form-data">
                                <label class="label panel-profile-pic" data-toggle="tooltip">
                                    <img class="rounded" id="avatar" src="../files/images/user.png" alt="avatar">
                                    <input type="file" class="sr-only" id="input" name="image" accept="image/*">
                                </label>
                                <label for="input" id="panel-holder-profilepic">
                                    تغییر عکس پروفایل
                                </label>
                                <button class="btn btn-primary" id="profile-pic-change-btn">تغییر</button>
                            </form><div class="clear"></div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>

                        </fieldset>
                    </li>
                </ul>
            </div>
            <div class="right-group delete-account col-xl-9 col-lg-9 col-md-9">
                <ul class="list-group">
                    <li class="list-group-item active">حذف حساب کاربری</li>
                    <li class="list-group-item">
                    adasd</li>
                </ul>
            </div> 
        </div>




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






<script src="../files/js/jquery-3.1.1.js"></script>
<script src="../files/js/bootstrap.bundle.min.js"></script>
<script src="../files/dist/cropper.js"></script>


    <script type="text/javascript">


        var formDATA = new FormData();
        let changeState = false;
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
                    width: 450,
                    height: 450,
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
    

        $(document).ready(()=>{

            $('#delete-account-link').click((e)=>{
                $('.personal-detail').css('display','none');
                $('.delete-account').fadeIn(700);
            });
            $('#personal-details').click((e)=>{
                $('.personal-detail').fadeIn(700);
                $('.delete-account').css('display','none');

            });

            $('#input').change((e)=>{
                changeState = true;
                console.log(changeState);
            });
            $('#profilepic-change').submit((e)=>{
                e.preventDefault();
                if(changeState){
                    $.ajax({
                        url:'change_profile_pic.php',
                        type:'POST',
                        data:formDATA,
                        processData: false,
                        contentType: false,
                        xhr:()=>{
                            let xhr = new XMLHttpRequest();
                            xhr.upload.addEventListener('progress',(e)=>{
                                if(e.lengthComputable){
                                    let percentComplete = Math.round( (e.loaded * 100) / e.total );
                                    $('.progress').css('display','block').delay(4000).fadeOut('slow');
                                    $(".progress-bar").css('width',percentComplete+'%');
                                    $('.progress-bar').text(percentComplete+'%');
                                }
                            });
                            return xhr;
                        },
                        success:(responce)=>{
                            alert(responce);
                        }
                    });
                }

            });

            $('#email-displayname-change').submit((e)=>{
                e.preventDefault();
                let up_display_name = $('#up-displayname').val();
                let up_email = $('#up-email').val();
                $.ajax({
                    url:'update_email_displayname.php',
                    type:"POST",
                    data:{
                        up_display_name: up_display_name,
                        up_email: up_email
                    },
                    success:(responce)=>{
                        if(responce == 'OK_EMAIL'){
                            $('#up_email').val(up_email);
                            $('#email-change').fadeIn().delay(3000).fadeOut('slow');
                        }else if(responce == 'OK_DISPLAYNAME'){
                            $('#up_displayname').val(up_display_name);
                            $('#display-change').fadeIn().delay(3000).fadeOut('slow');
                        }else if(responce == 'OK_NAME_EMAIL'){
                            $('#all-change').fadeIn().delay(3000).fadeOut('slow');
                        }
                        
                    }
                });
            });
        });

    </script>
</body>
</html>