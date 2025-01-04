<?php

//
// 기즈모Lib 거래명세서(거래명세표) 와 세금계산서 작성용 Class
//

/*

  -- 라이센스 안내 --

  [저작자] : 설치형 다중계정 블로그 솔루션 - 기즈모 http://gizmo.co.kr

  본 라이브러리의 수정 및 사용/배포는 자유입니다.
  단, 어떤 경우(2차 저작물에 포함될 경우에도)에도 본 라이센스를 훼손해서는 안됩니다.
  온라인 배포시에는 출처와 함께 저작자 사이트로의 바로가기 링크를 "반드시" 제공해야 합니다.

*/


/*
  +------+
  | 설명 |
  +------+

  * 작성언어 : PHP4
  * 명칭 : 거래명세서/거래명세표 및 세금계산서 양식 HTML 출력용 PHP 클래스

  * 거래명세서(거래명세표) 및 세금계산서 작성에 필요한 각 항목들을 지정하고, 각 품목의 명칭과 가격등을 입력하면 거래명세서 양식에 따라 HTML로 출력해 주는 PHP 라이브러리 입니다.
  * VAT 포함/별도가 연산을 편의대로 지정할 수 있습니다.
  * 동일한 공급자용 / 공급받는자용 2개 Copy를 A4 가로용지에 맞게끔 한 페이지에 출력합니다. (반드시 가로출력 요망)
  * 익스플로어의 "도구"->"인터넷옵션"->"고급"->"인쇄" 항목 의 "배경색 및 이미지 인쇄"를 체크해 두지 않으면 셀의 테두리가 출력되지 않을 수 있습니다.

  * PHP class를 사용하지 않고 HTML 거래명세서 양식만 사용하는 방법
    - form_note.php 의 PHP 출력 변수를 모두 삭제하고 원하는 값으로 대체합니다.
    - 셀 안쪽에 빈 값이 들어갈 경우 테두리가 표시되지 않을 수 있습니다. 이 때에는 빈 셀마다 '&nbsp;'를 출력해 주어야 합니다.

  * 주의 : 세금계산서의 "현금", "수표", "어음", "외상미수금" 항목 인쇄는 지원하지 않습니다.
  * 저작자는 버그 및 기타 오류로 인한 어떠한 피해도 책임지지 않습니다.

  +----------------------------+
  | 사용방법 예제 (거래명세서) |
  +----------------------------+

  $NFORM = new Gizmo_TradeNote;

  // VAT포함가로 출력합니다. $NFORM->AddArticle() 로 단가 또는 금액 입력시 VAT 포함가가 들어가야 합니다.
  // $NFORM->SetTaxAdded(false); 이면 입력하는 단가나 금액은 VAT 별도가입니다.
  $NFORM->SetTaxAdded(true);

  // 공급자 정보를 입력합니다. 상호는 필수.
  $NFORM->SetCompany('*상호', '대표자성명', '사업등록번호', '사업장주소', '업태', '종목');
  
  // 공급받는자 정보를 입력합니다. 상호는 필수.
  $NFORM->SetCustomer('*상호', '사업장주소', '전화번호');

  // 작성일을 'YYYY-mm-dd' 포맷으로 지정합니다.
  $NFORM->SetIssuedDate('2006-01-26');

  // 명세서 일련번호를 설정합니다.
  $NFORM->SetSerial('A0020333');

  // 페이지 번호를 입력합니다.
  $NFORM->SetPage(1);

  // 출력매수 (공통:BOTH(공급자용/공급받는자용), 세금계산서:BLUE(공급받는자용만)/RED(공급자용만), 거래명세서:SINGLE(1매만)
  $NFORM->SetPair('SINGLE');

  // 공급자 직인 이미지의 URL경로 (배경이 투명해야함, 60*60px 이하가 적당)
  $NFORM->SetSignPath('/img/sign.gif');

  // 품목정보를 필요한 만큼 입력합니다.
  // 단가나 금액은 한가지만 넣어주면 수량을 참조하여 나머지를 자동으로 채워 넣습니다.
  // 단가와 금액 모두를 넣어주면 별도로 연산하지 않고 그대로 사용합니다.
  // (bool)영수에 false 를 지정하면 [청구]로 표시되며, 하단에 청구금액으로 합산됩니다.
  //$NFORM->AddArticle('품목명', (int)단가, (int)금액(단가*수량), (int)수량, '거래일(YYYY-mm-dd)', '규격', '비고', (bool)영수);
  $NFORM->AddArticle('제주 王 한라봉', 42000, 0, 5, '2006-01-26', '박스', '맛이 좋아요');
  $NFORM->AddArticle('홍성 밤고구마', 14000, 0, 10, '2006-01-26', '포대', '군고구마 사업용');
  $NFORM->AddArticle('토마토', 300, 0, 53, '2006-01-26', '개', '');

  // 거래명세서 HTML 양식 출력
  // 인수로 양식파일의 경로를 지정할 수 있습니다. 기본 설치했을 경우는 현재 디렉토리의 form_note.php 를 불러옵니다.
  $NFORM->PrintNote();


  +-----------------------------+
  | 사용방법 예제2 (세금계산서) |
  +-----------------------------+

  $NFORM = new Gizmo_TradeNote;

  // VAT포함가로 출력합니다. $NFORM->AddArticle() 로 단가 또는 금액 입력시 VAT 포함가가 들어가야 합니다.
  // $NFORM->SetTaxAdded(false); 이면 입력하는 단가나 금액은 VAT 별도가입니다.
  $NFORM->SetTaxAdded(true);
  
  // 일련번호를 지정합니다. (옵션)
  $NFORM->SetSerial('010022');
  
  // 비고란 (개인 매출시 주민번호등 기록용) 내용입니다 (옵션)
  $NFORM->SetTaxNote('790222-1111111);

  // 매출액의 청구/영수 여부를 지정합니다 (true:영수, false:청구 (기본:청구))
  $NFORM->SetReceipt(true);

  // 공급자 정보를 입력합니다. (계산서가 유효하기 위해서는 모든 항목을 정확히 입력해야 합니다)
  $NFORM->SetCompany('상호', '대표자성명', '사업등록번호', '사업장주소', '업태', '종목');
  
  // 공급받는자 정보를 입력합니다. (거래명세서 작성시에도 $this->SetCustomerTax() 를 사용할 수 있지만, 입력해야 할 항목들이 늘어납니다)
  $NFORM->SetCustomerTax('상호', '성명', '사업등록번호', '사업장주소', '업태', '종목');

  // 작성일을 'YYYY-mm-dd' 포맷으로 지정합니다.
  $NFORM->SetIssuedDate('2006-01-26');

  // 출력매수 (공통:BOTH(공급자용/공급받는자용), 세금계산서:BLUE(공급받는자용만)/RED(공급자용만), 거래명세서:SINGLE(1매만)
  $NFORM->SetPair('BLUE');

  // 공급자 직인 이미지의 URL경로 (배경이 투명해야함, 60*60px 이하가 적당)
  $NFORM->SetSignPath('/img/sign.gif');

  // 재발행건수를 설정합니다. (1 이상으로 설정시 우측하단에 재발행 메시지와 함께 카운트가 표시됩니다)
  $NFORM->SetReissue(0);

  // 품목정보를 필요한 만큼 입력합니다. (거래명세서와 동일)
  // 단가나 금액은 한가지만 넣어주면 수량을 참조하여 나머지를 자동으로 채워 넣습니다.
  // 단가와 금액 모두를 넣어주면 별도로 연산하지 않고 그대로 사용합니다.
  //$NFORM->AddArticle('품목명', (int)단가, (int)금액(단가*수량), (int)수량, '거래일(YYYY-mm-dd)', '규격', '비고');
  $NFORM->AddArticle('제주 王 한라봉', 42000, 0, 5, '2006-01-26', '박스', '맛이 좋아요');
  $NFORM->AddArticle('홍성 밤고구마', 14000, 0, 10, '2006-01-26', '포대', '군고구마 사업용');
  $NFORM->AddArticle('토마토', 300, 0, 53, '2006-01-26', '개', '');

  // 세금계산서 HTML 양식 출력
  // 인수로 양식파일의 경로를 지정할 수 있습니다. 기본 설치했을 경우는 현재 디렉토리의 form_tax.php 를 불러옵니다.
  $NFORM->PrintTax();

*/


