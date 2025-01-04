<script>



var table_name = "<?=$table_name?>";
var key_column_name = "<?=$key_column_name?>";



var view_group = "0";
var all_columns_index = [];
<? if($all_columns_index != "" && count($all_columns_index) >0 ){?>
  all_columns_index = <?=json_encode($all_columns_index,JSON_UNESCAPED_UNICODE)?>;
<?}?>


var view_group_array = [];
<? if($view_group_array != "" && count($view_group_array) >0 ){?>
  view_group_array = <?=json_encode($view_group_array,JSON_UNESCAPED_UNICODE)?>;
<?}?>



var edit_hide_array = [];      // 수정버튼 눌렀을때 칼럼 폭의 변경등으로 안보이게 처리할 칼럼 index
<? 

if($edit_hide_array != "" && count($edit_hide_array) > 0 ){?>
  edit_hide_array = <?=json_encode($edit_hide_array,JSON_UNESCAPED_UNICODE)?>;
<?}?>




<? if($function_date_search == "on"){?> 
var daterangepicker_on = 1; //날짜별 검색기능 사용할지 여부. 1: 사용 ,0:사용안함.
var startDate = null;
var endDate = null;
var date_apply = "off"; //기간 검색 적용할지 여부.
var date_part = "regist_datetime";  //기간 검색 파트
<?}?>

var custom_filter = "";





function setKeywordQuery(num){
    
        
    var keyword_info = keyword_column_array[num];
    //console.log("keyword_info:"+keyword_info);
    if(keyword_info.length < 4){ //join 아님

        <? if(isset($_GET['filter_type']) && $_GET['filter_type'] != ""){?>
        switch (keyword_info[2] ) {
            case "like":
                keyword_query = " select * from " + table_name +" where " + filter_query + keyword_info[1] + " like '###keyword###' ";//% 기호가 post 로 전송되면서 서버로 전송되지 않는다. 따라서 서버에서 ###을 %로 replace
                break;
            default:
                keyword_query = " select * from " + table_name +" where " + filter_query + keyword_info[1] +" "+ keyword_info[2]+" '#keyword#' ";
                break;
        }
        <?}else{?>
        switch (keyword_info[2] ) {
            case "like":
                keyword_query = " select * from " + table_name +" where " +  keyword_info[1] + " like '###keyword###' ";//% 기호가 post 로 전송되면서 서버로 전송되지 않는다. 따라서 서버에서 ###을 %로 replace
                break;
            default:
                keyword_query = " select * from " + table_name +" where " +  keyword_info[1] +" "+ keyword_info[2]+" '#keyword#' ";
                break;
        }
        <?}?>




    }else{ //join
        //     //    array_push($keyword_column_array,["브랜드명","brand_name","like","join",["brand"],["brand_idx"]]);

        var join_info = keyword_info[2];
        switch (keyword_info[2] ) {
            case "like":
                keyword_where_query = keyword_info[1] + " like '###keyword###' ";  //% 기호가 post 로 전송되면서 서버로 전송되지 않는다. 따라서 서버에서 ###을 %로 replace
                break;
            default:
                keyword_where_query = keyword_info[1] +" "+ keyword_info[2]+" '#keyword#' ";
                break;
        }


        var key_column; 
        if(keyword_info.length>5){
          if(keyword_info.length == 6){ //마지막 요소이면
              key_column = keyword_info[5][0];
          }else{ //요소가 6개보다 크면
                                                   // keyword_info.length > 6 중간인 경우는 keyword_info[5][0]:조인할 테이블 이름, keyword_info[5][1]앞뒤 테이블의 매칭칼럼이름:동일
              key_column = keyword_info[5][1];
          }
            
        }


        // var keyword_info_final = keyword_info[keyword_info.length-1];
        // if(keyword_info_final.length == 1){
        //     var pre_key = keyword_info_final[0];
        //     var next_key = keyword_info_final[0];

        // }else if(keyword_info_final.length == 2){
        //     var pre_key = keyword_info_final[0];
        //     var next_key = keyword_info_final[1];
        // }


        keyword_query = "select "+table_name+".* from (select "+key_column+"," + keyword_info[1] + " from " + keyword_info[4][0] + " where " + keyword_where_query + ") p ";
        

        var pre_table_name = "p";
        //var next_table_name = "q";

        var pre_key_column;
        var next_key_column;

        for(var i=5;i<keyword_info.length;i++){
            var last_obj = keyword_info[i];

            if(keyword_info.length -1 == i){ //마지막 요소이면

                if(keyword_info.length == 6){
                    pre_table_name = "p";
                }else{
                    pre_table_name = keyword_info[i-1][0];
                }

                next_table_name = table_name;

                if(last_obj.length == 1){
                    pre_key_column = last_obj[0];
                    next_key_column = last_obj[0];
                }else if(last_obj.length == 2){
                    pre_key_column = last_obj[0];
                    next_key_column = last_obj[1];
                }

            }else{

                if(i == 5){
                    pre_table_name = "p";
                    next_table_name = keyword_info[i][0];
                }else{
                    pre_table_name = keyword_info[i-1][0];
                    next_table_name = keyword_info[i][0];
                }

                
                if(last_obj.length == 2){
                    pre_key_column = last_obj[1];
                    next_key_column = last_obj[1];
                }else if(last_obj.length == 3){
                    pre_key_column = last_obj[1];
                    next_key_column = last_obj[2];
                }

            }


            keyword_query += " left join " + next_table_name + " on " + pre_table_name+"."+pre_key_column+"="+next_table_name+"."+next_key_column + "  ";

        }

        <? if(isset($_GET['filter_type']) && $_GET['filter_type'] != ""){?>
        keyword_query += " where "+table_name+"."+key_column_name+" is not null " + filter_query_join;

        <?}else{?>
          keyword_query += " where "+table_name+"."+key_column_name+" is not null " ;

        <?}
        ?>


    }

    //console.log("keyword_query 생성결과:"+keyword_query);
    return keyword_query;

}


