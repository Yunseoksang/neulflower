
//var categoryList = {}; //배열이 아닌 객체로 선언



$(document).ready(function(){


    $(document).on("click",".btn_add_client",function(){
        window.location.href="?page=flower/client/input";
    });


    $(document).on("click",".btn_add_sender",function(){

        var $sender_table = $("#sender_sample").find(".table_sender_info").clone(true);
        $sender_table.find("td.sender_setting").html("<button class='btn btn-success btn-xs btn_save_sender'>저장</button><br><button class='btn btn-danger btn-xs btn_cancel_sender'>취소</button>");
        $("div.x_panel_sender_info").after($sender_table);

    });

    $(document).on("click",".btn_cancel_sender",function(){
        reset_sender($(this));
    });



    $(document).on("click",".btn_save_sender",function(){

        var $table = $(this).closest("table");
        var client_flower_sender_idx = $table.attr("client_flower_sender_idx");
        if(client_flower_sender_idx == undefined || client_flower_sender_idx == ""){
            var mode = "insert";
        }else{
            var mode = "edit";
        }

        save_sender($table,mode);

   });

   
   $(document).on("click",".sender_del",function(){
        var $table = $(this).closest("table");
        

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
                    save_sender($table,"del");
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });

    });

       
   $(document).on("click",".sender_edit",function(){


        var $table = $(this).closest("table");

        var client_flower_sender_idx    = $table.attr("client_flower_sender_idx");
        var sender_name    = $table.find("td.sender_name").attr("old_value");




        var $new_table = $("#sender_sample").find("table").clone(true);
        $new_table.find("td.sender_setting").html("<button class='btn btn-success btn-xs btn_save_sender'>저장</button><br><button class='btn btn-danger btn-xs btn_cancel_sender'>취소</button>");

        $new_table.addClass("table_list");
        $new_table.attr("client_flower_sender_idx",client_flower_sender_idx);
        $new_table.find("input[name='sender_name']").val(sender_name);
        $new_table.find("td.sender_name").attr("old_value",sender_name);



        $table.after($new_table);
        $table.remove();


   });




    $(document).on("click",".btn_save_product",function(){
        save_product($(this),"insert");
    });

    $(document).on("click",".btn_edit_save_product",function(){
        save_product($(this),"edit");
    });
    
    $(document).on("click",".btn_edit_cancel_product",function(){
        var $tr = $(this).closest("tr");
        var client_price = $tr.find("td.client_price").attr("old_value");
        var client_price_tax           = $tr.find("td.client_price_tax").attr("old_value");
        var client_price_sum   = $tr.find("td.client_price_sum").attr("old_value");

        $tr.find("td.client_price").text(client_price);
        $tr.find("td.client_price_tax").text(client_price_tax);
        $tr.find("td.client_price_sum").text(client_price_sum);

        var setting_icon = $("#client_product_sample").find("td.product_setting").html();
        $tr.find("td.product_setting").html(setting_icon);
    });

    $(document).on("click",".product_edit",function(){
        
        var $tr = $(this).closest("tr");

        var client_price = $tr.find("td.client_price").attr("old_value");
        var client_price_tax           = $tr.find("td.client_price_tax").attr("old_value");
        var client_price_sum   = $tr.find("td.client_price_sum").attr("old_value");

        $tr.find("td.client_price").html('<input type="text" name="client_price" value="'+client_price+'" class="form-control col-xs-12">');
        $tr.find("td.client_price_tax").html('<input type="text" name="client_price_tax" disabled  value="'+client_price_tax+'" class="form-control col-xs-12">');
        $tr.find("td.client_price_sum").html('<input type="text" name="client_price_sum" value="'+client_price_sum+'"  class="form-control col-xs-12">');
        $tr.find("td.product_setting").html("<button class='btn btn-success btn-xs btn_edit_save_product'>저장</button> <button class='btn btn-danger btn-xs btn_edit_cancel_product'>취소</button>");
    });

    $(document).on("click",".product_del",function(){

        var $this_btn = $(this);
        

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
                    save_product($this_btn,"del");
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });

        
    });



    $(document).on("change","#new_product",function(){

        var product_idx      = $(this).find("option:selected").val();

        if(product_idx == "0"){

            var $tr = $("table.table_product_info tr.new_tr");
            $tr.find("input[name='client_price']").val("");
            $tr.find("input[name='client_price_tax']").val("");
            $tr.find("input[name='client_price_sum']").val("");
        }else{
            var client_price      = parseInt($(this).find("option:selected").attr("price"));
            var is_vat            = parseInt($(this).find("option:selected").attr("is_vat"));
            if(is_vat == 1){
                var client_price_tax = Math.round(client_price/10);

            }else{
                var client_price_tax = 0;

            }
            var client_price_sum = client_price + client_price_tax;

            var $tr = $("table.table_product_info tr.new_tr");
            $tr.find("input[name='client_price']").val(client_price);
            $tr.find("input[name='client_price_tax']").val(client_price_tax);
            $tr.find("input[name='client_price_sum']").val(client_price_sum);


            if(is_vat == 1){
                $tr.find("input[name='client_price_tax']").removeAttr("disabled");
            }else{
                $tr.find("input[name='client_price_tax']").attr("disabled",true);

            }


        }



    });



    $(document).on("keyup","input[name='client_price']",function(){
        var client_price      = parseInt($(this).val());
        var $tr = $(this).closest("tr");

        if($tr.find("input[name='client_price_tax']").prop("disabled")){
            var client_price_tax = 0;

        }else{
            var client_price_tax = Math.round(client_price/10);

        }

        var client_price_sum = client_price + client_price_tax;

        var $tr = $(this).closest("tr");
        $tr.find("input[name='client_price_tax']").val(client_price_tax);
        $tr.find("input[name='client_price_sum']").val(client_price_sum);

    });
    

    $(document).on("keyup","input[name='client_price_tax']",function(){
        var $tr = $(this).closest("tr");

        var client_price_tax      = parseInt($(this).val());

        var client_price = client_price_tax*10;
        var client_price_sum = client_price + client_price_tax;

        $tr.find("input[name='client_price']").val(client_price);
        $tr.find("input[name='client_price_sum']").val(client_price_sum);

    });
    

    $(document).on("keyup","input[name='client_price_sum']",function(){
        var $tr = $(this).closest("tr");
        var client_price_sum      = parseInt($(this).val());


        if($tr.find("input[name='client_price_tax']").prop("disabled")){
            var client_price_tax = 0;
            var client_price = client_price_sum;

        }else{
            var client_price_tax = Math.round(client_price/10);
            var client_price = Math.round(client_price_sum/1.1);

        }


        var client_price_tax      = client_price_sum - client_price;

        $tr.find("input[name='client_price']").val(client_price);
        $tr.find("input[name='client_price_tax']").val(client_price_tax);
    });
    



    $(document).on("click",".btn_show_manager",function(){

        $(".table_manager_info tr.hide_tr").removeClass("hide").show();
        $(this).hide();
        $(".btn_hide_manager").removeClass("hide").show();

    });

    $(document).on("click",".btn_hide_manager",function(){

        $(".table_manager_info tr.hide_tr").hide();
        $(this).hide();
        $(".btn_show_manager").show();

    });

    $(document).on("click",".company_edit",function(){

        var consulting_idx = $("table.table_company_key_info .company_name").attr("consulting_idx");

        window.location.href='dashbaord_ffm.php?page=client/input&consulting_idx='+consulting_idx;

    });


    $(document).on("click",".company_del",function(){

        var consulting_idx = $("table.table_company_key_info .company_name").attr("consulting_idx");


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
                    deleteCompany(consulting_idx);
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });



    });

    $(document).on("click",".memo_edit",function(){

        var $this_li = $(this).closest("li.memo_li");

        var memo_idx = $this_li.attr("memo_idx");
        var memo_title = $this_li.find("h2.title").text();
        var memo_text = $this_li.find(".excerpt").html();

        $("#memo_add_section").removeClass("hide").show();
        $("#memo_title").val(memo_title);
        $("#editor").html(memo_text);
        $("#memo_add_section").attr("memo_idx",memo_idx);
        $(".btn_save_history").text("수정하기");

    });

    $(document).on("click",".memo_del",function(){

        var memo_idx = $(this).closest("li.memo_li").attr("memo_idx");


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
                    deleteMemo($(this).closest("li.memo_li"),memo_idx);
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });



    });

    



    $(document).on("click",".btn_show_company_info",function(){

        var this_status = $(this).attr('status');
        if(this_status == "show"){
            $(this).attr("status","hide");
            $("table.table_company_info").hide();
            $("table.table_manager_info").hide();
        }else{
            $(this).attr("status","show");

            $("table.table_company_info").removeClass("hide");
            $("table.table_manager_info").removeClass("hide");
    
            $("table.table_company_info").show();
            $("table.table_manager_info").show();

        }
        

    });






    $(document).on("click","#memo_add_section span.memo_status",function(){
        var memo_status = $(this).text();
        if(memo_status == "상담"){
            var next_memo_status = "계약확정";
            var next_tag = "tag_blue";
        }else if(memo_status == "계약확정"){
            var next_memo_status = "계약완료";
            var next_tag = "tag_red";

        }else if(memo_status == "계약완료"){
            var next_memo_status = "상담";
            var next_tag = "";

        }

        $(this).parent().removeClass("tag_red");
        $(this).parent().removeClass("tag_blue");

        $(this).parent().addClass(next_tag);
        $(this).text(next_memo_status);

    });





    $(document).on("click",".btn_add_history",function(){
        $("#memo_add_section").removeClass("hide");
        $("#memo_add_section").show();
        $(".btn_save_history").text("저장하기");
        $("#memo_add_section").removeAttr("memo_idx");
        $("#memo_title").val("");
        $("#editor").html("");


    });
    $(document).on("click",".btn_cancel_history",function(){
        $("#memo_add_section").hide();
        $("#memo_title").val("");
        $("#descr").val("");
        $("#editor").html("");

    });






    $(document).on("click","td.td_x",function(){

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
                    delete_attach($(this));
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });
       

    });






    $(document).on("click","li.memo_li span.memo_status",function(){
        var memo_status = $(this).text();
        if(memo_status == "상담"){
            var next_memo_status = "계약확정";
            var next_tag = "tag_blue";
        }else if(memo_status == "계약확정"){
            var next_memo_status = "계약완료";
            var next_tag = "tag_red";

        }else if(memo_status == "계약완료"){
            var next_memo_status = "상담";
            var next_tag = "";

        }

        $(this).parent().removeClass("tag_red");
        $(this).parent().removeClass("tag_blue");

        $(this).parent().addClass(next_tag);
        $(this).text(next_memo_status);



        var url = "./contents/flower/client/api/updateCell.php";
        var memo_idx = $(this).closest(".memo_li").attr("memo_idx");
        var str = "mode=memo_status&memo_status="+next_memo_status+"&memo_idx="+memo_idx; 

        console.log(str);

        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        toast(result.msg);
                       
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





    $(document).on("click",".btn_save_history",function(){

        var url = "./contents/flower/client/api/insertMemo.php";
        var title = $("#memo_title").val();
        var memo = $("#editor").html();
        var memo_status = $("#memo_add_section").find(".memo_status").text();
        var consulting_idx = $("#detail_section").find(".company_name").attr("consulting_idx");

        var memo_idx =  parseInt($("#memo_add_section").attr("memo_idx"));
        if(memo_idx > 0){ //수정
            var str = "mode=edit&memo_idx="+memo_idx+"&consulting_idx="+consulting_idx+"&memo_status="+memo_status+"&memo_title="+title+"&memo_text="+memo; //

        }else{ //추가
            var str = "mode=add&consulting_idx="+consulting_idx+"&memo_status="+memo_status+"&memo_title="+title+"&memo_text="+memo; //

        }



        console.log(str);

        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        toast(result.msg);
                        $("#memo_add_section").hide();


                        var memo = result.data.memo;

                        if(memo_idx > 0){ //수정
                            var $memo_li = $("li.memo_li[memo_idx='"+memo_idx+"']");
                            //$memo_li.attr("memo_idx",memo.memo_idx);
                            $memo_li.find(".tag_title").html(memo.memo_status);
                            $memo_li.find(".tag").removeClass("tag_red");
                            $memo_li.find(".tag").removeClass("tag_blue");

                            if(memo.memo_status == "계약완료"){
                                $memo_li.find(".tag").addClass("tag_red");
                            }else if(memo.memo_status == "계약확정"){
                                $memo_li.find(".tag").addClass("tag_blue");
                            }

                            $memo_li.find("h2.title").html(memo.memo_title);
                            $memo_li.find(".update_datetime").html(memo.utime);
                            $memo_li.find(".awriter").html(memo.admin_name);
    
                            $memo_li.find(".excerpt").html(memo.memo_text);
                            //$memo_li.find(".excerpt").append("<br><a class='text_more'>Read&nbsp;More</a>");
    

    
                        }else{ //추가

                            var $memo_li = $("#memo_sample").find("li.memo_li").clone(true);
                            $memo_li.attr("memo_idx",memo.memo_idx);
                            $memo_li.find(".tag_title").html(memo.memo_status);
                            if(memo.memo_status == "계약완료"){
                                $memo_li.find(".tag").addClass("tag_red");
                            }else if(memo.memo_status == "계약확정"){
                                $memo_li.find(".tag").addClass("tag_blue");
                            }

                            $memo_li.find("h2.title").html(memo.memo_title);
                            $memo_li.find(".update_datetime").html(memo.utime);
                            $memo_li.find(".awriter").html(memo.admin_name);
    
                            $memo_li.find(".excerpt").html(memo.memo_text);
                            //$memo_li.find(".excerpt").append("<br><a class='text_more'>Read&nbsp;More</a>");
    
                            $("#memo_add_section").after($memo_li);
    

    
                        }
                            //초기화
                            $("#memo_title").val("");
                            $("#editor").html("");

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



    $(document).on("click","div.btn-group[col_name='client_status'] ul.dropdown-menu li a",function(e){

        var url = "./contents/flower/client/api/updateCell.php";
        var client_status = $(this).text();
        var consulting_idx = $(this).closest("tr").attr("consulting_idx");

        var str = "mode=client_status&client_status="+client_status+"&consulting_idx="+consulting_idx; //

        console.log(str);


        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        toast(result.msg);
                        $(this).closest("td").attr("client_status",client_status);
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




    $(document).on("click","#datatable-main td.company_name",function(){
        
        var consulting_idx = $(this).closest("tr").attr("consulting_idx");
         window.location.href='?page=consulting/detail&consulting_idx='+consulting_idx;
    });

/*

    $(document).on("click","#datatable-main td.company_name",function(){


        var url = "./contents/flower/client/api/getInfo.php";
        var consulting_idx = $(this).closest("tr").attr("consulting_idx");


        var str = "consulting_idx="+consulting_idx; //



        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){

                    console.log(result);
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {


                        view_detail_section("mode1",result);

                        
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


*/

    $(document).on("click","#datatable-main button.btn_show_client_info",function(){


        var url = "./contents/flower/client/api/getInfo.php";
        var consulting_idx = $(this).closest("tr").attr("consulting_idx");


        var str = "consulting_idx="+consulting_idx; //



        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){

                    console.log(result);
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {


                        view_detail_section("mode2",result);

                        
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


function view_detail_section(mode,result){

    //toast(result.msg);
    var company_info = result.data.company_info;


    $("#detail_section").find(".company_name").html(company_info.company_name);
    $("#detail_section").find(".company_name").attr("consulting_idx",company_info.consulting_idx);



    if(mode == "mode1"){

        $("table.table_company_info").removeClass("hide").show();
        $("table.table_manager_info").removeClass("hide").show();
        $("div.x_panel_memo_history ").removeClass("hide").show();
        $("div.x_panel_attachment").removeClass("hide").show();



        $("div.x_panel_product_info").removeClass("hide").hide();
        $("table.table_product_info").removeClass("hide").hide();

        $("div.x_panel_sender_info").removeClass("hide").hide();
        $("table.table_sender_info").removeClass("hide").hide();



        // $("#detail_section").find(".employees").html(company_info.employees);
        // $("#detail_section").find(".employment_fee").html(company_info.employment_fee);
        // $("#detail_section").find(".trading_items").html(company_info.trading_items);
        // $("#detail_section").find(".tradeable_items").html(company_info.tradeable_items);
    
        $("#detail_section").find(".biz_num").html(company_info.biz_num);
        $("#detail_section").find(".biz_type").html(company_info.biz_type);
        $("#detail_section").find(".corp_num").html(company_info.corp_num);
        $("#detail_section").find(".biz_part").html(company_info.biz_part);
        $("#detail_section").find(".ceo_name").html(company_info.ceo_name);
        $("#detail_section").find(".address").html(company_info.address);
        $("#detail_section").find(".tel").html(company_info.tel);
        $("#detail_section").find(".homepage").html(company_info.homepage);
        $("#detail_section").find(".fax").html(company_info.fax);
        $("#detail_section").find(".memo").html(company_info.memo);
    
    
    
    
        $(".table_manager_info").find("tbody").empty();
        var manager_list = result.data.manager_list;
        for(var i=0;i<manager_list.length;i++){
    
            var $manager_tr = $("#manager_sample").find("tr").clone(true);
            $manager_tr.attr("manager_idx", manager_list[i].manager_idx);
            $manager_tr.find("td.manager_name").text(manager_list[i].manager_name);
            $manager_tr.find("td.manager_position").text(manager_list[i].manager_position);
            $manager_tr.find("td.manager_department").text(manager_list[i].manager_department);
            $manager_tr.find("td.manager_email").text(manager_list[i].manager_email);
            $manager_tr.find("td.manager_tel").text(manager_list[i].manager_tel);
            $manager_tr.find("td.manager_hp").text(manager_list[i].manager_hp);
    
            $(".table_manager_info").find("tbody").append($manager_tr);
        }
    
    
    
    
    
    
        $(".ul_memo_history").find("li.memo_li").remove();
        var memo_list = result.data.memo_list;
        for(var i=0;i<memo_list.length;i++){
            var $memo_li = $("#memo_sample").find("li.memo_li").clone(true);
            $memo_li.attr("memo_idx",memo_list[i].memo_idx);
            $memo_li.find(".tag_title").html(memo_list[i].memo_status);
            if(memo_list[i].memo_status == "계약확정"){
                $memo_li.find(".tag").addClass("tag_blue");
            }else if(memo_list[i].memo_status == "계약완료"){
                $memo_li.find(".tag").addClass("tag_red");
            }
    
            $memo_li.find("h2.title").html(memo_list[i].memo_title);
            $memo_li.find(".update_datetime").html(memo_list[i].utime);
            $memo_li.find(".awriter").html(memo_list[i].admin_name);
    
            $memo_li.find(".excerpt").html(memo_list[i].memo_text);
            //$memo_li.find(".excerpt").append("<br><a class='text_more'>Read&nbsp;More</a>");
            $(".ul_memo_history").append($memo_li);
        }
    
    
        $("#table_attach_list").find("tbody").empty();
        var attachment_list = result.data.attachment_list;
        for(var i=0;i<attachment_list.length;i++){
    
            var $attach_tr = $("#attachment_sample").find("tr").clone(true);
            $attach_tr.attr("attachment_idx", attachment_list[i].attachment_idx);
    
            $attach_tr.find(".th_num").text(i+1);
            var filename_ex = attachment_list[i].filename.split("/");
            var this_lenth = filename_ex.length -1;
            $attach_tr.find(".td_filename a").text(filename_ex[this_lenth]).attr("href",attachment_list[i].filename);
    
            $("#table_attach_list").find("tbody").append($attach_tr);
        }
    
    }else if(mode == "mode2"){

        $("table.table_company_info").removeClass("hide").hide();
        $("table.table_manager_info").removeClass("hide").hide();
        $("div.x_panel_memo_history").removeClass("hide").hide();
        $("div.x_panel_attachment").removeClass("hide").hide();


        $("div.x_panel_product_info").removeClass("hide").show();
        $("table.table_product_info").removeClass("hide").show();

        $("div.x_panel_sender_info").removeClass("hide").show();
        $("table.table_sender_info").removeClass("hide").show();

        
        $(".table_product_info").find("tbody").empty();
        var client_product_list = result.data.client_product_list;
        for(var i=0;i<client_product_list.length;i++){
    
            var $product_tr = $("#client_product_sample").find("tr").clone(true);
            $product_tr.attr("client_product_idx", client_product_list[i].client_product_idx);
            $product_tr.attr("product_idx", client_product_list[i].product_idx);

            $product_tr.find("td.product_name").text(client_product_list[i].product_name).attr("old_value",client_product_list[i].product_name);
            $product_tr.find("td.client_price").text(client_product_list[i].client_price).attr("old_value",client_product_list[i].client_price);
            $product_tr.find("td.client_price_tax").text(client_product_list[i].client_price_tax).attr("old_value",client_product_list[i].client_price_tax);
            $product_tr.find("td.client_price_sum").text(client_product_list[i].client_price_sum).attr("old_value",client_product_list[i].client_price_sum);


            $(".table_product_info").find("tbody").append($product_tr);
        }


        $("#detail_section").find("table.table_list").remove();
        var sender_list = result.data.sender_list;
        for(var i=0;i<sender_list.length;i++){
    
            var $sender_table = $("#sender_sample").find("table").clone(true).addClass("table_list");
                $sender_table.find("input").remove();
                $sender_table.attr("client_flower_sender_idx", sender_list[i].client_flower_sender_idx);

                $sender_table.find("td.sender_name").text(sender_list[i].sender_name).attr("old_value",sender_list[i].sender_name);



            $("div.x_panel_sender_info").after($sender_table);
        }







    }


    $("#detail_section").removeClass("hide").show();
}



//회사 정보 삭제
function deleteCompany(consulting_idx){

    var url = "./contents/flower/client/api/updateCell.php";

    var str = "mode=del&consulting_idx="+consulting_idx; //

    console.log(str);


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);
                    window.location.href='dashbaord_ffm.php';
                    
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






//메모 삭제
function deleteMemo($obj,memo_idx){

    var url = "./contents/flower/client/api/insertMemo.php";

    var str = "mode=del&memo_idx="+memo_idx; //

    console.log(str);


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);
                    //$obj.remove();
                    $("li.memo_li[memo_idx='"+memo_idx+"']").remove();

                    
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




function ajaxFileUpload() {


    var consulting_idx = $("table.table_company_key_info").find("th.company_name").attr("consulting_idx");
    if(consulting_idx == undefined || consulting_idx == ""){
        toast("고유번호IDX값이 없습니다.");
        return;
    }

    var url = "./contents/flower/client/api/uploadFile.php";


    var form = jQuery("ajaxFrom")[0];
    var formData = new FormData(form);
    formData.append("consulting_idx", consulting_idx);
    formData.append("attachFile", jQuery("#attachFile")[0].files[0]);


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

                var $attach_tr = $("#attachment_sample").find("tr").clone(true);
                $attach_tr.attr("attachment_idx",attachment.attachment_idx);
                var tr_lenth = $("#table_attach_list").find("tbody").find("tr").length;
                $attach_tr.find(".th_num").text(tr_lenth+1);
                var filename_ex = attachment.filename.split("/");
                var this_lenth = filename_ex.length -1;
                $attach_tr.find(".td_filename a").text(filename_ex[this_lenth]).attr("href",attachment.filename);
                $("#table_attach_list").find("tbody").append($attach_tr);

                $("#attachFile").val(null);


               
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



function delete_attach($obj){
    
    var attachment_idx = $obj.closest("tr").attr("attachment_idx");
    var url = "./contents/flower/client/api/uploadFile_del.php";
    var str = "attachment_idx="+attachment_idx; 

    console.log(str);

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);
                    $("#table_attach_list").find("tr[attachment_idx='"+attachment_idx+"']").remove();

                   
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



function save_sender($table,mode){

    var sender_name = $table.find("input[name='sender_name']").val();


    
    var url = "./contents/flower/client/api/saveClientSender.php";

    var str = "mode="+mode; 

    if(mode == "insert"){
        var consulting_idx = $("table.table_company_key_info").find("th.company_name").attr("consulting_idx");
        str += "&consulting_idx="+consulting_idx;
    }

    if(mode == "edit" || mode == "del"){
        var client_flower_sender_idx = $table.attr("client_flower_sender_idx");
        str += "&client_flower_sender_idx="+client_flower_sender_idx;
    }

    if(mode == "insert" || mode == "edit"){
        str += "&sender_name="+sender_name;
    }

    console.log(str);

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);

                    if(mode == "insert"){
                        var sender_info = result.data.sender_info;

                        $table.addClass('table_list');
                        $table.attr("client_flower_sender_idx",sender_info.client_flower_sender_idx);
                        $table.find("td.sender_name").html(sender_info.sender_name).attr("old_value",sender_name);
                        var setting_icon = $("#sender_sample").find("td.sender_setting").html();
                        $table.find("td.sender_setting").html(setting_icon);
                    }else if(mode == "edit"){
                        var sender_info = result.data.sender_info;

                        $table.find("td.sender_name").html(sender_info.sender_name).attr("old_value",sender_name);

                        var setting_icon = $("#sender_sample").find("td.sender_setting").html();
                        $table.find("td.sender_setting").html(setting_icon);
                    }else if(mode == "del"){
                        $table.remove();
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


function reset_sender($obj){


    var $table = $obj.closest("table");
    var client_flower_sender_idx = $table.attr("client_flower_sender_idx");
    if(client_flower_sender_idx == undefined){
        $table.remove();
    }else{
        var sender_name    = $table.find("td.sender_name").attr("old_value");


        $table.find("td.sender_name").html(sender_name);

        var setting_icon = $("#sender_sample").find("td.sender_setting").html();
        $table.find("td.sender_setting").html(setting_icon);
    }


}

function save_product($obj,mode){

    $tr = $obj.closest('tr');
    
    var client_price     = $tr.find("input[name='client_price']").val();
    var client_price_tax = $tr.find("input[name='client_price_tax']").val();
    var client_price_sum = $tr.find("input[name='client_price_sum']").val();

    
    var url = "./contents/flower/client/api/saveClientProduct.php";

    var str = "mode="+mode; 

    if(mode == "insert"){
        var product_idx      = $tr.find("select[name='product_idx'] option:selected").val();

    }else{
        var product_idx      = $tr.attr("product_idx");


    }

    var client_product_idx = $tr.attr("client_product_idx");
    str += "&client_product_idx="+client_product_idx;

    var consulting_idx = $("table.table_company_key_info").find("th.company_name").attr("consulting_idx");
    str += "&consulting_idx="+consulting_idx;

    str += "&product_idx="+product_idx;
    str += "&client_price="+client_price;
    str += "&client_price_tax="+client_price_tax;
    str += "&client_price_sum="+client_price_sum;


    console.log(str);

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);

                    if(mode == "insert"){
                        var product_info = result.data.product_info;

                        var $new_tr = $("#client_product_sample").find("tr").clone(true);

                        $new_tr.attr("client_product_idx",product_info.client_product_idx);
                        $new_tr.find("td.product_name").html(product_info.product_name).attr("old_value",product_info.product_name);
                        $new_tr.find("td.client_price").html(product_info.client_price).attr("old_value",product_info.client_price);
                        $new_tr.find("td.client_price_tax").html(product_info.client_price_tax).attr("old_value",product_info.client_price_tax);
                        $new_tr.find("td.client_price_sum").html(product_info.client_price_sum).attr("old_value",product_info.client_price_sum);
                        var setting_icon = $("#client_product_sample").find("td.product_setting").html();
                        $new_tr.find("td.product_setting").html(setting_icon);

                        $(".table_product_info tbody").append($new_tr);

                    }else if(mode == "edit"){
                        var product_info = result.data.product_info;

                        $tr.find("td.product_name").html(product_info.product_name).attr("old_value",product_info.product_name);
                        $tr.find("td.client_price").html(product_info.client_price).attr("old_value",product_info.client_price);
                        $tr.find("td.client_price_tax").html(product_info.client_price_tax).attr("old_value",product_info.client_price_tax);
                        $tr.find("td.client_price_sum").html(product_info.client_price_sum).attr("old_value",product_info.client_price_sum);
                        var setting_icon = $("#client_product_sample").find("td.product_setting").html();
                        $tr.find("td.product_setting").html(setting_icon);
                    }else if(mode == "del"){
                        $tr.remove();
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