class Gizmo_TradeNote {

  var $note_form_path = 'form_note.php';      // 출력할 거래명세서 HTML 양식의 파일 경로
  var $tax_form_path = 'form_tax.php';        // 출력할 세금계산서 HTML 양식의 파일 경로
  var $tax_added = true;                      // 부가세 포함된 금액 처리이면 true
  var $company = array();                     // 공급자 정보 배열
  var $customer = array();                    // 공급받는자 정보 배열
  var $info = array();                        // 거래일/페이지등 부가정보 배열
  var $article = array();                     // 품목 배열
  var $tax_rate = 1.1;                        // 부가세율 (1.1 = 10%)
  var $article_rows_note = 19;                // 거래명세서의 품목 최대 줄 수
  var $article_rows_tax = 4;                  // 세금계산서의 품목 최대 줄 수
  var $pair = 'BOTH';                         // 공급자/공급받는자 함께출력:BOTH, 공급받는자만:BLUE, 공급자만:RED, 거래명세서1매:SINGLE
  var $sign_path = '';                        // 공급자 직인 URL 경로

  function Gizmo_TradeNote () {

    $this->info['date'] = date('Y-m-d', time());
    $this->info['page'] = 1;
    $this->info['amount_tot'] = 0;
    $this->info['receivable_tot'] = 0;
    $this->info['tax_added_txt'] = '포함';
    $this->info['serial'] = '';
    $this->info['bookno'] = '';
    $this->info['bookno_ho'] = '';
    $this->info['note'] = '';
    $this->info['tax_note'] = '';
    $this->info['receipt'] = false;
    $this->info['target'] = array('공급받는자 보관용', '공급자용');
    $this->info['color'] = array('청색', '적색');
    $this->info['tax_reissue'] = 0;

  }

