$(document).ready(function(){



    $(document).on("keyup","#product_filter",function() {
       
      var keyword = $(this).val();
  
      $(".x_content table tbody").find("tr").hide();

      $(".x_content table tbody").find("td:contains('"+keyword+"')").closest("tr").show();


    });


   
    $(document).on("keyup","#storage_filter",function() {
       
      var keyword = $(this).val();


      $(".dataTables_scrollHeadInner table tr th").removeClass("th_filter_bg");
      $("#datatable_static tr td").removeClass("td_filter_bg");

      if(keyword == ""){
        return;
      }


      //$(".dataTables_scrollHeadInner table tr th").hide();
      //$(".dataTables_scrollHeadInner table tr th").slice(0,3).show();

      
      // $("#datatable_static").find("tr").each(function(index, value) {
      //   $(this).find("td").hide();
      //   $(this).find("td").slice(0,3).show();
      // });
      


      $(".dataTables_scrollHeadInner table tr th:contains('"+keyword+"')").each(function(index, value) {
        var this_th_index = $(".dataTables_scrollHeadInner table tr th").index($(this));
        //$(".dataTables_scrollHeadInner table tr th").eq(this_th_index).show();
        $(".dataTables_scrollHeadInner table tr th").eq(this_th_index).addClass("th_filter_bg");
        $(".dataTables_scrollHeadInner table tr th").eq(3).after($(".dataTables_scrollHeadInner table tr th").eq(this_th_index));


        $("#datatable_static").find("tr").each(function(index, value) {
          //$(this).find("td").eq(this_th_index).show();
          $(this).find("td").eq(this_th_index).addClass("td_filter_bg");
          $(this).find("td").eq(3).after($(this).find("td").eq(this_th_index));

        });

      });

      //$(".dataTables_scrollHeadInner table tr th").slice(3,5).hide();

      // $("#datatable_static").find("tr").each(function(index, value) {
      //   $(this).find("td").slice(3,5).hide();
      // });
      
  
      // $(".x_content table tbody").find("tr").hide();

      // $(".x_content table tbody").find("td:contains('"+keyword+"')").closest("tr").show();

    });




   $('.datatable_wrap').css('height', $(window).height() - 250 );   

  $(window).resize(function() {        
    $('.datatable_wrap').css('height', $(window).height() - 250 );   
  });


  $('.x_panel').css('height','900px' );   
  $('.right_col').css('overflow','auto' );   



  /*
  $(document).on("click",".btn_excel_download",function(){
       showToast("기능 준비중입니다");
  });
   */



});



