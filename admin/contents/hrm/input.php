<?php

$page_title = "인사관리 추가하기";


$dbcon = $db->hrm_connect();

if($_REQUEST['hrm_idx'] != ""){
    $sel_con = mysqli_query($dbcon, "select * from hrm where hrm_idx='".$_REQUEST['hrm_idx']."' ") or die(mysqli_error($dbcon));
    $sel_con_num = mysqli_num_rows($sel_con);
    
    if ($sel_con_num > 0) {
        $hrm_info = mysqli_fetch_assoc($sel_con);
    }

}

if($hrm_info['date_of_employment'] != "" && $hrm_info['date_of_employment'] != null){

    $date_of_employment = $hrm_info['date_of_employment'];
}else{
    $date_of_employment = date('Y-m-d');
}


if($hrm_info['date_of_resignation'] != "" && $hrm_info['date_of_resignation'] != null){

    $date_of_resignation = $hrm_info['date_of_resignation'];
}else{
    $date_of_employment = "";
}


$sel_jisa = mysqli_query($dbcon, "select * from jisa where display='1' order by jisa  ") or die(mysqli_error($dbcon));
$sel_jisa_num = mysqli_num_rows($sel_jisa);

$jisa_list = array();
if ($sel_jisa_num > 0) {
   //$data = mysqli_fetch_assoc($sel);
   while($data_jisa = mysqli_fetch_assoc($sel_jisa)) {
      array_push($jisa_list,$data_jisa);
   }
}




$sel_organization = mysqli_query($dbcon, "select * from organization where display='1' order by organization  ") or die(mysqli_error($dbcon));
$sel_organization_num = mysqli_num_rows($sel_organization);

$organization_list = array();
if ($sel_organization_num > 0) {
   //$data = mysqli_fetch_assoc($sel);
   while($data_organization = mysqli_fetch_assoc($sel_organization)) {
      array_push($organization_list,$data_organization);
   }
}



$sel_office = mysqli_query($dbcon, "select * from office where display='1' order by office  ") or die(mysqli_error($dbcon));
$sel_office_num = mysqli_num_rows($sel_office);

$office_list = array();
if ($sel_office_num > 0) {
   //$data = mysqli_fetch_assoc($sel);
   while($data_office = mysqli_fetch_assoc($sel_office)) {
      array_push($office_list,$data_office);
   }
}



?>


<style>

