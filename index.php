<?php
session_start();


if(!isset($_SESSION['isLogin'])){
   header('Location:login.php'); 
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Chat Room</title>
</head>
<body>
   <h1>Welcome !</h1>
</body>
</html>
