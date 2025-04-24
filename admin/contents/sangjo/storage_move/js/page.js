document.body.addEventListener("keydown", function(event) {
    if(event.key !== "Enter"){
        $("#barcode_input_all").val($("#barcode_input_all").val()+event.key);
    }else{
        if($("#barcode_input_all").val().length == 13){
            go_next_status_all_QR($("#barcode_input_all").val());
        }
        $("#barcode_input_all").val("");
    }
    console.log("이벤트키:"+$("#barcode_input_all").val());
}, true);

function selectDropDownReset($obj){
    //오류로 selectDropDown 원상복귀
    var column =$obj.closest("td").attr("column");
    var default_val= $obj.closest("td").attr(column);
    $targetBox =$obj.closest("div[col_name='"+column+"']");

    $targetBox.find("button").first().text(default_val);
    $targetBox.find("button").first().removeClass().addClass("btn").addClass(column);
    $targetBox.find(".dropdown-toggle").removeClass().addClass("btn dropdown-toggle");

    var default_color = $targetBox.find("a:contains('"+default_val+"')").attr("selected_color");
    console.log(default_color);
    $targetBox.find("button").first().addClass(default_color);
    $targetBox.find(".dropdown-toggle").addClass(default_color);
}

$(document).ready(function(){


    $(document).on("change","#barcode_input",function(el) {
        go_next_status($(el.target).val());

    });

    $(document).on("paste","#barcode_input",function(el) {
        
        setTimeout(function(){
            go_next_status($(el.target).val());
            $(el.target).val("");

        },100);

    });


    /*
    $(document).on("change","#barcode_input_all",function(el) {
        go_next_status_all_QR($(el.target).val());

    });

    $(document).on("paste","#barcode_input_all",function(el) {
        
        setTimeout(function(){
            go_next_status_all_QR($(el.target).val());
            //$(el.target).val("");

        },100);

    });

    */

    $(document).on("click","#btn-scan-qr",function(){


        navigator.mediaDevices
        .getUserMedia({ video: { facingMode: "environment" } })
        .then(function(stream) {
          scanning = true;
          //qrResult.hidden = true;
          btnScanQR.hidden = true;
          canvasElement.hidden = false;
          video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
          video.srcObject = stream;
          video.play();
          tick();
          scan();
        });
    
    
    });

    $(document).on("click",".btn_qr_moveout,.btn_qr_movein",function(){
        //$("#hidden_qr").show();

        var io_idx = $(this).closest("tr").attr("io_idx");
        var product_idx = $(this).closest("tr").find("td.t_product_name").attr("product_idx");
        $("#inline_qr").trigger("click");
        $("#btn-scan-qr").trigger("click");

        $("#colorbox").attr("io_idx",io_idx);
        $("#colorbox").attr("product_idx",product_idx);

        $("#barcode_input").focus();


    });



    $(document).on("click",".btn_download_xls_moveout",function(){

        //toast("준비중입니다");
        window.location.href="./contents/sangjo/storage_move/api/download_xls.php?mode=moveout";

    });


    $(document).on("click",".btn_download_xls_movein",function(){

        //toast("준비중입니다");
        window.location.href="./contents/sangjo/storage_move/api/download_xls.php?mode=movein";
    
    });
    
    

    
    // //select2 변경시 이벤트 호출  //설명 https://select2.org/programmatic-control/events#preventing-events
    // $(document).on('select2:select','.select2_single', function (e) {
    //     var data = e.params.data;
    //     var $sel = $(this).closest("div.form-group").find("select");

    //     var column = $(this).closest('td').attr('column');
    //     $(this).closest("td").attr(column+"_new",data.id);

    //     // //console.log(data.text);  //select 의 text 값
    //     // //console.log(data.id);  //value 값임.
    // });


    $(document).on("click", ".btn_io_status",function() {
        

        var old_io_status = $(this).text();
        if(old_io_status == "이동출고완료" || old_io_status == "이동입고완료"){
            return;
        }



        var io_status = $(this).attr("next_io_status");



        var url = "./contents/sangjo/storage_move/api/updateCell.php";
        var old_val = $(this).closest("td").attr("io_status");
        var io_idx = $(this).closest("tr").attr("io_idx");



        var $tr = $(this).closest("tr");
        var $td = $(this).closest("td");
        var out_date = $tr.find("input[name='out_date']").val();
        var receive_date = $tr.find("input[name='receive_date']").val();
        var memo = $tr.find("textarea[name='memo']").val();


        var qr_code = $tr.attr("qr_code");
        if(qr_code != undefined){
            var qr_parameter = "&qr_mode=qr&qr_code="+qr_code;
        }else{
            var qr_parameter = "";
        }


        var str = "mode=io_status&io_status="+io_status+qr_parameter+"&io_idx="+io_idx+"&out_date="+out_date+"&receive_date="+receive_date+"&memo="+memo; //

        console.log(url);
        console.log(str);



        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {

                        toast(result.msg);
                        $(this).closest("td").attr("io_status",io_status);

                        if(io_status == "이동출고완료"){
                            $(this).removeClass("btn-danger").addClass("btn-success").text("이동출고완료");
                        }else if(io_status == "이동입고완료"){
                            $(this).removeClass("btn-warning").addClass("btn-primary").text("이동입고완료");
                        }
                        $td.find(".io_status_cancel").remove();

 
                        
                    }else{
                        
                        //오류로 selectDropDown 원상복귀
                        selectDropDownReset($(this));

                        var msg = result.msg;

                        window.alert(result.msg);
                    }
                }, //success
                error : function( jqXHR, textStatus, errorThrown ) {
                    alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
                }
        }); //ajax

    });




    $(document).on("click", ".btn_io_status_cancel",function() {

        $this = $(this);

        $.confirm({
            title: '', //타이틀
            content: '취소처리할까요?',  //메세지
            closeIcon: true, //우측상단 닫기버튼 보일지 여부
            closeIconClass: 'fa fa-close',  //닫기 버튼 아이콘
            type: 'red', //확인창 색상(옵션)
            boxWidth: '300px', //확인창 width
            useBootstrap: false,  //width 적용할때 false 로 해줘야 적용됨.

            
            buttons: {
                "확인": function () {
                    status_cancel($this);
                    
                },
                "닫기": function () {
                    //$.alert('Canceled!');
                },

            }
        });

    });








    $(document).on("click","div.btn-group[col_name='delivery_type'] ul.dropdown-menu li a",function(e){



        var url = "./contents/sangjo/storage_move/api/updateCell.php";
        var delivery_type = $(this).text();
        var io_idx = $(this).closest("tr").attr("io_idx");

        if(delivery_type == "-배송방법-"){
            return;

        }



        var str = "mode=delivery_type&delivery_type="+delivery_type+"&io_idx="+io_idx; //
        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {

                        toast(result.msg);

                        $(this).closest("td").attr("delivery_type",delivery_type);

 
                        
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



function status_cancel($btn){
    
    var old_io_status = $btn.text();
    if(old_io_status == "이동출고완료" || old_io_status == "이동입고완료" || old_io_status == "취소완료"){
        return;
    }



    var io_status = $btn.attr("next_io_status");



    var url = "./contents/sangjo/storage_move/api/updateCell.php";
    var io_idx = $btn.closest("tr").attr("io_idx");
    var $tr = $btn.closest("tr");
    var $td = $btn.closest("td");


    var str = "mode=io_status&io_status="+io_status+"&io_idx="+io_idx; //

    console.log(url);
    console.log(str);


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {

                    toast(result.msg);
                    $td.attr("io_status",io_status);

                    if(io_status == "이동출고취소"){
                        $btn.removeClass("btn-primary").addClass("btn-dark").text("취소완료");
                    }

                    $td.find(".btn_io_status").remove();


                    
                }else{
                    
                    //오류로 selectDropDown 원상복귀
                    selectDropDownReset($btn);

                    var msg = result.msg;

                    window.alert(result.msg);
                }
            }, //success
            error : function( jqXHR, textStatus, errorThrown ) {
                alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
            }
    }); //ajax
}


