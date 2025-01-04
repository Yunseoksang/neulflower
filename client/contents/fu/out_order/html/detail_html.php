

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
            <th colspan="8" class="company_info mtitle">
            주문정보 <span class="manager">(주문자:  )</span>
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th scope="row" class="mth">품목</th>
            <td class="product_name" colspan="5"></td>
            <th scope="row" class="mth">합계금액</td>
            <td class="total_client_price_sum info_td_right"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">수량</th>
            <td class="order_count"></td>
            <th scope="row" class="mth">단가</td>
            <td class="client_price info_td_right"></td>
            <th scope="row" class="mth">공급가</td>
            <td class="total_client_price info_td_right"></td>
            <th scope="row" class="mth">부가세</td>
            <td class="total_client_price_tax info_td_right"></td>

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
        
        
        


    </tbody>
</table>


<div class="div_button">
<!--<button class="btn btn-success btn_save_out_order" >출고지시</button>-->
<button class="btn btn-danger btn_cancel_out_order" >주문취소</button>
</div>



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