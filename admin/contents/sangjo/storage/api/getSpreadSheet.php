<?
// 에러 리포팅 설정
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set("display_errors", 0);

// 출력 버퍼링 시작
ob_start();

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/phpoffice_phpspreadsheet/vendor/autoload.php';
require_once 'common_spreadsheet.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$filename = date("재고현황_Ymd_His",time()).".xlsx";

// 창고 목록
$storage_arr = array();
$sel_storage = mysqli_query($dbcon, "select storage_idx,storage_name from storage order by display_order desc,storage_name ") or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);

if($sel_storage_num > 0){
    while($data_storage = mysqli_fetch_assoc($sel_storage)) {
        array_push($storage_arr,$data_storage);
    }
}

// 제품 목록
$product_arr = array();
$sel = mysqli_query($dbcon, "select product_idx,product_name,display_group,memo from product where product_name NOT LIKE '%test%' order by product_name") or die(mysqli_error($dbcon));
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

// 엑셀 생성
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$column_name_arr = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ","CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ"];

// 타이틀 라인
$last_cell_name = "";
$sheet->setCellValue('A1', '품목');
$sheet->setCellValue('B1', '합계');
$sheet->setCellValue('C1', '메모');

$sheet->setCellValue('A2', '창고별 합계');
$sheet->setCellValue('B2', $st_sum_total);

for ($i=0; $i<count($storage_arr); $i++) {
    $col_num = $i+3;
    $last_cell_name = $column_name_arr[$col_num]."1";
    $sheet->setCellValue($last_cell_name, $storage_arr[$i]['storage_name']);

    $storage_column = "storage_idx_".$storage_arr[$i]['storage_idx'];
    $this_st_sum = $storage_sum_arr[$storage_column]['sum_current_count'];
    
    if($st_in_wait_arr[$storage_column] > 0) {
        $this_st_sum .= "(".$st_in_wait_arr[$storage_column].")";
    }
    
    $last_cell_name2 = $column_name_arr[$col_num]."2";
    $sheet->setCellValue($last_cell_name2, $this_st_sum);
}

// 제품별 데이터
for ($i=0; $i<count($product_arr); $i++) {
    $row_num = $i+3;
    $cell_name = "A".$row_num;
    
    $sheet->setCellValue($cell_name, $product_arr[$i]['product_name']);
    
    $product_column = "product_idx_".$product_arr[$i]['product_idx'];
    $this_pr_sum = isset($product_sum_arr[$product_column]) ? $product_sum_arr[$product_column]['sum_current_count'] : 0;
    
    if(isset($pr_in_wait_arr[$product_column]) && $pr_in_wait_arr[$product_column] > 0) {
        $this_pr_sum .= "(".$pr_in_wait_arr[$product_column].")";
    }
    
    $sheet->setCellValue("B".$row_num, $this_pr_sum);
    $sheet->setCellValue("C".$row_num, $product_arr[$i]['memo']);
    
    for ($k=0; $k<count($storage_arr); $k++) {
        $storage_column = "storage_idx_".$storage_arr[$k]['storage_idx'];
        $cell_value = "";
        
        if(isset($storage_safe_arr[$storage_column][$product_column])) {
            $cell_value = $storage_safe_arr[$storage_column][$product_column][1];
            
            if(isset($stpr_in_wait_arr[$storage_column][$product_column]) && $stpr_in_wait_arr[$storage_column][$product_column] > 0) {
                $cell_value .= "(".$stpr_in_wait_arr[$storage_column][$product_column].")";
            }
        }
        
        $col_num = $k+3;
        $sheet->setCellValue($column_name_arr[$col_num].$row_num, $cell_value);
    }
}

// 스프레드시트 스타일 적용
$spreadsheet = setSpreadSheetStyle($spreadsheet, $sheet, $last_cell_name, count($product_arr)+2);

// 창고별 컬럼 너비 설정
setExtraColumnWidths($sheet, 3, count($storage_arr));

$spreadsheet->getActiveSheet()->freezePane('B2');

// 엑셀 파일 저장 및 출력
outputSpreadsheetToExcel($spreadsheet, $filename); 