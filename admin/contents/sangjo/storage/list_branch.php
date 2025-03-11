<?php



$folder_name = "sangjo/storage";


$storage_arr = array();
$sel_storage = mysqli_query($dbcon, "select storage_idx,storage_name from storage order by display_order desc,storage_name ") or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);

if($sel_storage_num > 0){
    while($data_storage = mysqli_fetch_assoc($sel_storage)) {
        array_push($storage_arr,$data_storage);
    }
}

// 더존 지사만 필터링
$douzone_storage_arr = array();
foreach($storage_arr as $storage) {
    if(strpos($storage['storage_name'], '더존') !== false) {
        array_push($douzone_storage_arr, $storage);
    }
}

// 본사 찾기
$headquarters_storage = null;
foreach($storage_arr as $storage) {
    if(strpos($storage['storage_name'], '본사') !== false) {
        $headquarters_storage = $storage;
        break;
    }
}





$product_arr = array();
$sel = mysqli_query($dbcon, "select product_idx,product_name,display_group,memo from product where product_name NOT LIKE '%test%' order by display_order,product_name ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);
$total_cnt = $sel_num;

if ($sel_num > 0) {
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_arr,$data);
    }
}





//창고별 재고 합계
// $storage_sum_sql = "
//     select sum(current_count) as sum_current_count,storage_idx,t_storage_name from (
//     select * from in_out where io_idx in (select max(io_idx) as max_io_idx from in_out group by storage_idx, product_idx)  
//     ) x group by storage_idx
//     "
// ;
$storage_sum_sql = "
select sum(current_count) as sum_current_count,storage_idx from storage_safe 
group by storage_idx order by storage_idx
    "
;

$storage_sum_arr = array();
$sel_st_sum = mysqli_query($dbcon, $storage_sum_sql) or die(mysqli_error($dbcon));
$sel_st_sum_num = mysqli_num_rows($sel_st_sum);

$st_sum_total = 0;
if ($sel_st_sum_num > 0) {
    while($data_st_sum = mysqli_fetch_assoc($sel_st_sum)) {
        $col_name = "storage_idx_".$data_st_sum['storage_idx'];
        //array_push($storage_sum_arr,$data_st_sum);
        $storage_sum_arr[$col_name] = $data_st_sum;
        $st_sum_total += $data_st_sum['sum_current_count'];
    }
}

// 더존 지사 합계 계산
$douzone_sum_total = 0;
foreach($douzone_storage_arr as $douzone_storage) {
    $storage_column = "storage_idx_".$douzone_storage['storage_idx'];
    if(isset($storage_sum_arr[$storage_column])) {
        $douzone_sum_total += $storage_sum_arr[$storage_column]['sum_current_count'];
    }
}



//재품별 재고 합계
$product_sum_sql = "
select sum(current_count) as sum_current_count,product_idx from storage_safe 
group by product_idx order by product_idx
    "
;

$product_sum_arr = array();
$sel_pr_sum = mysqli_query($dbcon, $product_sum_sql) or die(mysqli_error($dbcon));
$sel_pr_sum_num = mysqli_num_rows($sel_pr_sum);

if ($sel_pr_sum_num > 0) {
    while($data_pr_sum = mysqli_fetch_assoc($sel_pr_sum)) {
        $col_name = "product_idx_".$data_pr_sum['product_idx'];
        $product_sum_arr[$col_name] = $data_pr_sum;
    }
}





//창고별+상품별 안전재고 / 현재재고 
$storage_safe_sql = "
    select * from storage_safe order by storage_idx,product_idx
    ";

$storage_safe_arr = array();

$sel_st_safe = mysqli_query($dbcon, $storage_safe_sql) or die(mysqli_error($dbcon));
$sel_st_safe_num = mysqli_num_rows($sel_st_safe);

$st_safe_total = 0;
if ($sel_st_safe_num > 0) {
    while($data_st_safe = mysqli_fetch_assoc($sel_st_safe)) {

        $vs = $data_st_safe['safe_count'];
        $vc = $data_st_safe['current_count'];

        $storage_column = "storage_idx_".$data_st_safe['storage_idx'];
        $product_column = "product_idx_".$data_st_safe['product_idx'];


        //$storage_safe_arr[$data_st_safe['storage_idx']][$data_st_safe['product_idx']] = $data_st_safe['safe_count'];
        //$storage_safe_arr[$data_st_safe['storage_idx']][$data_st_safe['product_idx']] = [$vs,$vc];
        $storage_safe_arr[$storage_column][$product_column] = [$vs,$vc];

    }
}



