<link href="./contents/bill/css/bill.css?time=?ver=<?=time()?>" rel="stylesheet">


<div class="right_col" role="main" style="min-height: 682px;">

<div class="">
  <div class="page-title">
    <div class="title_left">
      <h3>거래명세서</h3>
    </div>

    <div class="title_right">
      <div class="pull-right">
        <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
        <i class="glyph-icon icon-search"></i>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>

  <div class="row">
    <div class="col-md-12">
      <div class="x_panel">
        <div class="x_title">
          <h2><small></small></h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a href="#"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Settings 1</a>
                </li>
                <li><a href="#">Settings 2</a>
                </li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-close"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>




            <div class="x_content" id="detail_section_bill"   consulting_idx="<?=$manager_info['consulting_idx']?>">

                <div class="x_title">
                    <div class="col-md-4 col-sm-4  col-xs-12">
                        <h2 style="padding:10px;">목록 </h2>
                    </div>
                    <div class="col-md-8 col-sm-8  col-xs-12">
                    <div class="float_right yyyymm_div">
                        <input id="yyyymm_right_bill" type="text" class="form-control input-sm" placeholder="<?=date("Y년 m월",time())?>"><button class="btn btn-dark btn-xs btn_print btn_print_bill" style="padding: 4px 10px 4px 10px !important; margin: -4px 0px -1px 10px !important;">인쇄</button>
                    </div>
                    </div>
                    
                    <div class="clearfix"></div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                      <?
                      // if(file_exists("./contents/bill/html/detail_html_bill.php")){
                      //     require("./contents/bill/html/detail_html_bill.php");
                      if(file_exists($_SERVER["DOCUMENT_ROOT"]."/common/html/detail_html_bill.php")){
                        require($_SERVER["DOCUMENT_ROOT"]."/common/html/detail_html_bill.php");
    
                      }else{
                          //require('./contents/'.$folder_name.'/html/detail.php');

                      }?>
                    </div>
                </div>
            </div>

      </div>
    </div>
  </div>
</div>

<!-- footer content -->
<footer>
  <div class="copyright-info">
    <p class="pull-right">
    </p>
  </div>
  <div class="clearfix"></div>
</footer>
<!-- /footer content -->

</div>

<script src="./js/monthPicker/MonthPicker.js?time=<?=time()?>"></script>

<script src="./contents/bill/js/page.js?time=<?=time()?>"></script>

<script>

var spart = '<?=$_REQUEST['part']?>';  //fl.su.sj

var yyyymm = $("#yyyymm_right_bill").val();

if(yyyymm == ""){

  var today = new Date();
  var year = today.getFullYear();
  var month = ('0' + (today.getMonth() + 1)).slice(-2);
  //var day = ('0' + today.getDate()).slice(-2);

  //var dateString = year + '-' + month  + '-' + day;

  yyyymm = year+month;
}

view_bill('<?=$manager_info['consulting_idx']?>',yyyymm);

</script>