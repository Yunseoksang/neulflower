<?

$category1_select_html = '<select id="category1" class="form-control"><option value="view_all">--카테고리--</option>';

$sel_category = mysqli_query($dbcon, "select * from category1 where category1_name not like '%테스트%' order by category1_name") or die(mysqli_error($dbcon));
$sel_category_num = mysqli_num_rows($sel_category);

if ($sel_category_num > 0) {
 //$data = mysqli_fetch_assoc($sel);
 while($data_category = mysqli_fetch_assoc($sel_category)) {
  $category1_select_html .= '<option value="'.$data_category['category1_idx'].'">'.$data_category['category1_name'].'</option>';
     
 }
}

$category1_select_html .= '</select>';


//=======================================================================================================================
//=======================================================================================================================
//=======================================================================================================================
//=======================================================================================================================
//=======================================================================================================================
//=======================================================================================================================
//=======================================================================================================================


$folder_name = "sangjo/bill";  //폴더명
$title ="거래명세서 발행";

$table_name ="consulting.consulting";

$key_column_name ="consulting_idx";

$function_date_search  = "on";   // 날짜검색기능 on ,off
$function_process_search = "off";  // 중앙 버튼식 선택메뉴 on ,off
$function_multi_search = "on";  // 체크박스 선택된 칼럼 검색
$function_keyword_search = "on";    // 키워드검색기능 on ,off
$function_add = "off";    // 추가하기 on ,off


if($_GET['st'] == "미발행"){$st = "미발행";
  //$main_filter_query = " part='상조물류' ";
  $main_filter_query = "bill_status is null";

}else{
  if($_GET['st'] == "미발송"){$st = "저장/미발송";}
  else if($_GET['st'] == "발송완료"){$st = "발송완료/수락대기";}
  else if($_GET['st'] == "수정요청"){$st = "수정요청";}
  else if($_GET['st'] == "승인완료"){$st = "승인완료";}
  //else if($_GET['st'] == "세금계산서발행완료"){$st = "세금계산서발행완료";}
  else if($_GET['st'] == "폐기"){$st = "폐기";}
  else{
    $st = "empty";
  }

  if($st != "empty"){

    $main_filter_query = "bill_status='".$st."'";

  }


}





// $order_by_column = "sdate";
// $order_by_sort = "asc";




