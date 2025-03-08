
            "processing": true,
            "serverSide": true,
            "destroy":true,  //reinstall 이 가능하도록 허용.

            "language" : lang_kor,
            "searching" : true,

            "pageLength": 50,//페이지당 기본 row 개수
            "lengthMenu" : [ [ 10, 20, 30, 50, 100, 500], [10, 20, 30, 50, 100, 500] ], //페이지당 볼 row 개수 선택하기 dropdown 메뉴
            //"dom": "Blfrtip", /* Bfrtip :버튼만 보이고 pagelength  사라짐, Blfrtip: Button, Length  모두 보임 */
            //"dom": '<"top"lfi>rt<"bottom"lip><"clear">',
            "stateSave": false,

            "order":[0,"desc"], //모든 페이지 기본 정열 key_column 기준 역순
            "dom": '<"top"f>rt<"bottom"lp><"clear">',

            //다운로드 버튼보이기
            //"dom": '<"top"lfip>rt<"bottom"Blip><"clear">',  
            //"buttons": [        'copy', 'excel', 'pdf'    ],
