<?php
ini_set('display_errors',1);
require_once 'setup.php';
try{
        $con = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8",$db_user,$db_pass);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
        echo 'BM_ERROR_FUNC_FILE ::'.$e->getMessage();
}

function encrypt_pass($pass){
        $salt = 'X`Gg.):#@BM_';
        $res = md5(sha1($salt.$pass).md5($pass));
        return $res;
}

function get_cookie_info_by_user($user){
        $data = get_user_info($user);
        $id = $data['id'];
        $res = get_cookie_info($id);
        return $res;
}
function check_cookie($hash,$data=false){
        global $con;
        $stmt = $con->prepare("SELECT * FROM cookie_id WHERE cookie_hash = ?");
        $stmt->execute(array($hash));
        $count = $stmt->rowCount();
        $res = $stmt->fetchAll();
        if($data==true){
                return $res[0];
        }
        if($count > 0 ){
                return true;
        }else{
                return false;
        }
}
function get_cookie_info($id){
        global $con;
        $stmt = $con->prepare("SELECT * FROM cookie_id WHERE id = ? ");
        $stmt->execute(array($id));
        $count = $stmt->rowCount();
        $res = $stmt->fetchAll();
        if($count > 0){
                return $res[0];
        }else{
                return false;
        }
}


function set_session($user, $path, $display, $ip,$id,$email){
        $_SESSION['logged_in'] = 'yes';
        $_SESSION['username'] = $user;
        $_SESSION['user_id'] = $id;
        $_SESSION['profilepic'] = $path;
        $_SESSION['displayname'] = $display;
        $_SESSION['ip'] = $ip;
        $_SESSION['email'] = $email;
}

function get_user_info($username,$id=null){
        global $con;
        if(isset($username)){
                $stmt = $con->prepare("SELECT * FROM users WHERE username=?");
                $stmt->execute(array($username));
        }else{
                $stmt = $con->prepare("SELECT * FROM users WHERE id=?");
                $stmt->execute(array($id));
        }
        $count = $stmt->rowCount();
        $res = $stmt->fetchAll(2);
        if($count > 0){
                return $res[0];
        }else{
                return false;
        }
}
function doLogin($user, $pass){
        global $con;
        $stmt = $con->prepare("SELECT * FROM users WHERE username=? AND password=?");
        $stmt->execute(array(strtolower(trim($user)), encrypt_pass($pass)));
        $count = $stmt->rowCount();
        if($count > 0 ){
                return true;
        }else{
                return false;
        }
}


function get_real_ip(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
                $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
}
function get_user_by_username($username){
        global $con;
        $stmt = $con->prepare("SELECT * FROM users WHERE username=?");
        $stmt->execute(array($username));
        $count = $stmt->rowCount();
        if($count == 0 ){
                return true;
        }else{
                return false;
        }
}

function get_user_by_email($email){
        global $con ;
        $stmt = $con->prepare("SELECT * FROM users WHERE email = ? ;");
        $stmt->execute(array(strtolower($email)));
        $count = $stmt->rowCount();
        if($count == 0){
                return true;
        }else{
                return false;
        }
}

function create_cookie_hash($txt){
        $salt = '&ASFUH&!*@$^&';
        $res = sha1(md5($salt.$txt).sha1($txt));
        return $res;
}

function register_user($user, $pass, $email, $displayname){
        global $con;
        if(get_user_by_username($user)){
                if(get_user_by_email($email)){
                        $path = "files/images/user.png";
                        $stmt = $con->prepare("INSERT INTO users VALUES(null,?,?,?,?,?) ");
                        $stmt->execute(array(strtolower(trim($user)), encrypt_pass($pass), trim($displayname), strtolower(trim($email)) ,$path));
                        $count = $stmt->rowCount();
                        if($count > 0 ){
                                $res = get_user_info($user);
                                $id = $res['id'];
                                $stmt2 = $con->prepare("INSERT INTO cookie_id VALUES(?,?)");
                                $stmt2->execute(array($id, create_cookie_hash($user)));
                                $count2 = $stmt2->rowCount();
                                if($count2 > 0){
                                        return true;
                                }else{
                                        return false;
                                }
                        }else{
                                return false;
                        }
                }else{
                        return 'ERR_DUP_EMAIL';
                }
        }else{
                return 'ERR_DUP_USERNAME';
        }
}


