<?php

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'behnam_db';

function setup(){
    $db_file_content = file_get_contents('db.conf');
    if($db_file_content == 'DATABASE_CONNECT::1;DATABASE_CREATED::1;USE_DATABASE::1;USERS_TABLE_CREATED::1;COOKIE_TABLE_CREATED::1;'){
        return true;
    }
    global $db_host,$db_name,$db_pass,$db_user;
    try{
        $con = new PDO('mysql:host='.$db_host.';charset=utf8;',$db_user,$db_pass);
        file_put_contents('db.conf','DATABASE_CONNECT::1;');
        // set the PDO error mode to exception
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // create a database
        // hex code ==  'DATABASE_CREATED::1;' 
        $sql = "CREATE DATABASE IF NOT EXISTS behnam_db CHARACTER SET utf8 COLLATE utf8_persian_ci;";
        $con->exec($sql);
        file_put_contents('db.conf','DATABASE_CREATED::1;',FILE_APPEND);

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
    		profile_pic varchar(128) UNIQUE NOT NULL
            )";
        $con->exec($sql2);
        file_put_contents('db.conf','USERS_TABLE_CREATED::1;',FILE_APPEND);


        // create cookies table
        $sql3 = "CREATE TABLE IF NOT EXISTS cookie_id (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            cookie_hash varchar(128) UNIQUE NOT NULL
            );";
        $con->exec($sql3);
        file_put_contents('db.conf','COOKIE_TABLE_CREATED::1;',FILE_APPEND);

    }catch(PDOException $e){
    echo $sql . "<br>" . $e->getMessage();
    }

}


