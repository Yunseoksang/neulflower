<?php
// 에러 리포팅 설정
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set("display_errors", 0);

// 출력 버퍼링 시작
ob_start();

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/phpoffice_phpspreadsheet/vendor/autoload.php';
require_once 'common_spreadsheet.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$filename = date("지점별재고현황_Ymd_His",time()).".xlsx";

// 창고 목록
$storage_arr = array();
$sel_storage = mysqli_query($dbcon, "select storage_idx,storage_name from storage order by display_order desc,storage_name") or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);

if($sel_storage_num > 0){
    while($data_storage = mysqli_fetch_assoc($sel_storage)) {
        array_push($storage_arr,$data_storage);
    }
}

// 더존 지사만 필터링
$branch_arr = array();
foreach($storage_arr as $storage) {
    if(strpos($storage['storage_name'], '더존') !== false) {
        array_push($branch_arr, $storage);
    }
}

// 본사 찾기
$headquarters_storage = null;
foreach($storage_arr as $storage) {
    if(strpos($storage['storage_name'], '본사') !== false) {
        $headquarters_storage = $storage;
        break;
    }
}

// 제품 목록
$product_arr = array();
$sel = mysqli_query($dbcon, "select product_idx,product_name,display_group,memo from product where product_name NOT LIKE '%test%' order by display_group, CAST(product_name AS CHAR CHARACTER SET utf8) COLLATE utf8_unicode_ci") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);
$total_cnt = $sel_num;

if ($sel_num > 0) {
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_arr,$data);
    }
}

// 창고별 재고 합계
$storage_sum_sql = "
    select sum(storage_safe.current_count) as sum_current_count, storage_safe.storage_idx 
    from storage_safe 
    left join storage on storage_safe.storage_idx = storage.storage_idx
    where storage.storage_idx is not null
    and storage_safe.product_idx in (select product_idx from product where product_name NOT LIKE '%test%')
    group by storage_safe.storage_idx 
    order by storage_safe.storage_idx
";

$storage_sum_arr = array();
$sel_st_sum = mysqli_query($dbcon, $storage_sum_sql) or die(mysqli_error($dbcon));
$sel_st_sum_num = mysqli_num_rows($sel_st_sum);

$st_sum_total = 0;
if ($sel_st_sum_num > 0) {
    while($data_st_sum = mysqli_fetch_assoc($sel_st_sum)) {
        $col_name = "storage_idx_".$data_st_sum['storage_idx'];
        $storage_sum_arr[$col_name] = $data_st_sum;
        $st_sum_total += $data_st_sum['sum_current_count'];
    }
}

// 더존 지사 합계 계산
$branch_sum_total = 0;
foreach($branch_arr as $branch) {
    $storage_column = "storage_idx_".$branch['storage_idx'];
    if(isset($storage_sum_arr[$storage_column])) {
        $branch_sum_total += $storage_sum_arr[$storage_column]['sum_current_count'];
    }
}

// 제품별 재고 합계
$product_sum_sql = "
    select sum(storage_safe.current_count) as sum_current_count, storage_safe.product_idx 
    from storage_safe 
    left join storage on storage_safe.storage_idx = storage.storage_idx
    left join product on storage_safe.product_idx = product.product_idx
    where storage.storage_idx is not null
    and product.product_name NOT LIKE '%test%'
    group by storage_safe.product_idx 
    order by storage_safe.product_idx
";

$product_sum_arr = array();
$sel_pr_sum = mysqli_query($dbcon, $product_sum_sql) or die(mysqli_error($dbcon));
$sel_pr_sum_num = mysqli_num_rows($sel_pr_sum);

if ($sel_pr_sum_num > 0) {
    while($data_pr_sum = mysqli_fetch_assoc($sel_pr_sum)) {
        $col_name = "product_idx_".$data_pr_sum['product_idx'];
        $product_sum_arr[$col_name] = $data_pr_sum;
    }
}

// 창고별+상품별 안전재고 / 현재재고 
$storage_safe_sql = "
    select * from storage_safe order by storage_idx,product_idx
";

$storage_safe_arr = array();
$sel_st_safe = mysqli_query($dbcon, $storage_safe_sql) or die(mysqli_error($dbcon));
$sel_st_safe_num = mysqli_num_rows($sel_st_safe);

if ($sel_st_safe_num > 0) {
    while($data_st_safe = mysqli_fetch_assoc($sel_st_safe)) {
        $vs = $data_st_safe['safe_count'];
        $vc = $data_st_safe['current_count'];
        $storage_column = "storage_idx_".$data_st_safe['storage_idx'];
        $product_column = "product_idx_".$data_st_safe['product_idx'];
        $storage_safe_arr[$storage_column][$product_column] = [$vs,$vc];
    }
}