var scol = "";
var sval = "";
var keyword_column_selected_index = 0;



var keyword_query = ""; //검색시 칼럼조건을 포함한 쿼리

<? if($function_keyword_search == "on"){?>
  //var search_column = "<?=$keyword_column_array_selected?>";
  var keyword_column_array = <?=json_encode($keyword_column_array,JSON_UNESCAPED_UNICODE)?>;

  
  //파라미터로 변수가 넘어온 상황이면 초기 ajax 호출시 세팅하여 호출
  <? if(isset($_GET['scol']) && $_GET['scol'] != "" && $_GET['sval'] != ""){?>
 
        scol = "<?=$_GET['scol']?>";
        sval = "<?=$_GET['sval']?>";

        for(var i=0;i<keyword_column_array.length;i++){
              //console.log("칼럼수:"+keyword_column_array[i][1]);

              if(scol == keyword_column_array[i][1]){
                  keyword_query = setKeywordQuery(i);
                  keyword_column_selected_index = i;
                  //console.log("keyword_query before:"+keyword_query);
                  break;
              }
          
        }


   <?}else{?>

        <?
        if(isset($keyword_column_selected_index) && $keyword_column_selected_index != ""){?>
            keyword_column_selected_index = <?=$keyword_column_selected_index?>;
        <?}
        ?>
        keyword_query = setKeywordQuery(keyword_column_selected_index);

    <?}?>

<?}else{?>
  var keyword_column_array =[];
<?}?>




var column_list   = "";
var main_process  = "";
var main_process_query = "";  //중앙 라디오박스 프로세스별 검색
var multi_search_query  = "";  //체크박스 조건 등 검색
var select_filter = "";
var multi_column_search_string = ""; //칼럼별 키워드 동시 검색
var search_keyword = "<?=isset($_GET['sval'])?$_GET['sval']:''?>";

var folder_name   = "<?=$folder_name?>";



<?
if(file_exists("./contents/".$folder_name."/api/updateRow.php")){?>
var ajax_update_url  = "./contents/"+folder_name+"/api/updateRow.php";
<?}else{?>
var ajax_update_url  = "./contents/common/api/updateRow.php";
<?}?>




// //console.log("datatable.js.php => ajax_update_url:" + ajax_update_url);

var ajax_url_cell = "./contents/"+folder_name+"/api/updateCell.php";




<?
if(file_exists("./contents/".$folder_name."/api/getList.php")){?>
var ajax_get_url  = "./contents/"+folder_name+"/api/getList.php";
<?}else{?>
var ajax_get_url  = "./contents/common/api/getList.php";
<?}?>




<?
if(file_exists("./contents/".$folder_name."/api/insertRow.php")){?>
var ajax_insert_url  = "./contents/"+folder_name+"/api/insertRow.php";
<?}else{?>
var ajax_insert_url  = "./contents/common/api/insertRow.php";
<?}?>


<?
//include('./contents/'.$folder_name.'/js/ajax_url.js.php');
?>


$(document).ready(function() {

    <?
    if($function_process_search == "on"){  //중앙 프로세스 버튼 그룹 사용이면.

      //print_r($process_option);
      for ($i=0;$i<count($process_option);$i++ )
      {
      
        //$return_arr,[$value,$text,"selected"],$condition);
        if($process_option[$i][0][2] == "selected"){
          if($process_option[$i][1] != ""){ //condition이 있으면?>
            main_process_query = "<?=$process_option[$i][1]?>";
            main_process = "<?=$process_option[$i][0][0]?>";
          <?}else{?>
            
            main_process_query = "<?=$function_process_column?>='<?=$process_option[$i][0][0]?>'";
            main_process = "<?=$process_option[$i][0][0]?>";
          <?}

          if($process_option[$i][0][0] == "" || $process_option[$i][0][0] == "전체"){?>
            main_process_query = "";
          <?}

          break;
        }

        if($process_option[$i][0][0] == "" || $process_option[$i][0][0] == "전체"){?>
          main_process_query = "";
        <?}

      }


    ?>
      //window.alert(main_process_query);


        if (main_process != ""){
            $(".main_process_menu label").removeClass("active");
            $(".main_process_menu input[value='"+main_process+"']").parent().addClass("active");

        }else{ //전체
          $(".main_process_menu input[value='"+main_process+"']").parent().addClass("active");
        }
        

        //처리 상황별 버튼 클릭.
        $(".main_process_menu label").click(function() {
          main_process = $(this).find("input").val();
          main_process_query = $(this).attr("condition");


          
          dataTableDraw();
        });
    <?}?>


    <?
    //multi_search_query 생성
    if($function_multi_search == "on"){
    ?>
      makeMultiSearchQuery();
    <?}?>
      dataTableDraw();
} );

