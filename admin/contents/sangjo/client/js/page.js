//var categoryList = {}; //배열이 아닌 객체로 선언



$(document).ready(function(){


    $(document).on("change","select[name='category_manager']",function(){

        var this_val = $(this).val();
        if(this_val == "신규입력"){
            var $td = $(this).closest("td");
            var $tr = $(this).closest("tr");

            //$(this).hide();
            $td.html("<input type=text class='form-control category_manager_new' name='category_manager_new'>");
            $tr.find("td.td_smi_btn").html('<button class="btn btn-sm btn-info btn_add_new_manger">저장</button>');
            $tr.find("td.td_smi_btn").append('<button class="btn btn-sm btn-dark btn_add_new_manger_cancel">취소</button>');
            $tr.find("input.smi_email").removeAttr("readonly");

        }else{
            saveCategoryManager($(this),"manager_set");

        }
    });

    $(document).on("click","button.btn_add_new_manger",function(){


        saveCategoryManager($(this),"manager_new");


    });


    $(document).on("change","select[name='sdate']",function(){
        saveCategoryManager($(this),"sdate");
    });



    $(document).on("click","button.btn_del_tr, button.btn_add_new_manger_cancel",function(){
        $(this).closest("tr").remove();
        reset_smi_num();
    });
    


    


    

    $(document).on("click",".btn_add_category_manger",function(){
       var $this_tr = $(this).closest("tr");
       var category1_idx = $this_tr.attr("category1_idx");
       var category_manager = $this_tr.find("select.category_manager").val();

       if(category_manager == "" || category_manager == undefined ||  category_manager == "0"){
           
           toast_long("해당카테고리의 거래처 담당자를 선택해주세요.");
           return;
       }




       var selected_date = $this_tr.find("td.td_smi_sdate ").attr("sdate");
       var $tr = $this_tr.clone("true");
       $tr.removeClass("first_tr");
       $tr.removeAttr("sm_idx");

       if(selected_date != undefined && selected_date != "" && selected_date != "미지정" ){
            $tr.find("td.td_smi_sdate").html(selected_date+"일");
       }else{
        $tr.find("td.td_smi_sdate").html("");
       }

       $tr.find("td.td_smi_manager_name").attr("manager_idx","");
       $tr.find("select.category_manager option[value='"+category_manager+"']").remove();
       $tr.find("select.category_manager").find("option[value='신규입력']").remove();

       $tr.find("select.category_manager").find("option").last().before("<option value='신규입력'>신규입력</option>");

       $tr.find("input.smi_email").val("");

       $tr.find(".td_smi_btn").html('<button class="btn btn-sm btn-dark btn_del_tr">삭제</button>');

       $this_tr.closest("table").find("tr[category1_idx='"+category1_idx+"']").last().after($tr);

       reset_smi_num();


    });




    
    $(document).on("click",".btn_add_client",function(){
        window.location.href="?page=sangjo/client/input";
    });


    $(document).on("click",".btn_add_place",function(){

        var $place_table = $("#place_sample").find(".table_place_info").clone(true);
        $place_table.find("td.place_setting").html("<button class='btn btn-success btn-xs btn_save_place'>저장</button><br><button class='btn btn-danger btn-xs btn_cancel_place'>취소</button>");
        $("div.x_panel_place_info").after($place_table);

    });

    $(document).on("click",".btn_cancel_place",function(){
        reset_place($(this));
    });



    $(document).on("click",".btn_save_place",function(){

        var $table = $(this).closest("table");
        var client_place_idx = $table.attr("client_place_idx");
        if(client_place_idx == undefined || client_place_idx == ""){
            var mode = "insert";
        }else{
            var mode = "edit";
        }

        save_place($table,mode);

   });

   
   $(document).on("click",".place_del",function(){
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
                    save_place($table,"del");
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });

    });

       
   $(document).on("click",".place_edit",function(){


        var $table = $(this).closest("table");

        var client_place_idx    = $table.attr("client_place_idx");
        var place_name    = $table.find("td.place_name").attr("old_value");
        var receiver_name = $table.find("td.receiver_name").attr("old_value");
        var receiver_hp   = $table.find("td.receiver_hp").attr("old_value");
        var addr          = $table.find("td.addr").attr("old_value");
        var memo          = $table.find("td.memo").attr("old_value");



        var $new_table = $("#place_sample").find("table").clone(true);
        $new_table.find("td.place_setting").html("<button class='btn btn-success btn-xs btn_save_place'>저장</button><br><button class='btn btn-danger btn-xs btn_cancel_place'>취소</button>");

        $new_table.addClass("table_list");
        $new_table.attr("client_place_idx",client_place_idx);
        $new_table.find("input[name='place_name']").val(place_name);
        $new_table.find("input[name='receiver_name']").val(receiver_name);
        $new_table.find("input[name='receiver_hp']").val(receiver_hp);
        $new_table.find("input[name='addr']").val(addr);
        $new_table.find("input[name='memo']").val(memo);


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
        $tr.find("td.client_price_tax").html('<input type="text" name="client_price_tax"  value="'+client_price_tax+'" class="form-control col-xs-12">');
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
            var client_price_tax = Math.round(client_price/10);
            var client_price_sum = client_price + client_price_tax;

            var $tr = $("table.table_product_info tr.new_tr");
            $tr.find("input[name='client_price']").val(client_price);
            $tr.find("input[name='client_price_tax']").val(client_price_tax);
            $tr.find("input[name='client_price_sum']").val(client_price_sum);
        }



    });



    $(document).on("keyup","input[name='client_price']",function(){
        var client_price      = parseInt($(this).val());
        var client_price_tax = Math.round(client_price/10);
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
        var client_price = Math.round(client_price_sum/1.1);
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



        var url = "./contents/sangjo/client/api/updateCell.php";
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

        var url = "./contents/sangjo/client/api/insertMemo.php";
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

        var url = "./contents/sangjo/client/api/updateCell.php";
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


        var url = "./contents/sangjo/client/api/getInfo.php";
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
        var url = "./contents/sangjo/client/api/getInfo.php";
        var consulting_idx = $(this).closest("tr").attr("consulting_idx");

        // consulting_idx 값 확인
        if(!consulting_idx || consulting_idx === "") {
            alert("상담 정보를 찾을 수 없습니다. consulting_idx가 없습니다.");
            return;
        }

        var str = "consulting_idx="+consulting_idx; //

        console.log("API 호출: " + url);
        console.log("파라미터: " + str);

        $.ajax({ 
                type: "POST",
                url: url,
                data: str,
                cache: false,
                dataType: "json", 
                beforeSend: function() {
                    // 로딩 표시
                    console.log("API 호출 시작");
                },
                context: this,
                success: function(result){
                    console.log("API 응답 성공:", result);
                    
                    var result_status = result.status;
                    if(result_status == 1) {
                        view_detail_section("mode2", result);
                    } else {
                        var msg = result.message || "오류가 발생했습니다.";
                        window.alert(msg);
                    }
                }, //success
                error : function(jqXHR, textStatus, errorThrown) {
                    console.error("API 오류:", jqXHR.status, textStatus, errorThrown);
                    console.error("응답 텍스트:", jqXHR.responseText);
                    
                    alert("오류가 발생했습니다.\n" + 
                          "상태: " + jqXHR.status + "\n" +
                          "메시지: " + textStatus + "\n" + 
                          "상세 정보: " + errorThrown);
                    
                    // 디버깅 정보 표시
                    alert("jqXHR.status: " + jqXHR.status + "\n" +
                          "jqXHR.statusText: " + jqXHR.statusText + "\n" + 
                          "jqXHR.responseText: " + jqXHR.responseText + "\n" + 
                          "jqXHR.readyState: " + jqXHR.readyState);
                }
        }); //ajax

    });




});








