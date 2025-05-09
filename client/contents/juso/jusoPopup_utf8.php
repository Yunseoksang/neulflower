<?php
set_error_handler(function(int $errno, string $errstr) {
    if ((strpos($errstr, 'Undefined array key') === false) && (strpos($errstr, 'Undefined variable') === false)) {
        return false;
    } else {
        return true;
    }
}, E_WARNING);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
<? 
	$ADDR['inputYn'] = $_POST['inputYn'];
	$ADDR['roadFullAddr'] = $_POST['roadFullAddr'];
	$ADDR['roadAddrPart1'] = $_POST['roadAddrPart1'];
	$ADDR['roadAddrPart2'] = $_POST['roadAddrPart2'];
	$ADDR['engAddr'] = $_POST['engAddr'];
	$ADDR['jibunAddr'] = $_POST['jibunAddr'];
	$ADDR['zipNo'] = $_POST['zipNo'];
	$ADDR['addrDetail'] = $_POST['addrDetail'];
	$ADDR['admCd'] = $_POST['admCd'];
	$ADDR['rnMgtSn'] = $_POST['rnMgtSn'];
	$ADDR['bdMgtSn'] = $_POST['bdMgtSn'];
	$ADDR['detBdNmList'] = $_POST['detBdNmList'];
	//** 2017년 2월 제공항목 추가 **/
	$ADDR['bdNm'] = $_POST['bdNm'];
	$ADDR['bdKdcd'] = $_POST['bdKdcd'];
	$ADDR['siNm'] = $_POST['siNm'];
	$ADDR['sggNm'] = $_POST['sggNm'];
	$ADDR['emdNm'] = $_POST['emdNm'];
	$ADDR['liNm'] = $_POST['liNm'];
	$ADDR['rn'] = $_POST['rn'];
	$ADDR['udrtYn'] = $_POST['udrtYn'];
	$ADDR['buldMnnm'] = $_POST['buldMnnm'];
	$ADDR['buldSlno'] = $_POST['buldSlno'];
	$ADDR['mtYn'] = $_POST['mtYn'];
	$ADDR['lnbrMnnm'] = $_POST['lnbrMnnm'];	
	$ADDR['lnbrSlno'] = $_POST['lnbrSlno'];
	//** 2017년 3월 제공항목 추가 **/
	$ADDR['emdNo'] = $_POST['emdNo'];
?>
</head>
<script language="javascript">
// opener관련 오류가 발생하는 경우 아래 주석을 해지하고, 사용자의 도메인정보를 입력합니다. ("주소입력화면 소스"도 동일하게 적용시켜야 합니다.)
document.domain = "neulflower.kr";

/*
		모의 해킹 테스트 시 팝업API를 호출하시면 IP가 차단 될 수 있습니다. 
		주소팝업API를 제외하시고 테스트 하시기 바랍니다.
*/

function init(){
	var url = location.href;
	var confmKey = "U01TX0FVVEgyMDIzMDEzMTE5MjkzODExMzQ2NDU=";
	var resultType = "4"; // 도로명주소 검색결과 화면 출력내용, 1 : 도로명, 2 : 도로명+지번+상세보기(관련지번, 관할주민센터), 3 : 도로명+상세보기(상세건물명), 4 : 도로명+지번+상세보기(관련지번, 관할주민센터, 상세건물명)
	// php.ini 에 short_open_tag 가 On 으로 설정되어 되어 있는 경우 아래 소스 코드 사용
	var inputYn= "<?=$ADDR['inputYn']?>";
	// php.ini 에 short_open_tag 가 Off 으로 설정되어 되어 있는 경우 아래 소스 코드 사용
	// var inputYn= "<?php echo $ADDR['inputYn']; ?>";
	if(inputYn != "Y"){
		document.form.confmKey.value = confmKey;
		document.form.returnUrl.value = url;
		document.form.resultType.value = resultType;
		document.form.action="https://business.juso.go.kr/addrlink/addrLinkUrl.do"; //인터넷망
		//document.form.action="https://business.juso.go.kr/addrlink/addrMobileLinkUrl.do"; //모바일 웹인 경우, 인터넷망
		document.form.submit();
	}else{
		opener.jusoCallBack("<?=$ADDR['roadFullAddr']?>","<?=$ADDR['roadAddrPart1']?>","<?=$ADDR['addrDetail']?>","<?=$ADDR['roadAddrPart2']?>","<?=$ADDR['engAddr']?>","<?=$ADDR['jibunAddr']?>","<?=$ADDR['zipNo']?>","<?=$ADDR['admCd']?>","<?=$ADDR['rnMgtSn']?>","<?=$ADDR['bdMgtSn']?>","<?=$ADDR['detBdNmList']?>","<?=$ADDR['bdNm']?>","<?=$ADDR['bdKdcd']?>","<?=$ADDR['siNm']?>","<?=$ADDR['sggNm']?>","<?=$ADDR['emdNm']?>","<?=$ADDR['liNm']?>","<?=$ADDR['rn']?>","<?=$ADDR['udrtYn']?>","<?=$ADDR['buldMnnm']?>","<?=$ADDR['buldSlno']?>","<?=$ADDR['mtYn']?>","<?=$ADDR['lnbrMnnm']?>","<?=$ADDR['lnbrSlno']?>","<?=$ADDR['emdNo']?>");
		window.close();
	}
}
</script>
<body onload="init();">
	<form id="form" name="form" method="post">
		<input type="hidden" id="confmKey" name="confmKey" value=""/>
		<input type="hidden" id="returnUrl" name="returnUrl" value=""/>
		<input type="hidden" id="resultType" name="resultType" value=""/>
		<!-- 해당시스템의 인코딩타입이 EUC-KR일경우에만 추가 START-->
		<!--input type="hidden" id="encodingType" name="encodingType" value="EUC-KR"/-->
		<!-- 해당시스템의 인코딩타입이 EUC-KR일경우에만 추가 END-->
	</form>
</body>
</html>