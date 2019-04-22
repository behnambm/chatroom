<?php
session_start();
require 'functions.php';
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
        echo '<script>window.location = "login.php";</script>';
}elseif(isset($_COOKIE['logged_in'],$_COOKIE['hash']) && $_COOKIE['logged_in'] == 'yes'){
        $cookie_info = check_cookie($_COOKIE['hash'],true);
        $cook_id = $cookie_info['id'];
        $user = get_user_info(null,$cook_id);
        set_session($user['username'], $user['profile_pic'], $user['display_name'], get_real_ip(),$user['id'],$user['email']);
}

if(isset($_GET['logout']) && $_GET['logout']==1){
        logout();
        echo '<script>window.location = "login.php";</script>';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Chat Room</title>
        <link rel="icon" href="files/images/tab-icon.png">
        <link rel="stylesheet" href="files/css/font-awesome.min.css">
        <link rel="stylesheet" href="files/css/bootstrap-4.1.2.min.css">
        <link rel="stylesheet" href="files/css/jquery-ui.css">
        <link rel="stylesheet" href="files/css/style.css">
</head>

<body>
        <div class="container">
                <div class="header">
                        <img src="files/images/chat.png" alt="چت روم" id="site-header-img">
                        <ul class="profile-ul">
                                <?php
                                if(isset($_SESSION['privilage'])){
                                        if($_SESSION['privilage'] == 'owner'){
                                                echo '<a href="administration/"><li style="padding: 13px 5px;"><i class="fa fa-dashboard"></i>مدیریت</li></a>';
                                        }
                                }
                                ?>

                                <a href="panel/">
                                        <li>
                                                <img src="<?php echo $_SESSION['profilepic'];?>" id="profile-pic"
                                                alt="<?php echo $_SESSION['displayname'];?>">
                                                <?php echo $_SESSION['displayname'];?>
                                        </li>
                                </a>

                                <a href="?logout=1"><li style="padding: 13px 5px;"><i class="fa fa-sign-out"></i>خروج</li></a>
                        </ul>
                        <div class="clear"></div>
                </div>


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
                        </div>
                </div>
        </div>

        <!-- modal for delete account -->
        <div class="modal-bg" id="modal-1">
                <ul class="list-group">
                        <li class="list-group-item">آیا مطمئن هستید که میخواهید این کاربر را حذف کنید ؟</li>
                        <li class="list-group-item accept"><a href="javascript:;" id="accept-delete">تایید</a></li>
                        <li class="list-group-item"><a href="javascript:;" id="deny-delete">لغو</a></li>
                </ul>
        </div>
        <div class="modal-bg" id="modal-2">
                <ul class="list-group">
                        <li class="list-group-item"><a href="javascript:;" class="edit-msg">ویرایش</a></li>
                        <li class="list-group-item"><a href="javascript:;" id="deny-options">لغو</a></li>
                </ul>
        </div>

        <div class="kick-alert">
        </div>

        <script src="files/js/jquery-3.1.1.js"></script>
        <script src="files/js/jquery-ui.js"></script>
        <script type="text/javascript">

        // PUSH Notification Initialize

        Notification.requestPermission(function (status) {
                navigator.serviceWorker.register('files/js/sw.js');
        });





        //PUSH Notification Function

        function displayNotification(username, count) {
                if (Notification.permission == 'granted') {
                        navigator.serviceWorker.getRegistration().then(function (reg) {
                                var icon_check = '';
                                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                                        icon_check = 'files/images/tab-icon.png';
                                }
                                var options = {
                                        icon: icon_check,
                                        vibrate: [100, 50, 100], // vibrate - pause - vibrate
                                };
                                reg.showNotification('شما '+count+' پیام جدید از  '+username + ' دارید.', options);

                        });
                }
        }



        // PUSH Notification Initialize

        $(document).ready(() => {
                let interval = null;
                let gorupInterval = null;
                //----------------------------------------------------------------------------------------------------
                // this function is structure of single chat dialog
                function make_chat_box(user_name, user_id) {      // this code will make dynamic chat box for each user
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
                $(document).on('click', '.chat-btn', (e) => { // user dialog setting
                        let toUserName = $(e.target).data('tousername');
                        let toUserId = $(e.target).data('touserid');
                        make_chat_box(toUserName, toUserId);

                        if($(document).width() < 350 ){
                                $("#user-dialog-" + toUserId).dialog({
                                        autoOpen: false,
                                        width: $(document).width(),
                                        draggable: true
                                });
                        }else{
                                $("#user-dialog-" + toUserId).dialog({
                                        autoOpen: false,
                                        width: 350,
                                        draggable: true
                                });
                        }

                        let to_user_id = $(e.target).data('touserid');
                        let message = $('#chat-message-' + to_user_id).val();
                        $('#user-dialog-' + toUserId).dialog('open');

                        interval = setInterval(() => {
                                // console.log('user Interval');
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

                        }, 1000);
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
                // click event for << ارسال >> button on the chat box dialog
                $(document).on('click', '.send-chat', (e) => {
                        let to_user_id = $(e.target).prop('id');
                        let message = $('#chat-message-' + to_user_id).val();
                        $('#chat-message-' + to_user_id).val('');
                        if (message != '') {
                                if($('#chat-message-'+to_user_id).hasClass('editing')){
                                        $.ajax({
                                                url: 'msg_options.php',
                                                type: 'POST',
                                                data:{
                                                        msg_id:msgId,
                                                        action:'edit-write',
                                                        message:message,
                                                },
                                                success: (responce) => {
                                                        // console.log(responce);
                                                }
                                        });
                                }else{
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
                        }
                });
                //----------------------------------------------------------------------------------------------------
                // Event for pressing    Ctrl+Enter    in  textarea
                $(document).on('click','.chat-btn',(e)=>{
                        var userId = $(e.target).data('touserid');
                        $('#chat-message-'+userId).blur();
                        $('#chat-message-'+userId).focus(()=>{
                                $(document).on('keydown',(e)=>{
                                        if(e.ctrlKey && e.which == 13){
                                                var msg = $('#chat-message-'+userId).val();
                                                $('#chat-message-'+userId).val('');
                                                if (msg != '') {
                                                        if($('#chat-message-'+userId).hasClass('editing')){
                                                                $.ajax({
                                                                        url: 'msg_options.php',
                                                                        type: 'POST',
                                                                        data:{
                                                                                msg_id:msgId,
                                                                                action:'edit-write',
                                                                                message:msg,
                                                                        },
                                                                        success: (responce) => {
                                                                                // console.log(responce);
                                                                        }
                                                                });
                                                        }else{
                                                                $.ajax({
                                                                        url: 'insert_chat.php',
                                                                        type: 'POST',
                                                                        data: {
                                                                                to_user_id: userId,
                                                                                chat_message: msg
                                                                        },
                                                                        success: (responce) => {
                                                                                $('#chat-history-' + userId).html(responce);
                                                                        }
                                                                });
                                                        }
                                                }
                                        }
                                });
                        });
                });

                //----------------------------------------------------------------------------------------------------
                // function for fetching msg count for group
                function getGroupMsgCount(){
                        $.ajax({
                                url: 'group_chat.php',
                                type: 'POST',
                                data: {
                                        action: 'msg_count'
                                },
                                success: (responce) => {
                                        if (responce != 0 ) {
                                                if(group_msg_count < responce){
                                                        if(group_msg_count == 0 && responce == 1)
                                                        count = 1;
                                                        else
                                                        count = responce - group_msg_count;
                                                        if(document.visibilityState == 'hidden'){
                                                                displayNotification(' گروه ', count );
                                                        }
                                                        group_msg_count = responce;
                                                }
                                                $('#group-msg-count').html(responce).fadeIn('fast');
                                        } else {
                                                $('#group-msg-count').css('display', 'none');
                                        }
                                }
                        });
                }
                getGroupMsgCount();
                //----------------------------------------------------------------------------------------------------
                // interval for refresh user's table list AND last activity
                var group_msg_count = 0;
                setInterval(() => {
                        fetchUser();
                        updateActivity();
                        getGroupMsgCount();
                }, 4000);
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
                                success: (responce) => {
                                        if(responce != ''){
                                                // console.log(responce);
                                        }
                                }
                        });
                });

                $(document).on('blur', '.txtarea-box', (e) => { // this code is for changing status of 'Is Typeing'
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
        var notified = false;
        var count_tmp = {};
        var msgObject = {};
        fetchUser();
        function fetchUser() {
                $.ajax({
                        url: 'fetch_user.php',
                        type: 'POST',
                        data: '',
                        success: (data) => {
                                $('.user-table').html(data);
                                if($('.unseen-chat').length){ // for checking this element is exists or not
                                        $('.unseen-chat').each((key,value)=>{ // a for loop for each element with this class
                                                var username = $(value).parents('.chat-btn').data('tousername');
                                                var count = $(value).data('msgcount');
                                                if(count_tmp[username] == undefined){
                                                        count_tmp[username] = 0;
                                                }else{
                                                        count_tmp[username] = msgObject[username];
                                                }
                                                msgObject[username] = count;
                                                if(count_tmp[username] < msgObject[username]){
                                                        if(document.visibilityState == 'hidden'){
                                                                displayNotification(username, count-count_tmp[username]);
                                                        }
                                                }
                                        });
                                }
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
        if($(document).width() < 350 ){  //  group dialog setting
                $('#group-chat-dialog').dialog({
                        autoOpen: false,
                        width: $(document).width(),
                        draggable: true
                });
        }else{
                $('#group-chat-dialog').dialog({
                        autoOpen: false,
                        width: 350,
                        draggable: true
                });
        }

        $(window).resize(()=>{  // setting dialog box width on window resize
                if($(document).width() < 350 ){  //  group dialog box
                        $('#group-chat-dialog').dialog({
                                autoOpen: false,
                                width: $(document).width(),
                                draggable: true
                        });
                }else{
                        $('#group-chat-dialog').dialog({
                                autoOpen: false,
                                width: 350,
                                draggable: true
                        });
                }

                if($(document).width() < 350 ){  // user dialog box
                        $('.chat-box').dialog({
                                autoOpen: false,
                                width: $(document).width(),
                                draggable: true
                        });
                }else{
                        $('.chat-box').dialog({
                                autoOpen: false,
                                width: 350,
                                draggable: true
                        });
                }
        }); // end of resize

        $('#start-group-chat').click((e)=>{
                $('#group-chat-dialog').dialog('open');
        });

        $('#send-group-message').click((e)=>{
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
        //----------------------------------------------------------------------------------------------------
        // Event for pressing    Ctrl+Enter    in  textarea
        $('#group-chat-message').focus((e)=>{
                $(document).on('keydown',(e)=>{
                        if(e.ctrlKey && e.which == 13 ){
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
                        }
                });
        });

        //----------------------------------------------------------------------------------------------------
        $('#start-group-chat').click((e)=>{
                groupInterval = setInterval(()=>{
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
                }, 1000);

        });

        // ajax for delete a uesr
        let userNameForKick = null;
        let userIdForKick = null;
        $(document).on('click', '.del-uesr-link',(e)=>{
                userNameForKick = $(e.target).data('username');
                userIdForKick = $(e.target).data('id');
                $.ajax({
                        url:'kick_user.php',
                        type:'POST',
                        data:{
                                username:userNameForKick,
                                id:userIdForKick
                        },
                        success:(responce)=>{
                                if(responce == 'OK'){
                                        $('#modal-1').css('display','block');
                                }
                        }
                });
        });

        // cancel user  delete
        $('#deny-delete').click((e)=>{
                $('#modal-1').hide();
        });

        // confirm user delete
        $('#accept-delete').click((e)=>{
                if(userNameForKick != null && userIdForKick != null){
                        $.ajax({
                                url:'kick_user.php',
                                type:'POST',
                                data:{
                                        confirm_kick:true,
                                        username:userNameForKick,
                                        id:userIdForKick
                                },
                                success:(responce)=>{
                                        if(responce == 'DONE'){
                                        }
                                }
                        });
                }
        });
        //----------------------------------------------------------------------
        // Event for when user click's on a li in chat-history
        var msgId = null;
        var toUserIdEdit = null;
        var editAllResult = null;
        $(document).on('click','.li-more-option',(e)=>{
                msgId = $(e.target).parents('li').data('msgid');
                toUserIdEdit = $(e.target).parents('.chat-history').data('touserid');
                $('#modal-2').show();

        });
        $('#deny-options').click(()=>{
                $('#modal-2').hide();
        });
        $('.modal-bg .edit-msg').click(()=>{
                $.ajax({
                        url:'msg_options.php',
                        type:'POST',
                        data:{
                                msg_id:msgId,
                                action:'edit'
                        },
                        success:(data)=>{
                                var res = JSON.parse(data);
                                editAllResult = res;
                                $('#modal-2').hide();
                                $('#chat-message-'+toUserIdEdit).text(res['chat_message']).val(res['chat_message']).addClass('editing').focus();
                        }
                });
        });

});   // ready {}
</script>
</body>
</html>
