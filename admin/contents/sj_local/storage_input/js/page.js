jQuery.expr[":"].Contains = jQuery.expr.createPseudo(function(arg) {
    return function( elem ) {
        return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});


function select_li($this){
    
        //var product_idx = $this.find("input[name='product']").val();
        var product_idx = $this.attr("product_idx");

        var product_name = $this.find(".li_product_name").text();
        var current_count = $this.find(".current_storage_cnt").text();


        if($this.find(".icheckbox_flat-green").hasClass("checked")){
            $this.find(".icheckbox_flat-green").removeClass("checked");
            $("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"']").remove();
        }else{
            $this.find(".icheckbox_flat-green").addClass("checked");

            if($("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"']").length == 0){
                var $product_li = $("#product_li_add_sample").find("li").clone(true);
                $product_li.attr("product_idx",product_idx);
                $product_li.find(".li_product_name").text(product_name);
                $product_li.find(".old_current_count").text(current_count);

                $("ul.ul_product_list_right").append($product_li);
            }
       }

       reset_total();
}


//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){


    $(document).on("click","ul.ul_product_list li",function(e) {
        e.stopPropagation();
        select_li($(this));
           
    });
    // $(document).on("click",".icheckbox",function() {
    //     //select_li($(this).closest("li"));
    //     window.alert('kkk'); 
    // });





    $(document).on("click","button.btn_cancel",function() {
        $("ul.ul_product_list_right").html("");
        reset_total();
    });




    $(document).on("click","button.btn_save",function() {
        var storage_idx = $("#in_storage").val();

        if(storage_idx > 0){
           //
        }else{

            showToast('입고 창고를 선택해주세요');
            return;
        }
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

        }else{
            showToast('선택된 품목이 없습니다');
        }
    });




    $(document).on("click","button.btn_save_adjust",function() {

        var memo = $("#memo").val();

        var product_list = Array();

        $("ul.ul_product_list_right").find("li").each(function(index) {
            var product_idx = $(this).attr("product_idx");
            var cnt = parseInt($(this).find("input[name='cnt']").val());
            var pcnt = {product_idx,cnt};
            if(cnt != 0){
                product_list.push(pcnt);
            }

        });

        var data = {
            mode: "adjust",
            product_list: product_list,
            memo:memo

        };

        console.log(data);

                

        if(product_list.length > 0){
            saveOutput("adjust",data);

        }else{
            showToast('선택된 품목이 없습니다');
        }
    });





    $(document).on("click","button.btn_save_out",function() {
        
        var to_place_name = $("#to_place_name").val();
        var address = $("#address").val();
        var to_name = $("#to_name").val();
        var hp = $("#hp").val();
        var memo = $("#memo").val();

        if($("#move_check").parent().hasClass("checked")){
            var move_check = 1;
        }else{
            var move_check = 0;

        }

        
        /*

        if(address == "" || to_name == "" || hp == ""){
            showToast("필수항목이 누락되었습니다.");
            return;
        }

        */

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
            mode: "output",
            product_list: product_list,
            to_place_name:to_place_name,
            address:address,
            to_name:to_name,
            hp:hp,
            memo:memo,
            move_check:move_check


        };

        console.log(data);

                

        if(product_list.length > 0){
            saveOutput("output",data);

        }else{
            showToast('선택된 품목이 없습니다');
        }
    });





    
    
    $(document).on("change",".input_cnt",function() {

        var mode = $(".right_set").attr("mode");

        if(mode != "adjust"){
            var cnt = $(this).val();
            if(cnt < 0){
                $(this).val("0");
            }
        }else if(mode == "adjust"){
            var cnt = parseInt($(this).val());
            if(isNaN(cnt)){
                cnt = 0;
            }
            var old_cnt = parseInt($(this).closest("li").find(".old_current_count").text());

            if(cnt < 0 && old_cnt + cnt < 0){
                $(this).val(old_cnt*(-1));
                showToast("제품별 조정개수가 0개 이하일수 없습니다");
            }
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


        if(this_id == "in_storage" && data.id >= 0){
             load_product(data.id);
             $(".title_left").find("h3").html("입고서 : "+data.text);
        }else if(this_id == "out_storage" && data.id >= 0){
            load_product(data.id);
            $(".title_left").find("h3").html("출고지시서 : "+data.text);
        }else if(this_id == "storage_from" && data.id >= 0){
            load_product(data.id);
            $(".title_left").find("h3").html("이동지시서 : "+data.text);
        }else if(this_id == "adjust_storage" && data.id >= 0){
            load_product(data.id);
            $(".title_left").find("h3").html("조정 : "+data.text);
        }

        // console.log(data.text);  //select 의 text 값
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

    var url = "./contents/sj_local/storage_input/api/save_product_info.php";
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
                        $product_li.find(".current_storage_cnt").text(check_null(pl.sum_current_count));
                        if(check_null(pl.sum_out_count) != 0){
                            $product_li.find(".plus_storage_cnt").text("+"+pl.sum_out_count).removeClass("hide");
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




function saveOutput(mode,dataArray){

    if(mode == "output"){
        var url = "./contents/sj_local/storage_input/api/save_product_output.php";

    }else if(mode == "move"){
        var url = "./contents/sj_local/storage_input/api/save_product_move.php";

    }
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

                    if(mode == "output"){
                        $("#to_place_name").val("");
                        $("#address").val("");
                        $("#to_name").val("");
                        $("#hp").val("");
                    }

                    $("#memo").val("");

                    $("ul.ul_product_list").html("");
                    $("ul.ul_product_list_right").html("");
                    reset_total();

                    for(var i=0;i<obj.product_list.length;i++){
                         var pl = obj.product_list[i];
                         var $product_li = $("#product_li_sample").find("li").clone(true);

                        $product_li.attr("product_idx",pl.product_idx);
                        $product_li.find(".li_product_name").text(pl.product_name);
                        $product_li.find(".current_storage_cnt").text(check_null(pl.sum_current_count));
                        if(check_null(pl.sum_out_count) != 0){
                            $product_li.find(".plus_storage_cnt_minus").text("-"+pl.sum_out_count).removeClass("hide");
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

    var mode = $(".right_set").attr("mode");


    //console.log(mode);
    var total = 0;
    $("ul.ul_product_list").find("li").find("br").hide();


    $("ul.ul_product_list").find("li").find(".plus_storage_cnt").hide();
    $("ul.ul_product_list").find("li").find(".plus_storage_cnt_minus").hide();


    $("ul.ul_product_list_right").find("li").each(function(index) {
        var cnt = parseInt($(this).find("input[name='cnt']").val());
        if(isNaN(cnt)){
            cnt = 0;
        }
        var old_cnt = parseInt($(this).find(".old_current_count").text());
        var product_idx = $(this).attr("product_idx");

        if(mode == "plus"){
            $("ul.ul_product_list").find("li[product_idx='"+product_idx+"']").find(".plus_storage_cnt").html("+"+cnt).removeClass("hide").show();
        }else if(mode == "minus"){
            $("ul.ul_product_list").find("li[product_idx='"+product_idx+"']").find(".plus_storage_cnt_minus").html("-"+cnt).removeClass("hide").show();
        }else if(mode == "adjust"){


            if(cnt >= 0){
                $("ul.ul_product_list").find("li[product_idx='"+product_idx+"']").find(".plus_storage_cnt").html("+"+cnt).removeClass("hide").show();

            }else{
                $("ul.ul_product_list").find("li[product_idx='"+product_idx+"']").find(".plus_storage_cnt_minus").html(cnt).removeClass("hide").show();

            }
        }
        $("ul.ul_product_list").find("li[product_idx='"+product_idx+"']").find("br").removeClass("hide").show();



        total += cnt;
    });

    var li_cnt = $("ul.ul_product_list_right").find("li").length;
    $("ul.selected_total_ul").find(".selected_product_num").text(li_cnt);

    if(mode == "plus"){
        $("ul.selected_total_ul").find(".plus_storage_cnt").text("+"+total);
    }else if(mode == "minus"){
        $("ul.selected_total_ul").find(".plus_storage_cnt_minus").text("-"+total);
    }else if(mode == "adjust"){
        $("ul.selected_total_ul").find(".plus_storage_cnt").hide();
        $("ul.selected_total_ul").find(".plus_storage_cnt_minus").hide();

        if(total >= 0){
            $("ul.selected_total_ul").find(".plus_storage_cnt").text("+"+total).removeClass("hide").show();
        }else{
            $("ul.selected_total_ul").find(".plus_storage_cnt_minus").text(total).removeClass("hide").show();
        }
    }

    
}


function load_product(){
    

    var mode = $(".right_set").attr("mode");

    var url = "./contents/sj_local/storage_input/api/get_product_info.php";
    var str = "mode="+mode;

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
                        $product_li.find(".current_storage_cnt").text(check_null(pl.sum_current_count));
                        if(check_null(pl.sum_out_count) != 0){
                            if(mode == "plus"){
                                $product_li.find(".plus_storage_cnt").text("+"+pl.sum_out_count).removeClass("hide");
                            }else if(mode == "minus"){
                                $product_li.find(".plus_storage_cnt_minus").text("-"+pl.sum_out_count).removeClass("hide");
                            }
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







