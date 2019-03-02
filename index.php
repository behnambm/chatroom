<?php
session_start();
require_once 'functions.php';
$cookie_flag = false;
$session_flag = false;
// check for logged_in and hash cookie
if(!isset($_COOKIE['logged_in'],$_COOKIE['hash'])){
   $cookie_flag = true;
}

// check for logged_in session
if(!isset($_SESSION['logged_in'])){
   $session_flag =true;
}

if($session_flag && $cookie_flag){
   redirect_to('login.php');
}elseif(isset($_COOKIE['logged_in'],$_COOKIE['hash']) && $_COOKIE['logged_in'] == 'yes'){
   $cookie_info = check_cookie($_COOKIE['hash'],true);
   $cook_id = $cookie_info['id'];
   $user = get_user_info(null,$cook_id);
   set_session($user['username'], $user['profile_pic'], $user['display_name'], get_real_ip(),$user['id'],$user['email']);
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
                  <img src="<?php echo $_SESSION['profilepic'];?>" id="profile-pic"
                     alt="<?php echo $_SESSION['displayname'];?>">
                  <i class="fa fa-caret-left left"></i>
               </div>
               <ul class="profile-ul">
                  <li><a href="panel/"><i class="fa fa-edit"></i>پروفایل</a></li>
                  <li><a href="?logout=1"><i class="fa fa-sign-out"></i>خروج</a></li>
               </ul>
         </div>
      </div>
      <div class="clear"></div>

      <div class="group-chat-div">
         <button class="btn btn-warning btn-sm" id="start-group-chat">چت گروهی<span
                  id="group-msg-count"></span></button>

      </div>
      <div class="user-table">
      </div>
      <div id="chat-box-container"></div>

      <div id="group-chat-dialog" title="چت گروهی">
         <div class="group-chat-history"></div>
         <div class="form-group">
               <textarea name="group-chat-message" id="group-chat-message" class="form-control"></textarea>
         </div>
         <div class="form-group">
               <button name="send_group_message" id="send-group-message" class="btn btn-info">ارسال</button>
         </div>
         <div class="form-group">

         </div>



      </div>



   </div>
   </div>
   <?php
ini_set('display_errors',1);

$ss = '#file::';
echo substr($ss,0,7);

   ?>
   <script src="files/js/jquery-3.1.1.js"></script>
   <script src="files/js/jquery-ui.js"></script>
   <script type="text/javascript">
   $(document).ready(() => {
      let interval = null;
      let gorupInterval = null;
      //----------------------------------------------------------------------------------------------------
      // this function is structure of single chat dialog
      function make_chat_box(user_name, user_id) {
         let boxContent = '<div class="chat-box" id="user-dialog-' + user_id + '" title="چت با : ' +
               user_name + '">';
         boxContent += '<div class="chat-history" id="chat-history-' + user_id + '" data-touserid="' +
               user_id + '"></div>';
         boxContent += '<div class="form-group">';
         boxContent += '<textarea name="chat-message-' + user_id + '" id="chat-message-' + user_id +
               '" class="form-control txtarea-box"></textarea> ';
         boxContent += '</div><div class="form-group" align="right">';
         boxContent += '<button id="' + user_id +
               '" name="send-chat" class="btn btn-info send-chat">ارسال</button>';
         boxContent += '</div></div>';
         $('#chat-box-container').html(boxContent);
      }
      //----------------------------------------------------------------------------------------------------
      // click event for << شروع چت >> button and this will open a dialog and send's ajax request for server to fetch chat history
      $(document).on('click', '.chat-btn', (e) => {
         let toUserName = $(e.target).data('tousername');
         let toUserId = $(e.target).data('touserid');
         make_chat_box(toUserName, toUserId);
         $("#user-dialog-" + toUserId).dialog({
               autoOpen: false,
               width: 350
         });
         let to_user_id = $(e.target).data('touserid');
         let message = $('#chat-message-' + to_user_id).val();
         $('#user-dialog-' + toUserId).dialog('open');

         interval = setInterval(() => {
               console.log('user Interval');
               $.ajax({
                  url: 'fetch_chat_history.php',
                  type: 'POST',
                  data: {
                     to_user_id: to_user_id
                  },
                  success: (responce) => {
                     $('#chat-history-' + to_user_id).html(responce);
                  }
               });

         }, 500);
      });
      //----------------------------------------------------------------------------------------------------
      // click event for close chat dialog 
      $(document).on('click', '.ui-dialog-titlebar-close', (e) => {
         let tmp = $(e.target).parents('.ui-dialog').attr('aria-describedby');
         if (tmp == 'group-chat-dialog') {
               clearInterval(groupInterval);
         } else {
               clearInterval(interval);
         }



      });
      //----------------------------------------------------------------------------------------------------
      $('.profile-pic').click((e) => {
         if ($('.header-widget i.fa-caret-left').hasClass('left')) {
               $('.header-widget i.fa-caret-left').css('transform', 'rotate(-90deg)').addClass('down')
                  .removeClass('left');
               $('.header-widget ul').slideDown();
         } else {
               $('.header-widget i.fa-caret-left').css('transform', 'none').addClass('left')
                  .removeClass('down');
               $('.header-widget ul').slideUp();
         }
      });
      //----------------------------------------------------------------------------------------------------
      // click event for << ارسال >> button on the chat box dialog
      $(document).on('click', '.send-chat', (e) => {
         let to_user_id = $(e.target).prop('id');
         let message = $('#chat-message-' + to_user_id).val();
         $('#chat-message-' + to_user_id).val('');
         if (message != '') {
               $.ajax({
                  url: 'insert_chat.php',
                  type: 'POST',
                  data: {
                     to_user_id: to_user_id,
                     chat_message: message
                  },
                  success: (responce) => {
                     $('#chat-history-' + to_user_id).html(responce);
                  }
               });
         }
      });
      //----------------------------------------------------------------------------------------------------
      // interval for refresh user's table list AND last activity
      setInterval(() => {
         fetchUser();
         updateActivity();
         $.ajax({
               url: 'group_chat.php',
               type: 'POST',
               data: {
                  action: 'msg_count'
               },
               success: (responce) => {
                  if (responce != 0) {
                     $('#group-msg-count').html(responce).fadeIn('fast');
                  } else {
                     $('#group-msg-count').css('display', 'none');
                  }
               }
         });
      }, 3000);
      $(document).on('focus', '.txtarea-box', (e) => {
         let is_type = 'yes';
         let to_user_id = $(e.target).attr('id').split('-');

         $.ajax({
               url: 'update_is_type_status.php',
               type: 'POST',
               data: {
                  is_type: is_type,
                  to_user_id: to_user_id[2]
               },
               success: () => {

               }
         });
      });

      $(document).on('blur', '.txtarea-box', (e) => {
         let is_type = 'no';
         let to_user_id = $(e.target).attr('id').split('-');

         $.ajax({
               url: 'update_is_type_status.php',
               type: 'POST',
               data: {
                  is_type: is_type,
                  to_user_id: to_user_id[2]
               },
               success: () => {

               }
         });
      });
      //----------------------------------------------------------------------------------------------------
      // fetch user's list from database
      fetchUser();

      function fetchUser() {
         $.ajax({
               url: 'fetch_user.php',
               type: 'POST',
               data: '',
               success: (data) => {
                  $('.user-table').html(data);
               }
         });
      }
      //----------------------------------------------------------------------------------------------------
      // update current user activity 
      function updateActivity() {
         $.ajax({
               url: 'update_last_activity.php',
               type: 'POST',
               success: () => {}
         });
      }

      //----------------------------------------------------------------------------------------------------
      $('#group-chat-dialog').dialog({
         autoOpen: false,
         width: 350
      });

      $('#start-group-chat').click((e) => {
         $('#group-chat-dialog').dialog('open');

      });

      $('#send-group-message').click((e) => {
         let chatMsg = $('#group-chat-message').val();
         let action = 'insert';
         $('#group-chat-message').val('');
         if (chatMsg != '') {
               $.ajax({
                  url: 'group_chat.php',
                  type: 'POST',
                  data: {
                     group_chat_message: chatMsg,
                     action: action
                  },
                  success: (responce) => {
                     $('.group-chat-history').html(responce);
                  }
               });
         }
      });


      $('#start-group-chat').click((e) => {
         groupInterval = setInterval(() => {
               
               $.ajax({
                  url: 'group_chat.php',
                  type: 'POST',
                  data: {
                     action: 'fetch'
                  },
                  success: (responce) => {

                     $('.group-chat-history').html(responce);
                  }
               });
         }, 500);

      });


   });
   </script>
</body>

</html>