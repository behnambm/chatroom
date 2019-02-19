<?php
session_start();
require_once 'functions.php';

$stmt = $con->prepare("UPDATE chat_message SET is_seen = ? WHERE from_user_id = ? AND to_user_id = ?");
$stmt->execute(array('1' , $_POST['to_user_id'] , $_SESSION['user_id']));
echo fetch_chat_history($_SESSION['user_id'] , $_POST['to_user_id'] );
