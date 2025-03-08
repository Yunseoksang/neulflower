
<?php


$folder_name = "flower/storage_input";


$sel_storage = mysqli_query($dbcon, "select * from storage order by storage_name ") or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);




?>


<link href="./contents/<?=$folder_name?>/css/style.css?ver=<?=time()?>" rel="stylesheet">
<script src="./contents/<?=$folder_name?>/js/page.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->


<div class="right_col" role="main" style="min-height: 527px;">



        <div class="right_set" mode='plus'>
          <div class="page-title">
            <div class="title_left">
              <h3>
                    생산 입력
                    <small>
                        
                    </small>
                </h3>
                <hr>
            </div>


            <div class="title_right">
            <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12" style='text-align:right;'></label>
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <select id="in_storage" class="select2_single form-control" tabindex="-1" style="display: none;">
                        <option value="0">전체</option>
                        <?php
                        if ($sel_storage_num > 0) {
                          while($data_storage = mysqli_fetch_assoc($sel_storage)) {?>
                            <option value="<?=$data_storage['storage_idx']?>"  <? if($data_storage['storage_idx'] == "2"){echo "selected='selected'";}?>><?=$data_storage['storage_name']?></option>

                          
                          <?}
                        }
                        ?>
                          
                        </select>
                        
                      </div>
                    </div>

            </div>
          </div>
          <div class="clearfix"></div>





  
          <div class="row">


            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel x_panel_minium">
                <div class="x_title">
                  <h2>제품 선택<small></small></h2>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                      <input type="text" class="form-control has-feedback-left" id="product_filter" placeholder="상품명 검색">
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
                <div class="x_content product_x_content">

                  <div class="">
                    <ul class="to_do ul_product_list">
<?php

$arr['storage_idx'] = "2";
require('./contents/flower/storage_input/api/common.php');

$sel_product = mysqli_query($dbcon, $base_input_product_query) or die(mysqli_error($dbcon));
$sel_productnum = mysqli_num_rows($sel_product);

if ($sel_productnum > 0) {
  //$data = mysqli_fetch_assoc($sel);
  while($data_product = mysqli_fetch_assoc($sel_product)) {
    
    if($data_product['sum_out_count'] > 0){
      $soc_class = "";
    }else{
      $soc_class = "hide";
    }
    ?>
  
                      <li product_idx="<?=$data_product['product_idx']?>">
                          
                          <input type="checkbox" class="flat" name='product' value='<?=$data_product['product_idx']?>' >
                          <span class='li_product_name'><?=$data_product['product_name']?></span>
                          
                          <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right">
                                <span class='current_storage_cnt'><?if($data_product['sum_current_count']==null){echo "0";}else{echo $data_product['sum_current_count'];}?></span>
                                <br class="<?=$soc_class?>">
                                <span class='plus_storage_cnt <?=$soc_class?>'>+<?=$data_product['sum_out_count']?></span>
                                
                          </div>
                          <div style='clear:right;'></div>
                      </li>

  
  <?}
}

?>



                    </ul>
                  </div>
                </div>
              </div>
            </div>







            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel x_panel_minium">
                <div class="x_title">
                  <h2>생산 제품<small></small></h2>
                  <ul class="nav navbar-right panel_toolbox simple_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content product_x_content">
                    
                  <div class="">
                      <ul class="to_do ul_product_list_right ">


                      </ul>



                      <ul class="to_do selected_total_ul">
                        <li>
                          <span class='grey total'>Total</span> <span class=''>품목수:</span>  <span class='selected_product_num'>0</span> 
                          
                            <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right ">
                              <span class='total_selected_cnt'>총수량:</span> <span class='plus_storage_cnt'>+0</span>
                          </div>
                        </li>
                      </ul>


                  </div>
                </div>
              </div>
            </div>





            <div class="col-md-6">
            <textarea id='memo' class='form-control' placeholder='MEMO' style='margin-bottom:20px;'></textarea>

            </div>



            <div class="col-md-6 col-sm-6 col-xs-12  right ">
              <button  class="btn btn-primary btn_cancel">취소</button>
              <button  class="btn btn-success btn_save">저장하기</button>
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


      <div class='hide' id='product_li_sample'>
          
          <li product_idx=''>
              <input type="checkbox" class="flat icheckbox" name='product' value=''>
              <span class='li_product_name'>제품명</span>
              
              <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right">
                    <span class='current_storage_cnt'>14</span>
                    <br>
                    <span class='plus_storage_cnt hide'>+1</span>
                    
              </div>
              <div style='clear:right;'></div>
          </li>
          
      </div>


      <div class='hide' id='product_li_add_sample'>
          <li product_idx=''>
            <input class="flat icheckbox " type=checkbox checked="checked" name='product_idx' value='' ><span class='li_product_name'>추가할 제품명</span>
            
              <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right">
                <input type=number name='cnt' value='1' class='input_cnt'>
                <i class='fa fa-minus-circle red btn_x_circle'></i>
            </div>
          </li>
      </div>


<!-- daterangepicker -->
<script type="text/javascript" src="js/moment/moment.min.js"></script>
<script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>

<!-- Autocomplete -->
<script type="text/javascript" src="js/autocomplete/countries.js"></script>


<!-- select2 -->
<script>
  $(document).ready(function() {
    $("#in_storage.select2_single").select2({
      placeholder: "입고지 선택",
      allowClear: true
    });
    $(".select2_group").select2({});


    $("input").iCheck({
      checkboxClass: 'icheckbox_flat'
    });

  });
</script>
<!-- /select2 -->