
//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){


    $(document).on("click",".btn_add_client_order",function(){
       window.location.href='dashboard_sffm.php?page=sfullfillment/client_input/order';
    });


    $(document).on("click","#datatable-main tr td",function(){


        var url = "./contents/sfullfillment/out_order/api/getOrderInfo.php";
        var oocp_idx = $(this).closest("tr").attr("oocp_idx");


        var str = "oocp_idx="+oocp_idx; //



        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){

                    console.log(result);
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {

                        //toast(result.msg);
                        var order_info = result.data.order_info;
                        $("#detail_section").attr("oocp_idx",oocp_idx);
                        $("#detail_section").find(".company_name").html(order_info.t_company_name);
                        $("#detail_section").find(".company_name").attr("consulting_idx",order_info.consulting_idx);
                        $("#detail_section").find(".product_name").html(order_info.product_name);
                        $("#detail_section").find(".order_count").html(order_info.order_count);
                        $("#detail_section").find(".client_price").html(number_format(order_info.client_price)+" 원");
                        $("#detail_section").find(".price_calcu").html(number_format(order_info.price_calcu)+" 원");

                        $("#detail_section").find(".to_place_name").html(order_info.to_place_name);
                        $("#detail_section").find(".to_address").html(order_info.to_address);
                        $("#detail_section").find(".to_name").html(order_info.to_name);
                        $("#detail_section").find(".to_hp").html(order_info.to_hp);
                        $("#detail_section").find(".memo").html(order_info.delivery_memo);
                        $("#detail_section").find(".attachment").html("");




                        if(order_info.manager_name != undefined && order_info.manager_name != ""){
                            $("#detail_section").find(".manager").html(" ( 주문담당자: "+order_info.manager_name+" )");

                        }else if(order_info.admin_name != undefined && order_info.admin_name != ""){
                            $("#detail_section").find(".manager").html(" ( 주문관리자: "+order_info.admin_name+" )");

                        }else{
                            $("#detail_section").find(".manager").html("");

                        }




                        var attachment_list = result.data.attachment_list;
                        for(var i = 0; i < attachment_list.length; i++){

                            var filename_ex = attachment_list[i].filename.split("/");
                            var this_lenth = filename_ex.length -1;
                            var only_filename = filename_ex[this_lenth];


                            var ext = getExtensionOfFilename(only_filename);
                            //console.log(ext);



                            if(ext == ".png" || ext == ".jpg" || ext == ".jpeg" || ext == ".gif"){

                                $("#detail_section").find(".attachment").append("<a href='"+ attachment_list[i].filename+"' target='_blank' ><img src='"+attachment_list[i].filename+"' class='attatch_img'></a>");
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

                        $("#detail_section").removeClass("hide").show();

                        // $("#detail_section #out_storage option[value='"+order_info.base_storage_idx+"']").attr("selected","selected");

                        $("#detail_section #out_storage").val(order_info.base_storage_idx);
                        $("#detail_section #out_storage").trigger("change");

                        
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
        var oocp_idx = $("#detail_section").attr("oocp_idx");
        var storage_idx = $("#out_storage option:selected").val();
        var admin_memo = $("#admin_memo").val();
        saveOocpStatus("출고지시",oocp_idx,storage_idx,admin_memo);
        
    });

    $(document).on("click", ".btn_cancel_out_order",function() {
        var oocp_idx = $("#detail_section").attr("oocp_idx");
        saveOocpStatus("주문취소",oocp_idx,"","");
    });

    
    $(document).on("click", ".btn_save_out_complete",function() {
        var io_idx = $("#detail_section").attr("io_idx");
        saveOocpStatus("출고완료",io_idx,"","");

    });










    $(document).on("click", ".btn_io_status",function() {
        

        var old_io_status = $(this).text();
        if(old_io_status == "출고완료"){
            return;
        }

        var io_status = $(this).attr("next_io_status");
        var old_val = $(this).closest("td").attr("io_status");
        
        var url = "./contents/sfullfillment/storage_out/api/updateCell.php";
        var io_idx = $(this).closest("tr").attr("io_idx");

        var str = "mode=io_status&io_status="+io_status+"&io_idx="+io_idx; //
        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        toast(result.msg);

                        if(io_status == "출고완료"){
                            $(this).removeClass("btn-danger").addClass("btn-success").text("출고완료");
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




});




function saveOocpStatus(mode,oocp_idx,storage_idx,admin_memo){

        
    var url = "./contents/sfullfillment/out_order/api/save_product_output.php";

    var str = "mode="+mode+"&oocp_idx="+oocp_idx+"&storage_idx="+storage_idx+"&admin_memo="+admin_memo; //
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);

                    $("#detail_section").hide();
                    $("#datatable-main").find("tr[oocp_idx='"+oocp_idx+"']").next().find("td.product_name").trigger("click");
                    $("#datatable-main").find("tr[oocp_idx='"+oocp_idx+"']").remove();

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