//창고별+상품별 미입고내역 합계
$storage_in_wait_sql = "
select sum(in_count) as sum_in,storage_idx,product_idx from in_out where part='이동입고' and io_status='미입고' group by storage_idx,product_idx

    ";

$stpr_in_wait_arr = array();

$sel_stpr_in_wait = mysqli_query($dbcon, $storage_in_wait_sql) or die(mysqli_error($dbcon));
$sel_stpr_in_wait_num = mysqli_num_rows($sel_stpr_in_wait);

$stpr_in_wait_total = 0;
if ($sel_stpr_in_wait_num > 0) {
    while($data_stpr_in_wait = mysqli_fetch_assoc($sel_stpr_in_wait)) {


        $storage_column = "storage_idx_".$data_stpr_in_wait['storage_idx'];
        $product_column = "product_idx_".$data_stpr_in_wait['product_idx'];


        $stpr_in_wait_arr[$storage_column][$product_column] = $data_stpr_in_wait['sum_in'];

    }
}





//상품별 미입고내역 합계
$pr_in_wait_sql = "
select sum(in_count) as sum_in,product_idx from in_out where part='이동입고' and io_status='미입고' group by product_idx

    ";

$pr_in_wait_arr = array();
$sel_pr_in_wait = mysqli_query($dbcon, $pr_in_wait_sql) or die(mysqli_error($dbcon));
$sel_pr_in_wait_num = mysqli_num_rows($sel_pr_in_wait);

if ($sel_pr_in_wait_num > 0) {
    while($data_pr_in_wait = mysqli_fetch_assoc($sel_pr_in_wait)) {
        $product_column = "product_idx_".$data_pr_in_wait['product_idx'];
        $pr_in_wait_arr[$product_column] = $data_pr_in_wait['sum_in'];
    }
}





//창고별 미입고내역 합계
$st_in_wait_sql = "
select sum(in_count) as sum_in,storage_idx from in_out where part='이동입고' and io_status='미입고' group by storage_idx

    ";

$st_in_wait_arr = array();
$sel_st_in_wait = mysqli_query($dbcon, $st_in_wait_sql) or die(mysqli_error($dbcon));
$sel_st_in_wait_num = mysqli_num_rows($sel_st_in_wait);

if ($sel_st_in_wait_num > 0) {
    while($data_st_in_wait = mysqli_fetch_assoc($sel_st_in_wait)) {
        $storage_column = "storage_idx_".$data_st_in_wait['storage_idx'];
        $st_in_wait_arr[$storage_column] = $data_st_in_wait['sum_in'];
        $st_sum_total += $data_st_in_wait['sum_in']; //전체재고 합계

    }
}






?>



<link href="./contents/<?=$folder_name?>/css/style.css?ver=<?=time()?>" rel="stylesheet">
<script src="./contents/<?=$folder_name?>/js/page.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->


