<?


if($admin_info['pm_super'] == "종합관리자"){
  //
}else{
  header('Location:./login/'.$admin_info['admin_login_page']);
  exit;  
}





$folder_name = "admin_list";  //폴더명
$title ="관리자 목록";

$table_name =$db_admin.".admin_list";
$key_column_name ="admin_idx";

$function_date_search  = "on";   // 날짜검색기능 on ,off
$function_process_search = "off";  // 중앙 버튼식 선택메뉴 on ,off
$function_multi_search = "on";  // 체크박스 선택된 칼럼 검색
$function_keyword_search = "on";    // 키워드검색기능 on ,off
$function_add = "on";    // 추가하기 on ,off




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

  array_push($keyword_column_array,["관리자명","admin_name","like"]);   //로딩시 첫 옵션 기본선택됨. dataTables.ajax.button.js 에서 keyword_query = setKeywordQuery(0); 에 의해 첫번째 옵션이 기본옵션으로 선택됨.
  array_push($keyword_column_array,["아이디","admin_id","like"]);
  // array_push($keyword_column_array,["카테고리","t_category_name","like"]);
  // array_push($keyword_column_array,["키워드","brand_keyword","like"]);
  // array_push($keyword_column_array,["등록관리자명","cf_admin_name","like"]);
  // array_push($keyword_column_array,["메뉴종류>=N","t_menu_count",">="]);

  // array_push($keyword_column_array,["쿠폰보유량>=N","t_brand_coupon_count",">="]);
  // array_push($keyword_column_array,["최대할인율>=N","t_max_discount_percent",">="]);

  //-------------------------------------------------------------------------
  

  $keyword_column_array_selected = "admin_name";      //기본 선택옵션 value

  $process_keyword_display = 1; //수정없음
}else{
  $process_keyword_display = 0; //수정없음
}





/*th 헤더 정보 입력 */
$th_info = [];

array_push($th_info,["admin_idx","고유번호"]);



//array_push($th_info,["storage_idx","소속"]);
//array_push($th_info,["admin_level","권한"]);


array_push($th_info,["exec_permission","권한"]); 
array_push($th_info,["exec_storage","소속"]); 


array_push($th_info,["admin_id","아이디"]);
array_push($th_info,["admin_name","이름"]);
array_push($th_info,["admin_hp","휴대폰"]);
array_push($th_info,["start_page","시작페이지","convert",[    ["종합물류관리","종합물류관리"],["화훼관리","화훼관리"],["상조물류관리","상조물류관리"],["계약업체관리","계약업체관리"],["통계관리","통계관리"],["인사총무관리","인사총무관리"]    ] ]);

array_push($th_info,["exec_pw_reset","비밀번호초기화"]);

// array_push($th_info,["regist_datetime","등록일"]);
// array_push($th_info,["update_datetime","수정일"]);


// array_push($th_info,["category_idx","카테고리" ]);
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

array_push($th_info,["admin_status","승인단계","selectDropDown",[  ["승인대기","승인대기","btn-info"],["승인","승인","btn-success"],["승인거절","승인거절","btn-dark","divider"] ,["탈퇴","탈퇴","btn-dark"]  ] ]);

array_push($th_info,["admin_login_count","로그인횟수"]);

array_push($th_info,["regist_datetime","등록일<br>수정일","datetime2line"]);


$admin_exec = "<button type='button' class='btn btn-dark btn-xs btn_modify'>수정</button>
<button type='button' class='btn btn-primary btn-xs btn_permission_setting' data-toggle='modal' data-target='.model-permission'>소속/권한</button>";
array_push($th_info,["exec","실행","custom_exec",$admin_exec]); //수정 + 소속/권한




//$other_table_column = "a:cache_url(cache_img_url) as cache_img_url"; 
//$other_table_column = "a:cache_url(brand_img_url) as cache_img_url/a:category_idx:category:category_name/a:brand_idx:brand_explain:brand_ex"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.

//$other_table_column = "a:admin_idx:admin.admin_permission:"; //메인테이블이 아닌 다른 테이블에서 join을 통해 가져와야 하는 칼럼 => sorting 할때 뒤쪽에 붙여야 함.
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

