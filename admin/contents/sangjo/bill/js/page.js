$(document).ready(function(){

    // DataTable 초기화 후 bill_month 셀의 너비 설정
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url.includes('getList.php')) {
            // DataTable이 로드된 후 bill_month 셀의 너비 설정
            setTimeout(function() {
                $('td.bill_month, th.bill_month').css({
                    'min-width': '118px',
                    'width': '118px',
                    'max-width': '118px'
                });
                
                // 테이블 레이아웃 재계산
                if ($.fn.dataTable.tables) {
                    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                }
            }, 100);
        }
    });


    

    // $(document).on('click','#btn_bill_print', function() { //인쇄하기
    //     console.log("인쇄버튼클릭");

    //     const url = "./dashboard_print.php?consulting_idx=68&category1_idx=3&yyyymm=202308";
        
    //     // 팝업 창을 엽니다.
    //     // 'width'와 'height'를 원하는 크기로 조정하실 수 있습니다.
    //     window.open(url, '_blank', 'width=800,height=600,scrollbars=yes');

        
    // });
    $(document).on("click", ".manager_name_button", function(event) {
        event.preventDefault(); // 클릭 이벤트의 기본 동작을 중지합니다.
        event.stopPropagation(); // 이벤트 버블링을 중지합니다.
    
        var manager_email = checkNull($(this).attr("manager_email"));
        var email_list = checkNull($(this).closest("tr").find("textarea[name='manager_email']").val());
    

        if(manager_email == ""){
            toast('등록된 메일이 없습니다');
            return;

        }

        if (email_list == "") {
            email_list = manager_email;
        } else {
            // 이메일 리스트를 배열로 변환합니다.
            var emailArray = email_list.split(',');
    
            // 중복된 이메일이 있는지 확인합니다.
            if (emailArray.indexOf(manager_email) === -1 && manager_email !== "") {
                emailArray.push(manager_email); // 중복이 아니면 배열에 추가합니다.
            }
    
            // 배열을 다시 문자열로 변환합니다.
            email_list = emailArray.join(',');
        }
    
        $(this).closest("tr").find("textarea[name='manager_email']").val(email_list);
    });


    $(document).on("click",".btn_resend",function(event){ //재발송
        event.preventDefault(); // 클릭 이벤트의 기본 동작을 중지합니다.
        event.stopPropagation(); // 이벤트 버블링을 중지합니다.
        sendBill($(this),"resend");
    });


    $(document).on("click",".btn-bill-approve",function(event){
        event.preventDefault(); // 클릭 이벤트의 기본 동작을 중지합니다.
        event.stopPropagation(); // 이벤트 버블링을 중지합니다.
        statusChange($(this),"approve");
    });


    $(document).on('click','.btn_bigo_edit', function() { //팝업 닫기
        var old_value = $(this).closest("td").attr("old_value");
        if (old_value == undefined) {
            old_value = "";
        }
        var input = $('<input/>', {
            type: 'text',
            class: 'form-control input_bigo',
            name: 'bigo',
            value: old_value
        });
        var button = $('<button/>', {
            class: 'btn btn-success btn-xs btn_save_bigo',
            text: '저장'
        });
        var button_cancel = $('<button/>', {
            class: 'btn btn-danger btn-xs btn_bigo_cancel',
            text: '취소'
        });

        $(this).closest("td").html('').append(input).append(button).append(button_cancel);
    });


    $(document).on('click','.btn-bill-close', function() { //팝업 닫기
        closeDetailPopup();

    });

    
    $(document).on('click','.btn_save_bigo', function() { //비고 저장

        var bigo = $(this).closest("tr").find("input.input_bigo").val();
        var oocp_idx = $(this).closest("tr").attr("oocp_idx");
        save_bigo(oocp_idx,bigo,$(this));

    });

    
    $(document).on('click','.btn_bigo_cancel', function() { //비고 저장
        var old_value = $(this).closest("td.bigo").attr("old_value");
        if(old_value == undefined){
            old_value = "";
        }
        var btn_bigo_edit = '<i class="fa fa-edit pointer btn_bigo_edit" title="입력/수정" ></i>';
        $(this).closest("td.bigo").text(old_value).append(btn_bigo_edit);

    });

    
    $(document).on('keydown','.input_bigo', function() { //비고 저장

        $(this).closest("tr").find(".btn_save_bigo").removeClass("hide").show();

    });
    
    $(document).on('keyup','.input_bigo', function() { //비고 저장

        var old_value = $(this).closest("td.bigo").attr("old_value");
        if(old_value == undefined){
            old_value = "";
        }
        var this_value = $(this).val();

        if(old_value == this_value){
            $(this).closest("tr").find(".btn_save_bigo").hide();

        }else{
            $(this).closest("tr").find(".btn_save_bigo").removeClass("hide").show();

        }


    });






    $(document).on('click','.btn-bill-send', function() { //발송하기

        sendBill($(this),"");
    });




    $(document).on('click','.btn-bill-destroy', function() { //폐기

        statusChange($(this),'destroy');
    });


    // $(document).on('click', function(event) {
    //     if (!$(event.target).closest('#detail_section').length) {
    //         $('#detail_section').hide();
    //     }
    // });
    

    $('.close-link1, #overlay').on('click', function(event) {
        console.log('test');
        event.preventDefault(); // 클릭 이벤트의 기본 동작을 중지합니다.
        event.stopPropagation(); // 이벤트 버블링을 중지합니다.
        //$('#detail_section').hide(); // #detail_section을 숨깁니다.
        closeDetailPopup();

    });

    $('#settlement_start_date').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_3",
        format: 'YYYY년 MM월 DD일',
        locale: {
        daysOfWeek: ['일', '월', '화', '수', '목', '금', '토'],
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        firstDay: 0
        }

    }, function(start, end, label) {
        //$("#r_weekday").val(getDayOfWeek(start));
        //$('#settlement_start_date').val($('#settlement_start_date').val()+" ("+getDayOfWeek(start)+")");
        //console.log(start.toISOString(), end.toISOString(), label);
    });


    $('#settlement_end_date').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_3",
        format: 'YYYY년 MM월 DD일',
        locale: {
        daysOfWeek: ['일', '월', '화', '수', '목', '금', '토'],
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        firstDay: 0
        }

    }, function(start, end, label) {
        //$("#r_weekday").val(getDayOfWeek(start));
        //$('#settlement_start_date').val($('#settlement_start_date').val()+" ("+getDayOfWeek(start)+")");
        //console.log(start.toISOString(), end.toISOString(), label);
    });





    $(document).on('change','#category1,#payment_method,#bill_status', function() {
        console.log($(this).find("option:selected").text());
        reset_filter();
    });


    

    $(document).on('change','select.bill_month', function(e) {
        e.stopImmediatePropagation(); //중복호출 방지
        console.log($(this).find("option:selected").val());
        var yyyymm_text = $(this).find("option:selected").text();
        var yyyymm_val = $(this).find("option:selected").val();
    
        console.log("yyyymm_val:"+yyyymm_val);

        if(yyyymm_val == "직접입력"){
            var thisMonth = $("#yyyymm").val(); //2023년 08월
            if(thisMonth == ""){
                thisMonth = $("#yyyymm").attr("yyyymm");
            }
            thisMonth = convertDateFormat(thisMonth);
    
            let nextMonth = getNextMonth(thisMonth);
    

            $(this).closest("td").html("<input type=text name=bill_month_input class='form-control' value='"+thisMonth+"' style='display:inline-block; width:80px; margin-right:5px;'><button class=' btn_save_bill_month btn btn-danger btn-sm'>변경</button><button class='btn_cancel_bill_month btn btn-dark btn-sm'>취소</button>");

        }else{

            updateBill($(this),"select");

        }
        
    });

    $(document).on('click','.btn_cancel_bill_month', function() {

        var targetMonth = $("#yyyymm").val(); //2023년 08월
        if(targetMonth == ""){
            targetMonth = $("#yyyymm").attr("yyyymm");
        }

        resetYmSelect($(this),targetMonth);
       
    });

    function isValidYYYYMM(new_yyyymm) { //년월 형태 포멧인지 체크
        // 숫자로 변환 가능한 6자리 문자열인지 확인
        if (typeof new_yyyymm !== 'string' || new_yyyymm.length !== 6 || isNaN(Number(new_yyyymm))) {
            return false;
        }
    
        const month = parseInt(new_yyyymm.slice(4, 6), 10);  // 마지막 두 자리를 추출하여 월 값으로 사용
        return month >= 1 && month <= 12;
    }
    $(document).on('click','.btn_save_bill_month', function() {
        var $tr = $(this).closest("tr");
        var new_yyyymm = $tr.find("input[name='bill_month_input']").val();

        console.log('new_yyyymm:'+new_yyyymm);

        if (!isValidYYYYMM(new_yyyymm)) {
            console.log("Invalid format");
            return false;
        }


        updateBill($tr.find("input[name='bill_month_input']"),"input");




    });


    

    // $('input').on('ifChecked', function(event) {
    //     //$("#zero_sale").val("ok");
    //     reset_filter();
    // });
    // $('input').on('ifUnchecked', function(event) {
    //     //$("#zero_sale").val("");

    //     reset_filter();
    // });
      


    var this_now = new Date();
    var todate=this_now.getDate();
    $('.row_c button').each(function() {
        var progressBar = $('<div class="progress-bar"><div class="progress"></div></div>');
        $(this).append(progressBar);

    });

    //check_sdate_ratio(); //지난달 정산일 분포 체크하기


    $('#sdate').on('mouseenter', function() {
        $('#calendar_dom').removeClass('hidden');
    });
    
    $('.input-container').on('mouseleave', function() {
        $('#calendar_dom').addClass('hidden');
      });
    $('#calendar_dom button').on('click', function() {
        var buttonText = $(this).text();
        if (!isNaN(parseInt(buttonText))) {
          buttonText += '일';
        }
        $('#sdate').val(buttonText);
        $('#sdate').attr("realValue",$(this).text());

        $('#calendar_dom').addClass('hidden');
        reset_filter();
    });


    $(document).on("click",".btn_print_detail",function(event){

        event.preventDefault(); // 클릭 이벤트의 기본 동작을 중지합니다.
        event.stopPropagation(); // 이벤트 버블링을 중지합니다.


        var yyyymm = $("#yyyymm_right").val();

        if(yyyymm == ""){
            var now = new Date();	// 현재 날짜 및 시간
            var year = now.getFullYear();	// 연도
            var month = (now.getMonth()+1).toString().padStart(2,'0');;	    // 월
            yyyymm= year+month;
        }
        console.log(yyyymm);
        var consulting_idx = $("#detail_section").attr("consulting_idx");

        window.open("dashboard_print.php?page=sangjo/bill/print_bill&fn=statistics/fu_cnst_m&mode=detail&cidx="+consulting_idx+"&yyyymm="+yyyymm,"거래내역상세","width=1000,height=800,top=100,left=100");
    });


    /*
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

        window.open("dashboard_print.php?page=statistics/fu_cnst_m/print_bill&fn=statistics/fu_cnst_m&mode=bill&cidx="+consulting_idx+"&yyyymm="+yyyymm,"거래명세서","width=1000,height=800,top=100,left=100");
    });
    */



    //$("#detail_section").removeClass("hide");

    $("select[name='search_column']").eq(2).hide();

    $(document).on("click","#datatable-main tr td:not(.exec_bill,.exec_next,.exec_checkbox,.exec_view,.manager_email,.origin_manager_info)",function(){
        // bill_month 셀의 너비 설정
        $('td.bill_month, th.bill_month').attr('style', 'min-width: 118px !important; width: 118px !important; max-width: 118px !important;');
        
        //var fcm_idx = $(this).closest("tr").attr("fcm_idx");
        var yyyymm = $("#yyyymm").val();
        var consulting_idx = $(this).closest("tr").attr("consulting_idx");
        var bill_idx = $(this).closest("tr").attr("bill_idx");

        var category1_idx = $(this).closest("tr").attr("category1_idx");
        var t_category1_name = $(this).closest("tr").find("td.t_category1_name").text();

        var company_name = $(this).closest("tr").find("td.consulting_idx").text();
        $(".right_company_name").html(company_name);
        $("#right_category1_name").html(t_category1_name).attr("category1_idx",category1_idx);

        $("#detail_section").attr("consulting_idx",consulting_idx);
        $("#detail_section").attr("category1_idx",category1_idx);

        //$("#yyyymm_right").val(yyyymm);

        //$("#detail_section").removeClass("hide").show();
        //openDetailPopup();

        $("#detail_section_bill").hide();

        //$("html, body").animate({ scrollTop: 0 }, "fast");


        view_popup(bill_idx,consulting_idx,category1_idx,yyyymm,"tr_click");
        $(this).closest("table").find("tr").removeAttr("last_tr"); //
        $(this).closest("tr").attr("last_tr","ok"); //마지막 클릭한 tr 저장
    });


    // $(document).on("click",".btn_view_detail",function() {
    //     var consulting_idx = $("#detail_section").attr("consulting_idx");
    //     var category1_idx = $("#detail_section").attr("category1_idx");
    //     var yyyymm = $("#yyyymm").val();
    //     view_popup(consulting_idx,category1_idx,yyyymm,"change_calendar");

    // });






    $(document).on("click","#datatable-main .btn-view-bill",function(e){

        e.stopPropagation();
        e.preventDefault();
        //console.log('test');

        


        var yyyymm = $("#yyyymm").val();
        // var consulting_idx = $(this).closest("tr").attr("consulting_idx");
        // var category1_idx = $(this).closest("tr").attr("category1_idx");


        $("#detail_section").hide();

        $("#detail_section_bill").show();


        // $("#detail_section_bill").attr("consulting_idx",consulting_idx);
        // $("#detail_section_bill").attr("category1_idx",category1_idx);

       $("#yyyymm_bill").val(yyyymm);
        //view_bill(consulting_idx,category1_idx,yyyymm);

        var bill_idx = $(this).attr("bill_idx");
        view_bill(bill_idx);



    });



    $(document).on("click",".btn-bill-publish",function(event){


        var consulting_idx = $("#detail_section").attr("consulting_idx");
        var category1_idx = $("#detail_section").attr("category1_idx");
        var yyyymm = $("#yyyymm_popup").val();
        if(yyyymm == ""){
            yyyymm = $("#yyyymm_popup").attr("yyyymm");
        }
    
        var checkedValues = $("#datatable-bill-popup input.icheck[name='oocp_idx[]']:checked").map(function() {
            // "on" 값을 제외하기 위한 필터 추가, value가 없는 첫뻔째 값을 "on" 으로 반환할때가 있기 때문에 제외처리
            if (this.value !== "on") {
                return this.value;
            }
        }).get();



        // 체크된 것이 없으면 모든 값을 배열로 가져오기
        if (checkedValues.length === 0) {
            checkedValues = $("#datatable-bill-popup input.icheck[name='oocp_idx[]']").map(function() {
                // "on" 값을 제외하기 위한 필터 추가,value가 없는 첫뻔째 값을 "on" 으로 반환할때가 있기 때문에 제외처리
                if (this.value !== "on") {
                    return this.value;
                }
            }).get();
        }


        // 합산된 값을 저장할 변수
        var totalSum = 0;

        // 체크된 값들을 기준으로 해당하는 tr의 .total_client_price 값을 합산
        checkedValues.forEach(function(value) {
            var priceText = $("#datatable-bill-popup input.icheck[name='oocp_idx[]'][value='" + value + "']").closest("tr").find(".total_client_price").text();
            var price = parseFloat(priceText.replace(/,/g, '')); // 쉼표 제거 후 숫자로 변환
            if (!isNaN(price)) {
                totalSum += price;
            }
        });

        /* 해설 
        The .map() function is used to create a new array by extracting the value of each checked checkbox.
        this.value refers to the value of the current checkbox being processed by the .map() function.
        .get():

        The .get() method is used to convert the jQuery object into a regular JavaScript array.
        */
        saveBill(consulting_idx,category1_idx,yyyymm,checkedValues,totalSum);

    });







    $(document).on("keyup","input[name='right_keyword']",function(){
        $("#datatable-bill-popup tr:not(.sample)").hide();
        $("#datatable-bill-popup tr:contains('"+$(this).val()+"')").show();
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
        Button: false, // 버튼 사용 여부
        IsRTL: false, // RTL 방향 사용 여부
        MonthFormat: 'yy년 mm월', // 월 표시 형식
        Position: { my: 'left top', at: 'left bottom', collision: 'none' }, // 팝업 위치 설정
        OnAfterChooseMonth: function() {  //onSelect 이벤트
            //alert($(this).val());
            
            //resetDateRangeMonthly($(this).val());

            reset_filter();
            $("#xtitle_yyyymm").html($(this).val());
            $('#yyyymm').attr("yyyymm",$(this).val().replace("년 ","").replace("월",""));
        } 
    });



    
