<?php


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


if($_POST['mode'] == "manager_set"){


    if($_POST['sm_idx'] != "undefined" && $_POST['sm_idx'] != ""){ //담당자 수정


        if($_POST['new_manager_idx'] == "" || $_POST['new_manager_idx'] == "0"  || $_POST['new_manager_idx'] == "undefined" ){


            $del = mysqli_query($dbcon, "delete from sangjo_new.settlement_manager where sm_idx='".$_POST['sm_idx']."' ") or die(mysqli_error($dbcon));
            $del_num = mysqli_affected_rows($dbcon);
            if($del_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.


                $result = array();
                $result['status'] = 1;
                $result['data']['type'] = "11";
                $result['data']['sm_idx'] = $_POST['sm_idx'];

                $result['msg'] = "저장되었습니다.";

                echo json_encode($result);
                exit;

                

            }else{ //쿼리실패
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "변경 실패하였습니다.";
            
                echo json_encode($result);
                exit;
            }


        }else if($_POST['new_manager_idx'] > 0){
            $up = mysqli_query($dbcon, "update sangjo_new.settlement_manager
            set 
            manager_idx='".$_POST['new_manager_idx']."',
            update_admin_idx = '".$admin_info['admin_idx']."'

            where sm_idx='".$_POST['sm_idx']."'  ") or die(mysqli_error($dbcon));
            $up_num = mysqli_affected_rows($dbcon);
            if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                $result = array();
                $result['status'] = 1;
                $result['data']['type'] = "12"; //담당자 변경 성공
                $result['data']['manager_idx'] = $_POST['new_manager_idx'];
                $result['msg'] = "저장되었습니다.";

                echo json_encode($result);
                exit;
            }else{ //쿼리실패
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "실패하였습니다.";
            
                echo json_encode($result);
                exit;
            }
        }
    }else{ //담당자 신규지정(추가 아님, 기존입력된 담당자를 매칭하는 과정)

        if($_POST['category1_idx'] == "" || $_POST['category1_idx'] == "undefined"  || $_POST['consulting_idx'] == "" || $_POST['consulting_idx'] == "undefined" ){

            $result = array();
            $result['status'] = 0;
            $result['msg'] = "필수 정보가 누락되었습니다.1";
    
            echo json_encode($result);
            exit;
        }
    
        $in = mysqli_query($dbcon, "insert into sangjo_new.settlement_manager
            set 

            manager_idx='".$_POST['new_manager_idx']."',
            category1_idx='".$_POST['category1_idx']."',
            consulting_idx ='".$_POST['consulting_idx']."',
            regist_admin_idx = '".$admin_info['admin_idx']."'
        ") or die(mysqli_error($dbcon));
        $sm_idx = mysqli_insert_id($dbcon);
        if($sm_idx){//쿼리성공
                $result = array();
                $result['status'] = 1;
                $result['data']['type'] = "13"; //담당자 신규지정

                $result['data']['manager_idx'] = $_POST['new_manager_idx'];
                $result['data']['sm_idx'] = $sm_idx;
                $result['msg'] = "저장되었습니다.1";

                echo json_encode($result);
                exit;
        }else{//쿼리실패
            $result = array();
            $result['status'] = 0;
            $result['msg'] = "실패하였습니다.";
        
            echo json_encode($result);
            exit;
        }

    }



    

}else if($_POST['mode'] == "manager_new"){

    if($_POST['manager_name'] != "" && $_POST['manager_email'] != ""){
        $sql = "select * from consulting.manager 
        where consulting_idx='".$_POST['consulting_idx']."' and  (manager_name='".$_POST['manager_name']."' or manager_email='".$_POST['manager_email']."')
        ";

    }else if($_POST['manager_name'] != "" && $_POST['manager_email'] == ""){
        $sql = "select * from consulting.manager 
        where consulting_idx='".$_POST['consulting_idx']."' and  manager_name='".$_POST['manager_name']."'
        ";

    }else if($_POST['manager_name'] == "" && $_POST['manager_email'] != ""){
        $sql = "select * from consulting.manager 
        where consulting_idx='".$_POST['consulting_idx']."' and manager_email='".$_POST['manager_email']."'
        ";

    }else{
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "담당자명 또는 담당자 이메일주소가 필요합니다.";
    
        echo json_encode($result);
        exit;
    }


    $sel = mysqli_query($dbcon, $sql) or die(mysqli_error($dbcon));




    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num == 0) { //기존에 회사소속 매니저 목록에 없으면 추가하기
        $in = mysqli_query($dbcon, "insert into consulting.manager
        set consulting_idx='".$_POST['consulting_idx']."',
        manager_name='".$_POST['manager_name']."',
        manager_email='".$_POST['manager_email']."',
        settlement_sangjo=1
        
        
        ") or die(mysqli_error($dbcon));
        $manager_idx = mysqli_insert_id($dbcon);
        if($manager_idx){//쿼리성공

            $in = mysqli_query($dbcon, "insert into sangjo_new.settlement_manager
                set 
                manager_idx='".$manager_idx."',
                category1_idx='".$_POST['category1_idx']."',
                consulting_idx ='".$_POST['consulting_idx']."',
                regist_admin_idx = '".$admin_info['admin_idx']."'
                ") or die(mysqli_error($dbcon));
            $in_id = mysqli_insert_id($dbcon);
            if($in_id){//쿼리성공


                $sel3 = mysqli_query($dbcon, "select * from manager where consulting_idx='".$_POST['consulting_idx']."' order by manager_idx") or die(mysqli_error($dbcon));
                $sel3_num = mysqli_num_rows($sel3);
                
                $manager_list = array();
                if ($sel3_num > 0) {
                    while($data3 = mysqli_fetch_assoc($sel3)) {
                        array_push($manager_list,$data3);
                    }
                }


                $result = array();
                $result['status'] = 1;
                $result['data']['manager_idx'] = $manager_idx;
                $result['data']['sm_idx'] = $in_id;
                $result['data']['category1_manager_list']=$manager_list;

                $result['msg'] = "저장되었습니다.";

                echo json_encode($result);
                exit;
            }else{//쿼리실패
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "정산담당자 목록추가과정에서 오류가발생하였습니다.";
            
                echo json_encode($result);
                exit;
            }

          
        }else{//쿼리실패
            $result = array();
            $result['status'] = 0;
            $result['msg'] = "담당자 추가중 오류가 발생하였습니다.";
        
            echo json_encode($result);
            exit;
        }
        

    }else{
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "기존에 등록된 담당자 목록에 있습니다. 신규추가를 하지마시고, 기존목록에서 선택해주세요.";
    
        echo json_encode($result);
        exit;
    }


}else if($_POST['mode'] == "sdate"){



        
        if (intval($_POST['fs_idx']) > 0) {
            if($_POST['new_sdate'] == "미지정" || $_POST['new_sdate'] == ""  || $_POST['new_sdate'] == "0" || $_POST['new_sdate'] == "undefined" ){
                $del = mysqli_query($dbcon, "delete from sangjo_new.settlement_sdate where fs_idx='".$_POST['fs_idx']."' ") or die(mysqli_error($dbcon));
                $del_num = mysqli_affected_rows($dbcon);
                if($del_num > 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                    $result = array();
                    $result['status'] = 1;
                    $result['delete'] = "delete";
                    $result['msg'] = "저장되었습니다.1";
    
                    echo json_encode($result);
                    exit;
                }

                exit;


            }else{
                $up = mysqli_query($dbcon, "update sangjo_new.settlement_sdate 
                set 
                sdate='".$_POST['new_sdate']."',
                update_admin_idx = '".$admin_info['admin_idx']."'

                where  fs_idx='".$_POST['fs_idx']."'  ") or die(mysqli_error($dbcon));
                $up_num = mysqli_affected_rows($dbcon);
                if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                    $result = array();
                    $result['status'] = 1;
                    $result['sdate'] = $_POST['new_sdate'];

                    $result['msg'] = "저장되었습니다.2";

                    echo json_encode($result);
                    exit;
                }else{ //쿼리실패
                    $result = array();
                    $result['status'] = 0;
                    $result['msg'] = "실패하였습니다.";
                
                    echo json_encode($result);
                    exit;
                }
            }


            
        }else{ //fs_idx 값이 없음. 기존에 정산일을 지정한적 없다는 이야기.

            if($_POST['new_sdate'] == "미지정" || $_POST['new_sdate'] == ""  || $_POST['new_sdate'] == "0" || $_POST['new_sdate'] == "undefined" ){
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "정산일을 지정해주세요.";
            
                echo json_encode($result);
                exit;

            }else{

                $sel_sdate = mysqli_query($dbcon, "select * from sangjo_new.settlement_sdate where category1_idx='".$_POST['category1_idx']."' and
                consulting_idx ='".$_POST['consulting_idx']."'
                
                ") or die(mysqli_error($dbcon));
                $sel_sdate_num = mysqli_num_rows($sel_sdate);
                
                if ($sel_sdate_num == 0) {

                    $in = mysqli_query($dbcon, "insert into sangjo_new.settlement_sdate
                    set 

                    sdate='".$_POST['new_sdate']."',
                    category1_idx='".$_POST['category1_idx']."',
                    consulting_idx ='".$_POST['consulting_idx']."',
                    regist_admin_idx = '".$admin_info['admin_idx']."'
                    ") or die(mysqli_error($dbcon));
                    $fs_idx = mysqli_insert_id($dbcon);
                    if($fs_idx){//쿼리성공
                            $result = array();
                            $result['status'] = 1;
                            $result['sdate'] = $_POST['new_sdate'];
                            $result['fs_idx'] = $fs_idx;
                            $result['msg'] = "저장되었습니다.";

                            echo json_encode($result);
                            exit;
                    }else{//쿼리실패
                        $result = array();
                        $result['status'] = 0;
                        $result['msg'] = "실패하였습니다.";
                    
                        echo json_encode($result);
                        exit;
                    }
                }else{
                    $result = array();
                    $result['status'] = 0;
                    $result['msg'] = "해당카테고리의 정산일 정보가 이미 있습니다.";
                
                    echo json_encode($result);
                    exit;
                }


            }

        }




}else if($_POST['mode'] == "del"){ //담당자 매칭해제


    $del = mysqli_query($dbcon, "delete from sangjo_new.settlement_manager  where sm_idx='".$_POST['sm_idx']."' ") or die(mysqli_error($dbcon));
    $del_num = mysqli_affected_rows($dbcon);
    if($del_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
        $result = array();
        $result['status'] = 1;
        $result['msg'] = "담당자를 삭제하였습니다.";

        echo json_encode($result);
        exit;
    }else{
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "담당자 삭제실패하였습니다";

        echo json_encode($result);
        exit;
    }
}