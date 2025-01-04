<?	 
    error_reporting(E_ALL); 
    ini_set("display_errors", 1);
    ini_set('memory_limit', -1); // 메모리 제한을 해제해준다. 


    use PhpOffice\PhpSpreadsheet\IOFactory;  
    
    require_once(__DIR__.'/PhpOffice/Psr/autoloader.php');
    require_once(__DIR__.'/PhpOffice/PhpSpreadsheet/autoloader.php');
  
    // 파일명 
    $inputFileName = __DIR__ . '/sample.xlsx';
 
    $spreadsheet = IOFactory::load($inputFileName);

    $Rows = $spreadsheet->getSheetByName('재고')->toArray(null, true, true, true);
   
?>
<table border=1>
<? 
     foreach($Rows as $row){
?>
    <tr>
        <? foreach($row as $col){ ?> 
            <td> <?=$col?></td>
        <? } ?> 
    
    </tr>
    
<? 
         
     }
    
?>
    
</table>