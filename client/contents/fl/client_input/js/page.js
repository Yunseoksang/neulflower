jQuery.expr[":"].Contains = jQuery.expr.createPseudo(function(arg) {
    return function( elem ) {
        return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});



//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){



    $(document).on("change","select[name='juso1']",function() {
        var juso1 = $(this).val();
        //console.log("juso1:"+juso1);
        $("select[name='juso2']").html("<option>--시/군--</option>");
        getJuso2(juso1);
    });

    $(document).on("change","select[name='juso2']",function() {
        var juso1 = $("select[name='juso1']").val();
        var juso2 = $("select[name='juso2']").val();

        $("#address1").val(juso1+" "+juso2);
    });





    var consulting_idx = $("#profile_info").attr("consulting_idx");
    var company_name = $("#profile_info .company_name").text();
    $(".title_left").find("h3").html("주문서 등록 : "+company_name);

    load_client_product(consulting_idx);



    $("input").autocomplete({
        focus: function(event, ui) {
          return false;
        }
      });


    $(document).on("click","ul.ul_product_list li:not(.li_left_header)",function() {
        select_li($(this));
    });


    $(document).on("click","button.btn_cancel",function() {
        $("ul.ul_product_list_right").html("");
        reset_total();
    });

    $(document).on("change","select[name='option_price']",function() {
        reset_total();
    });


    // $(document).on("mouseenter","#r_date",function() {
    //     $(this).trigger("click");
    // });


    // $(document).on("mouseleave",".daterangepicker",function() {
    //     $("#r_date").datepicker("hide");
        
    // });


    /*
    $(document).on("mouseenter","#r_hour",function() {
        $(".rHourLine").removeClass("hide");
        $(".rHourLine").show();
    });



    $(document).on("mouseleave",".rHourLine",function() {
        $(".rHourLine").hide();
        
    });


    $(document).on("change","#r_select_hour",function() {
        $("#r_hour").val($(this).find("option:selected").val());
        $(".rHourLine").hide();
    });




    $(document).on("click","#r_hour",function() {
        $(".rHourLine").removeClass("hide");
        $(".rHourLine").show();
    });




    $(document).on("click",".rHourLine span.li_v",function() {
        $("#r_hour").val($(this).text());
        $(".rHourLine").hide();
        
    });


    $(document).on("mouseenter","#msgTitle,#msgTitle2,#msgTitle3",function() {
        $(".msgTitleLine").removeClass("hide");
        $(".msgTitleLine").show();
    });


    $(document).on("mouseleave",".msgTitleLine",function() {
        $(".msgTitleLine").hide();
        
    });

*/

    $(document).on("click","#msgTitle",function() {

        //$(".msgTitleLine").removeClass("hide");
        //$(".msgTitleLine").show();
        $(".msgTitleLine").attr("msgTarget","msgTitle");

    });

    $(document).on("click","#msgTitle2",function() {

        //$(".msgTitleLine").removeClass("hide");
        //$(".msgTitleLine").show();
        $(".msgTitleLine").attr("msgTarget","msgTitle2");

    });
    $(document).on("click","#msgTitle3",function() {

        //$(".msgTitleLine").removeClass("hide");
        //$(".msgTitleLine").show();
        $(".msgTitleLine").attr("msgTarget","msgTitle3");

    });
    // $(document).on("click",".msgTitleLine",function() {

    //     $(".msgTitleLine").hide();


    // });


    $(document).on("click",".msgTitleLine span.li_v",function() {
        var tbar = $(".msgTitleLine").attr("msgTarget");
        if(tbar == undefined || tbar == ""){
            $("#msgTitle").val($(this).text());
            //$(".msgTitleLine").hide();
        }else{
            $("#"+tbar).val($(this).text());
            //$(".msgTitleLine").hide();
        }

        
    });

    $(document).on("click","#more_msgTitle",function() {
        if(!$(".mt2").is(":visible")){
            $(".mt2").removeClass("hide");
            $(".mt2").show();
            $("#msgTitle2").trigger("focus");
            $(".msgTitleLine").attr("msgTarget","msgTitle2");

        }else if(!$(".mt3").is(":visible")){
            $(".mt3").removeClass("hide");
            $(".mt3").show();
            $("#msgTitle3").trigger("focus");
            $(".msgTitleLine").attr("msgTarget","msgTitle3");


            $(this).hide();
        }
        
    });


    $(document).on("click",".del_msgTitle2",function() {
        $("#msgTitle2").val("");
        $(".mt2").hide();
        $("#more_msgTitle").show();
        $("#msgTitle1").trigger("focus");
        $(".msgTitleLine").attr("msgTarget","msgTitle1");

    });

    
    $(document).on("click",".del_msgTitle3",function() {
        $("#msgTitle3").val("");
        $(".mt3").hide();
        $("#more_msgTitle").show();
        $("#msgTitle2").trigger("focus");
        $(".msgTitleLine").attr("msgTarget","msgTitle2");
        
    });


    //보내는분추가
    $(document).on("click","#btn_add_sender_name",function() {
        //console.log('btn_add_sender_name');
        if($("#sender_name_custom").val() == ""){
            toast("보내는분을 입력해주세요");
            return;
        }
        save_add_sender_name("add",$("#sender_name_custom").val(),"");
    });
    //보내는분 삭제
    $(document).on("click","#btn_del_sender_name",function() {

        if($("#sender_name option:selected").val() == ""){
            toast("삭제할 보내는분을 선택해주세요");

            return;
        }
        save_add_sender_name("del","",$("#sender_name option:selected").val());
    });



    $(document).on("click","button.btn_save_client_order,button.btn_save_out_complete",function() {

        var consulting_idx = $("#profile_info").attr("consulting_idx");

        var outpart = "client_order";
        if($(this).hasClass("btn_save_out_complete")){
            outpart = "out_complete";
        }
        var manager_idx = $("#profile_info").attr("manager_idx");

        var out_order_idx     = checkNull($("button.btn_save_client_order").attr("out_order_idx"));

        var order_name        = checkNull($("#order_name").val());
        var order_tel         = checkNull($("#order_tel").val());
        var order_company_tel = checkNull($("#order_company_tel").val());

        var r_name            = checkNull($("#r_name").val());
        var r_tel             = checkNull($("#r_tel").val());
        var r_company_tel     = checkNull($("#r_company_tel").val());

        var r_date            = checkNull($("#r_date").val());
        var r_hour            = checkNull($("#r_hour").val());
        var address1          = checkNull($("#address1").val());
        var address2          = checkNull($("#address2").val());
        var zipNo             = checkNull($("#zipNo").val());

        var messageType       = checkNull($("input[name='messageType']:checked").val());
        var eType             = checkNull($("#eType").val());
        var msgTitle          = checkNull($("#msgTitle").val());
        var msgTitle2         = checkNull($("#msgTitle2").val());
        var msgTitle3         = checkNull($("#msgTitle3").val());
        var sender_name       = checkNull($("#sender_name_custom").val());


        var delivery_memo     = checkNull($("#delivery_memo").val());
        var paymentType       = checkNull($("input[name='paymentType']:checked").val());




        var total_client_price      = $(".total_client_price").attr("total_client_price");
        var total_client_price_tax  = $(".total_client_price_tax").attr("total_client_price_tax");
        var total_client_price_sum  = $(".total_client_price_sum").attr("total_client_price_sum");


        if(r_date == ""){
            showToast("배달일시를 입력해주세요");
            return;
        }


        if(address1 == ""){
            showToast("배달장소를 입력해주세요");
            return;
        }




        var total_flower_price = 0;
        var total_flower_price_tax = 0;
        var total_flower_price_sum_calcu = 0;

        var flower_product_list = Array();


        $("ul.ul_product_list_right").find("li[product_part='flower']").each(function(index) {

            var client_product_idx = $(this).attr("client_product_idx");
            var cnt = parseInt($(this).find("input[name='cnt']").val());
            var product_name = $(this).find(".li_product_name .pn").text();

            var client_price_sum = parseInt($(this).attr("client_price_sum"));
            var client_price = parseInt($(this).attr("client_price"));
            var client_price_tax = parseInt($(this).attr("client_price_tax"));
            var client_price_sum_calcu = client_price_sum*cnt;
            var client_price_calcu = client_price*cnt;
            var client_price_tax_calcu = client_price_tax*cnt;

            total_flower_price     = total_flower_price + client_price_calcu;
            total_flower_price_tax = total_flower_price_tax + client_price_tax_calcu;
            total_flower_price_sum_calcu = total_flower_price_sum_calcu + client_price_calcu + client_price_tax_calcu;

            var pcnt = {client_product_idx,cnt,product_name,client_price_sum,client_price,client_price_tax,client_price_sum_calcu};
            if(cnt > 0){
                flower_product_list.push(pcnt);

            }

        });


        var total_sangjo_price = 0;
        var total_sangjo_price_tax = 0;
        var total_sangjo_price_sum_calcu = 0;

        var sangjo_product_list = Array();

        $("ul.ul_product_list_right").find("li[product_part='sangjo']").each(function(index) {

            var product_idx = $(this).attr("product_idx");
            var cnt = parseInt($(this).find("input[name='cnt']").val());
            var product_name = $(this).find(".li_product_name .pn").text();

            var client_price_sum = parseInt($(this).attr("client_price_sum"));
            var client_price = parseInt($(this).attr("client_price"));
            var client_price_tax = parseInt($(this).attr("client_price_tax"));
            var client_price_sum_calcu = client_price_sum*cnt;
            var client_price_calcu = client_price*cnt;
            var client_price_tax_calcu = client_price_tax*cnt;


            total_sangjo_price     = total_sangjo_price + client_price_calcu;
            total_sangjo_price_tax = total_sangjo_price_tax + client_price_tax_calcu;
            total_sangjo_price_sum_calcu = total_sangjo_price_sum_calcu + client_price_calcu + client_price_tax_calcu;


            var pcnt = {product_idx,cnt,product_name,client_price_sum,client_price,client_price_tax,client_price_sum_calcu};
            if(cnt > 0){
                sangjo_product_list.push(pcnt);

            }

        });




        var data = {
            mode: outpart,
            consulting_idx: consulting_idx,
            flower_product_list: flower_product_list,
            sangjo_product_list: sangjo_product_list,


            total_flower_price           : total_flower_price,
            total_flower_price_tax       : total_flower_price_tax,
            total_flower_price_sum_calcu : total_flower_price_sum_calcu,



            total_sangjo_price: total_sangjo_price,
            total_sangjo_price_tax:total_sangjo_price_tax,
            total_sangjo_price_sum_calcu: total_sangjo_price_sum_calcu,


            
            total_client_price:total_client_price,
            total_client_price_tax:total_client_price_tax,
            total_client_price_sum:total_client_price_sum,


            out_order_idx     : out_order_idx    ,

            order_name        : order_name       ,
            order_tel         : order_tel        ,
            order_company_tel : order_company_tel,
            r_name            : r_name           ,
            r_tel             : r_tel            ,
            r_company_tel     : r_company_tel    ,
            r_date            : r_date           ,
            r_hour            : r_hour           ,
            address1          : address1         ,
            address2          : address2         ,
            zipNo             : zipNo            ,
            messageType       : messageType      ,
            eType             : eType            ,
            msgTitle          : msgTitle         ,
            msgTitle2         : msgTitle2        ,
            sender_name       : sender_name      ,
            msgTitle3         : msgTitle3        ,
            delivery_memo     : delivery_memo    ,
            paymentType       : paymentType      



        };

        console.log(data);

                

        if(flower_product_list.length > 0 || sangjo_product_list.length > 0){
            //saveClientOrder(data);
            saveClientOrderUpload(data);


        }else{
            showToast('선택된 품목이 없습니다');
        }
    });



    
    $(document).on("change",".input_cnt",function() {

        var cnt = $(this).val();
        if(cnt < 0){
            $(this).val("0");
        }
        cnt = parseInt($(this).val());

        var client_price_sum = parseInt($(this).closest("li").attr("client_price_sum"));
        var new_price = client_price_sum*cnt;
        $(this).parent().find(".client_price_calculation").attr("client_price_calculation",new_price).text(number_format(new_price));

        reset_total();

    });



    
    $(document).on("click",".btn_x_circle",function() {
        var client_product_idx = $(this).closest("li").attr("client_product_idx");
        $(this).closest("li").remove();
        var $li = $("ul.ul_product_list").find("li[client_product_idx='"+client_product_idx+"']")

        $li.find(".icheckbox_flat-green").removeClass("checked");

        reset_total();
           
    });





    
    //select2 변경시 이벤트 호출  //설명 https://select2.org/programmatic-control/events#preventing-events
    $(document).on('select2:select','.select2_single', function (e) {
        var data = e.params.data;
        var this_id = $(this).attr("id");

       if(this_id == "order_client" && data.id >= 0){
            load_client_product(data.id);
            $(".title_left").find("h3").html("주문서 등록 : "+data.text);
        }else if(this_id == "sender_name" && data.id >= 0){
            //$("#btn_del_sender_name").removeClass("hide");
            $("#sender_name_custom").val(data.text);
        }
        // console.log(data.text);  //select 의 text 값
        //console.log(data.id);  //value 값임.
    });




    $(document).on("keyup","#product_filter",function() {
          var keyword = $(this).val();
          var uppper = keyword.toUpperCase()
          console.log(keyword);
          console.log(uppper);

          $("ul.ul_product_list").find("li").hide();
          $("ul.ul_product_list").find("li .li_product_name:contains("+keyword+")").closest("li").show();
          $("ul.ul_product_list").find("li .li_product_name:contains("+uppper+")").closest("li").show();

            
     });
 



});




