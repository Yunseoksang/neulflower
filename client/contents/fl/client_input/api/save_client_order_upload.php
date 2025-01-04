<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
manager_check_ajax();


if($_POST['data'] == ""){
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "입력데이터가 없습니다.";

    echo json_encode($result);
    exit;
}



function getArea($zipNo,$address1){

    if($zipNo != ""){
        //https://bustar.tistory.com/237  우편번호로 지역 분류
        $code = (int) substr($zipNo,0,2);

              if($code >= 1 && $code <= 9){$local_area  = "서울";}
        else if($code >= 10 && $code <=20 ){$local_area = "경기";}
        else if($code >= 21 && $code <=23 ){$local_area = "인천";}
        else if($code >= 24 && $code <= 26){$local_area = "강원";}
        else if($code >= 27 && $code <=29 ){$local_area = "충북";}
        else if($code >= 30 ){$local_area               = "세종";}
        else if($code >= 31 && $code <= 33){$local_area = "충남";}
        else if($code >= 34 && $code <=35 ){$local_area = "대전";}
        else if($code >=36 && $code <=40 ){$local_area  = "경북";}
        else if($code >= 41 && $code <=43 ){$local_area = "대구";}
        else if($code >= 44 && $code <= 45){$local_area = "울산";}
        else if($code >= 46 && $code <= 49){$local_area = "부산";}
        else if($code >= 50 && $code <=53 ){$local_area = "경남";}
        else if($code >= 54 && $code <= 56){$local_area = "전북";}
        else if($code >= 57 && $code <=60 ){$local_area = "전남";}
        else if($code >= 61 && $code <= 62){$local_area = "광주";}
        else if($code = 63 ){$local_area                = "제주";}

        return $local_area;
       
    }else{//우편번호를 안 넣었으면.

        $address1 = str_replace(" ","",$address1);
             if(strpos($address1,"서울") || strpos($address1,"서울")){$local_area = "서울";}
        else if(strpos($address1,"경기") || strpos($address1,"경기")){$local_area = "경기";}
        else if(strpos($address1,"인천") || strpos($address1,"인천")){$local_area = "인천";}
        else if(strpos($address1,"강원") || strpos($address1,"강원")){$local_area = "강원";}
        else if(strpos($address1,"충북") || strpos($address1,"충청북")){$local_area = "충북";}
        else if(strpos($address1,"세종") || strpos($address1,"세종")){$local_area = "세종";}
        else if(strpos($address1,"충남") || strpos($address1,"충청남")){$local_area = "충남";}
        else if(strpos($address1,"대전") || strpos($address1,"대전")){$local_area = "대전";}
        else if(strpos($address1,"경북") || strpos($address1,"경상북")){$local_area = "경북";}
        else if(strpos($address1,"대구") || strpos($address1,"대구")){$local_area = "대구";}
        else if(strpos($address1,"울산") || strpos($address1,"울산")){$local_area = "울산";}
        else if(strpos($address1,"부산") || strpos($address1,"부산")){$local_area = "부산";}
        else if(strpos($address1,"경남") || strpos($address1,"경상남")){$local_area = "경남";}
        else if(strpos($address1,"전북") || strpos($address1,"전라북")){$local_area = "전북";}
        else if(strpos($address1,"전남") || strpos($address1,"전라남")){$local_area = "전남";}
        else if(strpos($address1,"광주") || strpos($address1,"광주")){$local_area = "광주";}
        else if(strpos($address1,"제주") || strpos($address1,"제주")){$local_area = "제주";}


        return $local_area;

    }



}







$arr = json_decode($_POST['data'],true);


/* Start transaction */
mysqli_begin_transaction($dbcon);







function get_sql($arr,$manager_info,$part,$p_info){
    global $db_flower,$dbcon;

    if($part == "flower"){
        $out_order_part_sql = "out_order_part='화훼',";
        $out_order_part_sql_n = "out_order_part='화훼'";

        $total_client_price = $arr['total_flower_price'] ;
        $total_client_price_tax = $arr['total_flower_price_tax'] ;
        $total_client_price_sum = $arr['total_flower_price_sum_calcu'];



     }else if($part == "sangjo" ){
        $out_order_part_sql = "out_order_part='상조',";
        $out_order_part_sql_n = "out_order_part='상조'";

        $total_client_price           = $arr['total_sangjo_price'] ;
        $total_client_price_tax       = $arr['total_sangjo_price_tax'] ;
        $total_client_price_sum = $arr['total_sangjo_price_sum_calcu'];
    }





    $ex = explode("(",$arr['r_date']);
    $r_date = $ex[0];
    $r_date = str_replace(" ","",str_replace("년","-",str_replace("월","-",str_replace("일","",$r_date))));



    $sql1 = "insert into flower.out_order ";


   
    $local_area = getArea($arr['zipNo'],$arr['address1']);


    $sql_base  = $sql1."
    set 
    consulting_idx='".$arr['consulting_idx']."',
    ".$out_order_part_sql."
    manager_idx='".$manager_info['manager_idx']."',
    order_product_title='".$p_info[0]."',
    total_order_kinds ='".$p_info[1]."',
    total_order_count ='".$p_info[2]."',

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
    total_client_price_sum='".$total_client_price_sum."'

    ";
    
    return $sql_base;
}




