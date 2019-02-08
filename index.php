<?php
session_start();
require_once 'functions.php';
$cookie_flag = false;
$session_flag = false;
if(!isset($_COOKIE['logged_in'],$_COOKIE['hash'])){
   $cookie_flag = true;}
if(!isset($_SESSION['logged_in'])){
   $session_flag =true;
}
if($session_flag && $cookie_flag){
   redirect_to('login.php');
}elseif(isset($_COOKIE['logged_in'],$_COOKIE['hash']) && $_COOKIE['logged_in'] == 'yes'){
   $cookie_info = check_cookie($_COOKIE['hash'],true);
   $cook_id = $cookie_info['id'];
   $user = get_user_info(null,$cook_id);
   set_session($user['username'], $user['profile_pic'], $user['display_name'], get_real_ip(),$user['id']);
}
if(isset($_GET['logout']) && $_GET['logout']==1){
   logout();
   redirect_to('login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Chat Room</title>
   <link rel="stylesheet" href="files/css/font-awesome.min.css">
   <link rel="stylesheet" href="files/css/bootstrap-4.1.2.min.css">
   <script src="files/js/jquery-3.1.1.js"></script>
   <link rel="stylesheet" href="files/css/style.css">
</head>
<body>
   <div class="container">
      <div class="header">
         <h1 class="site-name"><a href="index.php">چت روم</a></h1>
         <div class="header-widget">
            <span><?php echo $_SESSION['displayname'];?></span>
            <div class="profile-pic">
            <img src="<?php echo $_SESSION['profilepic'];?>" id="profile-pic" alt="<?php echo $_SESSION['displayname'];?>">
            <i class="fa fa-caret-left left"></i>
            </div>
            <ul>
               <li><a href="profile.php">پروفایل</a></li>
               <li><a href="?logout=1">خروج</a></li>
            </ul>
         </div>
      </div>
      <div class="clear"></div>
      

<div class="user-table"></div>

   </div>
   <script src="files/js/jquery-3.1.1.js"></script>
   <script>
   $(document).ready(()=>{
      $('.profile-pic').click((e)=>{
         if($('.header-widget i').hasClass('left')){
            $('.header-widget i').css('transform','rotate(-90deg)').addClass('down').removeClass('left');
            $('.header-widget ul').slideDown();
         }else{
            $('.header-widget i').css('transform','none').addClass('left').removeClass('down');
            $('.header-widget ul').slideUp();

         }
      });
      
      

      (()=>{
         $.ajax({
            url:'fetch_user.php',
            type:'POST',
            data:'',
            success:(data)=>{
               $('.user-table').html(data);
            }
         });
      })()

      
   });


   </script>
</body>
</html>
