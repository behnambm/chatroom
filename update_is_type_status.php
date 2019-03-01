<?php

require_once 'functions.php';
session_start();
if(isset($_POST['is_type'], $_POST['to_user_id'])){
    $stmt = $con->prepare("UPDATE login_details SET is_type = ? , typing_target = ? WHERE login_details_id = ?");
    $stmt->execute(array($_POST['is_type'],$_POST['to_user_id'], $_SESSION['login_details_id']));

}else{
    echo '<script>window.location = "login.php"</script>';

}