function view_detail_section(mode,result){
    console.log("view_detail_section 호출됨, 모드:", mode);
    
    try {
        // 데이터 유효성 검사
        if (!result || !result.data || !result.data.company_info) {
            console.error("결과 데이터가 유효하지 않습니다:", result);
            alert("데이터를 불러오는 중 오류가 발생했습니다.");
            return;
        }
        
        var company_info = result.data.company_info;
        console.log("회사 정보:", company_info);

        $("#detail_section").find(".company_name").html(company_info.company_name || "이름 없음");
        $("#detail_section").find(".company_name").attr("consulting_idx", company_info.consulting_idx);
        $(".link_manager").attr("href", "?page=consulting/manager_list/list&consulting_idx=" + company_info.consulting_idx);

        if(mode == "mode1"){
            $("table.table_company_info").removeClass("hide").show();
            $("table.table_manager_info").removeClass("hide").show();
            $("div.x_panel_memo_history ").removeClass("hide").show();
            $("div.x_panel_attachment").removeClass("hide").show();



            $("div.x_panel_product_info").removeClass("hide").hide();
            $("table.table_product_info").removeClass("hide").hide();

            $("div.x_panel_place_info").removeClass("hide").hide();
            $("table.table_place_info").removeClass("hide").hide();



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
            $("div.x_panel_memo_history ").removeClass("hide").hide();
            $("div.x_panel_attachment").removeClass("hide").hide();


            $("div.x_panel_product_info").removeClass("hide").show();
            $("table.table_product_info").removeClass("hide").show();

            $("div.x_panel_place_info").removeClass("hide").show();
            $("table.table_place_info").removeClass("hide").show();

            
            $(".table_product_info").find("tbody").empty();
            var client_product_list = result.data.client_product_list;
            for(var i=0;i<client_product_list.length;i++){
        
                var $product_tr = $("#client_product_sample").find("tr").clone(true);
                $product_tr.attr("client_product_idx", client_product_list[i].client_product_idx);
                $product_tr.find("td.product_name").text(client_product_list[i].product_name).attr("old_value",client_product_list[i].product_name);
                $product_tr.find("td.client_price").text(client_product_list[i].client_price).attr("old_value",client_product_list[i].client_price);
                $product_tr.find("td.client_price_tax").text(client_product_list[i].client_price_tax).attr("old_value",client_product_list[i].client_price_tax);
                $product_tr.find("td.client_price_sum").text(client_product_list[i].client_price_sum).attr("old_value",client_product_list[i].client_price_sum);


                $(".table_product_info").find("tbody").append($product_tr);
            }


            $("#detail_section").find("table.table_list").remove();
            var place_list = result.data.place_list;
            for(var i=0;i<place_list.length;i++){
        
                var $place_table = $("#place_sample").find("table").clone(true).addClass("table_list");
                    $place_table.find("input").remove();
                    $place_table.attr("client_place_idx", place_list[i].client_place_idx);

                    $place_table.find("td.place_name").text(place_list[i].place_name).attr("old_value",place_list[i].place_name);
                    $place_table.find("td.receiver_name").text(place_list[i].receiver_name).attr("old_value",place_list[i].receiver_name);
                    $place_table.find("td.receiver_hp").text(place_list[i].receiver_hp).attr("old_value",place_list[i].receiver_hp);
                    $place_table.find("td.addr").text(place_list[i].addr).attr("old_value",place_list[i].addr);
                    $place_table.find("td.memo").text(place_list[i].memo).attr("old_value",place_list[i].memo);


                $("div.x_panel_place_info").after($place_table);
            }



            var manager_list = result.data.manager_list;

            var manager_html = "<select name='category_manager' class='form-control category_manager'>";
            manager_html += "<option value='0'>-- 선택 --</option>";
            for(var i=0;i<manager_list.length;i++){
                manager_html += "<option value='"+manager_list[i]['manager_idx']+"'  email='"+checkNull(manager_list[i]['manager_email'])+"' >"+checkNull(manager_list[i]['manager_name'])+" "+checkNull(manager_list[i]['manager_position'])+"</option>";          

            }
            //manager_html += "<option value='신규입력'>신규입력</option>";

            manager_html += "<option value='0'>-- 선택해제 --</option></select>";

        
            var old_category1_idx = "";
            var parent_sdate = "";

            $("#table_settlement_sdate_info tbody").empty();
            var category1_manager_list = result.data.category1_manager_list;
            for(var i=0;i<category1_manager_list.length;i++){

        
                var $tr = $("#category_manager_sample").find("table tbody tr").clone(true);


                if(old_category1_idx != category1_manager_list[i]['category1_idx']){
                    $tr.addClass("parent_tr");
                    old_category1_idx = category1_manager_list[i]['category1_idx'];
                    $tr.find("select[name='sdate']").val(category1_manager_list[i]['sdate']);
                    if(category1_manager_list[i]['sdate'] != null){
                        parent_sdate = category1_manager_list[i]['sdate'];
                    }else{
                        parent_sdate = null;
                    }
                    //console.log(category1_manager_list[i]['category1_name'] +":"+category1_manager_list[i]['sdate']);

                }else{
                    $tr.removeClass("parent_tr");
                    if(parent_sdate == null){
                        $tr.find(".td_smi_sdate").html("");
                    }else{
                        $tr.find(".td_smi_sdate").html(parent_sdate + "일");
                    }

                    $tr.find("td.td_smi_btn").html("");

                }



                $tr.attr("category1_idx",category1_manager_list[i]['category1_idx']);

                if(category1_manager_list[i]['fs_idx'] != null){
                    $tr.attr("fs_idx",category1_manager_list[i]['fs_idx']);
                }

                if(category1_manager_list[i]['sm_idx'] != null){
                    $tr.attr("sm_idx",category1_manager_list[i]['sm_idx']);
                }


                $tr.find(".td_smi_num").html(i+1);
                $tr.find(".td_smi_category1_name").html(category1_manager_list[i]['category1_name']).attr("category1_idx",category1_manager_list[i]['category1_idx']);
                $tr.find(".td_smi_sdate").attr("sdate",category1_manager_list[i]['sdate']);


                $tr.find(".td_smi_manager_name").html(manager_html).attr("manager_idx",category1_manager_list[i]['manager_idx']);
                $tr.find(".smi_email").val(category1_manager_list[i]['manager_email']);
                if(category1_manager_list[i]['manager_idx'] != undefined){
                    $tr.find("select[name='category_manager']").val(category1_manager_list[i]['manager_idx']);
                }

                

                $("#table_settlement_sdate_info tbody").append($tr);





            }








        }


        $("#detail_section").removeClass("hide").show();
    } catch (error) {
        console.error("view_detail_section 오류:", error);
        alert("데이터를 표시하는 중 오류가 발생했습니다: " + error.message);
    }
}