// 창고별+상품별 미입고내역 합계
$storage_in_wait_sql = "
    select sum(in_count) as sum_in,storage_idx,product_idx 
    from in_out 
    where part='이동입고' and io_status='미입고' 
    group by storage_idx,product_idx
";

$stpr_in_wait_arr = array();
$sel_stpr_in_wait = mysqli_query($dbcon, $storage_in_wait_sql) or die(mysqli_error($dbcon));
$sel_stpr_in_wait_num = mysqli_num_rows($sel_stpr_in_wait);

if ($sel_stpr_in_wait_num > 0) {
    while($data_stpr_in_wait = mysqli_fetch_assoc($sel_stpr_in_wait)) {
        $storage_column = "storage_idx_".$data_stpr_in_wait['storage_idx'];
        $product_column = "product_idx_".$data_stpr_in_wait['product_idx'];
        $stpr_in_wait_arr[$storage_column][$product_column] = $data_stpr_in_wait['sum_in'];
    }
}

// 상품별 미입고내역 합계
$pr_in_wait_sql = "
    select sum(in_count) as sum_in,product_idx 
    from in_out 
    where part='이동입고' and io_status='미입고' 
    group by product_idx
";

$pr_in_wait_arr = array();
$sel_pr_in_wait = mysqli_query($dbcon, $pr_in_wait_sql) or die(mysqli_error($dbcon));
$sel_pr_in_wait_num = mysqli_num_rows($sel_pr_in_wait);

if ($sel_pr_in_wait_num > 0) {
    while($data_pr_in_wait = mysqli_fetch_assoc($sel_pr_in_wait)) {
        $product_column = "product_idx_".$data_pr_in_wait['product_idx'];
        $pr_in_wait_arr[$product_column] = $data_pr_in_wait['sum_in'];
    }
}

// 창고별 미입고내역 합계
$st_in_wait_sql = "
    select sum(in_count) as sum_in,storage_idx 
    from in_out 
    where part='이동입고' and io_status='미입고' 
    group by storage_idx
";

$st_in_wait_arr = array();
$sel_st_in_wait = mysqli_query($dbcon, $st_in_wait_sql) or die(mysqli_error($dbcon));
$sel_st_in_wait_num = mysqli_num_rows($sel_st_in_wait);

if ($sel_st_in_wait_num > 0) {
    while($data_st_in_wait = mysqli_fetch_assoc($sel_st_in_wait)) {
        $storage_column = "storage_idx_".$data_st_in_wait['storage_idx'];
        $st_in_wait_arr[$storage_column] = $data_st_in_wait['sum_in'];
    }
}

$column_name_arr = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ"];

// 엑셀 생성
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// 타이틀 라인
$last_cell_name = "";
$sheet->setCellValue('A1', '품목');
$sheet->setCellValue('B1', '합계');
$sheet->setCellValue('C1', '메모');

$sheet->setCellValue('A2', '지점별 합계');
$sheet->setCellValue('B2', $st_sum_total);

// 본사 데이터 추가 (D열)
if ($headquarters_storage) {
    $storage_column = "storage_idx_".$headquarters_storage['storage_idx'];
    $sheet->setCellValue('D1', '본사');
    $this_hq_sum = $storage_sum_arr[$storage_column]['sum_current_count'];
    if($st_in_wait_arr[$storage_column] > 0) {
        $this_hq_sum .= "(".$st_in_wait_arr[$storage_column].")";
    }
    $sheet->setCellValue('D2', $this_hq_sum);
}

// 더존 지사 합계 (E열)
$sheet->setCellValue('E1', '지사합계(더존)');
$sheet->setCellValue('E2', $branch_sum_total);

// 더존 지사별 데이터 (F열부터)
for ($i=0; $i<count($branch_arr); $i++) {
    $col_num = $i+5; // F열부터 시작
    $last_cell_name = $column_name_arr[$col_num]."1";
    $sheet->setCellValue($last_cell_name, $branch_arr[$i]['storage_name']);

    $storage_column = "storage_idx_".$branch_arr[$i]['storage_idx'];
    $this_br_sum = $storage_sum_arr[$storage_column]['sum_current_count'];
    
    if($st_in_wait_arr[$storage_column] > 0) {
        $this_br_sum .= "(".$st_in_wait_arr[$storage_column].")";
    }
    
    $last_cell_name2 = $column_name_arr[$col_num]."2";
    $sheet->setCellValue($last_cell_name2, $this_br_sum);
}

