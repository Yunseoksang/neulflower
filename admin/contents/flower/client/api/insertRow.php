<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



$post_data = file_get_contents("php://input");

$arr = json_decode($post_data , true); //배열



if($arr['mode'] == "input"){


    /** 필수 요소 누락 체크 시작 */
    if($arr['company_name'] == ""){
        error_json(0,"회사명은 필수입니다.");
        exit;
    }



    /** 필수 요소 누락 체크 끝 */



    /** 키데이터 중복여부 체크 */
    $sel = mysqli_query($dbcon, "select * from client where company_name='".$arr['company_name']."' ") or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);

    if ($sel_num > 0) {
    error_json(0,"이미 등록되어있는 회사명 입니다.");
    exit;
    }
    /** 키데이터 중복여부 체크 시작 끝 */






    // //폼 요소 중에서 key_table에 insert 되는 칼럼이 아닌경우를 제외하고 쿼리문 완성하기.
    // $set_sql = "";
    // foreach($_REQUEST as $key => $val) {

    //     //칼럼명이 아닌것은 제외
    //     if($key == "table_name"){ //칼럼목록이 아닌 테이블명 지명용도 라서 제외
    //         $table_name = $val;
    //         continue;
    //     }
    //     if($key == "key_column_name"){  //칼럼목록이 아닌 키칼럼 지명용도 라서 제외
    //         $key_column_name = $val;
    //         continue;
    //     }

    //     if($key == "mode"){
    //         continue;
    //     }



    //     //칼럼명과 value 를 inset 쿼리에 세팅
    //     if($val != "" && $val != "undefined" && $val != null){
    //         if($set_sql == ""){
    //             $set_sql = " ".$key."='".mysqli_real_escape_string($dbcon,$val)."'";
    //         }else{
    //             $set_sql .= ", ".$key."='".mysqli_real_escape_string($dbcon,$val)."'";
    //         }
    //     }


    // }
    // $set_sql .= ", admin_idx='".$admin_info['admin_idx']."',admin_name='".$admin_info['admin_name']."'";



    //insert 문이 2개 이상 일때 트랜젝션 삽입 권장.
    mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));


    $query_insert = "insert into client set 
    company_name   = '".$arr['company_name']."',
    employees      = '".$arr['employees']."',
    employment_fee = '".$arr['employment_fee']."',
    trading_items  = '".$arr['trading_items']."',
    tradeable_items= '".$arr['tradeable_items']."',
    biz_num        = '".$arr['biz_num']."',
    corp_num       = '".$arr['corp_num']."',
    ceo_name       = '".$arr['ceo_name']."',
    tel            = '".$arr['tel']."',
    fax            = '".$arr['fax']."',
    biz_part       = '".$arr['biz_part']."',
    biz_type       = '".$arr['biz_type']."',
    address        = '".$arr['address']."',
    homepage       = '".$arr['homepage']."',
    memo           = '".$arr['memo']."',
    admin_idx      = '".$admin_info['admin_idx']."',
    admin_name     = '".$admin_info['admin_name']."'

    ";
    $in = mysqli_query($dbcon, $query_insert) or die(mysqli_error($dbcon));
    $consulting_idx = mysqli_insert_id($dbcon);
    if($consulting_idx){//쿼리성공
    //
    }else{//쿼리실패
        mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

        $result = array();
        $result['status'] = 0;
        $result['msg'] = "쿼리 입력오류:1 입니다.";
        //$result['data']['query_insert']=$query_insert;
        
        
        echo json_encode($result);
        
        exit;
    }

    $manager_list = $arr['manager_list'];
    for ($i=0;$i<count($manager_list);$i++ )
    {
     
        $in = mysqli_query($dbcon, "insert into manager set
        consulting_idx    = '".$consulting_idx."',
        manager_name      = '".$manager_list[$i]['manager_name']."',
        manager_department= '".$manager_list[$i]['manager_department']."',
        manager_position  = '".$manager_list[$i]['manager_position']."',
        manager_email     = '".$manager_list[$i]['manager_email']."',
        manager_tel       = '".$manager_list[$i]['manager_tel']."',
        manager_hp        = '".$manager_list[$i]['manager_hp']."',
        admin_idx         = '".$admin_info['admin_idx']."',
        admin_name        = '".$admin_info['admin_name']."'
        
        ") or die(mysqli_error($dbcon));
        $manager_idx = mysqli_insert_id($dbcon);
        if($manager_idx){//쿼리성공
           //
        }else{//쿼리실패
            //
            mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

           
            $result = array();
            $result['status'] = 0;
            $result['msg'] = "쿼리 입력오류:2 입니다.";
            //$result['data']['query_insert']=$query_insert;
            
            
            echo json_encode($result);
            
           exit;
           break;
        }
    
    }





    mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));




    $result = array();
    $result['status'] = 1;
    $result['data'][$key_column_name]=$in_id;
    //$result['data']['query_insert']=$query_insert;


    echo json_encode($result);


