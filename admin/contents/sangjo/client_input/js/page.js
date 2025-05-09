jQuery.expr[":"].Contains = jQuery.expr.createPseudo(function(arg) {
    return function( elem ) {
        return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});



//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function() {


 
    // 현재 날짜를 yyyy-mm-dd 형식으로 반환하는 함수
    function getCurrentDate() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        var yyyy = today.getFullYear();
        return yyyy + '-' + mm + '-' + dd;
    }

    // order_date 입력 요소에 현재 날짜를 기본값으로 설정
    $('#order_date').val(getCurrentDate());

    $(document).on("click","button.btn_cancel",function() {
        $("ul.ul_product_list_right").html("");
        reset_total();
    });





    $(document).on("click","button.btn_save_client_order,button.btn_save_out_complete",function() {

        var consulting_idx = $("#order_client").val();
        if(consulting_idx > 0){
            //
        }else{
 
             showToast('주문고객사를 선택해주세요');
             return;
        }
        var outpart = "client_order";
        if($(this).hasClass("btn_save_out_complete")){
            outpart = "out_complete";
        }

        var out_storage = checkNull($("#out_storage option:selected").text());
        var storage_idx = $("#out_storage").val();

        var to_place_name = checkNull($("#to_place_name option:selected").text());
        var address = checkNull($("#address").val());
        var to_name = checkNull($("#to_name").val());
        var hp = checkNull($("#hp").val());
        var memo = checkNull($("#memo").val());
        var order_date = checkNull($("#order_date").val());

        var total_client_price      = $(".total_client_price").attr("total_client_price");
        var total_client_price_tax  = $(".total_client_price_tax").attr("total_client_price_tax");
        var total_client_price_sum  = $(".total_client_price_sum").attr("total_client_price_sum");



        if(order_date == ""){

            window.alert('발주일은 필수입니다');

            return;
        }



/*
        if(address == "" || to_name == "" || hp == ""){
            showToast("필수항목이 누락되었습니다.");
            return;
        }
*/


        var client_product_list = Array();

        $("ul.ul_product_list_right").find("li").each(function(index) {
            var client_product_idx = $(this).attr("client_product_idx");
            var cnt = parseInt($(this).find("input[name='cnt']").val());
            var product_name = $(this).find(".li_product_name").text();


            var client_price = $(this).attr("client_price");
            var client_price_tax = $(this).attr("client_price_tax");
            var price_calcu = $(this).find(".client_price_calculation").attr("client_price_calculation");
            var pcnt = {client_product_idx,cnt,product_name,client_price,client_price_tax,price_calcu};
            if(cnt > 0){
                client_product_list.push(pcnt);

            }

        });

        var data = {
            mode: outpart,
            consulting_idx: consulting_idx,
            client_product_list: client_product_list,
            to_place_name:to_place_name,
            address:address,
            to_name:to_name,
            hp:hp,
            memo:memo,
            order_date:order_date,
            storage_idx:storage_idx,
            out_storage:out_storage,

            total_client_price:total_client_price,
            total_client_price_tax:total_client_price_tax,
            total_client_price_sum:total_client_price_sum

        };

        console.log(data);


                

        if(client_product_list.length > 0){
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
        }else if(this_id == "to_place_name" && data.id >= 0){
            setClientPlace(data.id);

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


    var url = "./contents/sangjo/client_input/api/save_client_order_upload.php";
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

    console.log(formData);



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

                

                var attachment = result.data;
                console.log(attachment);

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

    var url = "./contents/sangjo/client_input/api/get_client_product_info.php";
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

                    reset_product_list(obj); // /common/js/product_list_order_html.js 에 정의




                    $("#memo").val("");

                    if(obj.place_list.length > 0){
                        $("#to_place_name").html("");
                        $("#address").val(obj.place_list[0].addr);
                        $("#to_name").val(obj.place_list[0].receiver_name);
                        $("#hp").val(obj.place_list[0].receiver_hp);
                        $("#memo").val(obj.place_list[0].memo);
    
                        for(var i=0;i<obj.place_list.length;i++){
                            var pl = obj.place_list[i];
    
                            if(pl.last_place == "1"){
                                $("#to_place_name").append("<option value='"+pl.client_place_idx+"' selected  address='"+pl.addr+"' receiver_name='"+pl.receiver_name+"' receiver_hp='"+pl.receiver_hp+"' memo='"+pl.memo+"'>"+pl.place_name+"</option>");
                                $("#address").val(pl.addr);
                                $("#to_name").val(pl.receiver_name);
                                $("#hp").val(pl.receiver_hp);
                                $("#memo").val(pl.memo);
    
                            }else{
                                $("#to_place_name").append("<option value='"+pl.client_place_idx+"' address='"+pl.addr+"' receiver_name='"+pl.receiver_name+"' receiver_hp='"+pl.receiver_hp+"' memo='"+pl.memo+"'>"+pl.place_name+"</option>");
    
                            }
    
    
                        }
                    }else{
                        $("#to_place_name").html("");
                        $("#address").val("");
                        $("#to_name").val("");
                        $("#hp").val("");
                        $("#memo").val("");
        
                    
                    }
                    





                    $("#to_place_name.select2_single").select2({
                        placeholder: "배송지 선택",
                        allowClear: true
                    });


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






function setClientPlace(id){
   var option = $("#to_place_name").find("option[value='"+id+"']");
   var address = option.attr("address");
   var receiver_name = option.attr("receiver_name");
   var receiver_hp = option.attr("receiver_hp");
   var memo = option.attr("memo");

   $("#address").val(address);
   $("#to_name").val(receiver_name);
   $("#hp").val(receiver_hp);
   $("#memo").val(memo);
}

