$(document).ready(function(){

   $(document).on("keyup","#product_filter",function() {
       
       var keyword = $(this).val();
    
       $(".x_content table tbody").find("tr").hide();

       $(".x_content table tbody").find("td:contains('"+keyword+"')").closest("tr").show();


   });




   $('.datatable_wrap').css('height', $(window).height() - 250 );   

  $(window).resize(function() {        
    $('.datatable_wrap').css('height', $(window).height() - 250 );   
  });

  /*
  $(document).on("click",".btn_excel_download",function(){
       showToast("기능 준비중입니다");
  });
   */



});



