<?
/************* *///보기 그룹이 선언된 경우 보기그룹별 배열 정리 시작 *****************************/

if($function_process_search == "on"){

  //  if($_GET['sval'] != "" && $_GET['scol'] != "" ){
  //     $process_option = make_options_array($process_options_list,"0"); //별도 파라미터가 넘어오면 전체 옵션으로 선택되게.
  //  }

    if($_GET['sprocess'] != ""){
      $process_option = make_options_array($process_options_list,$_GET['sprocess']); //별도 파라미터가 넘어오면 전체 옵션으로 선택되게.
    }

}



$all_columns_index = [];
$all_columns_arr = [];
for ($i = 0; $i < count($th_info); $i++) { 
  array_push($all_columns_index,$i);  //th index 를 개수만큼 배열에 할당
  array_push($all_columns_arr,$th_info[$i][0]);  //모든 th 칼럼명
}


if (isset($view_group_array) ){
  for ($i=0;$i<count($view_group_array);$i++ )
  {
    $view_group_name = $i;
    $th_view_array = []; //그룹별 보여질 칼럼 index
    $th_hide_array = []; //그룹별 숨길칼럼 index
    $th_hide_columns = []; //그룹별 숨길칼럼이름
    for ($j=0;$j<count($th_info);$j++ )
    {
      
      $temp = 0;
      $out_column = $th_info[$j][0];


      if(is_iterable($view_group_array[$i][1])){
        for ($k=0;$k<count($view_group_array[$i][1]);$k++ ) //보여질 칼럼 목록 순환
        {
          $in_column = $view_group_array[$i][1][$k];
          if($out_column == $in_column){
              $temp++;
              array_push($th_view_array,$j);
          }
        }
      }


    }





    if(is_array($view_group_array[$i][1])){
      $th_hide_columns = array_diff($all_columns_arr,$view_group_array[$i][1]); //중복 요소 제거, 기존의 index를 그대로 가지고 있음
    }
    $th_hide_columns = array_values($th_hide_columns); //index 초기화

    if(is_array($th_view_array)){
      $th_hide_array = array_diff($all_columns_index,$th_view_array); //중복 요소 제거, 기존의 index를 그대로 가지고 있음
    }
    $th_hide_array = array_values($th_hide_array); //index 초기화
    
    
    $view_group_array[$i][2] = $th_hide_columns; //숨길 칼럼명
    $view_group_array[$i][3] = $th_view_array;  //보여질 칼럼 index
    $view_group_array[$i][4] = $th_hide_array;  //숨길 칼럼 index

  }

}

/************* *///보기 그룹이 선언된 경우 보기그룹별 배열 정리 끝 *****************************/




$edit_hide_array = [];

$column_array = [];

