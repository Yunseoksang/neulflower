<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>세금 계산서</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">

.bout {
  font-family:굴림,바탕;
  border: 3px double blue;
}
body, table, tr, td, select{font-family:굴림, verdana, arial; font-size: 12px;color: #000000; border:1px;}
.ttxt {font-family:굴림, verdana, arial; font-size: 12px;color: #0000FF;}
.cborder {border-top-width:1; border-right-width:1; border-bottom-width:1; border-left-width:1; border-color:BLUE; border-top-style:solid; border-right-style:none; border-bottom-style:solid; border-left-style:solid;}
.ctit {font-size: 22px;color: #0000FF; font-weight:bold;}
.ccmt {font-size: 12px;color: #0000FF;}
.taxidno {font-size: 16px;color: black; font-weight:bold;}

.bout2 {font-family:굴림,바탕;border: 3px double red;}
.ttxt2 {font-family:굴림, verdana, arial; font-size: 12px;color: red;}
.cborder2 {border-top-width:1; border-right-width:1; border-bottom-width:1; border-left-width:1; border-color:red; border-top-style:solid; border-right-style:none; border-bottom-style:solid; border-left-style:solid;}
.ctit2 {font-size: 22px;color:red; font-weight:bold;}
.ccmt2 {font-size: 12px;color:red;}

#command_bar {
  font-size: 10pt;
  background-color: #FEFFD2;
  border: 1px solid #AF9E29;
  padding: 5px;
  margin-bottom: 10px;
}
.sign_area {
  position: relative;
}
.sign_img {
  position: absolute;
  top: 15px;
  left: 230px;
}
</style>
<script type="text/javascript">
window.resizeTo (700, 570);
window.focus();

function printNow() {
  document.getElementById('command_bar').style.display = 'none';
  window.print();
}
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="command_bar">
  A4용지를 준비하고 인쇄버튼을 클릭하세요. &nbsp; <input type="button" value="인쇄하기" onclick="printNow()" /> <input type="button" value="뒤로" onclick="window.history.go(-1)" />
</div>
<table width="586" border="0" cellspacing="0" cellpadding="0" align="center">
<?php if ($prtPair == 'BLUE' || $prtPair == 'BOTH') { // 공급받는자용 시작 ?>
  <tr>
    <td height="10">
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td height="10" class="ttxt">[별지 제11호 서식]</td>
          <td align="right" class="ttxt">[<?=$prtInfo['color'][0]?>]</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td width="586">
      <table width="100%" cellpadding="0" cellspacing="0" class="bout">
        <tr> 
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
			  	<td width="20" height="44"></td>
                <td width="359" align="center"><span class="ctit">세 금 계 산 서</span> <span class="ccmt">(<?=$prtInfo['target'][0]?>)</span></td>
                <td width="201"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="25%" class="cborder" align="center" style="border-top-style:none"><span class="ttxt">책 번 호</span></td>
                      <td width="33%" align="right" class="cborder" style="border-top-style:none"><?=$prtInfo['bookno']?> <span class="ttxt">권</span> &nbsp;</td>
                      <td width="42%" align="right" class="cborder" style="border-top-style:none"><?=$prtInfo['bookno_ho']?> <span class="ttxt">호</span> &nbsp;</td>
                    </tr>
                    <tr>
                      <td class="cborder" align="center" style="border-top-style:none;border-bottom-style:none"><span class="ttxt">일련번호</span></td>
                      <td colspan="2" align="center" class="cborder" style="border-top-style:none;border-bottom-style:none"><?=$prtInfo['serial']?></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td><table width="100%" class="cborder" style="border-left-style:none" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td width="18" align="center" class="cborder" style="border-left-style:none"><span class="ttxt" style="line-height:35px">공<br>
                        급<br>
                        자</span></td>
                      <td><div class="sign_area"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr height="35"> 
                            <td width="20%" align="center" class="cborder"><span class="ttxt">등록번호</span></td>
                            <td width="80%" class="cborder" align="center"><span class="taxidno"><?=$prtCompany['taxid']?></span></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder" style="border-top-style:none"><span class="ttxt">상 호<br>
                              (법인명)</span> </td>
                            <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td class="cborder" style="border-top-style:none" align="center"><?=$prtCompany['company']?></td>
                                  <td class="cborder" style="border-top-style:none" align="center" width="15"><span class="ttxt">성<br>
                                    명</span></td>
                                  <td class="cborder" style="border-top-style:none" align="center" width="100"><?=$prtCompany['name']?>&nbsp;<span class="ttxt">(인)</span><?=$prtCompany['sign_img']?></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder" style="border-top-style:none"><span class="ttxt">사업장<br>
                              주 소</span></td>
                            <td class="cborder" style="border-top-style:none" align="center"><?=$prtCompany['address']?></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder" style="border-top-style:none"><span class="ttxt">업 태</span></td>
                            <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td class="cborder" style="border-top-style:none" align="center"><?=$prtCompany['biz_type']?></td>
                                  <td class="cborder" style="border-top-style:none" width="15" align="center"><span class="ttxt">종<br>목</span></td>
                                  <td class="cborder" style="border-top-style:none" align="center"><?=$prtCompany['biz_item']?></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></div></td>
                    </tr>
                  </table></td>
                <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td class="cborder" width="18" align="center"><span class="ttxt" style="line-height:20px">공<br>
                        급<br>
						            받<br>
						            는<br>
                        자</span></td>
                      <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr height="35"> 
                            <td width="20%" align="center" class="cborder"><span class="ttxt">등록번호</span></td>
                            <td width="80%" class="cborder" align="center"><span class="taxidno"><?=$prtCustomer['taxid']?></span></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder" style="border-top-style:none"><span class="ttxt">상 
                              호<br>
                              (법인명)</span> </td>
                            <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td class="cborder" style="border-top-style:none" align="center"><?=$prtCustomer['company']?></td>
                                  <td class="cborder" style="border-top-style:none" align="center" width="15"><span class="ttxt">성<br>
                                    명</span></td>
                                  <td class="cborder" style="border-top-style:none" width="100" align="center"><?=$prtCustomer['name']?>&nbsp;<span class="ttxt">(인)</span></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder" style="border-top-style:none"><span class="ttxt">사업장<br>
                              주 소</span></td>
                            <td class="cborder" style="border-top-style:none" align="center"><?=$prtCustomer['address']?></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder" style="border-top-style:none"><span class="ttxt">업 
                              태</span></td>
                            <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td class="cborder" style="border-top-style:none" align="center"><?=$prtCustomer['biz_type']?></td>
                                  <td class="cborder" style="border-top-style:none" width="15" align="center"><span class="ttxt">종<br>
                                    목</span></td>
                                  <td class="cborder" style="border-top-style:none" align="center"><?=$prtCustomer['biz_item']?></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="cborder" style="border-top-style:none;border-left-style:none">
              <tr height="20"> 
                <td class="cborder" style="border-top-style:none;border-left-style:none" align="center"><span class="ttxt">작 성</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">공 &nbsp; &nbsp; 급 
                   &nbsp; &nbsp; 가 &nbsp; &nbsp; 액</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">세 &nbsp; &nbsp; 액</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">비 고</span></td>
              </tr>
              <tr> 
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr height="20"> 
                      <td class="cborder" style="border-top-style:none;border-left-style:none" align="center"><span class="ttxt">년</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">월</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">일</span></td>
                    </tr>
                    <tr height="24"> 
                      <td class="cborder" style="border-top-style:none;border-left-style:none" align="center"><?=$prtInfo['trade_date']['year']?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['trade_date']['month']?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['trade_date']['day']?></td>
                    </tr>
                  </table></td>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr height="20"> 
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">공란수</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">백</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">십</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">억</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">천</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">백</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">십</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">만</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">천</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">백</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">십</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">일</span></td>
                    </tr>
                    <tr height="24"> 
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_space']?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][10]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][9]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][8]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][7]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][6]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][5]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][4]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][3]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][2]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][1]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][0]?></td>
                    </tr>
                  </table></td>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr height="20"> 
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">십</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">억</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">천</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">백</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">십</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">만</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">천</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">백</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">십</span></td>
                      <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">일</span></td>
                    </tr>
                    <tr height="24"> 
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][9]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][8]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][7]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][6]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][5]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][4]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][3]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][2]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][1]?></td>
                      <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][0]?></td>
                    </tr>
                  </table></td>
                <td class="cborder" style="border-top-style:none" align="center"><?=$prtInfo['tax_note']?></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="cborder" style="border-top-style:none;border-left-style:none">
              <tr height="20"> 
                <td class="cborder" style="border-top-style:none;border-left-style:none" align="center"><span class="ttxt">월</span></td>
                <td class="cborder" style="border-top-style:none;border-left-style:none" align="center"><span class="ttxt">일</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">품 &nbsp; &nbsp; 목</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">규 격</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">수 량</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">단 가</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">공 급 가 액</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">세 액</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">비 고</span></td>
              </tr>
  <?php for ($i=0; $i<$prtInfo['blank_rows']; $i++) { ?>
              <tr height="24"> 
                <td class="cborder" style="border-top-style:none;border-left-style:none" align="center"><?=$prtArticle[$i]['month']?></td>
                <td class="cborder" style="border-top-style:none" align="center"><?=$prtArticle[$i]['day']?></td>
                <td class="cborder" style="border-top-style:none" align="center"><?=$prtArticle[$i]['article']?></td>
                <td class="cborder" style="border-top-style:none" align="center"><?=$prtArticle[$i]['type']?></td>
                <td class="cborder" style="border-top-style:none" align="center"><?=$prtArticle[$i]['pcs']?></td>
                <td class="cborder" style="border-top-style:none" align="right"><?=$prtArticle[$i]['price_each']?> &nbsp;</td>
                <td class="cborder" style="border-top-style:none" align="right"><?=$prtArticle[$i]['amount']?> &nbsp;</td>
                <td class="cborder" style="border-top-style:none" align="right"><?=$prtArticle[$i]['tax']?> &nbsp;</td>
                <td class="cborder" style="border-top-style:none" align="center"><?=$prtArticle[$i]['note']?></td>
              </tr>
  <?php } ?>
            </table></td>
        </tr>
        <tr> 
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr height="20"> 
                <td class="cborder" style="border-top-style:none;border-left-style:none" align="center"><span class="ttxt">합 계 금 액</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">현 금</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">수 표</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">어 음</span></td>
                <td class="cborder" style="border-top-style:none" align="center"><span class="ttxt">외상미수금</span></td>
                <td class="cborder" style="border-top-style:none;border-bottom-style:none" rowspan="2" align="center"><span class="ttxt">이 금액을</span> 
                  <b><?=$prtInfo['receipt']?></b> <span class="ttxt">함</span></td>
              </tr>
              <tr height="24"> 
                <td class="cborder" style="border-top-style:none;border-left-style:none;border-bottom-style:none" align="center"><?=$prtInfo['amount_sum']?></td>
                <td class="cborder" style="border-top-style:none;border-bottom-style:none" align="center">&nbsp;</td>
                <td class="cborder" style="border-top-style:none;border-bottom-style:none" align="center">&nbsp;</td>
                <td class="cborder" style="border-top-style:none;border-bottom-style:none" align="center">&nbsp;</td>
                <td class="cborder" style="border-top-style:none;border-bottom-style:none" align="center">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="right">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td class="ccmt">[직인과 일련번호가 있어야 유효합니다]</td>
  <?php if ($prtInfo['tax_reissue'] > 0) { ?>
          <td align="right">[재발행: <?=$prtInfo['tax_reissue']?>회]</td>
  <?php } ?>
        </tr>
      </table>
    </td>
  </tr>
