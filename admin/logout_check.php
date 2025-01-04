<?


//unset($_COOKIE['admin_info']);
setcookie("admin_info", "", time()-3600);

header('Location:login/index.php');

?>