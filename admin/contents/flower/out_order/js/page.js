//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){



    $(document).on("click",".out_storage_name",function(e){
        if($(".storage_info").is(":visible")){
            $(".storage_info").hide();

        }else{
            $(".storage_info").removeClass("hide").show();

        }
    });



    $(document).on("click","div.btn-group[col_name='out_order_status'] ul.dropdown-menu li a",function(e){

        var out_order_status = $(this).text();
        var out_order_idx = $(this).closest("tr").attr("out_order_idx");



        if(out_order_status == '주문취소' || out_order_status == "주문접수반려"){
            
            $.confirm({
                title: '', //타이틀
                content: out_order_status+' 하시겠습니까?',  //메세지
                closeIcon: true, //우측상단 닫기버튼 보일지 여부
                closeIconClass: 'fa fa-close',  //닫기 버튼 아이콘
                type: 'red', //확인창 색상(옵션)
                boxWidth: '300px', //확인창 width
                useBootstrap: false,  //width 적용할때 false 로 해줘야 적용됨.
                        
                
                buttons: {
                    "확인": function () {
                        goNextOutOrderStatus(out_order_idx,out_order_status);
                    },
                    "취소": function () {
                        //$.alert('Canceled!');
                    },

                }
            });
        }else{
            goNextOutOrderStatus(out_order_idx,out_order_status);
        }



    });








    $("#datatable-main tbody tr").eq(0).find("td.company_name").trigger("click");


    $(document).on("click",".order_edit",function(){
        var out_order_idx = $("#detail_section").attr("out_order_idx");
        var popup = window.open('?page=flower/client_input/order&out_order_idx='+out_order_idx,'주문내역 수정');
    });




    $(document).on("click",".order_product_del",function(){


        var product_cnt = $(".table_order_info").find("tbody tr:not(.sample):not(.origin)").length;
        if(product_cnt == 1){
            
            $.alert('단건 상품인경우 삭제할수 없습니다.<br>주문취소 처리만 가능합니다.');
            return;
        }

        var out_order_part = $("#detail_section").attr("out_order_part");
        var oocp_idx = $(this).closest("tr").attr("oocp_idx");



        $.confirm({
            title: '', //타이틀
            content: '삭제할까요?',  //메세지
            closeIcon: true, //우측상단 닫기버튼 보일지 여부
            closeIconClass: 'fa fa-close',  //닫기 버튼 아이콘
            type: 'red', //확인창 색상(옵션)
            boxWidth: '300px', //확인창 width
            useBootstrap: false,  //width 적용할때 false 로 해줘야 적용됨.

            
            buttons: {
                "확인": function () {
                    modifyOrderProduct(out_order_part,oocp_idx,"del");
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });





    });

    $(document).on("click",".order_product_edit",function(){


        var $tr = $(this).closest("tr");
        var old_client_price = $tr.find("td.client_price").attr("old_value");
        var old_client_price_tax = $tr.find("td.client_price_tax").attr("old_value");
        var old_order_count = $tr.find("td.order_count").attr("old_value");

        $tr.find("td.client_price").html("<input class='form-data' name='client_price'  value='"+old_client_price+"'>");
        $tr.find("td.client_price_tax").html("<input class='form-data' name='client_price_tax'  value='"+old_client_price_tax+"'>");
        $tr.find("td.order_count").html("<input class='form-data' name='order_count'  value='"+old_order_count+"'>");

        $tr.find(".setting_tool").hide();
        $tr.find("button").removeClass("hide").show();

        
    });


    $(document).on("keyup",".table_order_info input",function(){
        var xx = parseInt($(this).val());
        if(isNaN(xx)){
            xx = 0;
        }

        $(this).val(xx);

        var $tr = $(this).closest("tr");

        var client_price = parseInt($tr.find("input[name='client_price']").val())

        var client_price_tax = parseInt($tr.find("input[name='client_price_tax']").val());
        var order_count = parseInt($tr.find("input[name='order_count']").val());
        // console.log("sum:"+client_price);
        // console.log("sum:"+client_price_tax);
        // console.log("sum:"+order_count);

        var sum = (client_price + client_price_tax) * order_count;

        //console.log("sum:"+sum);
        sum = sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $tr.find("td.total_client_price_sum").text(sum+" 원");


    });






    $(document).on("click",".btn_save_out_order_complete",function(){
        var receiver_name = checkNull($("#receiver_name").val());
        var received_time = checkNull($("#received_time").val());

        var out_order_idx = checkNull($("#detail_section").attr("out_order_idx"));

        if(out_order_idx == ""){
            toast('주문정보 고유번호가 없습니다.');
            return;
        }

        var onlyPhotoOption = 0;
        if($(this).hasClass("onlyPhotos")){
            onlyPhotoOption = 1;
        }

        var dataJson = {
            receiver_name: receiver_name,
            received_time: received_time,
            out_order_idx : out_order_idx,
            onlyPhotoOption: onlyPhotoOption
        };

        var data = JSON.stringify(dataJson);


        var formData = new FormData();
        formData.append("data", data);


        // Read selected files
        var totalfiles = document.getElementById('files').files.length;
        for (var index = 0; index < totalfiles; index++) {
            formData.append("files[]", document.getElementById('files').files[index]);
        }


        if($(this).hasClass("onlyPhotos")){
           if(totalfiles == 0){
            toast('선택된 사진이 없습니다.');
            return;
           }
        }

        
        // ajax 처리 부분 * 
        var url = "./contents/flower/out_order/api/completeOutOrderStatus.php";

        $.ajax({
            url : url
            , type : "POST"
            , processData : false
            , contentType : false
            , data : formData
            ,dataType: "json"
            ,  success: function(result){

                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);


                    if(onlyPhotoOption == 0){
                        $("td.receiver_name").html(result.data.receiver_name);
                        $("td.received_time").html(result.data.received_time);

                        $(".div_button").hide();
                    }

                    //업로드한 사진 바로 보여주기
                    $("tr.tr_photos").show();
                    if (result.attachment && result.attachment.length > 0) {
                        for(var i=0;i<result.attachment.length;i++){
                            $("td.photos").append("<a href='"+result.attachment[i]+"' target='_blank'><img src='"+result.attachment[i]+"' class='img_photo'></a>");
                        }
                    }
                        
                    //첨부파일 박스 초기화. 배송완료 버튼 누르면 다시 첨부되는것 방지.
                    $("#multi_input_div").html('<input type="file" class="files" id="files" name="files[]" multiple>'); //첨부파일 박스 초기화가  $("#files").val("");로 안되서 사용.
                    $("input.files").filestyle({
                        iconName : 'glyphicon glyphicon-file',
                        buttonText : 'Select File',
                        buttonName : 'btn-warning'
                    });    

                    //업로드한 사진영역으로 스크롤
                    document.getElementById('table_delivery_info').scrollIntoView();

                    console.log(result);
    
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


    });


    

    $(document).on("click",".btn_order_product_edit_cancel",function(){

        
        var $tr = $(this).closest("tr");
        var old_client_price = $tr.find("td.client_price").attr("old_value");
        var old_client_price_tax = $tr.find("td.client_price_tax").attr("old_value");
        var old_order_count = $tr.find("td.order_count").attr("old_value");
        var old_total_client_price_sum = $tr.find("td.total_client_price_sum").attr("old_value");



        $tr.find("td.client_price").html(old_client_price.replace(/\B(?=(\d{3})+(?!\d))/g, ",")+" 원");
        $tr.find("td.client_price_tax").html(old_client_price_tax.replace(/\B(?=(\d{3})+(?!\d))/g, ",")+" 원");
        $tr.find("td.order_count").html(old_order_count.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $tr.find("td.total_client_price_sum").html(old_total_client_price_sum.replace(/\B(?=(\d{3})+(?!\d))/g, ",")+" 원");




        $tr.find(".setting_tool").show();
        $tr.find("button").hide();

        
    });


    $(document).on("click",".btn_order_product_edit_save",function(){
        var out_order_part = $("#detail_section").attr("out_order_part");
        var oocp_idx = $(this).closest("tr").attr("oocp_idx");
        //var cnt = $(this).closest("tr").find("input[name='cnt']").val();

        modifyOrderProduct(out_order_part,oocp_idx,"edit");

    });


    $(document).on("click",".btn_add_client_order",function(){
       window.location.href='dashboard_flower.php?page=flower/client_input/order';
    });


    $(document).on("click","#datatable-main tr td:not(.out_order_status)",function(){


        var url = "./contents/flower/out_order/api/getOrderInfo.php";
        var out_order_idx = $(this).closest("tr").attr("out_order_idx");
        var out_order_part = $(this).closest("tr").attr("out_order_part");


        var str = "out_order_idx="+out_order_idx+"&out_order_part="+out_order_part; //



        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){

                    console.log(result);
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        var order_info = result.data.order_info;
                        var order_product_list = result.data.order_product_list;

                        $("#detail_section").attr("out_order_idx",out_order_idx);
                        $("#detail_section").attr("out_order_part",order_info.out_order_part);


                        $("#detail_section").find(".order_product_list .total_client_price ii").text(number_format(order_info.total_client_price)+" 원");
                        $("#detail_section").find(".order_product_list .total_client_price_tax ii").text(number_format(order_info.total_client_price_tax)+" 원");
                        //$("#detail_section").find(".order_product_list .order_total_price ii").text(number_format(order_info.total_client_price_sum)+" 원");


                        $("#detail_section").find(".company_name").html(order_info.t_company_name);
                        $("#detail_section").find(".company_name").attr("consulting_idx",order_info.consulting_idx);

                        $("#detail_section").find(".order_name").html(order_info.order_name+" / " + order_info.order_tel).attr("order_name",order_info.order_name).attr("order_tel",order_info.order_tel);
                        $("#detail_section").find(".order_company_tel").html(order_info.order_company_tel).attr("order_company_tel",order_info.order_company_tel);
                        $("#detail_section").find(".r_name").html(order_info.r_name+" / " + order_info.r_tel).attr("r_tel",order_info.r_tel);
                        $("#detail_section").find(".r_company_tel").html(order_info.r_company_tel).attr("r_company_tel",order_info.r_company_tel);
                        $("#detail_section").find(".r_time").html(order_info.r_date_weekday+" " + order_info.r_hour).attr("r_date_weekday",order_info.r_date_weekday).attr("r_hour",order_info.r_hour);
                        $("#detail_section").find(".address").html(order_info.address1 + " " + order_info.address2).attr("address1",order_info.address1).attr("address2",order_info.address2);






                        //console.log(order_info.out_order_status);
                        if(order_info.out_order_status == "접수대기"){
                            $("#admin_notice").val("");
                            $("#head_officer").val("");
                            $("#admin_memo").val("");
                            $("#agency_order_price").val("");
                            $("#agency_order_price_sangjo").val("");
                            $(".tr_order").show();
                            $(".tr_req").hide();
                            $(".tr_branch").hide();
                            $(".tr_storage").hide();
                        }else{
                            $("#admin_notice").closest("td").text(order_info.admin_notice || '');
                            $("#head_officer").closest("td").text(order_info.head_officer || '');
                            $("#admin_memo").closest("td").text(order_info.admin_memo || '');

                            //출고점 정보 넣기
                            $("#detail_section").find(".tr_storage").removeClass("hide").find(".out_storage span.out_storage_name").text(order_info.t_storage_name || '');
                            $("#detail_section").find(".storage_info_detail").text(
                                ((order_info.manager || '') + " / " + (order_info.hp || '') + " / " + (order_info.address || '')).replace(/^\s*\/\s*\/\s*$/, '')
                            );

                            $("#detail_section").find(".out_order_status").text(order_info.out_order_status || '');




                            if(order_info.t_branch_name != undefined){
                                $("#detail_section").find("td.out_branch").text(order_info.t_branch_name || '');
                            }else{
                                $("#detail_section").find("td.out_branch").text('');
                            }

                            if(order_info.branch_price != "0" && order_info.branch_price){
                                $("#detail_section").find("td.branch_price").text(number_format(order_info.branch_price)+" 원");
                            }else{
                                $("#detail_section").find("td.branch_price").text('');
                            }
                            

                            $(".tr_order").hide();

                            if(order_info.out_order_staus == "배송요청"){
                                $(".tr_req").show();
                                $(".tr_branch").show();

                            }

                            // if(order_info.out_order_status == "배송완료"){
                            //     $("#detail_section").find(".receiver_name").html(order_info.receiver_name).attr("receiver_name",order_info.receiver_name);
                            //     $("#detail_section").find(".received_time").html(order_info.received_time).attr("received_time",order_info.received_time);

                            //     $(".tr_branch").show();



                            // }else if(order_info.out_order_status == "본부접수" ||order_info.out_order_status == "주문접수" || order_info.out_order_status == "배송중"){
                            //     $("#detail_section").find(".receiver_name").html('<input type="text" id="receiver_name" class="form-control col-md-5 col-xs-12" name="receiver_name" >');
                            //     $("#detail_section").find(".received_time").html('<input type="text" id="received_time" class="form-control col-md-5 col-xs-12" name="received_time" >');

                            // }

                            $("#detail_section").find(".receiver_name").html('<input type="text" id="receiver_name" class="form-control col-md-5 col-xs-12" name="receiver_name" value="'+(order_info.receiver_name || '')+'" >');
                            $("#detail_section").find(".received_time").html('<input type="text" id="received_time" class="form-control col-md-5 col-xs-12" name="received_time" value="'+(order_info.received_time || '')+'" >');
                            $("#detail_section").find(".agency_order_price").html('<input type="text" id="agency_order_price" class="form-control col-md-5 col-xs-12" name="received_time" value="'+(order_info.agency_order_price || '')+'" >');


    
                        }






                        

                        

                        if(out_order_part == "화훼"){
                            //toast(result.msg);

                            $("#detail_section").find(".mth_s").addClass("mth").removeClass("mth_s")
                            $("#detail_section").find(".bigo").removeClass("blue_color");
                            $("#detail_section").find(".tr_flower").removeClass("hide");
                            $("#detail_section").find(".tr_sangjo_move").addClass("hide");

                            $("#out_storage_wrapper").removeClass("hide").show();
                            $("#out_storage_sangjo_wrapper").hide();


                            $("#detail_section").find(".messageType").attr("messageType",order_info.messageType);
                            if(order_info.messageType == "messageCard"){
                                $("#detail_section").find(".messageType").html("카드만");

                            }else if(order_info.messageType == "messageRibbon"){
                                $("#detail_section").find(".messageType").html("리본만");

                            }


                            $("#detail_section").find(".eType").html(order_info.eType).attr("eType",order_info.eType);

                            var allMsgTitle = "";
                            if(order_info.msgTitle != undefined && order_info.msgTitle != ""){

                                allMsgTitle += order_info.msgTitle; 
                            }

                            if(order_info.msgTitle2 != undefined && order_info.msgTitle2 != ""){
                                allMsgTitle += "/"+order_info.msgTitle2;
                            }        
                            if(order_info.msgTitle3 != undefined && order_info.msgTitle3 != ""){
                                allMsgTitle += "/"+order_info.msgTitle3;
                            }

                            $("#detail_section").find(".msgTitle").html(allMsgTitle).attr("msgTitle",order_info.msgTitle).attr("msgTitle2",order_info.msgTitle2).attr("msgTitle3",order_info.msgTitle3);
                            $("#detail_section").find(".sender_name").html(order_info.sender_name);



                        }else if(out_order_part == "상조"){
                            //toast(result.msg);

                            $("#detail_section").find(".mth").addClass("mth_s").removeClass("mth")
                            $("#detail_section").find(".bigo").addClass("blue_color");

                            $("#detail_section").find(".tr_flower").addClass("hide");
                            $("#detail_section").find(".tr_sangjo_move").removeClass("hide");

                            $("#out_storage_wrapper").hide();
                            $("#out_storage_sangjo_wrapper").removeClass("hide").show();


                            if(order_info.messageType == "messageCard"){
                                $("#detail_section").find(".messageType").html("카드만");

                            }else if(order_info.messageType == "messageRibbon"){
                                $("#detail_section").find(".messageType").html("리본만");

                            }
                            $("#detail_section").find(".eType").html(order_info.eType);

                            var allMsgTitle = "";
                            if(order_info.msgTitle != undefined && order_info.msgTitle != ""){
                            allMsgTitle += order_info.msgTitle; 
                            }

                            if(order_info.msgTitle2 != undefined && order_info.msgTitle2 != ""){
                                allMsgTitle += "/"+order_info.msgTitle2;
                            }        
                            if(order_info.msgTitle3 != undefined && order_info.msgTitle3 != ""){
                                allMsgTitle += "/"+order_info.msgTitle3;
                            }

                            $("#detail_section").find(".msgTitle").html(allMsgTitle);
                            $("#detail_section").find(".sender_name").html(order_info.sender_name);




                        }


                        $(".table_order_info tbody tr:not(.origin)").remove();

                        for(var i=0;i<order_product_list.length;i++){
                            var $tr_sample = $(".table_order_info tbody tr.sample").clone(true);
                            $tr_sample.find("td").closest("tr").attr("oocp_idx",order_product_list[i].oocp_idx);

                            $tr_sample.find(".product_number").html(i+1);
                            $tr_sample.find(".bigo").html(out_order_part);
                            var pr = order_product_list[i].product_name;
                            if(order_product_list[i].option_name != undefined && order_product_list[i].option_name != "" && order_product_list[i].option_name != null){
                                pr = pr + "("+order_product_list[i].option_name+")";
                            }

                            $tr_sample.find(".product_name").html(pr);
                            $tr_sample.find(".order_count").attr("old_value",order_product_list[i].order_count).html(order_product_list[i].order_count);

                            $tr_sample.find(".client_price").attr("old_value",order_product_list[i].client_price).html(number_format(order_product_list[i].client_price)+" 원");
                            $tr_sample.find(".client_price_tax").attr("old_value",order_product_list[i].client_price_tax).html(number_format(order_product_list[i].client_price_tax)+" 원");
                            $tr_sample.find(".total_client_price_sum").attr("old_value",order_product_list[i].total_client_price_sum).html(number_format(order_product_list[i].total_client_price_sum)+" 원");

                            $tr_sample.removeClass("sample").removeClass("hide").removeClass("origin");

                            $(".table_order_info tbody").append($tr_sample);
                            
                        }
                        


                        $("#detail_section").find(".attachment").html("");

                        var attachment_list = result.data.attachment_list;
                        for(var i=0;i <attachment_list.length; i++){
                            var filename_ex = attachment_list[i].filename.split("/");
                            var this_lenth = filename_ex.length -1;
                            var only_filename = filename_ex[this_lenth];
                            //console.log(only_filename);

                            var ext = getExtensionOfFilename(only_filename);
                            //console.log(ext);
                            if(ext == ".png" || ext == ".jpg" || ext == ".jpeg" || ext == ".gif"){

                                $("#detail_section").find(".attachment").append("<a href='"+ attachment_list[i].filename+"' target='_blank' class='img_colorbox' ><img src='"+attachment_list[i].filename+"' class='attatch_img'></a>");

                            }else{

                                if(i != 0){
                                    $("#detail_section").find(".attachment").append("<br>");
                                }

                                if(ext == ".pdf"){
                                    $("#detail_section").find(".attachment").append("<a href='"+ attachment_list[i].filename+"' target='_blank' >"+only_filename+"</a>");

                                }else{
                                    $("#detail_section").find(".attachment").append("<a href='"+ attachment_list[i].filename+"' download >"+only_filename+"</a>");

                                }


                            }

                        }



                        $("#detail_section").find(".photos").html("");
                        var photo_list = result.data.photo_list;
                        if(photo_list.length == 0){
                            $("tr.tr_photo_upload").show();
                            $("tr.tr_photos").hide();

                        }else{
                            $("tr.tr_photos").show();
                           for(var i=0;i <photo_list.length; i++){
                                var filename_ex = photo_list[i].filename.split("/");
                                var this_lenth = filename_ex.length -1;
                                var only_filename = filename_ex[this_lenth];
                                //console.log(only_filename);

                                var ext = getExtensionOfFilename(only_filename);
                                //console.log(ext);
                                if(ext == ".png" || ext == ".jpg" || ext == ".jpeg" || ext == ".gif"){

                                    $("#detail_section").find(".photos").append("<a href='"+ photo_list[i].filename+"' target='_blank' class='img_colorbox' data-lightbox='photos"+out_order_idx+"'><img src='"+photo_list[i].filename+"' class='img_photo'></a>");
                                }else{

                                    //$("#detail_section").find(".attachment").append("<a href='"+ photo_list[i].filename+"' target='_blank' >"+only_filename+"</a>");
                                }

                            }


                        }





                        if(order_info.paymentType == "paymentBill"){
                            $("#detail_section").find(".paymentType").html("월말결제(계산서)");

                        }else if(order_info.paymentType == "paymentCard"){
                            $("#detail_section").find(".paymentType").html("카드결제");

                        }



                        $("#detail_section").find(".delivery_memo").html(order_info.delivery_memo);


                        $("#detail_section").removeClass("hide").show();

                        // $("#detail_section #out_storage option[value='"+order_info.base_storage_idx+"']").attr("selected","selected");

                        $("#detail_section #out_storage").val(order_info.base_storage_idx);
                        $("#detail_section #out_storage").trigger("change");

                        $("#detail_section #out_storage_sangjo").val(order_info.base_storage_idx);
                        $("#detail_section #out_storage_sangjo").trigger("change");

                        if(order_info.out_order_status != "접수대기"){
                            $("tr.status_order").hide();
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

    });




    $(document).on("click", ".btn_save_out_order",function() {
        var out_order_idx = $("#detail_section").attr("out_order_idx");

        saveOocpStatus("배송요청",out_order_idx);
        
    });

    $(document).on("click", ".btn_cancel_out_order",function() {
        var out_order_idx = $("#detail_section").attr("out_order_idx");




        $.confirm({
            title: '', //타이틀
            content: '주문취소 하시겠습니까??',  //메세지
            closeIcon: true, //우측상단 닫기버튼 보일지 여부
            closeIconClass: 'fa fa-close',  //닫기 버튼 아이콘
            type: 'red', //확인창 색상(옵션)
            boxWidth: '300px', //확인창 width
            useBootstrap: false,  //width 적용할때 false 로 해줘야 적용됨.

            
            buttons: {
                "확인": function () {
                    saveOocpStatus("주문취소",out_order_idx);
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });


    });

    
    $(document).on("click", ".btn_save_out_complete",function() {
        var io_idx = $("#detail_section").attr("io_idx");
        saveOocpStatus("배송완료",io_idx);

    });

    
    $(document).on("click", ".btn_save_out_order_branch",function() {
        var out_order_idx = $("#detail_section").attr("out_order_idx");
        saveOocpStatus("발주정보저장",out_order_idx);

    });

    $(document).on("click",".btn_update_out_order",function(){
        var receiver_name = checkNull($("#receiver_name").val());
        var received_time = checkNull($("#received_time").val());
        var agency_order_price = checkNull($("#agency_order_price").val());
        var out_order_idx = checkNull($("#detail_section").attr("out_order_idx"));

        if(out_order_idx == ""){
            toast('주문정보 고유번호가 없습니다.');
            return;
        }

        var dataJson = {
            receiver_name: receiver_name,
            received_time: received_time,
            agency_order_price: agency_order_price,
            out_order_idx : out_order_idx,
            mode: 'update_info'
        };

        var data = JSON.stringify(dataJson);
        var formData = new FormData();
        formData.append("data", data);

        // ajax 처리 부분
        var url = "./contents/flower/out_order/api/completeOutOrderStatus.php";

        $.ajax({
            url : url
            , type : "POST"
            , processData : false
            , contentType : false
            , data : formData
            , dataType: "json"
            , success: function(result){
                if(result.status == 1) {
                    toast(result.msg);
                    // 화면 업데이트
                    $("#detail_section").find(".receiver_name").html(receiver_name);
                    $("#detail_section").find(".received_time").html(received_time);
                    $("#detail_section").find(".agency_order_price").html(number_format(agency_order_price)+" 원");
                } else {
                    toast(result.msg);
                }
            }
            , error : function(jqXHR, textStatus, errorThrown) {
                alert("오류가 발생했습니다: " + textStatus);
            }
        });
    });

});








function saveOocpStatus(mode,out_order_idx){
    var out_order_part = $("#detail_section").attr("out_order_part");
    var consulting_idx = $("#detail_section .company_name").attr("consulting_idx");



    if(mode == "배송요청"){


        var admin_notice = checkNull($("#admin_notice").val());
        var head_officer = checkNull($("#head_officer").val());
        var admin_memo   = checkNull($("#admin_memo").val());

    
        if(out_order_part == "화훼"){

            
            if($("#out_storage option:selected").val() === undefined){
                toast("출고지점을 선택해주세요");
                return;
            }

            var storage_idx = $("#out_storage option:selected").val();

            var branch_storage_idx = checkNull($("#out_branch option:selected").val());
            var branch_price = checkNull($("#branch_price").val());
    


        }else if(out_order_part == "상조"){

                
            if($("#out_storage_sangjo option:selected").val() === undefined){
                toast("출고지점(협력사)를 선택해주세요");
                return;
            }

            var storage_idx = $("#out_storage_sangjo option:selected").val();
        }

        var agency_order_price = checkNull($("#agency_order_price").val());





        if($("#move_check").parent().hasClass("checked")){
            var move_check = 1;
        }else{
            var move_check = 0;

        }


        var str = "mode="+mode+"&consulting_idx="+consulting_idx+"&out_order_idx="+out_order_idx+"&storage_idx="+storage_idx+"&branch_storage_idx="+branch_storage_idx+"&branch_price="+branch_price; //
        str += "&admin_notice="+admin_notice+"&head_officer="+head_officer+"&admin_memo="+admin_memo+"&agency_order_price="+agency_order_price+"&out_order_part="+out_order_part+"&move_check="+move_check;

    }else if(mode == "발주정보저장"){

        var branch_storage_idx = checkNull($("#out_branch option:selected").val());
        var branch_price = checkNull($("#branch_price").val());


        var str = "mode="+mode+"&out_order_idx="+out_order_idx+"&branch_storage_idx="+branch_storage_idx+"&branch_price="+branch_price; //
   
        console.log(str);
   
    }else{

        var str = "mode="+mode+"&out_order_idx="+out_order_idx+"&out_order_part="+out_order_part; //

    }

        
    var url = "./contents/flower/out_order/api/save_product_output.php";

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);

                    if(mode == "발주정보저장"){


   
                    }else{

                        $("#detail_section").hide();
                        $("#datatable-main").find("tr[out_order_idx='"+out_order_idx+"']").next().find("td.product_name").trigger("click");
                        $("#datatable-main").find("tr[out_order_idx='"+out_order_idx+"']").remove();
    
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



/**
 * 파일명에서 확장자명 추출
 * @param filename   파일명
 * @returns _fileExt 확장자명
 */
function getExtensionOfFilename(filename) {
 
    var _fileLen = filename.length;
 
    /** 
     * lastIndexOf('.') 
     * 뒤에서부터 '.'의 위치를 찾기위한 함수
     * 검색 문자의 위치를 반환한다.
     * 파일 이름에 '.'이 포함되는 경우가 있기 때문에 lastIndexOf() 사용
     */
    var _lastDot = filename.lastIndexOf('.');
 
    // 확장자 명만 추출한 후 소문자로 변경
    var _fileExt = filename.substring(_lastDot, _fileLen).toLowerCase();
 
    return _fileExt;
}



function modifyOrderProduct(out_order_part,oocp_idx,mode){




    var out_order_idx = $("#detail_section").attr("out_order_idx");
    if(mode == "del"){

        var str = "mode="+mode+"&oocp_idx="+oocp_idx+"&out_order_idx="+out_order_idx+"&out_order_part="+out_order_part; //


    }else if(mode == "edit"){

        var $tr = $("table.table_order_info").find("tr[oocp_idx='"+oocp_idx+"']");


        var client_price = $tr.find("input[name='client_price']").val();
        var client_price_tax = $tr.find("input[name='client_price_tax']").val();
        var order_count = $tr.find("input[name='order_count']").val();


        var str = "mode="+mode+"&oocp_idx="+oocp_idx+"&client_price="+client_price+"&client_price_tax="+client_price_tax+"&order_count="+order_count+"&out_order_part="+out_order_part; //

    }

        
    var url = "./contents/flower/out_order/api/modifyOrderProduct.php";

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);

                    var $tr = $("#detail_section table.table_order_info").find("tr[oocp_idx='"+oocp_idx+"']");

                    if(mode == "del"){
                        $tr.remove();
                        $("th.order_product_list span.order_total_price ii").text(number_format(result.data.total_client_price_sum)+" 원");

                    }else if(mode == "edit"){
                        $("th.order_product_list span.total_client_price ii").text(number_format(result.data.total_client_price)+" 원");

                        $("th.order_product_list span.total_client_price_tax ii").text(number_format(result.data.total_client_price_tax)+" 원");

                        var out_order_idx = $("#detail_section").attr("out_order_idx");
                        $("#datatable-main").find("tr[out_order_idx='"+out_order_idx+"'] td.total_client_price_sum").text(number_format(result.data.total_client_price_sum));



                        $tr.find("td.order_count").text(cnt);
                        var client_price =  parseInt($tr.find("td.client_price").attr("old_value"));
                        var client_price_tax =  parseInt($tr.find("td.client_price_tax").attr("old_value"));
                        var client_price_sum = client_price + client_price_tax;

                        $tr.find("td.total_client_price_sum").text(number_format(cnt*client_price_sum)+" 원");
                        $tr.find("button").hide();
                        $tr.find(".setting_tool").show();
                    }

                    $("#datatable-main").find("tr[out_order_idx='"+result.data.out_order_idx+"']").find("td.order_product_title span").text(result.data.order_product_title);

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


function goNextOutOrderStatus(out_order_idx,out_order_status){
    var url = "./contents/flower/out_order/api/updateCell.php";
    var str = "mode=out_order_status&out_order_status="+out_order_status+"&out_order_idx="+out_order_idx;
    
    $.ajax({
        type: "POST",
        url: url,
        data: str,
        // ...
    });
}

