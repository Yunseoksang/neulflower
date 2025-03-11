<?
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect_sangjo_new.php'; //DB 접속
//session_start();
//require_once('../../lib/lib_api.php');
//admin_check();

/** Include PHPExcel */
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/PHPExcel-1.8/Classes/PHPExcel.php';

//$writeday = date("Y-m-d H:i:s",time());
$filename = date("재고현황_Ymd_His",time());


header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = ".$filename.".xls" );









$storage_arr = array();
$sel_storage = mysqli_query($dbcon, "select storage_idx,storage_name from storage order by storage_name ") or die(mysqli_error($dbcon));
$sel_storage_num = mysqli_num_rows($sel_storage);

if($sel_storage_num > 0){
    while($data_storage = mysqli_fetch_assoc($sel_storage)) {
        array_push($storage_arr,$data_storage);
    }
}





$product_arr = array();
$sel = mysqli_query($dbcon, "select product_idx,product_name from product order by product_name ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);
$total_cnt = $sel_num;

if ($sel_num > 0) {
    while($data = mysqli_fetch_assoc($sel)) {
        array_push($product_arr,$data);
    }
}





//창고별 재고 합계
$storage_sum_sql = "
    select sum(current_count) as sum_current_count,storage_idx,t_storage_name from (
    select * from in_out where io_idx in (select max(io_idx) as max_io_idx from in_out group by storage_idx, product_idx)  
    ) x group by storage_idx
    "
;







// Create new PHPExcel object
$objPHPExcel = new PHPExcel();



$column_name_arr = ["A1","B1","C1","D1","E1","F1","G1","H1","I1","J1","K1","L1","M1","N1","O1","P1","Q1","R1","S1","T1","U1","V1","W1","X1","Y1","Z1","AA1","AB1","AC1","AD1","AE1","AF1","AG1","AH1","AI1","AJ1","AK1","AL1","AM1","AN1","AO1","AP1","AQ1","AR1","AS1","AT1","AU1","AV1","AW1","AX1","AY1","AZ1","BA1","BB1","BC1","BD1","BE1","BF1","BG1","BH1","BI1","BJ1","BK1","BL1","BM1","BN1","BO1","BP1","BQ1","BR1","BS1","BT1","BU1","BV1","BW1","BX1","BY1","BZ1","CA1","CB1","CC1","CD1","CE1","CF1","CG1","CH1","CI1","CJ1","CK1","CL1","CM1","CN1","CO1","CP1","CQ1","CR1","CS1","CT1","CU1","CV1","CW1","CX1","CY1","CZ1"];

/*
$a_array = ["A1","B1","C1","D1","E1","F1","G1","H1","I1","J1","K1","L1","M1","N1","O1","P1","Q1","R1","S1","T1","U1","V1","W1","X1","Y1","Z1"];
$b_array = ["AA1","AB1","AC1","AD1","AE1","AF1","AG1","AH1","AI1","AJ1","AK1","AL1","AM1","AN1","AO1","AP1","AQ1","AR1","AS1","AT1","AU1","AV1","AW1","AX1","AY1","AZ1"];
$c_array = ["BA1","BB1","BC1","BD1","BE1","BF1","BG1","BH1","BI1","BJ1","BK1","BL1","BM1","BN1","BO1","BP1","BQ1","BR1","BS1","BT1","BU1","BV1","BW1","BX1","BY1","BZ1"];
$d_array = ["CA1","CB1","CC1","CD1","CE1","CF1","CG1","CH1","CI1","CJ1","CK1","CL1","CM1","CN1","CO1","CP1","CQ1","CR1","CS1","CT1","CU1","CV1","CW1","CX1","CY1","CZ1"];
*/


//타이틀 라인
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '품목');
for ($i=0;$i<count($storage_arr);$i++ )
{
   $col_num = $i+1;
   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column_name_arr[$col_num], $storage_arr['storage_name']);
}



for ($i=0;$i<count($product_arr);$i++ )
{
   $col_num = $i+1;

   for ($k=0;$k<count($storage_arr);$k++ )
   {



      $sel_io = mysqli_query($dbcon, "select current_count from in_out where storage_idx='".$storage_arr[$k]['storage_idx']."' and product_idx='".$product_arr[$i]['product_idx']."' order by io_idx desc limit 1 ") or die(mysqli_error($dbcon));
      $sel_io_num = mysqli_num_rows($sel_io);
      if ($sel_io_num > 0) {
         $data_io = mysqli_fetch_assoc($sel_io);
         
         if($data_io['current_count'] == 0){
            
            $cell_value = "-";
         }else{
            $cell_value = $data_io['current_count'];
         }
      }else{
         $cell_value = "-";
      }

      $col_num = $k+1;
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col_num, $cell_value);
   }

}





 
//echo "<meta content=\"application/vnd.ms-excel; charset=UTF-8\" name=\"Content-type\"> ";
$filename = date("재고현황_Ymd_His",time());
$this_file_name = $filename.".xls";

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');  //=>xls 저장.  //header('Content-Type: application/xlsx');
header('Content-Disposition: attachment;filename="'.$this_file_name.'"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');  //자동 다운로드.


?>