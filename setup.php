<?php
ini_set('display_errors',1);
$db_host = 'localhost';
$db_user = 'admin';
$db_pass = 'behnam1312';
$db_name = 'behnam_db';

function setup(){
        $db_file_content = file_get_contents('db.conf');
        if($db_file_content == "DATABASE_CONNECT::1;CREATE_DB::1;USE_DATABASE::1;USERS_TBL::1;COOKIE_TBL::1;LOGIN_DETAIL_TBL::1;CHAT_MSG_TBL::1;ADMIN_TBL::1;INSERT_USER::1;INSERT_HASH::1;INSERT_PRIVILAGE::1;INSERT_MEMBER_1::1;INSERT_MEMBER_2::1;"){
                return true;
        }
        global $db_host,$db_name,$db_pass,$db_user;
        try{
                $con = new PDO('mysql:host='.$db_host.';charset=utf8;',$db_user,$db_pass);
                file_put_contents('db.conf','DATABASE_CONNECT::1;');
                // set the PDO error mode to exception
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // create a database
                $sql = "CREATE DATABASE IF NOT EXISTS behnam_db CHARACTER SET utf8 COLLATE utf8_persian_ci;";
                $con->exec($sql);
                file_put_contents('db.conf','CREATE_DB::1;',FILE_APPEND);

                //select database
                $con->exec('USE behnam_db;');
                file_put_contents('db.conf','USE_DATABASE::1;',FILE_APPEND);

                // create users table
                $sql2 = "CREATE TABLE IF NOT EXISTS users (
                        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        username VARCHAR(32) NOT NULL,
                        password VARCHAR(32) NOT NULL,
                        display_name VARCHAR(64) NOT NULL,
                        email VARCHAR(64) UNIQUE,
                        profile_pic varchar(64) NOT NULL
                );";
                $con->exec($sql2);
                file_put_contents('db.conf','USERS_TBL::1;',FILE_APPEND);

                // create cookies table
                $sql3 = "CREATE TABLE IF NOT EXISTS cookie_id (
                        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        cookie_hash varchar(128) UNIQUE NOT NULL
                );";
                $con->exec($sql3);
                file_put_contents('db.conf','COOKIE_TBL::1;',FILE_APPEND);

                // create login_details TABLE
                $sql4 = "CREATE TABLE IF NOT EXISTS `login_details` (
                        `login_details_id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY ,
                        `user_id` int(11) NOT NULL,
                        `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `is_type` enum('no','yes') NOT NULL,
                        typing_target VARCHAR(2)
                );";
                $con->exec($sql4);
                file_put_contents('db.conf','LOGIN_DETAIL_TBL::1;', FILE_APPEND);

                // create chat_message TABLE
                $sql5 = "CREATE TABLE IF NOT EXISTS chat_message (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        from_user_id VARCHAR(32) NOT NULL,
                        to_user_id VARCHAR(32) NOT NULL,
                        chat_message TEXT,
                        timestamp VARCHAR(16),
                        is_sent VARCHAR(2) DEFAULT '0' ,
                        is_seen VARCHAR(2) DEFAULT '0',
                        id_per_msg INT DEFAULT 0 NOT NULL,
                        is_edited INT DEFAULT 0
                );";
                $con->exec($sql5);
                file_put_contents('db.conf','CHAT_MSG_TBL::1;', FILE_APPEND);

                // create admin TABLE
                $sql6 = "CREATE TABLE IF NOt EXISTS admin_tbl (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        username VARCHAR(32) NOT NULL,
                        privilage VARCHAR(8) NOT NULL
                )";
                $con->exec($sql6);
                file_put_contents('db.conf','ADMIN_TBL::1;', FILE_APPEND);

                // create root user
                $sql7 = "INSERT INTO users (id, username, password, display_name, email, profile_pic) VALUES (1, 'admin', '5d1ea37d3a602f792b251568fa9ba967', 'مدیر اصلی', 'admin@email.com', 'files/images/crown.png')";
                $con->exec($sql7);
                file_put_contents('db.conf', 'INSERT_USER::1;', FILE_APPEND);

                // insert cookie hash in table
                $sql8 = "INSERT INTO cookie_id (id, cookie_hash) VALUES (1, '42317fe0b4e71cd07bc4c406f88f693141690a93')";
                $con->exec($sql8);
                file_put_contents('db.conf', 'INSERT_HASH::1;', FILE_APPEND);

                // insert owner privilage in table
                $sql9 = "INSERT INTO admin_tbl (username, privilage) VALUES ('admin','owner')";
                $con->exec($sql9);
                file_put_contents('db.conf', 'INSERT_PRIVILAGE::1;', FILE_APPEND);

                // create tmp user  1
                $sql10 = "INSERT INTO users (id, username, password, display_name, email, profile_pic)
                VALUES (2, 'user1', '7555576cf46ba3c36dbabf0c38ede6c2', 'کاربر آزمایشی ۱', 'user1@gmail.com', 'files/images/user.png');
                INSERT INTO cookie_id (id, cookie_hash) VALUES (2, '4e8eec75537523cb9e54efe1aa4aa585aaa0378f');";
                $con->exec($sql10);
                file_put_contents('db.conf', 'INSERT_MEMBER_1::1;', FILE_APPEND);

                // create tmp user  2
                $sql10 = "INSERT INTO users (id, username, password, display_name, email, profile_pic)
                VALUES (3, 'user2', '7555576cf46ba3c36dbabf0c38ede6c2', 'کاربر آزمایشی 2', 'user2@gmail.com', 'files/images/user.png');
                INSERT INTO cookie_id (id, cookie_hash) VALUES (3, 'e574f2d7cb7c52a70242348120ea9ebb44a39f72');";
                $con->exec($sql10);
                file_put_contents('db.conf', 'INSERT_MEMBER_2::1;', FILE_APPEND);

        }catch(PDOException $e){
                echo $sql . "<br>" . $e->getMessage();
        }
}

if(isset($_GET['setup']) && $_GET['setup'] == 1){
        setup();
        echo '<script>window.location = "login.php";</script>';

}