/*

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
    
            view_bill(consulting_idx,yyyymm);

            //resetDateRangeMonthly2($(this).val());
        } 
    });
*/



    $('#yyyymm_popup').MonthPicker({

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
            



            var yyyymm = $("#yyyymm_popup").val();
            if(yyyymm == ""){
                yyyymm = $("#yyyymm_popup").attr("yyyymm");
            }
            var consulting_idx = $("#detail_section").attr("consulting_idx");
            var category1_idx = $("#detail_section").attr("category1_idx");


            if(consulting_idx == undefined || category1_idx == undefined){
                toast("오류입니다");
                //$("#detail_section").hide();
                closeDetailPopup();

                return;
            }
    
            view_popup("",consulting_idx,category1_idx,yyyymm,"change_month");

            //resetDateRangeMonthly2($(this).val());
        } 
    });



/*

    $('#yyyymm_bill').MonthPicker({

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
            



            var yyyymm = $("#yyyymm_bill").val();
            if(yyyymm == ""){
                yyyymm = $("#yyyymm_bill").attr("yyyymm");
            }
            var consulting_idx = $("#detail_section_bill").attr("consulting_idx");
            var category1_idx = $("#detail_section_bill").attr("category1_idx");


            if(consulting_idx == undefined || category1_idx == undefined){
                toast("오류입니다");
                //$("#detail_section").hide();
                closeDetailPopup();

                return;
            }
    
            view_bill(consulting_idx,category1_idx,yyyymm);

            //resetDateRangeMonthly2($(this).val());
        } 
    });

*/






});





