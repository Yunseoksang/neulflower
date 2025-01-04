<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_flower.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
manager_check_ajax();

mysqli_begin_transaction($dbcon);


if($_POST['mode'] == "del"){

    if($_POST['out_order_part'] == "상조"){

        $sel = mysqli_query($dbcon, "select * from ".$db_sangjo.".out_order_client_product where out_order_idx='".$_POST['out_order_idx']."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        if($sel_num == 1){
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="단건 상품주문인 경우 삭제할수 없습니다. 주문취소만 가능합니다.";

            echo json_encode($result);
            exit;
        }

        //$data = mysqli_fetch_assoc($sel);
        $flower_out_order_idx = $_POST['out_order_idx'];



        $del = mysqli_query($dbcon, "delete from ".$db_sangjo.".out_order_client_product where oocp_idx='".$_POST['oocp_idx']."' ") or die(mysqli_error($dbcon));
        $del_num = mysqli_affected_rows($dbcon);
        if($del_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
            $sel1 = mysqli_query($dbcon, "select product_name,count(*) as total_order_kinds, sum(order_count) as total_order_count,sum(price_calcu) as total_client_price_sum from ".$db_sangjo.".out_order_client_product where flower_out_order_idx='".$flower_out_order_idx."' ") or die(mysqli_error($dbcon));
            $sel1_num = mysqli_num_rows($sel1);

            
            if ($sel1_num > 0) {
                $data1 = mysqli_fetch_assoc($sel1);

                $total_client_price = (int) ($data1['total_client_price_sum']/1.1);
                $total_client_price_tax = (int) ($data1['total_client_price_sum']/11);

                $up = mysqli_query($dbcon, "update ".$db_flower.".out_order set 
                order_product_title='".$data1['product_name']."',
                total_order_kinds='".$data1['total_order_kinds']."',
                total_order_count='".$data1['total_order_count']."',
                total_client_price='".$total_client_price."',
                total_client_price_tax='".$total_client_price_tax."',
                total_client_price_sum='".$data1['total_client_price_sum']."'
                
                where out_order_idx='".$flower_out_order_idx."' ") or die(mysqli_error($dbcon));
                $up_num = mysqli_affected_rows($dbcon);
                if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.


                    if($data1['total_order_count'] > 1){
                        $product_name_add = " 등".$data1['total_order_count'];
                    }else{
                        $product_name_add = "";
                    }


                    mysqli_commit($dbcon);

                    $result = array();
                    $result['status'] = 1;
                    $result['data']['out_order_idx'] =  $flower_out_order_idx;

                    $result['data']['total_client_price_sum'] = $data1['total_client_price_sum'];
                    $result['data']['order_product_title'] = $data1['product_name'].$product_name_add;

                    $result['msg']="삭제되었습니다.";
            
                    echo json_encode($result);
                    exit;
                
                }else{ //쿼리실패
                    mysqli_rollback($dbcon);

                    $result = array();
                    $result['status'] = 0;
                    $result['msg']="쿼리실패";
            
                    echo json_encode($result);
                    exit;
                }


            }
            
        }else{ //쿼리실패
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="쿼리실패";

            echo json_encode($result);
            exit;
        }                      
    }else if($_POST['out_order_part'] == "화훼"){

        $sel = mysqli_query($dbcon, "select * from ".$db_flower.".out_order_client_product where out_order_idx='".$_POST['out_order_idx']."' ") or die(mysqli_error($dbcon));
        $sel_num = mysqli_num_rows($sel);
        if($sel_num == 1){
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="단건 상품주문인 경우 삭제할수 없습니다. 주문취소만 가능합니다.";

            echo json_encode($result);
            exit;
        }

        //$data = mysqli_fetch_assoc($sel);
        $out_order_idx =$_POST['out_order_idx'];



        $del = mysqli_query($dbcon, "delete from ".$db_flower.".out_order_client_product where oocp_idx='".$_POST['oocp_idx']."' ") or die(mysqli_error($dbcon));
        $del_num = mysqli_affected_rows($dbcon);
        if($del_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
            $sel1 = mysqli_query($dbcon, "select product_name,count(*) as total_order_kinds, sum(order_count) as total_order_count,sum(price_calcu) as total_client_price_sum from ".$db_flower.".out_order_client_product where out_order_idx='".$out_order_idx."' ") or die(mysqli_error($dbcon));
            $sel1_num = mysqli_num_rows($sel1);

            
            if ($sel1_num > 0) {
                $data1 = mysqli_fetch_assoc($sel1);

                $total_client_price = (int) ($data1['total_client_price_sum']/1.1);
                $total_client_price_tax = (int) ($data1['total_client_price_sum']/11);

                $up = mysqli_query($dbcon, "update ".$db_flower.".out_order set 
                order_product_title='".$data1['product_name']."',
                total_order_kinds='".$data1['total_order_kinds']."',
                total_order_count='".$data1['total_order_count']."',
                total_client_price='".$total_client_price."',
                total_client_price_tax='".$total_client_price_tax."',
                total_client_price_sum='".$data1['total_client_price_sum']."'
                
                where out_order_idx='".$out_order_idx."' ") or die(mysqli_error($dbcon));
                $up_num = mysqli_affected_rows($dbcon);
                if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                    if($data1['total_order_count'] > 1){
                        $product_name_add = " 등".$data1['total_order_count'];
                    }else{
                        $product_name_add = "";
                    }

                    mysqli_commit($dbcon);

                    $result = array();
                    $result['status'] = 1;
                    $result['data']['out_order_idx'] =  $out_order_idx;

                    $result['data']['total_client_price_sum'] = $data1['total_client_price_sum'];
                    $result['data']['order_product_title'] = $data1['product_name'].$product_name_add;

                    $result['msg']="삭제되었습니다.";
            
                    echo json_encode($result);
                    exit;
                
                }else{ //쿼리실패
                    mysqli_rollback($dbcon);

                    $result = array();
                    $result['status'] = 0;
                    $result['msg']="쿼리실패";
            
                    echo json_encode($result);
                    exit;
                }


            }
         
        }else{ //쿼리실패
            mysqli_rollback($dbcon);

           $result = array();
           $result['status'] = 0;
           $result['msg']="쿼리실패";
   
           echo json_encode($result);
           exit;
        }
    }

}else if($_POST['mode'] == "edit"){

    if($_POST['out_order_part'] == "상조"){

        $sel = mysqli_query($dbcon, "select * from ".$db_sangjo.".out_order_client_product where oocp_idx='".$_POST['oocp_idx']."' ") or die(mysqli_error($dbcon));
        $data = mysqli_fetch_assoc($sel);
        $flower_out_order_idx = $data['flower_out_order_idx'];



        $del = mysqli_query($dbcon, "update ".$db_sangjo.".out_order_client_product 
            set order_count=".$_POST['cnt'].",price_calcu=order_count*unit_price
            where oocp_idx='".$_POST['oocp_idx']."' ") or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
        if($up_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
            $sel1 = mysqli_query($dbcon, "select product_name,count(*) as total_order_kinds, sum(order_count) as total_order_count,sum(price_calcu) as total_client_price_sum from ".$db_sangjo.".out_order_client_product where flower_out_order_idx='".$flower_out_order_idx."' ") or die(mysqli_error($dbcon));
            $sel1_num = mysqli_num_rows($sel1);

            
            if ($sel1_num > 0) {
                $data1 = mysqli_fetch_assoc($sel1);

                $total_client_price = (int) ($data1['total_client_price_sum']/1.1);
                $total_client_price_tax = (int) ($data1['total_client_price_sum']/11);

                $up = mysqli_query($dbcon, "update ".$db_flower.".out_order set 
                    order_product_title='".$data1['product_name']."',
                    total_order_kinds='".$data1['total_order_kinds']."',
                    total_order_count='".$data1['total_order_count']."',
                    total_client_price='".$total_client_price."',
                    total_client_price_tax='".$total_client_price_tax."',
                    total_client_price_sum='".$data1['total_client_price_sum']."'
                    
                    where out_order_idx='".$flower_out_order_idx."' ") or die(mysqli_error($dbcon));
                $up_num = mysqli_affected_rows($dbcon);
                if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                    if($data1['total_order_count'] > 1){
                        $product_name_add = " 등".$data1['total_order_count'];
                    }else{
                        $product_name_add = "";
                    }


                    mysqli_commit($dbcon);

                    $result = array();
                    $result['status'] = 1;
                    $result['data']['out_order_idx'] =  $flower_out_order_idx;

                    $result['data']['total_client_price_sum'] = $data1['total_client_price_sum'];
                    $result['data']['order_product_title'] = $data1['product_name'].$product_name_add;

                    $result['msg']="수정되었습니다.";
            
                    echo json_encode($result);
                    exit;
                
                }else{ //쿼리실패
                    mysqli_rollback($dbcon);

                    $result = array();
                    $result['status'] = 0;
                    $result['msg']="쿼리실패";
            
                    echo json_encode($result);
                    exit;
                }


            }
         
        }else{ //쿼리실패
            mysqli_rollback($dbcon);

           $result = array();
           $result['status'] = 0;
           $result['msg']="쿼리실패";
   
           echo json_encode($result);
           exit;
        }
    }else if($_POST['out_order_part'] == "화훼"){

        $sel = mysqli_query($dbcon, "select * from ".$db_flower.".out_order_client_product where oocp_idx='".$_POST['oocp_idx']."' ") or die(mysqli_error($dbcon));
        $data = mysqli_fetch_assoc($sel);
        $out_order_idx = $data['out_order_idx'];



        $del = mysqli_query($dbcon, "update ".$db_flower.".out_order_client_product 
        set order_count=".$_POST['cnt'].",price_calcu=order_count*unit_price
        where oocp_idx='".$_POST['oocp_idx']."' ") or die(mysqli_error($dbcon));
        $up_num = mysqli_affected_rows($dbcon);
        if($up_num >= 0){ //쿼리 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
            $sel1 = mysqli_query($dbcon, "select product_name,count(*) as total_order_kinds, sum(order_count) as total_order_count,sum(price_calcu) as total_client_price_sum from ".$db_flower.".out_order_client_product where out_order_idx='".$out_order_idx."' ") or die(mysqli_error($dbcon));
            $sel1_num = mysqli_num_rows($sel1);
        
            
            if ($sel1_num > 0) {
                $data1 = mysqli_fetch_assoc($sel1);

                $total_client_price = (int) $data1['total_client_price_sum']/1.1;
                $total_client_price_tax = (int) $data1['total_client_price_sum']/11;

                $up = mysqli_query($dbcon, "update ".$db_flower.".out_order set 
                order_product_title='".$data1['product_name']."',
                total_order_kinds='".$data1['total_order_kinds']."',
                total_order_count='".$data1['total_order_count']."',
                total_client_price='".$total_client_price."',
                total_client_price_tax='".$total_client_price_tax."',
                total_client_price_sum='".$data1['total_client_price_sum']."'
                
                where out_order_idx='".$out_order_idx."' ") or die(mysqli_error($dbcon));
                $up_num = mysqli_affected_rows($dbcon);
                if($up_num >= 0){ //업데이트 성공 // 0이면 쿼리성공이지만 변경데이터 없음.
                        if($data1['total_order_count'] > 1){
                            $product_name_add = " 등".$data1['total_order_count'];
                        }else{
                            $product_name_add = "";
                        }


                        mysqli_commit($dbcon);

                        $result = array();
                        $result['status'] = 1;
                        $result['data']['out_order_idx'] =  $out_order_idx;
                        $result['data']['total_client_price_sum'] = $data1['total_client_price_sum'];
                        $result['data']['order_product_title'] = $data1['product_name'].$product_name_add;

                        $result['msg']="수정되었습니다.";
                
                        echo json_encode($result);
                        exit;
                    
                }else{ //쿼리실패
                    mysqli_rollback($dbcon);

                    $result = array();
                    $result['status'] = 0;
                    $result['msg']="쿼리실패";
            
                    echo json_encode($result);
                    exit;
                }


            }
                
        }else{ //쿼리실패
            mysqli_rollback($dbcon);

            $result = array();
            $result['status'] = 0;
            $result['msg']="쿼리실패";
    
            echo json_encode($result);
            exit;
        }
    }

}
