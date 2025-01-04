
//var categoryList = {}; //배열이 아닌 객체로 선언





// $(function() {  //상세기업정보에서 백버튼 눌렀을때 목록보기가 되게 하기 위해서.
//     if (window.history && window.history.pushState) {
//         //window.history.pushState('', null, './');
//         $(window).on('popstate', function() {
//             // alert('Back button was pressed.');
        
//             $("#main_list").show();
//             $("#detail_section").hide();
        

//         });
//     }
// });



$(document).ready(function(){





    //$("#detail_section").hide();

    $(document).on("keyup","#type_filter",function(e){

        if(e.keyCode == 13){
            var txt = $(this).val();

            $("ul.ul_memo_product_history").find(".memo_li").hide().filter(function() {
                return $(this).find("span.memo_status:contains('"+txt+"')").length==1;
                
            }).show();
        }else{
            var txt = $(this).val();

            if(txt == ""){
                $("ul.ul_memo_product_history").find(".memo_li").show();
            }
        }




    });



    
    /*
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

    */


    $(document).on("click",".btn_manager_plus",function(){


       //var $tr = $("#table_manager tbody").find("tr").first().clone(true);
       var $tr = $("#manager_info_sample table").find("tr").clone(true);

       //var max_num = $("#table_manager tbody").find("tr").last().find("th").text();
       var max_num = $("#table_manager tbody").find("tr").find("th").length;


       var next_num = parseInt(max_num)+1;

       $tr.removeAttr("manager_idx");
       $tr.find("input").val("");
       $tr.find("th").text(next_num);
       $("#table_manager tbody").append($tr);

    });




    $(document).on("click",".company_edit",function(){

        var consulting_idx = $("table.table_company_key_info .company_name").attr("consulting_idx");

        var this_url = window.location.href;
        if(this_url.includes('dashboard_cnst')){
            window.location.href='dashboard_cnst.php?page=consulting/input&consulting_idx='+consulting_idx;

        }else{
            window.location.href='dashboard_sffm.php?page=consulting/input&consulting_idx='+consulting_idx;

        }


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

        var $xp = $(this).closest(".x_panel_memo_history");

        var old_memo_idx = $xp.find("#memo_add_section").attr('memo_idx');
        if(old_memo_idx != undefined){
            $("li.memo_li[memo_idx='"+old_memo_idx+"']").show();
        }




        var $this_li    = $(this).closest("li.memo_li");

        var memo_idx    = $this_li.attr("memo_idx");
        var mdate       = $this_li.find("h2 .mdate").attr('mdate');
        var memo_title  = $this_li.find("h2 .atitle").text();
        var memo_text   = $this_li.find(".excerpt").html();
        var memo_status = $this_li.find(".memo_status").text();



        $xp.find("#memo_add_section").removeClass("hide").show();

        console.log("mdate:"+mdate);
        $xp.find("input.mdate").val(mdate);

        $xp.find("#memo_title").val(memo_title);


        $xp.find("#memo_add_section .memo_status").html(memo_status);

        $("#product_type option").filter(function() {
          //may want to use $.trim in here
          return $(this).text() == memo_status;
        }).prop('selected', true);

        $xp.find("#editor").html(memo_text);
        $xp.find("#memo_add_section").attr("memo_idx",memo_idx);
        $xp.find(".btn_save_history").text("수정하기");


        $this_li.before($xp.find("#memo_add_section"));
        $this_li.hide();


    });

    $(document).on("click",".memo_del",function(){

        var memo_idx = $(this).closest("li.memo_li").attr("memo_idx");
        var $btn = $(this);


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
                    deleteMemo($btn,memo_idx);
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
        if(memo_status == "TM"){
            var next_memo_status = "전화상담";
            var next_tag = "tag_green";
        }else if(memo_status == "전화상담"){
            var next_memo_status = "미팅";
            var next_tag = "tag_blue";
        }else if(memo_status == "미팅"){
            var next_memo_status = "메일";
            var next_tag = "tag_primary";

        }else if(memo_status == "메일" || memo_status == "" || memo_status == undefined){
            var next_memo_status = "TM";
            var next_tag = "tag_warning";

        }
        $(this).parent().removeClass("tag_warning");

        $(this).parent().removeClass("tag_green");
        $(this).parent().removeClass("tag_blue");
        $(this).parent().removeClass("tag_primary");

        $(this).parent().addClass(next_tag);
        $(this).text(next_memo_status);

    });





    $(document).on("click",".btn_add_history,.btn_add_product_history",function(){
        var $xp = $(this).closest(".x_panel_memo_history");
        var old_memo_idx = $xp.find("#memo_add_section").attr('memo_idx');
        if(old_memo_idx != undefined){
            $("li.memo_li[memo_idx='"+old_memo_idx+"']").show();
        }

        $xp.find("#memo_add_section").removeClass("hide");
        $xp.find("#memo_add_section").show();
        $xp.find(".btn_save_history").text("저장하기");
        $xp.find("#memo_add_section").removeAttr("memo_idx");
        $xp.find("#memo_title").val("");
        $xp.find("#editor").html("");


        $xp.find("ul.ul_memo_history").prepend($xp.find("#memo_add_section"));



    });
    $(document).on("click",".btn_cancel_history,.btn_cancel_product_history",function(){

        var $xp = $(this).closest(".x_panel_memo_history");

        $xp.find("#memo_add_section").hide();

        $xp.find("#memo_title").val("");
        $xp.find("#descr").val("");
        $xp.find("#editor").html("");

        var memo_idx = $xp.find("#memo_add_section").attr('memo_idx');
        if(memo_idx != undefined){
            $("li.memo_li[memo_idx='"+memo_idx+"']").show();
        }


    });



    $(document).on("click","#attatchTab li",function(){

          var title = $(this).find("a").text();
          if(title == "전체"){
            $("#table_attach_list").find("tbody").find("tr").show();
          }else{
            $("#table_attach_list").find("tbody").find("tr").hide();
            $("#table_attach_list").find("tbody").find("tr[part='"+title+"']").show();

          }


    });





    




    $(document).on("click","td.td_x",function(){

        var $this = $(this);

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
                    delete_attach($this);
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });
       

    });






    $(document).on("click","li.memo_li span.memo_status",function(){
        var memo_status = $(this).text();
        if(memo_status == "TM"){
            var next_memo_status = "전화상담";
            var next_tag = "tag_green";
        }else if(memo_status == "전화상담"){
            var next_memo_status = "미팅";
            var next_tag = "tag_blue";
        }else if(memo_status == "미팅"){
            var next_memo_status = "메일";
            var next_tag = "tag_primary";

        }else if(memo_status == "메일" || memo_status == "" || memo_status == undefined){
            var next_memo_status = "TM";
            var next_tag = "tag_warning";

        }

        $(this).parent().removeClass("tag_warning");
        $(this).parent().removeClass("tag_green");
        $(this).parent().removeClass("tag_blue");
        $(this).parent().removeClass("tag_primary");

        $(this).parent().addClass(next_tag);
        $(this).text(next_memo_status);



        var url = "./contents/consulting/api/updateCell.php";
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


    $(document).on("change","#product_type",function(){
        var product_type = $("#product_type option:checked").text();
        $(this).closest(".tab-pane").find("#memo_add_section").find(".memo_status").html(product_type);

    });



    $(document).on("click",".btn_save_history,.btn_save_product_history",function(){

        var part = $(this).closest(".tab-pane").attr("part");

        var $xp = $(this).closest(".x_panel_memo_history");


        var url = "./contents/consulting/api/insertMemo.php";
        var title = $xp.find("#memo_title").val();
        var memo = encodeURIComponent($xp.find("#editor").html());
        var memo_status = $xp.find("#memo_add_section").find(".memo_status").text();
        var consulting_idx = $("#detail_section").find(".company_name").attr("consulting_idx");
        
        var mdate;
        if(part == "memo_product"){
           mdate = $("input[name='date1']").val();
        }else if(part == "memo"){
            mdate = $("input[name='date2']").val();

        }

        var memo_idx =  parseInt($xp.find("#memo_add_section").attr("memo_idx"));
        if(memo_idx > 0){ //수정
            var str = "mode=edit&part="+part+"&mdate="+mdate+"&memo_idx="+memo_idx+"&consulting_idx="+consulting_idx+"&memo_status="+memo_status+"&memo_title="+title+"&memo_text="+memo; //

        }else{ //추가
            var str = "mode=add&part="+part+"&mdate="+mdate+"&consulting_idx="+consulting_idx+"&memo_status="+memo_status+"&memo_title="+title+"&memo_text="+memo; //

        }



        console.log(str);

        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        toast(result.msg);
                        $xp.find("#memo_add_section").hide();


                        var memo = result.data.memo;

                        if(memo_idx > 0){ //수정
                            var $memo_li = $xp.find("li.memo_li[memo_idx='"+memo_idx+"']");
                            //$memo_li.attr("memo_idx",memo.memo_idx);
                            $memo_li.find(".tag_title").html(memo.memo_status);
                            $memo_li.find(".tag").removeClass("tag_warning");
                            $memo_li.find(".tag").removeClass("tag_green");
                            $memo_li.find(".tag").removeClass("tag_blue");
                            $memo_li.find(".tag").removeClass("tag_primary");
                            if(memo.memo_status == "TM"){
                                $memo_li.find(".tag").addClass("tag_warning");
                            }else if(memo.memo_status == "전화상담"){
                                $memo_li.find(".tag").addClass("tag_green");
                            }else if(memo.memo_status == "미팅"){
                                $memo_li.find(".tag").addClass("tag_blue");
                            }else if(memo.memo_status == "메일"){
                                $memo_li.find(".tag").addClass("tag_primary");
                            }


                            $memo_li.find("h2 .mdate").html(memo.md).attr("mdate",memo.mdate);

                            $memo_li.find("h2 .atitle").html(memo.memo_title);
                            $memo_li.find(".update_datetime").html(memo.utime);
                            $memo_li.find(".awriter").html(memo.admin_name);
    
                            $memo_li.find(".excerpt").html(memo.memo_text);
                            $memo_li.show();


                            //$memo_li.find(".excerpt").append("<br><a class='text_more'>Read&nbsp;More</a>");
    

    
                        }else{ //추가

                            var $memo_li = $("#memo_sample").find("li.memo_li").clone(true);
                            $memo_li.attr("memo_idx",memo.memo_idx);
                            $memo_li.find(".tag_title").html(memo.memo_status);
                            if(memo.memo_status == "TM"){
                                $memo_li.find(".tag").addClass("tag_warning");
                            }else if(memo.memo_status == "전화상담"){
                                $memo_li.find(".tag").addClass("tag_green");
                            }else if(memo.memo_status == "미팅"){
                                $memo_li.find(".tag").addClass("tag_blue");
                            }else if(memo.memo_status == "메일"){
                                $memo_li.find(".tag").addClass("tag_primary");
                            }
                            $memo_li.find("h2 .mdate").html(memo.md).attr("mdate",memo.mdate);

                            $memo_li.find("h2 .atitle").html(memo.memo_title);
                            $memo_li.find(".update_datetime").html(memo.utime);
                            $memo_li.find(".awriter").html(memo.admin_name);
    
                            $memo_li.find(".excerpt").html(memo.memo_text);
                            //$memo_li.find(".excerpt").append("<br><a class='text_more'>Read&nbsp;More</a>");
    
                            $xp.find("#memo_add_section").after($memo_li);
    
    
                        }

                        //초기화
                        $xp.find("#memo_title").val("");
                        $xp.find("#editor").html("");



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



    $(document).on("click","div.btn-group[col_name='consulting_status'] ul.dropdown-menu li a",function(e){

        var url = "./contents/consulting/api/updateCell.php";
        var consulting_status = $(this).text();
        var consulting_idx = $(this).closest("tr").attr("consulting_idx");

        var str = "mode=consulting_status&consulting_status="+consulting_status+"&consulting_idx="+consulting_idx; //

        console.log(str);


        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        toast(result.msg);
                        $(this).closest("td").attr("consulting_status",consulting_status);
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

        



    $(document).on("click","#datatable-main td.company_name_old",function(){

        

        var url = "./contents/consulting/api/getInfo.php";
        var consulting_idx = $(this).closest("tr").attr("consulting_idx");


        var str = "consulting_idx="+consulting_idx; //



        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){

                    console.log(result);
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {


                        $("#main_list").hide();

                        //toast(result.msg);
                        var company_info = result.data.company_info;
                        $("#detail_section").find(".company_name").html(company_info.company_name);
                        $("#detail_section").find(".company_name").attr("consulting_idx",company_info.consulting_idx);

                        $("#detail_section").find(".consulting_status").html(company_info.consulting_status);

                        $("#detail_section").find(".employees").html(company_info.employees);
                        $("#detail_section").find(".employment_fee").html(company_info.employment_fee);
                        $("#detail_section").find(".trading_items").html(company_info.trading_items);
                        $("#detail_section").find(".tradeable_items").html(company_info.tradeable_items);

                        $("#detail_section").find(".biz_num").html(company_info.biz_num);
                        $("#detail_section").find(".biz_type").html(company_info.biz_type);
                        $("#detail_section").find(".corp_num").html(company_info.corp_num);
                        $("#detail_section").find(".biz_part").html(company_info.biz_part);
                        $("#detail_section").find(".ceo_name").html(company_info.ceo_name);
                        $("#detail_section").find(".address").html(company_info.address);
                        $("#detail_section").find(".tel").html(company_info.tel);
                        $("#detail_section").find(".homepage").html(company_info.homepage);
                        $("#detail_section").find(".fax").html(company_info.fax);
                        $("#detail_section").find(".memo").html(nl2br(company_info.memo));


                        // $("#detail_section").find(".settlement_period").html(company_info.settlement_period);
                        // $("#detail_section").find(".settlement_date").html(company_info.settlement_date);
                        // $("#detail_section").find(".settlement_memo").html(company_info.settlement_memo);
                        $("#detail_section").find(".payment_date").html(company_info.payment_date);




                        $("#detail_section").find(".meeting").html(company_info.meeting);
                        $("#detail_section").find(".meeting_person").html(company_info.meeting_person);

                        // $("#detail_section").find(".manager_name").html(company_info.manager_name);
                        // $("#detail_section").find(".manager_email").html(company_info.manager_email);
                        // $("#detail_section").find(".manager_position").html(company_info.manager_position);
                        // $("#detail_section").find(".manager_tel").html(company_info.manager_tel);
                        // $("#detail_section").find(".manager_department").html(company_info.manager_department);
                        // $("#detail_section").find(".manager_hp").html(company_info.manager_hp);




                        $(".table_manager_info").find("tbody").empty();
                        var manager_list = result.data.manager_list;
                        for(var i=0;i<manager_list.length;i++){

                            var $manager_tr = $("#manager_sample").find("tr").clone(true);
                            $manager_tr.attr("manager_idx", manager_list[i].manager_idx);
                            $manager_tr.find("td.manager_id").text(manager_list[i].manager_id);

                            $manager_tr.find("td.item_in_charge").text(manager_list[i].item_in_charge);

                            $manager_tr.find("td.manager_name").text(manager_list[i].manager_name);
                            $manager_tr.find("td.manager_position").text(manager_list[i].manager_position);
                            $manager_tr.find("td.manager_department").text(manager_list[i].manager_department);
                            $manager_tr.find("td.manager_email").text(manager_list[i].manager_email);
                            $manager_tr.find("td.manager_tel").text(manager_list[i].manager_tel);
                            $manager_tr.find("td.manager_hp").text(manager_list[i].manager_hp);
                            $manager_tr.find("td.manager_settlement_date").text(manager_list[i].manager_settlement_date);


                            $(".table_manager_info").find("tbody").append($manager_tr);
                        }


                        //기본 탭 설정
                        $("#myTab").find("li[role='presentation']").removeClass("active").find("a").attr("aria-expanded","false");
                        $("#myTabContent").find("div[role='tabpanel']").removeClass("active").removeClass("in");

                        if(company_info.consulting_status=="계약완료"){
                            $("#myTab").find("li[role='presentation']").eq(1).addClass("active").find("a").attr("aria-expanded","true");
                            $("#tab_content1").addClass("active").addClass("in");
                        }else{
                            $("#myTab").find("li[role='presentation']").eq(0).addClass("active").find("a").attr("aria-expanded","true");
                            $("#tab_content2").addClass("active").addClass("in");

                        }


                        $(".ul_memo_product_history").find("li.memo_li").remove();
                        var memo_product_list = result.data.memo_product_list;
                        for(var i=0;i<memo_product_list.length;i++){
                            var $memo_li = $("#memo_sample").find("li.memo_li").clone(true);
                            $memo_li.attr("memo_idx",memo_product_list[i].memo_idx);
                            $memo_li.find(".tag_title").html(memo_product_list[i].memo_status);

                            // if(memo_product_list[i].memo_status == "전화상담"){
                            //     $memo_li.find(".tag").addClass("tag_green");
                            // }else if(memo_product_list[i].memo_status == "미팅"){
                            //     $memo_li.find(".tag").addClass("tag_blue");
                            // }




                            $memo_li.find("h2 .mdate").html(memo_product_list[i].md).attr('mdate',memo_product_list[i].mdate);

                            $memo_li.find("h2 .atitle").html(memo_product_list[i].memo_title);
                            $memo_li.find(".update_datetime").html(memo_product_list[i].utime);
                            $memo_li.find(".awriter").html(memo_product_list[i].admin_name);

                            $memo_li.find(".excerpt").html(memo_product_list[i].memo_text);
                            //$memo_li.find(".excerpt").append("<br><a class='text_more'>Read&nbsp;More</a>");
                            $(".ul_memo_product_history").append($memo_li);
                        }





                        $(".ul_memo_history").find("li.memo_li").remove();
                        var memo_list = result.data.memo_list;
                        for(var i=0;i<memo_list.length;i++){
                            var $memo_li = $("#memo_sample").find("li.memo_li").clone(true);
                            $memo_li.attr("memo_idx",memo_list[i].memo_idx);
                            $memo_li.find(".tag_title").html(memo_list[i].memo_status);
                            if(memo_list[i].memo_status == "TM"){
                                $memo_li.find(".tag").addClass("tag_warning");
                            }else if(memo_list[i].memo_status == "전화상담"){
                                $memo_li.find(".tag").addClass("tag_green");
                            }else if(memo_list[i].memo_status == "미팅"){
                                $memo_li.find(".tag").addClass("tag_blue");
                            }else if(memo_list[i].memo_status == "메일"){
                                $memo_li.find(".tag").addClass("tag_primary");
                            }

                            $memo_li.find("h2 .mdate").html(memo_list[i].md).attr('mdate',memo_list[i].mdate);
                            $memo_li.find("h2 .atitle").html(memo_list[i].memo_title);
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
                            $attach_tr.attr("part", attachment_list[i].part);

                            $attach_tr.find(".th_num").text(i+1);
                            var filename_ex = attachment_list[i].filename.split("/");

                            var this_lenth = filename_ex.length -1;


                            var fex = filename_ex[this_lenth].split('.');
                            var ext = fex[fex.length-1];


                            if(ext == "JPEG" || ext == "JPG" || ext == "PNG" || ext == "PDF"  ||  ext == "XLSX" || ext == "jpeg" || ext == "jpg" || ext == "png" || ext == "pdf" ||  ext == "xlsx" ){
                                $attach_tr.find(".td_filename a").text(filename_ex[this_lenth]).attr("link",attachment_list[i].filename);
                            }else{
                                $attach_tr.find(".td_filename a").text(filename_ex[this_lenth]).attr("href",attachment_list[i].filename).attr("download","");

                            }



                            $("#table_attach_list").find("tbody").append($attach_tr);
                        }


                        $("#detail_section").removeClass("hide").show();

                        
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





    $(document).on("click",".td_filename a",function () {
        var src = $(this).attr("link");
        if(src != undefined){
            // 현재 페이지의 메인 URL
            var host = $(location).attr("host");
            // 이미지의 전체 링크
            var url = "http://" + host + src;
            // 새 창 짜잔
            window.open(url); 
        }

    });





});



//회사 정보 삭제
function deleteCompany(consulting_idx){

    var url = "./contents/consulting/api/updateCell.php";

    var str = "mode=del&consulting_idx="+consulting_idx; //

    console.log(str);


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);
                    window.location.href='dashboard_cnst.php';
                    
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


    var part = $obj.closest(".tab-pane").attr("part");

    var url = "./contents/consulting/api/insertMemo.php";

    var str = "mode=del&part="+part+"&memo_idx="+memo_idx; //

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



    var part = $("#attatchTab").find("li.active").find("a").text();

    var url = "./contents/consulting/api/uploadFile.php";


    var form = jQuery("ajaxFrom")[0];
    var formData = new FormData(form);
    formData.append("consulting_idx", consulting_idx);
    formData.append("part", part);

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
                $attach_tr.attr("part",attachment.part);

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
    var url = "./contents/consulting/api/uploadFile_del.php";
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



function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

