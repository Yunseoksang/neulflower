<?
require_once '../lib/DB_Connect.php'; //DB 접속

//session_start();
//require_once('../lib/lib_api.php');


if($_SESSION['admin_info']['admin_mode'] == "smart"){
    unset($_SESSION['admin_info']);

    header('Location:login/smart_login.php');
    exit;
    
}else{
    unset($_SESSION['admin_info']);

    header('Location:login/index.php');
    exit;
    
}




?>