function go_next_status(qr_code){


    var io_idx = $("#colorbox").attr("io_idx");
    var product_idx = $("#colorbox").attr("product_idx");

    var $tr = $("tr[io_idx='"+io_idx+"']");
    var $btn_io_status = $tr.find(".btn_io_status");
    var next_io_status = $btn_io_status.attr("next_io_status");

    var out_date = $tr.find("input[name='out_date']").val();
    var receive_date = $tr.find("input[name='receive_date']").val();
    var memo = $tr.find("textarea[name='memo']").val();


    if(io_idx == undefined || io_idx == "undefined"){
        return;
    }
    if(product_idx == undefined || product_idx == "undefined"){
        return;
    }

    var str_len = product_idx.length;
    var mlen = -1*str_len;
    var cut_qr_idx = qr_code.slice(mlen);
    if(cut_qr_idx != product_idx){
        toast("바코드가 일치하지 않습니다");
        console.log("바코드가 일치하지 않습니다.");
        console.log("cut_qr_idx:"+cut_qr_idx);
        console.log("product_idx:"+product_idx);

        return;
    }else{
        console.log("바코드가 일치합니다.");


    }

    
    var qr_length = qr_code.length;
    if(qr_length < 13){
        toast("바코드 인식값 오류입니다.");
        console.log("바코드 인식값 오류입니다.");
        return;
    }

    //beep 사운드 출력
    var audio = new Audio('./contents/qrRead/beep-07a.mp3'); 
    audio.play();
    



    var old_io_status = $btn_io_status.text();
    if(old_io_status == "이동출고완료" || old_io_status == "이동입고완료"){

        toast("이미 완료된 내역입니다");

        return;
    }



    var url = "./contents/sangjo/storage_move/api/updateCell.php";

    var str = "mode=io_status&qr_mode=qr&qr_code="+qr_code+"&io_status="+next_io_status+"&io_idx="+io_idx+"&out_date="+out_date+"&receive_date="+receive_date+"&memo="+memo; //

    console.log(url);
    console.log(str);


    $("#barcode_input").val("");


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);

                    $.fn.colorbox.close();
                    speech(result.msg_text);


                    if(next_io_status == "이동출고완료"){
                        $tr.find("button.btn_qr_out").removeClass("btn-info").addClass("btn-success").text("QR출고완료");
                        $tr.find("button.btn_io_status").remove(); //출고완료, 출고취소 버튼 삭제
                    }else if(next_io_status == "이동입고완료"){
                        $tr.find("button.btn_qr_out").removeClass("btn-info").addClass("btn-success").text("QR입고완료");
                        $tr.find("button.btn_io_status").remove(); //출고완료, 출고취소 버튼 삭제
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





function go_next_status_all_QR(qr_code){
    $("#barcode_input_all").val("");


    var product_idx = Number(qr_code.substr(8,5));
    var $td = $("td.t_product_name[product_idx='"+product_idx+"']");


    console.log(product_idx);


    if($td.length == 0){
        toast("일치하는 값이 없습니다.");
        return;
    }

    console.log("td개수:"+$td.length);

    var $tr = $td.closest("tr");


    //한번 인식했는지 여부
    if($tr.attr("qr_checked") == "ok"){


        //목록에서 유일한 상품인지 여부
        if($td.length > 1){
            toast("상품 중복으로 인해 개별처리 바랍니다.");
            return;
        }

        
    }else{

        $tr.find("td").css("background","#d3f5bd");
        $tr.attr("qr_checked","ok"); 
        $tr.attr("qr_code",qr_code);

        $("#datatable-main tbody").prepend($tr);
        return;
    }



    var io_idx = $tr.attr("io_idx");

    var $btn_io_status = $tr.find(".btn_io_status");
    var next_io_status = $btn_io_status.attr("next_io_status");

    var out_date = $tr.find("input[name='out_date']").val();
    var receive_date = $tr.find("input[name='receive_date']").val();
    var memo = $tr.find("textarea[name='memo']").val();


    if(io_idx == undefined || io_idx == "undefined"){
        return;
    }
    if(product_idx == undefined || product_idx == "undefined"){
        return;
    }

    
    var qr_length = qr_code.length;
    if(qr_length < 13){
        toast("바코드 인식값 오류입니다.");
        console.log("바코드 인식값 오류입니다.");
        return;
    }

    //beep 사운드 출력
    var audio = new Audio('./contents/qrRead/beep-07a.mp3'); 
    audio.play();
    



    var old_io_status = $btn_io_status.text();
    if(old_io_status == "이동출고완료" || old_io_status == "이동입고완료"){

        toast("이미 완료된 내역입니다");

        return;
    }



    var url = "./contents/sangjo/storage_move/api/updateCell.php";

    var str = "mode=io_status&qr_mode=qr&qr_code="+qr_code+"&io_status="+next_io_status+"&io_idx="+io_idx+"&out_date="+out_date+"&receive_date="+receive_date+"&memo="+memo; //

    console.log(url);
    console.log(str);




    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);

                    $.fn.colorbox.close();
                    speech(result.msg_text);


                    if(next_io_status == "이동출고완료"){
                        $tr.find("button.btn_qr_out").removeClass("btn-info").addClass("btn-success").text("QR출고완료");
                        $tr.find("button.btn_io_status").remove(); //출고완료, 출고취소 버튼 삭제
                    }else if(next_io_status == "이동입고완료"){
                        $tr.find("button.btn_qr_out").removeClass("btn-info").addClass("btn-success").text("QR입고완료");
                        $tr.find("button.btn_io_status").remove(); //출고완료, 출고취소 버튼 삭제
                    }

                    $tr.remove();



                    
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


