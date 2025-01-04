$(document).ready(function(){

    $(document).on("click",".btn_print_detail",function(){


        var yyyymm = $("#yyyymm_right").val();

        if(yyyymm == ""){
            var now = new Date();	// 현재 날짜 및 시간
            var year = now.getFullYear();	// 연도
            var month = (now.getMonth()+1).toString().padStart(2,'0');;	    // 월
            yyyymm= year+month;
        }
        //console.log(yyyymm);
        var storage_idx = $("#detail_section").attr("storage_idx");

        var new_win = window.open("dashboard_print.php?page=statistics/flower_storage_m/print_bill&fn=statistics/flower_storage_m&mode=detail&sidx="+storage_idx+"&yyyymm="+yyyymm,"거래내역상세","width=1000,height=800,top=100,left=100");
        

    });



    $(document).on("click",".btn_print_bill",function(){

        var yyyymm = $("#yyyymm_right_bill").val();
        //console.log(yyyymm);
        if(yyyymm == ""){
            var now = new Date();	// 현재 날짜 및 시간
            var year = now.getFullYear();	// 연도
            var month = (now.getMonth()+1).toString().padStart(2,'0');;	    // 월
            yyyymm= year+month;
        }

        var storage_idx = $("#detail_section_bill").attr("storage_idx");

        var new_win1 = window.open("dashboard_print.php?page=statistics/flower_storage_m/print_bill&fn=statistics/flower_storage_m&mode=bill&sidx="+storage_idx+"&yyyymm="+yyyymm,"거래명세서","width=1000,height=800,top=100,left=100");


    

    });



    $("#detail_section").removeClass("hide");

    $("select[name='search_column']").eq(2).hide();

    $(document).on("click","#datatable-main tr td:not(.exec_bill)",function(){
        var fsm_idx = $(this).closest("tr").attr("fsm_idx");
        var yyyymm = $("#yyyymm").val();
        var flower_storage_idx = $(this).closest("tr").attr("flower_storage_idx");

        var storage_name = $(this).closest("tr").find("td.flower_storage_idx").text();
        $(".right_storage_name").html(storage_name);
        $("#detail_section").attr("storage_idx",flower_storage_idx);

        $("#yyyymm_right").val(yyyymm);

        $("#detail_section").removeClass("hide").show();
        $("#detail_section_bill").hide();


        view_right(flower_storage_idx,yyyymm);
    });





    $(document).on("click","#datatable-main tr td.exec_bill .btn-bill",function(){
        var fsm_idx = $(this).closest("tr").attr("fsm_idx");
        var yyyymm = $("#yyyymm").val();
        var flower_storage_idx = $(this).closest("tr").attr("flower_storage_idx");

        var storage_name = $(this).closest("tr").find("td.flower_storage_idx").text();
        $(".right_storage_name").html(storage_name);
        $("#detail_section_bill").attr("storage_idx",flower_storage_idx);

        $("#yyyymm_right").val(yyyymm);


        $("#detail_section").hide();
        $("#detail_section_bill").removeClass("hide").show();

        view_bill(flower_storage_idx,yyyymm);
    });





    $(document).on("keyup","input[name='right_keyword']",function(){
        $("#datatable-right tr:not(.sample)").hide();
        $("#datatable-right tr:contains('"+$(this).val()+"')").show();
    });














    $(document).on("mouseover","#datatable-main tr",function(){
        $(this).find("td").css("color","#00b8ff");
    }).on("mouseleave","#datatable-main tr",function(){
        $(this).find("td").css("color","");
    })


    $("input").autocomplete({
        focus: function(event, ui) {
          return false;
        }
      });

    $('#yyyymm').MonthPicker({

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
            
            resetDateRangeMonthly($(this).val());
        } 
    });


    $('#yyyymm_right').MonthPicker({

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
            

            var yyyymm = $("#yyyymm_right").val();
            var flower_storage_idx = $("#detail_section").attr("storage_idx");
    
            view_right(flower_storage_idx,yyyymm);

            //resetDateRangeMonthly2($(this).val());
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
            var flower_storage_idx = $("#detail_section").attr("storage_idx");
    
            view_bill(flower_storage_idx,yyyymm);

            //resetDateRangeMonthly2($(this).val());
        } 
    });

});




function resetDateRangeMonthly(dValue){


    var txt = dValue.replace("년 ","");
    txt = txt.replace("월","");
    //startDate = txt.replace("월","/01");

    var ex1 = startDate.split("/");
    var y1 = parseInt(ex1[0]);
    var m1 = parseInt(ex1[1]);
    var last = new Date(y1,m1,0);
    var last_date = last.getDate();
    endDate = txt.replace("월","/"+last_date);

    startDate = txt;
    endDate = txt;


    date_apply = "on";
    date_part = "yyyymm";
    $('#datatable-main').DataTable().ajax.reload();
}



function reload(){
    $('#datatable-main').DataTable().ajax.reload();
}