function add_profile_path_to_db($username, $path){
        global $con;
        $stmt = $con->prepare("UPDATE users SET profile_pic = ? WHERE username = ?");
        $stmt->execute(array($path, $username));
        return true;

}
function redirect_to($add){
        header("Location:{$add}");
}

function logout(){
        session_destroy();
        setcookie('logged_in','',1);
        setcookie('hash','',1);

}

function set_id_to_login_details($id){
        global $con;
        $stmt = $con->prepare("INSERT INTO login_details VALUES(null,?,null,'',null)");
        $stmt->execute(array($id));
        $count = $stmt->rowCount();
        if($count > 0 ){
                return true;
        }else{
                return false;
        }
}
function get_login_details($user_id){
        global $con;
        $stmt = $con->prepare("SELECT * FROM login_details WHERE user_id = ? ORDER BY login_details_id DESC LIMIT 1");
        $stmt->execute(array($user_id));
        $count = $stmt->rowCount();
        if($count > 0){
                $res = $stmt->fetchAll();
                return $res[0];
        }else{
                return false;
        }
}


function fetch_user_last_activity($user_id){
        global $con;
        $stmt = $con->prepare("SELECT * FROM login_details WHERE user_id = ? ORDER BY last_activity DESC LIMIT 1");
        $stmt->execute(array($user_id));
        $res = $stmt->fetchAll();
        foreach($res as $row){
                return $row['last_activity'];
        }
}