  // 품목의 금액에 부가세가 포함되어있는지 여부 (true: 포함되어있음, false: 포함되어있지 않음)
  function SetTaxAdded ($tax=true) {
    $this->tax_added = (bool)$tax;
    $this->info['tax_added_txt'] = ($this->tax_added) ? '포함' : '별도';
  }

  // 세금계산서 전용 비고 설정
  function SetTaxNote ($note) {
    $this->info['tax_note'] = $note;
  }

  // 공급자 정보 설정
  // $company:상호, $name:성명, $taxid:사업등록번호, $address:사업장소재지, $biz_type:업태, $biz_item:종목
  function SetCompany ($company, $name='', $taxid='', $address='', $biz_type='', $biz_item='') {

    $this->company = array('company'=>$company, 'name'=>$name, 'taxid'=>$taxid, 'address'=>$address, 'biz_type'=>$biz_type, 'biz_item'=>$biz_item);
  
  }

  // 공급받는자 정보 설정 (세금계산서용. 인수의 용도는 $this->SetCompany()와 동일)
  function SetCustomerTax ($company, $name='', $taxid='', $address='', $biz_type='', $biz_item='') {

    $this->customer = array('company'=>$company, 'name'=>$name, 'taxid'=>$taxid, 'address'=>$address, 'biz_type'=>$biz_type, 'biz_item'=>$biz_item);

  }

  // 공급받는자 정보 설정(거래명세서 전용)
  // $company:상호, $address:사업장소재지, $tel:전화번호
  function SetCustomer ($company, $address='', $tel='') {

    $this->customer = array('company'=>$company, 'address'=>$address, 'tel'=>$tel);

  }

  // 발행일 설정 ($idate:'YYYY-mm-dd' 형식)
  function SetIssuedDate ($idate) {
    if ($idate) $this->info['date'] = date('Y-m-d', strtotime($idate));
  }

  // 페이지 설정
  function SetPage ($page=1) {
    $page = (int)$page;
    $this->info['page'] = ($page > 0) ? $page : 1;
  }

  // 세금계산서 책번호 설정
  function SetBookNo ($bookno, $bookno_ho='') {
    $this->info['bookno'] = $bookno;
    $this->info['bookno_ho'] = $bookno_ho;
  }

  // 일련번호 설정
  function SetSerial ($serial) {
    $this->info['serial'] = $serial;
  }

  // 영수 여부 (계산서)
  function SetReceipt ($ok) {
    $this->info['receipt'] = (bool)$ok;
  }

  // 출력매수 (공통:BOTH(공급자용/공급받는자용), 세금계산서:BLUE(공급받는자용만)/RED(공급자용만), 거래명세서:SINGLE(1매만)
  function SetPair ($pair) {
    $this->pair = strtoupper($pair);
    if ($this->pair != 'BOTH' && $this->pair != 'BLUE' && $this->pair != 'RED' && $this->pair != 'SINGLE') $this->pair = 'BOTH';
  }

