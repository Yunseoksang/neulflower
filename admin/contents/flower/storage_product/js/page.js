
//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){





    $(document).on("click",".btn_barcode",function(e){

        var product_idx = $(this).closest('tr').attr("product_idx");
        window.open('/admin/contents/barcode/barcode_print.php?part=sffm&product_idx='+product_idx, '_blank', '', '');
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



    $(document).on("change","#modal_add select[name='category1_idx']",function(e){

        var category1_idx = $(this).find("option:selected").val();
        var url = "./contents/flower/storage_product/api/getCategory2.php";
        var str = "category1_idx="+category1_idx; //
        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;
                    if(result_status == 1)
                    {
                        //toast(result.msg);

                        var category2_list = result.data.category2_list;

                        var $ct2 = $("select[name='category2_idx']");


                        if(category2_list.length == 0){
                            toast("등록된 중분류가 없습니다");

                            $ct2.html("");
                            $ct2.append("<option value=0 base_price=''>- 선택 -</option>");
      
                        }else{
                            $ct2.html("");
                            for(var i=0;i<category2_list.length;i++){
                                $ct2.append("<option value='"+category2_list[i]['category2_idx']+"' base_price='"+category2_list[i]['base_price']+"'>"+category2_list[i]['category2_name']+"</option>");
                            }
      
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





    $(document).on("change","#modal_add select[name='category2_idx']",function(e){
        $("input[name='product_price']").val($(this).find("option:selected").attr("base_price"));

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

