<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


if($_POST['data'] == ""){
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "입력데이터가 없습니다.";

    echo json_encode($result);
    exit;
}



$arr = json_decode($_POST['data'],true);


/* Start transaction */
mysqli_begin_transaction($dbcon);






function get_sql($arr,$admin_info,$part,$p_info){

    if($part == "flower"){
        $out_order_part_sql = "out_order_part='화훼',";
     }else if($part == "sangjo" ){
        $out_order_part_sql = "out_order_part='상조',";
    }
    

    $ex = explode("(",$arr['r_date']);
    $r_date = $ex[0];
    $r_date = str_replace(" ","",str_replace("년","-",str_replace("월","-",str_replace("일","",$r_date))));
    

    $sql_base  = "insert into out_order
    set 
    consulting_idx='".$arr['consulting_idx']."',
    ".$out_order_part_sql."
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
    address1          ='".$arr['address1']."',
    address2          ='".$arr['address2']."',
    messageType       ='".$arr['messageType']."',
    eType             ='".$arr['eType']."',
    msgTitle          ='".$arr['msgTitle']."',
    msgTitle2         ='".$arr['msgTitle2']."',
    msgTitle3         ='".$arr['msgTitle3']."',
    sender_name       ='".$arr['sender_name']."',
    delivery_memo     ='".$arr['delivery_memo']."',
    paymentType       ='".$arr['paymentType']."',
    
    total_client_price='".$arr['total_client_price']."',
    total_client_price_tax='".$arr['total_client_price_tax']."',
    total_client_price_sum='".$arr['total_client_price_sum']."',
    admin_idx='".$admin_info['admin_idx']."',
    admin_name='".$admin_info['admin_name']."'
    
    ";
    
    return $sql_base;
}






if(count($arr['client_product_list']) > 0 ){

    
    $client_product_list = $arr['client_product_list'];

    $p_names = $client_product_list[0]['product_name']; //첫번째 상품만 대표상품으로 저장
    $total_order_count = 0;
    $total_kinds = count($client_product_list);

    $p_info = [$p_names,$total_kinds,$total_kinds];
    for ($i=0;$i<count($client_product_list);$i++ )
    {
       $total_order_count += $client_product_list[$i]['cnt'];
    }

    $sql = get_sql($arr,$admin_info,"flower",$p_info );
    $in = mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));
    $out_order_idx = mysqli_insert_id($dbcon);




    if($out_order_idx){//쿼리성공
        for ($i=0;$i<count($client_product_list);$i++ )
        {

            $in = mysqli_query($dbcon, "insert into out_order_client_product 
                set
                out_order_idx='".$out_order_idx."',
                consulting_idx='".$arr['consulting_idx']."',
                client_product_idx='".$client_product_list[$i]['client_product_idx']."',
                product_name='".$client_product_list[$i]['product_name']."',
                order_count='".$client_product_list[$i]['cnt']."',
                unit_price='".$client_product_list[$i]['unit_price']."',
                price_calcu='".$client_product_list[$i]['price_calcu']."',
                admin_idx='".$admin_info['admin_idx']."',
                admin_name='".$admin_info['admin_name']."'
                
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





        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
        {
            $name = $_FILES['attachFile']['name'];
            $size = $_FILES['attachFile']['size'];
            
            
            if(strlen($name))
            {       
        
        
                $folder_idx = "/upload/client_flower/".$out_order_idx;
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
        
        
                $tmp = $_FILES['attachFile']['tmp_name'];
                //파일복사
                if(move_uploaded_file($tmp, $absolute_path))
                {
        
                    $in = mysqli_query($dbcon, "insert into attachment 
                    set
                    out_order_idx='".$out_order_idx."',
                    filename='".mysqli_real_escape_string($dbcon, $local_name )."',
                    admin_idx='".$admin_info['admin_idx']."',
                    admin_name='".$admin_info['admin_name']."'
        
                    ") or die(mysqli_error($dbcon));
                    $attachment_idx = mysqli_insert_id($dbcon);
                    if($attachment_idx){//쿼리성공
        
        
                        $sel = mysqli_query($dbcon, "select * from attachment where attachment_idx='".$attachment_idx."' ") or die(mysqli_error($dbcon));
                        $sel_num = mysqli_num_rows($sel);
                        
                        if ($sel_num > 0) {
                            $data = mysqli_fetch_assoc($sel);
                        }


                        mysqli_commit($dbcon);

        
                        $result = array();
                        $result['status'] = 1;
                        $result['data']=$data ;
                        $result['msg'] = "저장되었습니다";
                        echo json_encode($result);
                        exit;
                    }else{//쿼리실패

                        mysqli_rollback($dbcon);

                        $result = array();
                        $result['status'] = 0;
                        $result['msg'] = "DB입력실패";
                
                        echo json_encode($result);
                        exit;
                    }
                    
                
        
        
                }
                else
                {

                    mysqli_rollback($dbcon);

                    $result = array();
                    $result['status'] = 0;
                    $result['msg'] = "저장실패";
            
                    echo json_encode($result);
                    exit;
                }
                        
            }
            // else

            //     mysqli_rollback($dbcon);

            //     $result = array();
            //     $result['status'] = 0;
            //     $result['msg'] = "첨부파일을 선택해주세요";
        
            //     echo json_encode($result);
            //     exit;
        
        }
        




        
    }else{//쿼리실패

        mysqli_rollback($dbcon);

        $result = array();
        $result['status'] = 0;
        $result['msg']="저장 실패 E21";

        echo json_encode($result);
        exit;
    }





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

    $p_info = [$p_names,$total_kinds,$total_kinds];


    $sql = get_sql($arr,$admin_info,"sangjo",$p_info);
    $in = mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));
    $out_order_idx = mysqli_insert_id($dbcon);
    if($out_order_idx){//쿼리성공
        for ($i=0;$i<count($sangjo_product_list);$i++ )
        {

            $in = mysqli_query($dbcon, "insert into ".$db_sangjo_new.".out_order_client_product 
                set
                flower_out_order_idx='".$out_order_idx."',
                consulting_idx='".$arr['consulting_idx']."',
                product_idx='".$sangjo_product_list[$i]['product_idx']."',
                product_name='".$sangjo_product_list[$i]['product_name']."',
                order_count='".$sangjo_product_list[$i]['cnt']."',
                unit_price='".$sangjo_product_list[$i]['unit_price']."',
                price_calcu='".$sangjo_product_list[$i]['price_calcu']."',
                admin_idx='".$admin_info['admin_idx']."',
                admin_name='".$admin_info['admin_name']."'
                
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
 
 }
 










mysqli_commit($dbcon);

    
$result = array();
$result['status'] = 1;
$result['data']=$data ;
$result['msg'] = "저장되었습니다";
echo json_encode($result);
exit;