///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

}else if($arr['mode'] == "edit"){

    /** 필수 요소 누락 체크 시작 */
    if($arr['company_name'] == ""){
        error_json(0,"회사명은 필수입니다.");
        exit;
    }
    if($arr['consulting_idx'] > 0){

    }else{
        error_json(0,"IDX 정보 에러입니다.");
        exit;
    }



    /** 필수 요소 누락 체크 끝 */



    /** 키데이터 중복여부 체크 */
    $sel = mysqli_query($dbcon, "select * from client where company_name='".$arr['company_name']."' and consulting_idx != '".$arr['consulting_idx']."' ") or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);

    if ($sel_num > 0) {
    error_json(0,"이미 등록되어있는 회사명 입니다.");
       exit;
    }
    /** 키데이터 중복여부 체크 시작 끝 */






    //insert 문이 2개 이상 일때 트랜젝션 삽입 권장.
    mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));





    $up = mysqli_query($dbcon, "update client set 
    company_name   = '".$arr['company_name']."',
    employees      = '".$arr['employees']."',
    employment_fee = '".$arr['employment_fee']."',
    trading_items  = '".$arr['trading_items']."',
    tradeable_items= '".$arr['tradeable_items']."',
    biz_num        = '".$arr['biz_num']."',
    corp_num       = '".$arr['corp_num']."',
    ceo_name       = '".$arr['ceo_name']."',
    tel            = '".$arr['tel']."',
    fax            = '".$arr['fax']."',
    biz_part       = '".$arr['biz_part']."',
    biz_type       = '".$arr['biz_type']."',
    address        = '".$arr['address']."',
    homepage       = '".$arr['homepage']."',
    memo           = '".$arr['memo']."',
    update_admin_idx      = '".$admin_info['admin_idx']."',
    update_admin_name     = '".$admin_info['admin_name']."'
    
    where consulting_idx='".$arr['consulting_idx']."'") or die(mysqli_error($dbcon));
    $up_num = mysqli_affected_rows($dbcon);
    if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
     
    }else{ //쿼리실패
        mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));

        $result = array();
        $result['status'] = 0;
        $result['msg'] = "쿼리 실행오류:3 입니다.";
        //$result['data']['query_insert']=$query_insert;
        
        
        echo json_encode($result);
        
        exit;
    }



    $manager_list = $arr['manager_list'];
    for ($i=0;$i<count($manager_list);$i++ )
    {

        if($manager_list[$i]['manager_idx'] > 0){

            $up = mysqli_query($dbcon, "update manager set
            manager_name      = '".$manager_list[$i]['manager_name']."',
            manager_department= '".$manager_list[$i]['manager_department']."',
            manager_position  = '".$manager_list[$i]['manager_position']."',
            manager_email     = '".$manager_list[$i]['manager_email']."',
            manager_tel       = '".$manager_list[$i]['manager_tel']."',
            manager_hp        = '".$manager_list[$i]['manager_hp']."',
            admin_idx         = '".$admin_info['admin_idx']."',
            admin_name        = '".$admin_info['admin_name']."'

            where manager_idx='".$manager_list[$i]['manager_idx']."'
            
            ") or die(mysqli_error($dbcon));


            $up_num = mysqli_affected_rows($dbcon);
            if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
             //
            }else{ //쿼리실패
                mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));
    
               
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "쿼리 실행오류:4 입니다.";
                //$result['data']['query_insert']=$query_insert;
                
                
                echo json_encode($result);
                
               exit;
               break;
            }

        }else{

            $in = mysqli_query($dbcon, "insert into manager set
            consulting_idx    = '".$arr['consulting_idx']."',
            manager_name      = '".$manager_list[$i]['manager_name']."',
            manager_department= '".$manager_list[$i]['manager_department']."',
            manager_position  = '".$manager_list[$i]['manager_position']."',
            manager_email     = '".$manager_list[$i]['manager_email']."',
            manager_tel       = '".$manager_list[$i]['manager_tel']."',
            manager_hp        = '".$manager_list[$i]['manager_hp']."',
            admin_idx         = '".$admin_info['admin_idx']."',
            admin_name        = '".$admin_info['admin_name']."'
            
            ") or die(mysqli_error($dbcon));
            $manager_idx = mysqli_insert_id($dbcon);
            if($manager_idx){//쿼리성공
               //
            }else{//쿼리실패
                //
                mysqli_query($dbcon, "ROLLBACK") or die(mysqli_error($dbcon));
    
               
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "쿼리 실행오류:5 입니다.";
                //$result['data']['query_insert']=$query_insert;
                
                
                echo json_encode($result);
                
               exit;
               break;
            }
        }
     
    
    }





    mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));




    $result = array();
    $result['status'] = 1;
    $result['msg'] = "저장되었습니다.";


    echo json_encode($result);


}