array_push($css_array,["regist_datetime",["min-width","130px"]]);
array_push($css_array,["update_datetime",["min-width","130px"]]);
array_push($css_array,["exec",["min-width","50px"]]);




/******************수정모드 설정************************************************************************************************************* */




//수정모드 설정
$edit_array = [];

array_push($edit_array,["admin_name","이름","필수","text"]);
array_push($edit_array,["admin_hp","휴대폰","필수","text"]);
//array_push($edit_array,["admin_id","아이디","필수","text"]);

$start_page_array = make_options_array("종합물류관리:종합물류관리/화훼관리:화훼관리/상조물류관리:상조물류관리/계약업체관리:계약업체관리/통계관리:통계관리/인사총무관리:인사총무관리");


$admin_level_array = make_options_array("종합관리자:종합관리자:selected/물류관리자:물류관리자/물류창고관리자:물류창고관리자/상조관리자:상조관리자/상조창고관리자:상조창고관리자/계약업체관리자:계약업체관리자");
$admin_status_array = make_options_array("승인:승인/승인대기:승인대기/승인거절:승인거절/탈퇴:탈퇴");


//근무지는 DB에서 가져오기
$sel0 = mysqli_query($dbcon, "select * from ".$db_sangjo.".storage order by storage_name  ") or die(mysqli_error($dbcon));
$sel_num0 = mysqli_num_rows($sel0);

$storage_idx_array = [];
if ($sel_num0 > 0) {
  while($data0 = mysqli_fetch_assoc($sel0)){
      $this_arr = array();
      array_push($this_arr,$data0['storage_idx']);
      array_push($this_arr,$data0['storage_name']);
      
      if($data0['selected'] == 1){array_push($this_arr,"selected");}

      array_push($storage_idx_array,$this_arr);      
  }
}


array_push($edit_array,["admin_level","select",["options",$admin_level_array],["direct_save","1"],["same","Y"]]);
array_push($edit_array,["storage_idx","select",["options",$storage_idx_array],["direct_save","1"],["same","N"]]);
array_push($edit_array,["admin_status","select",["options",$admin_status_array],["direct_save","1"],["same","Y"]]);
array_push($edit_array,["start_page","select",["options",$start_page_array],["direct_save","1"],["same","Y"]]);


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
array_push($add_array,["admin_level","권한","","select",$admin_level_array]); //DB column 기준이라서 brand_name 이 아니고,brand_idx로,
//array_push($add_array,["storage_idx","소속","","select",[["1","본사","SELECTED"],["2","서산공장"],["4","개발팀"]]]); //DB column 기준이라서 brand_name 이 아니고,brand_idx로,
array_push($add_array,["storage_idx","소속","","select",$storage_idx_array]); //DB column 기준이라서 brand_name 이 아니고,brand_idx로,

array_push($add_array,["admin_name","이름","필수","text"]);
array_push($add_array,["admin_hp","휴대폰","","text"]);
array_push($add_array,["admin_id","아이디","필수","text"]);

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

