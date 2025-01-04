
<?php
//좌우 나누기 비율 없으면 12을 둘로 나눈다.
if($split_ratio_left == ""){
  $split_ratio_left = 6;
  $split_ratio_right = 6;

}

?>

<style>

</style>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="page-title">
      <div class="title_left">
        <h3>
              <a href="<?=$_SERVER['PHP_SELF']?><?if(isset($_GET['page'])){echo "?page=".$_GET['page'];}?>"><?=$title?></a>
              <small>
                  <!--- 칼럼 다중 보기 옵션 -->
                  <?

                  if(is_iterable($view_group_array) &&  count($view_group_array) > 0){?>
                    <span>보기옵션:</span> 
                    <?

  
                      for ($i=0;$i<count($view_group_array);$i++ )
                      {
                        if($i==$viewgroupStartIndex){
                          $default_btn = "btn-success";
                        }else{
                          $default_btn = "btn-default";

                        }


                        ?>
                        <button class="btn <?=$default_btn?> btn_view_group" view_group="<?=$i?>"><?=$view_group_array[$i][0]?></button>  
                      <?}
                    ?>
                  <?}?>
              </small>
          </h3>
          <hr>

      </div>



      <div class="title_right <?=$date_search_display?> hide">

        <div class="col-md-7 col-sm-7 col-xs-12 form-group pull-right top_search">

            <div class="float_right">
                <button type="button" id="btn_search_refresh" class="btn btn-dark btn-sm">기간미적용중</button>
            </div>

            <div id="reportrange" class="float_right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; ">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                    <span>March 15, 2019 - May 14, 2019</span> <b class="caret"></b>
                    
            </div>

            <div class="float_right">
              <select class="date_part" id="date_part">
                <?
                    foreach($function_date_column as $key => $value)
                    {?>
                      <option value='<?=$key?>' <? if($key == $function_date_column_selected){echo "selected";}?>><?=$value?></option>
                    <?}
                ?>
              </select>
            </div>



        </div>
      </div>

      
      
 

    </div>
    <div class="clearfix"></div>

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
                <h2 style="padding:10px;">목록<small id="total_list_cnt"> 총: 0 건</small> </h2>
            </div>
            <div class="col-md-8 col-sm-8  col-xs-12">
              <div class="float_right yyyymm_div">
                  <input id="yyyymm" type="text" class="form-control input-sm" placeholder="<?=date("Y년 m월",time())?>">
              </div>
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
      <div class="col-md-<?=$split_ratio_right?>  col-sm-6  col-xs-12 hide" id="detail_section">
        <div class="x_panel">
            <div class="x_title">
                <div class="col-md-4 col-sm-4  col-xs-12">
                    <h2 style="padding:10px;" class="right_company_name">거래처명 </h2>
                </div>

                <div class="col-md-8 col-sm-8  col-xs-12">
                
                  <div class="float_right yyyymm_div">
                  <input id="yyyymm_right" type="text" class="form-control input-sm" placeholder="<?=date("Y년 m월",time())?>"><button class="btn btn-dark btn-xs btn_print btn_print_detail" style="padding: 4px 10px 4px 10px !important; margin: -4px 0px -1px 10px !important;">인쇄</button>
                  </div>

                </div>
                <div class="clearfix"></div>
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
                  <input id="yyyymm_right_bill" type="text" class="form-control input-sm" placeholder="<?=date("Y년 m월",time())?>"><button class="btn btn-dark btn-xs btn_print btn_print_bill"  style="padding: 4px 10px 4px 10px !important;margin: -4px 0px -1px 10px !important;">인쇄</button>
                  </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="x_content" id="print_detail_section_bill">
                <div class="row">
                    <div class="col-sm-12">
                      <?
                      // if(file_exists("./contents/".$folder_name."/html/detail_html_bill.php")){
                      //     require("./contents/".$folder_name."/html/detail_html_bill.php");
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