</script>

<script>


var dataTable;
function dataTableDraw(){

  //console.log("테이블 호출전 keyword_query:"+keyword_query);
  //console.log("테이블 호출전 키워드:" + search_keyword);

  $('#datatable-main th[column]').each(function(){  //서버에서 sorting 을 위한 칼럼별 index 정보 전송
      column_list += $(this).index()+":"+$(this).attr("column")+"/";
      //console.log($(this).index()+":"+$(this).attr("column"));
  });
  

  //console.log("complain_status:"+complain_status);

      dataTable = $('#datatable-main').DataTable( {




        <?
        $datatable_setting_file = 'contents/'.$folder_name.'/js/datatable_setting.js.php';
        $is_file_exist = file_exists( $datatable_setting_file);

        if($base_order_column == ""){
          $base_order_column = 0;
        }

        if($base_order_sort == ""){
          $base_order_sort = "desc";
        }

        if($order_by_sort == ""){
          $order_by_sort = "desc";
        }


        if ($is_file_exist) {
          include($datatable_setting_file);
        }else{?>

            "processing": true,
            "serverSide": true,
            "destroy":true,  //reinstall 이 가능하도록 허용.

            "language" : lang_kor,
            "searching" : <? if($process_keyword_display==1){echo "true";}else{echo "false";}?>,

            "pageLength": 20,//페이지당 기본 row 개수
            "lengthMenu" : [ [ 10, 20, 30, 50, 100, 500], [10, 20, 30, 50, 100, 500] ], //페이지당 볼 row 개수 선택하기 dropdown 메뉴
            //"dom": "Blfrtip", /* Bfrtip :버튼만 보이고 pagelength  사라짐, Blfrtip: Button, Length  모두 보임 */
            //"dom": '<"top"lfi>rt<"bottom"lip><"clear">',
            "stateSave": false,

            "order":[<?=$base_order_column?>,"<?=$base_order_sort?>"], //모든 페이지 기본 정열 key_column 기준 역순

            <?
            if($dom_setting != ""){
            ?><?=$dom_setting?><?
            }else{
              if($this_agent == "mobile"){?>
                "dom": '<"top">rt<"bottom"p><"clear">',
    
                <?}else{?>
                  "dom": '<"top"lfip>rt<"bottom"lfip><"clear">',
    
                <?}
                
            }?>




            /*
            l - length changing input control
            f - searchbox (filtering input)
            t - The table!
            i - Table information summary
            p - pagination control
            r - processing display element
            */


            //다운로드 버튼보이기
            //"dom": '<"top"lfip>rt<"bottom"Blip><"clear">',  
            //"buttons": [        'copy', 'excel', 'pdf'    ],


        <?}
      ?>


      "ajax":{
          url :ajax_get_url, // json datasource
          type: "post",  // method  , by default get
          data:function ( d ) {  //search 결과에 날짜 및 변화되는 변수값을 반영하여 검색하기 위해서는 function 형태로 해야 됨.
                return $.extend( {}, d, {

                  <?
                    if(isset($other_table_column) && $other_table_column != ""){?>
                      "other_table_column" : "<?=$other_table_column?>",
                  <?}?>

                  <?
                    if(isset($main_filter_query) && $main_filter_query != ""){?>
                      "main_filter_query" : "<?=$main_filter_query?>",
                  <?}?>

                  <?
                    if(isset($order_by_column) && $order_by_column != ""){?>
                      "order_by_column" : "<?=$order_by_column?>",
                      "order_by_sort" : "<?=$order_by_sort?>",

                  <?}?>

                  <?
                  if($function_process_search == "on"){
                  ?>
                  "main_process_column" : "<?=$function_process_column?>",
                  "main_process" : main_process,
                  "main_process_query" : main_process_query,

                  <?}?>

                  


                  <?
                  if($function_multi_search == "on"){
                  ?>
                  "multi_search_query" : multi_search_query,
                  <?}?>

                  "multi_column_search_string" : multi_column_search_string,


                  <?
                  if($function_date_search == "on"){
                  ?>
                  "date_apply" : date_apply,
                  "start_date" : startDate,
                  "end_date"   : endDate,
                  "date_part"  : date_part,
                  <?}?>

                  <? if($function_keyword_search == "on"){?>
                      "search_column": $("select[name='search_column']").val(),   //search 할 대상 칼럼 지정 정보.
                      "keyword_query" : keyword_query,
                      "search_keyword" : search_keyword,
                      "keyword_column_selected_index" : keyword_column_selected_index,

                  <?}?>

                  "scol" : scol,
                  "sval" : sval,
                  <? if(isset($_GET['filter_type']) && $_GET['filter_type'] != ""){?>
                  "filter_query" : filter_query,

                  <?}?>



                  /*"request_action" : request_action,
                  "report_part"  : report_part, */
                  "db_part" : "<?=isset($db_part)?$db_part:''?>",

                  "select_filter" : select_filter,
                  "custom_filter" :custom_filter,

                  "table_name" : "<?=$table_name?>",
                  "key_column_name" : "<?=$key_column_name?>",
                  "folder_name" : "<?=$folder_name?>",

                  "column_list" : column_list  //soring index 정보 전달


                } );
          }
          
      },
    /*

    */
      // 서버에서 받은 값을 순서에 구애받지 않고 임의의 칼럼에 넣고 싶을때.
      "columns": [

      <?
          //칼럼별로 할당할 데이터 세팅
          for ($i=0;$i<count($column_array);$i++ )
          {
            //exec_td1, 등 exec 이 포함된 칼럼명으로 정하면 null 값으로됨.
            if(strpos($column_array[$i]['column'], "exec") !== false) {//문자열 포함?>  
                { "data": null ,
                  "defaultContent": ""
                },
            <?} else {   //문자열 없음?>
                { "data": "<?=$column_array[$i]['column']?>",
                  "defaultContent": ""
                <?
                   if(isset($numeric_columns) && is_iterable($numeric_columns)){
                    for ($j=0;$j<count($numeric_columns);$j++ )
                    {
                      if($column_array[$i]['column'] == $numeric_columns[$j]){?>
                          ,"type" : "num"
                      <?}

                    }
                  }
                ?>
                
                },
            <?}  
            
          }
      ?>

          /*,{ data: "salary", render: $.fn.dataTable.render.number( ',', '.', 0, '$' ) } */
      ],

      //특정 target 번째 칼럼의 고정된 html값 설정
      "columnDefs": [ 


          <?
          //칼럼별로 할당할 class명, column명 세팅
          //반드시 columnDefs 에서 할당해줘야 "수정"모드에서 오류없이 정확한 셀에 정확한 class가 할당된다.  
          for ($i=0;$i<count($column_array);$i++ )
          {
            $col = $column_array[$i];
            $column = $col['column'];


            // if($col['option_count'] == 0){
            //   continue; //기타 속성이 설정되어 있지 않으면 다음 칼럼으로 패스
            // }
            ?>
            {
              "targets": <?=$i?>,
              
              //sorting 금지 칼럼
              <? if(isset($col['unsortable']) && $col['unsortable'] == "Y"){?>
                "orderable": false,
              <?}?>

              //sorting 시 number 형식기준
              <? if(isset($col['numeric']) && $col['numeric'] == "Y"){?>
                "type": "num",
              <?}?>


              //처음 datatable 로딩시 안보여질 칼럼
              <? if(isset($col['first_hide']) && $col['first_hide'] == "Y"){?>
                "visible" : false,
              <?}?>

              "render": function(data, type, full, meta) {
                  
                    <?if(isset($col['type']) && $col['type'] != ""){?>
                        var col = <?=json_encode($col,JSON_UNESCAPED_UNICODE)?>;
                        return convertDataType(col,"<?=$col['type']?>",data,full);   
                    <?}else{?>
                        return datatableRender("<?=$column?>",data,full);
                    
                    <?}?>
              },



              //td 속성 설정
              "createdCell":  function (td, cellData, rowData, rowIndex, colIndex) {
                //td의 class마다 column명 넣고, column이라는  attr에도 column명 입력


                //console.log(rowData);
                <? if($column != ""){?>$(td).addClass("<?=$column?>").attr("column","<?=$column?>");<?}?>


                //수정가능한 td에는 각 수정가능한 항목의 original value를 저장
                <? if(isset($col['edit']) && $col['edit'] != ""){?>
                  $(td).addClass("editable").attr("edit_type","<?=$col['edit']?>").attr("<?=$column?>",rowData.<?=$column?>);


                  <? if($col['edit'] == "editable_custom"){?>
                    $("#input_sample").find("div.<?=$column?>.edit").find("input,textarea,select").each(function(index){
                      var this_name = $(this).attr("name");
                      $(td).attr(this_name,rowData[this_name]);

                    });
                  <?}else if($col['edit'] == "editable_img"){?>
                      $(td).addClass("editable_img");

                       <? 
                       if($col['edit_origin_column'] != ""){?>
                          $(td).attr("origin_column",'<?=$col['edit_origin_column']?>');
                          $(td).attr("<?=$col['edit_origin_column']?>",rowData.<?=$column?>);
                       <?}?>
                          


                    <?}?>
                <?}?>



                <? if(isset($col['join_table_name']) && $col['join_table_name'] != ""){?>
                    $(td).attr("join_table_name","<?=$col['join_table_name']?>");
                    $(td).attr("join_table_key_name","<?=$col['join_table_key_name']?>");
                    $(td).attr("join_table_key_value",rowData.<?=$col['join_table_key_name']?>);

                <?}?>



                <? if(isset($col['css']) && $col['css'] != "" && count($col['css']) > 0){
                     for ($j=0;$j<count($col['css']);$j++ )
                     {?>
                        
                        $(td).css("<?=$col['css'][$j][0]?>","<?=$col['css'][$j][1]?>");
                     <?}
                }?>


                <? if(isset($col['class']) && $col['class'] != "" && count($col['class']) > 0){
                     for ($j=0;$j<count($col['class']);$j++ )
                     {?>
                        $(td).addClass("<?=$col['class'][$j]?>");
                     <?}
                }?>


                <? if(isset($col['type']) && $col['type'] == "convert"){?>
                     var type_value = <?=json_encode($col['type_value'],JSON_UNESCAPED_UNICODE)?>;
                     for (var j=0;j<type_value.length;j++ )
                     {
                         var v1 = type_value[j][0];
                         if(v1 == rowData.<?=$column?>){
                             $(td).attr("<?=$column?>_text",type_value[j][1]);
                         }
                    }
                <?}?>


                <? if(isset($col['type']) && ($col['type'] == "select" || $col['type'] == "select2" || $col['type'] == "select2Idx")){?>
                    var type_value = <?=json_encode($col['type_value'],JSON_UNESCAPED_UNICODE)?>;
                    $(td).attr("<?=$column?>_text",rowData[type_value[0]]);
                <?}?>


                //console.log("check:"+$(td).attr("column"));
                datatableCreatedCell("<?=$column?>",$(td),rowData);
              }
            },
        <?}?>
],


//데이터 ajax 로딩이 끝났음을 알리며 호출됨. -> search 가 완료되었을때는 호출이 안되는 버그??현상 있어서 search완료되었을때는 drawcallback으로 하면 됨.
"initComplete": function(settings, json) {   

    /** 엔터쳤을때만 검색 시작되도록, 지우면 키보드 누를때마다 작동되어서 서버사이드 일때는 불필요한 쿼리가 반복되어 불편, */
    $('#datatable-main_filter input').unbind();
    $('#datatable-main_filter input').bind('keyup', function(e) {
        var num = $("#datatable-main_filter select[name='search_column']").find("option").index($("#datatable-main_filter select[name='search_column']").find("option:selected"));

        keyword_query = setKeywordQuery(num);
        search_column = $("select[name='search_column']").val();

        //console.log("keyword_query pre:"+keyword_query);
        dataTable.search(this.value);
        search_keyword = this.value;

        keyword_column_selected_index = num;

        if(e.keyCode == 13) {
          //console.log("keyword_query:"+keyword_query);
          //dataTable.search( this.value ).draw();
          dataTable.draw();
        }

    }); 

    //$('#datatable-main_filter input').val('<?=isset($_GET['sval'])?$_GET['sval']:''?>').trigger("keyup");


    // 각 칼럼별 검색 기능 추가
    var api = this.api();
    // Apply the search
    api.columns().every(function() {
      var that = this;

      $('input', this.footer()).on('keyup change', function(e) {
        if(e.keyCode == 13) {
          if (that.search() !== this.value) {
            that
              .search(this.value)
              .draw();
          }
        }
      });

    });

    //검색버튼 추가
    $searchButton = $("<button class='datatable_btn_search btn btn-dark'>").text('검색').click(function() {
                        api.search($('#datatable-main_filter input').val()).draw();
                       });

    <?
     if($this_agent == "mobile"){?>
    $('.dataTables_filter').append($searchButton);


     <?}else{?>
      $('.dataTables_filter').prepend($searchButton);

     <?}

    ?>
  




    //$("#total_list_cnt").text("총: "+json.recordsFiltered+"건");
    if(json.recordsFiltered != undefined){
        $("#total_list_cnt").text('총: '+json.recordsFiltered+'건');
    }
},


"createdRow": function( row, data, dataIndex ) {
  //$(row).find('td:eq(-1)').css("min-width","50px"); //두글자 최소 50px
  //$(row).find('td:eq(2)').css("max-width","100px"); //브랜드



  //$(row).attr("idx",1); //TR에 attr 추가
  //$(row).find('td:eq(2)').attr('data-validate', data.DT_RowData.user_name);  //TD 에 attr 추가.
  /*
  if(data.DT_RowData.category_name == "외식"){
    $(row).attr("success","ok");
  }

  $(row).attr("dataIndex",dataIndex);
  //console.log(data); 
  */


},

<?

if(isset($footer_sum_display) && $footer_sum_display=="on"){?>

"footerCallback": function (row, data, start, end, display) {

var api = this.api();

// Remove the formatting to get integer data for summation
var intVal = function (i) {
    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
};

// Total over all pages
total_amount = api
    .column(2)
    .data()
    .reduce(function (a, b) {
        return intVal(a) + intVal(b);
    }, 0);

// Total over this page
pageTotal_amount = api
    .column(2, { page: 'current' })
    .data()
    .reduce(function (a, b) {
        return intVal(a) + intVal(b);
    }, 0);

total_error = api
    .column(3)
    .data()
    .reduce(function (a, b) {
        return intVal(a) + intVal(b);
    }, 0);

// Total over this page
pageTotal_error = api
    .column(3, { page: 'current' })
    .data()
    .reduce(function (a, b) {
        return intVal(a) + intVal(b);
    }, 0);



// Update footer
//$(api.column(2).footer()).html(pageTotal + ' (' + total + ' total)');

$(api.column(1).footer()).html("합계|평균");

$(api.column(2).footer()).html(pageTotal_amount);
$(api.column(3).footer()).html(pageTotal_error);
$(api.column(4).footer()).html(((pageTotal_error/pageTotal_amount)*100).toFixed(2)+"%");


}


<?}?>




} );


// dataTable.on( 'search.dt', function () {
//     //window.alert(dataTable.search());
// } );




dataTable.on( 'draw.dt', function () { //redraw 할때 마다 이벤트 호출. .dt
    var json = dataTable.ajax.json();

    try {
      try{
        $("#total_list_cnt").text('총: '+json.recordsFiltered+'건');
        //console.log("recordsFiltered:"+json.recordsFiltered);

      }catch(error){
        //console.log(error.message);
      }

      if(typeof(json.multi_column_search_string) !== 'undefined'){
        var multi_column_search_string = json.multi_column_search_string;
        var exp = multi_column_search_string.split("/");
        for(var i=0;i<exp.length;i++){
          var ms = exp[i].split("=");
          $("th input[name='"+ms[0]+"']").val(ms[1]);
        }
      }

      <?
        include('contents/'.$folder_name.'/js/draw_dt.js.php')
      ?>

      sval = "";
      scol = "";
      //filter_type = json.filter_type; //필터는 다시 정의 안해도 계속 유지되어야 함.
      keyword_query = setKeywordQuery(keyword_column_selected_index);

      
      $("select[name='search_column'] option:eq("+keyword_column_selected_index+")").attr("selected","selected");
      //dataTable.search(json.search_keyword);
      search_keyword = json.search_keyword;


      $('#datatable-main_filter input').val(json.search_keyword).keyup();

      //console.log("결과화면 키워드 쿼리:" + keyword_query);
      //console.log("결과화면 키워드:" + json.search_keyword);



      if(json.sql_join != undefined){
          //console.log('sql_join:'+json.sql_join);
          //$("#query_div").html(json.sql_join);
      }else{
        //$("#query_div").html("");
      }




      if(json.keyword_query != undefined){
          //console.log('keyword_query_result:'+json.keyword_query);
          //$("#search_query_div").html(json.keyword_query);
      }else{
        //$("#search_query_div").html("");
      }




      // if(json.sql_where_after != undefined){
      //     //console.log('sql_where_after:'+json.sql_where_after);
      // }

      

 


    } catch (error) {
      //console.log(`Error: ${error.message}`);
    }



    


    
});




<?
if(count($multi_column_search) > 0){ 
  $js_array = json_encode($multi_column_search);
  ?>
  var multi_column_search = <?=$js_array?>;

  for(var i=0;i<multi_column_search.length;i++){
    
    //칼럼별 검색 기능 지원
    // $('#datatable-main tfoot td').each(function(index) {
    //   //var title = $(this).text();
    //   var column_name = $('#datatable-main thead th:eq('+index+')').attr("column");
    //   if(column_name == multi_column_search[i][0]){
    //     $(this).html('<input type="text" name="'+multi_column_search[i][1]+'" class="column_search_input " placeholder="'+multi_column_search[i][2]+' 검색" style="min-width:50px; padding:5px;"/>');
    //   }
    // });

    //칼럼별 검색 기능 지원
    $('#datatable-main thead th').each(function(index) {
      //var title = $(this).text();
      var column_name = $(this).attr("column");
      if(column_name == multi_column_search[i][0]){
        $(this).find("input.column_search_input").remove();
        $(this).find("br.column_search_br").remove();

        $(this).append('<br class="column_search_br"><input type="text"  name="'+multi_column_search[i][1]+'"  autocomplete="off" class="column_search_input" placeholder="'+multi_column_search[i][2]+' 검색" style="min-width:50px; padding:5px;"/>');
      }
    });
  }

  //$("#datatable-main_wrapper tbody").append($("#datatable-main_wrapper tfoot tr:eq(0)").clone(true));


<?}?>






//목록 보여줄때 보여주는 형식 변환하여 보여주
function convertDataType(col,format_type,data,full){
    var column = col.column;

    var rValue;

    switch (format_type) {
        
        case "convert":
            for(var i=0;i<col.type_value.length;i++){
               if(data == col.type_value[i][0]){
                   rValue = col.type_value[i][1];
                   break;
               }
            }

            if(typeof rValue === 'undefined' ){
                rValue = data;
            }

            break;
        case "select":
            rValue = "<span class='edit_area "+col.type_value[0]+"'>"+full[col.type_value[0]]+"</span><br><span class='etc "+column+" '>";
            break;
        case "select2":
            rValue = "<span class='edit_area "+col.type_value[0]+"'>"+full[col.type_value[0]]+"</span><br><span class='etc "+column+" '>";
            break;
        case "select2Idx":
            rValue = "<span class='edit_area "+col.type_value[0]+"'>"+full[col.type_value[0]]+"</span><br><span class='etc "+column+" '>("+data+")</span>";
            break;
        case "selectDropDown":

            var btn_group_color = 'btn-default';
            for(var i=0;i<col.type_value.length;i++){
                if(data == col.type_value[i][0]){
                    btn_group_color = col.type_value[i][2];
                    break;
                }
            }

            


            var base_option = "";
            var li = "";
            for(var i=0;i<col.type_value.length;i++){

               if(data == "" || data == null){
                  if(col.type_value[i][0] == ""){
                    base_option = col.type_value[i][1];
                  }
               }

               if(col.type_value[i][3] == "divider"){ //분리선
                  li += `<li class="divider"></li>`;
               }
               li += `<li><a href="#" value="`+col.type_value[i][0]+`" selected_color="`+col.type_value[i][2]+`">`+col.type_value[i][1]+`</a>
                      </li>`;
            }

            var default_text = data;
            if(data == "" || data == null){
              default_text = base_option;
            }
            rValue = `<div class="btn-group" col_name="`+column+`">
                        <button type="button" class="io_status btn `+btn_group_color+`" value="`+data+`">`+default_text+`</button>
                        <button type="button" class="btn  `+btn_group_color+` dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          <span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                        `+li+`
                        </ul>
                      </div>`;

            break;

        case "img": //이미지 썸네일

            var img_width = "100";
            var img_unique_idx;
            var img_title;
            for(var i=0;i<col.type_value.length;i++){

                switch (col.type_value[i][0]) {
                    case "unique_idx":
                        img_unique_idx = col.type_value[i][1];
                        break;
                    case "img_title":
                        img_title = col.type_value[i][1];
                        break;
                    case "width":
                        img_width = col.type_value[i][1];
                        break;
                    default:
                        break;
                }

            }

            

            if(data == "" || full.cu_status=="판매취소" || full.cu_status=="매입불가확인"){
                rValue = "<input type='hidden' name='menu_img_url'>";
            }else{
                rValue = "<a href='"+data+"'  data-lightbox='"+column+"_img_"+full[img_unique_idx]+"' data-title='"+full[img_title]+"'    ><img src='"+data+"' style='width:"+img_width+"px' > </a>";

            }
        
            
            break;

        case "button_onoff": //버튼하나에 on/off 기능

            //console.log("col.type_value:"+col.type_value);
            if(col.type_value == undefined || col.type_value == ""){
                rValue = data; //옵션값 설정이 없으면 값 그대로 텍스트로 노출
                break;
            }

            var btn_color = col.type_value[2];
            if(btn_color == "" || btn_color == undefined){
                btn_color = "btn-primary";
            }
            
            var rValue = "";
            if(data == col.type_value[0][0] || data == 1 || data == "Y" || data == "on"){
                rValue = "<button class='btn button_onoff "+btn_color+"' name='"+column+"'  on_value='"+col.type_value[0][0]+"'  on_text='"+col.type_value[0][1]+"' off_value='"+col.type_value[1][0]+"'  off_text='"+col.type_value[1][1]+"'  btn_color='"+btn_color+"'  value='"+col.type_value[0][0]+"'>"+col.type_value[0][1]+"</button>";
            }else if(data == col.type_value[1][0] || data == 0 || data == "N" ||  data == "off"){
                rValue = "<button class='btn button_onoff btn-defalut'  name='"+column+"'  on_value='"+col.type_value[0][0]+"'  on_text='"+col.type_value[0][1]+"' off_value='"+col.type_value[1][0]+"'  off_text='"+col.type_value[1][1]+"'   btn_color='"+btn_color+"'  value='"+col.type_value[1][0]+"'>"+col.type_value[1][1]+"</button>";

            }else{
                rValue = "<button class='btn button_onoff btn-defalut'  name='"+column+"' on_value='"+col.type_value[0][0]+"'  on_text='"+col.type_value[0][1]+"' off_value='"+col.type_value[1][0]+"'  off_text='"+col.type_value[1][1]+"'   btn_color='"+btn_color+"'  value='zero'>---</button>";
            }
            break;
    
        case "datetime2line": //등록일<br>수정일
            if(col.type_value == undefined || col.type_value == ""){
                rValue = data+"<br>"+full.update_datetime; //날짜 옵션값 없으면 기본적으로 등록일/수정일
                break;
            }else{ //날짜 옵션값이 있으면
                for(var i=0;i<col.type_value.length;i++){
                  if(i>0){
                      rValue += "<br>";
                      rValue += full[col.type_value[i]];
                  }else{
                    rValue = full[col.type_value[i]];
                  }
                  
                }
                //rValue = full[col.type_value[0]]+"<br>"+full[col.type_value[1]];
            }
            
            break;

        case "ellipsis": //말줄임표 처리
            var display_length = 18;  //보여질 기본 글자수

            if(col.type_value == undefined || col.type_value == ""){
                //
            }else{
                if(col.type_value == undefined || col.type_value == ""){
                    //
                }else{ //날짜 옵션값이 있으면
                    var display_length = col.type_value;
                }
            }


            if(data.length > display_length+2){
                rValue = '<span title="'+data+'">'+data.substr( 0, display_length )+'...</span>';
            }else{
                rValue = data;
            }
            break;

        case "exec":
            rValue = "<button type='button' class='btn btn-dark btn-xs btn_modify'>수정</button>";
            break;


        case "exec2":
            rValue = "<button type='button' class='btn btn-dark btn-xs btn_modify' mode='del_forbidden'>수정</button>";
            break;

            
            
        case "exec_colorbox_view":
            rValue = "<button type='button' class='btn btn-dark btn-xs btn_colorbox_view' >보기</button>";

            break;

        case "exec_colorbox_edit":
            rValue = "<button type='button' class='btn btn-dark btn-xs btn_colorbox_edit' >수정</button>";

            break;    

        case "admin_exec":
            rValue = "<button type='button' class='btn btn-dark btn-xs btn_modify'>수정</button>";
            rValue += "<button type='button' class='btn btn-primary btn-xs btn_permission_setting' data-toggle='modal' data-target='.bs-example-modal-lg'>소속/권한</button>";

            break;
        case "custom_exec":

            rValue = col.type_value;
            
            break;

        // case "empty":
        //     rValue = " ";
        //     break;

        
        default:
            rValue = "";

            break;
    }


    return rValue;
}

/*
//첫번째 칼럼에 index 숫자 넣을때.
dataTable.on( 'order.dt search.dt', function () {
dataTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
      cell.innerHTML = i+1;
});
}).draw();
*/


<?if($function_keyword_search == "on"){?>

var $search_column = $("#search_select_sample > div").clone(true);
$("#datatable-main_wrapper .dataTables_filter").append($search_column);

if(typeof search_column1 !== "undefined"){
 $("#datatable-main_wrapper .dataTables_filter").append(search_column1);
}
if(typeof search_column2 !== "undefined"){
 $("#datatable-main_wrapper .dataTables_filter").append(search_column2);
}
//$(".dataTables_filter label").addClass("pull-right");
//$(".dataTables_filter label").addClass("pull-right");
<?}?>

}

  <?
    //if($function_add == "on"){?>
    
    //var add_btn_title = "<?=isset($add_btn_title)?$add_btn_title:''?>";
    //php 배열을 js 배열로, 한글안깨지게처리.
    //var add_array = <?=json_encode($add_array,JSON_UNESCAPED_UNICODE)?>;
    var modal_array = <?=json_encode($modal_array,JSON_UNESCAPED_UNICODE)?>;

    //makeAddForm(add_btn_title ,add_array);    =>  dataTables.ajax.button.js 첫줄로 이동.
    <?
    
    //}
  ?>