for ($i=0;$i<count($th_info);$i++ )
{


  $column = $th_info[$i][0];
  $column_array[$i]['column'] = $th_info[$i][0];
  $column_array[$i]['th_title'] = $th_info[$i][1];
  $column_array[$i]['type'] = isset($th_info[$i][2])?$th_info[$i][2]:'';
  $column_array[$i]['type_value'] = isset($th_info[$i][3])?$th_info[$i][3]:'';

  //$column_array[$i]['edit'] = $edit_array[$j][1]; //아래쪽 수정가능 칼럼처리 for 문에서 추가됨



  //수정가능한 칼럼 표시
  if(is_iterable($edit_array)){

    for ($j=0;$j<count($edit_array);$j++ )
    {
      if($column == $edit_array[$j][0]){
        $column_array[$i]['edit'] = $edit_array[$j][1];

        if(isset($edit_array[$j][2][0]) && $edit_array[$j][2][0] == "origin_column"){ //editable_img 등에서 cache_ur 에 의해 다른 이름으로 칼럼명을 추출하는 경우 이미지 업데이트 할때 기본저장 칼럼명이 따라와야 가능하기 때문에 가져옴.
          $column_array[$i]['edit_origin_column'] = $edit_array[$j][2][1]; //datatable.js.php 에서  $(td).attr("origin_column",$col['edit_origin_column']); 로 사용됨.
        }
      }
    }
  }


  //검색창 키칼럼 매칭
  if(is_iterable($keyword_column_array)){

    for ($j=0;$j<count($keyword_column_array);$j++ )
    {
      if($keyword_column_array_selected == $keyword_column_array[$j][1]){
        $keyword_column_selected_index = $j;
      }
    }
  }






  //join 된 테이블칼럼의 값을 직접 수정하도록 할 경우
  if(is_iterable($other_table_column_edit_array)){

    for ($j=0;$j<count($other_table_column_edit_array);$j++ )
    {
      if($column == $other_table_column_edit_array[$j][0]){
          // table_name에서 db명 추출
          $db_name = "";
          if(strpos($table_name, ".") !== false){
              $db_name = explode(".", $table_name)[0] . ".";
          }

          $column_array[$i]['join_table_name'] = $db_name . $other_table_column_edit_array[$j][1];
          $column_array[$i]['join_table_key_name'] = $other_table_column_edit_array[$j][2];
      }
    }
  }


  

  //한 칼럼 에 다수의 css 추가 가능
  if(is_iterable($css_array)){

    for ($j=0;$j<count($css_array);$j++ )
    {
      if($column == $css_array[$j][0]){
        for ($k=1;$k<count($css_array[$j]);$k++ )
        {

          $column_array[$i]['css'] = [];
          array_push($column_array[$i]['css'],$css_array[$j][$k]);
        }
      }
    }
  }




  //한 칼럼 에 다수의 클래스 추가 가능

  if(isset($class_array) && is_iterable($class_array)){
    for ($j=0;$j<count($class_array);$j++ )
    {
      if($column == $class_array[$j][0]){
        for ($k=0;$k<count($class_array[$j][1]);$k++ )
        {

          $column_array[$i]['class'] = [];
          array_push($column_array[$i]['class'],$class_array[$j][1][$k]);
        }
      }
    }
  }




  //soring 불가 칼럼 array
  if(isset($unsortable_columns) && is_iterable($unsortable_columns)){

    for ($j=0;$j<count($unsortable_columns);$j++ )
    {
      if($column == $unsortable_columns[$j]){
        $column_array[$i]['unsortable'] = "Y";
      }
    }   
  }



  //soring 불가 칼럼 array
  if(isset($numeric_columns) && is_iterable($numeric_columns)){

    for ($j=0;$j<count($numeric_columns);$j++ )
    {
      if($column == $numeric_columns[$j]){
        $column_array[$i]['numeric'] = "Y";
      }
    }   
  }



  //padding 없는 칼럼 array
  if(isset($padding_unset_columns) && is_iterable($padding_unset_columns)){

    for ($j=0;$j<count($padding_unset_columns);$j++ )
    {
      if($column == $padding_unset_columns[$j]){
        
        $column_array[$i]['padding_unset'] = "Y";
      }
    }   
  }

  if(isset($edit_hide_columns) && is_iterable($edit_hide_columns)){
    //수정시 사라질 칼럼
    for ($j=0;$j<count($edit_hide_columns);$j++ )
    {
        if($column == $edit_hide_columns[$j]){
          $column_array[$i]['edit_hide'] = "Y";
          array_push($edit_hide_array,$i);
        }
    }
  }



  //화면 시작시 사라질 칼럼
  if(isset($_GET['viewgroup']) && $_GET['viewgroup'] != ""){ //주소창 호출시 get 파라미터에 viewgroup=1 과 같이 viewgroup 을 지정했을때
      $viewgroupStartIndex = $_GET['viewgroup'];
  }else{ //viewgroup 지정이 없을때.
      $viewgroupStartIndex = 0;
  }



  //화면 시작시 사라질 칼럼
  if(isset($view_group_array[$viewgroupStartIndex][2]) && is_iterable($view_group_array[$viewgroupStartIndex][2])){

    for ($j=0;$j<count($view_group_array[$viewgroupStartIndex][2]);$j++ ) //뷰그룹의 첫번째 요소 중 숨길칼럼 목록
    {
        if($column == $view_group_array[$viewgroupStartIndex][2][$j]){
          $column_array[$i]['first_hide'] = "Y";
        }
    }
  }



  //필터 객체 루프
  foreach($filter as $key=>$value){
    if($column == $filter[$key]['column']){
        //print_r($filter[$key]['column']);
        $column_array[$i]['filter'] = "Y";
        $column_array[$i]['unsortable'] = "Y"; //필터 있는 열은 filter 박스 클릭시 sorting 이벤트가 중복되므로 무조건 막야야 함.

    }
  }


   






}







//datatable 에서 필터 dropbox 출력 함수
function print_filter($tar_column){

  global $filter;
  
	if($tar_column == ""){
	  echo "";
	  return;
  }
  
	 
	$this_options = $filter[$tar_column]['options'];

	if(count($this_options) > 0){
	  $select_box = '<select name="'.$filter[$tar_column]['column'].'" class="'.$filter[$tar_column]['column'].' form-control select_filter" >';
   
  
	  for ($i=0;$i<count($this_options);$i++ )
	  {
       $this_opt= $this_options[$i];
       if($this_opt[0] == ""){
                $select_box .= '<option value="'.$this_opt[0].'" role="option" >'.$this_opt[1].'</option>';

       }else{
                $select_box .= '<option value="'.$this_opt[0].'" >'.$this_opt[1].'</option>';

       }
	  }
  
	  $select_box .='</select>';
  
	  echo $select_box;
	  return;
	}
  
}
  





?>