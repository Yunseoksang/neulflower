<script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>



<table class="table table-bordered table_out_order_info">

    <thead>
        <tr>
            <th colspan="5" class="company_name big_title">고객사를 선택해주세요</th>
        </tr>
    </thead>

</table>


<table class="table table-bordered table_order_info">

    <thead>
        <tr>
            <th colspan="8" class="order_product_list mtitle">
                주문품목
                <span class="total_client_price_tax right">부가세 : <ii>0원</ii></span><span class="total_client_price right">공급단가 : <ii>0원</ii></span>
            </th>
        </tr>
    </thead>

    <tbody>
        <tr class="title origin">
            <th scope="row" class="mth pr_td">번호</th>
            <th scope="row" class="mth pr_td">비고</th>
            <th scope="row" class="mth pr_td">품목</th>
            <th scope="row" class="mth pr_td">단가</td>
            <th scope="row" class="mth pr_td">부가세</td>
            <th scope="row" class="mth pr_td">수량</th>
            <th scope="row" class="mth pr_td">합계</td>
            <th scope="row" class="mth pr_td order_product_edit_td"><i class="fa fa-gear"></i></td>

        </tr>
        <tr class="hide origin sample">
            <td class="product_number pr_td"> </td>
            <td class="bigo pr_td"> </td>
            <td class="product_name pr_td"> </td>
            <td class="client_price info_td_right pr_td"> </td>
            <td class="client_price_tax info_td_right pr_td"> </td>

            <td class="order_count pr_td"> </td>
            <td class="total_client_price_sum info_td_right pr_td"> </td>
            <td class="order_product_edit_td  pr_td">

                 <?
                if($_REQUEST['mode'] != "complete" && $_REQUEST['mode'] != "cancel"){?>

                <ul class="setting_tool nav navbar-right panel_toolbox" style="min-width:unset;">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-ellipsis-v" style="color:#00b8ff;"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="javascript:" class="order_product_del">삭제</a>
                            </li>
                            <li><a href="javascript:" class="order_product_edit">수정</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <button type="button" class="btn btn-info btn-xs hide btn_order_product_edit_save">저장</button>
                <button type="button" class="btn btn-danger btn-xs hide btn_order_product_edit_cancel">취소</button>

                <?}
                ?>

            </td>

        </tr>
    </tbody>
</table>



