$(document).ready(function(){



    $(document).on("click","#btn_bill_approve",function(){
        change_bill_status("approve");
    });
    $(document).on("click","#btn_bill_modify_ask",function(){
        change_bill_status("modify");
    });

    $(document).on("click",".btn_print_bill",function(){

        var yyyymm = $("#yyyymm_right_bill").val();
        console.log(yyyymm);
        if(yyyymm == ""){
            var now = new Date();	// 현재 날짜 및 시간
            var year = now.getFullYear();	// 연도
            var month = (now.getMonth()+1).toString().padStart(2,'0');;	    // 월
            yyyymm= year+month;
        }

        var consulting_idx = $("#detail_section_bill").attr("consulting_idx");

        window.open("dashboard_print.php?page=bill/print_bill&mode=bill&cidx="+consulting_idx+"&yyyymm="+yyyymm,"거래명세서","width=1000,height=800,top=100,left=100");
    });



    $(document).on("keyup","input[name='right_keyword']",function(){
        $("#datatable-bill-popup tr:not(.sample)").hide();
        $("#datatable-bill-popup tr:contains('"+$(this).val()+"')").show();
    });





    $("input").autocomplete({
        focus: function(event, ui) {
          return false;
        }
      });

    $('#yyyymm_right_bill').MonthPicker({

        dateFormat: 'yymmdd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        OnAfterChooseMonth: function() {  //onSelect 이벤트
            //alert($(this).val());
            

            var yyyymm = $("#yyyymm_right_bill").val();
            var consulting_idx = $("#detail_section_bill").attr("consulting_idx");
    
            view_bill(consulting_idx,yyyymm,'','');

            //resetDateRangeMonthly2($(this).val());
        } 
    });



});


function  view_bill(idx,yyyymm,uid,salt){


    if(yyyymm != "" && yyyymm != undefined){
        if(spart == "fl"){
            var url = "./contents/bill/api/getMonthlyFlBill.php";
    
        }else if(spart == "fu"){
            var url = "./contents/bill/api/getMonthlyFuBill.php";
    
        }else if(spart == "sj"){
            var url = "./contents/bill/api/getMonthlySjBill.php";
    
        }
    
        var str = "consulting_idx="+idx+"&yyyymm="+yyyymm; //
    
    }else{ // 이메일 거래멩세서 바로보기

        //print_bill.php 의 자바스크립트에서 호출함
        var url = "../contents/bill/api/getMonthlyBill.php";
        var str = "uid="+encodeURIComponent(uid)+"&salt="+encodeURIComponent(salt); //

    }


    console.log(url);
    console.log(str);


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var data = result.data;
                    var bill_data = result.bill_data;

                    console.log(data);
                    $(".title_left").hide();


                    var consulting_info = result.consulting_info;


                    //var unique_number = result.yyyymm + consulting_info.consulting_idx.padStart(6,'0');

                    //$("#bill_title").text(" 거 래 명 세 서 "+bill_data.bill_month+"("+bill_data.t_category1_name+")");
                    $("#bill_rdate").text(bill_data.rdate);
                    $("#bill_unique_number").text(bill_data.unique_number);

                    if(bill_data.bill_status == "발송완료/수락대기"){
                        $("#btn_bill_approve").removeClass("hide").show();
                        $("#btn_bill_modify_ask").removeClass("hide").show();

                    }else if(bill_data.bill_status == "승인완료"){
                        $("#btn_bill_approved").removeClass("hide").show();

                    }else if(bill_data.bill_status == "수정요청"){
                        $("#btn_bill_modify_asked").removeClass("hide").show();

                    }


                    $("#table_receiver").find(".r_company_name").html(consulting_info.company_name);
                    $("#table_receiver").find(".r_ceo_name").html(consulting_info.ceo_name);
                    $("#table_receiver").find(".r_biz_num").html(consulting_info.biz_num);
                    $("#table_receiver").find(".r_address").html(consulting_info.address);
                    $("#table_receiver").find(".r_biz_part").html(consulting_info.biz_part);
                    $("#table_receiver").find(".r_biz_type").html(consulting_info.biz_type);
                    $("#table_receiver").find(".r_tel").html(consulting_info.tel);
                    //$("#table_receiver").find(".r_email").html();



                    
                    $("#bill_list_table tbody tr:not(.sample)").remove();


                    var total_price_sum = 0; //공급가액
                    var total_price_tax = 0; //세액 합계
                    for(var i=0;i<data.length;i++){
                        var $tr = $("#bill_list_table").find("tr.sample").clone(true);
                        $tr.removeClass("sample").removeClass("hide");

                        var date = new Date(data[i].order_date);
                        var mm = date.getMonth()+1;
                        var dd = date.getDate();

                        $tr.find(".blt_mm").html(mm.toString().padStart(2,'0'));
                        $tr.find(".blt_dd").html(dd.toString().padStart(2,'0'));

                        $tr.find(".blt_product").html(data[i].product_name);
                        $tr.find(".blt_etc").html(data[i].bigo);
                        $tr.find(".blt_to_name").html(data[i].to_name);

                        /*
                        if(parseInt(data[i].sum_order_count) > 1){
                            $tr.find(".blt_product").append(" 등");
                        }
                        */
                        $tr.find(".blt_amount").html(data[i].sum_order_count);

                        $tr.find(".blt_price").html(number_format(data[i].price));
                        $tr.find(".blt_price_sum").html(number_format(data[i].price_sum));
                        $tr.find(".blt_vat").html(number_format(data[i].price_tax));
                        //$tr.find(".blt_etc").html();

                        total_price_sum = total_price_sum + parseInt(data[i].price_sum);
                        total_price_tax = total_price_tax + parseInt(data[i].price_tax);



                        $("#bill_list_table tbody").append($tr);

                        //console.log(data[i].oocp_idx);
                        // console.log($tr.html());
                        // console.log(data.sql_aaa);

                    }




                    if(data.length < 10){
                        var namo = 10 - data.length;

                        for(var i=0;i<namo;i++){
                            var $tr = $("#bill_list_table").find("tr.sample").clone(true);
                            $tr.removeClass("sample").removeClass("hide");
                            $("#bill_list_table tbody").append($tr);


                        }
                    }

                    var total_calcu = total_price_sum + total_price_tax; //합계금액(부가세 포함)

                    $("#bill_list_table").find("tfoot td.blt_price_sum").text(number_format(total_price_sum));
                    $("#bill_list_table").find("tfoot td.blt_vat").text(number_format(total_price_tax));
                    $("#total_tr_table").find(".ttt_3").text(geKoreanNumber(total_calcu));
                    $("#total_tr_table").find(".ttt_5").text(number_format(" ￦ "+total_calcu));


                    //$("#right_total_list_cnt").html("총 :"+result.recordsFiltered);
                    if(typeof print_mode !== "undefined" && print_mode == "ok"){
                        window.print();
                        print_mode = "";
                    }
                    
                }else{
                    var msg = result.msg;
                    //window.alert(msg);
                }
            }, //success
            error : function( jqXHR, textStatus, errorThrown ) {
                //alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
            }
    }); //ajax
}


