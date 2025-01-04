<?php 

require($_SERVER["DOCUMENT_ROOT"].'/lib/lib.php');
//api_member_check();

function secure_random_string($length) {
    $rand_string = '';
    for($i = 0; $i < $length; $i++) {
        $number = random_int(0, 36);
        $character = base_convert($number, 10, 36);
        $rand_string .= $character;
    }
 
    return $rand_string;
}


$tmpfname = secure_random_string(10).uniqid();

//$tmpfname = tempnam('/tmp', 'FOO'); // 고유한파일명 생성


$inputName = $_POST['inputName'];  //업로드 화면의 file input box 의 name값을 파라메터로 넘겨받음
$directory = $_POST['directory'];  //업로드 화면의 file input box 의 name값을 파라메터로 넘겨받음


/*********************************************
* 넘어오는 데이터가 정상인지 검사하기 위한 절차
* 실제 페이지에서는 적용 X
**********************************************/

//$_FILES에 담긴 배열 정보 구하기.
// print_r ($_FILES['files']['type']);

// echo $tmpfname;
// exit;
// php 내부 소스에서 html 태그 적용 - 선긋기
///echo "<hr>";

/*********************************************
* 실제로 구축되는 페이지 내부.
**********************************************/

// 임시로 저장된 정보(tmp_name)
$tempFile = $_FILES['files']['tmp_name'];




// 파일타입 및 확장자 체크
$fileTypeExt = explode("/", $_FILES['files']['type']);


// var_dump($_FILES['files']);
// // echo $fileTypeExt[0];
// // echo $fileTypeExt[1];
// exit;

// 파일 타입 
$fileType = $fileTypeExt[0];

// 파일 확장자
$fileExt = $fileTypeExt[1];

// echo $fileExt;
// exit;

// 확장자 검사
$extStatus = false;

switch($fileExt){
	case 'jpeg':
	case 'jpg':
	case 'gif':
	case 'bmp':
	case 'png':
		$extStatus = true;
		break;
	
	default:

        $result = array();
        $result['status'] = 0;
        $result['msg']="이미지 전용 확장자(jpg, bmp, gif, png)외에는 사용이 불가합니다.{$fileExt}";

        echo json_encode($result);
		exit;
		break;
}

// 이미지 파일이 맞는지 검사. 
if($fileType == 'image'){
	// 허용할 확장자를 jpg, bmp, gif, png로 정함, 그 외에는 업로드 불가
	if($extStatus){
		// 임시 파일 옮길 디렉토리 및 파일명
		//$resFile = "../upload/{$_FILES['files']['name']}";

        $tmpfilename = $tmpfname.".".$fileExt;

        if($directory != ""){
            $dir_path = "../upload/".$directory;
            $file_path = $directory."/".$tmpfilename;
        }else{
            $dir_path = "../upload";
            $file_path = $tmpfilename;
        }

        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0777, true);
        }

        $resFile = $dir_path."/".$tmpfilename;



		// 임시 저장된 파일을 우리가 저장할 디렉토리 및 파일명으로 옮김
		$imageUpload = move_uploaded_file($tempFile, $resFile);
		
		// 업로드 성공 여부 확인
		if($imageUpload == true){

            $result = array();
            $result['status'] = 1;
            $result['data']['upload_file_url']=$api_domain."/upload/".$file_path;

            echo json_encode($result);
            exit;

		}else{

            $result = array();
            $result['status'] = 0;
            $result['msg']="파일 업로드에 실패하였습니다.";
    
            echo json_encode($result);
            exit;

		}
	}	// end if - extStatus
		// 확장자가 jpg, bmp, gif, png가 아닌 경우 else문 실행
	else {
        error_json(0,"파일 확장자는 jpg, bmp, gif, png 이어야 합니다.");

        $result = array();
        $result['status'] = 0;
        $result['msg']="파일 확장자는 jpg, bmp, gif, png 이어야 합니다. EXT:".$fileExt;

        echo json_encode($result);
        exit;

	}	
}	// end if - filetype
	// 파일 타입이 image가 아닌 경우 
else {
    error_json(0,"이미지 파일이 아닙니다.");
	exit;
}


0



?>