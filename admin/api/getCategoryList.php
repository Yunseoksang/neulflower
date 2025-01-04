<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();




$sel = mysqli_query($dbcon, "select * from category order by category_display_order,category_idx ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$categoryList = array();  //
if ($sel_num > 0) {
   //$data = mysqli_fetch_assoc($sel);
   while($data = mysqli_fetch_assoc($sel)) {

        
       $categoryList[] = $data;
   }
}





$result = array();
$result['status'] = 1;
$result['data']['categoryList']=$categoryList;

echo json_encode($result);



?>


