<?


$folder_name = "sj/storage_product";  //폴더명
$title ="제품관리";

$table_name ="product";
$key_column_name ="product_idx";

$function_date_search  = "on";   // 날짜검색기능 on ,off
$function_process_search = "off";  // 중앙 버튼식 선택메뉴 on ,off
$function_multi_search = "on";  // 체크박스 선택된 칼럼 검색
$function_keyword_search = "on";    // 키워드검색기능 on ,off
$function_add = "on";    // 추가하기 on ,off
$main_filter_query = "category_idx=4";


$date_search_display = "hide";//수정없음
if($function_date_search == "on"){
  //날짜검색 옵션 넣을 경우
  $function_date_column['regist_datetime'] = "등록일";      //날짜 검색 옵션칼럼 + 보여지는문구
  $function_date_column['update_datetime'] = "내용변경일";  //날짜 검색 옵션칼럼 + 보여지는문구
  //-------------------------------------------------------------------------
  $function_date_column_selected = "regist_datetime";      //기본 선택옵션 value
  $date_search_display = "";//수정없음
}



$process_search_display = "hide";//수정없음
// if($function_process_search == "on"){  
//   $function_process_column = "current_coupon_count";  //DB에서 필터검색에 적용할 칼럼명

//   $process_option['재고있음'] = "재고있음";
//   $process_option['재고없음'] = "재고없음";

//   $function_process_option_selected = "재고있음";      //기본 선택옵션 value
//   $process_search_display = "";//수정없음
// }





//멀티 검색 필터 설정
if($function_multi_search == "on"){
    
  $multi_search_list = [];

  array_push($multi_search_list,["checkbox","all","전체","","checked"]);
  // array_push($multi_search_list,["checkbox","t_new_menu_kinds_count","신상품","(t_new_menu_kinds_count > 0)",""]);  //checkbox 인경우에만 끝에 checked 또는 공백
  // array_push($multi_search_list,["radio","current_coupon_count",["재고있음","재고없음"],["current_coupon_count > 0","(current_coupon_count = 0 or current_coupon_count is null)"],"","deselectable"]); //5번째값 (index 4) : 에는 기본적으로 선택될 index 값을 입력, 없으면 ""
  // array_push($multi_search_list,["radio","is_show_seller",["매입중","매입중지"],["is_show_seller = 1","(is_show_seller=0 or is_show_seller is null)"],"","deselectable"]);
  // array_push($multi_search_list,["radio","is_show_buyer",["판매중","판매중지"],["is_show_buyer = 1","(is_show_buyer=0 or is_show_buyer is null )"],"","deselectable"]);

}




if($function_keyword_search == "on"){
  //DB에서 키워드 검색에 적용할 칼럼명------------------------------------------



  $keyword_column_array = [];

  array_push($keyword_column_array,["제품명","product_name","like"]);   //로딩시 첫 옵션 기본선택됨. dataTables.ajax.button.js 에서 keyword_query = setKeywordQuery(0); 에 의해 첫번째 옵션이 기본옵션으로 선택됨.

  array_push($keyword_column_array,["고객사","company_name","like","join",["consulting.consulting"],["consulting_idx","consulting_idx"]]);

  // array_push($keyword_column_array,["브랜드IDX","brand_idx","="]);
  // array_push($keyword_column_array,["카테고리","t_category_name","like"]);
  // array_push($keyword_column_array,["키워드","brand_keyword","like"]);
  // array_push($keyword_column_array,["등록관리자명","cf_admin_name","like"]);
  // array_push($keyword_column_array,["메뉴종류>=N","t_menu_count",">="]);

  // array_push($keyword_column_array,["쿠폰보유량>=N","t_brand_coupon_count",">="]);
  // array_push($keyword_column_array,["최대할인율>=N","t_max_discount_percent",">="]);

  //-------------------------------------------------------------------------
  

  $keyword_column_array_selected = "product_name";      //기본 선택옵션 value

  $process_keyword_display = 1; //수정없음
}else{
  $process_keyword_display = 0; //수정없음
}





/*th 헤더 정보 입력 */
$th_info = [];

array_push($th_info,["product_idx","고유번호"]);
array_push($th_info,["product_name","제품명"]);

