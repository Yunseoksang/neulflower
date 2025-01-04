<?php
  require_once './Popbill/PopbillStatement.php';

  // 링크아이디
  $LinkID = 'neulflower';

  // 비밀키
  $SecretKey = 'q2OAaFCReRH57CwbP2aj/bsUm9iUKOIJx54cMNDtLOg=';

  // 통신방식 기본은 CURL , curl 사용에 문제가 있을경우 STREAM 사용가능.
  // STREAM 사용시에는 php.ini의 allow_url_fopen = on 으로 설정해야함.
  define('LINKHUB_COMM_MODE','CURL');

  $StatementService = new StatementService($LinkID, $SecretKey);

  // 연동환경 설정값, 개발용(true), 상업용(false)
  $StatementService->IsTest(true);

  // 인증토큰에 대한 IP제한기능 사용여부, 권장(true)
  $StatementService->IPRestrictOnOff(true);

  // 팝빌 API 서비스 고정 IP 사용여부, 기본값(false)
  $StatementService->UseStaticIP(false);

  // 로컬시스템 시간 사용 여부 true(기본값) - 사용, false(미사용)
  $StatementService->UseLocalTimeYN(true);
?>