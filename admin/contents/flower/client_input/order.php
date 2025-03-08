<?php


$folder_name = "flower/client_input";


$sel_client = mysqli_query($dbcon, "select * from ".$db_consulting.".consulting where consulting_status='계약완료' order by company_name ") or die(mysqli_error($dbcon));
$sel_client_num = mysqli_num_rows($sel_client);



if($_REQUEST['out_order_idx'] != ""){

  $sel3 = mysqli_query($dbcon, "select * from out_order where out_order_idx='".$_REQUEST['out_order_idx']."' ") or die(mysqli_error($dbcon));
  $sel_num3 = mysqli_num_rows($sel3);

  if ($sel_num3 > 0) {
    $out_order_data = mysqli_fetch_assoc($sel3);

    if($out_order_data['out_order_status'] == "배송중" || $out_order_data['out_order_status'] == "주문완료" || $out_order_data['out_order_status'] == "주문취소" ){
        error_exit("데이터를 수정할수 없는 단계입니다.");
        exit;
    }

    $consulting_idx = $out_order_data['consulting_idx'];
    $out_order_part = $out_order_data['out_order_part'];
    if($out_order_part == "상조"){

      $sel4 = mysqli_query($dbcon, "select product_idx,order_count from
      ".$db_sangjo_new.".out_order_client_product where flower_out_order_idx='".$_REQUEST['out_order_idx']."' 
        ") or die(mysqli_error($dbcon));
      $sel_num4 = mysqli_num_rows($sel4);
      
      $p_arr = array();
      if ($sel_num4 > 0) {
          while($data4 = mysqli_fetch_assoc($sel4)) {
              array_push($p_arr,$data4);
          }
      }

      
    }else{
      $sel4 = mysqli_query($dbcon, "select b.product_idx,a.client_product_idx,a.order_count
        from
        (select * from ".$db_flower.".out_order_client_product where out_order_idx='".$_REQUEST['out_order_idx']."' ) a
        left join ".$db_flower.".client_product b 
        on a.client_product_idx=b.client_product_idx 
        ") or die(mysqli_error($dbcon));
      $sel_num4 = mysqli_num_rows($sel4);
      
      $p_arr = array();
      if ($sel_num4 > 0) {
          while($data4 = mysqli_fetch_assoc($sel4)) {
              array_push($p_arr,$data4);
          }
      }


    }


  }

}




// 한국 시간대로 설정
date_default_timezone_set('Asia/Seoul');

// 오늘의 날짜를 YYYY-MM-DD 형식으로 설정
$today_date = date('Y-m-d');

?>


<link href="./contents/<?=$folder_name?>/css/style.css?ver=<?=time()?>" rel="stylesheet">
<script src="/common/js/icheck_common.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->

<script src="./contents/<?=$folder_name?>/js/page.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->
<script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>


<div class="right_col" role="main" style="min-height: 527px;">



        <div class="right_set" mode='minus'>
          <div class="page-title">
            <div class="title_left">
              <h3>
                    주문서 등록
                    <small>
                        
                    </small>
                    
                </h3>
                <hr>
            </div>


            <div class="title_right">
            <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12" style='text-align:right;'></label>
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <select id="order_client" class="select2_single form-control" tabindex="-1" style="display: none;">
                        <option value="">고객사</option>
                      
                        <?php
                        if ($sel_client_num > 0) {
                          while($data_client = mysqli_fetch_assoc($sel_client)) {?>
                            <option value="<?=$data_client['consulting_idx']?>" <? if(isset($consulting_idx) && $consulting_idx == $data_client['consulting_idx']){echo "selected";}?>><?=$data_client['company_name']?></option>

                          
                          <?}
                        }
                        ?>
                          
                        </select>
                        
                      </div>
                    </div>

            </div>
          </div>
          <div class="clearfix"></div>




          <?
           $right_mode = "options";
           include($_SERVER["DOCUMENT_ROOT"].'/common/html/product_list_order_html.php');
          ?>



        </div>



        <div class="clear"></div>



        <div class="row">


          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="x_panel x_panel_minium">
              <div class="x_title">
                <h2>배송지 입력<small></small></h2>
                <ul class="nav navbar-right panel_toolbox simple_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                
                <div class="clearfix"></div>
              </div>


              <div class="x_content ">
                <div class="" id="out_form">

                        <div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 4px; border-bottom: 2px solid #E6E9ED; margin-bottom:20px;">
                          <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                              <label for="order_date">주문일</label>
                          </div>
                          <div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px;">
                              <input type="date" id="order_date" class="form-control no-datepicker" name="order_date" value="<?=$today_date?>">
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <!-- 빈 공간 -->
                          </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-xs-12" style="padding-left: 4px; border-bottom: 2px solid #E6E9ED; margin-bottom:20px; border-right: 1px solid #E6E9ED;">

                          <div class="col-md-4 col-sm-2 col-xs-12 align-right">
                              <label for="order_name">주문  &nbsp; 고객명</label>
                          </div>
                          <div class="col-md-8 col-sm-10 col-xs-12" style="margin-bottom: 10px;">
                              <input type="text" id="order_name" class="form-control" name="order_name" >
                          </div>
                          <div class="clear"></div>

                            
                          <div class="col-md-4 col-sm-2 col-xs-12 align-right">
                              <label for="order_tel">주문고객전화</label>
                          </div>
                          <div class="col-md-8 col-sm-10 col-xs-12" style="margin-bottom: 10px;">
                              <input type="text" id="order_tel" class="form-control" name="order_tel" >
                          </div>
                          <div class="clear"></div>




                          <div class="col-md-4 col-sm-2 col-xs-12 align-right">
                            <label for="order_company_tel">주문부서/사번</label>
                          </div>
                          <div class="col-md-8 col-sm-10 col-xs-12" style="margin-bottom: 10px;">
                              <input type="text" id="order_company_tel" class="form-control" name="order_company_tel" placeholder="*해당되는 회사만 입력해주세요">
                          </div>

                        </div>





                        <div class="col-md-6 col-sm-12 col-xs-12"  style="padding-left: 4px; border-bottom: 2px solid #E6E9ED;margin-bottom:20px;">
                          <div class="col-md-4 col-sm-2 col-xs-12 align-right">
                              <label for="r_name">받는 &nbsp;  고객명</label>
                          </div>
                          <div class="col-md-8 col-sm-10 col-xs-12" style="margin-bottom: 10px;">
                              <input type="text" id="r_name" class="form-control" name="r_name" >
                          </div>

                          <div class="clear"></div>
                          <div class="col-md-4 col-sm-2 col-xs-12 align-right">
                            <label for="r_tel">받는고객전화</label>
                          </div>
                          <div class="col-md-8 col-sm-10 col-xs-12" style="margin-bottom: 10px;">
                              <input type="text" id="r_tel" class="form-control" name="r_tel" >
                          </div>

                          <div class="clear"></div>


                          <div class="col-md-4 col-sm-2 col-xs-12 align-right">
                            <label for="r_company_tel">받는부서/사번</label>
                          </div>
                          <div class="col-md-8 col-sm-10 col-xs-12" style="margin-bottom: 10px;">
                              <input type="text" id="r_company_tel" class="form-control" name="r_company_tel" placeholder="*해당되는 회사만 입력해주세요" >
                          </div>

                        </div>

                        <div class="clear"></div>


                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="r_date"><span class="red">*</span>배달일시</label>
                        </div>
                        <div class="col-md-4 col-sm-10 col-xs-12" style="padding-left:0px;">
                            <fieldset style="padding-right:0px;">
                            <div class="control-group">
                              <div class="controls">
                                <div class="col-md-12 col-sm-12 col-xs-12 xdisplay_inputx form-group has-feedback" style="padding-right:10px;">
                                  <input type="text" class="form-control has-feedback-left" id="r_date" placeholder="<?=$today_date?>" aria-describedby="inputSuccess2Status3" name="r_date" style="padding-right:0px;" readonly value="<?=$today_date?>">
                                  <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                  <span id="inputSuccess2Status3" class="sr-only">(success)</span>
                                </div>
                              </div>
                            </div>
                          </fieldset>
                        </div>


                        
                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                            <label for="r_hour">배달시간</label>
                        </div>
                        <div class="col-md-4 col-sm-10 col-xs-12" style="margin-bottom: 10px; padding-right:20px;">
                            <input type="text" id="r_hour" class="form-control" name="r_hour" placeholder="예) 3시간이내"  >
                        </div>
                        <div class="clear"></div>



                        <div class="col-md-2 col-sm-2 col-xs-12 align-right rHourLine ">
                          <label for="rHourTable">배달시간</label>
                        </div>
                        
                        <div class="col-md-3 col-xs-12 align-right rHourLine ">
                          
                        </div>
                        <div class="col-md-7 col-sm-10 col-xs-12 rHourLine align-right " style="margin-top:-10px;margin-bottom:10px;padding-right:17px">
                          <table class="rHourTable">
                            <tr>

                            <td class="li_h">
                                <span class="li_v hangul">지금즉시</span>
                            </td>

                            <!--
                            <td class="li_h">
                              
                                <span class="li_v hangul">제작즉시</span>
                              
                            </td>  -->
                            <td class="li_h">
                              
                                <span class="li_v hangul">3시간이내</span>
                              
                            </td>
                            <td class="li_h">
                                 <select class="form-control" name="r_select_hour" id="r_select_hour">
                                  <option value="09시">09시</option>
                                  <option value="10시">10시</option>
                                  <option value="11시">11시</option>
                                  <option value="12시">12시</option>
                                  <option value="13시">13시</option>
                                  <option value="14시">14시</option>
                                  <option value="15시">15시</option>
                                  <option value="16시">16시</option>
                                  <option value="17시">17시</option>
                                  <option value="18시">18시</option>
                                  <option value="19시">19시</option>
                                  <option value="20시">20시</option>
                                  <option value="21시">21시</option>
                                  <option value="22시">22시</option>
                                 </select>
                              
                            </td>

                            
                            </tr>
                          </table>
                        </div>
                        <div class="clear"></div>








                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                            <label for="r_hour"></label>
                        </div>
                        <div class="col-md-10 col-sm-10 col-xs-12 " style="margin-top:-15px; ">
                          <span class="dodgerblue">
                            *원하시는 시간은 24시간을 기준으로 ~까지로 정확히 적어주시고, 예식.행사는 시간을 직접 입력해주세요.  
                            (예:13시까지/15시예식/18시행사)
                          </span>
                        </div>

                        <div class="clear" style=" margin-bottom:10px;"></div>


                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="address1"><span class="red">*</span>배달장소</label>
                        </div>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                          <input type="button" value="주소검색" onclick="goPopup();" style="padding:4px 12px !important"> 
                          <select name="juso1" class=""  style="padding:6px 12px !important">
                          <option>--지역--</option>
                          <option value="서울특별시">서울특별시</option>
                          <option value="부산광역시">부산광역시</option>
                          <option value="대구광역시">대구광역시</option>
                          <option value="인천광역시">인천광역시</option>
                          <option value="광주광역시">광주광역시</option>
                          <option value="대전광역시">대전광역시</option>
                          <option value="울산광역시">울산광역시</option>
                          <option value="경기도">경기도</option>
                          <option value="강원도">강원도</option>
                          <option value="충청북도">충청북도</option>
                          <option value="충청남도">충청남도</option>
                          <option value="전라북도">전라북도</option>
                          <option value="전라남도">전라남도</option>
                          <option value="경상북도">경상북도</option>
                          <option value="경상남도">경상남도</option>
                          <option value="세종특별자치시">세종특별자치시</option>
                          <option value="제주특별자치도">제주특별자치도</option>

                                                    
                        </select>
                        <select  name="juso2" class="" style="padding:6px 12px !important">
                          <option>--시/군--</option>
                          <option></option>
                          
                        </select>

                          <input type="text" id="address1" class="form-control" name="address1" >
                          <input type="text" id="address2" class="form-control" name="address2" >
                          <input type="text" id="zipNo" class="form-control smallest" name="zipNo">

                        </div>


                        <div class="clear" style="border-bottom: 2px solid #E6E9ED; margin-bottom:20px;"></div>



                        <!-- <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                           <label for="sender_name">보내는분</label>

                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12" style="margin-bottom: 10px;">
                            <select id="sender_name" class="select2_single form-control" tabindex="-1" style="display: none;">
                                <option value="">고객사</option>
                                <option value="<?=$data_client['client_place_idx']?>"><?=$data_client['place_name']?></option>
                            </select>
                        </div>
                        <div class="clear"></div>
                        
                        
                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="to_name">받는분 이름</label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="to_name" class="form-control input_normal" name="to_name" >
                        </div>

                        <div class="clear"></div>

                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                        <label for="hp">전화번호</label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                        <input type="text" id="hp" class="form-control input_normal" name="hp" >
                        </div>

                        <div class="clear"></div>
                        -->



                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="messageType"><span class="red">*</span>메세지종류</label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <p>
                          카드만:
                          <input type="radio" class="flat" name="messageType" id="messageCard" value="messageCard"  /> 
                          리본만:
                          <input type="radio" class="flat" name="messageType" id="messageRibbon" value="messageRibbon" checked=""  />
                          </p>
                        </div>

                        <div class="clear"></div>

                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="eType">경조유형</label>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="eType" class="form-control input_normal" name="eType" placeholder="예) 조모상 / 빙부상">
                        </div>

                        <div class="clear"></div>





                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="msgTitle"><span class="red">*</span>경조사어</label>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="msgTitle" class="form-control input_normal" name="msgTitle" placeholder="">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 align-right">
                          <button type="button" id="more_msgTitle" class="btn btn-sm">경조사어 추가</button>
                        </div>


                        <div class="clear "></div>



                        <div class="col-md-2 col-sm-2 col-xs-12 align-right mt2 hide">
                          <label for="msgTitle2">경조사어2</label>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12  mt2 hide">
                          <input type="text" id="msgTitle2" class="form-control input_normal" name="msgTitle2" placeholder="">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 align-right  mt2 hide">
                          
                          <i class="fa fa-trash-o sky del_msgTitle2" alt="경조사어2 삭제" title="경조사어2 삭제"></i>
                        </div>
                        <div class="clear mt2 hide"></div>




                        <div class="col-md-2 col-sm-2 col-xs-12 align-right  mt3 hide">
                          <label for="msgTitle3">경조사어3</label>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12  mt3 hide">
                          <input type="text" id="msgTitle3" class="form-control input_norma2" name="msgTitle3" placeholder="">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 align-right  mt3 hide">
                          <i class="fa fa-trash-o sky del_msgTitle3" alt="경조사어3 삭제" title="경조사어3 삭제"></i>
                        </div>
                        <div class="clear  mt3 hide"></div>




                        <div class="col-md-2 col-sm-2 col-xs-12 align-right msgTitleLine ">
                          <label for="msgTitle">경조사어</label>
                        </div>
                        <div class="col-md-10 col-sm-10 col-xs-12 msgTitleLine ">
                          <table class="msgTitleTable">
                            <tr>
                            <td class="li_h">
                                <span class="li_v hanja">祝結婚</span>
                                <span class="li_v hangul">축결혼</span>
                            </td>
                            <td class="li_h">
                              
                                <span class="li_v hanja">祝華婚</span>
                                <span class="li_v hangul">축화혼</span>
                              
                            </td>
                            <td class="li_h">
                              
                                <span class="li_v hanja">謹弔</span>
                                <span class="li_v hangul">근조</span>
                              
                            </td>
                            <td class="li_h">
                              
                                <span class="li_v hanja">祝發展</span>
                                <span class="li_v hangul">축발전</span>
                              
                            </td>
                            <td class="li_h">
                              
                                <span class="li_v hanja">祝開業</span>
                                <span class="li_v hangul">축개업</span>
                              
                            </td>
                            <td class="li_h">
                              
                                <span class="li_v hanja">祝榮轉</span>
                                <span class="li_v hangul">축영전</span>
                              
                            </td>
                            <td class="li_h">
                              
                                <span class="li_v hanja">祝昇進</span>
                                <span class="li_v hangul">축승진</span>
                              
                            </td>
                            <td class="li_h">
                              
                                <span class="li_v hanja">祝就任</span>
                                <span class="li_v hangul">축취임</span>
                              
                            </td>
                            <td class="li_h">
                              
                                <span class="li_v hanja">祝生日</span>
                                <span class="li_v hangul">축생일</span>
                              
                            </td>
                            <td class="li_h">
                              
                                <span class="li_v hanja">祝古稀</span>
                                <span class="li_v hangul">축고희</span>
                              
                            </td>
                            <td class="li_h li_long">
                              
                                <span class="li_v hanja">삼가 故人의 冥福을 빕니다</span>
                                <span class="li_v hangul">삼가 고인의 명복을 빕니다</span>
                              
                            </td>
                            </tr>
                          </table>
                        </div>
                        <div class="clear"></div>





                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                           <label for="sender_name">보내는분</label>

                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-10" style="margin-bottom: 10px;">
                            <select id="sender_name" class="select2_single form-control" tabindex="-1" style="display: none;">
                                <option value="">---선택---</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-2 align-right">
                            <i class="fa fa-trash-o sky" id="btn_del_sender_name" alt="보내는분 삭제" title="보내는분 삭제"></i>
                        </div>
                        <div class="clear"></div>
                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="sender_name_custom"></label>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                          <input type="text" id="sender_name_custom" class="form-control input_normal" name="sender_name_custom" placeholder="">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 align-right">
                          <button type="button" id="btn_add_sender_name" class="btn btn-sm">보내는분 추가</button>
                        </div>
                        <div class="clear"></div>


                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                            <label for="delivery_memo">고객요청사항</label>
                        </div>
                        <div class="col-md-10 col-sm-10 col-xs-12" style="margin-bottom: 10px;">
                            <textarea id="delivery_memo" required="required" class="form-control" name="delivery_memo" ></textarea>
                        </div>
                        <div class="clear"  style="border-bottom: 2px solid #E6E9ED; margin-bottom:20px;"></div>





                        <form enctype="multipart/form-data" id="ajaxFrom" method="post">

                        <div class="clear"></div>

                        <div class="div_attach">
                          <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="attachFile"><i class="fa fa-plus-square"></i>첨부하기</label>
                          </div>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                              <!-- <input type="file" name="attachFile" id="attachFile"/> -->
                              <input type="file" class="files" id='files' name="files[]" multiple>

                          </div>
                        </div>
                        <div class="clear"></div>


<!-- 
                        <div class="div_attach">
                          <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="attachFile"><i class="fa fa-plus-square"></i>첨부하기</label>
                          </div>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                              <input type="file" name="attachFile" />
                          </div>
                        </div>
                        <div class="clear"></div>

                        <div class="div_attach">
                          <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="attachFile"><i class="fa fa-plus-square"></i>첨부하기</label>
                          </div>
                          <div class="col-md-10 col-sm-10 col-xs-12">
                              <input type="file" name="attachFile" />
                          </div>
                        </div>
                        <div class="clear"></div> -->


                      </form>

                        <script>
                           $("input.files").filestyle({
                                iconName : 'glyphicon glyphicon-file',
                                buttonText : 'Select File',
                                buttonName : 'btn-warning'
                            });       
                            // $("#attachFile").filestyle({
                            //     iconName : 'glyphicon glyphicon-file',
                            //     buttonText : 'Select File',
                            //     buttonName : 'btn-warning'
                            // });       
                            
                        </script>



                        <div class="col-md-2 col-sm-2 col-xs-12 align-right">
                          <label for="paymentType"><span class="red">*</span>결제선택</label>
                        </div>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                          <p>
                          월말결제(계산서):
                          <input type="radio" class="flat" name="paymentType" id="paymentBill" value="paymentBill" checked  /> 
                          카드결제:
                          <input type="radio" class="flat" name="paymentType" id="paymentCard" value="paymentCard"  />
                          </p>
                        </div>

                        <div class="clear"></div>





                        <div class="col-md-2 col-sm-2 col-xs-12">
                        
                        </div>
                        <div class="col-md-10 col-sm-10 col-xs-12" style="margin-top:10px;">
                          <!-- <div class="checkbox">
                            <label class="">
                              <div class="icheckbox_flat-green " style="position: relative;">
                              <input type="checkbox" id='move_check' class="flat"  style="position: absolute; opacity: 0;">
                              </div> 이동지시서 동시 작성
                            </label>
                          </div> -->
                          <button  class="btn btn-success btn_save_client_order">주문하기</button>
                        </div>

                        <div class="clear"></div>



                        <!--<button  class="btn btn-danger btn_cancel">취소</button>-->
                        <!--<button  class="btn btn-primary btn_save_out_complete">출고서 등록</button>-->









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



<!-- daterangepicker -->
<script type="text/javascript" src="js/moment/moment.min.js"></script>
<script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>

<!-- 모달창 방지를 위한 스크립트 추가 -->
<script type="text/javascript">
  $(document).ready(function() {
    // no-datepicker 클래스를 가진 요소에 대해 daterangepicker 이벤트 제거
    $('#order_date').off('click');
    $('#order_date').off('focus');
    
    // 기본 HTML5 date input 사용
    $('#order_date').on('click', function(e) {
      e.stopPropagation();
    });
  });
</script>

<!-- Autocomplete -->
<script type="text/javascript" src="js/autocomplete/countries.js"></script>


<!-- select2 -->
<script>
  $(document).ready(function() {
    $("#order_client.select2_single").select2({
      placeholder: "고객사 선택",
      allowClear: true
    });

    $("#sender_name.select2_single").select2({
      placeholder: "---- 선택 ----",
      allowClear: true
    });
    
    $(".select2_group").select2({});


    $(document).on("click","input.icheck").iCheck({
              checkboxClass: 'icheckbox_flat-green'
    });

  });
</script>
<!-- /select2 -->



<script type="text/javascript">

    var start_num = 0;

    //기존에 구매신청한 건이 있어서 수정들어올때 기존 구매신청한 내역 기본 선택되되록 적용
    function do_init(){ //수정페이지 처음들어올때만 적용.

        <?
        if($_REQUEST['out_order_idx'] != ""){?>
          var check_idx = <?=$_REQUEST['out_order_idx']?>;
        <?}else{?>
          var check_idx = 0;
        <?}
        ?>

        if(check_idx == 0){
          start_num++;
          return;
        }
        
        if(start_num == 0){

          <?php 
          
          if($out_order_part == "상조"){
              for ($i=0; $i < count($p_arr); $i++) { ?>
              
                if($("ul.ul_product_list li[product_part='sangjo'][product_idx='<?=$p_arr[$i]['product_idx']?>']").length == 0){
                    toast('기존상품은 현재 주문할수 없는 상태입니다.');
                }else{
                  $("ul.ul_product_list li[product_part='sangjo'][product_idx='<?=$p_arr[$i]['product_idx']?>']").trigger("click");
                  $("ul.ul_product_list_right li[product_part='sangjo'][product_idx='<?=$p_arr[$i]['product_idx']?>'] input[name='cnt']").val('<?=$p_arr[$i]['order_count']?>');

                }
            <?}
          }else if($out_order_part == "화훼"){//화훼
              for ($i=0; $i < count($p_arr); $i++) { ?>
                if($("ul.ul_product_list li[product_part='flower'][product_idx='<?=$p_arr[$i]['product_idx']?>'][client_product_idx='<?=$p_arr[$i]['client_product_idx']?>']").length == 0){
                  toast('기존상품은 현재 주문할수 없는 상태입니다.');

                }else{
                  $("ul.ul_product_list li[product_part='flower'][product_idx='<?=$p_arr[$i]['product_idx']?>'][client_product_idx='<?=$p_arr[$i]['client_product_idx']?>']").trigger("click");
                  $("ul.ul_product_list_right li[product_part='flower'][product_idx='<?=$p_arr[$i]['product_idx']?>'][client_product_idx='<?=$p_arr[$i]['client_product_idx']?>']  input[name='cnt']").val('<?=$p_arr[$i]['order_count']?>');

                }
            <?}
          }



          ?>



          var out_order_info = <?php echo json_encode($out_order_data); ?>;
          apply_order_info(out_order_info);
          
          start_num++; //수정페이지 처음들어올때만 적용. 이후에 고객사 변경할때는 호출해도 적용안되도록.
        }

    }



    $(document).ready(function() {


      <?
         //주문내역에서 수정하기로 들어오면 회사기본선택, 기존 주문내역 기본 선택
         if(isset($consulting_idx)){
          ?>
            load_client_product(<?=$consulting_idx?>);



          <?}?>



      $('#r_date').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_3",
        format: 'YYYY년 MM월 DD일',
        locale: {
          daysOfWeek: ['일', '월', '화', '수', '목', '금', '토'],
          monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
          firstDay: 0
        }

      }, function(start, end, label) {
        //$("#r_weekday").val(getDayOfWeek(start));
        $('#r_date').val($('#r_date').val()+" ("+getDayOfWeek(start)+")");
        //console.log(start.toISOString(), end.toISOString(), label);
      });

    });

    function getDayOfWeek(rDate){ //ex) getDayOfWeek('2022-06-13')

      const week = ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'];

      const dayOfWeek = week[new Date(rDate).getDay()];

      return dayOfWeek;

    }


  </script>

