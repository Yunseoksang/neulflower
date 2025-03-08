<?

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 디버깅 함수 정의
function debug_to_file($data, $label = '') {
    $debug_file = $_SERVER["DOCUMENT_ROOT"].'/debug_log.txt';
    $output = date('Y-m-d H:i:s') . " - " . $label . ": ";
    if (is_array($data) || is_object($data)) {
        $output .= print_r($data, true);
    } else {
        $output .= $data;
    }
    file_put_contents($debug_file, $output . "\n", FILE_APPEND);
}

// 전역 예외 처리기 설정
set_exception_handler(function($e) {
    debug_to_file($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(), 'EXCEPTION');
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "오류가 발생했습니다: " . $e->getMessage();
    echo json_encode($result);
    exit;
});

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();

debug_to_file('API 호출 시작', 'SAVE_CLIENT_ORDER');

if($_POST['data'] == ""){
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "입력데이터가 없습니다.";

    echo json_encode($result);
    exit;
}



function getArea($zipNo,$address1){
    $local_area = "대기"; // 기본값을 "대기"로 설정
    
    if($zipNo != ""){
        //https://bustar.tistory.com/237  우편번호로 지역 분류
        $code = (int) substr($zipNo,0,2);

        if($code >= 1 && $code <= 9){$local_area = "서울";}
        else if($code >= 10 && $code <=20 ){$local_area = "경기";}
        else if($code >= 21 && $code <=23 ){$local_area = "인천";}
        else if($code >= 24 && $code <= 26){$local_area = "강원";}
        else if($code >= 27 && $code <=29 ){$local_area = "충북";}
        else if($code >= 30 ){$local_area = "세종";}
        else if($code >= 31 && $code <= 33){$local_area = "충남";}
        else if($code >= 34 && $code <=35 ){$local_area = "대전";}
        else if($code >=36 && $code <=40 ){$local_area = "경북";}
        else if($code >= 41 && $code <=43 ){$local_area = "대구";}
        else if($code >= 44 && $code <= 45){$local_area = "울산";}
        else if($code >= 46 && $code <= 49){$local_area = "부산";}
        else if($code >= 50 && $code <=53 ){$local_area = "경남";}
        else if($code >= 54 && $code <= 56){$local_area = "전북";}
        else if($code >= 57 && $code <=60 ){$local_area = "전남";}
        else if($code >= 61 && $code <= 62){$local_area = "광주";}
        else if($code == 63 ){$local_area = "제주";} // = 를 == 로 수정
       
    }else{//우편번호를 안 넣었으면.
        $address1 = str_replace(" ","",$address1);
        
        if(strpos($address1,"서울") !== false){$local_area = "서울";}
        else if(strpos($address1,"경기") !== false){$local_area = "경기";}
        else if(strpos($address1,"인천") !== false){$local_area = "인천";}
        else if(strpos($address1,"강원") !== false){$local_area = "강원";}
        else if(strpos($address1,"충북") !== false || strpos($address1,"충청북") !== false){$local_area = "충북";}
        else if(strpos($address1,"세종") !== false){$local_area = "세종";}
        else if(strpos($address1,"충남") !== false || strpos($address1,"충청남") !== false){$local_area = "충남";}
        else if(strpos($address1,"대전") !== false){$local_area = "대전";}
        else if(strpos($address1,"경북") !== false || strpos($address1,"경상북") !== false){$local_area = "경북";}
        else if(strpos($address1,"대구") !== false){$local_area = "대구";}
        else if(strpos($address1,"울산") !== false){$local_area = "울산";}
        else if(strpos($address1,"부산") !== false){$local_area = "부산";}
        else if(strpos($address1,"경남") !== false || strpos($address1,"경상남") !== false){$local_area = "경남";}
        else if(strpos($address1,"전북") !== false || strpos($address1,"전라북") !== false){$local_area = "전북";}
        else if(strpos($address1,"전남") !== false || strpos($address1,"전라남") !== false){$local_area = "전남";}
        else if(strpos($address1,"광주") !== false){$local_area = "광주";}
        else if(strpos($address1,"제주") !== false){$local_area = "제주";}
    }

    return $local_area;
}







