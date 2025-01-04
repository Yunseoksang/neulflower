
<?php
//좌우 나누기 비율 없으면 12을 둘로 나눈다.
if($split_ratio_left == ""){
  $split_ratio_left = 6;
  $split_ratio_right = 6;

}

$s_yyyymm = date("Y년 m월",time());

?>

<style>

</style>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="page-title" style="float:left; display:block">
      <div class="title_left col-md-5 col-sm-5 col-xs-12 settlement_filter" style="padding-right:0px;">

         <div class="col-md-3 col-sm-3 col-xs-12 selectbox1">
            <h3>
                <a href="<?=$_SERVER['PHP_SELF']?><?if(isset($_GET['page'])){echo "?page=".$_GET['page'];}?>"><?=$title?></a>

            </h3>
            <hr>
         </div>


         <div class="col-md-7 col-sm-7 col-xs-12 selectbox2" style="padding-top:10px;">
             <table style="width:100%;">
              <tr>
                <td style="width:20%;padding-right:10px;">              
                    <div class="float_right yyyymm_div">
                      정산월1: <input id="yyyymm" type="text" class="form-control input-sm readonly-input" yyyymm="<?=date("Ym",time())?>" placeholder="<?=date("Y년 m월",time())?>" readonly>
                    </div>
                </td>
                <td style="width:20%;padding-right:10px;"><?=$category1_select_html?></td>
                <td style="width:20%;padding-right:10px;">
                  <div class="input-container">
                    <input id="sdate" class="form-control readonly-input" type="text" placeholder="정산종료일 그룹" readonly>
                    <div id="calendar_dom" class="hidden">
                      <div class="row_c">
                        <button>1</button>
                        <button>2</button>
                        <button>3</button>
                        <button>4</button>
                        <button>5</button>
                        <button>6</button>
                        <button>7</button>
                        <button>8</button>
                        <button>9</button>
                        <button>10</button>
                      </div>
                      <div class="row_c">
                        <button>11</button>
                        <button>12</button>
                        <button>13</button>
                        <button>14</button>
                        <button>15</button>
                        <button>16</button>
                        <button>17</button>
                        <button>18</button>
                        <button>19</button>
                        <button>20</button>
                      </div>
                      <div class="row_c">
                        <button>21</button>
                        <button>22</button>
                        <button>23</button>
                        <button>24</button>
                        <button>25</button>
                        <button>26</button>
                        <button>27</button>
                        <button>28</button>
                        <button>29</button>
                        <button>30</button>
                      </div>
                      <div class="row_c">
                        <button>말일</button>
                        <button>건별</button>
                        <button>상시</button>
                        <button>미지정</button>
                        <button>전체</button>
                      </div>
                    </div>
                </div>
              
              </td>
              <td style="width:20%;padding-right:10px;">
                  <select class="form-control" id="payment_method">
                    <option value="view_all">--결제수단--</option>
                    <option value="계산서">계산서</option>
                    <option value="카드">카드</option>
                    <option value="계산서역발행">계산서역발행</option>
                    <option value="미지정">미지정</option>

                  </select>
                </td>
              <td style="width:20%;padding-right:10px;">
                  <select class="form-control" id="bill_status">
                    <option value="view_all">--발행단계--</option>
                    <option value="미발행">미발행</option>
                    <option value="저장/미발송">저장/미발송</option>
                    <option value="발송완료/수락대기">발송완료/수락대기</option>
                    <option value="수정요청">수정요청</option>
                    <option value="승인완료">승인완료</option>
                    <!-- <option value="세금계산서발행완료">세금계산서발행완료</option> -->
                    <option value="폐기">폐기</option>
                  </select>
                </td>
              </tr>
            </table>

         </div>

         <div class="col-md-2 col-sm-2 col-xs-12 selectbox3" style="padding-right:0px; padding-top:10px;">
             <table style="width:100%;">
              <tr>

                <td ><button class="btn btn-primary" id="btn_bill_publish_all" style="margin-right:0px; width:100%;margin-bottom:0px;">거래명세서 일괄발행</button></td>
              </tr>
            </table>
             
         </div>



      </div>



      
      
 

    </div>
    <div class="clearfix" style="clear:both"></div>

    <div class="row">

      <div class="col-md-<?=$split_ratio_left?> col-sm-6  col-xs-12">
        <div class="x_panel">
          
          
          
          <?
            if( isset( $title_bar_ratio) && $title_bar_ratio  == ""){
              if($function_add == "on"){ //새로추가하기 버튼 있으면
                  $title_bar_ratio = [2,8,2];

              }else{  //추가버튼 없으면, 우측 공간 거의 없애도 됨
                $title_bar_ratio = [2,10];

              }
            }
          ?>
          <div class="x_title">
            <div class="col-md-4 col-sm-4  col-xs-12">
                <h2 style="padding:10px;"><span id="xtitle_yyyymm"><?=$s_yyyymm?></span><small id="total_list_cnt"> 총: 0 건</small> </h2>
            </div>
            <div class="col-md-8 col-sm-8  col-xs-12">
                <!-- <div class="checkbox right">
                    <label class="zero_sale_label">
                      <input type="checkbox" class="flat" id="zero_sale" value="ok"> 전체
                    </label>
                  </div> -->
            </div>
            
            <div class="clearfix"></div>
          </div>





          <div class="x_content">
            <div class="row">
              <div class="col-sm-12">
                <div class="card-box table-responsive">
                  <p class="text-muted font-13 m-b-30">
                    <!--- 타이틀 설명---->
                  </p>

                  <table id="datatable-main" key_name="<?=$key_column_name?>" class="datatable full_width table table-striped table-bordered " style="width:50%;">
                    <thead>
                      <tr>
                        <?
                          for ($i=0;$i<count($column_array);$i++ )
                          {

                            if (!empty($col['padding_unset'])) {
                              if(!isset($col['padding_unset'])){$col['padding_unset'] = "";}
                            }

                            if (!empty($col['filter'])) {
                              if(!isset($col['filter'])){$col['filter'] = "";}
                            }
                            


                            $col = $column_array[$i];
                            $column = $col['column'];
                            if( isset($col['padding_unset']) && $col['padding_unset'] == "Y"){$padding_unset = "th_padding_unset";}else{$padding_unset="";}

                            if( isset($col['filter']) && $col['filter'] == "Y"){?>
                              <th column="<?=$column?>" class="<?=$column?> <?=$padding_unset?>" select_filter="<?=$column?>">
                                <?=print_filter($column)?>
                              </th>
                            <?}else{?>
                              <th column="<?=$column?>" class="<?=$column?>" >
                                <?=$col['th_title']?>
                              </th>
                            <?}
                          }
                        ?>

                      </tr>
                    </thead>

                    <tfoot>
                      <tr>
                        <?
                          for ($i=0;$i<count($column_array);$i++ )
                          {
                            $col = $column_array[$i];
                            $column = $col['column'];

                              ?>
                              <td column="<?=$column?>" class="<?=$column?>" >
                                 
                              </td>
                              <?


                          }
                        ?>

                      </tr>
                    </tfoot>

                    <!-- tbody 태그 필요 없다. -->
                    
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      <!-- /page content -->


      <!--우측 상세내역 영역   --->
      <div class="col-md-<?=$split_ratio_right?>  col-sm-6  col-xs-12 " id="detail_section">
        <div class="x_panel">
            <div class="x_title">
                <div class="col-md-4 col-sm-4  col-xs-12">
                    <h2 style="padding:10px;" class="right_company_name">거래처명 </h2>
                </div>
