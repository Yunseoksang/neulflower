<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();





if($_POST['mode'] == "get"){


    $sel = mysqli_query($dbcon, "
    
    select a.start_page,b.* from (
    select * 
    
    from ".$db_admin.".admin_list 
    
    
    where admin_idx='".$_POST['admin_idx']."' ) a 
    
    left join ".$db_admin.".admin_permission b 
    on a.admin_idx=b.admin_idx
    
    
    ") or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);

    if ($sel_num > 0) {
        $data = mysqli_fetch_assoc($sel);

        $result = array();
        $result['status'] = 1;
        $result['data']=$data;
        $result['msg']="성공";


        echo json_encode($result,JSON_UNESCAPED_UNICODE);
        exit;

    
    }else{
        
        $result = array();
        $result['status'] = 0;
        $result['msg']="관리자 권한 정보가 없습니다";


        echo json_encode($result,JSON_UNESCAPED_UNICODE);
        exit;

    }





}else if($_POST['mode'] == "edit"){

    $set_sql = "";
    if($_POST['permission_super']        != ""){  $set_sql .= "pm_super               ='".$_POST['permission_super']."',";        }else{  $set_sql .= "pm_super               =NULL,";}
    if($_POST['permission_sangjo']       != ""){  $set_sql .= "pm_sangjo              ='".$_POST['permission_sangjo']."',";       }else{  $set_sql .= "pm_sangjo              =NULL,";}
    if($_POST['permission_fullfillment'] != ""){  $set_sql .= "pm_fullfillment        ='".$_POST['permission_fullfillment']."',"; }else{  $set_sql .= "pm_fullfillment        =NULL,";}
    if($_POST['permission_flower']       != ""){  $set_sql .= "pm_flower              ='".$_POST['permission_flower']."',";       }else{  $set_sql .= "pm_flower              =NULL,";}
    if($_POST['permission_consulting']   != ""){  $set_sql .= "pm_consulting          ='".$_POST['permission_consulting']."',";   }else{  $set_sql .= "pm_consulting          =NULL,";}
    if($_POST['permission_statistics']   != ""){  $set_sql .= "pm_statistics          ='".$_POST['permission_statistics']."',";   }else{  $set_sql .= "pm_statistics          =NULL,";}
    if($_POST['storage_super']           != ""){  $set_sql .= "storage_super          ='".$_POST['storage_super']."',";           }else{  $set_sql .= "storage_super          =NULL,";}
    if($_POST['storage_sangjo']          != ""){  $set_sql .= "storage_sangjo         ='".$_POST['storage_sangjo']."',";          }else{  $set_sql .= "storage_sangjo         =NULL,";}
    if($_POST['storage_fullfillment']    != ""){  $set_sql .= "storage_fullfillment   ='".$_POST['storage_fullfillment']."',";    }else{  $set_sql .= "storage_fullfillment   =NULL,";}
    if($_POST['storage_flower']          != ""){  $set_sql .= "storage_flower         ='".$_POST['storage_flower']."',";          }else{  $set_sql .= "storage_flower         =NULL,";}
    if($_POST['storage_consulting']      != ""){  $set_sql .= "storage_consulting     ='".$_POST['storage_consulting']."',";      }else{  $set_sql .= "storage_consulting     =NULL,";}
    if($_POST['storage_statistics']      != ""){  $set_sql .= "storage_statistics     ='".$_POST['storage_statistics']."'";       }else{  $set_sql .= "storage_statistics     =NULL" ;}
    
    if($_POST['start_page']              != ""){  $set_start_sql = "start_page       ='".$_POST['start_page']."'";                }else{  $set_start_sql = "start_page        =NULL";}


    $sel = mysqli_query($dbcon, "select * from ".$db_admin.".admin_permission where admin_idx='".$_POST['admin_idx']."' ") or die(mysqli_error($dbcon));
    $sel_num = mysqli_num_rows($sel);
    
    if ($sel_num > 0) { //기존에 admin 권한정보가 있으면  update
        $up = mysqli_query($dbcon, "update ".$db_admin.".admin_permission 
            set ".$set_sql."
            where admin_idx='".$_POST['admin_idx']."' ") or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
        if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
            
            $up1 = mysqli_query($dbcon, "update ".$db_admin.".admin_list set ".$set_start_sql." where admin_idx='".$_POST['admin_idx']."' ") or die(mysqli_error($dbcon));
            $up1_num = mysqli_affected_rows($dbcon);
            if($up1_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                $result = array();
                $result['status'] = 1;
                $result['msg']="성공";
                echo json_encode($result,JSON_UNESCAPED_UNICODE);
                exit;
            
            }else{ //쿼리실패
                $result = array();
                $result['status'] = 0;
                $result['msg']="업데이트 실패";
                echo json_encode($result,JSON_UNESCAPED_UNICODE);
                exit;
            
            }

        }else{ //쿼리실패
            $result = array();
            $result['status'] = 0;
            $result['msg']="업데이트 실패";
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            exit;

        }
    
    }else{ //기존에 admin 권한정보가 없으면 insert
          

        $set_sql = "admin_idx='".$_POST['admin_idx']."',".$set_sql;
        $in = mysqli_query($dbcon, "insert into ".$db_admin.".admin_permission
        
        set
        ".$set_sql."

        ") or die(mysqli_error($dbcon));
        $in_id = mysqli_insert_id($dbcon);
        if($in_id){//쿼리성공
         
            $up1 = mysqli_query($dbcon, "update ".$db_admin.".admin_list set ".$set_start_sql." where admin_idx='".$_POST['admin_idx']."' ") or die(mysqli_error($dbcon));
            $up1_num = mysqli_affected_rows($dbcon);
            if($up1_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                $result = array();
                $result['status'] = 1;
                $result['msg']="성공";
                echo json_encode($result,JSON_UNESCAPED_UNICODE);
                exit;
            
            }else{ //쿼리실패
                $result = array();
                $result['status'] = 0;
                $result['msg']="업데이트 실패";
                echo json_encode($result,JSON_UNESCAPED_UNICODE);
                exit;
            
            }

        }else{ //쿼리실패
            $result = array();
            $result['status'] = 0;
            $result['msg']="업데이트 실패";
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            exit;

        
       }
    }
    

}



?>