function fetch_chat_history($from_user_id, $to_user_id){
        global $con;
        $stmt = $con->prepare("SELECT * FROM chat_message WHERE
                (from_user_id = ? AND to_user_id = ?)
                OR (from_user_id = ? AND to_user_id = ?)
                ORDER BY timestamp ASC");
                $stmt->execute(array($from_user_id , $to_user_id , $to_user_id , $from_user_id));
                $res = $stmt->fetchAll();
                $output = '<ul class="list-unstyled">';
                $count = $stmt->rowCount();
                if($count == 0){
                        $output .= '‌<li class="empty-history"><em>پیامی وجود ندارد.</em></li>';
                }else{
                        $first_date = 0;

                        foreach($res as $row){
                                $time = $row['timestamp'];
                                $time = explode(' ', $time);

                                // DATE
                                $date = $time[0];
                                $date = explode('-',$date);


                                // TIME
                                $time = $time[1];
                                $time = explode(':',$time);
                                $hour = $time[0];
                                $min = $time[1];

                                $current_id = $row['id_per_msg'];
                                // OR (from_user_id = ? AND to_user_id = ?)
                                $stmt = $con->prepare("SELECT * FROM chat_message WHERE (from_user_id = ? AND to_user_id = ? AND id_per_msg = ?) OR (from_user_id = ? AND to_user_id = ? AND id_per_msg = ?)");
                                $stmt->execute(array($from_user_id, $to_user_id, ($current_id+1), $to_user_id, $from_user_id, ($current_id+1)));
                                $data2 = $stmt->fetchAll();
                                $next_date = null;
                                foreach($data2 as $row2){
                                        $next_date =  $row2['timestamp'];
                                }

                                $sub = date('nd',strtotime($next_date)) - date('nd',strtotime($row['timestamp']));

                                $sub_year = date('Y',strtotime($next_date)) - date('Y',strtotime($row['timestamp']));


                                if($first_date == 0){  // this code is for date in top of chat history ==> to findout when chat is started
                                        $tmp = strtotime($row['timestamp']);
                                        $msg_Y = date('Y',$tmp);
                                        $now_Y = date('Y');

                                        if($now_Y == $msg_Y){
                                                $tmp = date('F j',$tmp);
                                                $output .= '<li class="group-other"><small class="new-time"><strong><em> '.$tmp.'</em></strong></small><li>';

                                        }else{
                                                $tmp = date('F j , Y',$tmp);
                                                $output .= '<li class="group-other"><small class="new-time"><strong><em> '.$tmp.'</em></strong></small><li>';

                                        }
                                        $first_date = 1;
                                }


                                if($row['from_user_id'] == $from_user_id){
                                        $tick1 = '';
                                        $tick2 = '';
                                        if($row['is_sent'] == '1'){
                                                $tick1 = 'fa fa-check';
                                        }
                                        if($row['is_seen'] == '1'){
                                                $tick2 = 'fa fa-check';
                                        }



                                        $output .= '
                                        <li class="message-you" data-msgId='.base64_encode($row['id']).'>
                                        <p>'.$row['chat_message'].'
                                        <div class="message-time">
                                        <small><em>'.$hour.':'.$min.'</em><i class="'.$tick1.'"></i><i class="'.$tick2.'" style="margin-right: -5px;"></i></small>
                                        <small class="li-more-option">...</small>
                                        </div>
                                        </p>
                                        </li>';
                                }else{
                                        $output .= '
                                        <li class="message-other" data-msgId='.base64_encode($row['id']).'>
                                        <p>'. $row['chat_message'] .'
                                        <div class="message-time">
                                        <small><em>'.$hour.':'.$min.'</em></small>
                                        </div>
                                        </p>
                                        </li>';
                                }
                                if($sub_year > 0 ){
                                        $tmp = strtotime($next_date);
                                        $tmp = date('F j , Y',$tmp);
                                        $output .= '<li class="group-other"><small class="new-time"><strong><em> '.$tmp.'</em></strong></small><li>';
                                }else if($sub > 0){
                                        $tmp = strtotime($next_date);
                                        $tmp = date('F j',$tmp);
                                        $output .= '<li class="group-other"><small class="new-time"><strong><em> '.$tmp.'</em></strong></small><li>';

                                }
                        }
                }
                $output .= '</ul>';
                return $output;
        }

        function fetch_unseen_chat($from_user_id , $to_user_id){
                global $con;
                $stmt = $con->prepare("SELECT * FROM chat_message WHERE from_user_id = ? AND to_user_id = ? AND is_seen = ?");
                $stmt->execute(array($from_user_id , $to_user_id , '0'));
                $count = $stmt->rowCount();
                $output = '';
                if($count > 0){
                        if($count > 99){
                                $count = '99+';
                        }
                        $output .= '<small class="unseen-chat" data-msgcount='.$count.' style="    position: absolute;
                        background-color: red;
                        padding: 3px 5px 0px 4px;
                        border-radius: 10px;
                        bottom: -8px;
                        left: -8px;">'.$count.'</small>';
                        return $output;
                }

        }

        function fetch_is_type($user_id){
                global $con;
                $stmt = $con->prepare("SELECT is_type,typing_target FROM login_details WHERE user_id = ? ORDER BY last_activity DESC LIMIT 1");
                $stmt->execute(array($user_id));
                $count = $stmt->rowCount();
                $res = $stmt->fetchAll();
                $output = 'none';

                foreach($res as $row){

                        if($row['typing_target'] == $_SESSION['user_id']){
                                if($row['is_type'] == 'yes')
                                $output = 'block';
                        }
                }
                return $output;

        }

        function fetch_group_chat_history($user_id){
                global $con;
                $stmt = $con->prepare("SELECT * FROM chat_message WHERE to_user_id = '0' ORDER BY timestamp ASC;");
                $stmt->execute();
                $count = $stmt->rowCount();
                $output = '<ul style="list-style: none;">';
                if($count == 0)
                $output .= '‌<li class="empty-history"><em>پیامی وجود ندارد.</em></li>';
                else{
                        $data = $stmt->fetchAll();
                        $first_date = 0;
                        foreach($data as $row){
                                $time = $row['timestamp'];
                                $time = explode(' ', $time);
                                // DATE
                                $date = $time[0];
                                $date = explode('-',$date);
                                // TIME
                                $time = $time[1];
                                $time = explode(':',$time);
                                $hour = $time[0];
                                $min = $time[1];
                                $user = get_user_info(null,$row['from_user_id']);

                                // codes for computing messages date
                                $current_id = $row['id_per_msg'];
                                $stmt = $con->prepare("SELECT * FROM chat_message WHERE to_user_id = 0 AND id_per_msg = ? ");
                                $stmt->execute(array(($current_id+1)));
                                $data2 = $stmt->fetchAll();
                                $next_date = null;
                                foreach($data2 as $row2){
                                        $next_date =  $row2['timestamp'];
                                }


                                $sub = date('nd',strtotime($next_date)) - date('nd',strtotime($row['timestamp']));

                                $sub_year = date('Y',strtotime($next_date)) - date('Y',strtotime($row['timestamp']));

                                if($first_date == 0){ // this code is for date in top of chat history ==> to findout when chat is started
                                        $tmp = strtotime($row['timestamp']);
                                        $tmp = date('F j , Y',$tmp);
                                        $output .= '<li class="group-other"><small class="new-time"><strong><em> '.$tmp.'</em></strong></small><li>';
                                        $first_date = 1;
                                }


                                
                                if($row['from_user_id'] == $_SESSION['user_id']){
                                        $tick1 = '';
                                        $tick2 = '';
                                        if($row['is_sent'] == '1'){
                                                $tick1 = 'fa fa-check';
                                        }
                                        if($row['is_seen'] == '1'){
                                                $tick2 = 'fa fa-check';
                                        }




                                        $output .= '
                                        <li class="group-you" data-msgId='.base64_encode($row['id']).'>
                                        <p>'.$row['chat_message'].'
                                        <span class="message-time">
                                        <small><em>'.$hour.':'.$min.'</em><i class="'.$tick1.'"></i><i class="'.$tick2.'" style="margin-right: -5px;"></i></small>
                                        <small class="li-more-option">...</small>
                                        </span>
                                        </p>
                                        </li>';
                                }else{
                                        $output .= '
                                        <li class="group-other" data-msgId='.base64_encode($row['id']).'>
                                        <img src="'.$user['profile_pic'].'" id="history-img" >
                                        <p class="history-text"><span class="group-user-name">'.$user['username'].'</span> <span>'. $row['chat_message'].'</span>
                                        <span class="message-time">
                                        <small><em>'.$hour.':'.$min.'</em></small>
                                        </span>
                                        </p>
                                        </li>';
                                }
                                if($sub_year > 0 ){
                                        $tmp = strtotime($next_date);
                                        $tmp = date('F j , Y',$tmp);
                                        $output .= '<li class="group-other"><small class="new-time"><strong><em> '.$tmp.'</em></strong></small><li>';
                                }else if($sub > 0){
                                        $tmp = date('Y') - date('Y',strtotime($next_date));
                                        if($tmp > 0 ){
                                                $tmp = strtotime($next_date);
                                                $tmp = date('F j , Y',$tmp);
                                                $output .= '<li class="group-other"><small class="new-time"><strong><em>'.$tmp.'</em></strong></small><li>';
                                        }else{
                                                $tmp = strtotime($next_date);
                                                $tmp = date('F j',$tmp);
                                                $output .= '<li class="group-other"><small class="new-time"><strong><em> '.$tmp.'</em></strong></small><li>';
                                        }

                                }
                        }
                }
                $output .= '</ul>';
                return $output;
        }

        function delete_account($username, $email, $id){
                global $con;
                if($email == null){
                        $stmt = $con->prepare("DELETE FROM users WHERE username = ?");
                        $stmt->execute(array($username));
                }else{
                        $stmt = $con->prepare("DELETE FROM users WHERE username = ? AND email = ?");
                        $stmt->execute(array($username, $email));
                }
                $stmt = $con->prepare("DELETE FROM cookie_id WHERE id = ? ");
                $stmt->execute(array($id));

                $stmt = $con->prepare("DELETE FROM login_details WHERE user_id = ?");
                $stmt->execute(array($id));
                return true;
        }

        function check_admin($username){  // check for admin privilage
                global $con;
                $stmt = $con->prepare("SELECT * FROM admin_tbl WHERE username = ?;");
                $stmt->execute(array($username));
                $count = $stmt->rowCount();
                $res = $stmt->fetchAll();
                if($count > 0){
                        return $res[0];
                }else{
                        return false;
                }
        }
