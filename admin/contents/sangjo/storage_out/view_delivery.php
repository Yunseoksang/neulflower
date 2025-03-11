<?


$folder_name = "sangjo/storage_out";  //폴더명
$title ="출고지시서 목록";

$table_name ="sangjo_new.in_out";
$key_column_name ="io_idx";

$function_date_search  = "on";   // 날짜검색기능 on ,off
$function_process_search = "off";  // 중앙 버튼식 선택메뉴 on ,off
$function_multi_search = "on";  // 체크박스 선택된 칼럼 검색
$function_keyword_search = "on";    // 키워드검색기능 on ,off
$function_add = "off";    // 추가하기 on ,off
//$main_filter_query = "part='출고' and io_status != '주문취소'  and io_status != '출고취소' and (io_status != '출고완료' or (receive_date is not null and DATEDIFF(now(),receive_date) < 1))";
$main_filter_query = " part='출고' and io_status='미출고' ";

$function_add_custom = ["on","btn_download_xls_view_delivery","엑셀다운로드"];    // 커스텀 추가하기 버튼 on ,off
$function_add_html = "<input type='text' name='barcode_input_all' class='form-control' id='barcode_input_all' placeholder='QR코드입력' style='width:150px; float:right;'>";




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
    array_push($keyword_column_array,["출고지","t_storage_name","like"]);  
    array_push($keyword_column_array,["고객사","t_company_name","like","join",["out_order"],["out_order_idx","out_order_idx"]]);

    array_push($keyword_column_array,["받는분이름","to_name","like","join",["out_order"],["out_order_idx","out_order_idx"]]);

    //array_push($keyword_column_array,["수령인","receiver_name","like"]);   

    array_push($keyword_column_array,["휴대폰","to_hp","like"]);  
    array_push($keyword_column_array,["배송지명","to_place_name","like"]);   
    array_push($keyword_column_array,["상품명","t_product_name","like"]);   
    array_push($keyword_column_array,["상품명(일치)","t_product_name","="]);   

    //array_push($keyword_column_array,["주소","to_address","like"]);  
  
    // array_push($keyword_column_array,["브랜드IDX","brand_idx","="]);
    // array_push($keyword_column_array,["카테고리","t_category_name","like"]);
    // array_push($keyword_column_array,["키워드","brand_keyword","like"]);
    // array_push($keyword_column_array,["등록관리자명","cf_admin_name","like"]);
    // array_push($keyword_column_array,["메뉴종류>=N","t_menu_count",">="]);
  
    // array_push($keyword_column_array,["쿠폰보유량>=N","t_brand_coupon_count",">="]);
    // array_push($keyword_column_array,["최대할인율>=N","t_max_discount_percent",">="]);
  
    //-------------------------------------------------------------------------
  
  
    $keyword_column_array_selected = "t_storage_name";      //기본 선택옵션 value
  
    $process_keyword_display = 1; //수정없음
  }else{
    $process_keyword_display = 0; //수정없음
  }
  
  
  
  
  
  
  
  
  
  /*th 헤더 정보 입력 */
  $th_info = [];
  
  array_push($th_info,["io_idx","고유번호"]);
  array_push($th_info,["regist_datetime","주문일"]);
  array_push($th_info,["t_storage_name","출고지"]);
  array_push($th_info,["t_product_name","품목"]);
  array_push($th_info,["out_count","수량"]);

  array_push($th_info,["company_name","고객사"]);
  array_push($th_info,["to_place_name","배송지"]);
  array_push($th_info,["to_name","받는분"]);
  array_push($th_info,["to_hp","휴대폰"]);
  array_push($th_info,["to_address","주소"]);

  
  // array_push($th_info,["receiver_name","인수자"]);
  
  // array_push($th_info,["out_date","출고일"]);
  array_push($th_info,["delivery_memo","주문자메모"]);
  array_push($th_info,["admin_memo","관리자메모"]);

  //array_push($th_info,["io_status","출고상태"]);

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









//array_push($th_info,["regist_datetime","등록일"]);
//array_push($th_info,["io_status","출고상태","selectDropDown",[  ["미출고","미출고","btn-primary"],["출고완료","출고완료","btn-dark","divider"]  ] ]);
array_push($th_info,["io_status","출고상태"]);
array_push($th_info,["exec_td","실행",]);







//$other_table_column = "a:cache_url(cache_img_url) as cache_img_url"; 
//$other_table_column = "a:cache_url(brand_img_url) as cache_img_url/a:category_idx:category:category_name/a:brand_idx:brand_explain:brand_ex"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.