if(count($arr['flower_product_list']) > 0 ){
    
    $flower_product_list = $arr['flower_product_list'];

    $p_names = $flower_product_list[0]['product_name']; //첫번째 상품만 대표상품으로 저장
    $total_order_count = 0;
    $total_kinds = count($flower_product_list);

    for ($i=0;$i<count($flower_product_list);$i++ )
    {
       $total_order_count += $flower_product_list[$i]['cnt'];
    }
    $p_info = [$p_names,$total_kinds,$total_order_count];




    $sql = get_sql($arr,$manager_info,"flower",$p_info );  //get insert query
    $in = mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));
    $out_order_idx = mysqli_insert_id($dbcon);
    if($out_order_idx){//쿼리성공
    //
    }else{
        mysqli_rollback($dbcon);
        error_exit("저장 실패 E212");
        exit;
    }






    for ($i=0;$i<count($flower_product_list);$i++ )
    {

        $cnt =$flower_product_list[$i]['cnt'];

        $tcp = ((int)($flower_product_list[$i]['client_price']))*$cnt;
        $tcpt = ((int)($flower_product_list[$i]['client_price_tax']))*$cnt;
        $tcps = $tcp+$tcpt;

        $in = mysqli_query($dbcon, "insert into flower.out_order_client_product 
            set
            out_order_idx='".$out_order_idx."',
            consulting_idx='".$arr['consulting_idx']."',
            client_product_idx='".$flower_product_list[$i]['client_product_idx']."',
            product_name='".$flower_product_list[$i]['product_name']."',
            
            client_price='".$flower_product_list[$i]['client_price']."',
            client_price_tax='".$flower_product_list[$i]['client_price_tax']."',
            client_price_sum='".$flower_product_list[$i]['client_price_sum']."',
            order_count='".$flower_product_list[$i]['cnt']."',

            total_client_price='".$tcp."',
            total_client_price_tax='".$tcpt."',
            total_client_price_sum='".$tcps."'
            
        ") or die(mysqli_error($dbcon));
        $in_id = mysqli_insert_id($dbcon);
        if($in_id){//쿼리성공
            //
        }else{//쿼리실패
            mysqli_rollback($dbcon);
            error_exit("저장 실패 E215");
            exit;
        }
        
        
    }





    if(isset($_FILES['files']['name'])){


        $countfiles = count($_FILES['files']['name']);
        $ymd = date("Y/m/d",time());
    
        $folder_idx = "/upload/client_flower/".$ymd."/".$out_order_idx;
        $absolute_folder_idx = $_SERVER["DOCUMENT_ROOT"]. $folder_idx;
    
        if (!file_exists($absolute_folder_idx)) {
            mkdir($absolute_folder_idx, 0777, true);
        }
    
    
    
        $local_name = $folder_idx."/".$name;
        $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;
    
        //중복파일 존재하면
        if(file_exists($absolute_path)){ 
            list($txt, $ext) = explode(".", $name);
            $new_name = $txt."_".time().".".$ext;
            $local_name = $folder_idx."/".$new_name;
            $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;
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
    
                // Get extension
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
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
                        list($txt, $ext) = explode(".", $filename);
                        $new_name = $txt."_".time().".".$ext;
                        $local_name = $folder_idx."/".$new_name;
                        $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;
                    }
    
    
    
                    // Upload file
                    if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$absolute_path)){
                        $files_arr[] = str_replace($_SERVER["DOCUMENT_ROOT"],"",$absolute_path); //저장된 파일주소 배열로 담기
                    }
                }
            }
        }

    
        for($i=0;$i<count($files_arr);$i++){
    
            $in = mysqli_query($dbcon, "insert into flower.attachment
            set
            out_order_idx='".$out_order_idx."',
            filename='".mysqli_real_escape_string($dbcon, $files_arr[$i])."'

            
            ") or die(mysqli_error($dbcon));
            
        }


    }


    // mysqli_commit($dbcon);


    // $result = array();
    // $result['status'] = 1;
    // $result['data']=$data ;
    // $result['msg'] = "저장되었습니다";
    // echo json_encode($result);
    // exit;





 }



 /////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////  상조용품 주문 별도 저장  ////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////
 /////////////////////////////////////////////////////////////////////////////////////////////////////////



 if(count($arr['sangjo_product_list']) > 0 ){




    $sangjo_product_list = $arr['sangjo_product_list'];

    $p_names = $sangjo_product_list[0]['product_name']; //첫번째 상품만 대표상품으로 저장
    $total_order_count = 0;
    $total_kinds = count($sangjo_product_list);


    for ($i=0;$i<count($sangjo_product_list);$i++ )
    {
       $total_order_count += $sangjo_product_list[$i]['cnt'];
    }


    $p_info = [$p_names,$total_kinds,$total_order_count];


    $sql = get_sql($arr,$manager_info,"sangjo",$p_info );  //get insert query
    $in = mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));
    $out_order_idx = mysqli_insert_id($dbcon);
    if($out_order_idx){//쿼리성공
    //
    }else{
        mysqli_rollback($dbcon);
        error_exit("저장 실패 E214");
        exit;
    }




    for ($i=0;$i<count($sangjo_product_list);$i++ )
    {
     

        $in = mysqli_query($dbcon, "insert into sangjo.out_order_client_product 
            set
            flower_out_order_idx='".$out_order_idx."',
            consulting_idx='".$arr['consulting_idx']."',
            product_idx='".$sangjo_product_list[$i]['product_idx']."',
            product_name='".$sangjo_product_list[$i]['product_name']."',
            order_count='".$sangjo_product_list[$i]['cnt']."',
            unit_price='".$sangjo_product_list[$i]['client_price_sum']."',
            price_calcu='".$sangjo_product_list[$i]['client_price_sum_calcu']."'

            
        ") or die(mysqli_error($dbcon));
        $in_id = mysqli_insert_id($dbcon);
        if($in_id){//쿼리성공
            //
        }else{//쿼리실패
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="쿼리실행 오류";
        
            echo json_encode($result);
            exit;
        }
        
        
    }






 }





mysqli_commit($dbcon);

    
$result = array();
$result['status'] = 1;
$result['data']=$data ;
$result['msg'] = "저장되었습니다";
echo json_encode($result);
exit;




