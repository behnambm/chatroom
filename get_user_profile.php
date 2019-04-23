<?php
session_start();
require_once 'functions.php';
if($_SESSION['logged_in'] == 'yes'){
        $id = $_POST['user_id'];
        $data = null;
        foreach (get_user_info(null,$id) as $key => $value) {
                if($key == 'password'){
                        continue;
                }
                $data[$key] = $value;
        }
        $stmt = $con->prepare("SELECT ");
        echo json_encode($data);

}
