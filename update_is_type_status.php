<?php

require_once 'functions.php';
session_start();
$stmt = $con->prepare("UPDATE login_details SET is_type = ? WHERE login_details_id = ?");
$stmt->execute(array($_POST['is_type'], $_SESSION['login_details_id']));

