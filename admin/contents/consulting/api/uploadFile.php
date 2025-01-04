<?
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/DB_Connect.php'; //DB 접속
require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//session_start();
admin_check_ajax();


if($_POST['consulting_idx'] == ""){
    $result = array();
    $result['status'] = 0;
    $result['msg'] = "ERROR: 고유번호누락";

    echo json_encode($result);
    exit;
}


// if($_FILES['attachFile']['name'] != ''){
//     $test = explode('.', $_FILES['attachFile']['name']);
//     $extension = end($test);    
//     $name = rand(100,999).'.'.$extension;

//     $location = $_SERVER["DOCUMENT_ROOT"].'/upload/consulting/'.$name;
//     move_uploaded_file($_FILES['attachFile']['tmp_name'], $location);

//     echo '<img src="'.$location.'" height="100" width="100" />';
// }

//  echo "### POST ###";
//  echo "<pre>";
//  print_r($_POST);
//  echo "</pre>";

//  echo "### FILE ###";
//  echo "<pre>";
//  print_r($_FILES);
//  echo "</pre>";



// exit;

//$path = $_SERVER["DOCUMENT_ROOT"]."/upload/consulting/";
 
//$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");

if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
{
    $name = $_FILES['attachFile']['name'];
    $size = $_FILES['attachFile']['size'];
     
     
    if(strlen($name))
    {       


        $folder_idx = "/upload/consulting/".$_POST['consulting_idx'];
        $absolute_folder_idx = $_SERVER["DOCUMENT_ROOT"]. $folder_idx;

        if (!file_exists($absolute_folder_idx)) {
            mkdir($absolute_folder_idx, 0777, true);
        }



        $local_name = $folder_idx."/".$name;
        $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;

        //중복파일 존재하면
		if(file_exists($absolute_path)){ 
            list($txt, $ext) = explode(".", $name);
            $new_name = $txt."_".time().".".$ext;
            $local_name = "/upload/consulting/".$_POST['consulting_idx']."/".$new_name;
            $absolute_path = $_SERVER["DOCUMENT_ROOT"].$local_name;
        }


        $tmp = $_FILES['attachFile']['tmp_name'];
        //파일복사
        if(move_uploaded_file($tmp, $absolute_path))
        {

            $in = mysqli_query($dbcon, "insert into attachment 
            set
            consulting_idx='".$_POST['consulting_idx']."',
            part='".$_POST['part']."',

            filename='".mysqli_real_escape_string($dbcon, $local_name )."',
            admin_idx='".$admin_info['admin_idx']."',
            admin_name='".$admin_info['admin_name']."'

            ") or die(mysqli_error($dbcon));
            $in_id = mysqli_insert_id($dbcon);
            if($in_id){//쿼리성공


                $sel = mysqli_query($dbcon, "select * from attachment where attachment_idx='".$in_id."' ") or die(mysqli_error($dbcon));
                $sel_num = mysqli_num_rows($sel);
                
                if ($sel_num > 0) {
                    $data = mysqli_fetch_assoc($sel);
                    //2023.5.25. 배은송님 요청으로 첨부파일 등록시 업데이트 날짜적용
                    $up = mysqli_query($dbcon, "update consulting.consulting set update_datetime=now() where consulting_idx='".$_POST['consulting_idx']."' ") or die(mysqli_error($dbcon));

                }

                $result = array();
                $result['status'] = 1;
                $result['data']=$data ;
                $result['msg'] = "저장되었습니다";
                echo json_encode($result);
                exit;
            }else{//쿼리실패
                $result = array();
                $result['status'] = 0;
                $result['msg'] = "DB입력실패";
        
                echo json_encode($result);
                exit;
            }
            
           


        }
        else
        {
            $result = array();
            $result['status'] = 0;
            $result['msg'] = "저장실패";
    
            echo json_encode($result);
            exit;
        }
                
    }
    else
        $result = array();
        $result['status'] = 0;
        $result['msg'] = "첨부파일을 선택해주세요";

        echo json_encode($result);
        exit;

}
 
 
?>