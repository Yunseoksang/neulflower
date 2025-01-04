
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



      <!--우측 상세내역 영역   --->
      <div class="col-md-12  col-sm-12 col-xs-12 hide" id="detail_section">
        <div class="x_panel">
            

            <div class="x_content" id="print_detail_section">
                <div class="row">
                    <div class="col-sm-12">
                      <?
                      if(file_exists("./contents/".$_REQUEST['fn']."/html/detail_html.php")){
                          require("./contents/".$_REQUEST['fn']."/html/detail_html.php");

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
      <div class="col-md-12  col-sm-12  col-xs-12 hide" id="detail_section_bill">
        <div class="x_panel">
            

            <div class="x_content" id="print_detail_section_bill">
                <div class="row">
                    <div class="col-sm-12">
                      <?
                      // if(file_exists("./contents/".$_REQUEST['fn']."/html/detail_html_bill.php")){
                      //     require("./contents/".$_REQUEST['fn']."/html/detail_html_bill.php");
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
      <!-- /page content -->



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
<script src="./contents/statistics/flower_storage_m/js/page.js?time=<?=time()?>"></script>


<?php

if($_REQUEST['yyyymm'] == ""){
    $_REQUEST['yyyymm'] = date("YM",time());
}

?>

<script>
var print_mode = "ok";

$(document).ready(function(){




    var mode='<?=$_REQUEST['mode']?>'; //detail:상세거래내역, bill:거래내역서

    if(mode == "detail"){
        $("#detail_section").removeClass("hide").show();
        $("#detail_section_bill").hide();

        view_right('<?=$_REQUEST['sidx']?>','<?=$_REQUEST['yyyymm']?>');


    }else if(mode == "bill"){
        $("#detail_section_bill").removeClass("hide").show();
        $("#detail_section").hide();
        view_bill('<?=$_REQUEST['sidx']?>','<?=$_REQUEST['yyyymm']?>');


    }




    

});
</script>