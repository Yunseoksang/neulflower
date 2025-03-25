<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

function setSpreadSheetStyle($spreadsheet, $sheet, $last_cell_name, $total_rows) {
    // 기본 행 높이 설정
    $sheet->getDefaultRowDimension()->setRowHeight(20);
    
    // 첫 번째 행 높이 설정
    $sheet->getRowDimension(1)->setRowHeight(50);
    
    // 컬럼 너비 설정
    $sheet->getColumnDimension('A')->setWidth(30); // 품목 컬럼은 고정 너비
    
    // 합계와 메모 컬럼
    $sheet->getColumnDimension('B')->setWidth(13);
    $sheet->getColumnDimension('C')->setWidth(13);
    
    // 모든 행의 높이를 명시적으로 설정
    for ($i = 1; $i <= $total_rows; $i++) {
        if ($i == 1) {
            $sheet->getRowDimension($i)->setRowHeight(50);
        } else {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }
    }
    
    // 헤더 스타일 설정
    $spreadsheet->getActiveSheet()->getStyle('A1:'.$last_cell_name)->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('00efefef');
    
    // 모든 셀의 정렬 설정
    $spreadsheet->getActiveSheet()->getStyle('A1:'.$last_cell_name)->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER)
        ->setWrapText(true);
    
    // A열은 왼쪽 정렬
    $spreadsheet->getActiveSheet()->getStyle('A1:A'.$total_rows)->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_LEFT)
        ->setWrapText(true);
    
    // 기본 폰트 크기를 14로 설정
    $spreadsheet->getDefaultStyle()->getFont()->setSize(14);
    
    return $spreadsheet;
}

// 엑셀 파일 저장 및 출력 함수
function outputSpreadsheetToExcel($spreadsheet, $filename) {
    try {
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        $content = file_get_contents($filename);
        
        // 출력 전에 버퍼 초기화
        ob_clean();
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        
        unlink($filename);
        exit($content);
    } catch(Exception $e) {
        exit($e->getMessage());
    }
}

// 컬럼 너비 설정 함수
function setExtraColumnWidths($sheet, $start_col, $count) {
    global $column_name_arr;
    for ($i=0; $i<$count; $i++) {
        $col_num = $i+$start_col;
        $column_letter = $column_name_arr[$col_num];
        $sheet->getColumnDimension($column_letter)->setWidth(13);
    }
}    