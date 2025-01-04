$(document).ready(function() {



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




    $(document).on("change","select.date_part",function(){
        $('#datatable-main').DataTable().ajax.reload();

    });



    $("input").autocomplete({
        focus: function(event, ui) {
          return false;
        }
      });



    //모달창에서 문자 보내기 버튼 클릭
    $(document).on("click",".btn_modal_save",function(){
        sendSMS();

    });



    //템플릿추가 버튼클릭
    $(document).on("click",".btn_templete_add",function(){
        $("#table_sms .group1").hide();
        $("#table_sms .group2").show().removeClass("hide");
        $("#template_title").focus();
        $("#table_sms .group3").addClass("border_blue");

    });

    //템플릿추가 취소
    $(document).on("click",".btn_templete_cancel",function(){
        $("#table_sms .group1").show();
        $("#table_sms .group2").hide();
        $("#table_sms .group3").removeClass("border_blue");

    });

    //템플릿추가 저장
    $(document).on("click",".btn_templete_save",function(){
        save_template('save');

    });

    //템플릿수정
    $(document).on("click",".btn_templete_edit",function(){
        save_template('edit');

    });

    //템플릿삭제
    $(document).on("click",".btn_templete_del",function(){

        $.confirm({
            title: '', //타이틀
            content: '선택하신 템플릿을 삭제할까요?',  //메세지
            closeIcon: true, //우측상단 닫기버튼 보일지 여부
            closeIconClass: 'fa fa-close',  //닫기 버튼 아이콘
            type: 'red', //확인창 색상(옵션)
            boxWidth: '300px', //확인창 width
            useBootstrap: false,  //width 적용할때 false 로 해줘야 적용됨.

            
            buttons: {
                "확인": function () {
                    save_template('del');
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });

    });

    //템플릿기본값적용
    $(document).on("click",".btn_templete_default",function(){
        save_template('default');

    });


    //템플릿 선택 변경
    $(document).on("change","#sms_template",function(){

        var template_subject = $("#sms_template option:selected").attr("template_subject");
        var template_content = $("#sms_template option:selected").attr("template_content");

        $("#template_subject").val(template_subject);
        $("#template_content").val(template_content);

        apply_sms_template();
    });


    //템플릿 변경 실시간 메세지 반영
    $(document).on("keyup","#template_content",function(){

        apply_sms_template();
    });
    //템플릿 변경 실시간 메세지 반영
    $(document).on("keyup","#template_subject",function(){

        apply_sms_template();
    });



    //sms 보내기 모달창 열기
    $(document).on("click",".btn_send_sms",function(){

        var out_order_idx = $(this).closest("tr").attr("out_order_idx");
        $("#modal-sms").attr("out_order_idx",out_order_idx);

        var url = "./contents/flower/board/api/getReadySms.php";
        var str = "out_order_idx="+out_order_idx; //

        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        var order_info = result.data.order_info;
                        var template_list = result.data.template_list;



                        $("#modal-sms").attr("order_name",order_info.order_name); //주문자
                        $("#modal-sms").attr("order_tel",order_info.order_tel); //주문번호 = 문자수신번호

                        $("#modal-sms").attr("r_name",order_info.r_name); //받는분
                        $("#modal-sms").attr("r_tel",order_info.r_tel); //받는번호

                        $("#modal-sms").attr("order_product_title",order_info.order_product_title); //상품명

                        $("#sms_receiver_tel").val(order_info.order_tel);
                        $("#table_sms .order_company_tel span").text(order_info.order_company_tel);

                        //console.log("admin_permission.pm_super:"+admin_permission.pm_super);
                        $("#modal-sms #sms_template").html(""); //초기화
                        for(var i=0;i<template_list.length;i++){

                            if(i == 0){
                                $("#template_subject").val(template_list[i].template_subject);
                                $("#template_content").val(template_list[i].template_content);
                            }

                            if(template_list[i].default_selected == "1"){
                                $("#modal-sms #sms_template").append('<option value="'+template_list[i].template_idx+'" selected template_subject="'+template_list[i].template_subject+'" template_content="'+template_list[i].template_content+'">'+template_list[i].template_title+'</option>');

                                $("#template_subject").val(template_list[i].template_subject);
                                $("#template_content").val(template_list[i].template_content);

                            }else{
                                $("#modal-sms #sms_template").append('<option value="'+template_list[i].template_idx+'"  template_subject="'+template_list[i].template_subject+'" template_content="'+template_list[i].template_content+'">'+template_list[i].template_title+'</option>');

                            }
                        }




                        apply_sms_template(); //메세지 템플릿 적용



                    }else{
                        var msg = result.msg;
                        toast(msg);

                    }
                }, //success
                error : function( jqXHR, textStatus, errorThrown ) {
                    alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
                }
        }); //ajax
        
    });








});



