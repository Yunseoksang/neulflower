$(document).ready(function(){


    //수정
    $(document).on("click",".datatable .btn_modify",function(){
        //$last_clicked_td.closest("tr").find("td.storage_idx").append("<button class='btn btn-primary btn-xs'>설정</button>");
        //$last_clicked_td.append("<button class='btn btn-primary btn-xs btn_permission_setting'>권한설정</button>");
    });


    //취소
    $(document).on("click",".datatable .btn_cancel",function(){

    });


    //삭제
    $(document).on("click",".datatable .btn_delete",function(){

    });


    //저장
    $(document).on("click",".datatable .btn_save",function(){

        //var $td = $(this).closest("tr").find("td.category_idx");
        //$td.find("span.etc").text('('+$td.attr("category_idx_new")+')');

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

