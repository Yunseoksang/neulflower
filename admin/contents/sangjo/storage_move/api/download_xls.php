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


if($_REQUEST['mode'] == "moveout"){
    $filename = date("이동출고내역_Ymd_His",time());

    $filename = "이동출고내역_".$todate;


    $result = mysqli_query($dbcon, "

   select a.*,b.manager,b.hp,b.address 
   
   from (

    select io_idx,
    date_format(regist_datetime,'%Y-%m-%d') as ymd,
    t_storage_name as from_storage,
    to_storage_idx,
    t_to_storage_name as to_storage, 
    
    t_product_name,
    out_count,
    memo,
    io_status
    
    FROM in_out where part='이동출고' and io_status='미출고' ORDER BY io_idx desc) a
    
    
    left join storage b 
    on a.to_storage_idx=b.storage_idx 

    order by io_idx desc
    ") or die(mysqli_error($dbcon));

    $sel_num = mysqli_num_rows($result);




    header( "Content-type: application/vnd.ms-excel; charset=utf-8");
    header( "Content-Disposition: attachment; filename = ".$filename.".xls" );
    
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    
    cellColor('A1:K1', 'FFAF00');   //타이틀 라인 배경색
    
    
    // //셀너비
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("15");
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("18");
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("18");
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("20");
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth("20");
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("90");
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth("30");
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth("10");
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth("20");
    
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth("18");
    
    
    
    // 테이블 상단 만들기
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '고유번호')
        ->setCellValue('B1', '요청일자')
        ->setCellValue('C1', '출발지창고')
        ->setCellValue('D1', '도착지창고')
        ->setCellValue('E1', '도착지관리자')
        ->setCellValue('F1', '전화번호')
        ->setCellValue('G1', '주소')
        ->setCellValue('H1', '품목')
        ->setCellValue('I1', '이동수량')
        ->setCellValue('J1', '메모')
        ->setCellValue('K1', '처리상태')
        ;
    
    
    $num = 1;
    while($row = mysqli_fetch_array($result)) {
        $num++;
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$num, $row['io_idx'])
        ->setCellValue('B'.$num, $row['ymd'])
        ->setCellValue('C'.$num, $row['from_storage'])
        ->setCellValue('D'.$num, $row['to_storage'])
        ->setCellValue('E'.$num, $row['manager'])
        ->setCellValue('F'.$num, $row['hp'])
        ->setCellValue('G'.$num, $row['address'])
        ->setCellValue('H'.$num, $row['t_product_name'])
        ->setCellValue('I'.$num, $row['out_count'])
        ->setCellValue('J'.$num, $row['memo'])
        ->setCellValue('K'.$num, $row['io_status'])

        ;

    }
            
    


    
}else if($_REQUEST['mode'] == "movein"){
    $filename = date("이동입고내역_Ymd_His",time());

    $filename = "이동입고내역_".$todate;


    $result = mysqli_query($dbcon, "

    select io_idx,
    date_format(regist_datetime,'%Y-%m-%d') as ymd,
    t_from_storage_name as from_storage,
    t_storage_name as to_storage, 

    t_product_name,
    in_count,

    out_date,
    receive_date,

    memo,
    io_status
    
    FROM in_out where part='이동입고' and io_status='미입고' ORDER BY io_idx desc ") or die(mysqli_error($dbcon));

    $sel_num = mysqli_num_rows($result);



    header( "Content-type: application/vnd.ms-excel; charset=utf-8");
    header( "Content-Disposition: attachment; filename = ".$filename.".xls" );
    
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    
    cellColor('A1:J1', 'FFAF00');   //타이틀 라인 배경색

    //셀너비
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth("15");
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth("18");
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth("18");
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth("45");
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth("18");
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth("18");
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth("18");
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth("18");

    
    

    // 테이블 상단 만들기
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', '고유번호')
        ->setCellValue('B1', '요청일자')
        ->setCellValue('C1', '출발지창고')
        ->setCellValue('D1', '도착지창고')
        ->setCellValue('E1', '품목')
        ->setCellValue('F1', '이동수량')
        ->setCellValue('G1', '발송일자')
        ->setCellValue('H1', '수령일자')
        ->setCellValue('I1', '메모')
        ->setCellValue('J1', '처리상태')
        ;


    $num = 1;
    while($row = mysqli_fetch_array($result)) {
        $num++;
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$num, $row['io_idx'])
        ->setCellValue('B'.$num, $row['ymd'])
        ->setCellValue('C'.$num, $row['from_storage'])
        ->setCellValue('D'.$num, $row['to_storage'])
        ->setCellValue('E'.$num, $row['t_product_name'])
        ->setCellValue('F'.$num, $row['in_count'])
        ->setCellValue('G'.$num, $row['out_date'])
        ->setCellValue('H'.$num, $row['receive_date'])
        ->setCellValue('I'.$num, $row['memo'])
        ->setCellValue('J'.$num, $row['io_status'])
        ;
    }
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