<table class="table table-bordered table_delivery_info" id="table_delivery_info">
    <thead>
        <tr>
            <th colspan="4" class="order_info mtitle">
                주문정보


                <?
                if($_REQUEST['mode'] != "complete" && $_REQUEST['mode'] != "cancel"){?>
                <button class="btn btn-primary order_edit right btn-xs" style="float: right;">수정</button>

                <?}
                ?>



            </th>
        </tr>
    </thead>
    <tbody>

        <tr class="tr_flower tr_photos">
            <th scope="row" class="mth">완료사진</th>
            <td class="photos" colspan="3">
            </td>
        </tr>
        <tr class="tr_flower tr_storage hide">
            <th scope="row" class="mth">출고점</th>
            <td class="out_storage" >
                <span class="out_storage_name blue"></span>
                <div class="storage_info hide">
                    <span class="storage_info_detail"></span>
                </div>
            </td>
            <th scope="row" class="mth">진행상태</th>
            <td class="out_order_status blue"></td>

        </tr>



        <tr >
            <th scope="row" class="mth">주문고객</th>
            <td class="order_name"></td>
            <th scope="row" class="mth">부서/사번</th>
            <td class="order_company_tel"></td>
        </tr>
        <tr>
            <th scope="row" class="mth">받는고객</th>
            <td class="r_name"></td>
            <th scope="row" class="mth">부서/사번</th>
            <td class="r_company_tel"></td>
        </tr>
        <tr class="tr_flower">
            <th scope="row" class="mth">배달일시</th>
            <td class="r_time" colspan="3"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">배달장소</th>
            <td class="address" colspan="3"></td>
        </tr>
        <tr class="tr_flower">
            <th scope="row" class="mth">메세지종류</th>
            <td class="messageType"></td>
            <th scope="row" class="mth">경조유형</th>
            <td class="eType"></td>
        </tr>
        <tr class="tr_flower">
            <th scope="row" class="mth">경조사어</th>
            <td class="msgTitle" colspan="3"></td>
        </tr>

        <tr class="tr_flower">
            <th scope="row" class="mth">보내는분</th>
            <td class="sender_name" colspan="3"></td>
        </tr>


        <tr>
            <th scope="row" class="mth">첨부파일</th>
            <td class="attachment" colspan="3"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">결제선택</th>
            <td class="paymentType" colspan="3"></td>
        </tr>
        <tr class="tr_flower">
            <th scope="row" class="mth">고객사<br>요청사항</th>
            <td class="delivery_memo" colspan="3"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">본사요청사항</th>
            <td class="admin_notice" colspan="3"><textarea class="form-data" id="admin_notice"></textarea></td>
        </tr>

        <tr>
            <th scope="row" class="mth">본사담당자</th>
            <td class="head_officer">
                <input type="text" id="head_officer" class="form-control" name="head_officer" >
            </td>
            <th scope="row" class="mth">담당자메모</th>
            <td class="admin_memo">
                <input type="text" id="admin_memo" class="form-control" name="admin_memo" >
            </td>
        </tr>

        <?
        if($_REQUEST['mode'] != "order" ){?>

        <tr class="tr_flower">
            <th scope="row" class="mth">인수자</th>
            <td class="receiver_name" >
            <input type="text" id="receiver_name" class="form-control col-md-5 col-xs-12" name="receiver_name"  >

            </td>
            <th scope="row" class="mth">인수시간</th>
            <td class="received_time">
            <input type="text" id="received_time" class="form-control col-md-5 col-xs-12" name="received_time"   >

            </td>


        </tr>
        <tr class="tr_flower">
            <th scope="row" class="mth">지점 발주액</th>
            <td class="agency_order_price" >
            </td>
            <th scope="row" class="mth"></th>
            <td class="">
               
            </td>


        </tr>
        <?}?>


        <?php
        if($_REQUEST['mode'] != "order"){?>



        <tr class="tr_req tr_photo_upload">
            <th scope="row" class="mth">사진올리기<br>(여러장가능)</th>
            <td class="photos_upload" colspan="3">


                <div class="div_attach">

                    <div class="col-md-9 col-sm-9 col-xs-12" id="multi_input_div">
                    <input type="file" class="files" id='files' name="files[]" multiple>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                    <button class="btn btn-info onlyPhotos btn_save_out_order_complete" style="margin-left:-50px;">사진만 업로드</button>
                    </div>

                </div>
                <div class="clear"></div>

    <!-- 
                <div class="div_attach">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input type="file"  class="files" name="multi_file[]" />
                    </div>
                </div>
                <div class="clear"></div>

                <div class="div_attach">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input type="file" class="files"  name="multi_file[]" />
                    </div>
                </div>
                <div class="clear"></div> -->



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



            </td>
        </tr>

        <?}?>


        <tr class="status_order tr_order">
            <th scope="row" class="mth">출고지점</th>
            <td class="out_storage">
            <?php
                if($_REQUEST['mode'] == "order"){?>


                      <div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:0px;padding-right:0px;">
                        <div id="out_storage_wrapper" class="hide">
                        <select id="out_storage" class="select2_single form-control" tabindex="-1" style="display: none;">
                            <option value="0">화훼 출고지점 선택</option>
                            <?php
                            
                            $sel_storage = mysqli_query($dbcon, "select * from ".$db_flower.".storage where storage_type='지점' order by storage_name ") or die(mysqli_error($dbcon));
                            $sel_storage_num = mysqli_num_rows($sel_storage);

                            if ($sel_storage_num > 0) {
                            while($data_storage = mysqli_fetch_assoc($sel_storage)) {?>
                                <option value="<?=$data_storage['storage_idx']?>" ><?=$data_storage['storage_name']?></option>
                            
                            <?}
                            }
                            ?>
                        </select>
                        </div>


                        <div id="out_storage_sangjo_wrapper" class="hide">
                        <select id="out_storage_sangjo" class="select2_single form-control" tabindex="-1" style="display: none;">
                            <option value="0">상조 출고지 선택</option>
                            <?php
                            
                            $sel_storage = mysqli_query($dbcon, "select * from ".$db_sangjo.".storage order by storage_name ") or die(mysqli_error($dbcon));
                            $sel_storage_num = mysqli_num_rows($sel_storage);

                            if ($sel_storage_num > 0) {
                            while($data_storage = mysqli_fetch_assoc($sel_storage)) {?>
                                <option value="<?=$data_storage['storage_idx']?>"><?=$data_storage['storage_name']?></option>
                            
                            <?}
                            }
                            ?>
                        </select>
                        </div>
                        


                      </div>
                <?}

                ?>
            
            </td>
            <th scope="row" class="mth">지점<br>발주금액</th>
            <td class="agency_order_price">
               <input type="text" id="agency_order_price" class="form-control" name="agency_order_price"  >
            </td>
        </tr>


        <?php
        if($_REQUEST['mode'] == "req" || $_REQUEST['mode'] == "complete" ){
            if($admin_info['super_flower_permission'] == 1 ){?>

        
                <tr class="tr_branch">
                    <th scope="row" class="mth">출고협력사</th>
                    <td class="out_branch">
                    <?php
                        if($_REQUEST['mode'] == "req"  || $_REQUEST['mode'] == "complete" ){?>


                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:10px;padding-right:0px;">
                                <div id="out_storage_wrapper" class="hide">
                                <select id="out_branch" class="select2_single form-control" tabindex="-1" style="display: none;">
                                    <option value="0">------------------</option>
                                    <?php
                                    
                                    $sel_storage = mysqli_query($dbcon, "select * from ".$db_flower.".storage where storage_type='협력사' order by storage_name ") or die(mysqli_error($dbcon));
                                    $sel_storage_num = mysqli_num_rows($sel_storage);

                                    if ($sel_storage_num > 0) {
                                    while($data_storage = mysqli_fetch_assoc($sel_storage)) {?>
                                        <option value="<?=$data_storage['storage_idx']?>" ><?=$data_storage['storage_name']?></option>
                                    
                                    <?}
                                    }
                                    ?>
                                </select>
                                </div>



                            </div>
                        <?}

                        ?>
                    
                    </td>
                    <th scope="row" class="mth">협력사<br>발주금액</th>
                    <td class="branch_price">
                    <input type="text" id="branch_price" class="form-control" name="branch_price"  >
                    </td>
                </tr>

        <?}
    
            }

        ?>


        
        <?php
        if($_REQUEST['mode'] == "order"){?>

        <tr  class="tr_sangjo_move">
            <th scope="row" class="mth"> </th>
            <td class="admin_notice" colspan="3">
                
            <div class="checkbox">
                          <label class="">
                            <div class="icheckbox_flat-green " style="position: relative;">
                            <input type="checkbox" id='move_checked' class="flat"  style="position: absolute; opacity: 0;">
                            </div> 이동지시서를 동시에 작성합니다. 
                          </label>
                        </div>
        
           </td>
        </tr>

        <?}?>


    </tbody>
