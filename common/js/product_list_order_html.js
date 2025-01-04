





function select_li($this){
    //var product_idx = $this.find("input[name='product']").val();



    var client_product_idx = $this.attr("client_product_idx");
    var product_part= $this.attr("product_part");
    var product_idx= $this.attr("product_idx");

    var product_name = $this.find(".li_product_name").html();

    var client_price_sum = $this.attr("client_price_sum");
    var client_price = $this.attr("client_price");
    var client_price_tax= $this.attr("client_price_tax");

    var product_options= $this.attr("options");


    //console.log("product_options:"+product_options);
    

    var $checkbox = $this.find('input.icheck');

    if (!$checkbox.prop('checked')) {

            $("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"'][product_part='"+product_part+"']").remove();

    }else{


        if($("ul.ul_product_list_right").find("li[product_idx='"+product_idx+"'][product_part='"+product_part+"']").length == 0){
            var $product_li = $("#product_li_add_sample").find("li").clone(true);

            $product_li.attr("product_idx",product_idx);

            $product_li.attr("client_product_idx",client_product_idx);
            $product_li.attr("product_part",product_part);
            $product_li.find(".li_product_name").html(product_name);

            $product_li.attr("client_price_sum",client_price_sum);
            $product_li.attr("client_price",client_price);


            if(product_part == "sangjo"){
                $product_li.find(".rl_option_price").html("");

            }

            if(product_options != undefined && product_options != ""){
                var $options = "<option>옵션선택</option>";
                var product_options_ex = product_options.split("/");
                if(product_options_ex.length > 0){
                    for(var i=0;i<product_options_ex.length;i++){
                        $options += "<option value='"+product_options_ex[i]+"'>"+product_options_ex[i]+"</option>";
                    }
                    $product_li.find(".rl_options").find("select[name='options']").html($options);
                }else{
                    //$product_li.find(".rl_option_price").html("");

                    $product_li.find(".rl_options").html("");

                }




            }else{
                //$product_li.find(".rl_option_price").html("");
                $product_li.find(".rl_options").html("");

            }


            $product_li.attr("client_price_tax",client_price_tax);

            $product_li.find(".client_price").text(number_format(client_price));
            $product_li.find(".client_price_tax").text(number_format(client_price_tax));

            $("ul.ul_product_list_right").append($product_li);
        }



        $("ul.ul_product_list_right input.icheck").iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green',
            increaseArea: '20%' // optional
        });
   }

   reset_total();

}




function reset_product_list(obj){
    

    $("ul.ul_product_list li:not(.li_left_header)").remove();
    $("ul.ul_product_list_right li:not(.li_right_header)").remove();

    for(var i=0;i<obj.product_list.length;i++){
        var pl = obj.product_list[i];


        var $product_li = $("#product_li_sample").find("li").clone(true);

        $product_li.attr("product_part",pl.product_part);

        $product_li.attr("product_idx",pl.product_idx);
        $product_li.attr("client_product_idx",pl.client_product_idx);
        $product_li.attr("client_price_sum",parseInt(pl.client_price_sum));
        $product_li.attr("options",pl.options);


        var product_name_addition = "";
        if(pl.product_part == "sangjo"){
            product_name_addition = "<span class='blue'>(상조용품)</span> ";
        }
        var pn = "<span class='pn'>"+pl.product_name+"</span>";

        $product_li.find(".li_product_name").html(product_name_addition+pn);
        //$product_li.find(".current_storage_cnt").text(check_null(pl.sum_current_count));
        $product_li.find(".client_price").text(number_format(parseInt(pl.client_price)));
        $product_li.find(".client_price_tax").text(number_format(parseInt(pl.client_price_tax)));
        $product_li.attr("client_price",parseInt(pl.client_price));
        $product_li.attr("client_price_tax",parseInt(pl.client_price_tax));



        $("ul.ul_product_list").append($product_li);


   }

   $("ul.ul_product_list input.icheck").iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green',
        increaseArea: '20%' // optional
    });



   reset_total();


}





function reset_total(){




    var total = 0;
    var total_client_price_sum = 0;
    var total_client_price = 0;
    var total_client_price_tax = 0;


    $("ul.ul_product_list_right").find("li:not(.li_right_header)").each(function(index) {
        var cnt = parseInt($(this).find("input[name='cnt']").val());
        if(isNaN(cnt)){
            cnt = 0;
        }

        var client_price = parseInt($(this).attr("client_price"));
        var client_price_tax = parseInt($(this).attr("client_price_tax"));

        if($(this).find("select[name='option_price']").length > 0){
            var option_price = parseInt($(this).find("select[name='option_price']").val());
        }else if($(this).find("input[name='option_price']").length > 0){
            var option_price = parseInt($(this).find("input[name='option_price']").val()); //직접입력
            if(isNaN(option_price)){
                option_price = 0;
            }
            console.log('직접입력값:'+option_price);

        }else{
            option_price = 0;
        }
        console.log('option_price:'+option_price);


        total += cnt;
        total_client_price += (client_price+option_price)*cnt;

        if(client_price_tax > 0 && option_price > 0){
            client_price_tax = client_price_tax + option_price*0.1; //옵션상품의 부가세를 추가한다.
        }
        total_client_price_tax += client_price_tax*cnt;



    });


    console.log(total);
    console.log(total_client_price);
    console.log(total_client_price_tax);


    var li_cnt = $("ul.ul_product_list_right").find("li:not(.li_right_header)").length;//타이틀라인 제거
    $("ul.selected_total_ul").find(".selected_product_num").text(li_cnt);


    //var total_client_price = parseInt(total_client_price_sum/1.1);
    var total_client_price_sum = total_client_price + total_client_price_tax;


    $("ul.selected_total_ul").find(".order_count_sum").text(total+" 개");
    $("ul.selected_total_ul").find(".total_client_price_sum").text(number_format(total_client_price_sum)+" 원");
    $("ul.selected_total_ul").find(".total_client_price").text(number_format(total_client_price)+" 원");
    $("ul.selected_total_ul").find(".total_client_price_tax").text(number_format(total_client_price_tax)+" 원");

    $("ul.selected_total_ul").find(".order_count_sum").attr("order_count_sum",total);
    $("ul.selected_total_ul").find(".total_client_price_sum").attr("total_client_price_sum",total_client_price_sum);
    $("ul.selected_total_ul").find(".total_client_price").attr("total_client_price",total_client_price);
    $("ul.selected_total_ul").find(".total_client_price_tax").attr("total_client_price_tax",total_client_price_tax);
    
}
