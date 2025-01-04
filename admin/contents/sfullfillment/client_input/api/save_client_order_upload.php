<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_fullfillment.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


// print_r($_POST);
// exit;

if($_POST['data'] == ""){
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "입력데이터가 없습니다.";

    echo json_encode($result,JSON_UNESCAPED_UNICODE);
    exit;
}



$arr = json_decode($_POST['data'],true);


/* Start transaction */
mysqli_begin_transaction($dbcon);


if($arr['memo'] == "undefined"){
    $arr['memo'] = "";
}

$in = mysqli_query($dbcon, "insert into out_order
set 
consulting_idx='".$arr['consulting_idx']."',
to_place_name='".$arr['to_place_name']."',
to_address='".$arr['address']."',
to_name='".$arr['to_name']."',
to_hp='".$arr['hp']."',
delivery_memo='".$arr['memo']."',
order_date='".$arr['order_date']."',
total_client_price='".$arr['total_client_price']."',
total_client_price_tax='".$arr['total_client_price_tax']."',
total_client_price_sum='".$arr['total_client_price_sum']."',
admin_idx='".$admin_info['admin_idx']."',
admin_name='".$admin_info['admin_name']."'

") or die(mysqli_error($dbcon));


$out_order_idx = mysqli_insert_id($dbcon);
if($out_order_idx){//쿼리성공
    $client_product_list = $arr['client_product_list'];
    for ($i=0;$i<count($client_product_list);$i++ )
    {
        $cnt =$client_product_list[$i]['cnt'];

        $tcp = ((int)($client_product_list[$i]['client_price']))*$cnt;
        $tcpt = ((int)($client_product_list[$i]['client_price_tax']))*$cnt;
        $tcps = $tcp+$tcpt;

        $sel_oocp = mysqli_query($dbcon, "select b.category1_idx,b.t_category1_name from client_product a left join product b on a.product_idx=b.product_idx where a.client_product_idx='".$client_product_list[$i]['client_product_idx']."' ") or die(mysqli_error($dbcon));
        $sel_oocp_num = mysqli_num_rows($sel_oocp);
        
        if ($sel_oocp_num > 0) {
            $data_oocp = mysqli_fetch_assoc($sel_oocp);
        }

        $in = mysqli_query($dbcon, "insert into out_order_client_product 
            set
            out_order_idx='".$out_order_idx."',
            consulting_idx='".$arr['consulting_idx']."',
            category1_idx='".$data_oocp['category1_idx']."',
            t_category1_name='".$data_oocp['t_category1_name']."',
            client_product_idx='".$client_product_list[$i]['client_product_idx']."',
            order_date='".$arr['order_date']."',

            product_name='".$client_product_list[$i]['product_name']."',
            client_price='".$client_product_list[$i]['client_price']."',
            client_price_tax='".$client_product_list[$i]['client_price_tax']."',
            order_count='".$client_product_list[$i]['cnt']."',

            total_client_price='".$tcp."',
            total_client_price_tax='".$tcpt."',
            total_client_price_sum='".$tcps."',


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
        
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        
    }




    /*
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $name = $_FILES['attachFile']['name'];
        $size = $_FILES['attachFile']['size'];
         
         
        if(strlen($name))
        {       
    
    
            $folder_idx = "/upload/client_order/".$out_order_idx;
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
                $local_name = "/upload/client_order/".$out_order_idx."/".$new_name;
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
     */



    if(!isset($_FILES['files']['name'])){

        mysqli_commit($dbcon);


        $result = array();
        $result['status'] = 1;
        $result['data']=$data ;
        $result['msg'] = "저장되었습니다";
        echo json_encode($result);
        exit;
    
    
    
    }

    $countfiles = count($_FILES['files']['name']);



    $ymd = date("Y/m/d",time());



    $folder_idx = "/upload/client_order/".$ymd."/".$out_order_idx;
    $absolute_folder_idx = $_SERVER["DOCUMENT_ROOT"].$folder_idx;





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

        $in = mysqli_query($dbcon, "insert into attachment
        set
        out_order_idx='".$out_order_idx."',
        filename='".mysqli_real_escape_string($dbcon, $files_arr[$i])."',
        admin_idx='".$admin_info['admin_idx']."',
        admin_name='".$admin_info['admin_name']."'
        
        ") or die(mysqli_error($dbcon));
        
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
    $result['msg']="저장 실패 E21";

    echo json_encode($result);
    exit;
}