$other_table_column = "a:out_order_idx:sangjo_new.out_order:consulting_idx+t_company_name as company_name+out_order_idx+to_place_name+to_address+to_name+to_hp+to_phone+receiver_name+delivery_memo"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.
$other_table_column .= "/a:out_order_idx:sangjo_new.attachment:filename";
//$other_table_column = "a:io_idx:in_out:t_product_name+out_count+io_status+t_write_admin_name"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.
//$other_table_column = "a:user_idx:user:user_name+user_phone/a:user_idx:order_list:menu_name/order_list:order_list_idx:order_coupon:order_price"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.






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

//$unsortable_columns = ["cache_img_url","exec"];  //soring 기능 불가칼럼 지정.
//$padding_unset_columns = ["brand_idx","exec"];  //타이틀에 우측 padding 없앰
//$edit_hide_columns = ["detail","regist_datetime"]; //수정버튼 눌렀을때 잠시 안보이게 하는 칼럼



/*****************css 설정************************************************************************************************************** */

//css 설정
$css_array = [];

//array_push($css_array,["brand_name",["max-width","200px"]]);
//array_push($css_array,["",["min-width","70px"]]);

// array_push($css_array,["regist_datetime",["min-width","130px"]]);
// array_push($css_array,["update_datetime",["min-width","130px"]]);
// array_push($css_array,["exec",["min-width","50px"]]);

array_push($css_array,["io_status",["width","110px"]]);

array_push($css_array,["t_storage_name",["min-width","50px"]]);




/******************수정모드 설정************************************************************************************************************* */





//수정모드 설정
$edit_array = [];

array_push($edit_array,["to_place_name","배송지","","text"]);
array_push($edit_array,["to_address","textarea"]);
array_push($edit_array,["to_name","받는분","","text"]);
array_push($edit_array,["to_hp","휴대폰","","text"]);
//array_push($edit_array,["receiver_name","실수령인","","text"]);
array_push($edit_array,["memo","textarea"]);
// array_push($edit_array,["out_date","date"]);

//$io_status_array = make_options_array("미출고:미출고:SELECTED/출고완료:출고완료");
//array_push($edit_array,["io_status","select",["options",$io_status_array],["direct_save","1"],["same","Y"]]);



// $admin_status_array = make_options_array("승인:승인/승인대기:승인대기/승인거절:승인거절/탈퇴:탈퇴");


// //근무지는 DB에서 가져오기
// $sel0 = mysqli_query($dbcon, "select * from working_group where display=1 order by working_group_name  ") or die(mysqli_error($dbcon));
// $sel_num0 = mysqli_num_rows($sel0);

// $working_group_idx_array = [];
// if ($sel_num0 > 0) {
//   while($data0 = mysqli_fetch_assoc($sel0)) {
//       $this_arr = array();
//       array_push($this_arr,$data0['working_group_idx']);
//       array_push($this_arr,$data0['working_group_name']);
      
//       if($data0['selected'] == 1){array_push($this_arr,"selected");}

//       array_push($working_group_idx_array,$this_arr);      
//   }   
// }


// array_push($edit_array,["working_group_idx","select",["options",$working_group_idx_array],["direct_save","1"],["same","N"]]);
// array_push($edit_array,["admin_status","select",["options",$admin_status_array],["direct_save","1"],["same","Y"]]);


// array_push($edit_array,["cache_img_url","editable_img",["origin_column","brand_img_url"]]); //[2] 항목은 칼럼 원래 이름이 따로 있을 경우 지정해줌. 파일 업로드 박스 생성될때 origin_column 참조하여 생성됨.

//array_push($edit_array,["category_idx","select2",["same","N"]]);
// array_push($edit_array,["naver_category_name","text",["direct_save","1"]]);

// array_push($edit_array,["brand_name","text",["direct_save","1"]]);

// array_push($edit_array,["brand_display_order","text",["direct_save","1"]]);
// array_push($edit_array,["brand_keyword","textarea"]);
// array_push($edit_array,["brand_ex","textarea"]);


/*************************************************새로 추가하기********************************************/
$modal_array = [];


$add_array = [];
array_push($add_array,["modal_info",["new","새로 추가하기"]]); //0: 모달창네임, 1: 모달창타이틀, 2: submit_url 3:적용할 table, 4: 적용할 row 고유키명
// array_push($add_array,["admin_level","권한","","select",$admin_level_array]); //DB column 기준이라서 brand_name 이 아니고,brand_idx로,
// //array_push($add_array,["working_group_idx","소속","","select",[["1","본사","SELECTED"],["2","서산공장"],["4","개발팀"]]]); //DB column 기준이라서 brand_name 이 아니고,brand_idx로,
// array_push($add_array,["working_group_idx","소속","","select",$working_group_idx_array]); //DB column 기준이라서 brand_name 이 아니고,brand_idx로,

