<?
$folder_name = "cs";  //폴더명
$title ="쿠폰이용불가 신고내역";

$table_name ="coupon_complain";
$key_column_name ="coupon_complain_idx";

$function_date_search  = "on";   // 날짜검색기능 on ,off
$function_process_search = "on";  // 중앙 버튼식 선택메뉴 on ,off
$function_keyword_search = "on";    // 키워드검색기능 on ,off
$function_multi_search = "off";  // 체크박스 선택된 칼럼 검색
$function_add = "off";    // 추가하기 on ,off


$date_search_display = "hide";//수정없음
if($function_date_search == "on"){
  //날짜검색 옵션 넣을 경우
  $function_date_column['regist_datetime'] = "신고일";      //날짜 검색 옵션칼럼 + 보여지는문구
  $function_date_column['update_datetime'] = "내용변경일";  //날짜 검색 옵션칼럼 + 보여지는문구
  /*--------------------------------------------*/
  $function_date_column_selected = "regist_datetime";      //기본 선택옵션 value
  $date_search_display = "";//수정없음
}

$process_search_display = "hide";//수정없음
if($function_process_search == "on"){
  $function_process_column = "complain_status";  //DB에서 필터검색에 적용할 칼럼명
  /*--------------------------------------------*/

  $process_option_base = "처리전:처리전:complain_status='신고접수' or complain_status='처리보류' or complain_status is null/처리진행중:처리진행중:complain_status='환불예정'/처리완료:처리완료:complain_status='환불완료' or complain_status='재발행' or complain_status='대체상품발송' or complain_status='신고접수반려' or complain_status='신고취소' or complain_status='기타'";
  $process_options_list = ":전체/".$process_option_base;

  $process_option = make_options_array($process_options_list,"0"); //$return_arr,[$value,$text,"selected"],$condition);
  //$process_option = make_options_array("전체/송금대기:송금대기:amount > 0/송금중/송금완료/송금오류/송금취소","1");  // 2번째 옵션에 컨디션 조건을 넣고 싶을때.


  /*--------------------------------------------*/
  $process_search_display = "";//수정없음
}






  /* $keyword_column_array -> keyword_query로 변환되어 ajax 전송됨, 변환은 dataTables.ajax.button.js에서 수행*/
  if($function_keyword_search == "on"){
    //DB에서 키워드 검색에 적용할 칼럼명------------------------------------------
    $keyword_column_array = [];
    array_push($keyword_column_array,["바코드","barcode_number","like","join",["coupon"],["order_coupon","coupon_idx","coupon_idx"],["order_coupon_idx"]]);
    array_push($keyword_column_array,["구매자명","user_name","like","join",["user"],["user_idx","buy_user_idx"]]);
    array_push($keyword_column_array,["구매자전화","user_phone","like","join",["user"],["user_idx","buy_user_idx"]]);
    array_push($keyword_column_array,["판매자명","user_name","like","join",["user"],["user_idx","sell_user_idx"]]);
    array_push($keyword_column_array,["판매자전화","user_phone","like","join",["user"],["user_idx","sell_user_idx"]]);
    array_push($keyword_column_array,["메뉴명","menu_name","like","join",["menu"],["order_coupon","menu_master_idx"],["order_coupon_idx"]]);
    array_push($keyword_column_array,["브랜드명","brand_name","like","join",["brand"],["menu","brand_idx"],["order_coupon","menu_master_idx"],["order_coupon_idx"]]);
    array_push($keyword_column_array,["발행처","publisher","like","join",["coupon"],["order_coupon","coupon_idx"],["order_coupon_idx"]]);
    array_push($keyword_column_array,["사용지점","report_place","like"]);
    array_push($keyword_column_array,["담당자명","admin_name","like"]);
  

    /*--------------------------------------------*/
    $keyword_column_array_selected = "buy_user_name";      //기본 선택옵션 value
    $process_keyword_display = 1; //수정없음
  }else{
    $process_keyword_display = 0; //수정없음
  }








/*************th 헤더 정보 입력 ************************************************************************************** */
$th_info = [];


$th_info = [];

// array_push($th_info,["menu_master_idx","menu_master_idx"]);
// array_push($th_info,["menu_img_url","이미지","img",[ ["unique_idx","menu_master_idx"],["img_title","menu_name"],["width","100"] ]   ]);
// array_push($th_info,["brand_idx","브랜드명<br>(brand_idx)","select2Idx",["brand_name"]  ]);
// array_push($th_info,["menu_name","메뉴명"]);

