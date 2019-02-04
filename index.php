<?php
session_start();
if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] =! 'yes'){
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
   <link rel="stylesheet" href="files/css/font-awesome.min.css">
   <link rel="stylesheet" href="files/css/bootstrap-4.1.2.min.css">
   <script src="files/js/jquery-3.1.1.js"></script>

   <link rel="stylesheet" href="files/css/style.css">
</head>
<body>
   <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <a class="navbar-brand active" href="index.php">چت روم</a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
               <a class="nav-item nav-link" href="profile.php">پروفایل</a>
               <a class="nav-item nav-link" href="?logout=1">خروج</a>
            </div>
         </div>
      </nav>
      <div class="clear"></div>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
      <a class="nav-item nav-link active" href="#">Home <span class="sr-only">(current)</span></a>
      <a class="nav-item nav-link" href="#">Features</a>
      <a class="nav-item nav-link" href="#">Pricing</a>
      <a class="nav-item nav-link disabled" href="#">Disabled</a>
    </div>
  </div>
</nav>
   </div>
   <script src="files/js/jquery-3.1.1.js"></script>
</body>
</html>
