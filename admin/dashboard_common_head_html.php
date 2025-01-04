
  <!-- Bootstrap core v3.3.6 CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- input box autocomplete ajax 연동에 필요, 신규메뉴추가시 기존메뉴명 검색할때 이용 -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


  <!-- select2 -->
  <link href="css/select/select2.min.css" rel="stylesheet">
  <!--스위치버튼 효과-->
  <link href="css/switchery/switchery.min.css" rel="stylesheet">
  <!--스위치버튼 효과 끝-->


  
  <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="css/animate.min.css" rel="stylesheet">

  <!-- Custom styling plus plugins -->
  <link href="css/custom.css?time=?ver=<?=time()?>" rel="stylesheet">
  <link href="css/icheck/flat/green.css" rel="stylesheet">

  <!--<link href="js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />-->
  <link href="js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />


  <link href="js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />


	<link rel="stylesheet" type="text/css" href="js/datatables//fixedColumns.dataTables.min.css">

  <!--- (주)늘 CSS ---->
  <link href="css/main.css?time=?ver=<?=time()?>" rel="stylesheet">
  <link href="css/modal_add.css?time=?ver=<?=time()?>" rel="stylesheet">

  <!-- jquery confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

  <!-- 라이트박스 -->
  <link rel="stylesheet" href="./contents/flower/board/css/lightbox.css" />

  <!-- 화훼관리 월별 검색시 월 선택툴 https://kidsysco.github.io/jquery-ui-month-picker/ -->
  <link href="js/monthPicker/MonthPicker.css?time=<?=time()?>" rel="stylesheet" type="text/css" />
  <link href="js/yearPicker/yearpicker.css?time=<?=time()?>" rel="stylesheet" type="text/css" />




  <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic|Nanum+Myeongjo&display=swap&subset=korean" rel="stylesheet">

  <style> p { font-family: 'Nanum Gothic', sans-serif; } </style>


  <style>
  
  @font-face {
    font-family: 'Daehan';
    font-style: normal;
    font-weight: 400;
    src: url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff2') format('woff2'), url('//cdn.jsdelivr.net/korean-webfonts/1/corps/yoon/Daehan/DaehanR.woff') format('woff');

    
  }

  .main_menu_side {

    padding-top:100px;

  }


  </style>


<script>
   var api_domain = '<?=$api_domain?>';
   //var img_resize_domain = '<?=$resize_domain?>/100x/';
   var img_resize_domain = '<?=$resize_domain?>';
   var admin_domain = "<?=$admin_domain?>";


</script>


<script>

// 데이터 테이블 Korean 세팅
var lang_kor = {
  "decimal" : "",
  "emptyTable" : "데이터가 없습니다.",
  "info" : "_START_ - _END_ (총 _TOTAL_ 건)",
  "infoEmpty" : "0건",
  "infoFiltered" : "(전체 _MAX_ 건 중 검색결과)",
  "infoPostFix" : "",
  "thousands" : ",",
  "lengthMenu" : "_MENU_ 개씩 보기",
  "loadingRecords" : "<div class='lds-hourglass loading-image'  ></div>",
  "processing" : "<div class='lds-hourglass loading-image'  ></div>",
  "search" : "검색 : ",
  "zeroRecords" : "검색된 데이터가 없습니다.",
  "paginate" : {
      "first" : "첫 페이지",
      "last" : "마지막 페이지",
      "next" : "다음",
      "previous" : "이전"
  },
  "aria" : {
      "sortAscending" : " :  오름차순 정렬",
      "sortDescending" : " :  내림차순 정렬"
  }
};
</script>



  <!--<script src="js/jquery.min.js"></script>-->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
     //$("#partner_menu").slideDown();
  });


  </script>
<script src="./js/datatables/dataTables.ajax.button.js?ver=<?=time()?>"></script> <!-- 수정,저장,취소,삭제 공통기능 정의 --> 




  <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
