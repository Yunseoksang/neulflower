$(document).ready(function(){


    //수정
    $(document).on("click",".datatable .btn_modify",function(){
        

    });


    //취소
    $(document).on("click",".datatable .btn_cancel",function(){

    });


    //삭제
    $(document).on("click",".datatable .btn_delete",function(){

    });


    //저장
    $(document).on("click",".datatable .btn_save",function(){

        var $tr = $(this).closest("tr");
        var input_amount = $tr.find("td.input_amount").attr("input_amount");
        var error_amount = $tr.find("td.error_amount").attr("error_amount");
        var error_rate = ((error_amount/input_amount)*100).toFixed(1) +"%";


        $tr.find("td.exec_error_rate").html(error_rate);

        console.log("input_amount:"+input_amount);
        console.log("error_amount:"+error_amount);

        console.log("error_rate:"+error_rate);

    });


    //셀 더블클릭하면 select 박스 보여지게, 숨기게.
    $(document).on("dblclick",".selectable,.editable",function(){

    });


    //select box change시 즉시 저장
    $(document).on("change","tr td.selectable select",function(){

    });



    //추가
    $(document).on("click",".btn_add",function(e){

    });

    



});

