<script type="text/javascript" src="./js/bootstrap-filestyle.min.js"> </script>



<table class="table table-bordered table_out_order_info">

    <thead>
        <tr>
            <th colspan="5" class="company_name big_title">고객사/th>
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
            </td>

        </tr>
    </tbody>
</table>



<table class="table table-bordered table_delivery_info" id="table_delivery_info">
    <thead>
        <tr>
            <th colspan="4" class="order_info mtitle">
                주문정보
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
            <td class="out_storage" colspan="3">
                <span class="out_storage_name blue"></span>
                <div class="storage_info hide">
                    <span class="storage_info_detail"></span>
                </div>
            </td>
        </tr>


        <tr >
            <th scope="row" class="mth">주문고객</th>
            <td class="order_name"></td>
            <th scope="row" class="mth">부서/사번</th>
            <td class="order_company_tel"></td>
        </tr>
        <tr>
            <th scope="row" class="mth">받는분</th>
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
            <th scope="row" class="mth">주문시<br>요청사항</th>
            <td class="delivery_memo" colspan="3"></td>
        </tr>
<!-- 
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
        </tr> -->

  
        <tr class="tr_flower">
            <th scope="row" class="mth">인수자</th>
            <td class="receiver_name"  colspan="3">
                
            </td>
        </tr>



    </tbody>
</table>


<div class="div_button tr_req">
    <button class="btn  btn-danger btn_cancel_out_order" >주문취소</button>
</div>


