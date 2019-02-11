<?php
session_start();
if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] != 'yse'){
    header("Location:login.php");
}
if(isset($_GET['logout']) && $_GET['logout']==1){
    logout();
    redirect_to('login.php');
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
   <script src="../files/js/jquery-3.1.1.js"></script>
   <link rel="stylesheet" href="../files/css/style.css">
</head>
<body style="text-align: right;">
    <div class="container-fluid">
        <div class="row">
            <div class="left-group col-xl-3 col-lg-4 col-md-5 col-sm-10 col-xs-12">

                <ul class="list-group">
                    <li class="list-group-item active">اطلاعات کاربری</li>
                    <li class="list-group-item">
                        <a href="edit-account/" id="edit-profile-link">مشخصات فردی</a>
                        <span id="edit-profile">مشخصات فردی</span>
                </li>
                    
                    <!-- IF UESR IS ADMIN HERE GONNA SHOW "  مدیریت  " -->
                    
                    <li class="list-group-item"><a href="?logout=1">خروج</a></li>
                </ul>

            </div>

        </div>
    </div>

    <script src="../files/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(()=>{

    });
    </script>
</body>
</html>