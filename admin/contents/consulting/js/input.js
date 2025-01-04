
$(document).ready(function(){

    $(document).on("change","input[name='office_type']",function() {
        // '본사' 라디오 버튼이 선택되었는지 확인합니다.
        if ($(this).val() == "본사") {
          // 'head_office_consulting_idx' 이름을 가진 select 박스의 선택값을 초기화합니다.
          $('#head_office_consulting_idx').val(null).trigger('change');
        }
      });

    $(document).on("click",".manager_settlement .fa",function(e){

        if($(this).hasClass("mgreen")){
            $(this).removeClass("mgreen");
        }else{
            $(this).addClass("mgreen");
        }
    });




    $(document).on("click",".btn_cancel",function(e){

        var consulting_idx = parseInt($(this).attr("consulting_idx"));
        if(consulting_idx > 0){

            window.location.href='?page=consulting/detail&consulting_idx='+consulting_idx;

        }else{
            $("div.input_area input").val("");
            $("div.input_area textarea").val("");

        }



    });

    
    // 매니저 row 추가
    $(document).on("click",".btn_manager_add",function(){

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



    // 매니저 삭제
    $(document).on("click","td.td_x",function(){

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





    $(document).on("click",".btn_save",function(e){

        var url = "./contents/consulting/api/insertRow.php";


        var company_name       = $("#company_name").val();
        var employees          = $("#employees").val();
        var employment_fee     = $("#employment_fee").val();
        var trading_items      = $("#trading_items").val();
        //var tradeable_items    = $("#tradeable_items").val();
        var contract_date      = $("#contract_date").val();

        var biz_num            = $("#biz_num").val();
        var corp_num           = $("#corp_num").val();
        var office_type        = $("input[name='office_type']:checked").val();
        var head_office_consulting_idx        = $("#head_office_consulting_idx option:selected").val();
        var head_office        = $("#head_office_consulting_idx option:selected").text();

        var ceo_name           = $("#ceo_name").val();
        var tel                = $("#tel").val();
        var fax                = $("#fax").val();
        var biz_part           = $("#biz_part").val();
        var biz_type           = $("#biz_type").val();
        var address            = $("#address").val();
        var homepage           = $("#homepage").val();

        var memo               = $("#memo").val();

        var meeting            = $("input[name='meeting']:checked").val();
        var meeting_person     = $("#meeting_person").val();

        //var settlement_period  = $("#settlement_period").val();
        //var settlement_date    = $("#settlement_date").val();
        var payment_date       = $("#payment_date").val();  //대금지급일
        //var settlement_memo    = $("#settlement_memo").val();
        var input_part         = $(this).attr("mode");

        if(company_name == ""){
            toast("회사명은 필수입니다");
            $("#company_name").focus();
            return;
        }


        if(office_type == "계열사"){
            if(head_office_consulting_idx == undefined || head_office_consulting_idx == "" || head_office_consulting_idx == "0"){
                window.alert('계열사인경우 본사를 선택해야합니다');
                return;
            }
        }else{
            head_office_consulting_idx = null;
            head_office = null;
        }




        var manager_list = [];
        $("#table_manager tbody").find("tr").each(function(index) {
            var manager_idx        = $(this).attr("manager_idx");
            var manager_id     = $(this).find("input[name='manager_id']").val();

            var item_in_charge     = $(this).find("input[name='item_in_charge']").val();
            var manager_name       = $(this).find("input[name='manager_name']").val();
            var manager_department = $(this).find("input[name='manager_department']").val();
            var manager_position   = $(this).find("input[name='manager_position']").val();
            var manager_email      = $(this).find("input[name='manager_email']").val();
            var manager_tel        = $(this).find("input[name='manager_tel']").val();
            var manager_hp         = $(this).find("input[name='manager_hp']").val();
            var manager_settlement_date         = $(this).find("input[name='manager_settlement_date']").val();


            if(parseInt(manager_idx) > 0){
                manager_list.push({
                    "manager_idx" : parseInt(manager_idx),
                    "manager_id": manager_id,
                    "item_in_charge": item_in_charge,
                    "manager_name": manager_name,
                    "manager_department": manager_department,
                    "manager_position": manager_position,
                    "manager_email": manager_email,
                    "manager_tel": manager_tel,
                    "manager_hp": manager_hp,
                    "manager_settlement_date": manager_settlement_date

                });
                
            }else{
                if((manager_name != "" && manager_name != undefined) || (item_in_charge != "" && item_in_charge != undefined)){
                    manager_list.push({
                        "manager_id": manager_id,
                        "item_in_charge": item_in_charge,
                        "manager_name": manager_name,
                        "manager_department": manager_department,
                        "manager_position": manager_position,
                        "manager_email": manager_email,
                        "manager_tel": manager_tel,
                        "manager_hp": manager_hp,
                        "manager_settlement_date": manager_settlement_date

                    });
                }
    
            }






        });



        var consulting_idx = parseInt($(this).attr("consulting_idx"));
        if(consulting_idx > 0){
            var data = {
                mode: "edit",
                consulting_idx: consulting_idx,

                company_name   : company_name,
                employees      : employees,
                employment_fee : employment_fee,
                trading_items  : trading_items,
                /*tradeable_items: tradeable_items,*/
                contract_date  : contract_date,
                biz_num        : biz_num,
                corp_num       : corp_num,
                office_type    : office_type,
                head_office_consulting_idx : head_office_consulting_idx,
                head_office    : head_office,
                ceo_name       : ceo_name,
                tel            : tel,
                fax            : fax,
                biz_part       : biz_part,
                biz_type       : biz_type,
                address        : address,
                homepage       : homepage,

                manager_list   : manager_list,
                memo           : memo,
                
                meeting        : meeting,
                meeting_person : meeting_person,

                // settlement_period : settlement_period,
                // settlement_date   : settlement_date,
                payment_date      : payment_date
                // settlement_memo   : settlement_memo
    
            };
        }else{
            var data = {
                mode: "input",
               
                company_name   : company_name,
                employees      : employees,
                employment_fee : employment_fee,
                trading_items  : trading_items,
                /*tradeable_items: tradeable_items,*/
                contract_date  : contract_date,

                biz_num        : biz_num,
                corp_num       : corp_num,
                office_type    : office_type,
                head_office_consulting_idx : head_office_consulting_idx,
                head_office    : head_office,
                ceo_name       : ceo_name,
                tel            : tel,
                fax            : fax,
                biz_part       : biz_part,
                biz_type       : biz_type,
                address        : address,
                homepage       : homepage,

                manager_list: manager_list,
                memo:memo,

                meeting        : meeting,
                meeting_person : meeting_person,

                // settlement_period : settlement_period,
                // settlement_date   : settlement_date,
                payment_date      : payment_date,
                input_part        : input_part
                // settlement_memo   : settlement_memo
            };
        }








        var str = JSON.stringify(data); //

        console.log(url);
        console.log(str);

        
    
        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {

                        if(consulting_idx == undefined){
                            var consulting_idx = result.data.consulting_idx;
                        }

                        console.log(result);

                        showToast('저장되었습니다');
                        window.location.href='?page=consulting/detail&consulting_idx='+consulting_idx;

                        //window.alert('저장되었습니다.');

                        
                    }else{
                        var msg = result.msg;
                        window.alert(msg);
                        console.log(result.data.query_insert);
                    }
                }, //success
                error : function( jqXHR, textStatus, errorThrown ) {
                    alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
                }
        }); //ajax

    });






});



//매니저 정보 삭제
function delete_manager($obj){
    
    var manager_idx = $obj.closest("tr").attr("manager_idx");
    var url = "./contents/consulting/api/updateCell.php";
    var str = "mode=manager_del&manager_idx="+manager_idx;
    console.log(str);

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    toast(result.msg);
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