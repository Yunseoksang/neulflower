


if (typeof optionSet1 === undefined) {
   var optionSet1 = undefined;
}


$(document).ready(function() {

  var cb = function(start, end, label) {
    console.log(start.toISOString(), end.toISOString(), label);
    $('#reportrange span').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
    //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
  }



  if (typeof optionSet1 === undefined) { 
    //dashboard_flower.php?page=flower/board/list 에서 별도의 ranges 를 설정하기 위해 체크
    //원래는 여기에서 var optionsSet1 = {...} 선언했음

    optionSet1 = {
      /*startDate: moment().subtract(29, 'days'),*/
      startDate: moment(),
      endDate: moment(),
      minDate: '2021/01/01',
      maxDate: '2030/12/31',
      dateLimit: {
        days: 1000
      },
      showDropdowns: true,
      showWeekNumbers: true,
      timePicker: false,
      timePickerIncrement: 1,
      timePicker12Hour: true,
      ranges: {
        '오늘': [moment(), moment()],
        '어제': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '지난7일': [moment().subtract(6, 'days'), moment()],
        '지난1개월': [moment().subtract(29, 'days'), moment()],
        '이번달': [moment().startOf('month'), moment().endOf('month')],
        '지난달': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      opens: 'left',
      buttonClasses: ['btn btn-default'],
      applyClass: 'btn-small btn-primary',
      cancelClass: 'btn-small',
      format: 'YYYY/MM/DD',
      separator: ' to ',
      locale: {
        applyLabel: 'Submit',
        cancelLabel: 'Clear',
        fromLabel: 'From',
        toLabel: 'To',
        customRangeLabel: '직접설정',
        daysOfWeek: ['일', '월', '화', '수', '목', '금', '토'],
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        firstDay: 1
      }
    };
  

  }


  //$('#reportrange span').html(moment().subtract(29, 'days').format('YYYY/MM/DD') + ' - ' + moment().format('YYYY/MM/DD'));
  $('#reportrange span').html(moment().format('YYYY/MM/DD') + ' - ' + moment().format('YYYY/MM/DD'));


  $('#reportrange').daterangepicker(optionSet1, cb);
  $('#reportrange').on('show.daterangepicker', function() {
    //console.log("show event fired");
  });
  $('#reportrange').on('hide.daterangepicker', function() {
    //console.log("hide event fired");
  });
  $('#reportrange').on('apply.daterangepicker', function(ev, picker) {

    //console.log("apply event fired, start/end dates are " + picker.startDate.format('YYYY/MM/DD') + " to " + picker.endDate.format('YYYY/MM/DD'));
    
    startDate=  $("#reportrange").data('daterangepicker').startDate.format('YYYY/MM/DD');
    endDate=  $("#reportrange").data('daterangepicker').endDate.format('YYYY/MM/DD');

    //console.log("startDate:" + startDate);
    //console.log("endDate:" + endDate);

    //datepicker_custome.js 에서도 startDate,endDate변수값은 새로 저장하지만 이것이 먼저 실행되어 여기에서 선언하지 않으면 click 트리거 적용할때 변경된 date로 작동안함.

  });
  $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
    //console.log("cancel event fired");
  });
  $('#options1').click(function() {
    $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
  });
  $('#options2').click(function() {
    $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
  });
  $('#destroy').click(function() {
    $('#reportrange').data('daterangepicker').remove();
  });

  //구간 검색시 시작날짜와 끝나는 날짜 선택값 가져오기
  try {
    endDate=  $("#reportrange").data('daterangepicker').endDate.format('YYYY/MM/DD');
    startDate=  $("#reportrange").data('daterangepicker').startDate.format('YYYY/MM/DD');
  
  
  } catch (error) {
    //
  }







  if (typeof daterangepicker_on !== 'undefined' && daterangepicker_on == 1){ //date rage picker on/off 기능 사용이면.

    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {

      //trigger click에 의해 기간적용을 위해 미리 미적용으로 해놓음.
      $("#btn_search_refresh").text('기간미적용중').removeClass("btn-danger").addClass("btn-dark");
      $("#btn_search_refresh").trigger("click");

    });

    $("#btn_search_refresh").click(function() {

      if($(this).hasClass("btn-danger")){
          $(this).text('날짜검색 해제상태').removeClass("btn-danger").addClass("btn-dark");
          date_apply = "off";
          toast('날짜검색 해제');

      }else{
          $(this).text('날짜별 검색 적용중').removeClass("btn-dark").addClass("btn-danger");
          date_apply = "on";

          toast('날짜별 검색 적용');
          //$("#datatable-main_filter button.datatable_btn_search").trigger("click");
          $('#datatable-main').DataTable().ajax.reload();

      }

      //날짜검색파트 선택 확인
      date_part = $("#date_part").val();
      if(date_part == undefined || date_part == ""){
          date_part = "regist_date";
      }

      //regist_date : 신고일
      //update_date : 최근변경일

    });


    $("#date_part").change(function name(params) {
        //신청일, 송금완료일 파트선택 확인
        date_part = $("#date_part").val();

    });



  }






});