<script language="javascript">

document.domain = "neulflower.kr";

function jusoCallBack(roadFullAddr,roadAddrPart1,addrDetail,roadAddrPart2,engAddr,jibunAddr,zipNo,admCd,rnMgtSn,bdMgtSn,detBdNmList,bdNm,bdKdcd,siNm,sggNm,emdNm,liNm,rn,udrtYn,buldMnnm,buldSlno,mtYn,lnbrMnnm,lnbrSlno,emdNo){
	//document.getElementById('roadFullAddr').value = roadFullAddr;
	document.getElementById('address1').value = roadAddrPart1 +' '+ roadAddrPart2;
	document.getElementById('address2').value = addrDetail;
	//document.getElementById('roadAddrPart2').value = roadAddrPart2;
	// document.getElementById('engAddr').value = engAddr;
	// document.getElementById('jibunAddr').value = jibunAddr;
	document.getElementById('zipNo').value = zipNo;
	// document.getElementById('admCd').value = admCd;
	// document.getElementById('rnMgtSn').value = rnMgtSn;
	// document.getElementById('bdMgtSn').value = bdMgtSn;
	// document.getElementById('detBdNmList').value = detBdNmList;
	// //** 2017년 2월 제공항목 추가 **/
	// document.getElementById('bdNm').value = bdNm;
	// document.getElementById('bdKdcd').value = bdKdcd;
	// document.getElementById('siNm').value = siNm;
	// document.getElementById('sggNm').value = sggNm;
	// document.getElementById('emdNm').value = emdNm;
	// document.getElementById('liNm').value = liNm;
	// document.getElementById('rn').value = rn;
	// document.getElementById('udrtYn').value = udrtYn;
	// document.getElementById('buldMnnm').value = buldMnnm;
	// document.getElementById('buldSlno').value = buldSlno;
	// document.getElementById('mtYn').value = mtYn;
	// document.getElementById('lnbrMnnm').value = lnbrMnnm;
	// document.getElementById('lnbrSlno').value = lnbrSlno;
	// //** 2017년 3월 제공항목 추가 **/
	// document.getElementById('emdNo').value = emdNo;
}

function goPopup(){
	// 주소검색을 수행할 팝업 페이지를 호출합니다.
	// 호출된 페이지(jusoPopup_utf8.php)에서 실제 주소검색URL(https://business.juso.go.kr/addrlink/addrLinkUrl.do)를 호출하게 됩니다.
	var pop = window.open("./contents/juso/jusoPopup_utf8.php","pop","width=570,height=420, scrollbars=yes, resizable=yes"); 
	
	// 모바일 웹인 경우, 호출된 페이지(jusoPopup_utf8.php)에서 실제 주소검색URL(https://business.juso.go.kr/addrlink/addrMobileLinkUrl.do)를 호출하게 됩니다.
    //var pop = window.open("./jusoPopup_utf8.php","pop","scrollbars=yes, resizable=yes"); 
}
</script>

