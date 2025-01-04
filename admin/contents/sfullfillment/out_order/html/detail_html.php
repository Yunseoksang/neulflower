

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
            <th colspan="4" class="company_info mtitle">
                주문정보 <span class="manager">(주문자:  )</span>
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th scope="row" class="mth">품목</th>
            <td class="product_name"></td>
            <th scope="row" class="mth">단가</td>
            <td class="client_price info_td_right"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">수량</th>
            <td class="order_count"></td>
            <th scope="row" class="mth">합계</td>
            <td class="price_calcu info_td_right"></td>
        </tr>


    </tbody>
</table>



<table class="table table-bordered table_delivery_info">

    <tbody>
        <tr>
            <th scope="row" class="mth">배송지명</th>
            <td class="to_place_name"></td>
        </tr>
        <tr>
            <th scope="row" class="mth">주소</th>
            <td class="to_address"></td>
        </tr>
        <tr>
            <th scope="row" class="mth">받는분</th>
            <td class="to_name"></td>
        </tr>
        <tr>
            <th scope="row" class="mth">전화번호</th>
            <td class="to_hp"></td>
        </tr>
        <tr>
            <th scope="row" class="mth">주문자메모</th>
            <td class="memo"></td>
        </tr>
        <tr>
            <th scope="row" class="mth">첨부파일</th>
            <td class="attachment"></td>
        </tr>
        <tr>
            <th scope="row" class="mth">출고지</th>
            <td class="out_storage">
            <?php
                if($_REQUEST['mode'] == "order"){?>


                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="out_storage" class="select2_single form-control" tabindex="-1" style="display: none;">
                        <option value="0">전체</option>
                        <?php
                        
                        $sel_storage = mysqli_query($dbcon, "select * from storage order by storage_name ") or die(mysqli_error($dbcon));
                        $sel_storage_num = mysqli_num_rows($sel_storage);

                        if ($sel_storage_num > 0) {
                          while($data_storage = mysqli_fetch_assoc($sel_storage)) {?>
                            <option value="<?=$data_storage['storage_idx']?>"><?=$data_storage['storage_name']?></option>

                          
                          <?}
                        }
                        ?>
                          
                        </select>
                        
                      </div>
                <?}

                ?>
            
            </td>
        </tr>

        <tr>
            <th scope="row" class="mth">관리자메모</th>
            <td class="admin_memo"><textarea class="form-data" id="admin_memo"></textarea></td>
        </tr>

    </tbody>
</table>

<?php
if($_REQUEST['mode'] == "order"){?>
<div class="div_button">
<button class="btn btn-success btn_save_out_order" >출고지시</button>
<button class="btn btn-danger btn_cancel_out_order" >주문취소</button>
</div>

<?}

?>



<!-- select2 -->
<script>
  $(document).ready(function() {
    $("#out_storage.select2_single").select2({
      placeholder: "출고지 선택",
      allowClear: true
    });

  });
</script>
<!-- /select2 -->