
//var categoryList = {}; //배열이 아닌 객체로 선언


document.body.addEventListener("keydown", function(event) {

    if(event.key !== "Enter"){
        $("#barcode_input_all").val($("#barcode_input_all").val()+event.key);

    }else{
        if($("#barcode_input_all").val().length == 13){
            go_next_status_all_QR($("#barcode_input_all").val());

        }
        $("#barcode_input_all").val("");


    }
    //event.preventDefault();


    console.log("이벤트키:"+$("#barcode_input_all").val());
}, true)





$(document).ready(function(){


    
    $(document).on("change","#barcode_input",function() {
        go_next_status($(this).val());

    });

    $(document).on("paste","#barcode_input",function(el) {
        
        setTimeout(function(){
            go_next_status($(el.target).val());
            $(el.target).val("");

        },100);

    });

    

    $(document).on("change","#barcode_input_all",function(el) {
        go_next_status_all_QR($(el.target).val());

    });

    $(document).on("paste","#barcode_input_all",function(el) {
        
        setTimeout(function(){
            go_next_status_all_QR($(el.target).val());
            //$(el.target).val("");

        },100);

    });


    
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

          var isPlaying = video.currentTime > 0 && !video.paused && !video.ended && video.readyState > video.HAVE_CURRENT_DATA;

           if (!isPlaying) {
            video.play();
            }
          //video.play();
          tick();
          scan();
        });
    
    
    });
    

    $(document).on("click",".btn_qr_out",function(){
        //$("#hidden_qr").show();

        var io_idx = $(this).closest("tr").attr("io_idx");
        var product_idx = $(this).closest("tr").find("td.t_product_name").attr("product_idx");
        $("#inline_qr").trigger("click");
        $("#btn-scan-qr").trigger("click");

        $("#colorbox").attr("io_idx",io_idx);
        $("#colorbox").attr("product_idx",product_idx);
        $("#barcode_input").focus();

    });


    $(document).on("click",".btn_download_xls_list",function(){

        console.log($("#btn_search_refresh").hasClass("btn-danger"));

        if($("#btn_search_refresh").hasClass("btn-danger")){
            ////*[@id="reportrange"]/span
            //toast("준비중입니다");

            var range_value = $("#reportrange>span").text();
            var exrange = range_value.split("-");
            var sdate = exrange[0].trim();
            var edate = exrange[1].trim();

            // console.log(range_value);
            // console.log("sdate:"+sdate);
            // console.log("edate:"+edate);

            window.location.href="./contents/sangjo/storage_out/api/download_xls.php?mode=list&sdate="+sdate+"&edate="+edate;

        }else{
            //toast("준비중입니다");
            window.location.href="./contents/sangjo/storage_out/api/download_xls.php?mode=list";

        }


    });

    $(document).on("click",".btn_download_xls_view_delivery",function(){

        //toast("준비중입니다");
        window.location.href="./contents/sangjo/storage_out/api/download_xls.php?mode=view_delivery";

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



    //$(document).on("click","div.btn-group[col_name='io_status'] ul.dropdown-menu li a",function(e){



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
                    change_status($this);
                    
                },
                "닫기": function () {
                    //$.alert('Canceled!');
                },

            }
        });

    });


    $(document).on("click", ".btn_io_status_back_status",function() {

        $this = $(this);

        $.confirm({
            title: '', //타이틀
            content: '미출고 처리할까요?',  //메세지
            closeIcon: true, //우측상단 닫기버튼 보일지 여부
            closeIconClass: 'fa fa-close',  //닫기 버튼 아이콘
            type: 'red', //확인창 색상(옵션)
            boxWidth: '300px', //확인창 width
            useBootstrap: false,  //width 적용할때 false 로 해줘야 적용됨.

            
            buttons: {
                "확인": function () {
                    change_status($this);
                    
                },
                "닫기": function () {
                    //$.alert('Canceled!');
                },

            }
        });

    });


    $(document).on("click", ".btn_io_status",function() {
        change_status($(this));

    });







});




function change_status($btn){

    var old_io_status = $btn.text();
    if(old_io_status == "출고완료"){
        return;
    }

    var io_status = $btn.attr("next_io_status");
    //var old_val = $btn.closest("td").attr("io_status");
    
    if(io_status == "배송완료"){
        return;
    }

    var $tr = $btn.closest("tr");
    var io_idx = $tr.attr("io_idx");



    var qr_code = $tr.attr("qr_code");
    if(qr_code != undefined){
        var qr_parameter = "&qr_mode=qr&qr_code="+qr_code;
    }else{
        var qr_parameter = "";
    }


    var url = "./contents/sangjo/storage_out/api/updateCell.php";

    var str = "mode=io_status&io_status="+io_status+qr_parameter+"&io_idx="+io_idx; //
    if(io_status == "배송완료"){
        // var receive_date = $tr.find("input.receive_date").val();
        // if(receive_date == ""){
        //     window.alert("배송일을 입력해주세요");
        //     return;
        // }
        str = "mode=io_status&io_status="+io_status+qr_parameter+"&io_idx="+io_idx+"&receive_date="+receive_date; //
    }

    console.log(str);
    
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);

                    if(io_status == "출고완료"){
                        $btn.removeClass("btn-danger").addClass("btn-success").text("출고완료");
                        $btn.closest("td").find(".btn-primary").remove();
                        $btn.closest("tr").find(".btn_qr_out").remove();
                    }else if(io_status == "배송완료"){
                            $btn.removeClass("btn-dark").addClass("btn-success").text("출고완료");
                            $btn.closest("td").find(".btn_io_status_back_status").remove(); //미출고전환 버튼 삭제

                    }else if(io_status == "출고취소" || io_status == "미출고"){
                        $btn.closest("tr").remove();

                        // $btn.removeClass("btn-primary").addClass("btn-dark").text("취소완료");
                        // $btn.closest("td").find(".btn-danger").remove();
                        // $btn.closest("tr").find(".btn_qr_out").remove();

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



function go_next_status(qr_code){


    var io_idx = $("#colorbox").attr("io_idx");
    var product_idx = $("#colorbox").attr("product_idx");


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


    var $tr = $("tr[io_idx='"+io_idx+"']");

    
    var qr_length = qrcode.length;
    if(qr_length < 13){
        toast("바코드 인식값 오류입니다.");
        console.log("바코드 인식값 오류입니다.");
        return;
    }

    //beep 사운드 출력
    var audio = new Audio('./contents/qrRead/beep-07a.mp3'); 
    audio.play();
    
    var url = "./contents/sangjo/storage_out/api/updateCell.php";

    var str = "mode=io_status&qr_mode=qr&qr_code="+qr_code+"&io_status=출고완료&io_idx="+io_idx; //
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


                    $tr.find("button.btn_qr_out").removeClass("btn-info").addClass("btn-success").text("QR출고완료");
                    $tr.find("button.btn_io_status").remove(); //출고완료, 출고취소 버튼 삭제

                    
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
    





    var url = "./contents/sangjo/storage_out/api/updateCell.php";

    var str = "mode=io_status&qr_mode=qr&qr_code="+qr_code+"&io_status=출고완료&io_idx="+io_idx; //
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


                    $tr.find("button.btn_qr_out").removeClass("btn-info").addClass("btn-success").text("QR출고완료");
                    $tr.find("button.btn_io_status").remove(); //출고완료, 출고취소 버튼 삭제

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


