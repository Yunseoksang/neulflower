
//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){




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


    //$(document).on("click","div.btn-group[col_name='io_status'] ul.dropdown-menu li a",function(e){

        

    $(document).on("click", ".btn_io_status",function() {
            
        var url = "./contents/sj_local/storage_move/api/updateCell.php";

        var old_io_status = $(this).text();
        if(old_io_status == "이동출고완료" || old_io_status == "이동입고완료"){
            return;
        }



        var io_status = $(this).attr("next_io_status");


        var old_val = $(this).closest("td").attr("io_status");
        var io_idx = $(this).closest("tr").attr("io_idx");



        var $tr = $(this).closest("tr");
        var out_date = $tr.find("input[name='out_date']").val();
        var receive_date = $tr.find("input[name='receive_date']").val();
        var memo = $tr.find("textarea[name='memo']").val();

        //var str = "mode=io_status&io_status="+io_status+"&io_idx="+io_idx+"&out_date="+out_date+"&receive_date="+receive_date+"&memo="+memo; //
        var str = "mode=io_status&io_status="+io_status+"&io_idx="+io_idx+"&out_date="+out_date+"&memo="+memo; //

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






    $(document).on("click","div.btn-group[col_name='delivery_type'] ul.dropdown-menu li a",function(e){



        var url = "./contents/sj_local/storage_move/api/updateCell.php";
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



