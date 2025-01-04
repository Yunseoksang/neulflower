<?php

ini_set('display_errors', '0');


// error_reporting(E_ALL);
// ini_set("display_errors", 1);

//echo "referer:".$_SERVER['HTTP_REFERER'];

$http_referer = "https://neulflower.kr/admin/dashboard_flower.php?page=flower/out_order/list&mode=complete";

require_once $_SERVER["DOCUMENT_ROOT"].'/admin/contents/common/api/getList_common.php'; //DB 접속





echo json_encode($result);

