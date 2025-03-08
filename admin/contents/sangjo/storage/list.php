
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





$product_arr = array();
$sel = mysqli_query($dbcon, "select product_idx,product_name,display_group,memo from product order by display_order,product_name ") or die(mysqli_error($dbcon));
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
                    전체 재고
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

                  <table id="datatable_static" class="table table-striped responsive-utilities jambo_table bulk_action" style="100%">
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
                        <!-- <th class="column-title th_memo">메모</th> -->

                          <?php
                          for ($i=0;$i<count($storage_arr);$i++ )
                          {

                            //해당 창고의 총 재고량 파악
                            // $this_st_sum = 0;
                            // for($j=0; $j<count($storage_sum_arr);$j++){
                            //   if($storage_sum_arr[$j]['storage_idx'] == $storage_arr[$i]['storage_idx']){
                            //     $this_st_sum = $storage_sum_arr[$j]['sum_current_count'];
                            //   }
                            // }

                            $storage_column = "storage_idx_".$storage_arr[$i]['storage_idx'];


                            $th_class = "";
                            if(mb_strlen($storage_arr[$i]['storage_name']) > 5){
                              $th_class = "th_long";
                            }
                            

                            if($st_in_wait_arr[$storage_column] > 0){
                              $st_in_wait_num = "(".$st_in_wait_arr[$storage_column].")";
                            }

                            
                          ?>
                            <th class="column-title <?=$th_class?>"><?=str_replace(" ","<br>",$storage_arr[$i]['storage_name'])?> <br><?=$storage_sum_arr[$storage_column]['sum_current_count']?><?=$st_in_wait_num?></th>

                        <?}

                        ?>

                      </tr>
                      
                            
                    </thead>

                    <tbody>


                    <?php





                    for ($i=0;$i<count($product_arr);$i++ )
                    {
                      if($i%2 == 0){
                        $line_class = "even";
                      }else{
                        $line_class = "odd";
                      }



                      $product_column = "product_idx_".$product_arr[$i]['product_idx'];



                      ?>
                        <tr class="<?=$line_class?> pointer">
                            <!--<td class="a-center ">
                              <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" class="flat" name="table_records" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                            </td>
                            <td class=" "><?=$product_arr[$i]['product_idx']?></td> -->
                            <td class=" "><a href="?page=sangjo/storage_adjust/history&product_idx=<?=$product_arr[$i]['product_idx']?>" target="history"><?=$product_arr[$i]['product_name']?></a></td>
                            <td class="display_group"><?=$product_arr[$i]['display_group']?></td>

                            <?php
                            
                            /*

                            $storage_cnt_arr = array();
                            //$pr_sum = 0;
                            for ($k=0;$k<count($storage_arr);$k++)
                            {
                                
                                $sel_io = mysqli_query($dbcon, "select current_count,zero_count from in_out where storage_idx='".$storage_arr[$k]['storage_idx']."' and product_idx='".$product_arr[$i]['product_idx']."'  order by io_idx desc limit 1 ") or die(mysqli_error($dbcon));
                                $sel_io_num = mysqli_num_rows($sel_io);
                                if ($sel_io_num > 0) {
                                    $data_io = mysqli_fetch_assoc($sel_io);



                                    if($data_io['zero_count'] == 'zero'){
                                      array_push($storage_cnt_arr,"-");

                                    }else{
                                      //if($data_io['current_count'] == 0){$data_io['current_count'] = "-";}
                                        
                                      array_push($storage_cnt_arr,$data_io['current_count']);
                                      //$pr_sum = $pr_sum + $data_io['current_count'];

                                    }
                                    

  
                                    
                                }else{
                                 
                                  array_push($storage_cnt_arr,"-");

                                }

                            }
                             */          

                            
                            
                            $storage_wait_cnt_arr = array();
                            $pr_wait_sum = 0;




                            $this_pr_sum = 0;
                            if($pr_in_wait_arr[$product_column] == 0){
                              //$pr_wait_sum = "";
                              $this_pr_sum = $product_sum_arr[$product_column]['sum_current_count'];
                            }else{
                              //$pr_wait_sum = "<span style='color:#ff6c00;'>(".$pr_wait_sum.")</span>";
                              $this_pr_sum = $product_sum_arr[$product_column]['sum_current_count'] + $pr_in_wait_arr[$product_column];
                            }

?>
                            <td class=" "><?=$this_pr_sum?></td>
                            <!-- <td class=" "><a href="?page=sangjo/storage_product/list" target="product"><?=$product_arr[$i]['memo']?></td> -->

<?

                            for ($k=0;$k<count($storage_arr);$k++)
                            {
                              $safe_alert = ""; //안전재고 미만일때 색상 다르게 표시
                              $title_alt = "";
                              $safe_num_w = ""; //안전재고량을 현재 재고량 옆에 동시 표기
                              //$storage_column = $storage_arr[$k]['storage_idx'];
                              //$product_column = $product_arr[$i]['product_idx'];
                              $storage_column = "storage_idx_".$storage_arr[$k]['storage_idx'];
                              //$product_column = "product_idx_".$product_arr[$i]['product_idx'];

                              $in_sum = "";
                              if($stpr_in_wait_arr[$storage_column][$product_column] > 0){
                                $in_sum = "<span style='color:#ff6c00;'>(".$stpr_in_wait_arr[$storage_column][$product_column].")</span>"; //미입고 내역
                              }
                              //if($storage_safe_arr[$storage_column][$product_column] > $storage_cnt_arr[$k] && $storage_safe_arr[$storage_column][$product_column] != "0"){
                              if($storage_safe_arr[$storage_column][$product_column][0] > $storage_safe_arr[$storage_column][$product_column][1] && $storage_safe_arr[$storage_column][$product_column][0] != "0"){
 
                                if($storage_cnt_arr[$k] != "-"){
                                  $safe_alert = "td_safe_alert";
                                  $title_alt = "안전재고:".$storage_safe_arr[$storage_column][$product_column][0]."개";
                                  $safe_num_w = "/".$storage_safe_arr[$storage_column][$product_column][0];


                                }
                              }

                              if($storage_cnt_arr[$k] == "--"){
                                $storage_safe_arr[$storage_column][$product_column][1] = "-";
                              }
                              
                              ?>
                                    <td class=" <?=$safe_alert?>" title="<?=$title_alt?>"><a href="dashboard_sffm.php?page=sangjo/storage_input/move&storage_idx=<?=$storage_arr[$k]['storage_idx']?>" target="move"><?=$storage_safe_arr[$storage_column][$product_column][1]?><?=$safe_num_w?><?=$in_sum?></a></td>
                            <?}
                            ?>


                        </tr>
                    <?}

                    ?>
                      


                      
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

