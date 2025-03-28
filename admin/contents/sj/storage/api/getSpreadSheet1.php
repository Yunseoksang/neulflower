<?
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
//session_start();
//require_once('../../lib/lib_api.php');
//admin_check();


require_once $_SERVER["DOCUMENT_ROOT"].'/lib/phpoffice_phpspreadsheet_1.10.1.0_require/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



//$writeday = date("Y-m-d H:i:s",time());
$filename = date("재고현황_Ymd_His",time()).".xlsx";








$storage_arr = array();
$sel_storage = mysqli_query($dbcon, "select storage_idx,storage_name from storage order by display_order desc,storage_name ") or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);

if($sel_storage_num > 0){
    while($data_storage = mysqli_fetch_assoc($sel_storage)) {
        array_push($storage_arr,$data_storage);
    }
}





$product_arr = array();
$sel = mysqli_query($dbcon, "select product_idx,product_name,memo from product order by product_name ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);
$total_cnt = $sel_num;

if ($sel_num > 0) {
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_arr,$data);
    }
}





//창고별 재고 합계
// $storage_sum_sql = "
//     select sum(current_count) as sum_current_count,storage_idx,t_storage_name from (
//     select * from in_out where io_idx in (select max(io_idx) as max_io_idx from in_out where in_count != 100 group by storage_idx, product_idx)  
//     ) x group by storage_idx
//     "
// ;


$storage_sum_sql = "
    select sum(current_count) as sum_current_count,storage_idx,t_storage_name from storage_safe group by storage_idx order by product_idx
    "
;

$storage_sum_arr = array();
$sel_st_sum = mysqli_query($dbcon, $storage_sum_sql) or die(mysqli_error($dbcon));
$sel_st_sum_num = mysqli_num_rows($sel_st_sum);

$st_sum_total = 0;
if ($sel_st_sum_num > 0) {
    while($data_st_sum = mysqli_fetch_assoc($sel_st_sum)) {
        array_push($storage_sum_arr,$data_st_sum);
        $st_sum_total += $data_st_sum['sum_current_count'];
    }
}



$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
//$sheet->setCellValue('A1', 'Hello World !');



//$column_name_arr = ["A1","B1","C1","D1","E1","F1","G1","H1","I1","J1","K1","L1","M1","N1","O1","P1","Q1","R1","S1","T1","U1","V1","W1","X1","Y1","Z1","AA1","AB1","AC1","AD1","AE1","AF1","AG1","AH1","AI1","AJ1","AK1","AL1","AM1","AN1","AO1","AP1","AQ1","AR1","AS1","AT1","AU1","AV1","AW1","AX1","AY1","AZ1","BA1","BB1","BC1","BD1","BE1","BF1","BG1","BH1","BI1","BJ1","BK1","BL1","BM1","BN1","BO1","BP1","BQ1","BR1","BS1","BT1","BU1","BV1","BW1","BX1","BY1","BZ1","CA1","CB1","CC1","CD1","CE1","CF1","CG1","CH1","CI1","CJ1","CK1","CL1","CM1","CN1","CO1","CP1","CQ1","CR1","CS1","CT1","CU1","CV1","CW1","CX1","CY1","CZ1"];
$column_name_arr = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ","CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ"];



/*
$a_array = ["A1","B1","C1","D1","E1","F1","G1","H1","I1","J1","K1","L1","M1","N1","O1","P1","Q1","R1","S1","T1","U1","V1","W1","X1","Y1","Z1"];
$b_array = ["AA1","AB1","AC1","AD1","AE1","AF1","AG1","AH1","AI1","AJ1","AK1","AL1","AM1","AN1","AO1","AP1","AQ1","AR1","AS1","AT1","AU1","AV1","AW1","AX1","AY1","AZ1"];
$c_array = ["BA1","BB1","BC1","BD1","BE1","BF1","BG1","BH1","BI1","BJ1","BK1","BL1","BM1","BN1","BO1","BP1","BQ1","BR1","BS1","BT1","BU1","BV1","BW1","BX1","BY1","BZ1"];
$d_array = ["CA1","CB1","CC1","CD1","CE1","CF1","CG1","CH1","CI1","CJ1","CK1","CL1","CM1","CN1","CO1","CP1","CQ1","CR1","CS1","CT1","CU1","CV1","CW1","CX1","CY1","CZ1"];
*/


