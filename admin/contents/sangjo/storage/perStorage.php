
<?php

$folder_name = "sangjo/storage";


//창고별 재고 합계
$storage_sum_sql = "
    select sum(current_count) as sum_current_count,sum(pr_sum) as sum_pr_count,storage_idx,t_storage_name as storage_name from (
    select *,count(*) as pr_sum from (
    select * from in_out where io_idx in (select max(io_idx) as max_io_idx from in_out group by storage_idx, product_idx)  
    ) y group by storage_idx, product_idx

    ) x group by storage_idx
    "
;

$storage_arr = array();
$sel_storage = mysqli_query($dbcon, $storage_sum_sql) or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);
$total_cnt = $sel_storage_num ;
if($sel_storage_num > 0){
    while($data_storage = mysqli_fetch_assoc($sel_storage)) {
        array_push($storage_arr,$data_storage);
    }
}








?>


<!--
<link href="./contents/<?=$folder_name?>/css/style.css?ver=<?=time()?>" rel="stylesheet">
-->

<script src="./contents/<?=$folder_name?>/js/page.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->


<div class="right_col" role="main" style="min-height: 527px;">



        <div class="right_set" mode='plus'>
          <div class="page-title">
            <div class="title_left">
              <h3>
                    창고별 재고
                    <small>
                        
                    </small>
                </h3>
                <hr>
            </div>


            <div class="title_right">
            <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12" style='text-align:right;'></label>
                      <div class="col-md-8 col-sm-8 col-xs-12">

                      </div>
                    </div>

            </div>
          </div>
          <div class="clearfix"></div>





  
          <div class="row">


            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel storage_border">
                <div class="x_title">
                  
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                      <input type="text" class="form-control has-feedback-left" id="product_filter" placeholder="창고 검색"> 
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

                  <p>총 <code><?=$total_cnt?></code></p>

                  <table id="datatable_one" class="table table-striped responsive-utilities jambo_table bulk_action">
                    <thead>
                      <tr class="headings">

                        <th>
                          <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" id="check-all" class="flat" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                        </th>
                        <th class="column-title th_storage_idx">창고번호 </th>
                        <th class="column-title th_storage_name">창고명 </th>
                        <th class="column-title th_sum_count">현재재고 </th>
                        <th class="column-title th_pr_count">품목수 </th>

                      </tr>
                    </thead>

                    <tbody>


                    <?php

                    for ($i=0;$i<count($storage_arr);$i++ )
                    {
                      if($i%2 == 0){
                        $line_class = "even";
                      }else{
                        $line_class = "odd";
                      }
                      ?>
                        <tr class="<?=$line_class?> pointer">
                            <td class="a-center ">
                              <div class="icheckbox_flat-green" style="position: relative;"><input type="checkbox" class="flat" name="table_records" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                            </td>
                            <td class=" "><?=$storage_arr[$i]['storage_idx']?></td>
                            <td class=" "><?=$storage_arr[$i]['storage_name']?></td>
                            <td class=" "><?=$storage_arr[$i]['sum_current_count']?></td>
                            <td class=" "><?=$storage_arr[$i]['sum_pr_count']?></td>

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

