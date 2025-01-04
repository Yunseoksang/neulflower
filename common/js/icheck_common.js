/// 좌우 icheckbox 모두 있는 구조
$(document).ready(function() {

    $(document).on("click","input.icheck").iCheck({
        checkboxClass: 'icheckbox_flat-green'
    });

    // Handle li click to toggle iCheck checkbox
    $(document).on("click", "ul.ul_product_list li", function(event) {
        // Prevent the click event from propagating to the checkbox itself
        if (!$(event.target).is('input.icheck')) {
            var $checkbox = $(this).find('input.icheck');
            if ($checkbox.length) {
                if ($checkbox.prop('checked')) {
                    $checkbox.iCheck('uncheck');
                    console.log('uncheck1');
                } else {
                    $checkbox.iCheck('check');
                    console.log('check1');

                }
            }
        }
    });



    // Select/Deselect all checkboxes
    $(document).on('ifChecked', 'ul.ul_product_list li:not(.li_left_header) .icheck', function(event) { //왼쪽 체크

        var $li = $(this).closest("li");


            select_li($li);


    });

    $(document).on('ifUnchecked', 'ul.ul_product_list li:not(.li_left_header) .icheck', function(event) { //왼쪽 해제

        var $li = $(this).closest("li");

            select_li($li);


    });

    $(document).on('ifUnchecked', 'ul.ul_product_list_right li:not(.li_right_header) .icheck', function(event) { // 오른쪽 해제

        var $li = $(this).closest("li");


        var client_product_idx = $li.attr("client_product_idx");


        // client_product_idx 속성이 없으면 product_idx 속성을 사용
        if (typeof client_product_idx === 'undefined' || client_product_idx == '0' || client_product_idx == '' ) {
            //window.alert('client_product_idx1:'+client_product_idx);
            var product_idx = $li.attr("product_idx");
            var $origin_li = $("ul.ul_product_list").find("li[product_idx='"+product_idx+"']");

        }else{
            // client_product_idx 값을 사용하여 해당 li 요소를 찾기
            //window.alert('client_product_idx2:'+client_product_idx);
            var $origin_li = $("ul.ul_product_list").find("li[client_product_idx='"+client_product_idx+"']");
        }


        var $checkbox = $origin_li.find('input.icheck');
        $checkbox.iCheck('uncheck');

    });



    // 왼쪽 전체선택/해제
    $(document).on('ifChecked', 'ul.ul_product_list .select-all', function(event) {
        $('ul.ul_product_list input.icheck').iCheck('check');
    });

    $(document).on('ifUnchecked', 'ul.ul_product_list .select-all', function(event) {
        $('ul.ul_product_list input.icheck').iCheck('uncheck');
    });


    // 오른쪽 전체선택/해제
    $(document).on('ifChecked', 'ul.ul_product_list_right  li.li_right_header input.select-all', function(event) {
        console.log("check");
        $('ul.ul_product_list_right input.icheck').iCheck('check');
    });

    $(document).on('ifUnchecked', 'ul.ul_product_list_right li.li_right_header input.select-all', function(event) {
        console.log("uncheck");
        $('ul.ul_product_list_right input.icheck').iCheck('uncheck');
    });


});