$arr = json_decode($_POST['data'],true);


/* Start transaction */
mysqli_begin_transaction($dbcon);





//수정하기이면 수정가능한 단계인지 체크
if($arr['out_order_idx'] > 0){

    $sel = mysqli_query($dbcon, "select * from ".$db_flower.".out_order where out_order_idx='".$arr['out_order_idx']."' ") or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num > 0) {
        $data = mysqli_fetch_assoc($sel);
        if($data['out_order_status'] == "배송중" || $data['out_order_status'] == "주문완료" || $data['out_order_status'] == "주문취소" ){

            error_exit("데이터를 수정할수 없는 단계입니다.");

            exit;
        }

       
        //수정접수된 원래 항목이 상조인지 화훼인지 체크.
        if($data['out_order_part'] == "상조"){
            $sangjo_out_order_idx = $arr['out_order_idx'];
            $arr['sangjo_out_order_idx'] = $arr['out_order_idx'];

        }else if($data['out_order_part'] == "화훼"){
            $flower_out_order_idx = $arr['out_order_idx'];
            $arr['flower_out_order_idx'] = $arr['out_order_idx'];

        }

        
    }else{
        error_exit("수정대상 데이터가 없습니다.");
        exit;
    }

    //상품들은 모두 지웠다가 다시 입력해야 함.
    $del = mysqli_query($dbcon, "delete from ".$db_flower.".out_order_client_product where out_order_idx='".$arr['out_order_idx']."' ") or die(mysqli_error($dbcon)); //삭제된 상품이 있을수 있기 때문에 전체 삭제후 다시 입력
    $del = mysqli_query($dbcon, "delete from ".$db_sangjo_new.".out_order_client_product where flower_out_order_idx='".$arr['out_order_idx']."' ") or die(mysqli_error($dbcon));


}





