<?php

$page_title = "총무관리 등록하기";


$dbcon = $db->cmu_connect();

if($_REQUEST['cmu_idx'] != ""){
    $sel_con = mysqli_query($dbcon, "select * from cmu where cmu_idx='".$_REQUEST['cmu_idx']."' ") or die(mysqli_error($dbcon));
    $sel_con_num = mysqli_num_rows($sel_con);
    
    if ($sel_con_num > 0) {
        $cmu_info = mysqli_fetch_assoc($sel_con);
    }

}



$sel_jisa = mysqli_query($dbcon, "select * from hrm.jisa where display='1' order by jisa  ") or die(mysqli_error($dbcon));
$sel_jisa_num = mysqli_num_rows($sel_jisa);

$jisa_list = array();
if ($sel_jisa_num > 0) {
   //$data = mysqli_fetch_assoc($sel);
   while($data_jisa = mysqli_fetch_assoc($sel_jisa)) {
      array_push($jisa_list,$data_jisa);
   }
}




?>


<style>

</style>
<link href="contents/cmu/css/input.css?ver=<?=time()?>" rel="stylesheet" type="text/css" />
<script src="contents/cmu/js/input.js?ver=<?=time()?>"></script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="page-title">
      <div class="title_left">
        <h3>
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              <?=$page_title?>
              <small>

              </small>
          </h3>
          

      </div>


      


    </div>
    <div class="clearfix"></div>

    <div class="row">

      <div class="col-md-12 col-sm-12 col-xs-12 input_area">
        <div class="x_panel">
          



            <div class="x_title">
                <div class="col-md-6">
                    <h2 style="padding:10px;">관리정보<small id=""></small> </h2>
                </div>

                <div class="clearfix"></div>
            </div>



            <div class="x_content">
                  <br>
                  <form data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">

                  <div class="col-md-6 col-sm-6 col-xs-6">


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">관리명 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="title" name="title" value="<?=$cmu_info['title']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="manager">담당자
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="manager" name="manager" value="<?=$cmu_info['manager']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expense">비용
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="expense" name="expense" value="<?=$cmu_info['expense']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="amount">수량
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="amount" name="amount" value="<?=$cmu_info['amount']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        
                    
                  </div>


                  <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_jisa">담당지사 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select id="t_jisa" name="t_jisa"  class="form-control col-md-7 col-xs-12" >
                                    <? 
                                        for ($i=0;$i<count($jisa_list);$i++ )
                                        {?>
                                            <option value="<?=$jisa_list[$i]['jisa_idx']?>" <? if($jisa_list[$i]['jisa_idx'] == $cmu_info['jisa_idx']){echo "selected";}?>><?=$jisa_list[$i]['jisa']?></option> 
                                        <?}
                                    ?>
                                </select>

                                <ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="c_company">담당업체
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="c_company" name="c_company" value="<?=$cmu_info['c_company']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_date">결제일
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="payment_date" name="payment_date" value="<?=$cmu_info['payment_date']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>

                        
                        





                  </div>


                  </form>
            </div>







           <div class="x_content">
                <br>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
                        <button type="submit" class="btn btn-primary btn_cancel"  cmu_idx="<?=$cmu_info['cmu_idx']?>">취소</button>
                        <button type="submit" class="btn btn-success btn_save" mode="<?=$input_part?>" cmu_idx="<?=$cmu_info['cmu_idx']?>">저장</button>
                    </div>
                </div>
            </div>



        </div>







        <!-- footer content -->
        <footer>
          <div class="copyright-info">
            <p class="pull-right"></a>
            </p>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->

      </div>
      <!-- /page content -->
    </div>

  </div>





</div>