//회사 정보 삭제
function deleteCompany(consulting_idx){

    var url = "./contents/sangjo/client/api/updateCell.php";

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

    var url = "./contents/sangjo/client/api/insertMemo.php";

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

    var url = "./contents/sangjo/client/api/uploadFile.php";


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
    var url = "./contents/sangjo/client/api/uploadFile_del.php";
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



function save_place($table,mode){

    var place_name = $table.find("input[name='place_name']").val();
    var receiver_name = $table.find("input[name='receiver_name']").val();
    var receiver_hp = $table.find("input[name='receiver_hp']").val();
    var addr = $table.find("input[name='addr']").val();
    var memo = $table.find("input[name='memo']").val();

    
    var url = "./contents/sangjo/client/api/saveClientPlace.php";

    var str = "mode="+mode; 

    if(mode == "insert"){
        var consulting_idx = $("table.table_company_key_info").find("th.company_name").attr("consulting_idx");
        str += "&consulting_idx="+consulting_idx;
    }

    if(mode == "edit" || mode == "del"){
        var client_place_idx = $table.attr("client_place_idx");
        str += "&client_place_idx="+client_place_idx;
    }

    if(mode == "insert" || mode == "edit"){
        str += "&place_name="+encodeURIComponent(place_name);
        str += "&receiver_name="+encodeURIComponent(receiver_name);
        str += "&receiver_hp="+encodeURIComponent(receiver_hp);
        str += "&addr="+encodeURIComponent(addr);
        str += "&memo="+encodeURIComponent(memo);
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
                        var place_info = result.data.place_info;

                        $table.addClass('table_list');
                        $table.attr("client_place_idx",place_info.client_place_idx);
                        $table.find("td.place_name").html(place_info.place_name).attr("old_value",place_name);
                        $table.find("td.receiver_name").html(place_info.receiver_name).attr("old_value",receiver_name);
                        $table.find("td.receiver_hp").html(place_info.receiver_hp).attr("old_value",receiver_hp);
                        $table.find("td.addr").html(place_info.addr).attr("old_value",addr);
                        $table.find("td.memo").html(place_info.memo).attr("old_value",memo);
                        var setting_icon = $("#place_sample").find("td.place_setting").html();
                        $table.find("td.place_setting").html(setting_icon);
                    }else if(mode == "edit"){
                        var place_info = result.data.place_info;

                        $table.find("td.place_name").html(place_info.place_name).attr("old_value",place_name);
                        $table.find("td.receiver_name").html(place_info.receiver_name).attr("old_value",receiver_name);
                        $table.find("td.receiver_hp").html(place_info.receiver_hp).attr("old_value",receiver_hp);
                        $table.find("td.addr").html(place_info.addr).attr("old_value",addr);
                        $table.find("td.memo").html(place_info.memo).attr("old_value",memo);
                        var setting_icon = $("#place_sample").find("td.place_setting").html();
                        $table.find("td.place_setting").html(setting_icon);
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


function reset_place($obj){


    var $table = $obj.closest("table");
    var client_place_idx = $table.attr("client_place_idx");
    if(client_place_idx == undefined){
        $table.remove();
    }else{
        var place_name    = $table.find("td.place_name").attr("old_value");
        var receiver_name = $table.find("td.receiver_name").attr("old_value");
        var receiver_hp   = $table.find("td.receiver_hp").attr("old_value");
        var addr          = $table.find("td.addr").attr("old_value");
        var memo          = $table.find("td.memo").attr("old_value");

        $table.find("td.place_name").html(place_name);
        $table.find("td.receiver_name").html(receiver_name);
        $table.find("td.receiver_hp").html(receiver_hp);
        $table.find("td.addr").html(addr);
        $table.find("td.memo").html(memo);
        var setting_icon = $("#place_sample").find("td.place_setting").html();
        $table.find("td.place_setting").html(setting_icon);
    }


}

function save_product($obj,mode){

    $tr = $obj.closest('tr');
    
    var product_idx      = $tr.find("select[name='product_idx'] option:selected").val();
    var client_price     = $tr.find("input[name='client_price']").val();
    var client_price_tax = $tr.find("input[name='client_price_tax']").val();
    var client_price_sum = $tr.find("input[name='client_price_sum']").val();

    
    var url = "./contents/sangjo/client/api/saveClientProduct.php";

    var str = "mode="+mode; 

    if(mode == "insert"){
        var consulting_idx = $("table.table_company_key_info").find("th.company_name").attr("consulting_idx");
        str += "&consulting_idx="+consulting_idx;
    }

    if(mode == "edit" || mode == "del"){
        var client_product_idx = $tr.attr("client_product_idx");
        str += "&client_product_idx="+client_product_idx;
    }

    if(mode == "insert" || mode == "edit"){
        str += "&product_idx="+product_idx;
        str += "&client_price="+client_price;
        str += "&client_price_tax="+client_price_tax;
        str += "&client_price_sum="+client_price_sum;
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


function saveCategoryManager($obj,mode){
        var $tr = $obj.closest("tr");

        var fs_idx = $tr.attr("fs_idx");
        var sm_idx = $tr.attr("sm_idx");

        var category1_idx = $tr.attr("category1_idx");
        var consulting_idx = $(".table_company_key_info").find("th.company_name").attr("consulting_idx");


        if(mode == "manager_set"){
            var old_manager_idx = $tr.find("td.td_smi_manager_name").attr("manager_idx");
            var new_manager_idx = $obj.val();

    
    
            if(old_manager_idx == new_manager_idx){
                return;
            }
    
            if(old_manager_idx == "0" || old_manager_idx == "" || old_manager_idx == undefined){
                if(new_manager_idx == "0"){
    
                    return;
                }
            }
    

            var url = "./contents/sangjo/client/api/saveCategoryManager.php";
            var str = "mode=manager_set&sm_idx="+sm_idx+"&new_manager_idx="+new_manager_idx+"&category1_idx="+category1_idx+"&consulting_idx="+consulting_idx; 


            console.log(str);
            //return;
        

        }else if(mode == "manager_new"){
            var manager_name = $tr.find("input.category_manager_new").val();
            var manager_email = $tr.find("input.smi_email").val();
            if(manager_name == "" && manager_email == ""){
                
                toast("담당자 이름과 이메일을 입력해주세요");
                return;
            }


            var url = "./contents/sangjo/client/api/saveCategoryManager.php";
            var str = "mode=manager_new&category1_idx="+category1_idx+"&consulting_idx="+consulting_idx+"&manager_name="+manager_name+"&manager_email="+manager_email; 
        


        }else if(mode == "sdate"){
            var old_sdate = $tr.find("td.td_smi_sdate").attr("sdate");
            var new_sdate = $obj.val();
    
    
            if(old_sdate == new_sdate){
                return;
            }
    
            if(old_sdate == "0" || old_sdate == "" || old_sdate == undefined || old_sdate == "미지정"  ){
                if(new_sdate == "미지정"){
    
                    return;
                }
            }
    
            var category1_idx = $tr.attr("category1_idx");


            $(".table_settlement_sdate_info").find("tr[category1_idx='"+category1_idx+"']").find("select.sdate > option[value='"+new_sdate+"']").attr("selected","true");

            var consulting_idx = $(".table_company_key_info").find("th.company_name").attr("consulting_idx");
    
    
    
            var url = "./contents/sangjo/client/api/saveCategoryManager.php";
        
            var str = "mode=sdate&fs_idx="+fs_idx+"&new_sdate="+new_sdate+"&category1_idx="+category1_idx+"&consulting_idx="+consulting_idx; 
        
        }else if(mode == "del"){
            //
        }


    //console.log(str);

    $.ajax( { 
        type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
        success: function(result){
            
            var result_status = result.status;
            if(result_status == 1)
            {
                toast(result.msg);
                

                if(mode == "manager_set"){
                    if(result.data.type == "11"){ //담당자 미선택으로 변경성공, delete from sangjo_new.settlement_manager
                        $tr.removeAttr("sm_idx");
                        $obj.closest("td").removeAttr("manager_idx");

                        $tr.find("select.category_manager").val("0");
                        $tr.find("input.smi_email").val("");

                    }else if(result.data.type == "12"){ //기존담당자가 있고 다른 담당자 선택으로 변경성공
                        $tr.find("input.smi_email").val($obj.find("option[value='"+new_manager_idx+"']").attr("email"));
                        $obj.closest("td").attr("manager_idx",new_manager_idx);

                    }else if(result.data.type == "13"){ //매니저 신규매칭. 기존에 매칭매니저 없었음
                        $tr.find("input.smi_email").val($obj.find("option[value='"+new_manager_idx+"']").attr("email"));
                        $obj.closest("td").attr("manager_idx",new_manager_idx);
                        $tr.attr("sm_idx",result.data.sm_idx);
                    }

                    if(new_manager_idx == "0"){
                        if(!$tr.hasClass("parent_tr")){
                            $tr.find("td.td_smi_btn").html('<button class="btn btn-sm btn-dark btn_del_tr">삭제</button>');
                        }

                    }else{
                        if(!$tr.hasClass("parent_tr")){
                            $tr.find("td.td_smi_btn").html("");
                        }
                    }


                }else if(mode == "manager_new"){
                    $tr.find("input.smi_email").prop("readonly", true);
                    $obj.closest("td").attr("manager_idx",result.data.manager_idx);
                    $tr.attr("sm_idx",result.data.sm_idx);


                    var manager_list = result.data.manager_list;

                    var manager_html = "<select name='category_manager' class='form-control category_manager'>";
                    manager_html += "<option value='0'>-- 선택 --</option>";
                    for(var i=0;i<manager_list.length;i++){
                        manager_html += "<option value='"+manager_list[i]['manager_idx']+"'  email='"+manager_list[i]['manager_email']+"' >"+manager_list[i]['manager_name']+" "+manager_list[i]['manager_position']+"</option>";          

                    }
                    manager_html += "<option value='신규입력'>신규입력</option>";

                    manager_html += "<option value='0'>-- 선택해제--</option></select>";


                    $tr.find("td.td_smi_manager_name").html(manager_html);

                    if($tr.hasClass("parent_tr")){
                        $tr.find("td.td_smi_btn").html('<button class="btn btn-sm btn-primary btn_add_category_manger">추가</button>');
                    }else{
                        $tr.find("td.td_smi_btn").html("");

                    }
                    
                }else if(mode == "sdate"){
                    //
                }
        

                reset_smi_num();

            }else{
                var msg = result.msg;
                window.alert(msg);
                if(mode == "manager_set"){
                    if(old_manager_idx != undefined){
                        $obj.val(old_manager_idx);

                    }else{
                        console.log("여기");
                        $obj.val("0");

                    }
                }

            }
        }, //success
        error : function( jqXHR, textStatus, errorThrown ) {
            alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
            if(old_manager_idx != undefined){
                $obj.val(old_manager_idx);

            }else{
                console.log("여기");
                $obj.val("0");

            }
        }
    }); //ajax

}





function reset_smi_num(){
    $('tbody td.td_smi_num').each(function(index) {
        $(this).text(index + 1);
    });
}