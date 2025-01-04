

<div id="magnify_window" class="hide">

</div>



<div id="custom_notifications" class="custom-notifications dsp_none">
  <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
  </ul>
  <div class="clearfix"></div>
  <div id="notif-group" class="tabbed_notifications"></div>
</div>






<div id="button_sample" class="hide">
     <button type="button" class="btn btn-dark btn-xs btn_modify">수정</button>
     <button type="button" class="btn btn-danger btn-xs btn_save">저장</button>
     <button type="button" class="btn btn-default btn-xs btn_cancel">취소</button>
     <button type="button" class="btn btn-info btn-xs btn_delete">삭제</button>

</div>



<!-- 추가하기 입력폼 샘플 시작 -->
<div id="add_form_sample" class="hide">

  <div class="form_area form-horizontal form-label-left" novalidate="">
    
    <div class="input form-group form_group_text">
      <label class="control-label col-md-4 col-sm-4 col-xs-12" for="menu_name" required="1">메뉴명*</label>
      <div class="col-md-8 col-sm-8 col-xs-12">
        <input type="text" autocomplete="off" name=menu_name  class="form-control col-md-7 col-xs-12" >
      </div>
    </div>
    <div class="input form-group form_group_hidden">
        <input type="hidden" name="hidden_name" >
    </div>

    <div class="input form-group form_group_number">
      <label class="control-label col-md-4 col-sm-4 col-xs-12" for="menu_original_price" required="1">정가*</label>
      <div class="col-md-8 col-sm-8 col-xs-12">
        <input type="number" autocomplete="off"  name=menu_original_price  class="form-control col-md-7 col-xs-12" >
      </div>
    </div>

    <div class="input form-group form_group_image">
      <label class="control-label col-md-4 col-sm-4 col-xs-12" for="upload_file"  required="0">이미지</label>
      <div class="col-md-8 col-sm-8 col-xs-12 form-group" >
          <input type="file" class="img_file" name="menu_img_url_file" data-icon="false" data-buttonName="btn-dark" data-buttonText="찾기" directory="">
          
      </div>
    </div>

    <!-- 단순 dropdown select box -->
    <div class="input form-group  form_group_select">
      <label class="control-label col-md-4 col-sm-4 col-xs-12">선택</label>
      <div class="col-md-8 col-sm-8 col-xs-12">
          <select class="form-control"  name="idx" >
            <option value="">선택</option>
          </select>
      </div>
    </div>


    <!-- 검색기능이 결합된 select box -->
    <div class="input form-group  form_group_select2">
      <label class="control-label col-md-4 col-sm-4 col-xs-12">선택</label>
      <div class="col-md-8 col-sm-8 col-xs-12 cont1">
        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback cont2">
          <select class="select2_single form-control"  same="N"  name="idx">
            <option value="">선택</option>
          </select>
        </div>
      </div>
    </div>
    


    <div class="input form-group  form_group_switch">
      <label class="control-label col-md-4 col-sm-4 col-xs-12">판매여부</label>
      <div class="col-md-8 col-sm-8 col-xs-12">
        <div class="btn-group left switch deselectable" data-toggle="buttons" >
          <label style="padding:5px;" class="btn btn-xs btn-default">
            <input type="radio" name="is_show_buyer" value="판매중">
          </label>
          <label style="padding:5px;" class="btn btn-xs btn-default">
            <input type="radio" name="is_show_buyer" value="판매중지">
          </label>
        </div>
      </div>
    </div>



    <div class="input form-group form_group_textarea">
      <label class="control-label col-md-4 col-sm-4 col-xs-12" for="detail">설명</span>
      </label>
      <div class="col-md-8 col-sm-8 col-xs-12">
        <textarea type="text"  name=detail class="form-control col-md-7 col-xs-12" ></textarea>
      </div>
    </div>


    <div class="ln_solid  form_group_submit"></div>
    <div class="form-group form_group_submit">
      <div class="col-md-8 col-sm-8 col-xs-12">
        <button type="button" class="btn btn-primary btn_cancel">취소</button>
        <button type="button" class="btn btn-success btn_submit">저장하기</button>
      </div>
    </div>

  </div>