<!---
                <div class="col-md-8 col-sm-8  col-xs-12" style="padding-right:0px; padding-top:10px;">
                                    <div class="col-md-2 col-sm-10 col-xs-12 right" style="padding-left:0px; padding-top:7px">
                       조회기간
                    </div>
                    <div class="col-md-4 col-sm-10 col-xs-12 right" style="padding-left:0px; padding-right:0px;">
                        <fieldset style="padding-right:0px;">
                        <div class="control-group">
                          <div class="controls">
                            <div class="col-md-12 col-sm-12 col-xs-12 xdisplay_inputx form-group has-feedback" style="padding-right:0px;">
                              <input type="text" class="form-control has-feedback-left readonly-input" id="settlement_start_date" placeholder="정산시작일" aria-describedby="inputSuccess2Status3" name="settlement_start_date" style="padding-right:0px;" readonly>
                              <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                              <span id="inputSuccess2Status3" class="sr-only">(success)</span>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>

                    <div class="col-md-4 col-sm-10 col-xs-12 right" style="padding-right:0px; padding-left:0px;">
                        <fieldset style="padding-right:0px;">
                        <div class="control-group">
                          <div class="controls">
                            <div class="col-md-12 col-sm-12 col-xs-12 xdisplay_inputx form-group has-feedback" style="padding-right:0px;">
                              <input type="text" class="form-control has-feedback-left readonly-input" id="settlement_end_date" placeholder="정산종료일" aria-describedby="inputSuccess2Status4" name="settlement_end_date" style="padding-right:0px;" readonly>
                              <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                              <span id="inputSuccess2Status4" class="sr-only">(success)</span>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12"  style="padding-right:0px;">
                    <button class="btn btn-dark btn_view_detail" style="">보기</button>

                    </div>


                </div>
                <div class="clearfix"></div>
                        -->
            </div>

            <div class="x_content" id="print_detail_section">
                <div class="row">
                    <div class="col-sm-12">
                      <?
                      if(file_exists("./contents/".$folder_name."/html/detail_html.php")){
                          require("./contents/".$folder_name."/html/detail_html.php");

                      }else{
                          //require('./contents/'.$folder_name.'/html/detail.php');

                      }?>
                    </div>
                </div>
            </div>
        </div>
        
      </div>
      <!-- /page content -->


      <!--우측 상세내역 영역2   --->
      <div class="col-md-<?=$split_ratio_right?>  col-sm-6  col-xs-12 hide" id="detail_section_bill">
        <div class="x_panel">
            <div class="x_title">
                <div class="col-md-4 col-sm-4  col-xs-12">
                    <h2 style="padding:10px;" class="right_company_name">거래명세서 </h2>
                </div>

                <div class="col-md-8 col-sm-8  col-xs-12">
                  <div class="float_right yyyymm_div">
                  <!-- <input id="yyyymm_right_bill" type="text" class="form-control input-sm" placeholder="<?=date("Y년 m월",time())?>"><button class="btn btn-dark btn-xs btn_print btn_print_bill"  style="padding: 4px 10px 4px 10px !important;margin: -4px 0px -1px 10px !important;">인쇄</button> -->
                  </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="x_content" id="print_detail_section_bill">
                <div class="row">
                    <div class="col-sm-12">
                      <?
                      if(file_exists("./contents/".$folder_name."/html/detail_html_bill.php")){
                           require("./contents/".$folder_name."/html/detail_html_bill.php");
  
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



  
  <? if($function_keyword_search == "on"){
    
    if(isset($_GET['scol']) && $_GET['scol'] != ""){
      $keyword_column_array_selected = $_GET['scol'];      //기본 선택옵션 value
    }
    
    ?>
  <div id="search_select_sample" class="hide"> 
    <div class="pull-right" style="margin-left:3px;">
      <select class="form-control" name="search_column">

      <?
        for ($i=0;$i<count($keyword_column_array);$i++ )
        {
           $option_text = $keyword_column_array[$i][0];
           $option_value = $keyword_column_array[$i][1];
           ?>
              <option value='<?=$option_value?>' <? if($option_value == $keyword_column_array_selected){echo "selected";}?>><?=$option_text?></option>
           <?
        }
      ?>

      </select>
    </div>
  </div>
  <?}?>



  <div id="add_form" style="display:none;">
    <div class="form_area form-horizontal form-label-left" novalidate="">
    </div>
  </div>



  <div id="modify_sample" class="hide">

  <?
      //filter 배열에 정의된 개수만큼 select 박스 생성. 정의.
      // foreach($filter as $key => $value)
      // {
      //   print_filter($key); //column_array.php 에 정의, 
      // }


      if(is_iterable($edit_array)){
        for ($i=0;$i<count($edit_array);$i++ )
        {
            $options = "";
            $direct_save = "";
            $same = "";
            $format = "";

            $this_colname = $edit_array[$i][0];

            $mm = $edit_array[$i][1];

            if(is_iterable($edit_array[$i])){
                
              for ($j=2;$j<count($edit_array[$i]);$j++ ) //옵션 사항 검토
              {

                
                if(!empty($edit_array[$i][$j][0])){
                  $nn = $edit_array[$i][$j][0];
                }else{
                  $nn = "";
                }

                if(!empty($edit_array[$i][$j][1])){
                  $val = $edit_array[$i][$j][1];
                }else{
                  $val = "";
                }
                
                switch ($nn)
                {
                    case "options":
                      $options = $val;
                      break;
                    case "direct_save":
                      $direct_save =  "direct_save";
                      break;
                    case "same":
                        $same =  $val;
                        break;
                    case "format":
                        $format =  $val;
                        break;
                    default:
                }
              }
            }



            
            switch ($mm)
            {
                case "select":
                ?>
                      <select name="<?=$this_colname?>" <? if($same != ""){?>same="<?=$same?>"<?}?> class="<?=$this_colname?> form-control <?=$direct_save?>">
                          <?
                            if(is_iterable($options)){
                              for ($j=0;$j<count($options);$j++ )
                              {
                                //print_r($options[$j][0]); 
                                if(!empty($options[$j])){
                                ?>
                                  <option value="<?=$options[$j][0]?>"  <? if(!empty($options[$j][2]) && $options[$j][2] == "SELECTED"){?>selected<?}?> ><?=$options[$j][1]?></option>
                              <?}
                              }

                            }

                          ?>
                      </select>
                  <?break;
                  
                case "select2":?>
                    <div class="<?=$this_colname?>">
                      <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                        <select class="select2_single form-control"   <? if($same != ""){?>same="<?=$same?>"<?}?>  tabindex="-1" name="<?=$this_colname?>" >
                            <?
                            for ($j=0;$j<count($options);$j++ )
                            {

                              ?>
                                <option value="<?=$options[$j][0]?>"  <? if($options[$j][2] == "SELECTED"){?>selected<?}?> ><?=$options[$j][1]?></option>
                            <?}
                            ?>
                        </select>
                      </div>
                    </div>
                  <?break;

                  case "selectDropDown":?>


                    <div class="btn-group <?=$this_colname?>"  col_name="<?=$this_colname?>">
                    <button type="button" class=" btn btn-primary"  value="<?=$options[0][0]?>"><?=$options[0][1]?></button>
                      <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                    <ul class="dropdown-menu" role="menu">
                      
                       <?

                                                
                        for ($j=0;$j<count($options);$j++ )
                        {

                          if($options[$j][3] == "divided"){?>
                             <li class="divider"></li>
                          <?}
                          ?>
                          <li><a href="#" value="<?=$options[$j][0]?>" selected_color="<?=$options[$j][2]?>"><?=$options[$j][1]?></a></li>
                          
                        <?}
                        ?>

                      </li>
                    </ul>
                  </div>

                  <?break;

                case "editable": //editable 선언이면 아래 text 로 진행됨

                case "text":?>
                    <input type="text" name="<?=$edit_array[$i][0]?>" <? if($format != ""){?>format="<?=$format?>"<?}?> class="<?=$edit_array[$i][0]?> form-control <?=$direct_save?>">

                  <?break;
                case "date":?>
                  <input type="date" name="<?=$edit_array[$i][0]?>" <? if($format != ""){?>format="<?=$format?>"<?}?> class="<?=$edit_array[$i][0]?> form-control <?=$direct_save?>">

                <?break;
                case "textarea":?>
                    <textarea name="<?=$edit_array[$i][0]?>" class="<?=$edit_array[$i][0]?> form-control  <?=$direct_save?>"></textarea>

                  <?break;
                case "img":
                  
                  break;


                case "switch":
                  
                  break;
                case "button_onoff":
                  
                  break;
                case "checkbox":
                  
                  break;
                case "custom":
                  //별도 표기
                  break;

            }
        }
      }
      
    ?>
</div>