</table>

<?php
if($_REQUEST['mode'] == "order"){?>
<div class="div_button tr_order">
    <button class="btn btn-success btn_save_out_order" >배송요청</button>
    <button class="btn btn-danger btn_cancel_out_order" >주문취소</button>
</div>

<?}else if($_REQUEST['mode'] == "req"){?>

<div class="div_button tr_req">
    <button class="btn  btn-success btn_save_out_order_complete" >배송완료</button>
    <button class="btn  btn-danger btn_cancel_out_order" >주문취소</button>

</div>

<?}else if($_REQUEST['mode'] == "complete"){
    if($admin_info['super_flower_permission'] == 1 ){?>
    
        <div class="div_button tr_req">
            <button class="btn  btn-info btn_save_out_order_branch" >협력사 발주정보 저장</button>
        </div>

<?}}


?>


<!-- select2 -->
<script>
  $(document).ready(function() {
    $("#out_storage.select2_single").select2({
      placeholder: "화훼 출고지 선택",
      allowClear: true
    });
    $("#out_branch.select2_single").select2({
      placeholder: "출고협력사 선택",
      allowClear: true
    });


    $("#out_storage_sangjo.select2_single").select2({
      placeholder: "상조 출고지 선택",
      allowClear: true
    });

  });
</script>
<!-- /select2 -->