//타이틀 라인

$last_cell_name = "";
$sheet->setCellValue('A1', '품목');
$sheet->setCellValue('B1', '합계');
$sheet->setCellValue('C1', '메모');

$sheet->setCellValue('A2', '창고별 합계');
$sheet->setCellValue('B2', $st_sum_total);

for ($i=0;$i<count($storage_arr);$i++ )
{
   $col_num = $i+3;
   $last_cell_name = $column_name_arr[$col_num]."1";
   $sheet->setCellValue($last_cell_name , $storage_arr[$i]['storage_name']);

   $this_st_sum = 0;
   for($j=0; $j<count($storage_sum_arr);$j++){
     if($storage_sum_arr[$j]['storage_idx'] == $storage_arr[$i]['storage_idx']){
       $this_st_sum = $storage_sum_arr[$j]['sum_current_count'];
     }
   }

   $last_cell_name2 = $column_name_arr[$col_num]."2";
   $sheet->setCellValue($last_cell_name2 , $this_st_sum);

}



for ($i=0;$i<count($product_arr);$i++ )
{
    $row_num = $i+3;
    $cell_name = "A".$row_num; 

   $sheet->setCellValue($cell_name, $product_arr[$i]['product_name']);

   $pr_sum = 0;
   $pr_wait_sum = 0;
   $pr_wait_sum_w = "";

   for ($k=0;$k<count($storage_arr);$k++ )
   {

      $sel_io = mysqli_query($dbcon, "select current_count from storage_safe where storage_idx='".$storage_arr[$k]['storage_idx']."' and product_idx='".$product_arr[$i]['product_idx']."'  ") or die(mysqli_error($dbcon));
      $sel_io_num = mysqli_num_rows($sel_io);

        if ($sel_io_num > 0) {
            $data_io = mysqli_fetch_assoc($sel_io);

            $cell_value = $data_io['current_count'];
            $pr_sum = $pr_sum + $data_io['current_count'];
        }else{
            //array_push($storage_cnt_arr,"");
            $cell_value = "";
        }


        $sel_io2 = mysqli_query($dbcon, "select sum(in_count) as sum_in from in_out where storage_idx='".$storage_arr[$k]['storage_idx']."' and product_idx='".$product_arr[$i]['product_idx']."' 
        and part='이동입고' and io_status='미입고'
        
        ") or die(mysqli_error($dbcon));
        $sel_io_num = mysqli_num_rows($sel_io2);
        if($sel_io_num > 0){
            $data_io2 = mysqli_fetch_assoc($sel_io2);

            if($data_io2['sum_in'] > 0){
                $cell_value .= "(".$data_io2['sum_in'].")";

            }

            $pr_wait_sum = $pr_wait_sum + $data_io2['sum_in'];
        }


        $col_num = $k+3;
        $sheet->setCellValue($column_name_arr[$col_num].$row_num, $cell_value);
   }


    if($pr_wait_sum > 0){
        $pr_sum = $pr_sum."(".$pr_wait_sum.")";
    }
   $sheet->setCellValue("B".$row_num, $pr_sum);  //품목별 합계

   $sheet->setCellValue("C".$row_num, $product_arr[$i]['memo']); //품목별 메모





}

$spreadsheet->getActiveSheet()->freezePane('B2');

$spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);

$spreadsheet->getActiveSheet()->getStyle('A1:'.$last_cell_name)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('00efefef');


$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(50, 'px');

$spreadsheet->getDefaultStyle()->getFont()->setSize(10);




try {
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
    $content = file_get_contents($filename);
} catch(Exception $e) {
    exit($e->getMessage());
}

header("Content-Disposition: attachment; filename=".$filename);

unlink($filename);
exit($content);