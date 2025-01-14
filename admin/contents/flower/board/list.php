<?


$folder_name = "flower/board";  //폴더명
$title ="배송상황판";

$table_name ="flower.out_order";

$key_column_name ="out_order_idx";

$function_date_search  = "on";   // 날짜검색기능 on ,off
$function_process_search = "off";  // 중앙 버튼식 선택메뉴 on ,off
$function_multi_search = "on";  // 체크박스 선택된 칼럼 검색
$function_keyword_search = "on";    // 키워드검색기능 on ,off
$function_add = "off";    // 추가하기 on ,off



$main_filter_query = "out_order_status != '주문취소'";

if($_REQUEST['st'] == "1"){ //당일배송건
  $main_filter_query .= " and r_date='".$todate."' ";
}else if($_REQUEST['st'] == "2"){ //예약건
  $main_filter_query .= " and r_date > '".$todate."' ";
}else if($_REQUEST['st'] == "3"){ //미배송건
  $main_filter_query = " r_date >= '".$todate."' and  (out_order_status!='배송완료' and out_order_status!='주문취소' ) ";
}else if($_REQUEST['st'] == "6"){ //금일수주
  $main_filter_query .= " and date(regist_datetime) = '".$todate."' ";
}else if($_REQUEST['st'] == "7"){ //금월수주
  $this_month = date("Y-m",time());
  $main_filter_query .= " and date_format(regist_datetime,'%Y-%m') = '".$this_month."' ";
}

$order_by_column = "regist_datetime";
$order_by_sort = "desc";




$date_search_display = "hide";//수정없음
if($function_date_search == "on"){
  //날짜검색 옵션 넣을 경우
  $function_date_column['regist_datetime'] = "등록일";      //날짜 검색 옵션칼럼 + 보여지는문구
  $function_date_column['r_date'] = "배달요청일";  //날짜 검색 옵션칼럼 + 보여지는문구
  //-------------------------------------------------------------------------
  $function_date_column_selected = "r_date";      //기본 선택옵션 value
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
  //array_push($keyword_column_array,["출고지","t_storage_name","like"]);  
  array_push($keyword_column_array,["발주사","t_company_name","like"]);   //로딩시 첫 옵션 기본선택됨. dataTables.ajax.button.js 에서 keyword_query = setKeywordQuery(0); 에 의해 첫번째 옵션이 기본옵션으로 선택됨.
  array_push($keyword_column_array,["지점명(협력사)","t_storage_name","like"]);
  array_push($keyword_column_array,["주문고객명","order_name","like"]);   
  array_push($keyword_column_array,["받는고객명","r_name","like"]);   

  array_push($keyword_column_array,["인수자","receiver_name","like"]);   
  array_push($keyword_column_array,["주소","address1","like"]);   
  //array_push($keyword_column_array,["수령인","receiver_name","like"]);   
  //array_push($keyword_column_array,["휴대폰","to_hp","like"]);  
  //array_push($keyword_column_array,["보내는분","sender_name","like"]);   
  //array_push($keyword_column_array,["주소","to_address","like"]);  

  // array_push($keyword_column_array,["브랜드IDX","brand_idx","="]);
  // array_push($keyword_column_array,["카테고리","t_category_name","like"]);
  // array_push($keyword_column_array,["키워드","brand_keyword","like"]);
  // array_push($keyword_column_array,["등록관리자명","cf_admin_name","like"]);
  // array_push($keyword_column_array,["메뉴종류>=N","t_menu_count",">="]);

  // array_push($keyword_column_array,["쿠폰보유량>=N","t_brand_coupon_count",">="]);
  // array_push($keyword_column_array,["최대할인율>=N","t_max_discount_percent",">="]);

  //-------------------------------------------------------------------------


  $keyword_column_array_selected = "t_company_name";      //기본 선택옵션 value

  $process_keyword_display = 1; //수정없음
}else{
  $process_keyword_display = 0; //수정없음
}









/*th 헤더 정보 입력 */
$th_info = [];

array_push($th_info,["out_order_idx","No"]);
//array_push($th_info,["reg_datetime","접수일"]);
array_push($th_info,["r_date","배달요청일"]);

array_push($th_info,["company_name","발주사"]);

array_push($th_info,["order_name","주문자<br>받는분"]);

array_push($th_info,["order_product_title","상품명"]);
array_push($th_info,["address1","배송지/리본/보내는분"]);


array_push($th_info,["total_client_price_sum","수주금액<br>발주금액"]);