</script>

<!--
<script type="text/javascript" src="./js/bootstrap-filestyle.min.js"> </script> 
-->

<?php
if(file_exists('./contents/'.$folder_name.'/css/style.css')){?>
<link href="./contents/<?=$folder_name?>/css/style.css?ver=<?=time()?>" rel="stylesheet">

<?}

if(file_exists('./contents/'.$folder_name.'/js/button_override.js')){?>
<script src="./contents/<?=$folder_name?>/js/button_override.js?ver=<?=time()?>"></script><!-- 수정,저장,취소,삭제 개별기능 정의 dataTables.ajax.button 보다 앞에 와야 함.--> 

<?}

if(file_exists('./contents/'.$folder_name.'/js/page.js')){?>
<script src="./contents/<?=$folder_name?>/js/page.js?ver=<?=time()?>"></script> <!-- 페이지별 개별 jquery 기능 정의 -->

<?}
?>


<!-- datepicker 날짜 검색 기능 필요하면 주석해제 ---->

<script type="text/javascript" src="js/moment/moment.min.js?ver=<?=time()?>"></script>
<script type="text/javascript" src="js/datepicker/daterangepicker.js?ver=<?=time()?>"></script>


<script type="text/javascript" src="js/datepicker/datepicker_custom.js?ver=<?=time()?>"></script>