// 제품별 데이터
$current_group = '';
$row_num = 2;

for ($i=0; $i<count($product_arr); $i++) {
    // 그룹이 변경되고, 새 그룹명이 비어있지 않고, 숫자만 있는 경우가 아닐 때만 그룹 헤더 추가
    if ($current_group != $product_arr[$i]['display_group'] && 
        !empty(trim($product_arr[$i]['display_group'])) && 
        !is_numeric(trim($product_arr[$i]['display_group']))) {
        $current_group = $product_arr[$i]['display_group'];
        $row_num++;
        
        // 그룹 헤더 추가
        $cell_name = "A".$row_num;
        $sheet->setCellValue($cell_name, $current_group);
        
        // 그룹 헤더 스타일 설정
        $last_column = $column_name_arr[count($branch_arr)+4]; // 본사, 지사합계 컬럼 고려
        $range = "A".$row_num.":".$last_column.$row_num;
        $sheet->getStyle($range)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
    } elseif ($current_group != $product_arr[$i]['display_group']) {
        // 그룹이 변경되었지만 헤더를 추가하지 않는 경우 current_group만 업데이트
        $current_group = $product_arr[$i]['display_group'];
    }
    
    $row_num++;
    $cell_name = "A".$row_num;
    
    $sheet->setCellValue($cell_name, $product_arr[$i]['product_name']);
    
    $product_column = "product_idx_".$product_arr[$i]['product_idx'];
    $this_pr_sum = isset($product_sum_arr[$product_column]) ? $product_sum_arr[$product_column]['sum_current_count'] : 0;
    
    if(isset($pr_in_wait_arr[$product_column]) && $pr_in_wait_arr[$product_column] > 0) {
        $this_pr_sum .= "(".$pr_in_wait_arr[$product_column].")";
    }
    
    $sheet->setCellValue("B".$row_num, $this_pr_sum);
    $sheet->setCellValue("C".$row_num, $product_arr[$i]['memo']);
    
    // 본사 데이터
    if ($headquarters_storage) {
        $storage_column = "storage_idx_".$headquarters_storage['storage_idx'];
        $cell_value = "";
        
        if(isset($storage_safe_arr[$storage_column][$product_column])) {
            $cell_value = $storage_safe_arr[$storage_column][$product_column][1];
            
            if(isset($stpr_in_wait_arr[$storage_column][$product_column]) && $stpr_in_wait_arr[$storage_column][$product_column] > 0) {
                $cell_value .= "(".$stpr_in_wait_arr[$storage_column][$product_column].")";
            }
        }
        $sheet->setCellValue("D".$row_num, $cell_value);
    }
    
    // 더존 지사 합계 계산
    $branch_product_sum = 0;
    foreach($branch_arr as $branch) {
        $storage_column = "storage_idx_".$branch['storage_idx'];
        if(isset($storage_safe_arr[$storage_column][$product_column])) {
            $branch_product_sum += $storage_safe_arr[$storage_column][$product_column][1];
        }
        if(isset($stpr_in_wait_arr[$storage_column][$product_column])) {
            $branch_product_sum += $stpr_in_wait_arr[$storage_column][$product_column];
        }
    }
    $sheet->setCellValue("E".$row_num, $branch_product_sum);
    
    // 더존 지사별 데이터
    for ($k=0; $k<count($branch_arr); $k++) {
        $storage_column = "storage_idx_".$branch_arr[$k]['storage_idx'];
        $cell_value = "";
        
        if(isset($storage_safe_arr[$storage_column][$product_column])) {
            $cell_value = $storage_safe_arr[$storage_column][$product_column][1];
            
            if(isset($stpr_in_wait_arr[$storage_column][$product_column]) && $stpr_in_wait_arr[$storage_column][$product_column] > 0) {
                $cell_value .= "(".$stpr_in_wait_arr[$storage_column][$product_column].")";
            }
        }
        
        $col_num = $k+5; // F열부터 시작
        $sheet->setCellValue($column_name_arr[$col_num].$row_num, $cell_value);
    }
}

// 스프레드시트 스타일 적용
$spreadsheet = setSpreadSheetStyle($spreadsheet, $sheet, $last_cell_name, count($product_arr)+2);

// 지점별 컬럼 너비 설정
setExtraColumnWidths($sheet, 3, count($branch_arr));

$spreadsheet->getActiveSheet()->freezePane('B2');

// 엑셀 파일 저장 및 출력
outputSpreadsheetToExcel($spreadsheet, $filename);