// $column = "admin_level";
// $filter[$column]['column'] = $column;  //필터를 적용할 칼럼 이름
// $opt = ":--권한--/종합관리자:종합관리자/물류관리자:물류관리자/물류창고관리자:물류창고관리자/상조관리자:상조관리자/상조창고관리자:상조창고관리자/계약업체관리자:계약업체관리자";  //"0:노출중단/1:노출"
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


  <?php

   $sel_fs = mysqli_query($dbcon, "select * from ".$db_fullfillment.".storage order by display_order desc, storage_name ") or die(mysqli_error($dbcon));
   $sel_fs_num = mysqli_num_rows($sel_fs);
   
   $fstorage = "";
   if ($sel_fs_num > 0) {
      //$data = mysqli_fetch_assoc($sel);
      while($data_fs = mysqli_fetch_assoc($sel_fs)) {
        $fstorage .= "<option value='".$data_fs['storage_idx']."'>".$data_fs['storage_name']."</option>\n";

      }
   }


   $sel_ss = mysqli_query($dbcon, "select * from ".$db_sangjo.".storage order by display_order desc, storage_name ") or die(mysqli_error($dbcon));
   $sel_ss_num = mysqli_num_rows($sel_ss);
   
   $sangjo_storage = "";
   if ($sel_ss_num > 0) {
      //$data = mysqli_fetch_assoc($sel);
      while($data_ss = mysqli_fetch_assoc($sel_ss)) {
        $sangjo_storage .= "<option value='".$data_ss['storage_idx']."'>".$data_ss['storage_name']."</option>\n";

      }
   }



   $sel_flower = mysqli_query($dbcon, "select * from ".$db_flower.".storage order by display_order desc, storage_name ") or die(mysqli_error($dbcon));
   $sel_flower_num = mysqli_num_rows($sel_flower);
   
   $flower_storage = "";
   if ($sel_flower_num > 0) {
      //$data = mysqli_fetch_assoc($sel);
      while($data_flower = mysqli_fetch_assoc($sel_flower)) {
        $flower_storage .= "<option value='".$data_flower['storage_idx']."'>".$data_flower['storage_name']."</option>\n";

      }
   }


   $sel_hrm = mysqli_query($dbcon, "select * from ".$db_hrm.".jisa where display=1 order by jisa_idx ") or die(mysqli_error($dbcon));
   $sel_hrm_num = mysqli_num_rows($sel_hrm);
   
   $hrm_storage = "";
   if ($sel_hrm_num > 0) {
      //$data = mysqli_fetch_assoc($sel);
      while($data_hrm = mysqli_fetch_assoc($sel_hrm)) {
        $hrm_storage .= "<option value='".$data_hrm['jisa_idx']."'>".$data_hrm['jisa']."</option>\n";

      }
   }




  ?>


  <!----  페이지별 html 필요 코드 --> 
  
  <div class="modal fade model-permission" tabindex="-1" role="dialog" aria-hidden="true"  aria-labelledby="mySmallModalLabel" id="modal-admin-permission">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">소속 / 권한 설정</h4>
            </div>
            <div class="modal-body">
            <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>구분</th>
                                <th>접근권한</th>
                                <th>소속</th>
                                <th>시작페이지</th>
                              </tr>
                            </thead>
                            <tbody>



                              <tr>
                                <th scope="row">종합관리</th>
                                <td>
                                  <select class="form-control" name="permission_super" id="select_permission_super">
                                    <option value="0">권한없음</option>
                                    <option value="종합관리자">종합관리자</option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control" name="storage_super" id="select_storage_super">>
                                    <option value="0">해당없음</option>
                                    <?=$fstorage?>
                                  </select>
                                </td>
                                <td class='td_start' rowspan="6">
                                    <select class="form-control"  name="start_page" id="select_start_page">

                                        <option value="종합물류관리">종합물류관리</option>
                                        <option value="상조물류관리">상조물류관리</option>
                                        <option value="화훼관리">화훼관리</option>
                                        <option value="계약업체관리">계약업체관리</option>
                                        <option value="통계관리">통계관리</option>
                                        <option value="인사총무관리">인사총무관리</option>

                                    </select>
                                </td>
                              </tr>

                              <tr>
                                <th scope="row">종합물류</th>
                                <td>
                                  <select class="form-control" name="permission_fullfillment" id="select_permission_fullfillment">
                                  <option value="0">권한없음</option>
                                    <option  value="종합물류관리자">종합물류관리자</option>
                                    <option  value="종합물류창고관리자">종합물류창고관리자</option>
                                    <option  value="종합물류뷰어">종합물류뷰어</option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control" name="storage_fullfillment" id="select_storage_fullfillment">>
                                    <option value="0">해당없음</option>
                                    <?=$fstorage?>
                                  </select>
                                </td>
                                 
                              </tr>


                              <tr>
                                <th scope="row">상조물류</th>
                                <td>
                                  <select class="form-control" name="permission_sangjo" id="select_permission_sangjo">>
                                  <option value="0">권한없음</option>
                                    <option   value="상조물류관리자">상조물류관리자</option>
                                    <option   value="상조물류창고관리자">상조물류창고관리자</option>
                                    <option   value="상조물류뷰어">상조물류뷰어</option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control" name="storage_sangjo" id="select_storage_sangjo">>
                                    <option value="0">해당없음</option>
                                    <?=$sangjo_storage?>
                                  </select>
                                </td>
                                
                              </tr>





                              <tr>
                                <th scope="row">화훼관리</th>
                                <td>
                                  <select class="form-control" name="permission_flower" id="select_permission_flower">
                                  <option value="0">권한없음</option>
                                    <option  value="화훼관리자">화훼관리자</option>
                                    <option  value="화훼지점관리자">화훼지점관리자</option>
                                    <option  value="화훼뷰어">화훼뷰어</option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control" name="storage_flower" id="select_storage_flower">>
                                    <option value="0">해당없음</option>
                                    <?=$flower_storage?>
                                  </select>
                                </td>
                                
                              </tr>
                              <tr>
                                <th scope="row">계약업체</th>
                                <td>
                                  <select class="form-control" name="permission_consulting" id="select_permission_consulting">
                                  <option value="0">권한없음</option>
                                    <option value="계약업체관리자">계약업체관리자</option>
                                    <option value="계약업체뷰어">계약업체뷰어</option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control" name="storage_consulting" id="select_storage_consulting">>
                                    <option value="0">해당없음</option>
                                    <?=$fstorage?>
                                  </select>
                                </td>
                                
                              </tr>
                              <tr>
                                <th scope="row">통계관리</th>
                                <td>
                                  <select class="form-control" name="permission_statistics" id="select_permission_statistics">
                                  <option value="0">권한없음</option>
                                    <option value="통계관리자">통계관리자</option>
                                    <option value="통계뷰어">통계뷰어</option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control" name="storage_statistics" id="select_storage_statistics">>
                                    <option value="0">해당없음</option>
                                    
                                  </select>
                                </td>
                                
                              </tr>


                              <tr>
                                <th scope="row">인사총무관리</th>
                                <td>
                                  <select class="form-control" name="permission_hrm" id="select_permission_hrm">
                                  <option value="0">권한없음</option>
                                    <option value="인사관리자">인사관리자</option>
                                    <option value="총무관리자">총무관리자</option>
                                    <option value="인사총무관리자">인사총무관리자</option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control" name="storage_hrm" id="select_storage_hrm">>
                                    <option value="0">해당없음</option>
                                    <?=$hrm_storage?>
                                  </select>
                                </td>
                                
                              </tr>
                            </tbody>
                          </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
              <button type="button" class="btn btn-primary btn_modal_save">저장</button>
            </div>

          </div>
        </div>
      </div>
  
  <!----  페이지별 html 필요 코드 끝 --> 