function save_template(mode){

    // var out_order_idx = $("#modal-sms").attr("out_order_idx");
    // if(out_order_idx == "" || out_order_idx == undefined ){
    //     toast('주문고유번호 정보가 없습니다. 새로고침후 시도해주세요.');
    //     return;
    // }
    if(mode == "" || mode == undefined){
        toast('입력정보에 오류가 있습니다. 새로고침후 시도해주세요.');
        return;
    }


    switch (mode) {
        case 'save':
            var template_title = checkNull($("#template_title").val());
            var template_subject = checkNull($("#template_subject").val());
            var template_content = checkNull($("#template_content").val());

            if(template_title == ""){
                toast('템플릿 타이틀을 입력해주세요.');
                $("#template_title").focus();
                return;
            }
            if(template_subject == ""){
                toast('템플릿 제목을 입력해주세요.');
                $("#template_subject").focus();
                return;
            }
            if(template_content == ""){
                toast('템플릿 내용을 입력해주세요.');
                $("#template_content").focus();
                return;
            }

            template_content = template_content.replace("&","$$$$$"); //&기호 임시 대체
            
            var str = "mode="+mode+"&template_title="+template_title+"&template_subject="+template_subject+"&template_content="+template_content; //


            break;
        case 'edit':
            var template_idx = $("#sms_template option:selected").val();
            var template_subject = checkNull($("#template_subject").val());
            var template_content = checkNull($("#template_content").val());

            if(template_idx == ""){
                toast('수정할 템플릿을 선택해주세요.');
                $("#sms_template").focus();
                return;
            }
            if(template_subject == ""){
                toast('템플릿 제목을 입력해주세요.');
                $("#template_subject").focus();
                return;
            }
            if(template_content == ""){
                toast('템플릿 내용을 입력해주세요.');
                $("#template_content").focus();
                return;
            }

            if(template_content.includes('&')){
                toast('템플릿 내용에 특수문자 "&"가  입력해주세요.');
                $("#template_content").focus();
                return;
            }
            template_content = template_content.replace("&","$$$$$"); //&기호 임시 대체
            
            var str = "mode="+mode+"&template_idx="+template_idx+"&template_subject="+template_subject+"&template_content="+template_content; //


            break;
        case 'del':
            var template_idx = $("#sms_template option:selected").val();
            var str = "mode="+mode+"&template_idx="+template_idx; 

            break;
        case 'default':
            var template_idx = $("#sms_template option:selected").val();
            var str = "mode="+mode+"&template_idx="+template_idx; 

            break;   

    }



    
    var url = "./contents/flower/board/api/saveSmsTemplate.php";


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var info = result.data;
                    var msg = result.msg;
                    toast(msg);

                    switch (mode) {
                        case 'save':
                            $("#table_sms .group1").show();
                            $("#table_sms .group2").hide();
                            $("#table_sms .group3").removeClass("border_blue");

                            $("#sms_template option:selected").removeAttr("selected");
                            $("#sms_template").append("<option value='"+info.template_idx+"' selected template_subject='"+template_subject+"' template_content='"+template_content.replace("$$$$$","&")+"'>"+info.template_title+"</option>");

                            apply_sms_template();
                    
                            break;
                        case 'edit':
                            $("#sms_template option[value='"+template_idx+"']").attr("template_subject",template_subject).attr("template_content",template_content);//템플릿 타이틀 자체는 못 바꿈.
                            apply_sms_template();
                
                            break;
                        case 'del':
                            $("#sms_template option[value='"+template_idx+"']").remove();
                            $("#sms_template option:selected").removeAttr("selected");
                            var first_val = $("#sms_template option").eq(0).val();
                            $("#sms_template").val(first_val); //onchange 이벤트 발생시킴
                            //apply_sms_template();

                            break;
                        case 'default':
                            // 기본 템플릿 저장 완료

                            break;   
                    }


                }else{
                    var msg = result.msg;
                    toast(msg);



                }
            }, //success
            error : function( jqXHR, textStatus, errorThrown ) {
                alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
            }
    }); //ajax



}



