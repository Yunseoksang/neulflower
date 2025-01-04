$(document).ready(function(){




    var url = "./contents/consulting/api/getInfo.php";
    //var consulting_idx = $(this).closest("tr").attr("consulting_idx");

    var consulting_idx = $("table.table_company_key_info").attr("consulting_idx");

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
                    $("#detail_section").find(".company_name").html(company_info.company_name+"("+company_info.consulting_idx+")");
                    $("#detail_section").find(".company_name").attr("consulting_idx",company_info.consulting_idx);

                    $("#detail_section").find(".consulting_status").html(company_info.consulting_status);

                    $("#detail_section").find(".employees").html(company_info.employees);

                    if(company_info.part == "sp"){
                        $("#detail_section").find(".not_sp").hide();
                    }else{
                        $("#detail_section").find(".unemployed").html(company_info.unemployed);

                        $("#detail_section").find(".employment_fee").html(company_info.employment_fee);
                    }


                    $("#detail_section").find(".trading_items").html(company_info.trading_items);
                    //$("#detail_section").find(".tradeable_items").html(company_info.tradeable_items);
                    $("#detail_section").find(".contract_date").html(company_info.contract_date_ym);

                    $("#detail_section").find(".biz_num").html(company_info.biz_num);
                    $("#detail_section").find(".biz_type").html(company_info.biz_type);
                    $("#detail_section").find(".corp_num").html(company_info.corp_num);
                    $("#detail_section").find(".biz_part").html(company_info.biz_part);
                    $("#detail_section").find(".office_type").html(company_info.office_type);
                    $("#detail_section").find(".head_office").html(company_info.head_office);





                    $("#detail_section").find(".ceo_name").html(company_info.ceo_name);
                    $("#detail_section").find(".address").html(company_info.address);
                    $("#detail_section").find(".tel").html(company_info.tel);
                    $("#detail_section").find(".homepage").html(company_info.homepage);
                    $("#detail_section").find(".fax").html(company_info.fax);

                    if(company_info.memo == null){
                        $("#detail_section").find(".memo").html("");

                    }else{
                        $("#detail_section").find(".memo").html(nl2br(company_info.memo));

                    }


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
                        if(manager_list[i].manager_id == null){manager_list[i].manager_id = "";}

                        if(manager_list[i].item_in_charge == null){manager_list[i].item_in_charge = "";}
                        if(manager_list[i].manager_name == null){manager_list[i].manager_name = "";}
                        if(manager_list[i].manager_position == null){manager_list[i].manager_position = "";}
                        if(manager_list[i].manager_department == null){manager_list[i].manager_department = "";}
                        if(manager_list[i].manager_email == null){manager_list[i].manager_email = "";}
                        if(manager_list[i].manager_tel == null){manager_list[i].manager_tel = "";}
                        if(manager_list[i].manager_hp == null){manager_list[i].manager_hp = "";}
                        if(manager_list[i].manager_settlement_date == null){manager_list[i].manager_settlement_date = "";}


                        var $manager_tr = $("#manager_sample").find("tr").clone(true);
                        $manager_tr.attr("manager_idx", manager_list[i].manager_idx);
                        $manager_tr.find("td.manager_id").text(manager_list[i].manager_id).attr("old_value",manager_list[i].manager_id);

                        $manager_tr.find("td.item_in_charge").text(manager_list[i].item_in_charge).attr("old_value",manager_list[i].item_in_charge);

                        $manager_tr.find("td.manager_name").text(manager_list[i].manager_name).attr("old_value",manager_list[i].manager_name);
                        $manager_tr.find("td.manager_position").text(manager_list[i].manager_position).attr("old_value",manager_list[i].manager_position);
                        $manager_tr.find("td.manager_department").text(manager_list[i].manager_department).attr("old_value",manager_list[i].manager_department);
                        $manager_tr.find("td.manager_email").text(manager_list[i].manager_email).attr("old_value",manager_list[i].manager_email);
                        $manager_tr.find("td.manager_tel").text(manager_list[i].manager_tel).attr("old_value",manager_list[i].manager_tel);
                        $manager_tr.find("td.manager_hp").text(manager_list[i].manager_hp).attr("old_value",manager_list[i].manager_hp);
                        $manager_tr.find("td.manager_settlement_date").text(manager_list[i].manager_settlement_date).attr("old_value",manager_list[i].manager_settlement_date);


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


                        if(ext == "JPEG" || ext == "JPG" || ext == "PNG" || ext == "PDF"  || ext == "jpeg" || ext == "jpg" || ext == "png" || ext == "pdf"){
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












    // 매니저 row 추가
    $(document).on("click",".btn_manager_plus",function(){

        //var $tr = $("#table_manager tbody").find("tr").first().clone(true);
        var $tr = $("#manager_info_sample table").find("tr").clone(true);
        $tr.removeAttr("manager_idx");
        $tr.find("input").val("");
        $("#table_manager_info tbody").append($tr);
        $tr.find(".td_edit .fa-save").removeClass("hide").css("display","block").show();


     });
 
 
 
     // 매니저 삭제
     $(document).on("click","a.manager_del",function(){
 
         var $obj = $(this);
 
 
         var manager_idx = $(this).closest("tr").attr("manager_idx");
         if(manager_idx != undefined && parseInt(manager_idx) > 0){
 
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
                         delete_manager($obj);
                     },
                     "취소": function () {
                         //$.alert('Canceled!');
                     },
 
                 }
             });
         }else{
             $(this).closest("tr").remove();
         }
 
        
 
     });


    // 매니저 수정
    $(document).on("click","a.manager_edit",function(){

        var $tr = $("#manager_info_sample table").find("tr").clone(true);
        $tr.removeAttr("manager_idx");
        $tr.find("input").val("");


        $manager_tr = $(this).closest("tr");


        manager_idx = $manager_tr.attr("manager_idx");
        manager_id = $manager_tr.find("td.manager_id").attr("old_value");
        item_in_charge = $manager_tr.find("td.item_in_charge").attr("old_value");
        manager_name = $manager_tr.find("td.manager_name").attr("old_value");
        manager_position = $manager_tr.find("td.manager_position").attr("old_value");
        manager_department = $manager_tr.find("td.manager_department").attr("old_value");

        manager_email = $manager_tr.find("td.manager_email").attr("old_value");
        manager_tel = $manager_tr.find("td.manager_tel").attr("old_value");
        manager_hp = $manager_tr.find("td.manager_hp").attr("old_value");
        manager_settlement_date = $manager_tr.find("td.manager_settlement_date").attr("old_value");




        $tr.attr("old_manager_idx", manager_idx);
        $tr.find("input[name='manager_id']").val(manager_id);
        $tr.find("input[name='item_in_charge']").val(item_in_charge);
        $tr.find("input[name='manager_name']").val(manager_name);
        $tr.find("input[name='manager_position']").val(manager_position);
        $tr.find("input[name='manager_department']").val(manager_department);
        $tr.find("input[name='manager_email']").val(manager_email);
        $tr.find("input[name='manager_tel']").val(manager_tel);
        $tr.find("input[name='manager_hp']").val(manager_hp);
        $tr.find("input[name='manager_settlement_date']").val(manager_settlement_date);

        $tr.find(".td_edit .fa-close").hide();
        $tr.find(".td_edit .fa-save").removeClass("hide").css("display","block").show();
        $tr.find(".td_edit .fa-undo").removeClass("hide").css("display","block").show();



        $manager_tr.after($tr);

        $(this).closest("tr").hide();

    });


    // 매니저 수정후 되돌리기
    $(document).on("click","#table_manager_info .fa-undo",function(){

        $manager_tr = $(this).closest("tr");

        manager_idx =  $manager_tr.attr("old_manager_idx");
        $manager_tr.remove();
        $("#table_manager_info").find("tr[manager_idx='"+manager_idx+"']").show();



    });


    // 매니저 수정후 저장/ 추가
    $(document).on("click","#table_manager_info .fa-save",function(){

        add_manager($(this));

    });


    // 매니저 추가취소
    $(document).on("click","#table_manager_info .fa-close",function(){

        $(this).closest("tr").remove();



    });














});






