
<?  
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속


//칼럼별 검색을 위해 칼럼 이름이 어느 테이블에 속해있는 것인지 배열에 담기
$sql_check = "show columns from flower.addr";



$sel = mysqli_query($dbcon, $sql_check) or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);





echo "success";