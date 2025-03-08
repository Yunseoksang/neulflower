
<?php


$folder_name = "sangjo/client_input";


$sel_client = mysqli_query($dbcon, "select * from ".$db_consulting.".consulting where consulting_status='계약완료' order by company_name ") or die(mysqli_error($dbcon));
$sel_client_num = mysqli_num_rows($sel_client);




?>


<link href="./contents/<?=$folder_name?>/css/style.css?ver=<?=time()?>" rel="stylesheet">
<script src="/common/js/icheck_common.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->

<script src="./contents/<?=$folder_name?>/js/page.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->
<script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>


<div class="right_col" role="main" style="min-height: 527px;">



        <div class="right_set" mode='minus'>
          <div class="page-title">
            <div class="title_left">
              <h3>
                    주문서 등록
                    <small>
                        
                    </small>
                    
                </h3>
                <hr>
            </div>


            <div class="title_right">
            <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12" style='text-align:right;'></label>
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <select id="order_client" class="select2_single form-control" tabindex="-1" style="display: none;">
                        <option value="">고객사</option>
                      
                        <?php
                        if ($sel_client_num > 0) {
                          while($data_client = mysqli_fetch_assoc($sel_client)) {?>
                            <option value="<?=$data_client['consulting_idx']?>"><?=$data_client['company_name']?></option>

                          
                          <?}
                        }
                        ?>
                          
                        </select>
                        
                      </div>
                    </div>

            </div>
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

                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="order_date">발주일</label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <!-- <input type="date" id="order_date" class="form-control input_normal" name="order_date" > -->
                          <input type="text" id="order_date" class="form-control input_normal" name="order_date" placeholder="yyyy-mm-dd">
                          <input type="date" id="order_date_picker" style="visibility: hidden; position: absolute;">


                        </div>
                        <style>

        #order_date_picker {
            visibility: hidden;
            position: absolute;
            z-index: 1000; /* 다른 요소보다 위에 표시되도록 우선순위 설정 */
        }
    </style>
         
        
         <script>
        const orderDateInput = document.getElementById('order_date');
        const orderDatePicker = document.getElementById('order_date_picker');

        const isValidDate = (year, month, day) => {
            const date = new Date(year, month - 1, day);
            return date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day;
        };

        const formatInput = (event) => {
            let value = orderDateInput.value.replace(/[^0-9]/g, ''); // 숫자만 허용

            if (value.length >= 4 && value.length <= 5) {
                value = value.slice(0, 4) + '-' + value.slice(4);
            } else if (value.length == 6) {
                value = value.slice(0, 4) + '-' + value.slice(4) + "-";
            } else if (value.length > 6) {
                value = value.slice(0, 4) + '-' + value.slice(4, 6) + '-' + value.slice(6, 8); // 날짜 부분 2자리로 제한
            }

            // 월과 날짜의 범위 확인 및 제한
            let parts = value.split('-');
            if (parts[1] && parts[1].length === 2) {
                let month = parseInt(parts[1], 10);
                if (month < 1) {
                    month = 1;
                } else if (month > 12) {
                    month = 12;
                }
                parts[1] = month.toString().padStart(2, '0');
            }

            if (parts[2] && parts[2].length === 2) {
                let day = parseInt(parts[2], 10);
                const year = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10);
                const maxDay = new Date(year, month, 0).getDate();

                if (day < 1) {
                    day = 1;
                } else if (day > maxDay) {
                    day = maxDay;
                }
                parts[2] = day.toString().padStart(2, '0');
            }

            orderDateInput.value = parts.join('-');
        };

        let timeout = null;

        orderDateInput.addEventListener('input', (event) => {
            const cursorPosition = orderDateInput.selectionStart;
            clearTimeout(timeout);

            if (event.inputType === 'deleteContentBackward') {
                timeout = setTimeout(() => {
                    formatInput(event);
                    orderDateInput.setSelectionRange(cursorPosition, cursorPosition);
                }, 200);
            } else {
                if (cursorPosition !== null && cursorPosition === 7) {
                    formatInput(event); // 월 입력 후 즉시 포맷 적용
                } else {
                    timeout = setTimeout(formatInput, 200);
                }
            }
        });

        orderDateInput.addEventListener('keydown', (event) => {
            const key = event.key;

            // 숫자, 백스페이스, 화살표, delete 키만 허용
            if (!/[0-9]/.test(key) && key !== 'Backspace' && key !== 'ArrowLeft' && key !== 'ArrowRight' && key !== 'Delete') {
                event.preventDefault();
                return;
            }

            // 현재 커서 위치
            const cursorPosition = event.target.selectionStart;
            const value = event.target.value;

            // '-'가 올바른 위치에 있는지 확인하고 삽입
            if (key !== 'Backspace' && key !== 'Delete') {
                if ((cursorPosition === 4 && value.length >= 4) || (cursorPosition === 7 && value.length >= 7)) {
                    event.target.value = value.slice(0, cursorPosition) + '-' + value.slice(cursorPosition);
                    event.target.setSelectionRange(cursorPosition + 1, cursorPosition + 1);
                }
            }
        });

        orderDateInput.addEventListener('focus', () => {
            orderDatePicker.style.visibility = 'visible';
            orderDatePicker.style.left = orderDateInput.offsetLeft + 'px';
            orderDatePicker.style.top = (orderDateInput.offsetTop + orderDateInput.offsetHeight) + 'px';
        });

        orderDateInput.addEventListener('blur', () => {
            setTimeout(() => {
                orderDatePicker.style.visibility = 'hidden';
            }, 200);
        });

        orderDatePicker.addEventListener('change', () => {
            orderDateInput.value = orderDatePicker.value;
            orderDatePicker.style.visibility = 'hidden';
        });
    </script>


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
                          <button  class="btn btn-success btn_save_client_order">저장하기</button>
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


      <div class='hide' id='product_li_sample'>
          
          <li product_idx=''>
              <input type="checkbox" class="icheck" name='product' value=''>
              <span class='li_product_name'>제품명</span>
              
              <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right">
                    <span class='client_price_sum' client_price_sum="11000">11,000</span><span> 원</span>
                    
              </div>
              <div style='clear:right;'></div>
          </li>
          
      </div>


      <div class='hide' id='product_li_add_sample'>
          <li product_idx=''>
            <div class="col-md-6 col-sm-6 col-xs-12 ">
                <input class="icheck " type=checkbox checked="checked" name='product_idx' value='' >
                <span class='li_product_name'>추가할 제품명</span>

            </div>

              <div class="col-md-6 col-sm-6 col-xs-12 pull-right">
                <span class="client_price_calculation" client_price_calculation="11000">11,000</span><span  class="won"> 원</span>
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
    $("#order_client.select2_single").select2({
      placeholder: "고객사 선택",
      allowClear: true
    });

    $("#to_place_name.select2_single").select2({
      placeholder: "배송지 선택",
      allowClear: true
    });
    
    $(".select2_group").select2({});



  });
</script>
<!-- /select2 -->