function delete_manager($obj){

    

    var manager_idx = $obj.closest("tr").attr("manager_idx");

    var url = "./contents/consulting/api/insertManager.php";

    var str = "mode=del&manager_idx="+manager_idx; //

    console.log(str);


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);
                    //$obj.remove();
                    $obj.closest("tr").remove();

                    
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



function add_manager($obj){


    $tr = $obj.closest("tr");

    var consulting_idx = $("table.table_company_key_info").attr("consulting_idx");

    var manager_idx                     = $tr.attr("old_manager_idx");
    var manager_id                      = $tr.find("input[name='manager_id']").val();
    var item_in_charge                  = $tr.find("input[name='item_in_charge']").val();
    var manager_name                    = $tr.find("input[name='manager_name']").val();
    var manager_department              = $tr.find("input[name='manager_department']").val();
    var manager_position                = $tr.find("input[name='manager_position']").val();
    var manager_email                   = $tr.find("input[name='manager_email']").val();
    var manager_tel                     = $tr.find("input[name='manager_tel']").val();
    var manager_hp                      = $tr.find("input[name='manager_hp']").val();
    var manager_settlement_date         = $tr.find("input[name='manager_settlement_date']").val();




    if(parseInt(manager_idx) > 0){

        var mode = "edit";

    }else{

        var mode = "add";

    }




    var url = "./contents/consulting/api/insertManager.php";

    var str = "mode="+mode+"&consulting_idx="+consulting_idx+"&manager_idx="+manager_idx+"&manager_id="+manager_id+"&item_in_charge="+item_in_charge+"&manager_name="+manager_name+"&manager_department="+manager_department+"&manager_position="+manager_position+"&manager_email="+manager_email+"&manager_tel="+manager_tel+"&manager_hp="+manager_hp+"&manager_settlement_date="+manager_settlement_date; //

    console.log(str);


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);
                    //$obj.remove();


                    if(mode == "edit"){
                        var $manager_tr = $("#table_manager_info").find("tbody").find("tr[manager_idx='"+manager_idx+"']");

                    }else if(mode == "add"){
                        var $manager_tr = $("#manager_sample").find("tr").clone(true);
                        $manager_tr.attr("manager_idx", result.data.manager_idx);
                    }

                    $manager_tr.find("td.manager_id").text(manager_id).attr("old_value",manager_id);

                    $manager_tr.find("td.item_in_charge").text(item_in_charge).attr("old_value",item_in_charge);

                    $manager_tr.find("td.manager_name").text(manager_name).attr("old_value",manager_name);
                    $manager_tr.find("td.manager_position").text(manager_position).attr("old_value",manager_position);
                    $manager_tr.find("td.manager_department").text(manager_department).attr("old_value",manager_department);
                    $manager_tr.find("td.manager_email").text(manager_email).attr("old_value",manager_email);
                    $manager_tr.find("td.manager_tel").text(manager_tel).attr("old_value",manager_tel);
                    $manager_tr.find("td.manager_hp").text(manager_hp).attr("old_value",manager_hp);
                    $manager_tr.find("td.manager_settlement_date").text(manager_settlement_date).attr("old_value",manager_settlement_date);



                    if(mode == "edit"){
                        $manager_tr.show();
                    }else if(mode == "add"){
                        $obj.closest("tr").after($manager_tr);
                    }

                    $obj.closest("tr").remove();
                
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