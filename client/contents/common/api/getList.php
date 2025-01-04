<?php

ini_set('display_errors', '0');


// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once $_SERVER["DOCUMENT_ROOT"].'/client/contents/common/api/getList_common.php'; //DB 접속





echo json_encode($result);