function openDetailPopup() {
    $('#overlay').show();
    $('#detail_section_bill').hide();
    $('#detail_section').removeClass("hide").show();
}

function openBillPopup() {
    $('#overlay').show();
    $('#detail_section').hide();
    $('#detail_section_bill').removeClass("hide").show();
}


// 팝업 닫기
function closeDetailPopup() {
    $('#overlay').hide();
    $('#detail_section').hide();
    $('#detail_section_bill').hide();
}

function resetDateRangeMonthly(dValue){


    var txt1 = dValue.replace("년 ","");
    //txt = txt.replace("월","");
    startDate = txt1.replace("월","");
    endDate = startDate;
    // var ex1 = startDate.split("/");
    // var y1 = parseInt(ex1[0]);
    // var m1 = parseInt(ex1[1]);
    // var last = new Date(y1,m1,0);
    // var last_date = last.getDate();
    // endDate = txt1.replace("월","/"+last_date);

    //startDate = txt;
    //endDate = txt;


    date_apply = "on";
    date_part = "yyyymm";
    $('#datatable-main').DataTable().ajax.reload();
}



function reload(){
    $('#datatable-main').DataTable().ajax.reload();
}





////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////


function  view_popup(bill_idx,consulting_idx,category1_idx,yyyymm,mode){

    console.log("yyyymm1:"+yyyymm);

    console.log("bill_idx:"+bill_idx);


    //$("#detail_section").removeClass("hide").show();
    openDetailPopup();

    var now = new Date();	// 현재 날짜 및 시간

    if(yyyymm == ""){
        var year1 = now.getFullYear();	// 연도
        var month2 = (now.getMonth()+1).toString().padStart(2,'0');;	    // 월
        yyyymm= year1+"년 "+month2+"월";
    }


    console.log("year2:"+year2);
    console.log("month2:"+month2);



    // 년도와 월을 추출
    var year2 = yyyymm.substr(0, 4);
    var month2 = yyyymm.substr(6, 2);

    console.log("year2:"+year2);
    console.log("month2:"+month2);


    var only_number_yyyymm = year2+month2;
    $("#yyyymm_popup").val(yyyymm).attr("yyyymm",year2+month2);
    // 해당 년월의 마지막 날짜를 구함
    var lastDayOfMonth = new Date(year2, month2, 0);


    if(mode == "tr_click"){





        var sdate = $("#datatable-main tr[consulting_idx='"+consulting_idx+"']").find("td.sdate").attr("sdate");
        //var category1_idx = $("#datatable-main tr[consulting_idx='"+consulting_idx+"']").attr("category1_idx");

        //console.log(sdate);

        if(parseInt(sdate) > 0){
            //console.log("2----"+sdate);

            var settlement_end_date = yyyymm+" "+sdate+"일";

            var parts = settlement_end_date.split(/[년월일]/).filter(Boolean);
            //settlement_end_date를 정규 표현식을 사용하여 "년", "월", "일" 문자를 기준으로 분할합니다. 그런 다음 filter(Boolean)을 사용하여 빈 문자열을 제거
            var year = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10);
            var day = parseInt(parts[2], 10);



            // 해당 날짜의 Date 객체 생성
            var date = new Date(year, month - 1, day);

            // 한 달 전 날짜를 구함
            date.setMonth(date.getMonth() - 1);

            // 한 달 전 바로 다음 날을 구함
            date.setDate(date.getDate() + 1);

            // 결과를 "yyyy년 mm월 dd일" 형식으로 출력
            var settlement_start_date = date.getFullYear() + "년 " + (date.getMonth() + 1).toString().padStart(2, '0') + "월 " + date.getDate().toString().padStart(2, '0') + "일";

        }else{
            //if(sdate == "말일" || sdate == "건별" || sdate == "상시" || sdate == "미지정"){
            var settlement_start_date = yyyymm+" 01일";
            var settlement_end_date = yyyymm+" "+lastDayOfMonth.getDate()+"일";
        }


        $("#settlement_start_date").val(settlement_start_date);
        $("#settlement_end_date").val(settlement_end_date);
        //$('#settlement_end_date').datepicker("setDate", new Date(settlement_end_date));
        //$('#settlement_start_date').datepicker("setDate", new Date(settlement_start_date));


        //변경된 날짜가 달력의 초기 세팅값으로 되게 변경. setDate
        $('#settlement_start_date').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_3",
            format: 'YYYY년 MM월 DD일',
            locale: {
            daysOfWeek: ['일', '월', '화', '수', '목', '금', '토'],
            monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            firstDay: 0,
            setDate: new Date(settlement_start_date)
            }

        });

        //변경된 날짜가 달력의 초기 세팅값으로 되게 변경. setDate
        $('#settlement_end_date').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_3",
            format: 'YYYY년 MM월 DD일',
            locale: {
            daysOfWeek: ['일', '월', '화', '수', '목', '금', '토'],
            monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
            firstDay: 0,
            setDate: new Date(settlement_end_date)
            }

        });

    }else if(mode == "change_calendar"){
        var settlement_start_date = $("#settlement_start_date").val();
        var settlement_end_date = $("#settlement_end_date").val();
    }else if(mode == "change_month"){
        //
        var settlement_start_date = yyyymm+" 01일";
        var settlement_end_date = yyyymm+" "+lastDayOfMonth.getDate()+"일";
    }



    var url = "./contents/sangjo/bill/api/getMonthlyConsultingDetail.php";
    var str = "consulting_idx="+consulting_idx+"&yyyymm="+yyyymm+"&start_date="+settlement_start_date+"&end_date="+settlement_end_date+"&category1_idx="+category1_idx; //
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var data = result.data;
                    $(".right_company_name").html(result.consulting_info.company_name);

                    $("#datatable-bill-popup tbody tr:not(.sample)").remove();


                    for(var i=0;i<data.length;i++){

                        var $tr = $("#datatable-bill-popup").find("tr.sample").clone(true);
                        $tr.removeClass("sample").removeClass("hide");
                        var this_color = "";


                        $tr.attr("oocp_idx",data[i].oocp_idx);
                        $tr.attr("io_idx",data[i].io_idx); // 발주창고 지정하기 전에는 값이 없을수도 있음.
                        $tr.attr("bill_idx",data[i].bill_idx); // 발주창고 지정하기 전에는 값이 없을수도 있음.


                        var new_yyyymm = yyyymm.replace(/\D/g, "");//yyyymm 숫자형태로만
                        $tr.find(".bill_month").attr("bill_month",new_yyyymm);//

                        $tr.find("input.bill_checkbox").val(data[i].oocp_idx);
                        $tr.find("td.bigo").attr("old_value",data[i].bigo);




                        $tr.find(".bill_status").html(data[i].bill_status);
                        
                        if(data[i].bill_status == "수정요청"){
                            $tr.find(".bill_status").html("<span class='red' style='display:block; margin-top:-5px;'>"+data[i].bill_status+"</span>");

                        }else if(data[i].bill_status == "" || data[i].bill_status == null || data[i].bill_status == undefined){
                            $tr.find(".bill_status").html("미발행");
                        }

                        if(data[i].unique_number != null){
                            $tr.find(".unique_number").html(data[i].unique_number);

                        }else if(data[i].unique_number == "" || data[i].unique_number == null || data[i].unique_number == undefined){
                            $tr.find(".unique_number").html("미발행");
                        }


                        if(data[i].bill_idx != null && data[i].bill_idx != undefined && data[i].bill_status != "수정요청"  && data[i].bill_status != "폐기" ){ //조회한 달이 명세서 이미 발행했으면



                            $tr.find(".bill_month").attr("bill_month",data[i].bill_yyyymm);
                            $tr.find(".bill_month").html("<span class='blue'>"+data[i].bill_yyyymm+"</span>");
                            $tr.find("td.bill_check").html("");
                            $tr.find(".bill_month").html(data[i].bill_yyyymm);

                            if(data[i].bill_yyyymm != new_yyyymm){
                                this_color = "#cccdd3";
                            }

                            $(".btn-bill-publish").hide();
                            $(".btn-bill-complete").show().removeClass("hide");
                            $(".btn-bill-close").show().removeClass("hide");
    
    
                        }else{//조회한 달이 명세서 발행 안했으면, bill_idx is null


                            var scheck_execpt = "";
                            var scheck_diff_month = "";

                            if(data[i].bill_yyyymm == "-1"){
                                $tr.find(".bill_month").html("발행제외");
                                var scheck_execpt = "selected";
                                this_color = "#cccdd3";
                                $tr.find("td.bill_check").html("");




                            }else if(data[i].bill_yyyymm != null && data[i].bill_yyyymm != undefined){



                                scheck_diff_month = '<option value="'+data[i].bill_yyyymm+'">'+data[i].bill_yyyymm+'</option>';


                                if(data[i].bill_yyyymm != new_yyyymm){ // 변경저장해둔 정산예정월이 선택한 정산월과 다른경우 배경색깔 어둡게
                                    this_color = "#cccdd3";
                                    $tr.find("td.bill_check").html("");

                                }


                            }else{ // bill_idx 와 bill_yyyymm 값이 모두 null 일때. 기본 보여주기 모드
                                //scheck_diff_month = '<option value="'+data[i].bill_yyyymm+'">'+data[i].bill_yyyymm+'</option>';

                            }



                            let nextMonth = getNextMonth(new_yyyymm);

                            $tr.find(".bill_month").html('<select class="form-control bill_month" name="bill_month">'+scheck_diff_month+'<option value="1">'+new_yyyymm+'</option><option value="2">'+nextMonth+'</option><option value="직접입력">직접입력</option><option value="발행제외" '+scheck_execpt+'>발행제외</option></select>');



                            $(".btn-bill-publish").show().removeClass("hide");
                            $(".btn-bill-complete").hide();
                            $(".btn-bill-close").show().removeClass("hide");

    
                        }


    

                        $tr.find("td,select").css("background-color",this_color);


                        //$tr.find(".oocp_idx").html(data[i].oocp_idx.padStart(8,'0'));
                        $tr.find(".oocp_idx").html(data[i].oocp_idx);

                        $tr.find(".io_status").html(data[i].io_status);
                        $tr.find(".order_date").html(data[i].order_date);
                        $tr.find(".out_date").html(data[i].out_date);


                        if(!checkDateDifference(data[i].order_date, only_number_yyyymm)){
                            $tr.find(".order_date").html("<span class='blue'>"+data[i].order_date+"</span>");

                        }


                        $tr.find(".product_name").html(data[i].product_name);
                        $tr.find(".order_count").html(data[i].order_count);
                        $tr.find(".client_price").html(number_format(data[i].client_price));
                        $tr.find(".total_client_price").html(number_format(data[i].total_client_price));
                        $tr.find(".total_client_price_tax").html(number_format(data[i].total_client_price_tax));
                        $tr.find(".to_name").html(data[i].to_name);


                        var bigo ;
                        if(data[i].bigo == null || data[i].bigo == undefined){
                            bigo = "";
                        }else{ 
                            bigo = data[i].bigo;
                        }
                        $tr.find(".bigo").text(bigo);
                        var btn_bigo_edit = '<i class="fa fa-edit pointer btn_bigo_edit" title="입력/수정" ></i>';
                        $tr.find("td.bigo").append(btn_bigo_edit);

                        $("#datatable-bill-popup tbody").append($tr);

                        // console.log(data[i].oocp_idx);
                        // console.log($tr.html());
                        // console.log(data.sql_aaa);

                    }


                    //선택한 bill_idx 에 해당하는 거래내역에 빨간테두리
                    $("#datatable-bill-popup tbody").find("td.unique_number").removeClass("red_border");
                    $("#datatable-bill-popup tbody").find("tr[bill_idx='"+bill_idx+"']").find("td.unique_number").addClass("red_border");
                    


                    $("#datatable-bill-popup tbody tr input.bill_checkbox").iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green',
                        increaseArea: '20%' // optional
                    });



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