<?php } // 공급받는자용 끝 ?>

<?php if ($prtPair == 'BLUE' || $prtPair == 'BOTH') { // 공급받는자용 절취선 ?>
  <tr height="70"> 
    <td><hr size="1"></td>
  </tr>
<?php } ?>

<?php if ($prtPair == 'RED' || $prtPair == 'BOTH') { // 공급자용 시작 ?>
  <tr>
    <td height="10">
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td height="10" class="ttxt2">[별지 제11호 서식] &nbsp; [직인과 일련번호가 있어야 유효합니다]</td>
          <td align="right" class="ttxt2">[<?=$prtInfo['color'][1]?>]</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td width="586">
      <table width="100%" cellpadding="0" cellspacing="0" class="bout2">
        <tr> 
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
			  	<td width="20" height="44"></td>
                <td width="359" align="center"><span class="ctit2">세 금 계 산 서</span> <span class="ccmt2">(<?=$prtInfo['target'][1]?>)</span></td>
                <td width="201"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="25%" class="cborder2" align="center" style="border-top-style:none"><span class="ttxt2">책 번 호</span></td>
                      <td width="33%" align="right" class="cborder2" style="border-top-style:none"><?=$prtInfo['bookno']?> <span class="ttxt2">권</span> &nbsp;</td>
                      <td width="42%" align="right" class="cborder2" style="border-top-style:none"><?=$prtInfo['bookno_ho']?> <span class="ttxt2">호</span> &nbsp;</td>
                    </tr>
                    <tr>
                      <td class="cborder2" align="center" style="border-top-style:none;border-bottom-style:none"><span class="ttxt2">일련번호</span></td>
                      <td colspan="2" align="center" class="cborder2" style="border-top-style:none;border-bottom-style:none"><?=$prtInfo['serial']?></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td><table width="100%" class="cborder2" style="border-left-style:none" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td width="18" align="center" class="cborder2" style="border-left-style:none"><span class="ttxt2" style="line-height:35px">공<br>
                        급<br>
                        자</span></td>
                      <td><div class="sign_area"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr height="35"> 
                            <td width="20%" align="center" class="cborder2"><span class="ttxt2">등록번호</span></td>
                            <td width="80%" class="cborder2" align="center"><span class="taxidno"><?=$prtCompany['taxid']?></span></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder2" style="border-top-style:none"><span class="ttxt2">상 호<br>
                              (법인명)</span> </td>
                            <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td class="cborder2" style="border-top-style:none" align="center"><?=$prtCompany['company']?></td>
                                  <td class="cborder2" style="border-top-style:none" align="center" width="15"><span class="ttxt2">성<br>
                                    명</span></td>
                                  <td class="cborder2" style="border-top-style:none" align="center" width="100"><?=$prtCompany['name']?>&nbsp;<span class="ttxt2">(인)</span><?=$prtCompany['sign_img']?></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder2" style="border-top-style:none"><span class="ttxt2">사업장<br>
                              주 소</span></td>
                            <td class="cborder2" style="border-top-style:none" align="center"><?=$prtCompany['address']?></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder2" style="border-top-style:none"><span class="ttxt2">업 태</span></td>
                            <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td class="cborder2" style="border-top-style:none" align="center"><?=$prtCompany['biz_type']?></td>
                                  <td class="cborder2" style="border-top-style:none" width="15" align="center"><span class="ttxt2">종<br>목</span></td>
                                  <td class="cborder2" style="border-top-style:none" align="center"><?=$prtCompany['biz_item']?></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></div></td>
                    </tr>
                  </table></td>
                <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td class="cborder2" width="18" align="center"><span class="ttxt2" style="line-height:20px">공<br>
                        급<br>
						            받<br>
						            는<br>
                        자</span></td>
                      <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr height="35"> 
                            <td width="20%" align="center" class="cborder2"><span class="ttxt2">등록번호</span></td>
                            <td width="80%" class="cborder2" align="center"><span class="taxidno"><?=$prtCustomer['taxid']?></span></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder2" style="border-top-style:none"><span class="ttxt2">상 
                              호<br>
                              (법인명)</span> </td>
                            <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td class="cborder2" style="border-top-style:none" align="center"><?=$prtCustomer['company']?></td>
                                  <td class="cborder2" style="border-top-style:none" align="center" width="15"><span class="ttxt2">성<br>
                                    명</span></td>
                                  <td class="cborder2" style="border-top-style:none" width="100" align="center"><?=$prtCustomer['name']?>&nbsp;<span class="ttxt2">(인)</span></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder2" style="border-top-style:none"><span class="ttxt2">사업장<br>
                              주 소</span></td>
                            <td class="cborder2" style="border-top-style:none" align="center"><?=$prtCustomer['address']?></td>
                          </tr>
                          <tr height="35"> 
                            <td align="center" class="cborder2" style="border-top-style:none"><span class="ttxt2">업 
                              태</span></td>
                            <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr> 
                                  <td class="cborder2" style="border-top-style:none" align="center"><?=$prtCustomer['biz_type']?></td>
                                  <td class="cborder2" style="border-top-style:none" width="15" align="center"><span class="ttxt2">종<br>
                                    목</span></td>
                                  <td class="cborder2" style="border-top-style:none" align="center"><?=$prtCustomer['biz_item']?></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="cborder2" style="border-top-style:none;border-left-style:none">
              <tr height="20"> 
                <td class="cborder2" style="border-top-style:none;border-left-style:none" align="center"><span class="ttxt2">작 성</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">공 &nbsp; &nbsp; 급 
                   &nbsp; &nbsp; 가 &nbsp; &nbsp; 액</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">세 &nbsp; &nbsp; 액</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">비 고</span></td>
              </tr>
              <tr> 
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr height="20"> 
                      <td class="cborder2" style="border-top-style:none;border-left-style:none" align="center"><span class="ttxt2">년</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">월</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">일</span></td>
                    </tr>
                    <tr height="24"> 
                      <td class="cborder2" style="border-top-style:none;border-left-style:none" align="center"><?=$prtInfo['trade_date']['year']?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['trade_date']['month']?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['trade_date']['day']?></td>
                    </tr>
                  </table></td>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr height="20"> 
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">공란수</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">백</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">십</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">억</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">천</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">백</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">십</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">만</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">천</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">백</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">십</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">일</span></td>
                    </tr>
                    <tr height="24"> 
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_space']?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][10]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][9]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][8]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][7]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][6]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][5]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][4]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][3]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][2]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][1]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_cell'][0]?></td>
                    </tr>
                  </table></td>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr height="20"> 
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">십</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">억</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">천</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">백</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">십</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">만</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">천</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">백</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">십</span></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">일</span></td>
                    </tr>
                    <tr height="24"> 
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][9]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][8]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][7]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][6]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][5]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][4]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][3]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][2]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][1]?></td>
                      <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['amount_tax_cell'][0]?></td>
                    </tr>
                  </table></td>
                <td class="cborder2" style="border-top-style:none" align="center"><?=$prtInfo['tax_note']?></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="cborder2" style="border-top-style:none;border-left-style:none">
              <tr height="20"> 
                <td class="cborder2" style="border-top-style:none;border-left-style:none" align="center"><span class="ttxt2">월</span></td>
                <td class="cborder2" style="border-top-style:none;border-left-style:none" align="center"><span class="ttxt2">일</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">품 &nbsp; &nbsp; 목</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">규 격</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">수 량</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">단 가</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">공 급 가 액</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">세 액</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">비 고</span></td>
              </tr>
  <?php for ($i=0; $i<$prtInfo['blank_rows']; $i++) { ?>
              <tr height="24"> 
                <td class="cborder2" style="border-top-style:none;border-left-style:none" align="center"><?=$prtArticle[$i]['month']?></td>
                <td class="cborder2" style="border-top-style:none" align="center"><?=$prtArticle[$i]['day']?></td>
                <td class="cborder2" style="border-top-style:none" align="center"><?=$prtArticle[$i]['article']?></td>
                <td class="cborder2" style="border-top-style:none" align="center"><?=$prtArticle[$i]['type']?></td>
                <td class="cborder2" style="border-top-style:none" align="center"><?=$prtArticle[$i]['pcs']?></td>
                <td class="cborder2" style="border-top-style:none" align="right"><?=$prtArticle[$i]['price_each']?> &nbsp;</td>
                <td class="cborder2" style="border-top-style:none" align="right"><?=$prtArticle[$i]['amount']?> &nbsp;</td>
                <td class="cborder2" style="border-top-style:none" align="right"><?=$prtArticle[$i]['tax']?> &nbsp;</td>
                <td class="cborder2" style="border-top-style:none" align="center"><?=$prtArticle[$i]['note']?></td>
              </tr>
  <?php } ?>
            </table></td>
        </tr>
        <tr> 
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr height="20"> 
                <td class="cborder2" style="border-top-style:none;border-left-style:none" align="center"><span class="ttxt2">합 계 금 액</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">현 금</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">수 표</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">어 음</span></td>
                <td class="cborder2" style="border-top-style:none" align="center"><span class="ttxt2">외상미수금</span></td>
                <td class="cborder2" style="border-top-style:none;border-bottom-style:none" rowspan="2" align="center"><span class="ttxt2">이 금액을</span> 
                  <b><?=$prtInfo['receipt']?></b> <span class="ttxt2">함</span></td>
              </tr>
              <tr height="24"> 
                <td class="cborder2" style="border-top-style:none;border-left-style:none;border-bottom-style:none" align="center"><?=$prtInfo['amount_sum']?></td>
                <td class="cborder2" style="border-top-style:none;border-bottom-style:none" align="center">&nbsp;</td>
                <td class="cborder2" style="border-top-style:none;border-bottom-style:none" align="center">&nbsp;</td>
                <td class="cborder2" style="border-top-style:none;border-bottom-style:none" align="center">&nbsp;</td>
                <td class="cborder2" style="border-top-style:none;border-bottom-style:none" align="center">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="right">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td class="ccmt2">[직인과 일련번호가 있어야 유효합니다]</td>
  <?php if ($prtInfo['tax_reissue'] > 0) { ?>
          <td align="right">[재발행: <?=$prtInfo['tax_reissue']?>회]</td>
  <?php } ?>
        </tr>
      </table>
    </td>
  </tr>
<?php } ?>

<?php if ($prtPair == 'RED') { // 공급자용 절취선  ?>
  <tr height="70"> 
    <td><hr size="1"></td>
  </tr>
<?php } ?>
</table>

<p>&nbsp;</p>
</body>
</html>
