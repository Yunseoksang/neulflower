
//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){







    
    // //select2 변경시 이벤트 호출  //설명 https://select2.org/programmatic-control/events#preventing-events
    // $(document).on('select2:select','.select2_single', function (e) {
    //     var data = e.params.data;
    //     var $sel = $(this).closest("div.form-group").find("select");

    //     var column = $(this).closest('td').attr('column');
    //     $(this).closest("td").attr(column+"_new",data.id);

    //     // //console.log(data.text);  //select 의 text 값
    //     // //console.log(data.id);  //value 값임.
    // });


    $(document).on("click",".btn_modal_save",function(){

        
        var admin_idx = $("#modal-admin-permission").attr("admin_idx");
        
        var str = "mode=edit&admin_idx="+admin_idx; //


        var permission_super         = $("#select_permission_super        option:selected").val();
        var permission_fullfillment  = $("#select_permission_fullfillment option:selected").val();
        var permission_sangjo        = $("#select_permission_sangjo       option:selected").val();
        var permission_flower        = $("#select_permission_flower       option:selected").val();
        var permission_consulting    = $("#select_permission_consulting   option:selected").val();
        var permission_statistics    = $("#select_permission_statistics   option:selected").val();

        var storage_super           = $("#select_storage_super           option:selected").val();
        var storage_fullfillment    = $("#select_storage_fullfillment    option:selected").val();
        var storage_sangjo          = $("#select_storage_sangjo          option:selected").val();
        var storage_flower          = $("#select_storage_flower          option:selected").val();
        var storage_consulting      = $("#select_storage_consulting      option:selected").val();
        var storage_statistics      = $("#select_storage_statistics      option:selected").val();

        var start_page              = $("#select_start_page              option:selected").val();





        console.log("permission_super        " + permission_super        );
        console.log("permission_sangjo       " + permission_sangjo       );
        console.log("permission_fullfillment " + permission_fullfillment );
        console.log("permission_flower       " + permission_flower       );
        console.log("permission_consulting   " + permission_consulting   );
        console.log("permission_statistics   " + permission_statistics   );

        console.log("storage_super           " + storage_super           );
        console.log("storage_sangjo          " + storage_sangjo          );
        console.log("storage_fullfillment    " + storage_fullfillment    );
        console.log("storage_flower          " + storage_flower          );
        console.log("storage_consulting      " + storage_consulting      );
        console.log("storage_statistics      " + storage_statistics      );
        console.log("start_page              " + start_page              );


        if(permission_super         != "0"){str += "&permission_super="+permission_super;}
        if(permission_fullfillment  != "0"){str += "&permission_fullfillment="+permission_fullfillment;}
        if(permission_sangjo        != "0"){str += "&permission_sangjo="+permission_sangjo;}
        if(permission_flower        != "0"){str += "&permission_flower="+permission_flower;}
        if(permission_consulting    != "0"){str += "&permission_consulting="+permission_consulting;}
        if(permission_statistics    != "0"){str += "&permission_statistics="+permission_statistics;}

        if(storage_super            != "0"){str += "&storage_super="+storage_super;}
        if(storage_fullfillment     != "0"){str += "&storage_fullfillment="+storage_fullfillment;}
        if(storage_sangjo           != "0"){str += "&storage_sangjo="+storage_sangjo;}
        if(storage_flower           != "0"){str += "&storage_flower="+storage_flower;}
        if(storage_consulting       != "0"){str += "&storage_consulting="+storage_consulting;}
        if(storage_statistics       != "0"){str += "&storage_statistics="+storage_statistics;}

        if(start_page               != "0"){str += "&start_page="+start_page;}



        var t_storage_super           = $("#select_storage_super           option:selected").text();
        var t_storage_fullfillment    = $("#select_storage_fullfillment    option:selected").text();
        var t_storage_sangjo          = $("#select_storage_sangjo          option:selected").text();
        var t_storage_flower          = $("#select_storage_flower          option:selected").text();
        var t_storage_consulting      = $("#select_storage_consulting      option:selected").text();
        var t_storage_statistics      = $("#select_storage_statistics      option:selected").text();

        // 저장후 표에 즉시 업데이트 표기위해서
        var result_pm = "";
        var result_st = "";

        if(permission_super         != "0"){
            result_pm += permission_super        + "<br>";
            if(t_storage_super            != "해당없음"){result_st += t_storage_super           + "<br>";}else{result_st += "--<br>";}

        }else{
            // if(t_storage_super            != "해당없음"){
            //     result_pm += "--<br>";
            //     result_st += t_storage_super           + "<br>";

            // }
        }

        if(permission_fullfillment         != "0"){
            result_pm += permission_fullfillment        + "<br>";
            if(t_storage_fullfillment            != "해당없음"){result_st += t_storage_fullfillment           + "<br>";}else{result_st += "--<br>";}

        }else{
            // if(t_storage_fullfillment            != "해당없음"){
            //     result_pm += "--<br>";
            //     result_st += t_storage_fullfillment           + "<br>";

            // }
        }


        if(permission_sangjo         != "0"){
            result_pm += permission_sangjo        + "<br>";
            if(t_storage_sangjo            != "해당없음"){result_st += t_storage_sangjo           + "<br>";}else{result_st += "--<br>";}

        }else{
            // if(t_storage_sangjo            != "해당없음"){
            //     result_pm += "--<br>";
            //     result_st += t_storage_sangjo           + "<br>";

            // }
        }


        if(permission_flower         != "0"){
            result_pm += permission_flower        + "<br>";
            if(t_storage_flower            != "해당없음"){result_st += t_storage_flower           + "<br>";}else{result_st += "--<br>";}

        }else{
            // if(t_storage_flower            != "해당없음"){
            //     result_pm += "--<br>";
            //     result_st += t_storage_flower           + "<br>";

            // }
        }


        if(permission_consulting         != "0"){
            result_pm += permission_consulting        + "<br>";
            if(t_storage_consulting            != "해당없음"){result_st += t_storage_consulting           + "<br>";}else{result_st += "--<br>";}

        }else{
            // if(t_storage_consulting            != "해당없음"){
            //     result_pm += "--<br>";
            //     result_st += t_storage_consulting           + "<br>";

            // }
        }


        if(permission_statistics         != "0"){
            result_pm += permission_statistics        + "<br>";
            if(t_storage_statistics            != "해당없음"){result_st += t_storage_statistics           + "<br>";}else{result_st += "--<br>";}

        }else{
            // if(t_storage_statistics            != "해당없음"){
            //     result_pm += "--<br>";
            //     result_st += t_storage_statistics           + "<br>";

            // }
        }




        // if(permission_fullfillment  != "0"){result_pm += permission_fullfillment + "<br>";}
        // if(permission_sangjo        != "0"){result_pm += permission_sangjo       + "<br>";}
        // if(permission_flower        != "0"){result_pm += permission_flower       + "<br>";}
        // if(permission_consulting    != "0"){result_pm += permission_consulting   + "<br>";}
        // if(permission_statistics    != "0"){result_pm += permission_statistics   + "<br>";}
      



        // if(t_storage_fullfillment     != "0"){result_st += t_storage_fullfillment    + "<br>";}
        // if(t_storage_sangjo           != "0"){result_st += t_storage_sangjo          + "<br>";}
        // if(t_storage_flower           != "0"){result_st += t_storage_flower          + "<br>";}
        // if(t_storage_consulting       != "0"){result_st += t_storage_consulting      + "<br>";}
        // if(t_storage_statistics       != "0"){result_st += t_storage_statistics      + "<br>";}


        if(result_pm != ""){
            result_pm = result_pm.slice(0, -4);
        }
  

        if(result_st != ""){
            result_st = result_st.slice(0, -4);
        }
  




        console.log(str);
        var url = "./contents/admin_list/api/getAdminPermissionInfo.php";


        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;

                    console.log();

                    if(result_status == 1)
                    {
                        toast(result.msg);

                        //var admin_permission = result.data;

                        $("#modal-admin-permission").modal('hide');
                        $("#datatable-main").find("tr[admin_idx='"+admin_idx+"']").find("td[column='exec_permission']").html(result_pm);
                        $("#datatable-main").find("tr[admin_idx='"+admin_idx+"']").find("td[column='exec_storage']").html(result_st);

                    }else{
                        var msg = result.msg;
                        toast(msg);



                    }
                }, //success
                error : function( jqXHR, textStatus, errorThrown ) {
                    alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
                }
        }); //ajax


    });
    


    //권한 수정 모달창 열기
    $(document).on("click",".btn_permission_setting",function(){

        var admin_idx = $(this).closest("tr").attr("admin_idx");
        $("#modal-admin-permission").attr("admin_idx",admin_idx);

        
        var url = "./contents/admin_list/api/getAdminPermissionInfo.php";


        var str = "mode=get&admin_idx="+admin_idx; //
        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        var admin_permission = result.data;

                        console.log(admin_permission);
                        //console.log("admin_permission.pm_super:"+admin_permission.pm_super);
                        $("#modal-admin-permission option:selected").removeAttr("selected"); //초기화

                        $("#select_permission_super        option[value='"+admin_permission.pm_super+"']").prop('selected','selected');
                        $("#select_permission_sangjo       option[value='"+admin_permission.pm_sangjo+"']").prop('selected','selected');
                        $("#select_permission_fullfillment option[value='"+admin_permission.pm_fullfillment+"']").prop('selected','selected');
                        $("#select_permission_flower       option[value='"+admin_permission.pm_flower+"']").prop('selected','selected');
                        $("#select_permission_consulting   option[value='"+admin_permission.pm_consulting+"']").prop('selected','selected');
                        $("#select_permission_statistics   option[value='"+admin_permission.pm_statistics+"']").prop('selected','selected');


                        $("#select_storage_super        option[value='"+admin_permission.storage_super+"']").prop('selected','selected');
                        $("#select_storage_sangjo       option[value='"+admin_permission.storage_sangjo+"']").prop('selected','selected');
                        $("#select_storage_fullfillment option[value='"+admin_permission.storage_fullfillment+"']").prop('selected','selected');
                        $("#select_storage_flower       option[value='"+admin_permission.storage_flower+"']").prop('selected','selected');
                        $("#select_storage_consulting   option[value='"+admin_permission.storage_consulting+"']").prop('selected','selected');
                        $("#select_storage_statistics   option[value='"+admin_permission.storage_statistics+"']").prop('selected','selected');

                        $("#select_start_page   option[value='"+admin_permission.start_page+"']").prop('selected','selected');




                    }else{
                        //var msg = result.msg;
                        //toast(msg);

                        $("#modal-admin-permission option:selected").removeAttr("selected");


                    }
                }, //success
                error : function( jqXHR, textStatus, errorThrown ) {
                    alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
                }
        }); //ajax
        
    });






    $(document).on("click",".btn_pw_reset",function(e){

        var url = "./contents/admin_list/api/updateCell.php";
        var admin_idx = $(this).closest("tr").attr("admin_idx");
        var str = "mode=pw_reset&admin_idx="+admin_idx; //
        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        //var obj = result.data;
                        // obj = JSON.stringify(obj); 
                        // obj = JSON.parse(obj);

                        // for(var i=0;i<obj.categoryList.length;i++){
                        //     var CL = obj.categoryList[i];
                        //     categoryList[obj.categoryList[i].category_idx] = CL;
                        // }

                        toast('비빌번호 초기화되었습니다');
                        $(this).removeClass("btn-danger").addClass("btn-info").text("비번초기화");

  
                        
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








    $(document).on("click","div.btn-group[col_name='admin_status'] ul.dropdown-menu li a",function(e){



        var url = "./contents/admin_list/api/updateCell.php";
        var admin_status = $(this).text();
        var admin_idx = $(this).closest("tr").attr("admin_idx");



        var str = "mode=admin_status&admin_status="+admin_status+"&admin_idx="+admin_idx; //
        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {

                        toast(result.msg);

                        $(this).closest("td").attr("admin_status",admin_status);

 
                        
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


/*

function applyCategoryList(inCategoryList) {

    var $parent = $("#input_sample,#modify_sample");
    $parent.find("select[name='category_idx']").html("<option value=''>카테고리</option>");
    var num = 0;
    for(key in inCategoryList)
    {
        num++;
        var element = inCategoryList[key];
        if(num == 1){
            $parent.find("select[name='category_idx']").append("<option selected value='"+element.category_idx+"'>"+element.category_name+"</option>");
        }else{
            $parent.find("select[name='category_idx']").append("<option value='"+element.category_idx+"'>"+element.category_name+"</option>");
        }
    }

}





function applyCategoryListModal(inCategoryList) {

    var $parent = $("div[modal_name='new']");

    $parent.find("select[name='category_idx']").html("<option value=''>카테고리</option>");
    var num = 0;
    for(key in inCategoryList)
    {
        num++;
        var element = inCategoryList[key];
        if(num == 1){
            $parent.find("select[name='category_idx']").append("<option selected value='"+element.category_idx+"'>"+element.category_name+"</option>");
        }else{
            $parent.find("select[name='category_idx']").append("<option value='"+element.category_idx+"'>"+element.category_name+"</option>");
        }
    }



        $('#modal_add').find(".select2_single").select2({
            allowClear: true
        });
}


*/

