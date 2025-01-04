jQuery.expr[":"].Contains = jQuery.expr.createPseudo(function(arg) {
    return function( elem ) {
        return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});



document.body.addEventListener("keydown", function(event) {

    if(event.key !== "Enter"){
        $("#barcode_input").val($("#barcode_input").val()+event.key);

    }else{
        if($("#barcode_input").val().length == 13){
            get_barcode_product($("#barcode_input").val());

        }
        $("#barcode_input").val("");


    }
    //event.preventDefault();


    console.log("이벤트키:"+$("#barcode_input").val());
}, true)






//var categoryList = {}; //배열이 아닌 객체로 선언
$(document).ready(function(){


/*
    $(document).on("change","#barcode_input, #input_qrcode",function() {
        get_barcode_product($(this).val());

    });
        

    $(document).on("paste","#barcode_input, #input_qrcode",function(el) {
        
        setTimeout(function(){
            get_barcode_product($(el.target).val());
            $(el.target).val("");

        },100);

    });

*/








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
            var qr = $(this).attr("qr");
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
        var storage_idx = $("#adjust_storage").val();

        if(storage_idx > 0){
           //
        }else{

            showToast('조정 창고를 선택해주세요');
            return;
        }
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
            storage_idx: storage_idx,
            product_list: product_list,
            memo:memo

        };

        console.log(data);

                

        if(product_list.length > 0){
            saveOutput("adjust",data,"");

        }else{
            showToast('선택된 품목이 없습니다');
        }
    });





    $(document).on("click","span.current_storage_cnt",function(event) {
        event.stopPropagation();
        var storage_idx = $("#adjust_storage").val();

        if(storage_idx > 0){
           //
        }else{

            showToast('조정 창고를 선택해주세요');
            return;
        }

        var product_idx = $(this).closest("li").attr("product_idx");
        var zero_count_txt = $(this).text();
        var zero_count = "zero";

        if(zero_count_txt == "--"){
            zero_count = "0";
        }else if(zero_count_txt == "0"){
            zero_count = "zero";

        }else{
            showToast('현재 재고가 0개일때만 재고없는창고로 등록 가능합니다.');
            return;
        }

        var data = {
            mode: "empty",
            storage_idx: storage_idx,
            product_idx: product_idx,
            zero_count:zero_count

        };

        console.log(data);

        saveOutputEmpty("empty",data,$(this));

    });






    $(document).on("click","button.btn_save_out,button.btn_save_out_complete",function() {

        var storage_idx = $("#out_storage").val();
        if(storage_idx > 0){
            //
        }else{
 
             showToast('출고 창고를 선택해주세요');
             return;
        }
        var outpart = "output";
        if($(this).hasClass("btn_save_out_complete")){
            outpart = "out_complete";
        }


        //var to_place_name = $("#to_place_name").val();
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

        //to_place_name:to_place_name,


        var data = {
            mode: outpart,
            storage_idx: storage_idx,
            product_list: product_list,
            address:address,
            to_name:to_name,
            hp:hp,
            memo:memo,
            move_check:move_check


        };

        console.log(data);

                

        if(product_list.length > 0){
            saveOutput("output",data,"");

        }else{
            showToast('선택된 품목이 없습니다');
        }
    });





    $(document).on("click","button.btn_save_move",function() {
        var from_storage_idx = $("#storage_from").val();
        var to_storage_idx = $("#storage_to").val();
        var memo = $("#memo").val();


        if(from_storage_idx > 0){
            //
        }else{
             showToast('출발지 창고를 선택해주세요');
             return;
        }

        if(to_storage_idx > 0){
            //
        }else{
             showToast('도착지 창고를 선택해주세요');
             return;
        }


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
            mode: "move",
            from_storage_idx: from_storage_idx,
            to_storage_idx: to_storage_idx,
            product_list: product_list,
            memo:memo,
        };

        //console.log(data);

                

        if(product_list.length > 0){
            saveOutput("move",data,"");

        }else{
            showToast('선택된 품목이 없습니다');
        }
    });


    $(document).on("keypress","input[name='safe_count']",function(e) {

        if(e.keyCode == 13){
            save_safe();
        }
    });

    $(document).on("click","button.btn_save_safe",function() {
        save_safe();
    });


     function save_safe(){
        var storage_idx = $("#safe_storage").val();

        if(storage_idx > 0){
           //
        }else{

            showToast('창고를 선택해주세요');
            return;
        }

        var product_list = Array();

        $("ul.ul_product_list").find("li").each(function(index) {
            var product_idx = $(this).attr("product_idx");
            var cnt = parseInt($(this).find("input[name='safe_count']").val());
            var old_cnt = parseInt($(this).find("input[name='safe_count']").attr("old_value"));

            var pcnt = {product_idx,cnt};
            if(cnt != null && old_cnt != null && cnt != old_cnt && cnt >= 0){
                product_list.push(pcnt);
            }

        });

        var data = {
            mode: "safe",
            storage_idx: storage_idx,
            product_list: product_list

        };

        console.log(data);

                

        if(product_list.length > 0){
            saveOutput("safe",data,storage_idx);

        }else{
            showToast('변경된 품목이 없습니다');
        }
     }
    
    
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

    
    $(document).on("change",".safe_count",function() {
            var cnt = $(this).val();
            if(cnt < 0){
                $(this).val("0");
            }
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
        }else if(this_id == "safe_storage" && data.id >= 0){
            load_safety("view",data.id);
            $(".title_left").find("h3").html("안전재고설정 : "+data.text);
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




function select_li($li){

    //var product_idx = $li.find("input[name='product']").val();
    var product_idx = $li.attr("product_idx");

    var product_name = $li.find(".li_product_name").text();
    var current_count = $li.find(".current_storage_cnt").text();

    var $checkbox = $li.find('input.icheck');

    if (!$checkbox.prop('checked')) {

        //$li.find(".icheckbox_flat-green").removeClass("checked");
        $("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"']").remove();
        
    }else{
        //$li.find(".icheckbox_flat-green").addClass("checked");

        if($("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"']").length == 0){
            var $product_li = $("#product_li_add_sample").find("li").clone(true);
            $product_li.attr("product_idx",product_idx);
            $product_li.find(".li_product_name").text(product_name);
            $product_li.find(".old_current_count").text(current_count);

            $("ul.ul_product_list_right").append($product_li);
            $("ul.ul_product_list_right input.icheck").iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green',
                increaseArea: '20%' // optional
            });

        }
   }

   reset_total();
}



function saveNew(dataArray){

    var url = "./contents/sfullfillment/storage_input/api/save_product_info.php";
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




function saveOutput(mode,dataArray,storage_idx){

    if(mode == "output"){
        var url = "./contents/sfullfillment/storage_input/api/save_product_output.php";

    }else if(mode == "move"){
        var url = "./contents/sfullfillment/storage_input/api/save_product_move.php";

    }else if(mode == "adjust"){
        var url = "./contents/sfullfillment/storage_input/api/save_product_adjust.php";

    }else if(mode == "safe"){
        var url = "./contents/sfullfillment/storage_input/api/save_storage_safe.php";

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

                    if(mode == "safe"){

                        showToast("저장되었습니다");
                        load_safety("view",storage_idx);
                        return;
                    }

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
                        if(check_null(pl.sum_in_count) != 0){
                            $product_li.find(".plus_storage_cnt_minus").text("-"+pl.sum_in_count).removeClass("hide");
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



function saveOutputEmpty(mode,dataArray,$obj){


    if(mode == "empty"){
        var url = "./contents/sfullfillment/storage_input/api/save_product_adjust.php";

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

                    if(mode == "empty"){

                        var msg = result.msg;

                        showToast(msg);

                        if(dataArray.zero_count == "0"){
                            $obj.text("0");

                        }else if(dataArray.zero_count == "zero"){
                            $obj.text("--");

                        }

                        return;
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


function load_product(storage_idx){
    

    var mode = $(".right_set").attr("mode");

    var url = "./contents/sfullfillment/storage_input/api/get_product_info.php";
    var str = "mode="+mode+"&storage_idx="+storage_idx; //

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

                        if(pl.zero_count == "zero" || pl.zero_count == null || pl.zero_count == ""){
                            $product_li.find(".current_storage_cnt").text("--");

                        }else{
                            $product_li.find(".current_storage_cnt").text(check_null(pl.sum_current_count));

                        }
                        if(check_null(pl.sum_in_count) != 0){
                            if(mode == "plus"){
                                $product_li.find(".plus_storage_cnt").text("+"+pl.sum_in_count).removeClass("hide");
                            }else if(mode == "minus"){
                                $product_li.find(".plus_storage_cnt_minus").text("-"+pl.sum_in_count).removeClass("hide");
                            }
                        }

                        $("ul.ul_product_list").append($product_li);



                    }


                    $("ul.ul_product_list input.icheck").iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green',
                        increaseArea: '20%' // optional
                    });


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





function load_safety(mode,storage_idx){
    


    var url = "./contents/sfullfillment/storage_input/api/get_storage_safe.php";
    var str = "mode="+mode+"&storage_idx="+storage_idx; //

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
                    //$("ul.ul_product_list_right").html("");
                    //reset_total();
                    //$("#memo").val("");

                    for(var i=0;i<obj.storage_safe_list.length;i++){
                         var pl = obj.storage_safe_list[i];
                         var $product_li = $("#product_li_sample").find("li").clone(true);

                        $product_li.attr("product_idx",pl.product_idx);
                        $product_li.find(".li_product_name").text(pl.product_name);
                        $product_li.find(".current_storage_cnt").text(check_null(pl.current_count));
                        $product_li.find("input[name='safe_count']").val(check_null(pl.safe_count));
                        $product_li.find("input[name='safe_count']").attr("old_value",check_null(pl.safe_count));
                        if(pl.safe_count > 0 && pl.safe_count > pl.current_count){
                            $product_li.find("input[name='safe_count']").addClass("safe_danger");
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





function get_barcode_product(qrcode){

    console.log("changed:"+qrcode);

    var qr_length = qrcode.length;
    if(qr_length < 13){
        return;
    }

    //beep 사운드 출력
    var audio = new Audio('./contents/qrRead/beep-07a.mp3'); 
    audio.play();


    var url = "./contents/sfullfillment/storage_input/api/get_barcode_product.php";
    var str = "qrcode="+qrcode; //

    console.log(url);
    console.log(str);

    $("#barcode_input").val("");


    $.ajax( { 
            type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
            success: function(result){
                
                var result_status = result.status;
                if(result_status == 1)
                {
                    var obj = result.data.product_info;

                    // obj = JSON.stringify(obj); 
                    // obj = JSON.parse(obj);
                    console.log(obj)

                    if(obj == null){
                        toast('없는 상품입니다.');
                        //$("#barcode_input").val("");
                    }else{


                        var product_idx = obj.product_idx;
                        var product_name = obj.product_name;
                        //var current_count = $(this).find(".current_storage_cnt").text();
                        //speech(product_name);
                
                
                        if($("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"']").length == 0){
                            var $product_li = $("#product_li_add_sample").find("li").clone(true);
                            $product_li.attr("product_idx",product_idx);
                            $product_li.find(".li_product_name").text(product_name);
                            $product_li.attr("qr","ok");
                            //$product_li.find(".old_current_count").text(current_count);
                
                            $("ul.ul_product_list_right").append($product_li);
                        }else{
                            var old_cnt = $("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"'] input[name='cnt']").val();
                            var new_cnt = parseInt(old_cnt) + 1;
                            $("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"'] input[name='cnt']").val(new_cnt);
                        }
                
                
                        reset_total();

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









