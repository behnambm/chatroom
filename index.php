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
   <link rel="stylesheet" href="files/css/jquery-ui.css">
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
               <li><a href="panel/">پروفایل</a></li>
               <li><a href="?logout=1">خروج</a></li>
               
            </ul>
         </div>
      </div>
      <div class="clear"></div>
      

<div class="user-table"></div>
<div id="chat-box-container"></div>




      </div>
   </div>
   <script src="files/js/jquery-3.1.1.js"></script>
   <script src="files/js/jquery-ui.js"></script>

   <script>

   $(document).ready(()=>{

   let interval = null;
   function make_chat_box(user_name,user_id){

      let boxContent = '<div class="chat-box" id="user-dialog-'+user_id+'" title="چت با : '+user_name+'">';
      boxContent += '<div class="chat-history" id="chat-history-'+user_id+'" data-touserid="'+user_id+'"></div>';
      boxContent += '<div class="form-group">';
      boxContent += '<textarea name="chat-message-'+user_id+'" id="chat-message-'+user_id+'" class="form-control"></textarea> ';
      boxContent +='</div><div class="form-group" align="right">';
      boxContent +='<button id="'+user_id+'" name="send-chat" class="btn btn-info send-chat">ارسال</button>';
      boxContent +='</div></div>';
      $('#chat-box-container').html(boxContent);
   }
      $(document).on('click','.chat-btn',(e)=>{

         let toUserName = $(e.target).data('tousername');
         let toUserId = $(e.target).data('touserid');
         make_chat_box(toUserName, toUserId);
         $("#user-dialog-"+toUserId).dialog({
            autoOpen:false,
            width:350
         });
         let to_user_id  = $(e.target).data('touserid');
         let message = $('#chat-message-'+to_user_id).val();
         $('#user-dialog-'+toUserId).dialog('open');
         console.log(to_user_id);
         interval = setInterval(() => {
            $.ajax({
               url:'fetch_chat_history.php',
               type:'POST',
               data:{
                  to_user_id:to_user_id
               },
               success:(responce)=>{
                  $('#chat-history-'+to_user_id).html(responce);
               }
            });
            console.log('interval');
         }, 1000);

      });

      $(document).on('click','.ui-dialog-titlebar-close' , (e)=>{
         clearInterval(interval);
         console.log('clear Interval');
      });



      $('.profile-pic').click((e)=>{
         if($('.header-widget i').hasClass('left')){
            $('.header-widget i').css('transform','rotate(-90deg)').addClass('down').removeClass('left');
            $('.header-widget ul').slideDown();
         }else{
            $('.header-widget i').css('transform','none').addClass('left').removeClass('down');
            $('.header-widget ul').slideUp();

         }
      });
      $(document).on('click','.send-chat',(e)=>{
         let to_user_id  = $(e.target).prop('id');
         let message = $('#chat-message-'+to_user_id).val();
         console.log(to_user_id +'------'+message);
         $('#chat-message-'+to_user_id).val('');

         if(message != ''){
            $.ajax({
               url:'insert_chat.php',
               type:'POST',
               data:{
                  to_user_id:to_user_id,
                  chat_message:message
               },
               success:(responce)=>{
                  $('#chat-history-'+to_user_id).html(responce);
               }
            });
         }

      });
      setInterval(() => {
         fetchUser();
         updateActivity();
      }, 5000);
      fetchUser();

      function fetchUser() {
         $.ajax({
            url:'fetch_user.php',
            type:'POST',
            data:'',
            success:(data)=>{
               $('.user-table').html(data);
            }
         });
      } 

      function updateActivity(){
         $.ajax({
            url:'update_last_activity.php',
            type:'POST',
            success:()=>{
            }
         });
      }


      
   });


   </script>
</body>
</html>
