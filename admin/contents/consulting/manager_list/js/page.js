
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




    $(document).on("click",".btn_pw_reset",function(e){

        var url = "./contents/consulting/manager_list/api/updateCell.php";
        var manager_idx = $(this).closest("tr").attr("manager_idx");
        var str = "mode=pw_reset&manager_idx="+manager_idx; //
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








    $(document).on("click","div.btn-group[col_name='manager_status'] ul.dropdown-menu li a",function(e){



        var url = "./contents/consulting/manager_list/api/updateCell.php";
        var manager_status = $(this).text();
        var manager_idx = $(this).closest("tr").attr("manager_idx");



        var str = "mode=manager_status&manager_status="+manager_status+"&manager_idx="+manager_idx; //
        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {

                        toast(result.msg);

                        $(this).closest("td").attr("manager_status",manager_status);

 
                        
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