function get_sql($arr,$admin_info,$part,$p_info){
    global $db_flower,$dbcon;

    debug_to_file("get_sql 함수 호출: part=$part", 'SQL_GENERATION');
    debug_to_file($arr, 'SQL_GENERATION_ARR');
    debug_to_file($p_info, 'SQL_GENERATION_P_INFO');

    $in_up = "insert"; //기본 insert 모드
    if($part == "flower"){
        $out_order_part_sql = "out_order_part='화훼',";
        $out_order_part_sql_n = "out_order_part='화훼'";

        $total_client_price = $arr['total_flower_price'] ;
        $total_client_price_tax = $arr['total_flower_price_tax'] ;
        $total_client_price_sum = $arr['total_flower_price_sum_calcu'];

        if($arr['out_order_idx'] > 0){ //수정모드인데
            if($arr['flower_out_order_idx'] > 0){ //플라워에서 수정 진입 + 플라워에 관한 작업중 $part == "flower"
                $in_up = "update";
            }
        }

        debug_to_file("화훼 SQL 생성 모드: $in_up", 'SQL_GENERATION');

     }else if($part == "sangjo" ){
        $out_order_part_sql = "out_order_part='상조',";
        $out_order_part_sql_n = "out_order_part='상조'";

        $total_client_price           = $arr['total_sangjo_price'] ;
        $total_client_price_tax       = $arr['total_sangjo_price_tax'] ;
        $total_client_price_sum       = $arr['total_sangjo_price_sum_calcu'];

        if($arr['out_order_idx'] > 0){ //수정모드인데
            if($arr['sangjo_out_order_idx'] > 0){ //상조에서 수정 진입 + 상조에 관한 작업중 $part == "sangjo"
                $in_up = "update";
            }
        }

        debug_to_file("상조 SQL 생성 모드: $in_up", 'SQL_GENERATION');
    }


    $ex = explode("(",$arr['r_date']);
    $r_date = $ex[0];
    $r_date = str_replace(" ","",str_replace("년","-",str_replace("월","-",str_replace("일","",$r_date))));

    debug_to_file("배송일 변환: {$arr['r_date']} -> $r_date", 'SQL_GENERATION');

    if($arr['out_order_idx'] > 0){

        $sel = mysqli_query($dbcon, "select * from ".$db_flower.".out_order where out_order_idx='".$arr['out_order_idx'] ."' and ".$out_order_part_sql_n) or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        debug_to_file("기존 주문 확인 결과: $sel_num 건", 'SQL_GENERATION');
        
        if ($sel_num > 0) {
            $sql1 = "update out_order ";
            $sql2 = " where out_order_idx='".$arr['out_order_idx']."' ";
            debug_to_file("기존 주문 있음, 업데이트 모드", 'SQL_GENERATION');
        }else{ //화훼주문 수정과정에서 상조용품이 추가된 경우. 별도의  out_order 생성. 
            $sql1 = "insert into ".$db_flower.".out_order ";
            $sql2 = "";
            debug_to_file("기존 주문 없음, 삽입 모드", 'SQL_GENERATION');
        }
    }else{
        $sql1 = "insert into ".$db_flower.".out_order ";
        $sql2 = "";
        debug_to_file("신규 주문, 삽입 모드", 'SQL_GENERATION');
    }

   
    $local_area = getArea($arr['zipNo'],$arr['address1']);
    debug_to_file("지역 정보: $local_area", 'SQL_GENERATION');

    $sql_base  = $sql1."
    set 
    consulting_idx='".$arr['consulting_idx']."',
    ".$out_order_part_sql."
    order_product_title='".$p_info[0]."',
    total_order_kinds ='".$p_info[1]."',
    total_order_count ='".$p_info[2]."',
    order_date        ='".$arr['order_date']."',
    order_name        ='".$arr['order_name']."',
    order_tel         ='".$arr['order_tel']."',
    order_company_tel ='".$arr['order_company_tel']."',
    r_name            ='".$arr['r_name']."',
    r_tel             ='".$arr['r_tel']."',
    r_company_tel     ='".$arr['r_company_tel']."',
    r_date            ='".$r_date."',
    r_date_weekday    ='".$arr['r_date']."',
    r_hour            ='".$arr['r_hour']."',
    local_area        ='".$local_area."',
    address1          ='".$arr['address1']."',
    address2          ='".$arr['address2']."',
    zipNo             ='".$arr['zipNo']."',

    messageType       ='".$arr['messageType']."',
    eType             ='".$arr['eType']."',
    msgTitle          ='".$arr['msgTitle']."',
    msgTitle2         ='".$arr['msgTitle2']."',
    msgTitle3         ='".$arr['msgTitle3']."',
    sender_name       ='".$arr['sender_name']."',
    delivery_memo     ='".$arr['delivery_memo']."',
    paymentType       ='".$arr['paymentType']."',
    
    total_client_price='".$total_client_price."',
    total_client_price_tax='".$total_client_price_tax."',
    total_client_price_sum='".$total_client_price_sum."',
    admin_idx='".$admin_info['admin_idx']."',
    admin_name='".$admin_info['admin_name']."'
    ".$sql2;

    debug_to_file("생성된 SQL: " . substr($sql_base, 0, 500) . "...", 'SQL_GENERATION');
    
    return $sql_base;
}




