<?php
ini_set('display_errors',1);
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'behnam_db';

function setup(){
    $db_file_content = file_get_contents('db.conf');
    if($db_file_content == 'DATABASE_CONNECT::1;IS_DATABASE_CREATED::1;USE_DATABASE::1;IS_USERS_TABLE_CREATED::1;IS_COOKIE_TABLE_CREATED::1;IS_LOGIN_DETAILS_TABLE_CREATED::1;IS_CHAT_MESSAGE_TABLE_CREATED::1;IS_ADMIN_TABLE_CREATED::1;'){
        return true;
    }
    global $db_host,$db_name,$db_pass,$db_user;
    try{
        $con = new PDO('mysql:host='.$db_host.';charset=utf8;',$db_user,$db_pass);
        file_put_contents('db.conf','DATABASE_CONNECT::1;'.PHP_EOL);
        // set the PDO error mode to exception
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // create a database
        $sql = "CREATE DATABASE IF NOT EXISTS behnam_db CHARACTER SET utf8 COLLATE utf8_persian_ci;";
        $con->exec($sql);
        file_put_contents('db.conf','IS_DATABASE_CREATED::1;'.PHP_EOL,FILE_APPEND);

        //select database
        $con->exec('USE behnam_db;');
        file_put_contents('db.conf','USE_DATABASE::1;'.PHP_EOL,FILE_APPEND);

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
        file_put_contents('db.conf','IS_USERS_TABLE_CREATED::1;'.PHP_EOL,FILE_APPEND);

        // create cookies table
        $sql3 = "CREATE TABLE IF NOT EXISTS cookie_id (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            cookie_hash varchar(128) UNIQUE NOT NULL
            );";
        $con->exec($sql3);
        file_put_contents('db.conf','IS_COOKIE_TABLE_CREATED::1;'.PHP_EOL,FILE_APPEND);

        // create login_details TABLE
        $sql4 = "CREATE TABLE IF NOT EXISTS `login_details` (
            `login_details_id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY ,
            `user_id` int(11) NOT NULL,
            `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `is_type` enum('no','yes') NOT NULL,
            typing_target VARCHAR(2)
          );";
        $con->exec($sql4);
        file_put_contents('db.conf','IS_LOGIN_DETAILS_TABLE_CREATED::1;'.PHP_EOL, FILE_APPEND);

        // create chat_message TABLE
        $sql5 = "CREATE TABLE IF NOT EXISTS chat_message (
            id INT AUTO_INCREMENT PRIMARY KEY,
            from_user_id VARCHAR(32) NOT NULL,
                to_user_id VARCHAR(32) NOT NULL,
                chat_message TEXT,
                timestamp VARCHAR(16),
                is_sent VARCHAR(2) DEFAULT '0' ,
                is_seen VARCHAR(2) DEFAULT '0',
                id_per_msg INT DEFAULT 0
            );";
        $con->exec($sql5);
        file_put_contents('db.conf','IS_CHAT_MESSAGE_TABLE_CREATED::1;'.PHP_EOL, FILE_APPEND);

        // create admin TABLE 
        $sql6 = "CREATE TABLE IF NOt EXISTS admin_tbl (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(32) NOT NULL,
            privilage VARCHAR(8) NOT NULL 
        )";
        $con->exec($sql6);
        file_put_contents('db.conf','IS_ADMIN_TABLE_CREATED::1;'.PHP_EOL, FILE_APPEND);
        // create root user 
    }catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
    }
}
