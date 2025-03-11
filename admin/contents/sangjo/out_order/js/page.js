//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){


    $(document).on("click",".btn_add_client_order",function(){
       window.location.href='dashboard_sffm.php?page=sangjo/client_input/order';
    });


    $(document).on("click","#datatable-main tr td",function(){


        var url = "./contents/sangjo/out_order/api/getOrderInfo.php";
        var oocp_idx = $(this).closest("tr").attr("oocp_idx");

        if(!oocp_idx) {
            console.error("oocp_idx가 없습니다.");
            return;
        }

        console.log("요청 파라미터: oocp_idx=" + oocp_idx);
        var str = "oocp_idx="+oocp_idx; //



        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){

                    console.log("서버 응답:", result);
                    
                    // 응답 데이터 검증
                    if(!result) {
                        window.alert("서버에서 응답이 없습니다.");
                        return;
                    }
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        // 안전하게 객체 접근을 위한 함수
                        function safeGet(obj, key, defaultValue) {
                            return (obj && obj[key] !== undefined && obj[key] !== null) ? obj[key] : (defaultValue || "");
                        }

                        //toast(result.msg);
                        var order_info = result.data.order_info || {};
                        console.log("주문 정보:", order_info);
                        
                        $("#detail_section").attr("oocp_idx", oocp_idx);
                        $("#detail_section").find(".company_name").html(safeGet(order_info, "t_company_name"));
                        $("#detail_section").find(".company_name").attr("consulting_idx", safeGet(order_info, "consulting_idx"));
                        $("#detail_section").find(".product_name").html(safeGet(order_info, "product_name"));
                        $("#detail_section").find(".order_count").html(safeGet(order_info, "order_count"));
                        
                        // 가격 정보 표시 - 테이블 필드 사용
                        // 기본단가 (공급단가)
                        $("#detail_section").find(".client_price").html(number_format(safeGet(order_info, "client_price", 0)) + " 원");
                        
                        // 부가세액
                        $("#detail_section").find(".vat_amount").html(number_format(safeGet(order_info, "client_price_tax", 0)) + " 원");
                        
                        // 총단가 (공급단가 + 부가세)
                        $("#detail_section").find(".total_unit_price").html(number_format(safeGet(order_info, "client_price_sum", 0)) + " 원");
                        
                        // 총구매금액 (총 공급단가)
                        $("#detail_section").find(".total_purchase").html(number_format(safeGet(order_info, "total_client_price", 0)) + " 원");
                        
                        // 총부가세액 (총 부가세)
                        $("#detail_section").find(".total_vat").html(number_format(safeGet(order_info, "total_client_price_tax", 0)) + " 원");
                        
                        // 총결제금액 (총 공급단가 + 총 부가세)
                        $("#detail_section").find(".total_payment").html(number_format(safeGet(order_info, "total_client_price_sum", 0)) + " 원");
                        
                        $("#detail_section").find(".price_calcu").html(number_format(safeGet(order_info, "price_calcu", 0)) + " 원");

                        $("#detail_section").find(".to_place_name").html(safeGet(order_info, "to_place_name"));
                        $("#detail_section").find(".to_address").html(safeGet(order_info, "to_address"));
                        $("#detail_section").find(".to_name").html(safeGet(order_info, "to_name"));
                        $("#detail_section").find(".to_hp").html(safeGet(order_info, "to_hp"));
                        $("#detail_section").find(".memo").html(safeGet(order_info, "delivery_memo"));
                        $("#detail_section").find(".attachment").html("");

                        // 관리자/담당자 정보 표시
                        var manager_name = safeGet(order_info, "manager_name");
                        var admin_name = safeGet(order_info, "admin_name");
                        
                        if(manager_name) {
                            $("#detail_section").find(".manager").html(" ( 주문담당자: " + manager_name + " )");
                        } else if(admin_name) {
                            $("#detail_section").find(".manager").html(" ( 주문관리자: " + admin_name + " )");
                        } else {
                            $("#detail_section").find(".manager").html("");
                        }

                        // 첨부 파일 처리
                        var attachment_list = result.data.attachment_list || [];
                        console.log("첨부 파일 목록:", attachment_list);
                        
                        for(var i = 0; i < attachment_list.length; i++){
                            var filename = safeGet(attachment_list[i], "filename", "");
                            if(!filename) continue;
                            
                            var filename_ex = filename.split("/");
                            var this_lenth = filename_ex.length - 1;
                            var only_filename = filename_ex[this_lenth];

                            var ext = getExtensionOfFilename(only_filename);
                            //console.log(ext);

                            if(ext == ".png" || ext == ".jpg" || ext == ".jpeg" || ext == ".gif"){
                                $("#detail_section").find(".attachment").append("<a href='"+ filename +"' target='_blank' ><img src='"+ filename +"' class='attatch_img'></a>");
                            } else {
                                if(i != 0){
                                    $("#detail_section").find(".attachment").append("<br>");
                                }

                                if(ext == ".pdf"){
                                    $("#detail_section").find(".attachment").append("<a href='"+ filename +"' target='_blank' >"+ only_filename +"</a>");
                                } else {
                                    $("#detail_section").find(".attachment").append("<a href='"+ filename +"' download >"+ only_filename +"</a>");
                                }
                            }
                        }

                        $("#detail_section").removeClass("hide").show();

                        // 출고지 설정
                        var base_storage_idx = safeGet(order_info, "base_storage_idx");
                        if(base_storage_idx) {
                            $("#detail_section #out_storage").val(base_storage_idx);
                            $("#detail_section #out_storage").trigger("change");
                        }
                    } else {
                        // 오류 메시지 상세 표시
                        var errorMsg = "";
                        if(result.error) {
                            errorMsg = result.error;
                        } else if(result.error_details) {
                            errorMsg = result.error_details;
                        } else if(result.msg) {
                            errorMsg = result.msg;
                        } else {
                            errorMsg = "알 수 없는 오류가 발생했습니다. (상태 코드: " + result_status + ")";
                        }
                        
                        console.error("API 오류:", errorMsg);
                        window.alert(errorMsg);
                    }
                }, //success
                error : function( jqXHR, textStatus, errorThrown ) {
                    console.error("AJAX 오류:", textStatus, errorThrown);
                    console.error("응답 텍스트:", jqXHR.responseText);
                    
                    var errorMsg = "서버 통신 오류가 발생했습니다.\n";
                    errorMsg += "상태: " + jqXHR.status + " (" + jqXHR.statusText + ")\n";
                    
                    try {
                        // JSON 응답인지 확인
                        var jsonResponse = JSON.parse(jqXHR.responseText);
                        if(jsonResponse.error) {
                            errorMsg += "오류 메시지: " + jsonResponse.error;
                        } else if(jsonResponse.error_details) {
                            errorMsg += "오류 상세: " + jsonResponse.error_details;
                        }
                    } catch(e) {
                        // JSON이 아닌 경우 응답 텍스트 일부 표시
                        if(jqXHR.responseText) {
                            errorMsg += "응답: " + jqXHR.responseText.substring(0, 100) + (jqXHR.responseText.length > 100 ? "..." : "");
                        }
                    }
                    
                    alert(errorMsg);
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
        
        var url = "./contents/sangjo/storage_out/api/updateCell.php";
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

        
    var url = "./contents/sangjo/out_order/api/save_product_output.php";

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