if(count($arr['flower_product_list']) > 0 ){

    debug_to_file('화훼 주문 처리 시작', 'FLOWER_ORDER');
    debug_to_file($arr['flower_product_list'], '화훼 상품 리스트');
    
    $flower_product_list = $arr['flower_product_list'];

    $p_names = $flower_product_list[0]['product_name']; //첫번째 상품만 대표상품으로 저장
    $total_order_count = 0;
    $total_kinds = count($flower_product_list);


    for ($i=0;$i<count($flower_product_list);$i++ )
    {
       $total_order_count += $flower_product_list[$i]['cnt'];
    }
    $p_info = [$p_names,$total_kinds,$total_order_count];

    debug_to_file("상품명: $p_names, 종류: $total_kinds, 총 개수: $total_order_count", '화훼 정보');
    debug_to_file("flower_out_order_idx: $flower_out_order_idx", '화훼 주문 인덱스');


    if($flower_out_order_idx > 0){ //수정하기 모드

        debug_to_file("수정 모드 진입", '화훼 주문');
        $out_order_idx = $arr['out_order_idx'];

        if(count($arr['sangjo_product_list']) == 0 ){ //out_order는 화훼와 상조가 분리가되어 저장되므료 교차되는 수정사항이 있으면 수정하지 않고 삭제처리.
            debug_to_file("상조 상품 없음, 기존 상조 주문 삭제", '화훼 주문');
            $del = mysqli_query($dbcon, "delete from ".$db_flower.".out_order where out_order_idx='".$arr['out_order_idx']."' and out_order_part='상조' ") or die(mysqli_error($dbcon));
        }


        //$up = mysqli_query($dbcon, "update out_order set out_order_status='주문취소' where out_order_idx='".$arr['out_order_idx']."' ") or die(mysqli_error($dbcon));
        $sql = get_sql($arr,$admin_info,"flower",$p_info ); ///get update query
        debug_to_file($sql, '화훼 업데이트 SQL');

        $up = mysqli_query($dbcon,$sql) or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
        debug_to_file("업데이트 영향 받은 행 수: $up_num", '화훼 업데이트 결과');
        
        if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
         //
        }else{ //쿼리실패
            mysqli_rollback($dbcon);
            debug_to_file("업데이트 실패", '화훼 업데이트 오류');
            error_exit("저장 실패 E211");
            exit;
        }



        

    }else{
        $sql = get_sql($arr,$admin_info,"flower",$p_info );  //get insert query
        debug_to_file($sql, '화훼 삽입 SQL');

        $in = mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));
        $out_order_idx = mysqli_insert_id($dbcon);
        debug_to_file("삽입된 주문 ID: $out_order_idx", '화훼 삽입 결과');
        
        if($out_order_idx){//쿼리성공
        //        
        //echo "쿼리1:".$sql;
        //exit;

        }else{
            mysqli_rollback($dbcon);
            debug_to_file("삽입 실패", '화훼 삽입 오류');
            error_exit("저장 실패 E212");
            exit;
        }



    }
    

    debug_to_file("화훼 상세 정보 저장 시작", '화훼 상세');
    for ($i=0;$i<count($flower_product_list);$i++ )
    {
        debug_to_file($flower_product_list[$i], "화훼 상세 #$i");
        
        $cnt =$flower_product_list[$i]['cnt'];

        $tcp = ((int)($flower_product_list[$i]['client_price']))*$cnt;
        $tcpt = ((int)($flower_product_list[$i]['client_price_tax']))*$cnt;
        $tcps = $tcp+$tcpt;



        //var client_price_sum_calcu = client_price_sum*cnt;
        //var client_price_calcu = client_price*cnt;
        $product_sql = "insert into ".$db_flower.".out_order_client_product 
            set
            out_order_idx='".$out_order_idx."',
            consulting_idx='".$arr['consulting_idx']."',
            product_idx='".$flower_product_list[$i]['product_idx']."',
            product_name='".$flower_product_list[$i]['product_name']."',
            order_count='".$flower_product_list[$i]['cnt']."',
            client_price='".$flower_product_list[$i]['client_price']."',
            client_price_tax='".$flower_product_list[$i]['client_price_tax']."',
            client_price_sum='".$flower_product_list[$i]['client_price_sum']."',
            total_client_price='".$tcp."',
            total_client_price_tax='".$tcpt."',
            total_client_price_sum='".$tcps."',

            admin_idx='".$admin_info['admin_idx']."',
            admin_name='".$admin_info['admin_name']."'
            
        ";
        
        debug_to_file($product_sql, "화훼 상세 SQL #$i");
        
        $in = mysqli_query($dbcon, $product_sql) or die(mysqli_error($dbcon));
        $in_id = mysqli_insert_id($dbcon);
        if($in_id){//쿼리성공
            debug_to_file("화훼 상세 저장 성공, ID: $in_id", "화훼 상세 #$i 결과");
            //
        }else{//쿼리실패
            mysqli_rollback($dbcon);
            debug_to_file("화훼 상세 저장 실패", "화훼 상세 #$i 오류");
            error_exit("저장 실패 E213");
            exit;
        }
        
        
    }



    if(isset($_FILES['files']['name'])){

        debug_to_file("파일 업로드 처리 시작", 'FILE_UPLOAD');
        $countfiles = count($_FILES['files']['name']);
        debug_to_file("업로드 파일 수: $countfiles", 'FILE_UPLOAD');
        
        $ymd = date("Y/m/d",time());
    
        $folder_idx = "/upload/client_flower/".$ymd."/".$out_order_idx;
        $absolute_folder_idx = $_SERVER["DOCUMENT_ROOT"]. $folder_idx;
        debug_to_file("업로드 폴더 경로: $absolute_folder_idx", 'FILE_UPLOAD');
    
        if (!file_exists($absolute_folder_idx)) {
            debug_to_file("폴더 생성: $absolute_folder_idx", 'FILE_UPLOAD');
            mkdir($absolute_folder_idx, 0777, true);
        }
    
    
    
        $local_name = $folder_idx."/".$name;
        $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;
    
        //중복파일 존재하면
        if(file_exists($absolute_path)){ 
            debug_to_file("중복 파일 발견: $absolute_path", 'FILE_UPLOAD');
            list($txt, $ext) = explode(".", $name);
            $new_name = $txt."_".time().".".$ext;
            $local_name = $folder_idx."/".$new_name;
            $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;
            debug_to_file("새 파일명: $new_name", 'FILE_UPLOAD');
        }
    
    
    
    
        // Upload Location
        $upload_location = $absolute_folder_idx."/";
    
        // To store uploaded files path
        $files_arr = array();
    
        // Loop all files
        for($index = 0;$index < $countfiles;$index++){
    
            if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != ''){
                // File name
                $filename = $_FILES['files']['name'][$index];
                debug_to_file("파일 처리 중: $filename", 'FILE_UPLOAD');
    
                // Get extension
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                debug_to_file("파일 확장자: $ext", 'FILE_UPLOAD');
    
                // Valid image extension
                //$valid_ext = array("png","jpeg","jpg");
                $unvalid_ext = array("exe");
    
                // Check extension
                //if(in_array($ext, $valid_ext)){
                if(!in_array($ext, $unvalid_ext)){
    
                    // File path
                    $absolute_path = $upload_location.$filename;
    
                    //중복파일 존재하면
                    if(file_exists($absolute_path)){ 
                        debug_to_file("중복 파일 발견: $absolute_path", 'FILE_UPLOAD');
                        list($txt, $ext) = explode(".", $filename);
                        $new_name = $txt."_".time().".".$ext;
                        $local_name = $folder_idx."/".$new_name;
                        $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;
                        debug_to_file("새 파일명: $new_name", 'FILE_UPLOAD');
                    }
    
    
    
                    // Upload file
                    if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$absolute_path)){
                        debug_to_file("파일 업로드 성공: $absolute_path", 'FILE_UPLOAD');
                        $files_arr[] = str_replace($_SERVER["DOCUMENT_ROOT"],"",$absolute_path); //저장된 파일주소 배열로 담기
                    } else {
                        debug_to_file("파일 업로드 실패: {$_FILES['files']['error'][$index]}", 'FILE_UPLOAD_ERROR');
                    }
                } else {
                    debug_to_file("허용되지 않는 파일 확장자: $ext", 'FILE_UPLOAD_ERROR');
                }
            }
        }

        debug_to_file("업로드된 파일 수: " . count($files_arr), 'FILE_UPLOAD');
        debug_to_file($files_arr, 'FILE_UPLOAD_PATHS');
    
        for($i=0;$i<count($files_arr);$i++){
            $file_sql = "insert into attachment
            set
            out_order_idx='".$out_order_idx."',
            filename='".mysqli_real_escape_string($dbcon, $files_arr[$i])."',
            admin_idx='".$admin_info['admin_idx']."',
            admin_name='".$admin_info['admin_name']."'
            ";
            
            debug_to_file("첨부 파일 DB 저장 SQL: $file_sql", 'FILE_UPLOAD');
            
            $in = mysqli_query($dbcon, $file_sql) or die(mysqli_error($dbcon));
            $file_id = mysqli_insert_id($dbcon);
            
            if($file_id) {
                debug_to_file("첨부 파일 DB 저장 성공, ID: $file_id", 'FILE_UPLOAD');
            } else {
                debug_to_file("첨부 파일 DB 저장 실패", 'FILE_UPLOAD_ERROR');
            }
        }

        debug_to_file("파일 업로드 처리 완료", 'FILE_UPLOAD');
    }


    debug_to_file("화훼 주문 처리 완료", 'FLOWER_ORDER');
    // mysqli_commit($dbcon);





}



 /////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////  상조용품 주문 별도 저장  ////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////


