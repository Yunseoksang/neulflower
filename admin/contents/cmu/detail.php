<link href="contents/cmu/css/style.css?ver=<?=time()?>" rel="stylesheet" type="text/css" />
<script src="contents/cmu/js/page.js?ver=<?=time()?>"></script>
<script src="contents/cmu/js/detail.js?ver=<?=time()?>"></script>

<style>

</style>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>

    <div class="row">


      <!--우측 상세내역 영역   --->
      <div class="col-md-12 col-sm-12 col-xs-12 " id="detail_section">
        <div class="x_panel">
            <div class="x_title">
                <div class="col-md-12">
                    <h2 style="padding:10px;">상세정보 </h2>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
<?
if(file_exists("./contents/cmu/html/detail_html.php")){
    require("./contents/cmu/html/detail_html.php");

}else{
    //require('./contents/'.$folder_name.'/html/detail.php');

}?>
                    </div>
                </div>
            </div>
        </div>
        
      </div>
      <!-- /page content -->
    </div>

  </div>