function apply_sms_template(){
    var order_name = checkNull($("#modal-sms").attr("order_name")); //주문자
    var order_tel  = checkNull($("#modal-sms").attr("order_tel")); //주문 번호 = 문자수신번호
    var r_name     = checkNull($("#modal-sms").attr("r_name")); //받는분
    var r_tel     = checkNull($("#modal-sms").attr("r_tel")); //받는분

    var order_product_title = checkNull($("#modal-sms").attr("order_product_title")); //상품명

    var template_subject = checkNull($("#template_subject").val()); //템플릿 타이틀
    var template_content = checkNull($("#template_content").val()); //템플릿 컨텐트

    template_subject = template_subject.replace("{{주문자}}",order_name).replace("{{주문자번호}}",order_tel).replace("{{받는분}}",r_name).replace("{{받는분번호}}",r_tel).replace("{{상품명}}",order_product_title);
    template_content = template_content.replace("{{주문자}}",order_name).replace("{{주문자번호}}",order_tel).replace("{{받는분}}",r_name).replace("{{받는분번호}}",r_tel).replace("{{상품명}}",order_product_title);


   $("#sms_subject").val(template_subject);
   $("#sms_content").val(template_content);


}



function sendSMS(){

    var out_order_idx = $("#modal-sms").attr("out_order_idx");
    if(out_order_idx == "" || out_order_idx == undefined ){
        toast('주문고유번호 정보가 없습니다. 새로고침후 시도해주세요.');
        return;
    }



    var template_idx = $("#sms_template option:selected").val();
    var sms_receiver_tel = checkNull($("#sms_receiver_tel").val());
    var sms_subject = checkNull($("#sms_subject").val());
    var sms_content = checkNull($("#sms_content").val());

    var regex = /[^0-9]/g;				// 숫자가 아닌 문자열을 선택하는 정규식
    sms_receiver_tel = sms_receiver_tel.replace(regex, "");   

    if(sms_receiver_tel.length < 10){
        toast('수신자 전화번호를 확인해주세요.');
        $("#sms_receiver_tel").val(sms_receiver_tel).focus();
        return;
    }

    
    sms_content = sms_content.replace("&","$$$$$"); //&기호 임시 대체

    var url = "./contents/flower/board/api/sendSMS.php";
    var str = "out_order_idx="+out_order_idx+"&template_idx="+template_idx+"&sms_receiver_tel="+sms_receiver_tel+"&sms_subject="+sms_subject+"&sms_content="+sms_content;

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    //var info = result.data;
                    $(".btn_modal_dismiss").trigger("click");
                    $("#datatable-main").find("tr[out_order_idx='"+out_order_idx+"']").find(".btn_send_sms").removeClass("btn-primary").addClass("btn-dark");
                    toast(result.msg);
                }else{
                    window.alert(result.msg);
                }
            }, //success
            error : function( jqXHR, textStatus, errorThrown ) {
                alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
            }
    }); //ajax

}


function resetDateRangeMonthly(dValue){


    var txt = dValue.replace("년 ","/");
    startDate = txt.replace("월","/01");

    var ex1 = startDate.split("/");
    var y1 = parseInt(ex1[0]);
    var m1 = parseInt(ex1[1]);
    var last = new Date(y1,m1,0);
    var last_date = last.getDate();
    endDate = txt.replace("월","/"+last_date);


    date_apply = "on";
    date_part = $("select.date_part option:selected").val();
    $('#datatable-main').DataTable().ajax.reload();
}

function reload(){
    $('#datatable-main').DataTable().ajax.reload();
}