// array_push($add_array,["admin_name","이름","필수","text"]);
// array_push($add_array,["admin_hp","휴대폰","필수","text"]);
// array_push($add_array,["admin_email","이메일","","text"]);

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


require('./contents/common/addon_html_qr_colorbox.php');





?>


  <!-----  샘플 영역 시작---->
  <div id="input_sample" class="hide">

    <?
      foreach($filter as $key => $value)
      {
        print_filter($key);
      }

    ?>



    <!--
    <select name="request_action" class="request_action form-control direct_save">
        <option val="요청사항" role="option">요청사항</option>
        <option val="결제취소환불">결제취소환불</option>
        <option val="재발송">재발송</option>
    </select>
    -->



  </div>



  <!-----  샘플 영역 끝---->




<?
require('./contents/common/datatable.js.php');
//require('./contents/'.$folder_name.'/columnDefs.js.php');
?>

<!-- 테스트 모달 -->
<div class="modal fade" id="testModal" tabindex="-1" role="dialog" aria-labelledby="testModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="testModalLabel">출고 상세정보</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="testModalBody">
                <!-- 데이터가 여기에 동적으로 삽입됩니다 -->
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" onclick="printModalContent()"><i class="fa fa-print"></i> 인쇄하기</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<style>
/* 인쇄 스타일 */
@media print {
  body * {
    visibility: hidden;
  }
  #testModalBody, #testModalBody * {
    visibility: visible;
  }
  #testModalBody {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
  .modal-footer, .modal-header, .close {
    display: none;
  }
}

/* 삭제 버튼 숨기기 */
.dataTables_wrapper .btn_delete {
    display: none !important;
}
</style>

<script>
// 데이터 저장용 객체 (공통 JS에서 사용)
var deliveryData = {};

// 사용자 정의 타이틀 맵 (그룹화 순서 지정)
var customTitleMap = {
    // 그룹 1: 고유번호/주문일/출고지
    'io_idx': '고유번호',
    'regist_datetime': '주문일',
    't_storage_name': '출고지',
    
    // 그룹 2: 품목/수량/주문자메모
    't_product_name': '품목',
    'out_count': '수량',
    'delivery_memo': '주문자메모',
    
    // 그룹 3: 고객사/받는분/휴대폰/주소
    'company_name': '고객사',
    'to_place_name': '배송지',
    'to_name': '받는분',
    'to_hp': '휴대폰',
    'to_address': '주소',
    
    // 별도 섹션: 관리자메모
    'admin_memo': '관리자메모'
};

// 페이지 로드 완료 후 실행
$(document).ready(function() {
    console.log('view_delivery.php 페이지 로드 완료');
    console.log('사용자 정의 타이틀 맵:', customTitleMap);
});

// 테스트 모달 표시 함수
function testModal() {
    try {
        // 테스트 데이터 준비
        var testData = {
            '고유번호': '123456',
            '주문일': '2023-05-15 14:30:00',
            '출고지': '서울 창고',
            '품목': '테스트 상품',
            '수량': '10',
            '고객사': '테스트 고객사',
            '배송지': '테스트 배송지',
            '받는분': '홍길동',
            '휴대폰': '010-1234-5678',
            '주소': '서울시 강남구 테스트로 123',
            '주문자메모': '테스트 주문자 메모',
            '관리자메모': '테스트 관리자 메모'
        };
        
        // 모달 내용 생성 - 표 형식으로
        var contentHtml = '<div class="container-fluid p-0">';
        contentHtml += '<div class="row">';
        contentHtml += '<div class="col-12">';
        contentHtml += '<table class="table table-bordered table-sm">';
        contentHtml += '<tbody>';
        
        // 데이터 항목 순서대로 표시
        for (var key in testData) {
            if (testData.hasOwnProperty(key)) {
                contentHtml += '<tr>';
                contentHtml += '<th width="25%" class="bg-light">' + key + '</th>';
                contentHtml += '<td width="75%">' + (testData[key] || '') + '</td>';
                contentHtml += '</tr>';
            }
        }
        
        contentHtml += '</tbody>';
        contentHtml += '</table>';
        contentHtml += '</div>'; // col-12
        contentHtml += '</div>'; // row
        contentHtml += '</div>'; // container-fluid
        
        // 모달 내용 삽입
        $('#testModalBody').html(contentHtml);
        
        // 모달 표시
        $('#testModal').modal('show');
    } catch (err) {
        alert('오류 발생: ' + err.message);
    }
}

// 모달 내용 인쇄 함수
function printModalContent() {
    window.print();
}