array_push($th_info,["consulting_idx","고객사"]);

array_push($th_info,["display","구매페이지<br>노출여부","convert",[    ["on","노출"],["off","숨김"]    ] ]);


array_push($th_info,["display_order","노출순서"]);

array_push($th_info,["base_storage_idx","기본출고지","select2",["t_base_storage_name"] ]);

array_push($th_info,["sum_current_count","현재재고수량<br>(미입고수량제외)"]);
array_push($th_info,["delivery_unit","배송단위"]);
array_push($th_info,["delivery_fee","배송비"]);
//array_push($th_info,["admin_memo","메모"]);



// array_push($th_info,["regist_datetime","등록일"]);
// array_push($th_info,["update_datetime","수정일"]);


//array_push($th_info,["category_idx","카테고리" ]);
// array_push($th_info,["category_idx","카테고리","select2Idx",["t_category_name"] ]);
// array_push($th_info,["t_naver_category_name","네이버카테고리"]);

// array_push($th_info,["cache_img_url","로고","img",[ ["unique_idx","brand_idx"],["img_title","brand_name"],["width","100"] ]   ]);

// array_push($th_info,["brand_name","브랜드명"]);
// array_push($th_info,["is_show_seller","매입여부","convert",[    ["1","매입중"],["0","매입중지"]    ] ]);
// array_push($th_info,["is_show_buyer", "판매여부","convert",[    ["1","판매중"],["0","판매중지"]    ] ]);
// array_push($th_info,["brand_display_order","노출순서"]);

// array_push($th_info,["t_menu_count","보유쿠폰 메뉴종류"]);
// array_push($th_info,["t_brand_coupon_count","보유쿠폰수량"]);
// array_push($th_info,["t_max_discount_percent","현재최대할인율"]);


// array_push($th_info,["brand_keyword","브랜드키워드"]);
// array_push($th_info,["brand_ex","상세내용","ellipsis", "18"]);

array_push($th_info,["regist_datetime","등록일"]);
//array_push($th_info,["exec","실행","exec"]);

array_push($th_info,["exec_qr","바코드출력"]);

array_push($th_info,["exec","실행","exec"]);



//$other_table_column = "a:cache_url(cache_img_url) as cache_img_url"; 
//$other_table_column = "a:cache_url(brand_img_url) as cache_img_url/a:category_idx:category:category_name/a:brand_idx:brand_explain:brand_ex"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.

//$other_table_column = "a:brand_img_url as cache_img_url/a:category_idx:category:category_name/a:brand_idx:brand_explain:brand_ex"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.
//$other_table_column = "a:user_idx:user:user_name+user_phone/a:user_idx:order_list:menu_name/order_list:order_list_idx:order_coupon:order_price"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.

$other_table_column = "a:consulting_idx:consulting.consulting:company_name";


//조인된 테이블의 칼럼값을 직접수정하는 경우.
$other_table_column_edit_array = [];
//array_push($other_table_column_edit_array,["brand_ex","brand_explain","brand_idx"]);  //th 칼럼이름,조인된 table_name, 연결 key_column_name
//array_push($other_table_column_edit_array,["brand_ex","brand_explain","brand_idx"]);  //th 칼럼이름,조인된 table_name, 연결 key_column_name








/************ 각 칼럼별 동시 검색 기능 구현할 칼럼 정의 */
$multi_column_search = [];//th column_name,  search_column_name, place_holder
//array_push($multi_column_search,["category_idx","category_name", "카테고리명"]);  //th column_name,  search_column_name, place_holder
//array_push($multi_column_search,["brand_name","brand_name", "브랜드명"]);  //th column_name,  search_column_name, place_holder





/*************칼럼 보기 그룹 버튼****************************************************************************************************************** */
$view_group_array = [];
// array_push($view_group_array,["기본보기",["brand_idx","category_idx","cache_img_url","brand_name","is_show_seller","is_show_buyer","brand_display_order","t_menu_count","t_brand_coupon_count","t_max_discount_percent","regist_datetime","exec"]]); //index 2번째 요소는 처음 datatable 화면 로딩시 숨겨질 칼럼으로 사용됨
// array_push($view_group_array,["키워드관리",["brand_idx","category_idx","naver_category_name","cache_img_url","brand_name","brand_keyword","brand_ex","regist_datetime","exec"]]);
// array_push($view_group_array,["모든칼럼","all"]);




