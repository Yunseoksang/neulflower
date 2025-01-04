
<?php


$folder_name = "fu/client_input";





?>


<link href="./contents/<?=$folder_name?>/css/style.css?ver=<?=time()?>" rel="stylesheet">
<script src="./contents/<?=$folder_name?>/js/page.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->
<script type="text/javascript" src="./js/bootstrap-filestyle.min.js"> </script>


<div class="right_col" role="main" style="min-height: 527px;">



        <div class="right_set" mode='minus'>
          <div class="page-title">
            <div class="title_left">
              <h3>
                    물품발주하기
                    <small>
                        
                    </small>
                    
                </h3>
                <hr>
            </div>




          <div class="clearfix"></div>






          <? 
           include($_SERVER["DOCUMENT_ROOT"].'/common/html/product_list_order_html.php');
          ?>



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
                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                           <label for="to_place_name">배송지 선택</label>

                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px;">
                            <select id="to_place_name" class="select2_single form-control" tabindex="-1" style="display: none;">
                                <option value="">고객사</option>
                                <option value="<?=$data_client['client_place_idx']?>"><?=$data_client['place_name']?></option>
                            </select>
                        </div>
                        <div class="clear"></div>


                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="address">배송지 주소</label>
                        </div>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                          <input type="text" id="address" class="form-control" name="address" >
                        </div>

                        <div class="clear"></div>


                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="to_name">받는분 이름</label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="to_name" class="form-control input_normal" name="to_name" >
                        </div>

                        <div class="clear"></div>

                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                        <label for="hp">전화번호</label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                        <input type="text" id="hp" class="form-control input_normal" name="hp" >
                        </div>

                        <div class="clear"></div>

                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                        <label for="memo">메모</label>
                        </div>
                        <div class="col-md-10 col-sm-10 col-xs-12" style="margin-bottom: 10px;">
                        <textarea id="memo" required="required" class="form-control" name="memo" ></textarea>
                        </div>


                        <form enctype="multipart/form-data" id="ajaxFrom" method="post">

                        <div class="clear"></div>

                        <div class="div_attach">
                          <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="attachFile"><i class="fa fa-plus-square"></i>첨부하기</label>
                          </div>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                              <!-- <input type="file" name="attachFile" id="attachFile"/> -->
                              <input type="file" class="files" id='files' name="files[]" multiple>
                          </div>
                        </div>
                        <div class="clear"></div>

<!-- 
                        <div class="div_attach">
                          <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="attachFile"><i class="fa fa-plus-square"></i>첨부하기</label>
                          </div>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                              <input type="file" name="attachFile" />
                          </div>
                        </div>
                        <div class="clear"></div>
                        <div class="div_attach">
                          <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="attachFile"><i class="fa fa-plus-square"></i>첨부하기</label>
                          </div>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                              <input type="file" name="attachFile" />
                          </div>
                        </div>
                        <div class="clear"></div> -->


                      </form>

                        <script>
                           $("input.files").filestyle({
                                iconName : 'glyphicon glyphicon-file',
                                buttonText : 'Select File',
                                buttonName : 'btn-warning'
                            });       
                            // $("#attachFile").filestyle({
                            //     iconName : 'glyphicon glyphicon-file',
                            //     buttonText : 'Select File',
                            //     buttonName : 'btn-warning'
                            // });       
                            
                        </script>




                        <div class="col-md-2 col-sm-2 col-xs-12">
                        
                        </div>
                        <div class="col-md-10 col-sm-10 col-xs-12" style="margin-top:10px;">
                          <!-- <div class="checkbox">
                            <label class="">
                              <div class="icheckbox_flat-green " style="position: relative;">
                              <input type="checkbox" id='move_check' class="flat"  style="position: absolute; opacity: 0;">
                              </div> 이동지시서 동시 작성
                            </label>
                          </div> -->
                          <button  class="btn btn-success btn_save_client_order">발주하기</button>
                        </div>

                        <div class="clear"></div>



                        <!--<button  class="btn btn-danger btn_cancel">취소</button>-->
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




<!-- daterangepicker -->
<script type="text/javascript" src="./js/moment/moment.min.js"></script>
<script type="text/javascript" src="./js/datepicker/daterangepicker.js"></script>

<!-- Autocomplete -->
<script type="text/javascript" src="./js/autocomplete/countries.js"></script>
<!-- select2 dropdown 박스 + search-->
<script src="./js/select/select2.full.js"></script>

<!-- icheck -->
<script src="./js/icheck/icheck.min.js"></script>


<!-- select2 -->
<script>
  $(document).ready(function() {
    $("#order_client.select2_single").select2({
      placeholder: "고객사 선택",
      allowClear: true
    });

    $("#to_place_name.select2_single").select2({
      placeholder: "배송지 선택",
      allowClear: true
    });
    
    $(".select2_group").select2({});


    $("input").iCheck({
      checkboxClass: 'icheckbox_flat'
    });

  });
</script>
<!-- /select2 -->