// array_push($th_info,["is_show_seller","매입여부","convert",[    ["1","매입중"],["0","매입중지"]    ] ]);
// array_push($th_info,["is_show_buyer", "판매여부","convert",[    ["1","판매중"],["0","판매중지"]    ] ]);
// array_push($th_info,["t_new_menu_kinds_count","신상품포함"]);
// array_push($th_info,["menu_original_price","정가<br>매입최저가<br>매입최고가<br>판매최저가<br>판매최고가"]);
// array_push($th_info,["current_coupon_count","판매재고<br>보유재고"]);
// array_push($th_info,["menu_keyword","메뉴키워드"]);
// array_push($th_info,["category_onoff","노출여부","button_onoff",[ ["on","노출"],["off","숨김"],"btn-primary" ]   ]);

// array_push($th_info,["menu_ex","상세내용","ellipsis", "18"]);
// array_push($th_info,["regist_datetime","등록일<br>수정일","datetime2line",   ["regist_datetime","update_datetime"]  ]);
// array_push($th_info,["exec","실행","exec"]);

// $other_table_column = "a:brand_idx:brand:brand_name/a:menu_master_idx:menu_explain:menu_ex"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.
// //$other_table_column = "a:user_idx:user:user_name+user_phone/a:user_idx:order_list:menu_name/order_list:order_list_idx:order_coupon:order_price"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.





array_push($th_info,["coupon_complain_idx","순번"]);
array_push($th_info,["menu_name","발행처<br>브랜드<br>메뉴명<br>바코드"]);
array_push($th_info,["request_action","요청사항"]);
array_push($th_info,["report_part","신고사유"]);
//array_push($th_info,["report_memo","내용<br>(지점)"]);
array_push($th_info,["complain_status","처리상황"]);
//array_push($th_info,["reward_point_amount","보상"]);
array_push($th_info,["contact_seller","판매자 컨택"]);
array_push($th_info,["admin_memo","관리자 메모"]);
array_push($th_info,["buy_in_price","매입/환불<br>회수/손실"]);
array_push($th_info,["sell_user_name","판매자<br>구매자"]);
//array_push($th_info,["buy_user_name","구매자"]);
array_push($th_info,["regist_datetime","신고시간"]);
//array_push($th_info,["t_admin_name","담당자"]);
array_push($th_info,["exec","실행","exec"]);


// date_format에 포함된 "/ 와 :" 는 아래 규칙에서 사용되므로 "/"는 ";;" 로, ":"는 ";"로 대체하여 표현, 이후에  getList에서 다시 친환됨
$other_table_column = "a:order_coupon_idx:order_coupon:coupon_idx+order_list_idx/order_coupon:coupon_idx:coupon:publisher+barcode_number+menu_master_idx/coupon:menu_master_idx:menu:menu_name/menu:brand_idx:brand:brand_name/a:buy_user_idx:user as buy_user:user_idx:user_name as buy_user_name/a:t_sell_user_idx:user as sell_user:user_idx:user_name as sell_user_name"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.
//$other_table_column = "a:user_idx:user:user_name+user_phone/a:user_idx:order_list:menu_name/order_list:order_list_idx:order_coupon:order_price"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.
//

/*************칼럼 보기 그룹 버튼********************************************************************************************* */
// $view_group_array = [];
// array_push($view_group_array,["기본보기",["","","","","","","","","","","exec"]]); //index 2번째 요소는 처음 datatable 화면 로딩시 숨겨질 칼럼으로 사용됨
// array_push($view_group_array,["키워드관리",["","","","","","","","exec"]]);
// array_push($view_group_array,["모든칼럼","all"]);


/***************칼럼별 속성 지정**************************************************************************************************************** */
$unsortable_columns = ["request_action","report_part","complain_status","admin_memo","buy_in_price","contact_seller","exec"];  //soring 기능 불가칼럼 지정.
//$numeric_columns = ["bt_amount","cf_order_coupon_idx","cf_order_list_idx"];  //soring 기능시 string이 아닌 number 순으로 sorting

$padding_unset_columns = ["coupon_complain_idx","request_action","report_part","complain_status","contact_seller","admin_memo","buy_in_price","t_admin_name"];  //타이틀에 우측 padding 없앰
$edit_hide_columns = ["t_admin_name","regist_datetime","buy_user_name","sell_user_name"]; //수정버튼 눌렀을때 잠시 안보이게 하는 칼럼


/***************필터  dropdown 메뉴 출력 설정**************************************************************************************************************** */
$filter = [];

/*-------------------------*/
$column = "request_action";
$filter[$column]['column'] = $column;  //필터를 적용할 칼럼 이름
$options_request_action = ":--요청사항--/결제취소환불/기프티머니로환불/재발송";  //"0:노출중단/1:노출"
$filter[$column]['options'] = make_options_array($options_request_action,"");  //옵션명과 노출명이 다를때는 '/'로분리 표기, ['refund/결제취소환불','emoney/기프티머니로환불','resend/재발송'];