$date_search_display = "hide";//수정없음
if($function_date_search == "on"){
  //날짜검색 옵션 넣을 경우
  $function_date_column['order_date'] = "발주일";      //날짜 검색 옵션칼럼 + 보여지는문구
  //$function_date_column['update_datetime'] = "내용변경일";  //날짜 검색 옵션칼럼 + 보여지는문구
  //-------------------------------------------------------------------------
  $function_date_column_selected = "order_date";      //기본 선택옵션 value
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
  //array_push($keyword_column_array,["출고지","t_company_name","like"]);  
  array_push($keyword_column_array,["거래처","t_company_name","like"]);

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
array_push($th_info,["exec_checkbox","<input type='checkbox' class='flat list_checkbox select-all icheck'>"]);

array_push($th_info,["num","No"]);
//array_push($th_info,["reg_datetime","접수일"]);
array_push($th_info,["t_category1_name","카테고리"]);
array_push($th_info,["sdate"  ,"정산일"]);
array_push($th_info,["bill_month"  ,"정산월"]);
array_push($th_info,["bill_date"  ,"거래명세서 작성일"]);

array_push($th_info,["company_name","거래처명"]);

array_push($th_info,["cnt"  ,"주문건수"]);
array_push($th_info,["sum_total_client_price","공급가액"]);

array_push($th_info,["payment_method","결제방식"]);

if($_GET['st'] != "미발행"){
  array_push($th_info,["exec_view","거래명세서보기"]);
  array_push($th_info,["manager_email","메일"]);
  array_push($th_info,["origin_manager_info","담당자"]);
}


array_push($th_info,["bill_status","처리단계"]);


if($_GET['st'] != "" && $_GET['st'] != "미발행"){
  array_push($th_info,["exec_next","다음단계"]);

}



//array_push($th_info,["price_tax","세액"]);
//array_push($th_info,["price_sum","수주합계"]);

//array_push($th_info,["exec_bill","거래명세서"]);





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
//$other_table_column = "a:consulting_idx:consulting.consulting:consulting_idx:company_name";
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
$unsortable_columns=['exec_checkbox','num'];
$padding_unset_columns = [];
$edit_hide_columns = [];

//$unsortable_columns = ["cache_img_url","exec"];  //soring 기능 불가칼럼 지정.
//$padding_unset_columns = ["brand_idx","exec"];  //타이틀에 우측 padding 없앰
//$edit_hide_columns = ["detail","regist_datetime"]; //수정버튼 눌렀을때 잠시 안보이게 하는 칼럼



/*****************css 설정************************************************************************************************************** */

//css 설정
$css_array = [];

//array_push($css_array,["brand_name",["max-width","200px"]]);
array_push($css_array,["exec_bill",["width","80px"]]);
array_push($css_array,["exec_view",["width","243px"]]);

array_push($css_array,["consulting_idx",["width","230px"]]);
// array_push($css_array,["cnt",["text-align","right"],["width","50px"]]);
// array_push($css_array,["price",["text-align","right"]]);
// array_push($css_array,["price_tax",["text-align","right"]]);
array_push($css_array,["sum_total_client_price",["text-align","right"]]);


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



$split_ratio_left = 5;
$split_ratio_right = 7;
//require('./contents/common/datatable_html_split.php');
require('./contents/sangjo/bill/html/datatable_html_popup.php');








?>




  <!----  페이지별 html 필요 코드 --> 
  

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
$dom_setting = '"dom": \'<"top"f>rt<"bottom"lip><"clear">\',';
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


  switch (column) {


    case "exec_checkbox":
        rValue = "<input type='checkbox' class='flat icheck'>";
      break;
    // case "price_tax":
    //     rValue = number_format(data);
    //   break;
    case "sum_total_client_price":
        rValue = number_format(data);
      break;


    case "manager_email":
        if(full.bill_status == undefined || full.bill_status == "저장/미발송"  || full.fill_status == "폐기"){
           //rValue = full.origin_manager_email;
           //rValue = "<textarea class='form-control' name='manager_email' >"+checkNull(full.origin_manager_email)+"</textarea>";
           rValue = "<textarea class='form-control' name='manager_email' ></textarea>";

        }else{

          <?
          if($_GET['st'] == "발송완료" || $_GET['st'] == "승인완료" ){?>

                    if (full.manager_email && full.manager_email.trim() !== "") {
                      var emails = full.manager_email.split(',');
                      var buttonHTML = "";

                      emails.forEach(function(email) {
                        email = email.trim();
                        if (email) {
                          buttonHTML += "<div style='margin-bottom: 10px;'><button type='button' class='m_email' value='"+email+"'>" + email + "</button> <button type='button' class='btn_resend'>재발송</button></div>";
                        }
                      });

                        rValue = buttonHTML;
                      }else{
                        rValue = "";
                      }
          <?}else{?>
                    //rValue = "<textarea class='form-control' name='manager_email' >"+checkNull(full.manager_email)+"</textarea>";
                    rValue = "<textarea class='form-control' name='manager_email' ></textarea>";

          <?}
          ?>



        }
      break;



    case "origin_manager_info":
      // Check if origin_manager_info is null or empty
      if (full.origin_manager_info) {
          // Split the origin_manager_info by comma
          let names = full.origin_manager_info.split(',');

          // Initialize an empty array to hold the span elements
          let spans = [];

          // Loop through each name/email pair
          names.forEach(nameEmail => {
              // Split by slash to separate name and email
              let parts = nameEmail.split('/');
              let name = parts[0]; // Name is always the first part
              let email = parts.length > 1 ? parts[1] : ''; // Email is the second part if exists

              // Create a span element with the appropriate class and attributes
              let span = `<button class='btn manager_name_button  btn-xs' ${email ? `manager_email='${email}'` : ''} title='${email}' >${name}</button>`;
              
              // Push the span element into the spans array
              spans.push(span);
          });

          // Join all spans into a single string separated by spaces
          rValue = spans.join('');
      } else {
          // If origin_manager_info is null or empty, set rValue to an empty string
          rValue = '';
      }
      break;



    case "bill_status":
      
      if(data == undefined){
        rValue  = "<button class='btn btn-danger btn-xs btn-bill'>미발행</button>";
      }
      else{
        rValue = data;
      }
      
      /*
      else if(data == "저장/미발송"){
        rValue  = "<button class='btn btn-warning btn-xs btn-bill'>"+data+"</button>";
      }else if(data == "발송완료/수락대기"){
        rValue  = "<button class='btn btn-info btn-xs btn-bill'>"+data+"</button>";
      }else if(data == "수정요청"){
        rValue  = "<button class='btn btn-danger btn-xs btn-bill'>"+data+"</button>";
      }else if(data == "승인완료"){
        rValue  = "<button class='btn btn-success btn-xs btn-bill'>"+data+"</button>";
      }else if(data == "폐기"){
        rValue  = "<button class='btn btn-dark btn-xs btn-bill'>"+data+"</button>";

      }else{
        rValue = data;
      }

      */

      break;



    case "sdate":
        if(data == undefined || data == "미지정"){
          rValue = "<span class='info'>미지정</span>";

        }else{
          rValue = data;
        }
      break;

    case "exec_view":
      if(full.bill_idx != undefined &&  full.bill_idx != null){
        rValue = "<button class='btn btn-success btn-xs btn-view-bill' bill_idx='" + full.bill_idx + "'>거래명세서</button>";


        if(full.bill_status == undefined){
          rValue  += "<button class='btn btn-warning btn-xs btn-bill-save' bill_idx='" + full.bill_idx + "'>저장하기</button>";
        }else if(full.bill_status == "저장/미발송"){
          rValue  += "<button class='btn btn-info btn-xs btn-bill-send' bill_idx='" + full.bill_idx + "'>발송하기</button>";
        }else if(full.bill_status == "발송완료/수락대기"){
          rValue  += "<button class='btn btn-primary btn-xs btn-bill-approve' bill_idx='" + full.bill_idx + "'>직권승인</button>";
        }else if(full.bill_status == "수정요청"){
          rValue  += "<button class='btn btn-danger btn-xs btn-bill-modify' bill_idx='" + full.bill_idx + "'>수정하기</button>";
        }else if(full.bill_status == "승인완료"){
          //rValue  += "<button class='btn btn-primary btn-xs btn-bill-tax' bill_idx='" + full.bill_idx + "'>세금계산서발행</button>";
        }else if(full.bill_status == "폐기"){
          rValue  += "<button class='btn btn-warning btn-xs btn-bill-save' bill_idx='" + full.bill_idx + "'>저장하기</button>";
        }else{
          rValue += full.bill_status;
        }
      }
      else{
        rValue = "";
      }




      break;


    case "payment_method":
        if(data == undefined || data == "미지정"){
          rValue = "<span class='info'>미지정</span>";

        }else{
          rValue = data;
        }
      break;
    case "exec_next":




          if(full.bill_status != undefined){
            rValue  = "<button class='btn btn-dark btn-xs btn-bill-destroy'>폐기</button>";

          }else{
            rValue  = "";
            //rValue  += "<button class='btn btn-dark btn-xs btn-bill-destroy'>폐기</button>";

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
    case "company_name":

      $td.closest("tr").attr("consulting_idx",rowData.consulting_idx);
      $td.closest("tr").attr("bill_idx",rowData.bill_idx);
      $td.closest("tr").attr("category1_idx",rowData.category1_idx);


      break;
    case "t_category1_name":

      $td.closest("tr").attr("category1_idx",rowData.category1_idx);

      break;





    case "sdate":

      $td.attr("sdate",rowData.sdate);

      break;



    // case "exec_checkbox":
    //   $td.find('input').iCheck({
    //     handle: 'checkbox'
    //   });
    //   break;



    default:
      break;
  }
}



</script>


<script src="js/yearPicker/yearpicker.js?time=<?=time()?>"></script>

<script>
$(document).ready(function() {

  var today = new Date();
  var year = today.getFullYear();

   $(".yearpicker").yearpicker({
      year: year,
      startYear: 2022,
      endYear: 2040,
   });
});
</script>



<script>
        $(document).ready(function() {
          //header 영역 기본 보여지는 체크박스에 아이체크적용
            $(document).on("click","input.icheck").iCheck({
              checkboxClass: 'icheckbox_flat-green'
            });

        });

        //datatable.js에 의해서 동적으로 이후에 추가되는 checkbox 에 icheck 적용
        $('#datatable-main').on('draw.dt', function() {
          $('#datatable-main input.icheck').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green',
                increaseArea: '20%' // optional
            });
        });

   
        // Select/Deselect all checkboxes
        $(document).on('ifChecked', '.select-all', function(event) {
            $('#datatable-main input.icheck').iCheck('check');
        });

        $(document).on('ifUnchecked', '.select-all', function(event) {
            $('#datatable-main input.icheck').iCheck('uncheck');
        });






        //datatable.js에 의해서 동적으로 이후에 추가되는 checkbox 에 icheck 적용
        $('#datatable-bill-popup').on('draw.dt', function() {
          $('#datatable-bill-popup input.icheck').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green',
                increaseArea: '20%' // optional
            });
        });

        // Select/Deselect all checkboxes
        $(document).on('ifChecked', '.select-all-popup', function(event) {
            $('#datatable-bill-popup input.icheck').iCheck('check');
        });

        $(document).on('ifUnchecked', '.select-all-popup', function(event) {
            $('#datatable-bill-popup input.icheck').iCheck('uncheck');
        });




</script>