array_push($th_info,["t_storage_name","출고지점(협력사)"]);
array_push($th_info,["paymentType", "결제정보","convert",[    ["paymentBill","월말결제(계산서)"],["paymentCard","카드결제"]    ] ]);

array_push($th_info,["out_order_status","배송상황"]);
array_push($th_info,["photos","사진"]);

array_push($th_info,["exec_sms","문자발송"]);





// array_push($th_info,["sender_name","보내는분"]);
// array_push($th_info,["to_address","주소"]);
// array_push($th_info,["to_name","받는분"]);
// array_push($th_info,["to_hp","휴대폰"]);

// array_push($th_info,["memo","메모"]);

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
//array_push($th_info,["exec_io_status","출고상태","exec_io_status"]);

//array_push($th_info,["io_status","출고상태","selectDropDown",[  ["미출고","미출고","btn-primary"],["출고완료","출고완료","btn-dark","divider"]  ] ]);

//array_push($th_info,["exec","실행","exec"]);







//$other_table_column = "a:consulting_idx:".$db_consulting.".consulting:company_name";
//$other_table_column = "a:date_format(regist_datetime,'%Y-%m-%d %H;%i') as reg_datetime/a:out_order_idx:out_order:t_company_name as company_name+r_date+date_format(r_date,'%m-%d') as r_md+r_hour+address1+address2+msgTitle+msgTitle2+msgTitle3+sender_name";
$other_table_column = "a:date_format(regist_datetime,'%Y-%m-%d %H;%i') as reg_datetime+date_format(r_date,'%m-%d') as r_md+t_company_name as company_name+(select group_concat(filename) from attachment where out_order_idx=out_order.out_order_idx and attachType='photo') as photos";
//$other_table_column .= "";




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
array_push($css_array,["out_order_idx",["min-width","20px"]]);

array_push($css_array,["r_date",["min-width","120px"]]);
array_push($css_array,["address1",["min-width","160px"]]);
array_push($css_array,["company_name",["max-width","100px"],["min-width","80px"]]);
array_push($css_array,["order_product_title",["min-width","80px"]]);
array_push($css_array,["photos",["width","90px"]]);

// array_push($css_array,["update_datetime",["min-width","130px"]]);
// array_push($css_array,["exec",["min-width","50px"]]);




/******************수정모드 설정************************************************************************************************************* */




//수정모드 설정
$edit_array = [];

// array_push($edit_array,["sender_name","보내는분","","text"]);
// array_push($edit_array,["to_address","주소","","text"]);
// array_push($edit_array,["to_name","받는분","","text"]);
// array_push($edit_array,["to_hp","휴대폰","","text"]);
// array_push($edit_array,["receiver_name","실수령인","","text"]);
// array_push($edit_array,["memo","textarea"]);
// array_push($edit_array,["out_date","date",""]);

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

require('./contents/flower/board/html/datatable_html.php');







