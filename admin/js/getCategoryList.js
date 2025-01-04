var categoryList = new Object();


$(document).ready(function(){

    /*
    var url = "./api/getCategoryList.php";
    var str = "1=1"; 
    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var obj = result.data;
                    obj = JSON.stringify(obj); 
                    obj = JSON.parse(obj);

                    //var couponList = new Array(); //앞에서 글로벌 변수로 선언
                    categoryList = {};

                    for(var i=0;i<obj.categoryList.length;i++){

                        var CL = obj.categoryList[i];
                        categoryList[obj.categoryList[i].category_idx] = CL;
                    }

                    //첫번째 항목 호출: couponList[obj.coupon_list[0].cu_idx]
                    applyCategoryList(categoryList); //선택창에 목록으로 노출하기

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






function applyCategoryList(inCategoryList) {
    var num = 0;
    for(key in inCategoryList)
    {
        num++;
        var element = inCategoryList[key];
        if(num == 1){
            $("#select2_sample select[name='category_idx']").append("<option selected value='"+element.category_idx+"'>"+element.category_name+"</option>");
        }else{
            $("#select2_sample select[name='category_idx']").append("<option value='"+element.category_idx+"'>"+element.category_name+"</option>");
        }
    }

}