</style>
<link href="contents/hrm/css/input.css?ver=<?=time()?>" rel="stylesheet" type="text/css" />
<script src="contents/hrm/js/input.js?ver=<?=time()?>"></script>

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
                    <h2 style="padding:10px;">기본정보<small id=""></small> </h2>
                </div>

                <div class="clearfix"></div>
            </div>



            <div class="x_content">
                  <br>
                  <form data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">

                  <div class="col-md-6 col-sm-6 col-xs-6">


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">이름 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="name" name="name" value="<?=$hrm_info['name']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_organization">소속기관 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select id="t_organization" name="t_organization"  class="form-control col-md-7 col-xs-12" >
                                    <? 
                                        for ($i=0;$i<count($organization_list);$i++ )
                                        {?>
                                            <option value="<?=$organization_list[$i]['organization_idx']?>"  <? if($organization_list[$i]['organization_idx'] == $hrm_info['organization_idx']){echo "selected";}?>><?=$organization_list[$i]['organization']?></option> 
                                        <?}
                                    ?>
                                </select>

                                <ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="job_position">직책
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="job_position" name="job_position" value="<?=$hrm_info['job_position']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="work_type">근로유형 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">

                                <select id="work_type" name="work_type"  class="form-control col-md-7 col-xs-12" >
                                    <option value="스탭"  <? if($hrm_info['work_type'] == "스탭"){echo "selected";}?> >스탭</option> 
                                    <option value="근로지원인" <? if($hrm_info['work_type'] == "근로지원인"){echo "selected";}?> >근로지원인</option> 
                                    <option value="적응지도" <? if($hrm_info['work_type'] == "적응지도"){echo "selected";}?> >적응지도</option> 
                                    <option value="경증장애사원" <? if($hrm_info['work_type'] == "경증장애사원"){echo "selected";}?> >경증장애사원</option> 
                                    <option value="중증장애사원" <? if($hrm_info['work_type'] == "중증장애사원"){echo "selected";}?> >중증장애사원</option> 
                                </select>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date_of_employment">입사일 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="date" id="date_of_employment" name="date_of_employment" value="<?=$hrm_info['date_of_employment']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="disabled_type">장애유형 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="disabled_type" name="disabled_type" value="<?=$hrm_info['disabled_type']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tel">전화번호 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="tel" name="tel" value="<?=$hrm_info['tel']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="affiliate_organization">소개기관 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="affiliate_organization" name="affiliate_organization" value="<?=$hrm_info['affiliate_organization']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                    
                  </div>


                  <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_jisa">관리지사 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select id="t_jisa" name="t_jisa"  class="form-control col-md-7 col-xs-12" >
                                    <? 
                                        for ($i=0;$i<count($jisa_list);$i++ )
                                        {?>
                                            <option value="<?=$jisa_list[$i]['jisa_idx']?>" <? if($jisa_list[$i]['jisa_idx'] == $hrm_info['jisa_idx']){echo "selected";}?>><?=$jisa_list[$i]['jisa']?></option> 
                                        <?}
                                    ?>
                                </select>

                                <ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="t_office">근무지 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select id="t_office" name="t_office"  class="form-control col-md-7 col-xs-12" >
                                    <? 
                                        for ($i=0;$i<count($office_list);$i++ )
                                        {?>
                                            <option value="<?=$office_list[$i]['office_idx']?>" <? if($office_list[$i]['office_idx'] == $hrm_info['office_idx']){echo "selected";}?>><?=$office_list[$i]['office']?></option> 
                                        <?}
                                    ?>
                                </select>

                                <ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="job_grade">직급
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="job_grade" name="job_grade" value="<?=$hrm_info['job_grade']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="work_time">근무시간 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="work_time" name="work_time" value="<?=$hrm_info['work_time']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date_of_resignation">퇴사일 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="date" id="date_of_resignation" name="date_of_resignation" value="<?=$hrm_info['date_of_resignation']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="disabled_grade">장애등급 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select id="disabled_grade" name="disabled_grade"  class="form-control col-md-7 col-xs-12" >
                                    <option value="">----</option> 
                                    <option value="경도"  <? if($hrm_info['disabled_grade'] == "경도"){echo "selected";}?>>경도</option> 
                                    <option value="고도" <? if($hrm_info['disabled_grade'] == "고도"){echo "selected";}?>>고도</option> 

                                </select>



                                <ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tel_guardians">보호자전화번호 
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="tel_guardians" name="tel_guardians" value="<?=$hrm_info['tel_guardians']?>"  required="" class="form-control col-md-7 col-xs-12" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>






                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">주소 
                            </label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="address" name="address" value="<?=$hrm_info['address']?>"  required="" class="form-control col-md-7 col-xs-12" style="max-width:910px;" ><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="memo">메모 
                            </label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <textarea id="memo"  name="memo"  required="" class="form-control col-md-12 col-xs-12" style="max-width:910px;height:90px;"><?=$hrm_info['memo']?> </textarea><ul class="parsley-errors-list" ></ul>
                            </div>
                        </div>
                        
                  </div>


                  </form>
            </div>







           <div class="x_content">
                <br>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
                        <button type="submit" class="btn btn-primary btn_cancel"  hrm_idx="<?=$hrm_info['hrm_idx']?>">취소</button>
                        <button type="submit" class="btn btn-success btn_save" mode="<?=$input_part?>" hrm_idx="<?=$hrm_info['hrm_idx']?>">저장</button>
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