?>




  <!----  페이지별 html 필요 코드 --> 
  
  <div class="modal fade modal_sms" tabindex="-1" role="dialog" aria-hidden="true"  aria-labelledby="mySmallModalLabel" id="modal-sms">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">문자발송</h4>
            </div>
            <div class="modal-body">
            <table class="table table-bordered" id="table_sms">
                            <thead>
                              <tr>
                                <th>구분</th>
                                <th>입력값</th>
                                <th>템플릿</th>

                              </tr>
                            </thead>
                            <tbody>



                              <tr>
                                <th scope="row">문자수신번호</th>
                                <td><input type="text" id="sms_receiver_tel" class="form-control" value=""></td>
                                <td class="order_company_tel">부서번호:<span></span></td>
                              </tr>
                              <tr>
                                <th scope="row">템플릿항목</th>
                                <td colspan="2">{{주문자}}  {{주문자번호}}  {{받는분}}  {{받는분번호}}  {{상품명}}</td>
                              </tr>
                              <tr>
                                <th scope="row">템플릿명</th>
                                <td>
                                  <select id="sms_template" class="form-control group1">
                                    <option value="1">배송완료-상품명포함</option>
                                    <option value="2">배송완료 심플</option>
                                  </select>
                                  <input type="text" id="template_title" class="form-control group2 group3 hide" placeholder="예)배송완료 알림" value="">

                              </td>
                              <td class="">
                                  <button type="button" class="btn group1 btn-success btn_templete_add btn-xs">추가</button>
                                  <button type="button" class="btn group1 btn-primary btn_templete_edit btn-xs">수정저장</button>
                                  <button type="button" class="btn group1 btn-dark btn_templete_del btn-xs">삭제</button>
                                  <button type="button" class="btn group1 btn-warning btn_templete_default btn-xs">기본선택</button>
                                  <button type="button" class="btn group2 hide btn-success btn_templete_save btn-xs">템플릿저장</button>
                                  <button type="button" class="btn group2 hide btn-danger btn_templete_cancel btn-xs">취소</button>

                                </td>
                              </tr>

                              <tr>
                                <th scope="row">타이틀</th>
                                <td><input type="text" id="sms_subject" class="form-control group1" value=""></td>
                                <td><input type="text" id="template_subject" class="form-control group3" placeholder="예)[(주)늘]배송완료알림" value=""></td>
                              </tr>

                              <tr class="tr_message">
                                <th scope="row">메세지</th>
                                <td><textarea type="text" id="sms_content"  class="form-control group1">[(주)늘]주문하신 축하화환3단을 김철수님께 배송완료하였습니다.</textarea></td>
                                <td><textarea type="text" id="template_content" class="form-control group3">[(주)늘]주문하신 {{상품명}}을 {{받는분}}님께 배송완료하였습니다.</textarea></td>
                              </tr>

                            </tbody>
                          </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn_modal_dismiss" data-dismiss="modal">닫기</button>
              <!-- <button type="button" class="btn btn-info btn_modal_preview">미리보기</button> -->
              <button type="button" class="btn btn-success btn_modal_save">보내기</button>

            </div>

          </div>
        </div>
      </div>
  
  <!----  페이지별 html 필요 코드 끝 --> 



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




<script type="text/javascript" src="js/moment/moment.min.js?ver=<?=time()?>"></script>

<script>