function saveClientOrderUpload(dataArray){


    var url = "./contents/fl/client_input/api/save_client_order_upload.php";
    //var url = "./contents/consulting/api/uploadFile2.php";
    var data = JSON.stringify(dataArray); //


    var form = jQuery("ajaxFrom")[0];
    var formData = new FormData(form);
    formData.append("data", data);
    
    //formData.append("attachFile", jQuery("#attachFile")[0].files[0]);
    // Read selected files
    var totalfiles = document.getElementById('files').files.length;
    for (var index = 0; index < totalfiles; index++) {
        formData.append("files[]", document.getElementById('files').files[index]);
    }

    //                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,

    jQuery.ajax({
            url : url
        , type : "POST"
        , processData : false
        , contentType : false
        , data : formData
        ,dataType: "json"
        , success: function(result){

            var result_status = result.status;
            if(result_status == 1)
            {
                toast(result.msg);

                window.location.href='?page=fl/out_order/';


                // var attachment = result.data;
                // console.log(attachment);

                // var $attach_tr = $("#attachment_sample").find("tr").clone(true);
                // $attach_tr.attr("attachment_idx",attachment.attachment_idx);
                // var tr_lenth = $("#table_attach_list").find("tbody").find("tr").length;
                // $attach_tr.find(".th_num").text(tr_lenth+1);
                // var filename_ex = attachment.filename.split("/");
                // var this_lenth = filename_ex.length -1;
                // $attach_tr.find(".td_filename a").text(filename_ex[this_lenth]).attr("href",attachment.filename);
                // $("#table_attach_list").find("tbody").append($attach_tr);

                // $("#attachFile").val(null);


               
            }else{
                var msg = result.msg;
                window.alert(msg);
                //console.log(msg);
            }
        }, //success
        error : function( jqXHR, textStatus, errorThrown ) {
            alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
        }
    });
}