function datatableRender(column,data,full){

  var rValue;
  
  // 데이터 저장 (io_idx를 키로 사용)
  if (full && full.io_idx) {
    deliveryData[full.io_idx] = full;
  }
  
  switch (column) {

    case "to_hp":

      rValue = data;
      if(full.to_phone != undefined && full.to_phone != "" && full.to_phone != null){
         rValue += rValue + "<br>" + full.to_phone;
      }
      break;
    case "t_product_name":
        rValue = "<a href='/admin/contents/barcode/barcode_print.php?part=sffm&product_idx="+full.product_idx+"' target='_blank'>"+data+"</a>";
        break;

      
    case "io_status":
        rValue = '<button type="button" class="btn btn-danger btn_io_status"  next_io_status="출고완료">출고하기</button>';
        rValue += '<br><button type="button" class="btn btn-info" onclick="showDetail(\''+full.io_idx+'\')"><i class="fa fa-search"></i> 상세보기</button>';
        // rValue += '<br><button type="button" class="btn btn-info btn_qr_out "  next_io_status="출고완료">QR출고하기</button>';

      break;
    case "filename":

      if(data != undefined && data != "" ){
        var fname = data.split("/").pop();
        var ext = fname.split(".").pop();
        rValue = '<a href="'+data+'" target="_blank" title="'+fname+'" class="attach_alink">'+getAttachIcon(fname)+'</a>';

      }else{
        rValue = "";
      }


      break;
    case "exec_td":
        rValue = '<button type="button" class="btn btn-dark btn_modify"  >수정</button>';
        rValue += '<br><button type="button" class="btn btn-primary btn_io_status_cancel"  next_io_status="출고취소">주문취소</button>';

      break;
  
    default:
          rValue = data;
          break;

  }




  return rValue;


}

function datatableCreatedCell(column,$td,rowData){
  switch (column) {
    case "io_status":
      $td.attr('io_status', rowData.io_status); 
      break;

    case "t_product_name":
      $td.attr('product_idx', rowData.product_idx); 
      break;


    default:
      break;
  }
}

// 상세 정보 표시 함수
function showDetail(io_idx) {
    try {
        // 데이터 찾기
        var rowData = deliveryData[io_idx];
        
        if (!rowData) {
            alert('데이터를 찾을 수 없습니다.');
            return;
        }
        
        // 출력할 데이터 준비 - 요청한 그룹화 순서대로
        var printData = {
            // 그룹 1: 고유번호/주문일/출고지
            '고유번호': rowData.io_idx,
            '주문일': rowData.regist_datetime,
            '출고지': rowData.t_storage_name,
            
            // 그룹 2: 품목/수량/주문자메모
            '품목': rowData.t_product_name,
            '수량': rowData.out_count,
            '주문자메모': rowData.delivery_memo || '',
            
            // 그룹 3: 고객사/받는분/휴대폰/주소
            '고객사': rowData.company_name,
            '배송지': rowData.to_place_name || '',
            '받는분': rowData.to_name,
            '휴대폰': rowData.to_hp,
            '주소': rowData.to_address,
            
            // 별도 섹션: 관리자메모
            '관리자메모': rowData.admin_memo || ''
        };
        
        // 모달 내용 생성 - 표 형식으로
        var contentHtml = '<div class="container-fluid p-0">';
        contentHtml += '<div class="row">';
        contentHtml += '<div class="col-12">';
        contentHtml += '<table class="table table-bordered table-sm">';
        contentHtml += '<tbody>';
        
        // 데이터 항목 순서대로 표시
        for (var key in printData) {
            if (printData.hasOwnProperty(key)) {
                contentHtml += '<tr>';
                contentHtml += '<th width="25%" class="bg-light">' + key + '</th>';
                contentHtml += '<td width="75%">' + (printData[key] || '') + '</td>';
                contentHtml += '</tr>';
            }
        }
        
        contentHtml += '</tbody>';
        contentHtml += '</table>';
        contentHtml += '</div>'; // col-12
        contentHtml += '</div>'; // row
        contentHtml += '</div>'; // container-fluid
        
        // 모달 내용 삽입
        $('#testModalBody').html(contentHtml);
        
        // 모달 표시
        $('#testModal').modal('show');
    } catch (err) {
        console.error('상세 정보 표시 중 오류:', err);
        alert('오류 발생: ' + err.message);
        
        // 오류 발생 시 테스트 모달 표시
        testModal();
    }
}

// 수정 버튼 클릭 시 삭제 버튼 숨기기
$(document).ready(function() {
    $(document).on("click", ".btn_modify", function() {
        // 삭제 버튼 숨기기
        $(this).closest("tr").find(".btn_delete").hide();
    });
});

</script>


