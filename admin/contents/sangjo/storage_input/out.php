
<?php


$folder_name = "sangjo/storage_input";


$sel_storage = mysqli_query($dbcon, "select * from storage order by storage_name ") or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);




?>


<link href="./contents/<?=$folder_name?>/css/style.css?ver=<?=time()?>" rel="stylesheet">
<script src="./contents/<?=$folder_name?>/js/page.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->


<div class="right_col" role="main" style="min-height: 527px;">



        <div class="right_set" mode='minus'>
          <div class="page-title">
            <div class="title_left">
              <h3>
                    출고지시서 작성
                    <small>
                        
                    </small>
                    
                </h3>
                <hr>
            </div>


            <div class="title_right">
            <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12" style='text-align:right;'></label>
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <select id="out_storage" class="select2_single form-control" tabindex="-1" style="display: none;">
                        <option value="">출고지</option>
                      
                        <?php
                        if ($sel_storage_num > 0) {
                          while($data_storage = mysqli_fetch_assoc($sel_storage)) {?>
                            <option value="<?=$data_storage['storage_idx']?>"><?=$data_storage['storage_name']?></option>

                          
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

                    </ul>
                  </div>
                </div>
              </div>
            </div>







            <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="x_panel x_panel_minium">
                <div class="x_title">
                  <h2>출고 제품<small></small></h2>
                  <ul class="nav navbar-right panel_toolbox simple_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content product_right_content">
                    
                  <div class="">
                      <ul class="to_do ul_product_list_right ">


                      </ul>



                      <ul class="to_do selected_total_ul">
                        <li>
                          <span class='grey total'>Total</span> <span class=''>품목수:</span>  <span class='selected_product_num'>0</span> 
                          
                            <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right ">
                              <span class='total_selected_cnt'>총수량:</span> <span class='plus_storage_cnt_minus'>-0</span>
                          </div>
                        </li>
                      </ul>


                  </div>
                </div>
              </div>
            </div>

            








          </div>
        </div>



        <div class="clear"></div>



        <div class="row">


          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel x_panel_minium">
              <div class="x_title">
                <h2>배송지 입력<small></small></h2>
                <ul class="nav navbar-right panel_toolbox simple_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                
                <div class="clearfix"></div>
              </div>


              <div class="x_content ">
                <div class="" id="out_form">
                        <!-- <label for="to_place_name">배송지 명 :</label>
                        <input type="text" id="to_place_name" class="form-control input_normal" name="to_place_name" > -->

                        <label for="address">배송지 주소 :</label>
                        <input type="text" id="address" class="form-control" name="address" >

                        <label for="to_name">받는분 이름 :</label>
                        <input type="text" id="to_name" class="form-control input_normal" name="to_name" >

                        <label for="hp">전화번호 :</label>
                        <input type="text" id="hp" class="form-control input_normal" name="hp" >

                        <label for="memo">메모 :</label>
                        <textarea id="memo" required="required" class="form-control" name="memo" ></textarea>

                        <br>
                        <div class="checkbox">
                          <label class="">
                            <div class="icheckbox_flat-green " style="position: relative;">
                            <input type="checkbox" id='move_checked' class="flat"  style="position: absolute; opacity: 0;">
                            </div> 이동지시서를 동시에 작성합니다. 
                          </label>
                        </div>
                        <br>

                        <!--<button  class="btn btn-danger btn_cancel">취소</button>-->
                        <button  class="btn btn-success btn_save_out">저장하기</button>
                        <!--<button  class="btn btn-primary btn_save_out_complete">출고서 등록</button>-->





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


      <div class='hide' id='product_li_sample'>
          
          <li product_idx=''>
              <input type="checkbox" class="flat icheckbox" name='product' value=''>
              <span class='li_product_name'>제품명</span>
              
              <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right">
                    <span class='current_storage_cnt'>14</span>
                    <br>
                    <span class='plus_storage_cnt_minus hide'>+1</span>
                    
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
    $("#out_storage.select2_single").select2({
      placeholder: "출고지 선택",
      allowClear: true
    });
    $(".select2_group").select2({});


    $("input").iCheck({
      checkboxClass: 'icheckbox_flat'
    });

  });
</script>
<!-- /select2 -->