function check_null(str){

    if (str == "null" || str == null || str == "" || str == "undefind" || str == undefined) {
        return 0;
    }else{
        return str;
    }

}



function load_client_product(consulting_idx){
    

    var mode = $(".right_set").attr("mode");

    var url = "./contents/fl/client_input/api/get_client_product_info.php";
    var str = "mode="+mode+"&consulting_idx="+consulting_idx; //

    console.log(url);
    console.log(str);

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var obj = result.data;
                    // obj = JSON.stringify(obj); 
                    // obj = JSON.parse(obj);

                    console.log(obj);

                    reset_product_list(obj);




                   $("#memo").val("");

                    if(obj.sender_list.length > 0){
                        $("#sender_name").html("");
                        $("#address").val(obj.sender_list[0].addr);
                        $("#to_name").val(obj.sender_list[0].receiver_name);
                        $("#hp").val(obj.sender_list[0].receiver_hp);
                        $("#memo").val(obj.sender_list[0].memo);
    
                        for(var i=0;i<obj.sender_list.length;i++){
                            var pl = obj.sender_list[i];

                            if(i==0){
                                $("#sender_name").append("<option value='"+pl.client_flower_sender_idx+"' selected>"+pl.sender_name+"</option>");
                                $("#sender_name_custom").val(pl.sender_name);
                            
                            }else{
                                $("#sender_name").append("<option value='"+pl.client_flower_sender_idx+"' >"+pl.sender_name+"</option>");
                            }

    
    
                        }
                    }else{
                        $("#sender_name").html("");    
                    
                    }
                    


                    // $("#order_client.select2_single").select2({
                    //   placeholder: "고객사 선택",
                    //   allowClear: true
                    // });


                    // $("#sender_name.select2_single").select2({
                    //     placeholder: "---- 선택 ----",
                    //     allowClear: true
                    // });



                    do_init();



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




function save_add_sender_name(mode,sender_name,client_flower_sender_idx){
   
    var consulting_idx = $("#order_client").val();
    if(consulting_idx > 0){
        //
    }else{

         showToast('주문고객사를 선택해주세요');
         return;
    }
    var url = "./contents/fl/client_input/api/save_add_sender_name.php";
    var str = "mode="+mode+"&sender_name="+sender_name+"&consulting_idx="+consulting_idx+"&client_flower_sender_idx="+client_flower_sender_idx; //

    console.log(url);
    console.log(str);

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var obj = result.data;
                    // obj = JSON.stringify(obj); 
                    // obj = JSON.parse(obj);

                    console.log(obj);
                    toast(result.msg);

                    if(mode == "add"){
                        $("#sender_name").append("<option value='"+obj.client_flower_sender_idx+"' selected>"+obj.sender_name+"</option>");
                    }else if(mode == "del"){
                        $("#sender_name").find("option[value='"+client_flower_sender_idx+"']").remove();
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


function apply_order_info(info){
    $("#order_name").val(info.order_name);
    $("#order_tel").val(info.order_tel);
    $("#order_company_tel").val(info.order_company_tel);
    $("#r_name").val(info.r_name);
    $("#r_tel").val(info.r_tel);
    $("#r_company_tel").val(info.r_company_tel);
    $("#r_date").val(info.r_date_weekday);
    $("#r_hour").val(info.r_hour);
    $("#address1").val(info.address1);
    $("#address2").val(info.address2);
    $("#zipNo").val(info.zipNo);
    $("#eType").val(info.eType);
    $("#msgTitle").val(info.msgTitle);
    $("#msgTitle2").val(info.msgTitle2);
    $("#msgTitle3").val(info.msgTitle3);
    $("#sender_name_custom").val(info.sender_name);
    $("#delivery_memo").val(info.delivery_memo);

    $(function() {
        var $radios = $('input:radio[name=messageType]');
        if($radios.is(':checked') === false) {
            $radios.filter('[value=Male]').prop('checked', true);
        }
    });
    $('input:radio[name=messageType][value='+info.messageType+']').trigger("click");
    $('input:radio[name=paymentType][value='+info.paymentType+']').trigger("click");

    $("button.btn_save_client_order").attr("out_order_idx",info.out_order_idx);



    // $("#").val(info.);
    // $("#").val(info.);
    // $("#").val(info.);
    // $("#").val(info.);

}




function getJuso2(juso1){

    var url = "/common/api/getAddrList.php";
    var str = "juso1="+juso1; //

    console.log(url);
    console.log(str);

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var obj = result.data.sigungu_list;
                    console.log(obj);

                    $("select[name='juso2']").html("");
                    var $option = "<option>--시/군--</option>";


                    for(var i=0;i<obj.length;i++){
                        $option += "<option value='"+obj[i]+"'>"+obj[i]+"</option>";
                    }

                    $("select[name='juso2']").append($option);

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