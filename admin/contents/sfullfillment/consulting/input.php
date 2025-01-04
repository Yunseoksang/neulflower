<?php

if($_REQUEST['consulting_idx'] != ""){
    $sel_con = mysqli_query($dbcon, "select * from consulting where consulting_idx='".$_REQUEST['consulting_idx']."' ") or die(mysqli_error($dbcon));
    $sel_con_num = mysqli_num_rows($sel_con);
    
    if ($sel_con_num > 0) {
        $company_info = mysqli_fetch_assoc($sel_con);
    }


    $sel_manager = mysqli_query($dbcon, "select * from manager where consulting_idx='".$_REQUEST['consulting_idx']."' ") or die(mysqli_error($dbcon));
    $sel_manager_num = mysqli_num_rows($sel_manager);
    


}

?>


<style>

</style>
<link href="contents/consulting/css/input.css?ver=<?=time()?>" rel="stylesheet" type="text/css" />
<script src="contents/consulting/js/input.js?ver=<?=time()?>"></script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="page-title">
      <div class="title_left">
        <h3>
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              거래처 추가하기
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
                    <h2 style="padding:10px;">주요확인정보<small id="total_list_cnt"></small> </h2>
                </div>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                  <br>

                  <form data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">

                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company_name">회사명 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="company_name"  name="company_name" value="<?=$company_info['company_name']?>" required="" class="form-control col-md-7 col-xs-12"><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employees">직원수 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="employees" name="employees" value="<?=$company_info['employees']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="employment_fee">고용부담금 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="employment_fee" name="employment_fee" value="<?=$company_info['employment_fee']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                            


                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="trading_items">거래품목 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="trading_items" name="trading_items" value="<?=$company_info['trading_items']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tradeable_items">거래가능품목 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="tradeable_items" name="tradeable_items" value="<?=$company_info['tradeable_items']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>

                    </div>

                   <div class="clearfix"></div>



                  </form>
            </div>






            <div class="x_title">
                <div class="col-md-6">
                    <h2 style="padding:10px;">회사기본정보<small id="total_list_cnt"></small> </h2>
                </div>

                <div class="clearfix"></div>
            </div>



            <div class="x_content">
                  <br>
                  <form data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">

                  <div class="col-md-6 col-sm-6 col-xs-6">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="biz_num">사업자번호 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="biz_num"  name="biz_num" value="<?=$company_info['biz_num']?>"  required="" class="form-control col-md-7 col-xs-12"><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="corp_num">법인번호 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="corp_num" name="corp_num" value="<?=$company_info['corp_num']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ceo_name">대표자명 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="ceo_name" name="ceo_name" value="<?=$company_info['ceo_name']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tel">전화번호 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="tel" name="tel" value="<?=$company_info['tel']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fax">팩스번호 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="fax" name="fax" value="<?=$company_info['fax']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>


                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="biz_part">업태 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="biz_part"  name="biz_part" value="<?=$company_info['biz_part']?>"  required="" class="form-control col-md-7 col-xs-12"><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="biz_type">업종 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="biz_type" name="biz_type" value="<?=$company_info['biz_type']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">주소 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="address" name="address" value="<?=$company_info['address']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="homepage">홈페이지 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="homepage" name="homepage" value="<?=$company_info['homepage']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>



                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="memo">메모 
                            </label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <textarea id="memo"  name="memo"  required="" class="form-control col-md-7 col-xs-12" style="max-width:858px;height:90px;"><?=$company_info['memo']?> </textarea><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        
                  </div>


                  </form>
            </div>





            <div class="x_title">
                <div class="col-md-6">
                    <h2 style="padding:10px;">담당자정보 </h2>
                </div>
                <div class="col-md-6">
                    <h2 class="navbar-right" style="margin-top: 40px;" ><i class="fa fa-plus-square btn_manager_add" title="담당자 추가하기"></i></h2>
                </div>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                  <br>
                  <form data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                    <table class="table table-bordered" id="table_manager">
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th>이름</th>
                                <th>직위</th>
                                <th>부서</th>
                                <th>이메일</th>
                                <th>전화번호</th>
                                <th>휴대폰</th>
                                <th>삭제</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
    if ($sel_manager_num > 0) {
        //$data = mysqli_fetch_assoc($sel);
        $num = 0;
        while($manager_info = mysqli_fetch_assoc($sel_manager)) {
            $num++;
            ?>
            
            <tr manager_idx="<?=$manager_info['manager_idx']?>">
                <th scope="row"><?=$num?></th>
                <td ><input type="text"  name="manager_name"                value="<?=$manager_info['manager_name']?>"  required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_department"           value="<?=$manager_info['manager_department']?>"  required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_position"             value="<?=$manager_info['manager_position']?>"  required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_email"                value="<?=$manager_info['manager_email']?>"  required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_tel"                  value="<?=$manager_info['manager_tel']?>"  required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_hp"                   value="<?=$manager_info['manager_hp']?>"  required="" class="form-control col-xs-12"></td>
                <td class="td_x"><i class="fa fa-close"></i></td>
                
            </tr>
        
        <?}
    }else{?>
            <tr>
                <th scope="row">1</th>
                <td ><input type="text"  name="manager_name"         required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_department"    required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_position"      required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_email"         required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_tel"           required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_hp"            required="" class="form-control col-xs-12"></td>
                <td class="td_x"><i class="fa fa-close"></i></td>
                
            </tr>
            <tr>
                <th scope="row">2</th>
                <td ><input type="text"  name="manager_name"         required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_department"    required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_position"      required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_email"         required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_tel"           required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_hp"            required="" class="form-control col-xs-12"></td>
                <td class="td_x"><i class="fa fa-close"></i></td>
                
            </tr>
            <tr>
                <th scope="row">3</th>
                <td ><input type="text"  name="manager_name"         required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_department"    required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_position"      required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_email"         required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_tel"           required="" class="form-control col-xs-12"></td>
                <td><input type="text"  name="manager_hp"            required="" class="form-control col-xs-12"></td>
                <td class="td_x"><i class="fa fa-close"></i></td>
                
            </tr>




    <?}

?>


                        </tbody>
                    </table>
                  </form>
            </div>




           <div class="x_content">
                <br>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
                        <button type="submit" class="btn btn-primary btn_cancel">취소</button>
                        <button type="submit" class="btn btn-success btn_save" consulting_idx="<?=$company_info['consulting_idx']?>">저장</button>
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