<?
require('./contents/common/datatable.js.php');
//require('./contents/'.$folder_name.'/columnDefs.js.php');
?>



<script>

function datatableRender(column,data,full){

  var rValue;
  switch (column) {

    // case "admin_idx":
    //       rValue = "<span class='admin_idx'><a href='?page=admin_list/list&sval="+full.admin_idx+"' target='category'>"+full.admin_name+"</a></span><br><span class='etc category_name'>("+data+")</span>";
    //       break;
    case "exec_permission":
      rValue = "";

       if(full.pm_super != undefined && full.pm_super != "undefined" && full.pm_super != "" ){
          rValue += full.pm_super+"<br>";
       }
       if(full.pm_fullfillment != undefined && full.pm_fullfillment != "undefined" && full.pm_fullfillment != "" ){
          rValue += full.pm_fullfillment+"<br>";
       }
       if(full.pm_sangjo != undefined && full.pm_sangjo != "undefined" && full.pm_sangjo != "" ){
          rValue += full.pm_sangjo+"<br>";
       }
       if(full.pm_flower != undefined && full.pm_flower != "undefined" && full.pm_flower != "" ){
          rValue += full.pm_flower+"<br>";
       }
       if(full.pm_consulting != undefined && full.pm_consulting != "undefined" && full.pm_consulting != "" ){
          rValue += full.pm_consulting+"<br>";
       }
       if(full.pm_statistics != undefined && full.pm_statistics != "undefined" && full.pm_statistics != "" ){
          rValue += full.pm_statistics+"<br>";
       }
       if(full.pm_hrm != undefined && full.pm_hrm != "undefined" && full.pm_hrm != "" ){
          rValue += full.pm_hrm+"<br>";
       }
       if(rValue != ""){
          rValue = rValue.slice(0, -4);
       }

        break;


    case "exec_storage":


      rValue = "";
      if(full.t_storage_super != undefined && full.t_storage_super != "undefined" && full.t_storage_super != "" ){
        if(full.pm_super != undefined && full.pm_super != "undefined" && full.pm_super != "" ){
            rValue += full.t_storage_super+"<br>";
        }
      }else{
        if(full.pm_super != undefined && full.pm_super != "undefined" && full.pm_super != "" ){
            rValue += "--<br>";
        }
      }

      if(full.t_storage_fullfillment != undefined && full.t_storage_fullfillment != "undefined" && full.t_storage_fullfillment != "" ){

        if(full.pm_fullfillment != undefined && full.pm_fullfillment != "undefined" && full.pm_fullfillment != "" ){
          rValue += full.t_storage_fullfillment+"<br>";
       }
      }else{
        if(full.pm_fullfillment != undefined && full.pm_fullfillment != "undefined" && full.pm_fullfillment != "" ){
          rValue += "--<br>";
       }
      }

      if(full.t_storage_sangjo != undefined && full.t_storage_sangjo != "undefined" && full.t_storage_sangjo != "" ){
        if(full.pm_sangjo != undefined && full.pm_sangjo != "undefined" && full.pm_sangjo != "" ){
          rValue += full.t_storage_sangjo+"<br>";
        }
      }else{
        if(full.pm_sangjo != undefined && full.pm_sangjo != "undefined" && full.pm_sangjo != "" ){
          rValue += "--<br>";
        }
      }

      if(full.t_storage_flower != undefined && full.t_storage_flower != "undefined" && full.t_storage_flower != "" ){
        if(full.pm_flower != undefined && full.pm_flower != "undefined" && full.pm_flower != "" ){
          rValue += full.t_storage_flower+"<br>";
       }
      }else{
        if(full.pm_flower != undefined && full.pm_flower != "undefined" && full.pm_flower != "" ){
          rValue += "--<br>";
       }
      }

      if(full.t_storage_consulting != undefined && full.t_storage_consulting != "undefined" && full.t_storage_consulting != "" ){
        if(full.pm_consulting != undefined && full.pm_consulting != "undefined" && full.pm_consulting != "" ){
          rValue += full.t_storage_consulting+"<br>";
        }
      }else{
        if(full.pm_consulting != undefined && full.pm_consulting != "undefined" && full.pm_consulting != "" ){
          rValue += "--<br>";
        }
      }


      if(full.t_storage_statistics != undefined && full.t_storage_statistics != "undefined" && full.t_storage_statistics != "" ){
        if(full.pm_statistics != undefined && full.pm_statistics != "undefined" && full.pm_statistics != "" ){
          rValue += full.t_storage_statistics+"<br>";
        }
      }else{
        if(full.pm_statistics != undefined && full.pm_statistics != "undefined" && full.pm_statistics != "" ){
          rValue += "--<br>";
        }
      }

      if(full.t_storage_hrm != undefined && full.t_storage_hrm != "undefined" && full.t_storage_hrm != "" ){
        if(full.pm_hrm != undefined && full.pm_hrm != "undefined" && full.pm_hrm != "" ){
          rValue += full.t_storage_hrm+"<br>";
        }
      }else{
        if(full.pm_hrm != undefined && full.pm_hrm != "undefined" && full.pm_hrm != "" ){
          rValue += "--<br>";
        }
      }




      if(rValue != ""){
          rValue = rValue.slice(0, -4);
      }

      break;



    case "exec_pw_reset":

      //console.log("exec_storage:000");

      if(full.admin_pw == ''){
        rValue = '<button type="button" class="btn btn-danger btn-xs btn_pw_reset">초기화상태</button>';

      }else{
        rValue = '<button type="button" class="btn btn-info btn-xs btn_pw_reset">비번초기화</button>';

      }
      break;

    default:
      rValue = data;
      break;

  }




  return rValue;


}

function datatableCreatedCell(column,$td,rowData){
  // switch (column) {
  //   case "category_idx":
  //     $td.attr('category_idx_text', rowData.t_category_name); 
  //     break;

  //   default:
  //     break;
  // }
}



</script>


