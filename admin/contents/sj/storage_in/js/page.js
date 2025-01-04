jQuery.expr[":"].Contains = jQuery.expr.createPseudo(function(arg) {
    return function( elem ) {
        return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});




function select_li($this){
    //var product_idx = $this.find("input[name='product']").val();
    var product_idx = $this.attr("product_idx");

    var product_name = $this.find(".li_product_name").text();


    if($this.find(".icheckbox_flat-green").hasClass("checked")){
        $this.find(".icheckbox_flat-green").removeClass("checked");
        $("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"']").remove();
    }else{
        $this.find(".icheckbox_flat-green").addClass("checked");

        if($("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"']").length == 0){
            var $product_li = $("#product_li_add_sample").find("li").clone(true);
            $product_li.attr("product_idx",product_idx);
            $product_li.find(".li_product_name").text(product_name);
            $("ul.ul_product_list_right").append($product_li);
        }
    }

    reset_total();
               
}


//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){





    $(document).on("click","ul.ul_product_list li",function() {
        
        select_li($(this));
    });




    $(document).on("click","button.btn_cancel",function() {
        $("ul.ul_product_list_right").html("");
        reset_total();
    });


    $(document).on("click","button.btn_save",function() {
        var storage_idx = $("#storage").val();
        var memo = $("#memo").val();

        var product_list = Array();

        $("ul.ul_product_list_right").find("li").each(function(index) {
            var product_idx = $(this).attr("product_idx");
            var cnt = parseInt($(this).find("input[name='cnt']").val());
            var pcnt = {product_idx,cnt};
            if(cnt > 0){
                product_list.push(pcnt);

            }

        });

        var data = {
            mode: "input",
            storage_idx: storage_idx,
            product_list: product_list,
            memo:memo

        };

        console.log(data);

                

        if(product_list.length > 0){
            saveNew(data);

        }
    });


    
    $(document).on("change",".input_cnt",function() {

        var cnt = $(this).val();
        if(cnt < 0){
            $(this).val("0");
        }
        reset_total();
        
    });


    
    $(document).on("click",".btn_x_circle",function() {
        var product_idx = $(this).closest("li").attr("product_idx");
        $(this).closest("li").remove();
        var $li = $("ul.ul_product_list").find("li[product_idx='"+product_idx+"']")

        $li.find(".icheckbox_flat-green").removeClass("checked");

        reset_total();
           
    });





    
    //select2 변경시 이벤트 호출  //설명 https://select2.org/programmatic-control/events#preventing-events
    $(document).on('select2:select','.select2_single', function (e) {
         var data = e.params.data;

         var this_id = $(this).attr("id");
         if(this_id == "storage"){
             load_product(data.id);
             $(".title_left").find("h3").html("입고서 > "+data.text);
         }

        //console.log(data.text);  //select 의 text 값
        //console.log(data.id);  //value 값임.
    });




    $(document).on("keyup","#product_filter",function() {
          var keyword = $(this).val();
          var uppper = keyword.toUpperCase()
          console.log(keyword);
          console.log(uppper);

          $("ul.ul_product_list").find("li").hide();
          $("ul.ul_product_list").find("li .li_product_name:contains("+keyword+")").closest("li").show();
          $("ul.ul_product_list").find("li .li_product_name:contains("+uppper+")").closest("li").show();

            
     });
 



});



function saveNew(dataArray){

    var url = "./contents/sj/api/save_product_info.php";
    var str = JSON.stringify(dataArray); //

    console.log(url);
    console.log(str);

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var obj = result.data;
                    // obj = JSON.stringify(obj); 
                    // obj = JSON.parse(obj);

                    $("ul.ul_product_list").html("");
                    $("ul.ul_product_list_right").html("");
                    reset_total();
                    $("#memo").val("");

                    for(var i=0;i<obj.product_list.length;i++){
                         var pl = obj.product_list[i];
                         var $product_li = $("#product_li_sample").find("li").clone(true);

                        $product_li.attr("product_idx",pl.product_idx);
                        $product_li.find(".li_product_name").text(pl.product_name);
                        $product_li.find(".current_storage_cnt").text(check_null(pl.current_count));
                        if(check_null(pl.sum_in_count) != 0){
                            $product_li.find(".plus_storage_cnt").text("+"+pl.sum_in_count).removeClass("hide");
                        }

                        $("ul.ul_product_list").append($product_li);

                    }

                    showToast(result.msg);

                    

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




function check_null(str){

    if (str == "null" || str == null || str == "" || str == "undefind" || str == undefined) {
        return 0;
    }else{
        return str;
    }

}


function reset_total(){
    var total = 0;
    $("ul.ul_product_list").find("li").find("br").hide();
    $("ul.ul_product_list").find("li").find(".plus_storage_cnt").hide();

    $("ul.ul_product_list_right").find("li").each(function(index) {
        var cnt = parseInt($(this).find("input[name='cnt']").val());
        var product_idx = $(this).attr("product_idx");
        if(cnt > 0){
            $("ul.ul_product_list").find("li[product_idx='"+product_idx+"']").find(".plus_storage_cnt").html("+"+cnt).removeClass("hide").show();
            $("ul.ul_product_list").find("li[product_idx='"+product_idx+"']").find("br").removeClass("hide").show();

        }

        total += cnt;
    });

    var li_cnt = $("ul.ul_product_list_right").find("li").length;
    $("ul.selected_total_ul").find(".selected_product_num").text(li_cnt);
    $("ul.selected_total_ul").find(".plus_storage_cnt").text("+"+total);

    
}


function load_product(storage_idx){
    
    var url = "./contents/sj/api/get_product_info.php";
    var str = "storage_idx="+storage_idx; //

    console.log(url);
    console.log(str);

    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var obj = result.data;
                    // obj = JSON.stringify(obj); 
                    // obj = JSON.parse(obj);

                    console.log(obj);

                    $("ul.ul_product_list").html("");
                    $("ul.ul_product_list_right").html("");
                    reset_total();
                    $("#memo").val("");

                    for(var i=0;i<obj.product_list.length;i++){
                         var pl = obj.product_list[i];
                         var $product_li = $("#product_li_sample").find("li").clone(true);

                        $product_li.attr("product_idx",pl.product_idx);
                        $product_li.find(".li_product_name").text(pl.product_name);
                        $product_li.find(".current_storage_cnt").text(check_null(pl.current_count));
                        if(check_null(pl.sum_in_count) != 0){
                            $product_li.find(".plus_storage_cnt").text("+"+pl.sum_in_count).removeClass("hide");
                        }

                        $("ul.ul_product_list").append($product_li);

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