////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////


function checkDateDifference(order_date, only_number_yyyymm) {

    // order_date에서 년월을 추출
    let orderDate = new Date(order_date);
    let orderYearMonth = orderDate.getFullYear().toString() + 
                         (orderDate.getMonth() + 1).toString().padStart(2, '0');

    // 비교
    if (orderYearMonth !== only_number_yyyymm) {
      //console.log(`Difference found at index ${i}: ${orderYearMonth} vs ${only_number_yyyymm}`);
      return 0;
    }else{
      return 1;
    }
  
}


function saveBill(consulting_idx,category1_idx,yyyymm,oocp_idx_list,totalSum){
    if(yyyymm == ""){
        window.alert('정산월 정보가 없습니다');
        return;
    }




    console.log("oocp_idx_list:"+oocp_idx_list);
    console.log("oocp_idx_list.length:"+oocp_idx_list.length);


    var url = "./contents/sangjo/bill/api/saveBill.php";
    var str = "consulting_idx="+consulting_idx+"&category1_idx="+category1_idx+"&yyyymm="+yyyymm+"&oocp_idx_list="+oocp_idx_list; //
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    //var data = result.data;

                    var bill_idx = result.data.bill_idx;

                    console.log(result);
                    //$(".title_left").hide();
                    toast("거래명세서가 저장되었습니다");
                    closeDetailPopup();
                    // $(".btn-bill-publish").hide();
                    // $(".btn-bill-complete").removeClass("hide").show();
                    // $(".btn-bill-close").removeClass("hide").show();
                    var $this_tr = $("#datatable-main").find("tr[consulting_idx='"+consulting_idx+"'][category1_idx='"+category1_idx+"'][last_tr='ok']");
                    $this_tr.removeAttr("last_tr");
                    var sum_total_client_price = $this_tr.find("td.sum_total_client_price").text();
                    var cnt = $this_tr.find("td.cnt").text();
                    sum_total_client_price = parseFloat(sum_total_client_price.replace(/,/g, '')); // 쉼표 제거 후 숫자로 변환

                    $this_tr.attr("bill_idx",bill_idx);
                    $this_tr.find("td.sum_total_client_price").text(totalSum);
                    $this_tr.find("td.bill_status").html("저장/미발송");
                    $this_tr.find("td.exec_next").html("<button class='btn btn-dark btn-xs btn-bill-destroy'>폐기</button>");


                    if(totalSum == sum_total_client_price){
                        $this_tr.find("td.exec_view").html("<button class='btn btn-success btn-xs btn-view-bill' bill_idx='"+bill_idx+"'>거래명세서</button><button class='btn btn-info btn-xs btn-bill-send' bill_idx='" + bill_idx + "'>발송하기</button>");
                        $this_tr.find("td.bill_status").html("저장/미발송");


                    }else{
                        var namoji = sum_total_client_price - totalSum;
                        var cnt_namoji = cnt - oocp_idx_list.length;

                        console.log("namoji:"+namoji);
                        $this_tr.find("td.cnt").text(oocp_idx_list.length);

                        $this_tr.find("td.exec_view").html("<button class='btn btn-success btn-xs btn-view-bill' bill_idx='"+bill_idx+"'>거래명세서</button><button class='btn btn-info btn-xs btn-bill-send' bill_idx='"+bill_idx+"'>발송하기</button>");
                        $this_tr.find("td.bill_status").html("저장/미발송");

                        

                        var $copy_tr = $this_tr.clone(true);
                        $copy_tr.removeAttr("bill_idx");
                        $copy_tr.find("td.sum_total_client_price").text(namoji);
                        $copy_tr.find("td.cnt").text(cnt_namoji);


                        $copy_tr.find("td.exec_view").html("");
                        $copy_tr.find("td.bill_status").html("<button class='btn btn-danger btn-xs btn-bill'>미발행</button>");

                        $this_tr.after($copy_tr);



                    }

                    $this_tr.find('td').css('backgroundColor', 'rgba(92, 184, 92, 0.5)') // 연두색으로 초기 설정
                    .animate({
                        backgroundColor: 'transparent' // 투명하게 만들기
                    }, 3000); // 3초에 걸쳐서






  
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



//function  view_bill(consulting_idx,category1_idx,yyyymm){
function  view_bill(bill_idx){

    console.log("view_bill 호출");
    openBillPopup();

    // if(yyyymm == ""){
    //     var now = new Date();	// 현재 날짜 및 시간
    //     var year = now.getFullYear();	// 연도
    //     var month = (now.getMonth()+1).toString().padStart(2,'0');;	    // 월
    //     yyyymm= year+month;
    // }

    //var url = "./contents/statistics/fu_cnst_m/api/getMonthlyConsultingBill.php";
    var url = "./contents/sangjo/bill/api/getMonthlyConsultingBill.php";

    //var str = "consulting_idx="+consulting_idx+"&category1_idx="+category1_idx+"&yyyymm="+yyyymm; //
    var str = "bill_idx="+bill_idx; //

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var data = result.data;
                    var bill_info = result.bill_info;
                    var consulting_info = result.consulting_info;

                    console.log(data);
                    //$(".title_left").hide();

                    //$("#bill_title").text("거 래 명 세 서 "+result.yyyymm+"("+result.category1_name+")");


                    if(consulting_info !== null){
                        $("#table_receiver").find(".r_company_name").html(consulting_info.company_name);
                        $("#table_receiver").find(".r_ceo_name").html(consulting_info.ceo_name);
                        $("#table_receiver").find(".r_biz_num").html(consulting_info.biz_num);
                        $("#table_receiver").find(".r_address").html(consulting_info.address);
                        $("#table_receiver").find(".r_biz_part").html(consulting_info.biz_part);
                        $("#table_receiver").find(".r_biz_type").html(consulting_info.biz_type);
                        $("#table_receiver").find(".r_tel").html(consulting_info.tel);
                        //$("#table_receiver").find(".r_email").html();

                    }else{
                        $("#table_receiver").find(".r_company_name").html("");
                        $("#table_receiver").find(".r_ceo_name").html("");
                        $("#table_receiver").find(".r_biz_num").html("");
                        $("#table_receiver").find(".r_address").html("");
                        $("#table_receiver").find(".r_biz_part").html("");
                        $("#table_receiver").find(".r_biz_type").html("");
                        $("#table_receiver").find(".r_tel").html("");
                    }

                    if(bill_info === null || typeof bill_info.unique_number === "undefined"){

                        toast("데이터가 없습니다");


                        $("#bill_rdate").text("");
                        $("#bill_unique_number").text("");

                        $("#bill_list_table tbody tr td").html("");
                        
                        $("#bill_list_table tfoot tr td.blt_price_sum").html("");
                        $("#bill_list_table tfoot tr td.blt_vat").html("");

                        $("#total_tr_table tbody th.ttt_3").html("");
                        $("#total_tr_table tbody th.ttt_5").html("");

                        

                    }else{

                    
                        $("#bill_rdate").text(bill_info.rdate);
                        $("#bill_unique_number").text(bill_info.unique_number);




                        
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

                            /*
                            if(parseInt(data[i].sum_order_count) > 1){
                                $tr.find(".blt_product").append(" 등");
                            }
                            */
                            $tr.find(".blt_amount").html(data[i].sum_order_count);

                            $tr.find(".blt_price").html(number_format(data[i].price));
                            $tr.find(".blt_price_sum").html(number_format(data[i].price_sum));
                            $tr.find(".blt_vat").html(number_format(data[i].price_tax));
                            $tr.find(".blt_to_name").html(data[i].to_name);
                            $tr.find(".blt_etc").html(data[i].bigo);

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
  
  //const result = geKoreanNumber(123456789);
  // result: 일억 이천삼백사십오만 육천칠백팔십구


function reset_filter(){

    
    var txt1 = $("#yyyymm").val().replace("년 ","");
    var yyyymm = txt1.replace("월","");
    if(yyyymm == ""){
        yyyymm = cfGetDate("yyyyMM");
    }

    var category1 = $("#category1 option:selected").val();
    var sdate = $("#sdate").attr('realValue');
    if(sdate == undefined){
        sdate = "";
    }
    var payment_method = $("#payment_method option:selected").val();
    var bill_status = $("#bill_status option:selected").val();

    if($(".zero_sale_label .icheckbox_flat-green").hasClass("checked")){
        var zero_sale = ""; //checkbox 선택되었을때와 안되었을때 값이 반대로 나오고 있는 상황이라서 거꾸로 임시 대처
    }else{
        var zero_sale = "ok";
    }


    //var url = "./contents/sangjo/bill/api/getList.php";
    custom_filter = "category1="+category1+"&yyyymm="+yyyymm+"&sdate="+sdate+"&payment_method="+payment_method+"&bill_status="+bill_status+"&zero_sale="+zero_sale; //
    $('#datatable-main').DataTable().ajax.reload();





}

//날짜에 앞자리 0이 들어가면 빼기
function cfSetDeleteZero(target) {     
    
    var num = parseInt(target);
    
    var str = num.length >= 2 && num.substr(0, 1) == 0 ? num.substr(1, num.length - 1) : num;
    
    return str.toString();
}

function cfSetAddZero(target) {     
    
    var num = parseInt(target);
    
    var str = num >= 9 ? num : "0" + num;
    
    return str.toString();
}


function cfGetDate(format){  
    
    var result = "";
    var date = new Date();
    
    if(format !== undefined){
        
        result = format.replace(/(yyyy|MM|dd|hh|mm|ss)/gi, function($obj){
        
            switch ($obj) {
                
                case "yyyy": return date.getFullYear();                 
                case "MM": return cfSetAddZero(date.getMonth() + 1);

             
                default: return $obj;
            }
        });
        
    } else {
                    
        result = date.getFullYear() + "-" + cfSetAddZero(date.getMonth() + 1) + "-" + cfSetAddZero(date.getDate());
    }
    
    return result;
}




function getCurrentAndNextMonth() {
    let date = new Date();
    let thisYear = date.getFullYear();
    let thisMonth = date.getMonth() + 1; // JavaScript의 getMonth()는 0부터 11까지의 값을 반환합니다.
    let nextMonth = thisMonth + 1;
    let nextYear = thisYear;

    // 달이 12월인 경우, 다음달은 다음해 1월이 됩니다.
    if(thisMonth == 12){
        nextMonth = 1;
        nextYear += 1;
    }

    // 달이 한 자리 수인 경우 앞에 0을 붙여 두 자리로 만듭니다.
    if(thisMonth < 10){
        thisMonth = '0' + thisMonth;
    }

    if(nextMonth < 10){
        nextMonth = '0' + nextMonth;
    }

    let thisMonthStr = thisYear.toString() + thisMonth.toString();
    let nextMonthStr = nextYear.toString() + nextMonth.toString();

    return [thisMonthStr, nextMonthStr];
}


function getNextMonth(yyyymm) {
    let year = parseInt(yyyymm / 100);
    let month = yyyymm % 100;

    // 월을 증가시킵니다.
    month++;

    // 월이 13이라면, 연도를 1 증가시키고 월을 1월로 설정합니다.
    if (month > 12) {
        year++;
        month = 1;
    }

    // 년도와 월을 결합하여 'YYYYMM' 형태의 문자열을 만듭니다.
    // 'padStart' 함수를 사용하여 월이 한 자리수인 경우 앞에 '0'을 추가합니다.
    return parseInt('' + year + String(month).padStart(2, '0'));
}


function updateBill($obj,mode)
{
    var oocp_idx = $obj.closest("tr").attr("oocp_idx");
    var yyyymm_text = "";
    var yyyymm_val = "";

    if(mode == "select"){
        var yyyymm_text = $obj.find("option:selected").text();
        var yyyymm_val = $obj.find("option:selected").val();
    
    }else if(mode == "input"){
        var yyyymm_text = $obj.val();
        var yyyymm_val = $obj.val();
    
    }



    var url = "./contents/sangjo/bill/api/updateBill.php";
    var str = "oocp_idx="+oocp_idx+"&bill_month="+yyyymm_text+"&mode=bill_month"; //
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var data = result.data;

                    console.log(data);
                    //$(".title_left").hide();

                    console.log("mode:"+mode);
                    console.log("yyyymm_text:"+yyyymm_text);


                    if(mode == "input"){
                        resetYmSelect($obj,yyyymm_text);
                    }

                    var yyyymm = $("#yyyymm_popup").val(); //2023년 08월
                    if(yyyymm == ""){
                        yyyymm = $("#yyyymm_popup").attr("yyyymm");
                    }
                    var new_yyyymm = yyyymm.replace(/\D/g, "");//yyyymm 숫자형태로만
                    console.log("yyyymm:"+yyyymm);

                    console.log(yyyymm_text+":"+new_yyyymm);

                    if(yyyymm_text == new_yyyymm){ //변경한 월과 조회한 월이 같으면 row 배경색상 제거
                        $obj.closest("tr").find("td,select").css("background-color","");
                        $obj.closest("tr").find("input.icheck").closest('div.icheckbox_flat-green').show();
                        console.log("같음조건성립:"+yyyymm_text+":"+new_yyyymm);


                    }else if(yyyymm_text == "발행제외" || yyyymm_val == "2" || yyyymm_text != new_yyyymm){

                        console.log("다름조건성립:"+yyyymm_text+":"+new_yyyymm);

                        $obj.closest("tr").find("td,select").css("background-color","#cccdd3");
                        $obj.closest("tr").find("input.icheck").closest('div.icheckbox_flat-green').hide();


                    }else{
                        console.log("추가조건성립:"+yyyymm_text+":"+new_yyyymm);

                    }
                



                    toast('저장되었습니다');




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


function resetYmSelect($obj,targetMonth){
    let nextMonth = getNextMonth(targetMonth);


    $obj.closest("td").html('<select class="form-control bill_month" name="bill_month"><option value="1">'+targetMonth+'</option><option value="2">'+nextMonth+'</option><option value="직접입력">직접입력</option><option value="발행제외">발행제외</option></select>');
}




function convertDateFormat(dateStr) {
    // "yyyymm" 형태인 경우 그대로 반환
    if (/^\d{4}\d{2}$/.test(dateStr)) {
        return dateStr;
    }

    // "yyyy년 mm월" 형태인 경우 변환
    let yyyy = dateStr.split("년")[0].trim();
    let mm = dateStr.split("년")[1].split("월")[0].trim();

    if (mm.length === 1) {
        mm = "0" + mm;
    }

    return yyyy + mm;
}



function statusChange($obj,mode){

     var consulting_idx = $obj.closest("tr").attr("consulting_idx");
     var category1_idx = $obj.closest("tr").attr("category1_idx");
     var bill_idx = $obj.closest("tr").attr("bill_idx");


   
   var url = "./contents/sangjo/bill/api/updateBill.php";
   var str = "mode="+mode+"&consulting_idx="+consulting_idx+"&category1_idx="+category1_idx+"&bill_idx="+bill_idx; //
   $.ajax( { 
           type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
           success: function(result){
               
               var result_status = result.status;
               if(result_status == 1)
               {
                   var data = result.data;

                   //console.log(data);
                  

                    if(mode == "destroy"){
                        toast("폐기되었습니다");
                        $obj.closest("tr").attr("bill_idx","");

                        $obj.closest("tr").find("td.exec_view").html("");
                        $obj.closest("tr").find("td.bill_status").html("<button class='btn btn-danger btn-xs btn-bill'>미발행</button>");
                        //$obj.closest("tr").find("td.exec_next").html("<button class='btn btn-warning btn-xs btn-bill-save'>저장하기</button>");
                        $obj.closest("tr").find("td.exec_next").html("");

                    }else if(mode == "approve"){
                        $obj.closest("tr").find("td.bill_status").html("승인완료");
                        toast("승인되었습니다.");
                        $obj.remove();

                        //$obj.replace("<button class='btn btn-primary btn-xs btn-bill-tax'>세금계산서발행</button>");

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

function validateEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}


function sendBill($obj,mode){

    


    //var consulting_idx = $obj.closest("tr").attr("consulting_idx");
    //var category1_idx = $obj.closest("tr").attr("category1_idx");
    var $tr = $obj.closest("tr");
    var bill_idx = $tr.find("button.btn-view-bill").attr("bill_idx");
    //var bill_month = $("#yyyymm").attr("yyyymm");

    if(bill_idx == undefined || bill_idx == "0"){
        window.alert("요청정보가 올바르지 않습니다.")
        return;
    }else{
        console.log(bill_idx);
    }


    var emails;
    if(mode == "resend"){
        emails = $obj.closest("div").find("button.m_email").text().split(',');

    }else{
        emails = $tr.find("textarea[name='manager_email']").val().replace(" ","" ).replace("\n","" ).split(',');

    }



    var isValid = true;

    for (var i = 0; i < emails.length; i++) {
        var email = emails[i].trim();
        if (!validateEmail(email)) {
            isValid = false;
            break;
        }
    }

    if (!isValid) {

        window.alert('유효한 이메일 유형이 아닙니다.')
        return;
    }




    var url = "./contents/sangjo/bill/api/sendBill.php";
    var str = "bill_idx="+bill_idx+"&emails="+emails+"&mode="+mode;

    console.log(str);

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var data = result.data;

                    //console.log(data);

                    toast(result.msg);

                    if(mode != "resend"){
                        $tr.find("td.bill_status").html("발송완료/수락대기");
                        $tr.find(".btn-bill-send").after('<button class="btn btn-primary btn-xs btn-bill-approve">직권승인</button>').remove();
                        $tr.find('td').css('backgroundColor', 'rgba(92, 184, 92, 0.5)') // 연두색으로 초기 설정
                        .animate({
                            backgroundColor: 'transparent' // 투명하게 만들기
                        }, 3000); // 3초에 걸쳐서


                    }else if(mode == "resend"){
                        $obj.closest("td").find("button").css('backgroundColor', 'rgba(92, 184, 92, 0.5)') // 연두색으로 초기 설정
                        .animate({
                            backgroundColor: 'transparent' // 투명하게 만들기
                        }, 3000); // 3초에 걸쳐서
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



function save_bigo(oocp_idx,bigo,$obj){

    var url = "./contents/sangjo/bill/api/updateBill.php";
    var str = "oocp_idx="+oocp_idx+"&bigo="+bigo+"&mode=bigo";
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var data = result.data;

                    toast('저장되었습니다');
                    $obj.closest("td.bigo").attr("old_value",bigo);
                    $obj.closest("td.bigo").html(bigo).append('<i class="fa fa-edit pointer btn_bigo_edit" title="입력/수정"></i>');


                   


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