</div>
<!-- 추가하기 입력폼 샘플 끝 -->




       <!--- 토스트 --->

       <div class='toast' style='display:none'></div>
      <script>

        function toast_fast(msg){
          $('.toast').text(msg).fadeIn(40).delay(300).fadeOut(40); //fade out after 3 seconds
        }
        function toast_short(msg){
          $('.toast').text(msg).fadeIn(40).delay(300).fadeOut(40); //fade out after 3 seconds
        }
        function toast(msg){
          $('.toast').text(msg).fadeIn(400).delay(1000).fadeOut(400); //fade out after 3 seconds
        }
        function showToast(msg){
          $('.toast').text(msg).fadeIn(400).delay(1000).fadeOut(400); //fade out after 3 seconds
        }
        function toast_center(msg){
          $('.toast').css("bottom","350px");
          $('.toast').text(msg).fadeIn(400).delay(1000).fadeOut(400); //fade out after 3 seconds
        }
        function toast_long(msg){
          $('.toast').text(msg).fadeIn(400).delay(2000).fadeOut(400); //fade out after 3 seconds
        }
	    </script>

      <style>
        .toast { 
        width: 250px; height: 20px; height:auto; position: fixed;
        left: 50%; margin-left:-125px; bottom: 100px; z-index: 99999999; 
        background-color: #383838; color: #F0F0F0; font-family: Calibri;
        font-size: 15px; padding: 10px; text-align:center; border-radius: 2px;
        -webkit-box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1);
        -moz-box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1); 
        box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1); 
        
        }

      </style>
	   <!---- 토스트 끝 --->


    <!-- 로딩창 --->
    <div id="loading-image" style="width:100%;position:fixed;left:50%; top: 50%; display:none;">
       <div  class="lds-hourglass"  ></div>
    </div>
    <style>

        .lds-hourglass {
          display: inline-block;
          position: relative;
          width: 64px;
          height: 64px;
        }
        .lds-hourglass:after {
          content: " ";
          display: block;
          border-radius: 50%;
          width: 0;
          height: 0;
          margin: 6px;
          box-sizing: border-box;
          border: 26px solid #ff8000;
          border-color: #ffaf60 transparent #ffaf60 transparent;
          animation: lds-hourglass 1.2s infinite;
        }
        @keyframes lds-hourglass {
          0% {
            transform: rotate(0);
            animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
          }
          50% {
            transform: rotate(900deg);
            animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
          }
          100% {
            transform: rotate(1800deg);
          }
        }

        .dataTables_processing{
          background-color:unset !important;
          background:unset !important;
          border:unset !important;
          top:-10px !important;


        }

        .dataTables_processing.panel{
          box-shadow:unset !important;
        }

    </style>
     <!-- 로딩창 끝--->



     <!---- 모달 컨펌 시작 ---->

      <div class="modal modal_confirm fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            </div>
            <div class="modal-body">
              <p>확인하셨습니까?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" id="modal-btn-yes">확인</button>
              <button type="button" class="btn btn-primary" id="modal-btn-no">취소</button>
            </div>
          </div>
        </div>
      </div>

      <script>
        var modalConfirm = function(callback){
          
          $("#btn-confirm").on("click", function(){
            $("#mi-modal").modal('show');
          });

          $("#modal-btn-yes").on("click", function(){
            callback(true);
            $("#mi-modal").modal('hide');
          });
          
          $("#modal-btn-no").on("click", function(){
            callback(false);
            $("#mi-modal").modal('hide');
          });
        };

        modalConfirm(function(confirm){
          if(confirm){
            //Acciones si el usuario confirma
            $("#result").html("CONFIRMADO");
          }else{
            //Acciones si el usuario no confirma
            $("#result").html("NO CONFIRMADO");
          }
        });
      </script>
    <!---- 모달 컨펌 끝 ----->


    <!--- 모달 확인 창 $("#mi-notice").modal('show');----->
    <div class="modal modal_notice fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-notice">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <!---
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
            </div>
            --->

            <div class="modal-body">
              <p>확인하셨습니까?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" id="notice-modal-btn-yes">확인</button>
            </div>
          </div>
        </div>
      </div>
      <script>

          $("#notice-modal-btn-yes").on("click", function(){
            $("#mi-notice").modal('hide');
            $("body").css("padding-right","0px");
          });
          

      </script>
      <!--- /모달 확인창 --->


      <!-- 모달 입력창 --->
        <div id="modal_add" class="modal_add">
          <div class="modal_add_overlay modal_add_toggle">
          </div>
          <div class="modal_add_wrapper modal_add_transition">
            <div class="modal_add_header">
                <button class="modal_add_close modal_add_toggle fa fa-close"></button>
                <h2 class="modal_add_heading"><i class="fa fa-plus"></i> 추가하기</h2>
            </div>
            <div class="modal_add_body">
              <div class="modal_add_content">


              </div>
            </div>
          </div>
        </div>
        <script>
          //열기 :$('.modal_add').toggleClass('is-visible');
          $(".modal_add_close,.modal_add_toggle").click(function() {  //닫기버튼, 여백클릭 시 닫기
            $('#modal_add').toggleClass('is-visible');
          });




        </script>

        <!--- /모달입력창 --->



      <!-- 큰 모달 창 --->
  
      <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true"  aria-labelledby="mySmallModalLabel" id="modal-free">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
              <h4>Text in a modal</h4>
              <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
              <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary btn_modal_save">저장</button>
            </div>

          </div>
        </div>
      </div>
      <!-- 큰 모달 창 끝 --->











        <!-- jquery confirm   https://craftpip.github.io/jquery-confirm/  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>




        <script src="js/switchery/switchery.min.js?ver=<?=time()?>"></script>




        <script src="js/bootstrap.min.js"></script>

        <!-- bootstrap progress js -->
        <script src="js/progressbar/bootstrap-progressbar.min.js"></script>
        <script src="js/nicescroll/jquery.nicescroll.min.js"></script>


        <!-- 이미지 라이트 박스 https://lokeshdhakar.com/projects/lightbox2/#getting-started -->
        <link rel="stylesheet" href="./js/lightbox2/css/lightbox.css?ver=<?=time()?>">
        <script src="./js/lightbox2/js/lightbox.js"></script>
        <script>
          lightbox.option({
            'resizeDuration': 200,
            'fadeDuration' : 150,
            'imageFadeDuration' : 150,
            'positionFromTop':150,
            'wrapAround': true
          })
        </script>

        <!-- icheck -->

        <script src="js/custom.js?ver=<?=time()?>"></script>
        <script src="js/icheck/icheck-1.0.3/icheck.min.js"></script>



        <!-- Datatables -->
        <!-- <script src="js/datatables/js/jquery.dataTables.js"></script>
        <script src="js/datatables/tools/js/dataTables.tableTools.js"></script> -->



        <!-- Datatables 1.10.11-->
        <script src="js/datatables/jquery.dataTables.min.js"></script>
        <script src="js/datatables/dataTables.bootstrap.js"></script>
        <script src="js/datatables/dataTables.buttons.min.js"></script>
        <script src="js/datatables/buttons.bootstrap.min.js"></script>
        <script src="js/datatables/jszip.min.js"></script>
        <script src="js/datatables/pdfmake.min.js"></script>
        <script src="js/datatables/vfs_fonts.js"></script>
        <script src="js/datatables/buttons.html5.min.js"></script>
        <script src="js/datatables/buttons.print.min.js"></script>
        <script src="js/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="js/datatables/dataTables.keyTable.min.js"></script>
        <script src="js/datatables/dataTables.responsive.min.js"></script>
        <script src="js/datatables/responsive.bootstrap.min.js"></script>
        <script src="js/datatables/dataTables.scroller.min.js"></script>
        <script type="text/javascript" language="javascript" src="js/datatables/dataTables.fixedColumns.min.js"></script>


        <!-- select2 dropdown 박스 + search-->
        <script src="js/select/select2.full.js"></script>

        <!-- 파일첨부 버튼 디자인 -->
        <script src="js/bootstrap-filestyle.min.js"> </script>
        <script>
                 //$(":file").filestyle(); //파일첨부버튼 디자인 초기화
        </script>

        <!-- pace -->
        <script src="js/pace/pace.min.js"></script>
        <script>
          var handleDataTableButtons = function() {
              "use strict";
              0 !== $("#datatable-buttons").length && $("#datatable-buttons").DataTable({
                dom: "Bfrtip",
                buttons: [{
                  extend: "copy",
                  className: "btn-sm"
                }, {
                  extend: "csv",
                  className: "btn-sm"
                }, {
                  extend: "excel",
                  className: "btn-sm"
                }, {
                  extend: "pdf",
                  className: "btn-sm"
                }, {
                  extend: "print",
                  className: "btn-sm"
                }],
                responsive: !0
              })
            },
            TableManageButtons = function() {
              "use strict";
              return {
                init: function() {
                  handleDataTableButtons()
                }
              }
            }();
        </script>
        <script type="text/javascript">

          
          $(document).ready(function() {
            //$.noConflict();

            $('#datatable').dataTable();
            $('#datatable-keytable').DataTable({
              keys: true
            });
            $('#datatable-responsive').DataTable();
            $('#datatable-scroller').DataTable({
              ajax: "js/datatables/json/scroller-demo.json",
              deferRender: true,
              scrollY: 380,
              scrollCollapse: true,
              scroller: true
            });
              var table = $('#datatable-fixed-header').DataTable({
              fixedHeader: true
            });
          });


          TableManageButtons.init();
        </script>

        



<!-- 월선택 datepicker -->
<script src="js/monthPicker/MonthPicker.js?time=<?=time()?>"></script>
