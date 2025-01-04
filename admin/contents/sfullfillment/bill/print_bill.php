
<style>
    body{
        background:white;
    }
    .title_left{
      text-align:center;
    }

    hr{
      float: unset !important;
    }

</style>

<!-- page content -->
<div class="" role="main">
  <div class="">

  <div class="page-title">
      <div class="title_left">
          <h3 id="title_line">
              
              
          </h3>
          <hr>

      </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">



      <!--우측 상세내역 영역2   --->
      <div class="col-md-12  col-sm-12  col-xs-12 hide" id="detail_section_bill">
        <div class="x_panel">
            

            <div class="x_content" id="print_detail_section_bill">
                <div class="row">
                    <div class="col-sm-12">
                      <?

                      if(file_exists($_SERVER["DOCUMENT_ROOT"]."/common/html/detail_html_bill.php")){
                        require($_SERVER["DOCUMENT_ROOT"]."/common/html/detail_html_bill.php");

                      }else{
                          //require('./contents/'.$folder_name.'/html/detail.php');

                      }

                      
                      ?>
                    </div>
                </div>
            </div>
        </div>
        
      </div>
      <!-- /page content -->



    </div>

  </div>






<script>

$(document).ready(function(){


    $("#detail_section_bill").removeClass("hide").show();

    view_bill('<?=$_REQUEST['consulting_idx']?>','<?=$_REQUEST['category1_idx']?>','<?=$_REQUEST['yyyymm']?>');
    

});
</script>