function geKoreanNumber(val) {
    val = val.toString();
    var numKor = new Array("", "일", "이", "삼", "사","오","육","칠","팔","구","십");                                  // 숫자 문자
    var danKor = new Array("", "십", "백", "천", "", "십", "백", "천", "", "십", "백", "천", "", "십", "백", "천");    // 만위 문자열
    var result = "";
    
    if(val && !isNaN(val)){
        // CASE: 금액이 공란/NULL/문자가 포함된 경우가 아닌 경우에만 처리
        
        for(var i = 0; i < val.length; i++) {
            var str = "";
            var num = numKor[val.charAt(val.length - (i+1))];
            if(num != "")   str += num + danKor[i];    // 숫자가 0인 경우 텍스트를 표현하지 않음
            switch(i){
                case 4:str += "만";break;     // 4자리인 경우 '만'을 붙여줌 ex) 10000 -> 일만
                case 8:str += "억";break;     // 8자리인 경우 '억'을 붙여줌 ex) 100000000 -> 일억
                case 12:str += "조";break;    // 12자리인 경우 '조'를 붙여줌 ex) 1000000000000 -> 일조
            }

            result = str + result;
        }
        
        // Step. 불필요 단위 제거
        if(result.indexOf("억만") > 0)    result = result.replace("억만", "억");
        if(result.indexOf("조만") > 0)    result = result.replace("조만", "조");
        if(result.indexOf("조억") > 0)    result = result.replace("조억", "조");
        
        
        
        //result = result + "원";
    }
    
    return result ;
  }
  
  //const result = geKoreanNumber(123456789);
  // result: 일억 이천삼백사십오만 육천칠백팔십구

  
function number_format(num){ //숫자 세자리 컴마
    if(num == undefined || num == ""){
        return 0;
    }
    
    var new_num = num.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    return new_num;
}



function change_bill_status(mode){

    if(mode != "approve" && mode != "modify"){
        return;
    }


    //print_bill.php 의 자바스크립트에서 호출함
    var url = "../contents/bill/api/updateBill.php";
    var str = "mode="+mode+"&uid="+encodeURIComponent(en_uid)+"&salt="+encodeURIComponent(en_salt); //




    console.log(url);
    console.log(str);


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    if(mode == "approve"){
                        window.alert('거래내역 승인되었습니다');
                        $("#btn_bill_approve").hide();
                        $("#btn_bill_modify_ask").hide();
                        $("#btn_bill_approved").removeClass("hide").show();

                    }else if(mode == "modify"){
                        window.alert("거래내역 수정요청되었습니다.");
                        $("#btn_bill_approve").hide();
                        $("#btn_bill_modify_ask").hide();
                        $("#btn_bill_modify_asked").removeClass("hide").show();
                    }
                }else{
                    var msg = result.msg;
                    window.alert(msg);
                }
            }, //success
            error : function( jqXHR, textStatus, errorThrown ) {
                //alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
            }
    }); //ajax
}