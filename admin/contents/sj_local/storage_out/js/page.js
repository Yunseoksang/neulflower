
//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){

    $(document).on("click", ".btn_add_receiver",function() {
        //openModal("new");

    });

    $(document).on("click", ".btn_show_detail",function() {

        var detail = $(this).parent().find(".detail_div").html();
        var $this_tr = $(this).closest('tr');
        var io_idx = $this_tr.attr("io_idx");
        $("#datatable-main").find("tr.tr_detail[io_idx='"+io_idx+"']").remove();

        $this_tr.after("<tr io_idx='"+io_idx+"' class='tr_detail'><td colspan='5'>"+detail+"</td></tr>");
        $(this).hide();
        $this_tr.find(".btn_hide_detail").removeClass("hide").show();

    });


    $(document).on("click", ".btn_hide_detail",function() {

        var $this_tr = $(this).closest('tr');
        var io_idx = $this_tr.attr("io_idx");
        $("#datatable-main").find("tr.tr_detail[io_idx='"+io_idx+"']").remove();

        $(this).hide();
        $this_tr.find(".btn_show_detail").show();

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


    $(document).on("click", ".btn_io_status",function() {
        var io_idx = $(this).closest("tr").attr("io_idx");
        var old_io_status = $(this).text();
        if(old_io_status == "출고완료" ){
            return;
        }


        $( "#dialog").dialog();
        $( "#dialog").find("input[name='io_idx']").val(io_idx);


    });
        

    $(document).on("click", ".btn_modal_close",function() {
        $( "#dialog").dialog("close");

    });


    $(document).on("click", ".btn_io_status_save",function() {
        

        //var io_status = $(this).attr("next_io_status");

        var url = "./contents/sj_local/storage_out/api/updateCell.php";
        var io_idx =$( "#dialog").find("input[name='io_idx']").val();
        var receiver_name =$( "#dialog").find("input[name='receiver_name']").val();

        var io_status="출고완료";

        if(receiver_name == ""){
            window.alert("인수자 이름은 필수입니다.");
            return;
        }


        var str = "mode=io_status&io_status="+io_status+"&io_idx="+io_idx+"&receiver_name="+receiver_name; //

        console.log(str);
        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        toast(result.msg);

                        $( "#dialog").dialog("close");


                        if(io_status == "출고완료"){
                            $("#datatable-main").find("tr[io_idx='"+io_idx+"']").find("button.btn_io_status").removeClass("btn-danger").addClass("btn-primary").text("출고완료");
                            //$("#datatable-main").find("tr[io_idx='"+io_idx+"']").find(".detail_div .receiver_name").text(receiver_name);
                            $("#datatable-main").find("tr[io_idx='"+io_idx+"']").find("span.receiver_name").text(receiver_name);

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



