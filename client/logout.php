<?
//require_once '../lib/DB_Connect.php'; //DB 접속

//require_once('../lib/lib_api.php');


if(isset($_COOKIE['manager_info'])){

    //unset($_COOKIE['manager_info']); //not working
    setcookie("manager_info", "", time()-3600);
    header('Location:login/');
    exit;

    
}else{
    //unset($_COOKIE['manager_info']);

    header('Location:login/');
    exit;
    
}