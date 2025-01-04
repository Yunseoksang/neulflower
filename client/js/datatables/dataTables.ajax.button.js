var $last_clicked_td;
var last_exec_html;
var $last_clicked_tr;
$(document).ready(function(){

    //datatable.js.php 에서 add_btn_title ,add_array 는 정의되어 있음. 모달입력창의 타이틀, 나열할 목록
    //makeAddForm(add_btn_title ,add_array);   

    if(typeof modal_array !== 'undefined' && Array.isArray(modal_array)){
        //console.log("타입오브 모달어레이:"+typeof modal_array);
        for(var i=0;i<modal_array.length;i++){
            var one_modal = modal_array[i];
            makeAddFormMulti(one_modal);
        }
    }

    
    //셀 더블클릭하면 select 박스 보여지게, 숨기게.
    $(document).on("dblclick",".selectable,.editable:not(.editable_img),.editable_custom",function(){
        var $td = $(this);
        var td_mode = $(this).attr("td_mode");
        if(td_mode == "edit"){
            var target_mode = "view";
        }else{
            var target_mode = "edit";
        }
        convert_form($td,target_mode);

        var $tr = $(this).closest("tr");
        var mode = $tr.attr("mode");
        if(mode == "edit"){  //수정모드 일때는 전체 수정후 저장 버튼 눌러야 저장 실행되도록.
            return;
        }

        if($(this).find("input,select,textarea").length > 0){
            var $exec_td = $tr.find("td.exec");
            $exec_td.empty();
            $exec_td.append($("#button_sample").find(".btn_save").clone(true));
            $exec_td.append($("#button_sample").find(".btn_cancel").clone(true));
    
        }else{
            $tr.find(".btn_cancel").trigger("click"); //다른 셀에 있는 모든 폼 요소들도 보기모드로 전환
            // var $td = $tr.find("td.exec");
            // $td.empty();
            // $td.append($("#button_sample").find(".btn_modify").clone(true));
        }



    });
 



    $(document).on("dblclick","td.editable_img",function(){
        if($(this).find("input").length > 0){

            $(this).find("input").remove();
            $(this).find("button").remove();
            $(this).find("img").attr('src',$(this).attr("old_value"));
            $(this).find("a").attr('href',$(this).attr("old_value"));

        }else{

            view_img_file_attach($(this)); //파일첨부박스,저장하기 버튼 보이기
        }


        
    });

    function view_img_file_attach($tar,btn_mode){

        $tar.find("input").remove();
        $tar.find("button").remove();
        $tar.attr("old_value",$tar.find("img").attr("src"));
        $tar.append('<input type="file" name="img_file"   class="img_file" style="margin-top:5px;" >');

        var origin_column = $tar.attr("origin_column");
        if(typeof(origin_column) != "undefined" && origin_column != ""){ //cache_img_url 로 이미지 주소를 뽑아올경우 칼럼네임이 닉네임으로 설정되어 있어 저장시 오류가 나므로, origin_column 으로 원래 칼럼명을 명시해준다.
            $tar.append("<input type=hidden  name='"+origin_column+"' >");
        }else{
            var column = $tar.attr("column");
            $tar.append("<input type=hidden  name='"+column+"' >");

        }


    }
   

    //수정
    $(document).on("click",".datatable .btn_modify",function(){

        if($(this).attr("mode") != "del_forbidden"){ //삭제버튼 노출금지가 아니면.
            $(".datatable .exec").css("min-width","130px");

        }else{
            $(".datatable .exec").css("min-width","100px");

        }


        $last_clicked_td = $(this).closest("td");
        last_exec_html = $last_clicked_td.html();


        var $tr = $(this).closest("tr");
        $last_clicked_tr = $tr;
        $tr.attr("mode","edit");

        //수정모드시 input 박스들로 인해 table width가 길어지면서 스크롤바가 생기는 불편함이 있어서. 
        //강제로 몇몇 칼럼을 수정모드에서 안보이게하기.
        dataTable.columns(edit_hide_array).visible(false);
        //dataTable.columns([-1,-2,-3,-4]).visible(false);


        $tr.find("td:visible.selectable,td:visible.editable:not(.editable_img),td:visible.editable_custom").each(function(){
            //console.log("each테스트:"+$(this).attr("column"));
            convert_form( $(this),"edit");
        });


        $tr.find("td:visible.editable_img").each(function(){
            view_img_file_attach($(this));
        });
        

        var $td = $(this).closest("td");
        $td.empty();
        $td.append($("#button_sample").find(".btn_save").clone(true));
        $td.append($("#button_sample").find(".btn_cancel").clone(true));
        if($(this).attr("mode") != "del_forbidden"){ //삭제버튼 노출금지가 아니면.
            $td.append($("#button_sample").find(".btn_delete").clone(true));
        }


    });





    $(document).on("change",".img_file",function(){
       
        var $td = $(this).closest("td"); 
        var old_img_width = $td.find("img").css("width");
        if(window.FileReader){ //image 파일만 
            if (!$(this)[0].files[0].type.match(/image\//)) 
                return; 
            var reader = new FileReader(); 
            reader.onload = function(e){ 
                var src = e.target.result; 

                if($td.find("a").length > 0){
                    $td.find("a img").attr("src",src).css("width",old_img_width);
                    $td.find("a").attr("href",src);
                }else{
                    $td.find("input[type='file']").before("<a href='"+src+"'  data-lightbox='' data-title=''    ><img style='width:"+$td.find("img").css("width")+";' src='"+src+"'  > </a>");

                }

            } 
            
            reader.readAsDataURL($(this)[0].files[0]); 
        }else{
            $(this)[0].select(); 
            $(this)[0].blur(); 
            var imgSrc = document.selection.createRange().text; 

            if($td.find("a").length > 0){
                $td.find("a").attr("href",imgSrc);
            }else{
                $td.find("input[type='file']").before("<a href='"+imgSrc+"'  data-lightbox='' data-title='' ><img style='width:"+old_img_width+";' src='"+imgSrc+"' > </a>");
                
                var img = $td.find("a img");
                img.css("width",old_img_width);
                img[0].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enable='true',sizingMethod='scale',src=\""+imgSrc+"\")"; 

            }
        }
    });





    //취소
    $(document).on("click",".datatable .btn_cancel",function(){

        $(".datatable .exec").css("min-width","50px");


        $last_clicked_td = $(this).closest("td");
        var $tr = $(this).closest("tr");

        $tr.removeAttr("mode");
        for(var i=0;i<edit_hide_array.length;i++){
            dataTable.column(edit_hide_array[i]).visible(true);
        }


        //recover($tr);
        //form 박스 원상복귀
        $tr.find("td.editable,td.selectable,td.editable_custom").each(function(){
            convert_form($(this),"view");
        });
        $tr.find("td.editable_img").each(function(){
            var old_value = $(this).attr("old_value");
            $(this).find("input[type='file'],input[type='hidden'],button").remove();
            $(this).find("a").attr("href",old_value);
            $(this).find("img").attr("src",old_value);
        });



        var $td = $(this).closest("td");
        $td.empty();
        //$td.append($("#button_sample").find(".btn_modify").clone(true));
        $td.html(last_exec_html);

    });

    //삭제
    $(document).on("click",".datatable .btn_delete",function(){
        $last_clicked_td = $(this).closest("td");

        var $tr = $(this).closest("tr");

        var key_name  =  $(this).closest("table").attr("key_name");
        var key_value = $tr.attr(key_name);


        $.confirm({
            title: '', //타이틀
            content: '삭제할까요?',  //메세지
            closeIcon: true, //우측상단 닫기버튼 보일지 여부
            closeIconClass: 'fa fa-close',  //닫기 버튼 아이콘
            type: 'red', //확인창 색상(옵션)
            boxWidth: '300px', //확인창 width
            useBootstrap: false,  //width 적용할때 false 로 해줘야 적용됨.

            
            buttons: {
                "확인": function () {
                    deleteRow(key_name,key_value);
                },
                "취소": function () {
                    //$.alert('Canceled!');
                },

            }
        });

    });


    
    function deleteRow(key_name,key_value){
        
        //var $tr = $(this).closest("tr");
        // var key_name  =  $(this).closest("table").attr("key_name");
        // var key_value = $tr.attr(key_name);

        var ajax_string= "mode=delete&table_name="+table_name;
        if(key_value != undefined){
            ajax_string += "&key_name="+key_name+"&key_value="+key_value;
        }

        var url = ajax_update_url;  //delete url
        var str = ajax_string;



        console.log("API 호출 url: " + url);
        console.log("API 호출 parameter: " + str);


        $.ajax( { 
                type: "POST",url: url,data: str,cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;

                    console.log("API 호출 url: " + url);
                    console.log("API 호출 parameter: " + str);

                    // $(".right_col").find("div.delete_query").remove();
                    // $(".right_col").find("div.page-title").before("<div class='delete_query'><span style='color:red;'>delete 쿼리:</span>"+obj.query_delete+"</div>");



                    
                    if(result_status == 1)
                    {
                        $("#datatable-main tr["+key_name+"='"+key_value+"']").remove();
                        toast('삭제되었습니다.');

                        for(var i=0;i<edit_hide_array.length;i++){
                            dataTable.column(edit_hide_array[i]).visible(true);
                        }
            
                    }else{
                        var msg = result.msg;
                        //console.log(msg);
                    }
                }, //success
                error : function( jqXHR, textStatus, errorThrown ) {
                    alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
                }
        }); //ajax
    }


    

    //저장
    $(document).on("click",".datatable .btn_save",function(){

        $last_clicked_td = $(this).closest("td");

        var $tr = $(this).closest("tr");
        $tr.find("td.editable_img").has("input[type='file']").each(function(index){

            $(this).find("input[type='file']").each(function(){
                if($(this).val() != ""){

                    var $td = $(this).closest("td");
                    var files = $(this)[0].files[0];
                    saveImageOnly($td,files);
                }
            });
        });
        updateRow($(this),"tr");  // 변환된 폼값들을 nested json 으로 변환하여 ajax 전송

        $last_clicked_td.html(last_exec_html);
    });


    
    //th 선택필터 선택된 값 세팅
    $(document).on("change","th select.select_filter",function(event) {
        event.stopPropagation();
        select_filter = "";
        $("th select.select_filter").each(function(){
            var this_val = $(this).val();
            //var base_option_value = $(this).attr("base_option_value");
            var filter_name = $(this).attr("name");

            if(this_val == ""){
               //
            }else{
                if(select_filter == ""){
                    select_filter = filter_name+":"+this_val;
                }else{
                    select_filter += "|"+filter_name+":"+this_val;
                }
            }
        });
        dataTableDraw();
    });


    //select box change시 즉시 저장
    $(document).on("change",".datatable tr select:not(.temp)",function(){  
        //포함했다가 다시제외(input을 change 이벤트에서 왜 바로 저장하게 했는지 기억이 안나서) .datatable tr input:not(.temp), .datatable tr textarea:not(.temp)


        var $tr = $(this).closest("tr");
        var mode = $tr.attr("mode");
        if(mode == "edit"){  //수정모드 일때는 전체 수정후 저장 버튼 눌러야 저장 실행되도록.
            return;
        }

        if($(this).hasClass("direct_save")){ //select 태그에 direct_save 클래스를 가지고 있을때만, 즉시 저장 실행
            updateRow($(this),"td");


        }else{ //즉시 저장이 아니므로 마지막 셀에 저장 버튼 보여주기, 삭제버튼은 없음.
            var $td = $tr.find("td.exec");
            $td.empty();
            $td.append($("#button_sample").find(".btn_save").clone(true));
            $td.append($("#button_sample").find(".btn_cancel").clone(true));
        }

    });


    //select box change시 즉시 저장
    $(document).on("keyup",".datatable tr input:not(.temp), .datatable tr textarea:not(.temp)",function(e){

        var $tr = $(this).closest("tr");
        if(e.keyCode == 13) {
            if($(this).hasClass("direct_save")){ //select 태그에 direct_save 클래스를 가지고 있을때만, 즉시 저장 실행
                $tr.find(".btn_save").trigger("click");
            }
        }
    });




    //button  on,off 클릭시 즉시 수정기능
    $(document).on("click","button.button_onoff",function(e){
        updateRow($(this),"td");
   });



   //vew 그룹 버튼 클릭
   $(document).on("click","button.btn_view_group",function(e){

        // 목록중에 하나만 기본 선택되어 보여지게.
        $(this).parent().find("button").removeClass("btn-success");
        $(this).addClass("btn-success");



        view_group = $(this).attr("view_group");
        if(view_group_array[view_group][1] == "all"){ //전체보기
            dataTable.columns(all_columns_index).visible(true,true);
        }else{ //그룹별 보기
            var show_arr = view_group_array[view_group][3]; //3번째에 index 기준 visible칼럼 목록 생성되어 있음
            var hide_arr = view_group_array[view_group][4]; //4번째에 index 기준 invisible칼럼 목록 생성되어 있음

            // //console.log('show_arr0:'+show_arr);
            // //console.log('hide_arr0:'+hide_arr);
            dataTable.columns(show_arr).visible(true,true);
            dataTable.columns(hide_arr).visible(false,true);
        }



   });



    function updateRow($clickedObj,mode){ //mode == tr 일때만 마지막 button cell 복구 작업 수행.
        
        var $this = $clickedObj;
        var $tr = $this.closest("tr");

        //$tr.removeAttr("mode");
        var key_name  =  $this.closest("table").attr("key_name");
        var key_value = $tr.attr(key_name);
        var inputArray = [];

        var $tar;
        if(mode == "tr"){
           $tar = $tr.find("td.editable,td.selectable,td.editable_custom,td.editable_img");
        }else if(mode == "td"){
           $tar = $this.closest("td");
           if($this.hasClass("button_onoff")){
               
                var val = $this.val();
                var on_value = $this.attr("on_value");
                var on_text = $this.attr("on_text");

                var off_value = $this.attr("off_value");
                var off_text = $this.attr("off_text");

                if(val == on_value){
                    var next_val = off_value;
                    var next_text = off_text;
                }else{
                    var next_val  = on_value;
                    var next_text = on_text;
                }

                var inputObject = {};

                var column_name = $this.attr("name");
                var new_value = next_val;
                    
                inputObject.name = column_name;
                inputObject.value = new_value;

                inputArray.push(inputObject);
           }
        }
        
        
        $tar.find("input:not([type='file']),textarea,select").not(".temp").each(function(){
            $this_td =$(this).closest("td");
            
            if($(this).attr("type") == "hidden" && $(this).val() == ""){
                return true; //continue 역할. return false는 break 역할.
            }

            var inputObject = {};
            var new_value = $(this).val();

            var column_name = $(this).attr("name");
            var old_value = $(this).closest("td").attr(column_name);

            var join_table_name = $(this).closest("td").attr("join_table_name");  // 다른 테이블에 저장할때
            var join_table_key_name = $(this).closest("td").attr("join_table_key_name");// 다른 테이블에 저장할때
            var join_table_key_value = $(this).closest("td").attr("join_table_key_value");// 다른 테이블에 저장할때

            // //console.log("column_name:"+column_name);
            // //console.log("old_value:"+old_value);


            if(typeof(old_value) != "undefined" && old_value != ""){
                if(new_value.replace(',','') == old_value.replace(',','')){ //숫자는 컴마제외한값 비교, 값의 변화가 없으면.
                    return true; //continue;
                }
            }


            inputObject.name = column_name;
            inputObject.value = new_value;
            if(typeof(join_table_name) != 'undefined' && typeof(join_table_key_name) != 'undefined' && typeof(join_table_key_value) != 'undefined'  ){
                if(join_table_name != null && join_table_key_name != null && join_table_key_value != null){
                    inputObject.join_table_name = join_table_name;
                    inputObject.join_table_key_name = join_table_key_name;
                    inputObject.join_table_key_value = join_table_key_value;
                }
            }

            inputArray.push(inputObject);
                
            $this_td.attr(column_name+"_new",new_value); //여러폼 요소용.
            //$this_td.attr("new_value",new_value); //단일 폼용.

            if($(this).get(0).tagName == "SELECT" && $(this).attr("same") == "N"){ //select 박스인데 value 와 text 가 다른경우
                var new_value_text = $(this).find("option:selected").text();
                $this_td.attr(column_name+"_text_new",new_value_text); 

            }


            // if($(this).get(0).tagName == "SELECT" && $(this).is('[virtual_name]')){ //select 박스인데 value 와 text 가 다른경우
            //     var new_virtual_value = $(this).find("option:selected").text();
            //     $this_td.attr("new_virtual_value",new_virtual_value); //
            // }
 

        });









        if(inputArray.length == 0){
            //console.log('선택내역 없음');


            var $td = $tr.find(".exec .btn_cancel").trigger("click");
            // $td.empty();
            // $td.append($("#button_sample").find(".btn_modify").clone(true));
            return;

        }



        /** 저장할 데이터들을 nested json 형태로 변환하여 전송 */
        var jsonObj= {};
        jsonObj.mode = "update";
        jsonObj.table_name = table_name; //table_name 은 list.php 에서 정의

        jsonObj.key_name = key_name;
        jsonObj.key_value = key_value;
        jsonObj.inputArray = inputArray;

        var jsonData = JSON.stringify(jsonObj);

        var url = ajax_update_url;
        //console.log("url:"+url+"\n"+jsonData);
        //return;
        //console.log("ajax_update_url 최종:"+ajax_update_url);


        console.log("API 호출 url: " + ajax_update_url);
        console.log("API 호출 parameter: " + jsonData);

        $.ajax( { 
                type: "POST",url: url,data: {jsonDataPHP : jsonData },cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){


                    
                    var result_status = result.status;
                    var obj = result.data;
                    //console.log("update 쿼리:"+obj);
                    console.log("API 실행 쿼리: " + obj.query_update);


                    // $(".right_col").find("div.update_query").remove();
                    // $(".right_col").find("div.page-title").before("<div class='update_query'><span style='color:red;'>update 쿼리:</span>"+obj.query_update+"</div>");



                    if(result_status == 1)
                    {



                        //var obj = result.data;
                        //console.log("json 결과보기:"+JSON.stringify(result));

                        var $exec_td = $tr.find("td.exec");
                        $exec_td.empty();
                        $exec_td.append($("#button_sample").find(".btn_modify").clone(true));
                        $exec_td.removeAttr("mode");

                        if(mode == "td"){
                            if($tar.hasClass("editable_img")){
                                $tar.find("input").remove();
                                toast('저장되었습니다.');

                            }else if($this.hasClass("button_onoff")){
                                $clickedObj.val(next_val);
                                $clickedObj.text(next_text);

                                var btn_color = $clickedObj.attr("btn_color");
                                if(next_val == "on"){
                                
                                    $clickedObj.removeClass("btn-default");
                                    $clickedObj.addClass(btn_color);

                                }else{
                                    $clickedObj.removeClass(btn_color);
                                    $clickedObj.addClass("btn-default");
                                }
                                toast_fast('저장되었습니다.');

                            }else{
                                convert_form($tar,"view");
                                toast('저장되었습니다.');

                            }
                        }else if(mode == "tr"){ //row별 수정하기 일때만.

                            $tr.removeAttr("mode");

                            $tr.find("td.editable,td.selectable,td.editable_custom,td.editable_img").each(function(){
                                if($(this).hasClass("editable_img")){
                                    $(this).find("input").remove();
                                }else{
                                    convert_form($(this),"view");
                                }
                            });

                            var $td = $this.closest("td");
                            $td.empty();
                            $td.append($("#button_sample").find(".btn_modify").clone(true));

                            //수정모드로 변하며 감춰진 칼럼 다시 보이기
                            for(var i=0;i<edit_hide_array.length;i++){
                                dataTable.column(edit_hide_array[i]).visible(true);
                            }
                            toast('저장되었습니다.');
                        }



                        //업데이트 이후 개별적인 후속 작업이 필요한 경우 실행됨. updateRow_afterWork 및 함수 updateRow_after 는 list.php 에 정의함. 제안시작: user>membership>list.php
                        if(typeof(updateRow_afterWork) !== 'undefind'){
                            try{
                               updateRow_after(result,$tr);

                            }catch(e){
                               //
                            }

                        }




                    }else{
                        var msg = result.msg;
                        //console.log(msg);
                    }
                }, //success
                error : function( jqXHR, textStatus, errorThrown ) {
                    alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
                }
        }); //ajax
        
    }



    function saveImageOnly($td,file){
        var fd = new FormData();
        var files = $td.find("input[type='file']")[0].files[0];
        fd.append('files',files,files.name);

        //console.log('api_domain:'+api_domain);
        $.ajax({
            url: api_domain+'/api/file/upload_file',
            type: 'post',
            async: false,  // 이미지를 순차적으로 모두 저장한 이후에 row 의 텍스트 데이터를 저장하기 위해 비동기가 아닌 동기 방식 채택!!
            data: fd,
            contentType: false,
            processData: false,
            success: function(result){
                if(result.status == 1){
                    var new_img_url = result.data.upload_file_url;
                    var origin_column = $td.attr("origin_column");
                    if(typeof(origin_column) != "undefined" && origin_column != ""){
                        var target_column = origin_column;

                    }else{
                        var target_column = $td.attr("column");

                    }
                    $td.find("input[name='"+target_column+"']").val(new_img_url);
                    $td.find("input[type='file'],button").remove();


                }else{
                    alert('파일 업로드에 실패하였습니다.');
                }
            },
        });
    }





    //select2 변경시 이벤트 호출  //설명 https://select2.org/programmatic-control/events#preventing-events
    // $(document).on('select2:select','.select2_single', function (e) {
    //     var data = e.params.data;
    //     var $sel = $(this).closest("div.form-group").find("select");
    //     //console.log(data.text);
    //     //console.log(data.id);
    // });





    //추가

    $(document).on("click",".btn_add",function(e){
        //e.preventDefault();

        //console.log('order2');

        openModal("new");
    });


    //파일첨부 등록시 파일 선택했을때 이미지 미리보기
    $(document).on("change","#modal_add input:file",function(){
        //파일첨부 후 파일명이 filestyle input box에 안 보일때.
        // var $tar =$("input[type='text'][name='"+$(this).attr("name")+"']");
        // if($tar.val() == ""){
        //    $tar.val($(this).val());
        // }

  
        var $td = $(this).closest(".form-group"); 
        $td.find("a").remove();
        var $img = "<a href='' data-lightbox='' data-title=''><img class='attached_img' src='./images/picture2.jpg' style='width:181px; margin-bottom:5px;'></a>";
        $td.prepend($img);



        //$td.css("border","3px solid red");
        if(window.FileReader){ //image 파일만 
            if (!$(this)[0].files[0].type.match(/image\//)) 
                return; 
            var reader = new FileReader(); 
            reader.onload = function(e){ 
                var src = e.target.result; 
                $td.find("a img").attr("src",src);
                $td.find("a").attr("href",src);
            } 
            
            reader.readAsDataURL($(this)[0].files[0]); 
        }else{
            $(this)[0].select(); 
            $(this)[0].blur(); 
            var imgSrc = document.selection.createRange().text; 
            var img = $td.find("a img");

            $td.find("a").attr("href",imgSrc);

            img[0].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enable='true',sizingMethod='scale',src=\""+imgSrc+"\")"; 
        }

    });


    


    //추가하기 -> 저장
    $(document).on("click","#modal_add .btn_submit",function(e){
        e.preventDefault();
        var modal_name = $("#modal_add .form_area").attr("modal_name");
        var submit_url = $("#modal_add .form_area").attr("submit_url");
        var submit_table_name= $("#modal_add .form_area").attr("submit_table_name");
        var submit_key_column_name = $("#modal_add .form_area").attr("submit_key_column_name");
        var modal_submit_function = $("#modal_add .form_area").attr("modal_submit_function");


        var temp = 0 ;
        var str = "table_name="+submit_table_name+"&key_column_name="+submit_key_column_name; //table_name 은 list.php 에서 정의;
        
        //저장 프로세스
        //var url = ajax_insert_url;



        $("#modal_add div.input").filter("[req='1']").each(function(index){
            if($(this).find("input,select,textarea").val() == ""){
                $(this).find("input,select,textarea").addClass("input_error_border");
                $(this).find("label").css("color","red");
                temp = 1;
                return false; //break
            }else{
                $(this).find("input,select,textarea").removeClass("input_error_border");
                $(this).find("label").css("color","");
            }
        });


        if(temp == 1){
            toast('필수항목을 입력해주세요');
            return;
        }


        var uploaded_file_url = "";
        var upload_input_name = "";
        //파일 업로드 후 파일 주소 받아오기
        if( $("#modal_add input[type='file']").length > 0 && $("#modal_add input[type='file']").val() != ""){

            upload_input_name = $("#modal_add input[type='file']").attr("name");
            upload_input_directory = $("#modal_add input[type='file']").attr("directory");

            
            var img_upload_success = 1;

            
            var fd = new FormData();
            var files = $("#modal_add input[type='file']")[0].files[0];

            fd.append('files',files,files.name);
            fd.append('inputName',upload_input_name);
            fd.append('directory',upload_input_directory);


            //form 데이터 로그 찍어보기
            // for (var pair of fd.entries()) {
            //     console.log(pair[0]+ ', ' + pair[1]); 
            // }


            //console.log('파일업로드 API : '+ api_domain+'/api/file/upload_file');



            $.ajax({
                url: api_domain+'/api/upload_file.php',
                type: 'post',
                data: fd,
                async: false,
                contentType: false,
                processData: false,
                success: function(result){

                    var obj = jQuery.parseJSON(result);
                    if(obj.status == 1){
                        uploaded_file_url = obj.data.upload_file_url;
                        console.log(uploaded_file_url);
                    }else{

                        img_upload_success = 0;
                        console.log(obj.msg);
                        alert('파일 업로드에 실패하였습니다.');
                    }
                }
            });

            if(img_upload_success == 0){
                return;
            }
        }

  

        //serialize()
        $("#modal_add div.input").each(function(index){
            if($(this).find("input[type='file']").length == 0){
                var $box = $(this).find("input,select,textarea").eq(0);
                var box_name = $box.attr("name");
                var box_type = $box.attr("type");

                if(box_type == "radio"){
                    var box_val = $(":input:radio[name='"+box_name+"']:checked").val();
                }else{
                    var box_val = $box.val();
                }

                if(str == ""){
                    str = box_name+"="+box_val;
                }else{
                    str += "&"+box_name+"="+box_val;
                }
            }

        });

        if(upload_input_name != "" && uploaded_file_url != ""){
            str += "&"+upload_input_name+"="+uploaded_file_url;
        }



        console.log("API 호출 url: " + submit_url);
        console.log("API 호출 parameter: " + str);


        $.ajax( { 
                type: "POST",url: submit_url,data:str,
                async: false,
                cache: false,dataType: "json", beforeSend: function() {},context: this,
                success: function(result){
                    
                    var result_status = result.status;



                    if(result_status == 1)
                    {
                        var obj = result.data;
                        //var new_insert_idx = obj[key_column_name];
                        console.info("result data row:" + JSON.stringify(obj));
                        // $(".right_col").find("div.insert_query").remove();
                        // $(".right_col").find("div.page-title").before("<div class='insert_query'><span style='color:red;'>insert 쿼리:</span>"+obj.query_insert+"</div>");


                        $('#modal_add').toggleClass('is-visible');
                        toast('저장되었습니다.');


                        location.reload();
                        return;

                        var param = result.param;  //param 형태는 "idx=123&type=c&name=이름" 의 serialize()타입

                        if(typeof modal_submit_function !== 'undefined'){
                            if(typeof param !== 'undefined'){
                                doAfterModalSubmit(modal_name,param);
                            }else{
                                doAfterModalSubmit(modal_name,"");
                            }
                        }





                        if( $(".datatable").find("tbody tr").length > 0){
                            var $tr = $(".datatable").find("tbody tr").first().clone(true);
                            $tr.attr(obj['key_column_name'],obj[key_column_name]);
                            $tr.find("td").each(function(index) {
                                var column = $(this).attr("column");
                                if(obj[column] != undefined){
                                    $(this).attr(column,obj[column]).html(obj[column]);

                                    if(obj.replace_columns != undefined){
                                        for(var i=0;i<obj.replace_columns.length;i++){
                                            if(obj.replace_columns[i].origin_column == column){
                                                $(this).attr(column,obj[column]).html(obj[obj.replace_columns[i].replace_column]);
                                                $(this).attr(column+"_text",obj[obj.replace_columns[i].replace_column]);
                                                break;
                                            }
                                        }
                                    }else{
                                        $(this).attr(column,obj[column]).html(obj[column]);
                                    }
                                }else{
                                    //$(this).html("");
                                }
                            });

                            $(".datatable").find("tbody").prepend($tr);

                        }else{
                            var result_tr = "<tr "+obj['key_column_name']+"='"+obj[key_column_name]+"'>";

                            $(".datatable").find("thead th").each(function(index) {
                                var column = $(this).attr("column");
                                if(obj[column] != undefined){


                                    if(obj.replace_columns != undefined){

                                        var renum = 0;
                                        for(var i=0;i<obj.replace_columns.length;i++){
                                            if(obj.replace_columns[i].origin_column == column){
                                                result_tr += "<td class='"+column+"' column='"+column+"' "+column+"='"+obj[column]+"'  "+column+"_text='"+obj[obj.replace_columns[i].replace_column]+"'>"+obj[obj.replace_columns[i].replace_column]+"</td>";
                                                renum++;
                                                break;
                                            }
                                        }

                                        if(renum == 0){
                                            result_tr += "<td class='"+column+"' column='"+column+"' "+column+"='"+obj[column]+"'>"+obj[column]+"</td>";

                                        }
                                    }else{
                                        result_tr += "<td class='"+column+"' column='"+column+"' "+column+"='"+obj[column]+"'>"+obj[column]+"</td>";

                                    }


                                }else{
                                    result_tr += "<td class='"+column+"' column='"+column+"' "+column+"=''></td>";
                                }
                            });



                            result_tr += "</tr>";
                            console.info("new row html:" + result_tr);
                            $(".datatable").find("tbody").prepend(result_tr);
                        }


                        
            
                    }else{
                        var msg = result.msg;
                        //console.log(msg);
                    }
                }, //success
                error : function( jqXHR, textStatus, errorThrown ) {
                    alert( "jqXHR.status: " + jqXHR.status + "\n"+"jqXHR.statusText: " +jqXHR.statusText + "\n" + "jqXHR.responseText: " +  jqXHR.responseText + "\n" + "jqXHR.readyState:" + jqXHR.readyState + "\n" );
                }
        }); //ajax

    });



    //추가하기 -> 취소
    $(document).on("click","#modal_add .btn_cancel",function(e){
        e.preventDefault();
        $('#modal_add').toggleClass('is-visible');
    });
    
    
    
    //swtich 버튼 변화 주었을때,특히 아무것도 선택되지 않은 초기 모드로 전환 가능한 기능.
    $(document).on("click","div.switch label",function(event) {
        if($(this).has("input[type='radio']").length){

            if(typeof $(this).attr("selectedClass") !== 'undefined'){
                var selectedClass = $(this).attr("selectedClass");
            }else{
                var selectedClass = "btn-success";
            }

            if($(this).hasClass("active")){
                var $cgroup = $(this).closest(".btn-group").clone(false);
                if($cgroup.hasClass("deselectable")){ //둘다 선택안할수 있는 모드
                    $cgroup.find("label").removeClass("active").removeClass(selectedClass).addClass("btn-default");
                    $(this).closest(".btn-group").html($cgroup.html());
                    $(this).find("input[type='radio']").removeAttr("checked");
                }else{
                    //둘중하나는 선택해야 하는 radio 모드 인데, 이미 선택되어 있으므로 아무 변화 없음.
                    $(this).find("input[type='radio']").attr("checked","checked");
                    return;
                }
            }else{
                $(this).siblings().removeClass("active").removeClass(selectedClass).addClass("btn-default");
                $(this).siblings().find("input[type='radio']").removeAttr("checked");
                $(this).addClass("active").addClass(selectedClass);
                $(this).find("input[type='radio']").attr("checked","checked");

            }
        }
    });




    //원인은 모르겠지만 checkbox 가 $(document).on("click",".filter_box .filter_element" 에서는 인식이 되지 않아서 별도로 처리해줌
    $(".filter_box .filter_element").click(function(e) {  
       if($(this).attr("type") != "radio"){
           filter_adjust($(this));
       }
    });
    
    $(document).on("click",".filter_box .filter_element",function(e) {
        filter_adjust($(this));
    });

    //칼럼별 키워드 동시 검색 기능
    $(document).on("keyup","input.column_search_input",function(e) {
        filter_adjust_multi_columns($(this));
    });

    //칼럼별 키워드 동시 검색 기능
    $(document).on("dblclick","input.column_search_input",function(e) {
         var enterKey = $.Event( "keypress", { which: 13 } );
        $(this).val("").keyup().trigger(enterKey);

        //$(this).closest("th").find(".search_icon").remove();

    });



    //추가하기 -> 취소
    $(document).on("click","tr.temp .btn_close",function(e){
        $(this).closest("tr").find("td").eq(0).empty();

        var $tr = $(this).closest("tr");
        setTimeout(function(){
        // 1초 후 작동해야할 코드
                $tr.remove();
        }, 300);


        

    });
    

    $(document).on("change","#datatable-main_filter select[name='search_column']",function(e) {
        var num = $(this).find("option").index($(this).find("option:selected"));
        keyword_query = setKeywordQuery(num);
    });



    $(document).on("mouseenter","td:has(.filter_anchor)",function () {
       $(this).find(".fa").show(); 
    }).on("mouseleave","td:has(.filter_anchor)",function () {
        $(this).find(".fa").hide(); 
    });


    $(document).on("click",".filter_anchor:not()",function () {
        var column = $(this).closest("td").attr("column");
        var e = $.Event( "keypress", { which: 13 } );
        if(typeof($(this).find(".fa-filter").attr("search_column_name")) !== 'undefined' ){
            var search_column_name = $(this).find(".fa-filter").attr("search_column_name");
            var search_column_place_holder = $(this).find(".fa-filter").attr("search_column_place_holder");

            $("#datatable-main").find("thead th[column='"+column+"'] .search_icon").remove();
            $("#datatable-main").find("thead th[column='"+column+"'] span[column='"+search_column_name+"']").append("<i class='fa fa-filter search_icon color-info' style='margin-left:3px;'></i>");
            $("#datatable-main").find("thead th[column='"+column+"'] input").attr("name",search_column_name).attr("placeholder",search_column_place_holder+" 검색").val($(this).find("a").text()).keyup().trigger(e);

        }else{
            $("#datatable-main").find("thead th[column='"+column+"'] input").val($(this).find("a").text()).keyup().trigger(e);

        }
    });


 
    //th 에서 공백 눌렀을때만 sorting 기능 작동하게. 타이틀 또는 input 박스 눌렀을때 특정 기능 수행해야 하는 경우는 sorting 안되게
    $('#datatable-main thead th span').click(function(e) {
        //e.stopPropagation();  //이걸로는 작동 안됨
        if(typeof($(this).attr("column")) != "undefined"){
            $(this).closest("th").find(".search_icon").remove();
            $(this).append("<i class='fa fa-filter search_icon color-info' style='margin-left:3px;'></i>");
            var column = $(this).attr("column");
            var place_holder = $(this).text();
            $(this).closest("th").find("input").attr("name",column).attr("placeholder",place_holder+" 검색");
        }
        return false;
    });

    //th 에서 공백 눌렀을때만 sorting 기능 작동하게.
    $('#datatable-main thead th input').click(function(e) {
        //e.stopPropagation();//이걸로는 작동 안됨
        return false;
    });


    // $(document).on("click","#datatable-main thead th input",function(e) {
    //     //e.stopPropagation();//이걸로는 작동 안됨

    //     return false;

    // });

    // $(document).on("click","#datatable-main thead th span",function(e) {
    //     //e.stopPropagation();  //이걸로는 작동 안됨
    //     if(typeof($(this).attr("column")) != "undefined"){
    //         $(this).closest("th").find(".search_icon").remove();
    //         $(this).append("<i class='fa fa-filter search_icon color-info' style='margin-left:3px;'></i>");
    //         var column = $(this).attr("column");
    //         var place_holder = $(this).text();
    //         $(this).closest("th").find("input").attr("name",column).attr("placeholder",place_holder+" 검색");
    //     }
    //     return false;
    // });


});


// keyword_query = setKeywordQuery(0);

// function setKeywordQuery(num){
//     //    array_push($keyword_column_array,["브랜드명","brand_name","like","join",["brand"],["brand_idx"]]);
//     var keyword_info = keyword_column_array[num];
//     if(keyword_info[1] != "join"){
//         switch (keyword_info[2] ) {
//             case "like":
//                 keyword_query = " select * from " + table_name +" where " + keyword_info[1] + " like '%#keyword#%' ";
//                 break;
//             default:
//                 keyword_query = " select * from " + table_name +" where " + keyword_info[1] +" "+ keyword_info[2]+" '#keyword#' ";
//                 break;
//         }
//     }else{ //join
//         var join_info = keyword_info[2];
//         switch (join_info[3] ) {
//             case "like":
//                 keyword_where_query = join_info[1] + " like '%#keyword#%' ";
//                 break;
//             default:
//                 keyword_where_query = join_info[1] +" "+ join_info[3]+" '#keyword#' ";
//                 break;
//         }

//         keyword_query = "select q.* from (select "+join_info[2]+"," + join_info[1] + " from " + join_info[0] + " where " + keyword_where_query + ") p ";
//         keyword_query += " left join " + table_name + " q on p."+join_info[2]+"=q."+join_info[2] + " ";
//     }
//     return keyword_query;
// }





//칼럼별 키워드  동시 검색

function filter_adjust_multi_columns($obj){
    multi_column_search_string = "";
    $("thead th input.column_search_input").each(function(index){
        if($(this).val() != ""){
            //console.log("검색값:" + $(this).val());
            if(multi_column_search_string == ""){
                multi_column_search_string += $(this).attr("name") + "=" + $(this).val();
            }else{
                multi_column_search_string += "/" + $(this).attr("name") + "=" + $(this).val();
            }

            //해당 칼럼 검색 방식이 like 인지 "=" 인지 저장
            for(var i=0;i<keyword_column_array.length;i++){
                var keyword_info = keyword_column_array[i];
                if(keyword_info[1] == $(this).attr("name")){
                    if(keyword_info[2] == "="){
                        multi_column_search_string += "=equal"; // like 가 아닌 정확히 같은것만 검색하는 칼럼은 =equal 을 붙여준다.
                    }
                }
            }

        }
    });
}





function filter_adjust($obj){
    

    if($obj.find("input[type='checkbox']").attr("name") == "all"){ //all 버튼 클릭

        if(typeof $obj.find("input[type='checkbox']").attr("hot_click") === "undefined"){ //all 버튼 직접 클릭

            //기존에 check 되어있는가? => 지금 클릭했을때는 unckeck 됨
            if($obj.find("input[type='checkbox']").is(":checked")){


            }else{ //기존에 uncheck => check됨.
                //multi_search_query = "";

                //모든 라디오버튼 초기화
                $(".filter_box").find(".btn-group").each(function(index){
                    var $cgroup = $(this).clone(false);
                    $cgroup.find(".filter_element").removeClass("active").removeClass("btn-success").addClass("btn-default");
                    $(this).html($cgroup.html());
                });

                //모든 체크박스 초기화.(전체 버튼 제외)
                $(".filter_box").find("input[type='checkbox']").not("[name='all']").each(function(index){
                    
                    if($(this).is(":checked")){  //기존에 체크되어 있으면 모두 해제
                        $(this).closest("label").trigger("click");
                    }
                    
                });

            }

        }else{ //all 버튼이 trigger에 의해 click : 무조건 all 버튼이 해제되는 상황임.

            $obj.find("input[type='checkbox']").removeAttr("hot_click"); //triiger 전에 hot_click 이 설정되었고, 프로세스를 탔으므로 해제
            //전체를 선택하면 나머지 체크박스, 라디오버튼 모두 선택되지 않은 상태로 초기화.
            return; // return 해서 나가야 함.
            
        }
    }else{ //클릭된 것이 all 버튼이 아니라면
        // var $all_btn = $(".filter_box").find("input[type='checkbox']").filter("[name='all']");

        // if($all_btn.is(":checked")){
        //     window.alert('all 체크되어 있음 => 체크해제');
        //     $(".filter_box input[name='all']").attr("hot_click","1").closest("label").trigger("click");
        // }else{
        //     window.alert('all 체크안되어 있음.');

        // }


        if($obj.has("input[type='checkbox']").length){

            $obj.find("input[type='checkbox']").attr("hot_click","1");

            //checkbox 가 포함된 경우에만 처리. (라디오 버튼은 위쪽에서 처리. document .on 에서)
            if($obj.find("input[type='checkbox']").is(":checked")){ //기존에 체크되어 있으면
                //단순 해제
            }else{ //기존 unchecked => checked
                if($(".filter_box input[name='all']").is(":checked")){ //전체 박스가 선택되어 있으면 전체박스 선택 해제
                    //all 체크박스가 트리거에 의해 수행되었음을 표시하고 클릭처리
                    $("input[name='all']").attr("hot_click","1").closest("label").trigger("click");
                }
            }
        }




        if($obj.has("input[type='radio']").length){ //라디오버튼의 각 선택버튼을 눌렀을때

            if($obj.hasClass("active")){
               var $cgroup = $obj.closest(".btn-group").clone(false);
               if($cgroup.hasClass("deselectable")){ //둘다 선택안할수 있는 모드
                    $cgroup.find(".filter_element").removeClass("active").removeClass("btn-success").addClass("btn-default");
                    $obj.closest(".btn-group").html($cgroup.html());
               }else{
                    //둘중하나는 선택해야 하는 radio 모드 인데, 이미 선택되어 있으므로 아무 변화 없음.
                    //return;
               }
            }else{
                
                if($(".filter_box input[name='all']").is(":checked")){
                    //all 체크박스가 트리거에 의해 수행되었음을 표시하고 클릭처리
                    $("input[name='all']").attr("hot_click","1").closest("label").trigger("click");
                }

                $obj.siblings().removeClass("active").removeClass("btn-success").addClass("btn-default");
                $obj.addClass("active").addClass("btn-success");
            }

        }

    }
    //console.log("multi_search_query:"+multi_search_query);

    makeMultiSearchQuery();
    dataTableDraw();

}







//datatable 필터 검색식 세팅
function makeMultiSearchQuery(){ //필터 검색조건 stringify

    if($(".filter_box").find("input[type='checkbox']").filter("[name='all']").is(":checked")){
        multi_search_query = "";


    }else{


        var str = "";

        //모든 체크박스 초기화.(전체 버튼 제외)
        $(".filter_box").find("input[type='checkbox']").not("[name='all']").each(function(index){
            var this_name = $(this).attr("name");
            var this_query = $(this).attr("query");

            //최초 로딩할때와 click 이벤트 시에 checked 여부가 달라지는지 체크해야 함.??
            if($(this).is(":checked")){ //기존에는 checked가 된 상태이면 지금 클릭할때는 checked 안된다.
                //최초 로딩할때는 여기로 옴.

                if(str == ""){
                    str = this_query;
                }else{
                    str += " and "+this_query;

                }

                
                if($(this).attr("hot_click")=="1"){
                    //
                    $(this).removeAttr("hot_click");


                }else{
                    if(str == ""){
                        str = this_query;
                    }else{
                        str += " and "+this_query;
    
                    }

                }


            }else{

                if($(this).attr("hot_click")=="1"){
                    if(str == ""){
                        str = this_query;
                    }else{
                        str += " and "+this_query;
    
                    }
                    $(this).removeAttr("hot_click");

                }else{
                    //
                }

                // //console.log(this_query);



            }

            
        });

        //console.log('msg1:' + str);

        $(".filter_box").find("input[type='radio']").each(function(index){
            var this_query = $(this).attr("query");

            if($(this).parent().hasClass("active")){
                if(str == ""){
                    str = this_query;
                }else{
                    str += " and "+this_query;
                }
            }
        
        });
        //console.log('msg2:' + str);

        multi_search_query = str;

        //console.log("multi_search_query:"+multi_search_query);


    }

}

//list.php 에서 datatable read 완료 후 호출함.
function makeAddFormMulti(add_array){

    /*
       dashboard_sj.php 에 #add_form_sample 이 있고(hide), 여기에 각 폼 타입별 샘플이 정의 되어 있음
       common/datatable_html.php 에 #add_form - display:none 으로 정의되어 있음, - 실질적으로 구현되어 보여지는 모달창임.
    */

    var $new_form = $("#add_form").clone(false);
    if(add_array[0][0] == "modal_info"){
        $new_form.removeAttr("id").attr("modal_name",add_array[0][1][0]); //모달 고유명 modal_name attr로 할당 //[0] = ["modal_info",["new","새로 추가하기"]], [0][1] = ["new","새로 추가하기"], [0][1][0] = "new"
        $("#add_form").after($new_form);
    }else{
        window.alert('modal창의 고유명이 정의되지 않았습니다.');
        return;
    }

    //list.php $add_array 샘플: array_push($add_array,["modal_info",["new","새 메뉴 추가","./contents/menu/menu/api/insertRow.php","menu","menu_master_idx"],"1"]); // "modal_info",[0: 모달창네임, 1: 모달창타이틀, 2: submit_url 3:적용할 table, 4: 적용할 row 고유키명],"1"  // 마지막 요소는 모달창에서 submit 후 성공하면 실행할 function key

    var modal_name = add_array[0][1][0];  //new
    var modal_title = add_array[0][1][1];  //새 메뉴 추가
    var modal_submit_url = add_array[0][1][2];  //"./contents/menu/menu/api/insertRow.php"
    var modal_table_name = add_array[0][1][3];   //menu
    var modal_key_column_name = add_array[0][1][4];   //menu_master_idx
    var modal_submit_function = add_array[0][2]; //"1"   //modal submit  후 작동할 function


    var submit_url;
    var submit_table_name;
    var submit_key_column_name;

    
    var $modal = $("div[modal_name='"+modal_name+"']");  //모달 고유네임
    var $modal_area = $modal.find(".form_area");
    $modal_area.empty();
    $modal_area.attr("title",modal_title);  //타이틀


    //<----- 새로만들기 일때와 수정하기 일때에 따른 submit 정보 할당 시작 //
    if(modal_name == "new"){  //new 인경우는 신규추가. 별도의 url을 지정하지 않으면, ajax_url_update 로 지정됨

        
        if(typeof modal_submit_url !== 'undefined' && modal_submit_url != ""){ //new인데 url 이 저정되어 있으면 지정된 url 사용.
           submit_url = modal_submit_url;
        }else{
           submit_url = ajax_insert_url; 
        }
         if(typeof modal_table_name !== 'undefined' && modal_table_name != ""){ 
            submit_table_name = modal_table_name;
        }else{
            submit_table_name = table_name; //datatable.js.php 에서 정의
        }

        if(typeof modal_key_column_name !== 'undefined' && modal_key_column_name != ""){ 
            submit_key_column_name = modal_key_column_name;
        }else{
            submit_key_column_name = key_column_name; //datatable.js.php 에서 정의
        }


    }else{
        submit_url = modal_submit_url;
        submit_table_name = modal_table_name;
        submit_key_column_name = modal_key_column_name;
    }

    $modal_area.attr("submit_url",submit_url);
    $modal_area.attr("submit_table_name",submit_table_name);
    $modal_area.attr("submit_key_column_name",submit_key_column_name);
    $modal_area.attr("modal_name",modal_name);

    if(typeof modal_submit_function !== 'undefined'){
        $modal_area.attr("modal_submit_function",modal_submit_function);
    }else{
        $modal_area.removeAttr("modal_submit_function");
    }
    // 새로만들기 일때와 수정하기 일때에 따른 submit 정보 할당 끝 ----->//


    
    //list.php 에 정의된 폼필드 형태별로 구성하여 넣기
    for(var i=1;i<add_array.length;i++){

        var input_type = add_array[i][3];  //list.php > add_array 에서 정의된 폼필드요소들중 네번째 정의된 값들.(text, select2,number,textarea,switch 등)
        if(typeof input_type === 'undefined'){  //첫번째 요소에 modal_info 인경우 pass
            continue;
        }
        //console.log(i+":"+add_array[i][0]);
        var $cl = $("#add_form_sample").find("div.form_group_"+input_type).clone(true);

        //input box에 name 값 할당
        $cl.find("input,textarea,select,radio").attr("name",add_array[i][0]);
        //default 입력값이 있는경우에는 할당(배열다섯번째값이 있는경우)
        if(input_type == "text" || input_type == "number" || input_type == "textarea"){
            if(add_array[i][4] != undefined){
                $cl.find("input,textarea").val(add_array[i][4]);  //추가시 기본 입력값 노출
            }
        }


        //추가화면 라벨출력
        $cl.find("label").eq(0).html(add_array[i][1]);//칼럼제목

        //필수 라고 표시된 경우 별표 반영
        if(add_array[i][2] == "필수"){
            $cl.attr("req","1");  //소스보기하면 required='required' 로 변형되어 표기됨.
            $cl.find("label").eq(0).append(" *").attr("req","1");
        }else{
            $cl.attr("req","0");
            $cl.find("label").eq(0).attr("req","0");
        }



        //dashboard_sj.php => dashboard_commmon.php 에 <div id="add_form_sample" class="hide"> 에 정의되어 있는 기본 포멧을 변형하여 name 이외의 추가적인 정보를 더 overide 하기 위한 용도
        switch (input_type) {

            case "select":

                $cl.find("select").html("");
                var select_opions = add_array[i][4];
                for(var j=0;j<select_opions.length;j++){

                    if(select_opions[j][2] == "SELECTED"){
                        select_option = "selected";
                    }else{
                        select_option = "";
                    }
                     
                     $cl.find("select").append("<option value='"+select_opions[j][0]+"' "+select_option+" >"+select_opions[j][1]+"</option>")
                }

                break;
            case "switch":

                var defaultClass = "btn-default";
                var defaultSelectedClass = "btn-success";
                var selectedClass = defaultSelectedClass;
                if(add_array[i][6] != "deselectable"){
                    $cl.find(".deselectable").removeClass("deselectable");
                }
    
                
                if(typeof add_array[i][5] !== 'undefined' && add_array[i][5] != ""){
                    selectedClass = add_array[i][5];
                }
                
                var switch1 = add_array[i][4][0];
                $cl.find("input:eq(0)").val(switch1[0]);
                $cl.find("label:eq(1)").append(switch1[1]);
    
                $cl.find("label:eq(1)").attr("selectedClass",selectedClass);
                $cl.find("label:eq(2)").attr("selectedClass",selectedClass);
    
                if(switch1[2] == "selected"){
    
                    $cl.find("label:eq(1)").removeClass(defaultClass).addClass(selectedClass).addClass("active");
                    $cl.find("label:eq(1)").find("input[type='radio']").attr("checked","checked")
                }else{
                    $cl.find("label:eq(1)").removeClass(selectedClass).removeClass("active").addClass(defaultClass);
                    $cl.find("label:eq(1)").find("input[type='radio']").removeAttr("checked");
    
                }
    
                var switch2 = add_array[i][4][1];
                $cl.find("input:eq(1)").val(switch2[0]);
                $cl.find("label:eq(2)").append(switch2[1]);
                if(switch2[2] == "selected"){
                    $cl.find("label:eq(2)").removeClass(defaultClass).addClass(selectedClass).addClass("active");
                    $cl.find("label:eq(2)").find("input[type='radio']").attr("checked","checked")
    
                }else{
                    $cl.find("label:eq(2)").removeClass(selectedClass).removeClass("active").addClass(defaultClass);
                    $cl.find("label:eq(2)").find("input[type='radio']").removeAttr("checked");
                }

                break;
            case "image":
                //if(!isNaN(add_array[i][4])){
                $cl.find("input").attr("directory",add_array[i][4]);  //이미지 저장 디렉토리 설정
                //}
                
                break;
            case "select2":
                $cl.find("select").html("");
                $cl.find("select").attr("name",add_array[i][0]);
                var select_opions = add_array[i][4];
                for(var j=0;j<select_opions.length;j++){

                    if(select_opions[j][2] == "SELECTED"){
                        select_option = "selected";
                    }else{
                        select_option = "";
                    }
                     
                     $cl.find("select").append("<option value='"+select_opions[j][0]+"' "+select_option+" >"+select_opions[j][1]+"</option>")
                }

                break;
            

            default:
                break;
        }


        if(typeof $cl !== 'undefined'){
            $modal_area.append($cl);
            //$("#add_form .form_area").find("[type='file']").filestyle();

        }


    }

    var $sub = $("#add_form_sample").find("div.form_group_submit").clone(true);
    $modal_area.append($sub);
    //$modal_area.find(".form_group_select2 select").select2();




}




function insertRow($tr){
    var cols = $tr.find("td").length - 1;
    
    $tr.after("<tr class='temp'><td colspan='"+cols+"'></td><td><button class='btn btn-danger btn-xs btn_close' value='닫기'>닫기</button></td></tr>");

}

//새로등록하기 모달창 열기
function openModal(modalName){
    //modalName == "new" : 새로추가하기, 기타->url에 따름
    //window.alert("new");
    var $add_form = $("div[modal_name='"+modalName+"']").find(".form_area").clone(true);

    //console.log($("#add_form").find(".form_area").html());
    $("#modal_add .modal_add_content").empty().append($add_form);
    $('#modal_add').toggleClass('is-visible');

    $('#modal_add').find("select.select2_single").select2({
        allowClear: true
    });
}




//form box 수정모드/기본모드 전환
function convert_form($td,mode=''){

    var column = $td.attr("column");
    //console.log("column:"+column);
    var $targetBox;
    if(mode == "edit"){

        $td.attr("td_mode","edit");
        if($td.hasClass("editable") && $td.attr("edit_type") == "editable_custom"){
            $targetBox = $("#input_sample").find("div."+column+".edit").clone(false); //false: 이벤트 핸들러는 copy하지 않음.
            //window.alert($targetBox.html());
        }else{
            $targetBox = $("#modify_sample").find("."+column).clone(false); //false: 이벤트 핸들러는 copy하지 않음.
            //$targetBox = $("#modify_sample").find("."+$td.attr("edit_type")).clone(false); //false: 이벤트 핸들러는 copy하지 않음.
            //window.alert($targetBox.html());
        }

        viewBox($td,$targetBox);
        $td.find(".select2_single").select2({
            allowClear: true
        });

    }else if(mode == "view"){

        $td.attr("td_mode","view");
        var $area = $td;
        if($td.find(".edit_area").length > 0){
            $area = $td.find(".edit_area");
        }

        if($td.hasClass("editable_img")){
            $td.find("input,button").remove();
        }



        if($td.attr("edit_type") == "editable_custom"){
            $targetBox = $("#input_sample").find("div."+column+".view").clone(false); //false: 이벤트 핸들러는 copy하지 않음.
            $area.html($targetBox.html());

            $td.find("span.input").each(function(){

                var column = $(this).attr("name");
                if($td.is("["+column+"_text_new"+"]")){ //새로 저장한 값이 있으면.
                    var new_value = $td.attr(column+"_text_new");
                    $(this).text(comma(new_value));
                    $td.attr(column,$td.attr(column+"_new")); //임시로 생성한_new attr 은 삭제
                    $td.attr(column+"_text",$td.attr(column+"_text_new")); //임시로 생성한_new attr 은 삭제

                    $td.removeAttr(column+"_new"); //임시로 생성한_new attr 은 삭제
                    $td.removeAttr(column+"_text_new"); //임시로 생성한_new attr 은 삭제
                }else if($td.is("["+column+"_new"+"]")){ //새로 저장한 값이 있으면.
                    var new_value = $td.attr(column+"_new");
                    $(this).text(comma(new_value));
                    $td.attr(column,$td.attr(column+"_new")); //임시로 생성한_new attr 은 삭제
                    $td.removeAttr(column+"_new"); //임시로 생성한_new attr 은 삭제
                }else{//취소하기
                    if($(this).attr("same") == "N"){
                        var new_value = $td.attr(column+"_text");
                        $(this).text(comma(new_value));
                    }else{ 
                        var new_value = $td.attr(column);
                        if(new_value == undefined){
                            $(this).text("");

                        }else{
                            $(this).text(comma(new_value));

                        }
                    }
                }
            });

        }else{

            $td.find("select,input,textarea").each(function(){

                if($td.is("["+column+"_new"+"]")){ //새로 저장한 값이 있으면.
                    
                    var new_value = $td.attr(column+"_new");

                    if($(this).get(0).tagName == "SELECT" && $(this).attr("same") == "N"){ //select 박스인데 value 와 text 가 다른경우
                        var new_text_value = $td.attr(column+"_text_new");
                        //$area.html(comma(new_text_value));
                        $area.html(new_text_value);

                        $td.attr(column,new_value); //column attr 에 새로운 value 할당
                        $td.removeAttr(column+"_new"); //임시로 생성한_new attr 은 삭제

                        $td.attr(column+"_text",new_text_value); //column_text attr 에 새로운 text value 할당
                        $td.removeAttr(column+"_text_new"); //임시로 생성한 _text_new attr 은 삭제
                    }else{
                        //$area.html(comma(new_value));
                        $area.html(new_value);

                        $td.attr(column,new_value);
                        $td.removeAttr(column+"_new");

                    }
                }else{//취소하기

                    
                    var cnt_selectDropDown = $td.find("div.btn-group"); //selectDropDown 은 숨겼다가 그냥 다시보여주기

                    if(cnt_selectDropDown.length > 0){ 
                        var $dd = $td.find("div.btn-group").clone(true);
                        $td.html("").append($dd.show());

                    }else{
                        

                        var old_value = $td.attr(column);

                        
                        if(old_value == undefined){
                            $area.html("");
                        }else{


                            if($(this).get(0).tagName == "SELECT" && $(this).attr("same") == "N"){
                                var old_value = $td.attr(column+"_text");
                            }
                            //$area.html(comma(old_value));
                            $area.html(old_value);
                        }

                    }

                }
            });

        }

    }

}

function viewBox($td,$targetBox){ 

    //var select_mode = "Y"; //Y:select option 의 value 와 text 값이 일치하는 경우. N:은 value 와 text 가 일치하지 않는 경우.
    // //console.log(typeof  $targetBox.get(0).tagName);
    // //console.log($targetBox.get(0).tagName);

    try {
        if($targetBox.get(0).tagName == "SELECT"){  //대문자로 해야됨. //여기서 에러나면 list.php에 form input 등 요소의 name과 class 가 정의 되지 않아서임.
            $targetBox.find("option[role='option']").remove();  //기본옵션 삭제
        }
        
    }catch(e){
        //list.php 에 해당 form 요소를 정의하지 않았으면 기본 text 박스로 보여짐.
        $targetBox = "<input type=text class='form-control' name='"+$td.attr("column")+"' value=''>";
    }


    /* selectDropDown 수정할때 -> 동적 호출 실패해서 생략
    var edit_type = $td.attr("edit_type");
    var column = $td.attr("column");
    var default_val= $td.attr(column);
    if(edit_type == "selectDropDown"){
        $targetBox.find("button").first().text(default_val);
        $targetBox.find("button").first().removeClass().addClass(column);
        $targetBox.find(".dropdown-toggle").removeClass().addClass("btn dropdown-toggle");

        var default_color = $targetBox.find("a:contains('"+default_val+"')").attr("selected_color");
        $targetBox.find("button").first().addClass(default_color);
        $targetBox.find(".dropdown-toggle").addClass(default_color);
    }

    */

    

    var $edit_area = $td;
    if($td.find(".edit_area").length > 0){
        $edit_area = $td.find(".edit_area");
    }

    var cnt_selectDropDown = $edit_area.find("div.btn-group");

    if(cnt_selectDropDown.length > 0){
        $edit_area.find("div.btn-group").hide();
        $edit_area.append($targetBox);

    }else{
        $edit_area.html("").append($targetBox);

    }


   



    $edit_area.find("input,textarea,select").each(function(index){ //각 사용자 정의 입력셀의 여러 formbox 박스 순환

        var column = $(this).attr("name");
        

        if($(this).get(0).tagName == "SELECT" && $(this).attr("same") == "N"){
            var this_val_text  = $td.attr(column+"_text");
            var char_format =  $edit_area.attr("format");
            var char_format2 = $(this).attr("format");
            if(char_format == "number" || char_format2 == "number"){
                this_val_text = uncomma(this_val_text);
            }
            $(this).find("option").each(function(index){
                var this_txt = $(this).text();
                if(this_txt == this_val_text){
                    //console.log("같음:"+this_txt);
                    $(this).attr("selected","selected");
                }
            });
        }else{
            var this_val  = $td.attr(column);
            $(this).val(this_val);
        }
    });
}

