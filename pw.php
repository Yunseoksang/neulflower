

<?




	
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/password_bcrypt.php'; //패스워드 암호화. php 5.5이상. 
$pw_hash = password_hash($_GET['pw'], PASSWORD_BCRYPT);

echo $pw_hash;


?>