/***************보여질 type 설정**************************************************************************************************************** */
$type_array = [];
//array_push($type_array,["regist_datetime","datetime2line",["regist_datetime","update_datetime"]]); //윗줄 날짜칼럼/아랫줄 날짜 칼럼/
//array_push($type_array,["category_onoff","button_onoff",[["on","노출"],["off","숨김"]],"btn-primary"]);  //on버튼/ off버튼
//array_push($type_array,["menu_ex","ellipsis",18]); //말줄임표. 18줄이상 줄임 이 기본 설정




/***************칼럼별 속성 지정**************************************************************************************************************** */
$unsortable_columns=[];
$padding_unset_columns = [];
$edit_hide_columns = [];

$unsortable_columns = ["sum_current_count","exec"];  //soring 기능 불가칼럼 지정.
//$padding_unset_columns = ["brand_idx","exec"];  //타이틀에 우측 padding 없앰
//$edit_hide_columns = ["detail","regist_datetime"]; //수정버튼 눌렀을때 잠시 안보이게 하는 칼럼



/*****************css 설정************************************************************************************************************** */

//css 설정
$css_array = [];

//array_push($css_array,["brand_name",["max-width","200px"]]);
//array_push($css_array,["",["min-width","70px"]]);
array_push($css_array,["product_name",["max-width","150px"]]);

array_push($css_array,["regist_datetime",["min-width","130px"]]);
array_push($css_array,["exec",["min-width","50px"]]);


array_push($css_array,["sum_current_count",["min-width","100px"]]);


/******************수정모드 설정************************************************************************************************************* */




//수정모드 설정
$edit_array = [];

array_push($edit_array,["product_name","제품명","필수","text"]);
//array_push($edit_array,["display","노출여부","","switch",[["on","노출","selected"],["off","숨김"]],"btn-primary","deselectable"]);
array_push($edit_array,["display","select",["options",[["on","노출"],["off","숨김"]]],["direct_save","1"],["same","N"]]);

array_push($edit_array,["display_order","노출순서","","text"]);



//기본출고지는 DB에서 가져오기
$sel0 = mysqli_query($dbcon, "select * from storage order by storage_name") or die(mysqli_error($dbcon));
$sel_num0 = mysqli_num_rows($sel0);

$storage_idx_array = [];
if ($sel_num0 > 0) {
  while($data0 = mysqli_fetch_assoc($sel0)) {
      $this_arr = array();
      array_push($this_arr,$data0['storage_idx']);
      array_push($this_arr,$data0['storage_name']);
      
      if($data0['storage_idx'] == "2"){array_push($this_arr,"SELECTED");} //서산창고 기본 selected

      array_push($storage_idx_array,$this_arr);      
  }   
}

array_push($edit_array,["base_storage_idx","select2",["options",$storage_idx_array],["direct_save","1"],["same","N"]]);




//기본출고지는 DB에서 가져오기
$sel0 = mysqli_query($dbcon, "select consulting_idx,company_name from consulting.consulting where ( consulting_status='계약완료' or consulting_status='계약종료') order by company_name") or die(mysqli_error($dbcon));
$sel_num0 = mysqli_num_rows($sel0);

$consulting_idx_array = [];
if ($sel_num0 > 0) {
  while($data0 = mysqli_fetch_assoc($sel0)) {
      $this_arr = array();
      array_push($this_arr,$data0['consulting_idx']);
      array_push($this_arr,$data0['company_name']);
      

      array_push($consulting_idx_array,$this_arr);      
  }   
}

array_push($edit_array,["consulting_idx","select2",["options",$consulting_idx_array],["direct_save","1"],["same","N"]]);




array_push($edit_array,["delivery_unit","배송단위","","text"]);
array_push($edit_array,["delivery_fee","배송비","","textarea"]);
array_push($edit_array,["memo","메모","","textarea"]);



/*************************************************새로 추가하기********************************************/
$modal_array = [];


$add_array = [];
array_push($add_array,["modal_info",["new","새로 추가하기"]]); //0: 모달창네임, 1: 모달창타이틀, 2: submit_url 3:적용할 table, 4: 적용할 row 고유키명