$column = "report_part";
$filter[$column]['column'] = $column;
$options_report_part = ":--신고사유--/이미사용된상품/취소된상품/특정매장사용불가/구매정보상이/바코드인식오류/사용불가상품/기타";  //"0:노출중단/1:노출"
$filter[$column]['options'] = make_options_array($options_report_part,"");  //옵션명과 노출명이 다를때는 '/'로분리 표기, ['refund/결제취소환불','emoney/기프티머니로환불','resend/재발송'];



$column = "complain_status";
$filter[$column]['column'] = $column;
$options_complain_status = ":--처리상황--/신고접수/처리보류/환불예정/환불완료/재발행/대체상품발송/신고접수반려/신고취소/기타";  //"0:노출중단/1:노출"
$filter[$column]['options'] = make_options_array($options_complain_status,"");  //옵션명과 노출명이 다를때는 '/'로분리 표기, ['refund/결제취소환불','emoney/기프티머니로환불','resend/재발송'];


$column = "contact_seller";
$filter[$column]['column'] = $column;
$options_contact_seller = ":--판매자연락--/카톡/문자/전화/기타";  //"0:노출중단/1:노출"
$filter[$column]['options'] = make_options_array($options_contact_seller,"");  //옵션명과 노출명이 다를때는 '/'로분리 표기, ['refund/결제취소환불','emoney/기프티머니로환불','resend/재발송'];


/*****************css 설정************************************************************************************************************** */
$css_array = [];
array_push($css_array,["coupon_complain_idx",["font-size","11px"]]);
array_push($css_array,["menu_name",["font-size","11px"]]);
array_push($css_array,["admin_memo",["font-size","11px"]]);
array_push($css_array,["menu_name",["min-width","65px"],["max-width","150px"]]);
array_push($css_array,["report_memo",["min-width","65px"]]);
array_push($css_array,["reward_point_amount",["min-width","50px"]]);
array_push($css_array,["contact_seller",["min-width","40px"]]);
array_push($css_array,["buy_in_price",["min-width","85px"]]);
array_push($css_array,["exec",["min-width","50px"]]);


/******************수정모드 설정************************************************************************************************************* */
$edit_array = [];
array_push($edit_array,["request_action","select",["options",$options_request_action],["direct_save","1"]]);
array_push($edit_array,["report_part","select",["options",$options_report_part],["direct_save","1"]]);
array_push($edit_array,["complain_status","select",["options",$options_complain_status],["direct_save","1"]]);
array_push($edit_array,["contact_seller","select",["options",$options_contact_seller],["direct_save","1"]]);
array_push($edit_array,["admin_memo","textarea",["direct_save","1"]]);
array_push($edit_array,["buy_in_price","editable_custom",["direct_save","1"]]);
//array_push($edit_array,["display","select",["options",[["on","노출"],["off","숨김"]]],["direct_save","1"],["same","N"]]);



/************* 보기 그룹이 선언된 경우 보기그룹별 배열 정리 시작 *****************************/
require('./contents/common/column_array.php');

require('./contents/common/datatable_html.php');


?>


  <div id="input_sample" class="hide">


    <div class="buy_in_price edit">
       매입:<input type=text class="form-control" name="buy_in_price">
       <br>환불:<input type=text class="form-control"  name="refund_money_to_buyer" >
       <br>회수:<input type=text  class="form-control" name="refund_money_from_seller" >
       <br>매입:<input type=text class="form-control"  name="loss_money">
    </div>

    <div class="buy_in_price view">
       매입:<span name="buy_in_price" class="input" format="number"></span>
       <br>환불:<span name="refund_money_to_buyer" class="input"  format="number"></span>
       <br>회수:<span name="refund_money_from_seller" class="input"  format="number"></span>
       <br>매입:<span name="loss_money" class="input"  format="number"></span>
       
    </div>


    



  </div>












  <?
require('./contents/common/datatable.js.php');
//require('./contents/'.$folder_name.'/columnDefs.js.php');
?>





<script>



//td 에 data 값 포멧 변경해서 보여줘야 하는 경우.
function datatableRender(column,data,full){

  var rValue = "";
  switch (column) {

    // case "brand_idx":
    //       rValue = "<span class='edit_area brand_idx'>"+full.brand_name+"</span><br><span class='etc brand_name'>("+data+")</span>";
    //       break;

    

    // case "buy_user_name":
    //       if (data == null)
    //           data = "";
    //       if (full.buy_user_phone == null){
    //           full.buy_user_phone = "";
    //           rValue =  data;
    //       }else{
    //           rValue =  data+"<br>("+full.buy_user_phone+")";
    //       }
    //       break;



    default:
          rValue = data;
          break;
  }

  return rValue;
}







//td 의 attr 적용해야 할때
function datatableCreatedCell(column,$td,rowData){
  switch (column) {
    // case "brand_idx":
    //   $td.attr('brand_idx_text', rowData.brand_name); 
    //   break;


    default:
      break;
  }
}



</script>