  // 공급자 도장 이미지 경로 (URL경로)
  function SetSignPath ($path) {
    $this->sign_path = $path;
  }

  // 세금계산서 재발행 회수 (거래명세서 해당없음, 0이면 신규)
  function SetReissue ($cnt=0) {
    $this->info['tax_reissue'] = (int)$cnt;
  }

  // 품목 리스트 추가
  // $article:품목명, $adate:거래일(YYYY-mm-dd), $type:규격, $pcs:수량, $price_each:단가, $amount:금액, $note:비고
  // $price_each(단가), $amount(금액)는 한 가지만 전달해도 $pcs를 참조하여 자동 계산 (둘다 전달 받으면 계산 없음)
  // $this->SetTaxAdded() 세팅 후 호출
  function AddArticle ($article, $price_each=0, $amount=0, $pcs=1, $adate='', $type='', $note='', $paid=true) {

    $pcs = (int)$pcs;
    $pcs = ($pcs > 0) ? $pcs : 1;
    $price_each = (int)$price_each;
    $amount = (int)$amount;
    $ts = ($adate) ? strtotime($adate) : time();

    $arr = array('article'=>$article, 'price_each'=>$price_each, 'amount'=>$amount, 'pcs'=>$pcs, 'type'=>$type, 'note'=>$note, 'year'=>date('Y', $ts), 'month'=>date('m', $ts), 'day'=>date('d', $ts), 'paid'=>(bool)$paid);

    // 단가와 금액 확정
    if (!$price_each && $amount) $price_each = round($amount / $pcs);
    elseif ($price_each && !$amount) $amount = $price_each * $pcs;

    // 품목별 부가세
    //$tax = ($this->tax_added == true) ? round($amount - ($amount / $this->tax_rate)) : round(($amount / $this->tax_rate) - $amount);

    $arr['price_each'] = $price_each;
    $arr['amount'] = $amount;

    $this->info['amount_tot']+= $arr['amount'];
    if ($arr['paid'] == false) $this->info['receivable_tot']+= $arr['amount'];
      
    $this->article[] = $arr;

  }