<div class="right_col" role="main" style="min-height: 527px;">



        <div class="right_set" mode='plus'>
          <div class="page-title">
            <div class="title_left">
              <h3>
                    더존 재고
                    <small>
                        
                    </small>
                </h3>
                <hr>
            </div>


            <div class="title_right">
            <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right" style="text-align:right;">
                      <a href='contents/sangjo/storage/api/getSpreadSheet.php'><button type="button" class="btn btn-primary btn_excel_download">엑셀 다운로드</button></a>
            </div>
          </div>
          <div class="clearfix"></div>





  
          <div class="row">


            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel storage_border">
                <div class="x_title">
                  
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                      <input type="text" class="form-control has-feedback-left" id="product_filter" placeholder="품목명 검색"> 
                      <span class="fa fa-search form-control-feedback left" aria-hidden="true"></span> 
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                      <input type="text" class="form-control has-feedback-left" id="storage_filter" placeholder="창고 검색"> 
                      <span class="fa fa-search form-control-feedback left" aria-hidden="true"></span> 
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                      <input type="text" class="form-control has-feedback-left" id="display_group_filter" placeholder="관리그룹"> 
                      <span class="fa fa-search form-control-feedback left" aria-hidden="true"></span> 
                    </div>
                  <ul class="nav navbar-right panel_toolbox simple_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content datatable_wrap">

                  <p>총 <code><?=$total_cnt?></code> 품목</p>

                  <table id="datatable_static" class="table table-striped responsive-utilities jambo_table bulk_action" style="width:100%">
                    <thead>
                      <tr class="headings">
                        <!--
                        <th>
                          <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" id="check-all" class="flat" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                        </th>
                        <th class="column-title">품목번호 </th>  -->
                        <th class="column-title th_product_name"  style="background: #405467;">품목명 </th>
                        <th class="column-title th_display_group"  style="background: #405467;">관리그룹 </th>

                        <th class="column-title th_sum">재고<br>합계<br>(<?=$st_sum_total?>) </th>
                        
                        <?php if($headquarters_storage): ?>
                        <?php 
                            $storage_column = "storage_idx_".$headquarters_storage['storage_idx'];
                            $st_in_wait_num = "";
                            if(isset($st_in_wait_arr[$storage_column]) && $st_in_wait_arr[$storage_column] > 0){
                                $st_in_wait_num = "(".$st_in_wait_arr[$storage_column].")";
                            }
                        ?>
                        <th class="column-title">본사<br><?=$storage_sum_arr[$storage_column]['sum_current_count']?><?=$st_in_wait_num?></th>
                        <?php endif; ?>
                        
                        <th class="column-title">지사합계<br>(더존)<br><?=$douzone_sum_total?></th>

                        <?php
                        // 더존 지사만 표시
                        foreach($douzone_storage_arr as $storage) {
                            $storage_column = "storage_idx_".$storage['storage_idx'];
                            $th_class = "";
                            if(mb_strlen($storage['storage_name']) > 5){
                                $th_class = "th_long";
                            }
                            
                            $st_in_wait_num = "";
                            if(isset($st_in_wait_arr[$storage_column]) && $st_in_wait_arr[$storage_column] > 0){
                                $st_in_wait_num = "(".$st_in_wait_arr[$storage_column].")";
                            }
                        ?>
                            <th class="column-title <?=$th_class?>"><?=str_replace(" ","<br>",$storage['storage_name'])?> <br><?=$storage_sum_arr[$storage_column]['sum_current_count']?><?=$st_in_wait_num?></th>
                        <?php } ?>
                      </tr>
                    </thead>

                    <tbody>
                    <?php
                    for ($i=0;$i<count($product_arr);$i++) {
                        if($i%2 == 0){
                            $line_class = "even";
                        }else{
                            $line_class = "odd";
                        }

                        $product_column = "product_idx_".$product_arr[$i]['product_idx'];
                    ?>
                        <tr class="<?=$line_class?> pointer">
                            <td class=" "><a href="?page=sangjo/storage_adjust/history&product_idx=<?=$product_arr[$i]['product_idx']?>" target="history"><?=$product_arr[$i]['product_name']?></a></td>
                            <td class="display_group"><?=$product_arr[$i]['display_group']?></td>

                            <?php
                            $this_pr_sum = 0;
                            if($pr_in_wait_arr[$product_column] == 0){
                                $this_pr_sum = $product_sum_arr[$product_column]['sum_current_count'];
                            }else{
                                $this_pr_sum = $product_sum_arr[$product_column]['sum_current_count'] + $pr_in_wait_arr[$product_column];
                            }
                            ?>
                            <td class=" "><?=$this_pr_sum?></td>
                            
                            <?php if($headquarters_storage): ?>
                            <?php
                                $storage_column = "storage_idx_".$headquarters_storage['storage_idx'];
                                $product_column = "product_idx_".$product_arr[$i]['product_idx'];
                                
                                $safe_alert = "";
                                $title_alt = "";
                                $safe_num_w = "";
                                
                                $in_sum = "";
                                if(isset($stpr_in_wait_arr[$storage_column][$product_column]) && $stpr_in_wait_arr[$storage_column][$product_column] > 0){
                                    $in_sum = "<span style='color:#ff6c00;'>(".$stpr_in_wait_arr[$storage_column][$product_column].")</span>";
                                }
                                
                                if(isset($storage_safe_arr[$storage_column][$product_column]) && 
                                   $storage_safe_arr[$storage_column][$product_column][0] > $storage_safe_arr[$storage_column][$product_column][1] && 
                                   $storage_safe_arr[$storage_column][$product_column][0] != "0"){
                                    $safe_alert = "td_safe_alert";
                                    $title_alt = "안전재고:".$storage_safe_arr[$storage_column][$product_column][0]."개";
                                    $safe_num_w = "/".$storage_safe_arr[$storage_column][$product_column][0];
                                }
                                
                                $current_count = isset($storage_safe_arr[$storage_column][$product_column]) ? $storage_safe_arr[$storage_column][$product_column][1] : "-";
                            ?>
                            <td class=" <?=$safe_alert?>" title="<?=$title_alt?>">
                                <a href="dashboard_sffm.php?page=sangjo/storage_input/move&storage_idx=<?=$headquarters_storage['storage_idx']?>" target="move">
                                    <?=$current_count?><?=$safe_num_w?><?=$in_sum?>
                                </a>
                            </td>
                            <?php endif; ?>
                            
                            <?php
                            // 더존 지사 합계 계산
                            $douzone_product_sum = 0;
                            foreach($douzone_storage_arr as $storage) {
                                $storage_column = "storage_idx_".$storage['storage_idx'];
                                $product_column = "product_idx_".$product_arr[$i]['product_idx'];
                                
                                if(isset($storage_safe_arr[$storage_column][$product_column])) {
                                    $douzone_product_sum += $storage_safe_arr[$storage_column][$product_column][1];
                                }
                                
                                if(isset($stpr_in_wait_arr[$storage_column][$product_column])) {
                                    $douzone_product_sum += $stpr_in_wait_arr[$storage_column][$product_column];
                                }
                            }
                            ?>
                            <td class=" "><?=$douzone_product_sum?></td>
                            
                            <?php
                            // 더존 지사별 재고 표시
                            foreach($douzone_storage_arr as $storage) {
                                $storage_column = "storage_idx_".$storage['storage_idx'];
                                $product_column = "product_idx_".$product_arr[$i]['product_idx'];
                                
                                $safe_alert = "";
                                $title_alt = "";
                                $safe_num_w = "";
                                
                                $in_sum = "";
                                if(isset($stpr_in_wait_arr[$storage_column][$product_column]) && $stpr_in_wait_arr[$storage_column][$product_column] > 0){
                                    $in_sum = "<span style='color:#ff6c00;'>(".$stpr_in_wait_arr[$storage_column][$product_column].")</span>";
                                }
                                
                                if(isset($storage_safe_arr[$storage_column][$product_column]) && 
                                   $storage_safe_arr[$storage_column][$product_column][0] > $storage_safe_arr[$storage_column][$product_column][1] && 
                                   $storage_safe_arr[$storage_column][$product_column][0] != "0"){
                                    $safe_alert = "td_safe_alert";
                                    $title_alt = "안전재고:".$storage_safe_arr[$storage_column][$product_column][0]."개";
                                    $safe_num_w = "/".$storage_safe_arr[$storage_column][$product_column][0];
                                }
                                
                                $current_count = isset($storage_safe_arr[$storage_column][$product_column]) ? $storage_safe_arr[$storage_column][$product_column][1] : "-";
                            ?>
                            <td class=" <?=$safe_alert?>" title="<?=$title_alt?>">
                                <a href="dashboard_sffm.php?page=sangjo/storage_input/move&storage_idx=<?=$storage['storage_idx']?>" target="move">
                                    <?=$current_count?><?=$safe_num_w?><?=$in_sum?>
                                </a>
                            </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>







          </div>
        </div>

        <!-- footer content -->
        <footer>
          <div class="copyright-info">
            <p class="pull-right">
            </p>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->

      </div>



<!-- daterangepicker -->
<script type="text/javascript" src="js/moment/moment.min.js"></script>
<script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>

<!-- Autocomplete -->
<script type="text/javascript" src="js/autocomplete/countries.js"></script>
<script>
$(document).ready(function() {
    var table = $('#datatable_static').DataTable( {
        scrollY:        "600px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            left: 1,
            right:1
        },
        bFilter: false,
        bInfo: false,
        aaSorting: []
        
    } );
} );

</script>