debug_to_file('상조용품 주문 처리 시작', 'SANGJO_ORDER');

if(count($arr['sangjo_product_list']) > 0 ){

    debug_to_file($arr['sangjo_product_list'], '상조용품 리스트');

    $sangjo_product_list = $arr['sangjo_product_list'];

    $p_names = $sangjo_product_list[0]['product_name']; //첫번째 상품만 대표상품으로 저장
    $total_order_count = 0;
    $total_kinds = count($sangjo_product_list);


    for ($i=0;$i<count($sangjo_product_list);$i++ )
    {
       $total_order_count += $sangjo_product_list[$i]['cnt'];
    }

    debug_to_file("상품명: $p_names, 종류: $total_kinds, 총 개수: $total_order_count", '상조용품 정보');

    $p_info = [$p_names,$total_kinds,$total_order_count];

    debug_to_file("sangjo_out_order_idx: $sangjo_out_order_idx", '상조용품 주문 인덱스');
    
    if($sangjo_out_order_idx > 0){ //수정하기 모드

        debug_to_file("수정 모드 진입", '상조용품 주문');
        $out_order_idx = $arr['out_order_idx'];


        if(count($arr['flower_product_list']) == 0 ){
           debug_to_file("화훼 상품 없음, 기존 화훼 주문 삭제", '상조용품 주문');
           $del = mysqli_query($dbcon, "delete from ".$db_flower.".out_order where out_order_idx='".$arr['out_order_idx']."' and out_order_part='화훼' ") or die(mysqli_error($dbcon));
        }

        //$up = mysqli_query($dbcon, "update out_order set out_order_status='주문취소' where out_order_idx='".$arr['out_order_idx']."' ") or die(mysqli_error($dbcon));
        $sql = get_sql($arr,$admin_info,"sangjo",$p_info ); ///get update query

        debug_to_file($sql, '상조용품 업데이트 SQL');


        $up = mysqli_query($dbcon,$sql) or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
        debug_to_file("업데이트 영향 받은 행 수: $up_num", '상조용품 업데이트 결과');
        
        if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
         //
        }else{ //쿼리실패
            mysqli_rollback($dbcon);
            debug_to_file("업데이트 실패", '상조용품 업데이트 오류');
            error_exit("저장 실패 E213");
            exit;
        }


 

    }else{
        $sql = get_sql($arr,$admin_info,"sangjo",$p_info );  //get insert query
        
        debug_to_file($sql, '상조용품 삽입 SQL');
        
        $in = mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));
        $out_order_idx = mysqli_insert_id($dbcon);
        debug_to_file("삽입된 주문 ID: $out_order_idx", '상조용품 삽입 결과');
        
        if($out_order_idx){//쿼리성공
        //
        }else{
            mysqli_rollback($dbcon);
            debug_to_file("삽입 실패", '상조용품 삽입 오류');
            error_exit("저장 실패 E214");
            exit;
        }
    }
    

    debug_to_file("상조용품 상세 정보 저장 시작", '상조용품 상세');
    for ($i=0;$i<count($sangjo_product_list);$i++ )
    {
        debug_to_file($sangjo_product_list[$i], "상조용품 상세 #$i");

        //** 상조 테이블의 가격체계 향후 통일 해야함. */
        $product_sql = "insert into ".$db_sangjo_new.".out_order_client_product 
            set
            flower_out_order_idx='".$out_order_idx."',
            consulting_idx='".$arr['consulting_idx']."',";
        
        debug_to_file($product_sql, "상조용품 상세 SQL #$i");


        $cnt =$sangjo_product_list[$i]['cnt'];

        $tcp = ((int)($sangjo_product_list[$i]['client_price']))*$cnt;
        $tcpt = ((int)($sangjo_product_list[$i]['client_price_tax']))*$cnt;
        $tcps = $tcp+$tcpt;



        $product_sql .= "client_product_idx='".$sangjo_product_list[$i]['client_product_idx']."',
            product_name='".$sangjo_product_list[$i]['product_name']."',
            order_count='".$sangjo_product_list[$i]['cnt']."',
            order_date='".$arr['order_date']."',


            client_price='".$sangjo_product_list[$i]['client_price']."',
            client_price_tax='".$sangjo_product_list[$i]['client_price_tax']."',
            client_price_sum='".$sangjo_product_list[$i]['client_price_sum']."',
            total_client_price='".$tcp."',
            total_client_price_tax='".$tcpt."',
            total_client_price_sum='".$tcps."',

            
            admin_idx='".$admin_info['admin_idx']."',
            admin_name='".$admin_info['admin_name']."'
            
        ";


        debug_to_file($product_sql, "상조용품 상세 SQL #$i");

        $in = mysqli_query($dbcon, $product_sql) or die(mysqli_error($dbcon));
        $in_id = mysqli_insert_id($dbcon);
        if($in_id){//쿼리성공
            debug_to_file("상조용품 상세 저장 성공, ID: $in_id", "상조용품 상세 #$i 결과");
            //
        }else{//쿼리실패
            mysqli_rollback($dbcon);
            debug_to_file("상조용품 상세 저장 실패", "상조용품 상세 #$i 오류");

            $result = array();
            $result['status'] = 0;
            $result['msg']="쿼리실행 오류";
        
            echo json_encode($result);
            exit;
        }
        
        
    }





    /*
    if(isset($_FILES['files']['name']) && count($arr['flower_product_list']) == 0){ //화훼내역이 없으므로 업로드해야함
      //상조에서는 원래부터 첨부파일처리가 없음.
    }
    */


    debug_to_file("상조용품 주문 처리 완료", "상조용품 주문");



}


debug_to_file("트랜잭션 커밋 시작", "TRANSACTION");

mysqli_commit($dbcon);

debug_to_file("트랜잭션 커밋 완료", "TRANSACTION");
    
$result = array();
$result['status'] = 1;
$result['data']=$data ;
$result['msg'] = "저장되었습니다";

debug_to_file($result, "최종 결과");

echo json_encode($result);
exit;




