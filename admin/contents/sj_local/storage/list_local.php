
<?php



$folder_name = "sj_local/storage";


$storage_arr = array();
$sel_storage = mysqli_query($dbcon, "select storage_idx,storage_name from storage where storage_idx='".$admin_info['storage_sangjo']."' ") or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);

if($sel_storage_num > 0){
    while($data_storage = mysqli_fetch_assoc($sel_storage)) {
        array_push($storage_arr,$data_storage);
    }
}





$product_arr = array();
$sel = mysqli_query($dbcon, "select product_idx,product_name from product order by product_name ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);
$total_cnt = $sel_num;

if ($sel_num > 0) {
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_arr,$data);
    }
}





//창고별 재고 합계
$storage_sum_sql = "
    select sum(current_count) as sum_current_count,storage_idx,t_storage_name from (
    select * from in_out where io_idx in (select max(io_idx) as max_io_idx from in_out where storage_idx='".$admin_info['storage_sangjo']."' group by storage_idx, product_idx)  
    ) x group by storage_idx
    "
;




?>



<link href="./contents/<?=$folder_name?>/css/style.css?ver=<?=time()?>" rel="stylesheet">
<script src="./contents/<?=$folder_name?>/js/page.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->


<div class="right_col" role="main" style="min-height: 527px;">



        <div class="right_set" mode='plus'>
          <div class="page-title">
            <div class="title_left">
              <h3>
                    품목별 재고
                    <small>
                        
                    </small>
                </h3>
                <hr>
            </div>


            <div class="title_right">
            <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right" style="text-align:right;">
                      <a href='contents/sj_local/storage/api/getSpreadSheet.php'><button type="button" class="btn btn-primary btn_excel_download">엑셀 다운로드</button></a>
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
                        <th class="column-title th_product_name" style="background: #405467;">품목명 </th>

                          <?php
                          for ($i=0;$i<count($storage_arr);$i++ )
                          {
                            $th_class = "";
                            if(mb_strlen($storage_arr[$i]['storage_name']) > 5){
                              $th_class = "th_long";
                            }
                            
                          ?>
                            <th class="column-title <?=$th_class?>"><?=$storage_arr[$i]['storage_name']?> </th>

                        <?}

                        ?>
                        <!-- <th class="column-title th_sum">재고<br>합계 </th> -->

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
                      ?>
                        <tr class="<?=$line_class?> pointer">
                            <!--<td class="a-center ">
                              <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" class="flat" name="table_records" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                            </td>
                            <td class=" "><?=$product_arr[$i]['product_idx']?></td> -->
                            <td class=" "><?=$product_arr[$i]['product_name']?></td>

                            <?php
                            


                            $pr_sum = 0;
                            for ($k=0;$k<count($storage_arr);$k++)
                            {

                                $sel_io = mysqli_query($dbcon, "select current_count from in_out where storage_idx='".$storage_arr[$k]['storage_idx']."' and product_idx='".$product_arr[$i]['product_idx']."' order by io_idx desc limit 1 ") or die(mysqli_error($dbcon));
                                $sel_io_num = mysqli_num_rows($sel_io);
                                if ($sel_io_num > 0) {
                                    $data_io = mysqli_fetch_assoc($sel_io);
                                    //if($data_io['current_count'] == 0){$data_io['current_count'] = "-";}
                                    
                                    ?>
                                    
                                    <td class=" "><?=$data_io['current_count']?></td>
                                <?}else{?>
                                  <td class=" ">0</td>

                                <?}

                                $pr_sum = $pr_sum + $data_io['current_count'];


                            }


                            ?>
                            <!-- <td class=" "><?=$pr_sum?></td> -->

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
        bInfo: false
        
    } );
} );

</script>