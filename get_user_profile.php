<?php
session_start();
require_once 'functions.php';
date_default_timezone_set('Asia/Tehran');
if($_SESSION['logged_in'] == 'yes'){
        $id = $_POST['user_id'];
        $data = null;
        foreach (get_user_info(null,$id) as $key => $value) {
                if($key == 'password'){
                        continue;
                }
                $data[$key] = $value;
        }
        $current_stimestamp = strtotime(date('Y-m-d H:i:s').'-4 second');
        $current_stimestamp = date('Y-m-d H:i:s',$current_stimestamp);
        $user_last_activity = fetch_user_last_activity($data['id']);

        if($user_last_activity > $current_stimestamp){
                $data['status'] = '<span style="color:#43A047;">آنلاین</span>';
        }else{
                $date = explode(' ', $user_last_activity);
                $time = explode(':' , $date[1]);

                $date = explode('-',$date[0]);
                $year = $date[0];
                $month = $date[1];
                $day = $date[2];

                $hour = $time[0];
                $min = $time[1];
                if(date('Y') != $year){
                        $data['status'] = 'بیش از یک سال';
                }else{
                        if(date('m') != $month){
                                $tmp_month = date('m') - $month;
                                $data['status'] = (date('m') - $month).' ماه قبل ';
                        }else{
                                if(date('d') == $day){
                                        $tmp_hour = date('H');
                                        $tmp_min = date('i');
                                        if($tmp_hour == $hour){
                                                if(($tmp_min - $min) < 2){
                                                        $data['status'] = 'لحضاتی پیش';
                                                }else{
                                                        $data['status'] = ($tmp_min - $min).' دقیقه قبل ';
                                                }
                                        }else{
                                                $data['status'] = ($tmp_hour - $hour).' ساعت قبل';
                                        }
                                }else{
                                        $data['status'] = (date('d') - $day) . ' روز قبل ';

                                }
                        }
                }

        }
        echo json_encode($data);
}
