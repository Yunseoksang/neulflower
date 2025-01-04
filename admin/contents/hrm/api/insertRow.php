<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();



$post_data = file_get_contents("php://input");

$arr = json_decode($post_data , true); //배열



if($arr['mode'] == "input"){


    /** 필수 요소 누락 체크 시작 */
    if($arr['name'] == ""){
        error_json(0,"이름은 필수입니다.");
        exit;
    }



    /** 필수 요소 누락 체크 끝 */




    if($arr['tel'] != ""){
        /** 키데이터 중복여부 체크 */
        $sel = mysqli_query($dbcon, "select * from hrm where tel='".$arr['tel']."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);

        if ($sel_num > 0) {
        error_json(0,"이미 등록되어있는 전화번호 입니다.");
        exit;
        }
        /** 키데이터 중복여부 체크 시작 끝 */


    }



    //insert 문이 2개 이상 일때 트랜젝션 삽입 권장.
    mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));


    $query_insert = "insert into hrm set 

    name                    = '".$arr['name']."',

    jisa_idx                = '".$arr['t_jisa']."',
    organization_idx        = '".$arr['t_organization']."',
    office_idx              = '".$arr['t_office']."',

    job_position            = '".$arr['job_position']."',
    job_grade               = '".$arr['job_grade']."',
    work_type               = '".$arr['work_type']."',
    work_time               = '".$arr['work_time']."',
    date_of_employment      = '".$arr['date_of_employment']."',
    date_of_resignation     = '".$arr['date_of_resignation']."',
    disabled_type           = '".$arr['disabled_type']."',
    disabled_grade          = '".$arr['disabled_grade']."',
    tel                     = '".$arr['tel']."',
    tel_guardians           = '".$arr['tel_guardians']."',
    affiliate_organization  = '".$arr['affiliate_organization']."',
    address                 = '".$arr['address']."',
    memo                    = '".$arr['memo']."',

    admin_idx      = '".$admin_info['admin_idx']."',
    admin_name     = '".$admin_info['admin_name']."'

    ";
    $in = mysqli_query($dbcon, $query_insert) or die(mysqli_error($dbcon));
    $hrm_idx = mysqli_insert_id($dbcon);
    if($hrm_idx){//쿼리성공
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

    



    mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));




    $result = array();
    $result['status'] = 1;
    $result['data']['hrm_idx']=$hrm_idx;
    $result['msg']="저장되었습니다.";

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
    if($arr['name'] == ""){
        error_json(0,"이름은 필수입니다.");
        exit;
    }
    if($arr['hrm_idx'] > 0){

    }else{
        error_json(0,"IDX 정보 에러입니다.");
        exit;
    }



    /** 필수 요소 누락 체크 끝 */




    if($arr['tel'] != ""){
        /** 키데이터 중복여부 체크 */
        $sel = mysqli_query($dbcon, "select * from hrm where tel='".$arr['tel']."' and hrm_idx != '".$arr['hrm_idx']."'  ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);

        if ($sel_num > 0) {
        error_json(0,"이미 등록되어있는 전화번호 입니다.");
        exit;
        }
        /** 키데이터 중복여부 체크 시작 끝 */


    }




    //insert 문이 2개 이상 일때 트랜젝션 삽입 권장.
    mysqli_query($dbcon, "START TRANSACTION") or die(mysqli_error($dbcon));





    $up = mysqli_query($dbcon, "update hrm set 

    name                    = '".$arr['name']."',
    jisa_idx                = '".$arr['t_jisa']."',
    organization_idx        = '".$arr['t_organization']."',
    office_idx              = '".$arr['t_office']."',
    job_position            = '".$arr['job_position']."',
    job_grade               = '".$arr['job_grade']."',
    work_type               = '".$arr['work_type']."',
    work_time               = '".$arr['work_time']."',
    date_of_employment      = '".$arr['date_of_employment']."',
    date_of_resignation     = '".$arr['date_of_resignation']."',
    disabled_type           = '".$arr['disabled_type']."',
    disabled_grade          = '".$arr['disabled_grade']."',
    tel                     = '".$arr['tel']."',
    tel_guardians           = '".$arr['tel_guardians']."',
    affiliate_organization  = '".$arr['affiliate_organization']."',
    address                 = '".$arr['address']."',
    memo                    = '".$arr['memo']."',


    update_admin_idx      = '".$admin_info['admin_idx']."',
    update_admin_name     = '".$admin_info['admin_name']."'
    
    where hrm_idx='".$arr['hrm_idx']."'") or die(mysqli_error($dbcon));
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



 

    mysqli_query($dbcon, "COMMIT") or die(mysqli_error($dbcon));




    $result = array();
    $result['status'] = 1;
    $result['data']['hrm_idx']=$arr['hrm_idx'];

    $result['msg'] = "저장되었습니다.";


    echo json_encode($result);


}

