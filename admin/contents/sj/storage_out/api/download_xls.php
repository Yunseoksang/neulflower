<?
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/lib.php'; //DB 접속

//session_start();
//require_once('../../lib/lib_api.php');
//admin_check();


function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}





/** Include PHPExcel */
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/PHPExcel-1.8/Classes/PHPExcel.php';

//$writeday = date("Y-m-d H:i:s",time());
//$filename = date("출고내역_Ymd_His",time());


$todate = date("Ymd_His",time());

if($_REQUEST['mode'] == "list"){

    $filename = "출고내역_".$todate;
    $where_sql = " part='출고' and io_status='출고완료' ";

}else if($_REQUEST['mode'] == "view"){
    $filename = "출고지시서목록_".$todate;

    $where_sql = "part='출고' and io_status='미출고' ";


}




header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = ".$filename.".xls" );


$result = mysqli_query($dbcon, "

select a.* ,
out_order.out_order_idx,
out_order.storage_idx,
out_order.t_storage_name,
out_order.to_place_name,
out_order.to_address,
out_order.to_name,
out_order.to_hp,
out_order.to_phone,
out_order.receiver_name 

from (select * FROM in_out where 1=1 AND ".$where_sql." ORDER BY io_idx desc ) a left join out_order on a.out_order_idx=out_order.out_order_idx where 1=1  order by io_idx desc") or die(mysqli_error($dbcon));

$sel_num = mysqli_num_rows($result);


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();


/*


cellColor('A1:L1', 'FFAF00');   //타이틀 라인 배경색


// //셀너비
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("10");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("18");

$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("50");

$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("18");

$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth("18");


$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth("50");



// 테이블 상단 만들기
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', '고유번호')
    ->setCellValue('B1', '품목')
    ->setCellValue('C1', '수량')
    ->setCellValue('D1', '출고창고')
    ->setCellValue('E1', '배송지')
    ->setCellValue('F1', '주소')
    ->setCellValue('G1', '받는분')
    ->setCellValue('H1', '휴대폰')
    ->setCellValue('I1', '인수자')
    ->setCellValue('J1', '출고일')
    ->setCellValue('K1', '메모')
    ->setCellValue('L1', '등록일')





    ->setCellValue('L1', '출고상태')
    ;


$num = 1;
while($row = mysqli_fetch_array($result)) {
    $num++;
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$num, $row['io_idx'])
    ->setCellValue('B'.$num, $row['t_product_name'])
    ->setCellValue('C'.$num, $row['out_count'])
    ->setCellValue('D'.$num, $row['t_storage_name'])
    ->setCellValue('E'.$num, $row['to_place_name'])
    ->setCellValue('F'.$num, $row['to_address'])
    ->setCellValue('G'.$num, $row['to_name'])
    ->setCellValue('H'.$num, $row['to_hp'])
    ->setCellValue('I'.$num, $row['receiver_name'])
    ->setCellValue('J'.$num, $row['out_date'])
    ->setCellValue('K'.$num, $row['admin_memo'])
    ->setCellValue('L'.$num, $row['regist_datetime'])

    ;

}
    

*/






cellColor('A1:J1', 'FFAF00');   //타이틀 라인 배경색


// //셀너비
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("18");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("20");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("80");

$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("10");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("10");

$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("10");

$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth("30");
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth("18");


// 테이블 상단 만들기
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', '고유번호')
->setCellValue('B1', '받는분')
->setCellValue('C1', '휴대폰')
->setCellValue('D1', '주소')
->setCellValue('E1', '수량')

->setCellValue('F1', '')
->setCellValue('G1', '')
->setCellValue('H1', '품목')
->setCellValue('I1', '고객사')

//->setCellValue('G1', '배송지')
//->setCellValue('J1', '일반전화')
// ->setCellValue('L1', '인수자')
// ->setCellValue('M1', '출고일')
->setCellValue('J1', '메모')
;


$num = 1;
while($row = mysqli_fetch_array($result)) {
    $num++;
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$num, $row['io_idx'])
    ->setCellValue('B'.$num, $row['to_name'])
    ->setCellValue('C'.$num, $row['to_hp'])
    
    ->setCellValue('D'.$num, $row['to_address'])
    ->setCellValue('E'.$num, $row['out_count'])
    
    ->setCellValue('F'.$num, '')
    ->setCellValue('G'.$num, '')
    
    ->setCellValue('H'.$num, $row['t_product_name'])
    ->setCellValue('I'.$num, $row['company_name'])
    
    ->setCellValue('J'.$num, $row['admin_memo'])
    ;

}
    




 
//echo "<meta content=\"application/vnd.ms-excel; charset=UTF-8\" name=\"Content-type\"> ";
//$filename = date("출고내역_Ymd_His",time());
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