  // 거래명세서 양식 출력 (양식 파일을 찾을 수 없으면 false 리턴)
  function PrintNote ($form_path='', $form_type='note') {

    $form_path = ($form_path == '') ? $this->note_form_path : $form_path;

    if (!is_file($form_path)) return false;

    $prtCompany = $this->company;
    $prtInfo = $this->info;
    $prtCustomer = $this->customer;
    $prtPair = $this->pair;
    $prtAlign = ($prtPair != 'BOTH') ? 'left' : 'center';

    for ($i=0, $maxi=count($this->article); $i<$maxi; $i++) {

      $prtArticle[$i] = $this->FillSpace_($this->article[$i]);

      // 세금계산서 출력인데, 부가세 포함가가 입력되었으면
      if ($this->tax_added == true && $form_type == 'tax') {

        $prtArticle[$i]['amount'] = round(($prtArticle[$i]['price_each'] * $prtArticle[$i]['pcs']) / $this->tax_rate);
        $prtArticle[$i]['price_each'] = round($prtArticle[$i]['price_each'] / $this->tax_rate);
        $prtArticle[$i]['tax'] = round(($prtArticle[$i]['amount'] * $this->tax_rate) - $prtArticle[$i]['amount']);

        if ($i == 0) $prtInfo['amount_tot'] = $prtInfo['amount_tax'] = 0;
        $prtInfo['amount_tot']+= $prtArticle[$i]['amount'];
        $prtInfo['amount_tax']+= $prtArticle[$i]['tax'];

      // 세금계산서 출력이고 부가세 별도가로 입력되어 있으면
      } elseif ($this->tax_added == false && $form_type == 'tax') {

        $prtArticle[$i]['tax'] = round(($prtArticle[$i]['amount'] * $this->tax_rate) - $prtArticle[$i]['amount']);

      }

      $prtArticle[$i]['pay_type'] = ($this->article[$i]['paid'] == false) ? '청구' : '';
      $prtArticle[$i]['price_each'] = '￦'.number_format($prtArticle[$i]['price_each']);
      $prtArticle[$i]['amount'] = '￦'.number_format($prtArticle[$i]['amount']);
      $prtArticle[$i]['pcs'] = number_format($prtArticle[$i]['pcs']);
      if (isset($prtArticle[$i]['tax'])) $prtArticle[$i]['tax'] = '￦'.number_format($prtArticle[$i]['tax']);

    }

    // 세금계산서 출력이면
    if ($form_type == 'tax') {

      $prtInfo['amount_space'] = 11 - strlen((string)$prtInfo['amount_tot']);
      $prtInfo['amount_cell'] = $this->CellFormat_($prtInfo['amount_tot']);
      $prtInfo['amount_tax_cell'] = $this->CellFormat_($prtInfo['amount_tax']);

      $prtInfo['serial'] = $this->info['serial'];
      //$prtInfo['serial_cell'] = $this->CellFormat_($this->info['serial'], 6); // 삭제됨 (전달받은 일련번호를 모두 출력)

      $prtInfo['bookno'] = $this->info['bookno'];
      $prtInfo['bookno_ho'] = $this->info['bookno_ho'];

      list($prtInfo['trade_date']['year'], $prtInfo['trade_date']['month'],$prtInfo['trade_date']['day']) = explode('-', $this->info['date']);

      $prtInfo['blank_rows'] = (count($prtArticle) > $this->article_rows_tax) ? count($prtArticle) : $this->article_rows_tax;
      $prtInfo['amount_sum'] = '￦'.number_format((int)str_replace(',', '', $prtInfo['amount_tot']) + (int)str_replace(',', '', $prtInfo['amount_tax']));

    } else {
      $prtInfo['blank_rows'] = (count($prtArticle) > $this->article_rows_note) ? count($prtArticle) : $this->article_rows_note;
    }

    $prtInfo['paid_tot'] = '￦'.number_format($prtInfo['amount_tot'] - $prtInfo['receivable_tot']);
    $prtInfo['amount_tot'] = '￦'.number_format($prtInfo['amount_tot']);
    $prtInfo['receivable_tot'] = '￦'.number_format($prtInfo['receivable_tot']);

    $prtArticle = $this->BlankRows_($prtArticle, $prtInfo['blank_rows']);
    $prtArticle = $this->FillSpace_($prtArticle);

    $prtInfo['receipt'] = ($this->info['receipt'] == true) ? '영수' : '청구';
    $prtInfo = $this->FillSpace_($prtInfo);
    $prtCompany = $this->FillSpace_($prtCompany);
    $prtCustomer = $this->FillSpace_($prtCustomer);

    // 직인 태그
    $prtCompany['sign_img'] = ($this->sign_path != '') ? "<img src=\"{$this->sign_path}\" class=\"sign_img\" />" : '';

    include $form_path;
    return true;
  }

  // 세금계산서 양식 출력
  function PrintTax ($form_path='') {

    $form_path = ($form_path == '') ? $this->tax_form_path : $form_path;
    return $this->PrintNote($form_path, 'tax');
  
  }

  // 세금 계산서의 공급가액/세액 합계 출력용 배열 리턴
  function CellFormat_ ($amount, $cells=11) {

    $amount = (string)$amount;
    $amount_len = strlen($amount);
    $space = $cells - $amount_len;
    $cell = array();
    if ($amount_len > 0) {
      for ($i=0; $i<=$amount_len-1; $i++) {
        $cell[] = $amount[$amount_len-$i-1];
      }
    }
    for ($i=$amount_len; $i<$cells; $i++) {
      $cell[] = '.';
    }
    return $cell;
  }

  // (배열)변수 내 값이 없으면 "&nbsp;" 삽입
  function FillSpace_ ($vars) {

    if (!is_array($vars)) {
      if ($vars == '') return '&nbsp;'; else $vars;
    }

    $vars_ = array();
    foreach ($vars as $key => $val) {
      if (is_array($val)) {
        foreach ($val as $key2 => $val2) {
          $vars_[$key][$key2] = ($val2 == '') ? '&nbsp;' : $val2;
        }
      } else $vars_[$key] = ($val == '') ? '&nbsp;' : $val;
    }

    return $vars_;
  }

  function BlankRows_ ($vars, $max_rows) {

    $cur_rows = count($vars);
    //$copy_keys= array();

    for ($i=0; $i<$max_rows; $i++) {

      if (isset($vars[$i]) && !isset($copy_keys)) {
        $copy_keys = array();
        foreach ($vars[$i] as $key => $val) {
          $copy_keys[$key] = '';
        }
      }

      if (!isset($vars[$i])) $vars[$i] = $copy_keys;
    
    }

    return $vars;
  }

}

?>