array_push($add_array,["product_name","제품명","필수","text"]);

array_push($add_array,["consulting_idx","고객사","필수","select2",$consulting_idx_array]); //add form 에서는 select2 버그로 작동 안하고 있음.

//array_push($add_array,["display_order","노출순서","","number"]);

//array_push($add_array,["base_storage_idx","select",["options",$storage_idx_array],["direct_save","1"],["same","N"]]);
array_push($add_array,["base_storage_idx","기본출고지","필수","select2",$storage_idx_array]); //add form 에서는 select2 버그로 작동 안하고 있음.



array_push($add_array,["delivery_unit","배송단위","","text"]);
//array_push($add_array,["delivery_fee","배송비","","textarea"]);
array_push($add_array,["memo","메모","","textarea"]);


// array_push($add_array,["brand_name","브랜드명","필수","text"]);
// array_push($add_array,["category_idx","카테고리","","select2"]); //DB column 기준이라서 brand_name 이 아니고,brand_idx로,
// array_push($add_array,["brand_img_url","브랜드로고","","image"]);

// array_push($add_array,["is_show_seller","매입여부","","switch",[["1","매입중","selected"],["0","매입중지"]],"btn-primary","deselectable"]);
// array_push($add_array,["is_show_buyer","판매여부","","switch",[["1","판매중","selected"],["0","판매중지"]],"btn-primary","deselectable"]);
// array_push($add_array,["brand_display_order","노출순서","","text"]);


// array_push($add_array,["brand_keyword","키워드","","textarea"]);
// array_push($add_array,["brand_ex","상세설명","","textarea"]);

// array_push($add_array,["chatbot_category_name","챗봇카테고리명","","text"]);
// array_push($add_array,["naver_category_name","네이버카테고리명","","text"]);
// array_push($add_array,["naver_category_code","네이버카테고리넘버","","text"]);

//-----------------------------------//
array_push($modal_array,$add_array);

/*----------------------모달창이 여러개 필요한 경우 $add_array[] 리셋후 계속 추가----------------------*/






/// 보기그룹------------------------------------------------------------------------------------------------
$filter = [];

// $column = "is_show_seller";
// $filter[$column]['column'] = $column;  //필터를 적용할 칼럼 이름
// $opt = ":--매입여부--/0:매입중지/1:매입중";  //"0:노출중단/1:노출"
// $filter[$column]['options'] = make_options_array($opt,""); 

// $column = "is_show_buyer";
// $filter[$column]['column'] = $column;  //필터를 적용할 칼럼 이름
// $opt = ":--판매여부--/0:판매중지/1:판매중";  //"0:노출중단/1:노출"
// $filter[$column]['options'] = make_options_array($opt,""); 




/************* *///보기 그룹이 선언된 경우 보기그룹별 배열 정리 시작 *****************************/





require('./contents/common/column_array.php');



require('./contents/common/datatable_html.php');







?>


  <!-----  샘플 영역 시작---->
  <div id="input_sample" class="hide">

    <?
      foreach($filter as $key => $value)
      {
        print_filter($key);
      }

    ?>




  </div>
  <!-----  샘플 영역 끝---->








<?
require('./contents/common/datatable.js.php');
//require('./contents/'.$folder_name.'/columnDefs.js.php');
?>



<script>

function datatableRender(column,data,full){

  var rValue;
  switch (column) {

    // case "exec_current_count":
    //        rValue = "";
    //        break;
    case "consulting_idx":

          rValue = full.company_name;
          //rValue += "<br>"+full.naver_category_code;
          break;
    case "base_storage_idx":

          rValue = full.t_base_storage_name;
          //rValue += "<br>"+full.naver_category_code;
          break;

    case "exec_qr":
          rValue = '<button type="button" class="btn btn-info btn-xs btn_barcode">바코드출력</button>';
          break;

    default:
          rValue = data;
          break;

  }




  return rValue;


}

function datatableCreatedCell(column,$td,rowData){
  switch (column) {
    case "consulting_idx":
      $td.attr('consulting_idx', rowData.consulting_idx); 
      $td.attr('company_name', rowData.company_name); 

      break;

    default:
      break;
  }
}



</script>