var optionSet1 = {
      /*startDate: moment().subtract(29, 'days'),*/
      startDate: moment(),
      endDate: moment(),
      minDate: '2021/01/01',
      maxDate: '2030/12/31',
      dateLimit: {
        days: 1000
      },
      showDropdowns: true,
      showWeekNumbers: true,
      timePicker: false,
      timePickerIncrement: 1,
      timePicker12Hour: true,
      ranges: {
        '전전일': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '어제': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '오늘': [moment(), moment()],
        '내일': [moment().add(1, 'days'), moment().add(1, 'days')],

        '지난7일': [moment().subtract(6, 'days'), moment()],
        '지난1개월': [moment().subtract(29, 'days'), moment()],
        '이번달': [moment().startOf('month'), moment().endOf('month')],
        '지난달': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      opens: 'left',
      buttonClasses: ['btn btn-default'],
      applyClass: 'btn-small btn-primary',
      cancelClass: 'btn-small',
      format: 'YYYY/MM/DD',
      separator: ' to ',
      locale: {
        applyLabel: 'Submit',
        cancelLabel: 'Clear',
        fromLabel: 'From',
        toLabel: 'To',
        customRangeLabel: '직접설정',
        daysOfWeek: ['일', '월', '화', '수', '목', '금', '토'],
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        firstDay: 1
      }
    };
</script>


<?
require('./contents/common/datatable.js.php');
//require('./contents/'.$folder_name.'/columnDefs.js.php');
?>




<script>



// array_push($th_info,["oocp_idx","주문번호"]);
// array_push($th_info,["regist_datetime","주문접수일"]);

// array_push($th_info,["company_name","발주사"]);

// array_push($th_info,["product_name","상품명"]);
// array_push($th_info,["address1","배송지<br>리본<br>보내는분"]);
// array_push($th_info,["price_calcu","수주금액"]);

function datatableRender(column,data,full){

  var today = new Date();
  var year = today.getFullYear();
  var month = ('0' + (today.getMonth() + 1)).slice(-2);
  var day = ('0' + today.getDate()).slice(-2);

  var dateString = year + '-' + month  + '-' + day;


  var rValue;
  switch (column) {


      
    // case "reg_datetime":
    //     rValue = "<span class='reg_date'>"+data+"</span>";
    //     break;
      
    case "r_date":

        rValue = "<span class='reg_date'>"+full.reg_datetime+"</span><br>";

        if(dateString == full.r_date){
          rValue += "<span class='green today'>"+full.r_md+"</span>";

        }else{
          rValue += "<span class='blue'>"+full.r_md+"</span>";

        }
        rValue += "<span class='r_hour red'>"+full.r_hour+"</span>";

        break;

    case "company_name":
        rValue = "<span>"+data.substr(0,8)+"</span>";
        break;

    case "order_name":
        rValue = "<span>주문:"+data.substr(0,8)+"</span>";
        rValue += "<br><span>받는:"+full.r_name.substr(0,8)+"</span>";

        break;


    case "order_product_title":

        // if(full.out_order_part == "상조"){
        //   rValue = "<span class='red'>(상조)</span>"+data;
        //   rValue = "<span class='red'>(상조)</span>"+data;

        // }else{
        //   rValue = data;
        // }

        rValue = "<span>"+data.substr(0,10);

        if(full.total_order_count > 1){
          //var toc = full.total_order_count - 1;
          rValue += " 등"+full.total_order_count;
        }

        rValue += "</span>";

        break;

    case "total_client_price_sum":

        if(data != ""){
            rValue = "수주:"+data.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        }
        if(full.agency_order_price != "0"){
            rValue += "<br>발주:"+full.agency_order_price.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        }
        break;

    case "address1":

        var addr = data+" "+full.address2;
        addr = addr.substr(0,16);
        rValue = "<span class='address'>"+addr+"</span><br>";
        


        if(full.msgTitle != ""){
          rValue += "<span class='msgTitle'>"+full.msgTitle.substr(0,8)+"</span>";

        }


        if(full.msgTitle != "" && full.sender_name != ""){
          rValue += " | ";

        }

        if(full.sender_name != undefined){
          rValue += "<span class='sender_name'>"+full.sender_name.substr(0,8)+"</span>"; 

        }


        break;
      
    case "out_order_status":
        if(data == "접수대기"){
          rValue = '<button type="button" class="btn btn-default">접수대기</button>';
        }else if(data == "배송요청"){
          rValue = '<button type="button" class="btn btn-info">배송요청</button>';
        }else if(data == "본부접수" || data == "주문접수" || data == "배송중"){
          rValue = '<button type="button" class="btn btn-warning">'+data+'</button>';

        }else if(data == "배송완료"){
          rValue = '<button type="button" class="btn btn-success">배송완료</button>';
        }else if(data == "주문취소"){
          rValue = '<button type="button" class="btn btn-dark">주문취소</button>';

        }else{
          rValue = data;
        }

      break;

    case "photos":
      rValue = "";
      if(data != ""){
        ex = data.split(",");
        for(var i=0;i<ex.length;i++){
          //console.log(ex[i]);
          rValue += '<a class="group'+full.out_order_idx+'"  href="'+ex[i]+'" data-lightbox="photo'+full.out_order_idx+'" ><img src="'+ex[i]+'" class="photo_list"></a>';
          if(i==7){
            break;
          }
        }

      }


      break;  
    case "exec_sms":

      if(full.sms_sent == 1){ //발송완료
        rValue = '<button type="button" class="btn btn-dark btn_send_sms" data-toggle="modal" data-target=".modal_sms" title="발송완료">문자발송</button>';

      }else{ //미발송
        rValue = '<button type="button" class="btn btn-primary btn_send_sms" data-toggle="modal" data-target=".modal_sms" title="미발송">문자발송</button>';

      }


      break;
    default:
          rValue = data;
          break;

  }




  return rValue;


}

function datatableCreatedCell(column,$td,rowData){
  switch (column) {
    case "out_order_idx":
      if(rowData.out_order_part == "상조"){
          $td.closest("tr").attr("out_order_part","상조").addClass("tr_sangjo");
      }else if(rowData.out_order_part == "화훼"){
        $td.closest("tr").attr("out_order_part","화훼").addClass("tr_flower");
      }

      break;


    default:
      break;
  }
}



</script>


<!-- 라이트 박스 -->
<link rel="stylesheet" href="./contents/flower/board/css/colorbox.css" />
<script src="./contents/flower/board/js/jquery.colorbox.js"></script>



<script>

$(document).ready(function(){

//$(".group"+rowData.out_order_idx).colorbox({rel:'group'+rowData.out_order_idx, slideshow:true});

//$(".group22").colorbox({rel:'group22', slideshow:true});


});
</script>



<!-- //https://lokeshdhakar.com/projects/lightbox2/ -->
<!-- 라이트박스 -->
<script src="./contents/flower/board/js/lightbox.js"></script>
<script>
    lightbox.option({
      'resizeDuration': 10,
      'wrapAround': true,
      'maxWidth':'600',
      'albumLabel':"%1 of %2"

    })
</script>