function  view_right(flower_storage_idx,yyyymm){


    var url = "./contents/statistics/flower_storage_m/api/getMonthlyStorageDetail.php";
    var str = "flower_storage_idx="+flower_storage_idx+"&yyyymm="+yyyymm; //
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var data = result.data;

                    $("#title_line").html(result.storage_info.storage_name);
                    
                    $("#datatable-right tbody tr:not(.sample)").remove();

                    for(var i=0;i<data.length;i++){
                        var $tr = $("#datatable-right").find("tr.sample").clone(true);
                        $tr.removeClass("sample").removeClass("hide");
                        $tr.find(".oocp_idx").html(data[i].oocp_idx.padStart(8,'0'));
                        $tr.find(".r_date").html(data[i].r_date);
                        $tr.find(".product_name").html(data[i].product_name);
                        $tr.find(".order_count").html(data[i].order_count);
                        $tr.find(".sender_name").html(data[i].sender_name);
                        $tr.find(".oocp_idx").html(data[i].oocp_idx);
                        $tr.find(".price_calcu").html(number_format(data[i].price_calcu));

                        $("#datatable-right tbody").append($tr);

                        // console.log(data[i].oocp_idx);
                        // console.log($tr.html());
                        // console.log(data.sql_aaa);

                    }

                    $("#right_total_list_cnt").html("총 :"+result.recordsFiltered);
                    // if(typeof print_mode !== "undefined" && print_mode == "ok"){
                    //     window.print();
                    //     print_mode = "";
                    // }

                    
                }else{
                    var msg = result.msg;
                    window.alert(msg);
                }
            }, //success
            error : function( jqXHR, textStatus, errorThrown ) {
                alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
            }
    }); //ajax
}




function  view_bill(flower_storage_idx,yyyymm){


    var url = "./contents/statistics/flower_storage_m/api/getMonthlyStorageBill.php";
    var str = "flower_storage_idx="+flower_storage_idx+"&yyyymm="+yyyymm; //
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var data = result.data;
                    var storage_info = result.storage_info;

                    //$("#title_line").html(result.storage_info.storage_name);
                    $(".title_left").hide();

                    var unique_number = result.yyyymm + storage_info.storage_idx.padStart(6,'0');

                    $(".unique_number").text(unique_number);


                    $("#table_receiver").find(".r_company_name").html(storage_info.company_name);
                    $("#table_receiver").find(".r_ceo_name").html(storage_info.ceo_name);
                    $("#table_receiver").find(".r_biz_num").html(storage_info.biz_num);
                    $("#table_receiver").find(".r_address").html(storage_info.address);
                    $("#table_receiver").find(".r_biz_part").html(storage_info.biz_part);
                    $("#table_receiver").find(".r_biz_type").html(storage_info.biz_type);
                    $("#table_receiver").find(".r_tel").html(storage_info.tel);
                    //$("#table_receiver").find(".r_email").html();



                    
                    
                    $("#bill_list_table tbody tr:not(.sample)").remove();


                    var total_price_sum = 0; //공급가액
                    var total_price_tax = 0; //세액 합계
                    for(var i=0;i<data.length;i++){
                        var $tr = $("#bill_list_table").find("tr.sample").clone(true);
                        $tr.removeClass("sample").removeClass("hide");

                        var date = new Date(data[i].r_date);
                        var mm = date.getMonth()+1;
                        var dd = date.getDate();

                        $tr.find(".blt_mm").html(mm.toString().padStart(2,'0'));
                        $tr.find(".blt_dd").html(dd.toString().padStart(2,'0'));

                        $tr.find(".blt_product").html(data[i].product_name);

                        
                        if(parseInt(data[i].sum_order_count) > 1){
                            var etc_cnt = parseInt(data[i].sum_order_count) -1;
                            $tr.find(".blt_product").append(" 외 "+etc_cnt+"건");
                        }
                        
                        $tr.find(".blt_amount").html("1");

                        $tr.find(".blt_price").html(number_format(data[i].total_agency_order_price));
                        $tr.find(".blt_price_sum").html(number_format(data[i].total_agency_order_price));
                        $tr.find(".blt_vat").html(number_format(data[i].total_agency_order_price_tax));
                        //$tr.find(".blt_etc").html();

                        total_price_sum = total_price_sum + parseInt(data[i].total_agency_order_price);
                        total_price_tax = total_price_tax + parseInt(data[i].total_agency_order_price_tax);



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
                    window.alert(msg);
                }
            }, //success
            error : function( jqXHR, textStatus, errorThrown ) {
                alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
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
  


function print(printArea)

{

        win = window.open(); 

        self.focus(); 

        win.document.open();

        

        /*

        1. div 안의 모든 태그들을 innerHTML을 사용하여 매개변수로 받는다.

        2. window.open() 을 사용하여 새 팝업창을 띄운다.

        3. 열린 새 팝업창에 기본 <html><head><body>를 추가한다.

        4. <body> 안에 매개변수로 받은 printArea를 추가한다.

        5. window.print() 로 인쇄

        6. 인쇄 확인이 되면 팝업창은 자동으로 window.close()를 호출하여 닫힘

        */

        win.document.write('<html><head><title></title><style>');

        win.document.write('body, td {font-falmily: Verdana; font-size: 10pt;}');

        win.document.write('</style></head><body>');

        win.document.write(printArea);

        win.document.write('</body></html>');

        win.document.close();

        win.print();

        win.close();

}