
<!-- editor -->
<link href="https://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
<link href="css/editor/external/google-code-prettify/prettify.css" rel="stylesheet">
<link href="css/editor/index.css" rel="stylesheet">
<script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>








<table class="table table-bordered table_hrm_key_info"  hrm_idx="<?=$_REQUEST['hrm_idx']?>">

    <thead>
        <tr>
            <th colspan="7" class="hrm_name big_title">인사관리</th>
        </tr>
    </thead>

</table>


<table class="table table-bordered table_hrm_info">

    <thead>
        <tr>
            <th colspan="4" class="hrm_info mtitle">
                기본정보
                <ul class="nav navbar-right panel_toolbox" style="min-width:unset;">

                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-ellipsis-v" style="color:#00b8ff;"></i></a>
                      <ul class="dropdown-menu" role="menu" >
                        <li><a href="javascript:" class="hrm_del">삭제</a>
                        </li>
                        <li><a href="javascript:" class="hrm_edit">수정</a>
                        </li>
                      </ul>
                    </li>

                </ul>
            
            
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th scope="row" class="mth">이름</th>
            <td class="name"></td>
            <th scope="row" class="mth">관리지사</td>
            <td class="t_jisa"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">소속기관</th>
            <td class="t_organization"></td>
            <th scope="row" class="mth">근무지</td>
            <td class="t_office"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">직책</th>
            <td class="job_position"></td>
            <th scope="row" class="mth">직급</td>
            <td class="job_grade"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">근로유형</th>
            <td class="work_type"></td>
            <th scope="row" class="mth">근무시간</td>
            <td class="work_time"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">입사일</th>
            <td class="date_of_employment"></td>
            <th scope="row" class="mth">퇴사일</td>
            <td class="date_of_resignation"></td>
        </tr>

        
        <tr>
            <th scope="row" class="mth">장애유형</th>
            <td class="disabled_type"></td>
            <th scope="row" class="mth">장애등급</td>
            <td class="disabled_grade"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">전화번호</th>
            <td class="tel"></td>
            <th scope="row" class="mth">보호자전화번호</td>
            <td class="tel_guardians"></td>
        </tr>

        
        <tr>
            <th scope="row" class="mth">소개기관</th>
            <td class="affiliate_organization"></td>
            <th scope="row" class="mth"></td>
            <td class=""></td>
        </tr>


        <tr>
            <th scope="row" class="mth">주소</th>
            <td class="address" colspan="3"></td>

        </tr>
        <tr>
            <th scope="row" class="mth">메모</th>
            <td class="memo" colspan="3"></td>

        </tr>


    </tbody>
</table>










<div class="" role="tabpanel" data-example-id="togglable-tabs" id="memo_history">
    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">

        <li role="presentation" class="active"><a href="#tab_content2" role="tab" id="contract-tab" data-toggle="tab" aria-expanded="true">기록사항</a>
        </li>


    </ul>

    <div id="myTabContent" class="tab-content">

        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="product-tab" part="memo_product">
        

            <div class="x_panel x_panel_memo_history">
                <div class="x_title">
                    <div class="col-md-6 col-sm-6 col-xs-12 type_filter_wrap">
                        <input type="text" id="type_filter" name="type_filter" placeholder="필터" value="" required="" class="form-control col-md-12 col-xs-12" >
                        
                    </div>
                <div class="col-md- title_right_col" style="text-align:right; padding-right:0px;">
                    <button class="btn btn-primary btn_add_product_history" >기록추가</button>
                </div>
                <div class="clearfix"></div>
                </div>
                <div class="x_content">
                <ul class="list-unstyled timeline ul_memo_product_history">

                <li id="memo_add_section" class="hide">
                    <div class="block">
                        <div class="tags">
                        <a href="javascript:" class="tag">
                            <span class="memo_status">태그1</span>
                        </a>
                        </div>




                                        <div class="form-group">

                                        수행일: <input placeholder='수행일' type='date' class='mdate' name='date1'  value="<?=date('Y-m-d')?>"><br>

                                        <select id="product_type" name="product_type" placeholder="항목" required="required" >
                                            <option >태그1</option>
                                            <option >태그2</option>
                                            <option >태그3</option>
                                            
                                            <option >기타</option>

                                        </select>
                                        <input type="text" name="memo_title" id="memo_title" placeholder="타이틀" required="required" >
                                        </div>





                        <div class="block_content">

                                    

                        <p class="excerpt">
                                        
                            <div id="alerts"></div>
                            <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor">
                                <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa icon-font"></i><b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                </ul>
                                </div>
                                <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                    <a data-edit="fontSize 5">
                                        <p style="font-size:17px">Huge</p>
                                    </a>
                                    </li>
                                    <li>
                                    <a data-edit="fontSize 3">
                                        <p style="font-size:14px">Normal</p>
                                    </a>
                                    </li>
                                    <li>
                                    <a data-edit="fontSize 1">
                                        <p style="font-size:11px">Small</p>
                                    </a>
                                    </li>
                                </ul>
                                </div>
                                <div class="btn-group">
                                <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
                                <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>
                                <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="icon-strikethrough"></i></a>
                                <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="icon-underline"></i></a>
                                </div>
                                <div class="btn-group">
                                <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="icon-list-ul"></i></a>
                                <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="icon-list-ol"></i></a>
                                <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>
                                <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>
                                </div>
                                <div class="btn-group">
                                <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="icon-align-left"></i></a>
                                <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="icon-align-center"></i></a>
                                <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="icon-align-right"></i></a>
                                <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="icon-align-justify"></i></a>
                                </div>
                                <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="icon-link"></i></a>
                                <div class="dropdown-menu input-append">
                                    <input class="span2" placeholder="URL" type="text" data-edit="createLink" />
                                    <button class="btn" type="button">Add</button>
                                </div>
                                <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="icon-cut"></i></a>

                                </div>

                                <div class="btn-group">
                                <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="icon-picture"></i></a>
                                <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
                                </div>
                                <div class="btn-group">
                                <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
                                <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
                                </div>
                            </div>

                            <div id="editor">

                            </div>
                            <textarea name="descr" id="descr" style="display:none;"></textarea>

                        </p>
                        </div>
                        <div class="col-md- title_right_col" style="text-align:right; padding-right:0px;">
                        <button class="btn btn-info btn_cancel_product_history">취소</button> <button class="btn btn-success btn_save_product_history" >저장하기</button>
                        </div>

                    </div>
                    </li>

                    
                </ul>

                </div>
            </div>



        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="contract-tab" part="memo">
        



            <div class="x_panel x_panel_memo_history">
                <div class="x_title">
                <h2><small></small></h2>
                <div class="col-md- title_right_col" style="text-align:right; padding-right:0px;">
                    <button class="btn btn-primary btn_add_history">기록추가</button>
                </div>
                <div class="clearfix"></div>
                </div>
                <div class="x_content">
                <ul class="list-unstyled timeline ul_memo_history">

                <li id="memo_add_section" class="hide">
                    <div class="block">
                        <div class="tags">
                        <a href="javascript:" class="tag_warning tag">
                            <span class="memo_status"></span>
                        </a>
                        </div>
                        <div class="block_content">




                        
                        기록일: <input placeholder='기록일' type='date' class='mdate' name='date2' value="<?=date('Y-m-d')?>"><br>
                        <div class="form-group" >
                                    

                                    <input type="text" id="memo_title" placeholder="타이틀" required="required" class="form-control col-md-7 col-xs-12">
                                    <br><br>
                        

                        <p class="excerpt">
                                        
                            <div id="alerts"></div>
                            <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor">
                                <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa icon-font"></i><b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                </ul>
                                </div>
                                <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                    <a data-edit="fontSize 5">
                                        <p style="font-size:17px">Huge</p>
                                    </a>
                                    </li>
                                    <li>
                                    <a data-edit="fontSize 3">
                                        <p style="font-size:14px">Normal</p>
                                    </a>
                                    </li>
                                    <li>
                                    <a data-edit="fontSize 1">
                                        <p style="font-size:11px">Small</p>
                                    </a>
                                    </li>
                                </ul>
                                </div>
                                <div class="btn-group">
                                <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
                                <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>
                                <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="icon-strikethrough"></i></a>
                                <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="icon-underline"></i></a>
                                </div>
                                <div class="btn-group">
                                <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="icon-list-ul"></i></a>
                                <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="icon-list-ol"></i></a>
                                <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>
                                <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>
                                </div>
                                <div class="btn-group">
                                <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="icon-align-left"></i></a>
                                <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="icon-align-center"></i></a>
                                <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="icon-align-right"></i></a>
                                <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="icon-align-justify"></i></a>
                                </div>
                                <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="icon-link"></i></a>
                                <div class="dropdown-menu input-append">
                                    <input class="span2" placeholder="URL" type="text" data-edit="createLink" />
                                    <button class="btn" type="button">Add</button>
                                </div>
                                <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="icon-cut"></i></a>

                                </div>

                                <div class="btn-group">
                                <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="icon-picture"></i></a>
                                <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
                                </div>
                                <div class="btn-group">
                                <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
                                <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
                                </div>
                            </div>

                            <div id="editor" class="editor">

                            </div>
                            <textarea name="descr" id="descr" class="descr" style="display:none;"></textarea>

                        </p>
                        </div>
                        <div class="col-md- title_right_col" style="text-align:right; padding-right:0px;">
                        <button class="btn btn-info btn_cancel_history">취소</button> <button class="btn btn-success btn_save_history">저장하기</button>
                        </div>

                    </div>
                    </li>

                    
                </ul>

                </div>
            </div>





        </div>

    </div>
</div>
















<div class="" role="tabpanel" data-example-id="togglable-tabs" id="attachment_tabs">
    <ul id="attatchTab" class="nav nav-tabs bar_tabs" role="tablist">
        <li role="presentation" class="active attatch_tab"><a id="attatch-tab1" role="tab" data-toggle="tab" aria-expanded="true">전체</a>
        </li>
        <li role="presentation" class=" attatch_tab"><a  role="tab" id="attatch-tab2" data-toggle="tab" aria-expanded="false">근로계약서</a>
        </li>
        <li role="presentation" class=" attatch_tab"><a  role="tab" id="attatch-tab3" data-toggle="tab" aria-expanded="false">교육관련서류</a>
        </li>
        <li role="presentation" class=" attatch_tab"><a  role="tab" id="attatch-tab5" data-toggle="tab" aria-expanded="false">기타</a>
        </li>
    </ul>





    <div class="x_panel x_panel_attachment">
    <div class="x_title">
        <h2>첨부파일<small></small></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">


            <form enctype="multipart/form-data" id="ajaxFrom" method="post">

                    <div class="container">
                        <div class="col-xs-8">
                            <div class="form-group" >
                            <input type="file" name="attachFile" id="attachFile"/>
                            </div>
                        </div>
                        <div class="col-xs-4">
                        <input type="button" onClick="ajaxFileUpload();" value="첨부파일추가" class="btn btn-primary btn_add_attach" />
                        </div>

                    </div>
                </form>

                <script>
                            $('#attachFile').filestyle({
                                iconName : 'glyphicon glyphicon-file',
                                buttonText : 'Select File',
                                buttonName : 'btn-warning'
                            });                     
                </script>

        <div class="col-md-12 col-sm-12 col-xs-12">


                  <table class="table table-striped" id="table_attach_list">
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>첨부파일명</th>
                        <th class='td_x'>삭제</th>

                      </tr>
                    </thead>
                    <tbody>

                      <!---
                      <tr>
                        <th scope="row" class="th_num">1</th>
                        <td><a href=''>계약서1.doc</a></td>
                        <td class='td_x'><i class="fa fa-close"></i></td>

                      </tr>
                      <tr>
                        <th scope="row" class="th_num">2</th>
                        <td><a href=''>회사소개서.pdf</a></td>
                        <td class='td_x'><i class="fa fa-close"></i></td>

                      </tr>
                      <tr>
                        <th scope="row" class="th_num">3</th>
                        <td><a href=''>사업자등록증.jpg</a></td>
                        <td class='td_x'><i class="fa fa-close"></i></td>

                      </tr>
                        -->

                    </tbody>
                  </table>


            </div>

    </div>
</div>








</div>

























<!---
<table class="table table-bordered">

    <thead>
        <tr>
            <th class="hrm_docu mtitle">견적서/계약서</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>




            </td>
        </tr>


    </tbody>
</table>
-->

<div id="memo_sample" class="hide">


    <li class='memo_li' memo_idx='123'>
        <div class="block">
            <ul class="nav navbar-right panel_toolbox" style="min-width:unset;">

                <li class="dropdown" style="border-bottom:unset;">
                <a href="#" class="dropdown-toggle ellipsis" data-toggle="dropdown" role="button" aria-expanded="false" ><i class="fa fa-ellipsis-v" style="color: #0fdef3;"></i></a>
                <ul class="dropdown-menu" role="menu" >
                    <li><a href="javascript:" class="memo_del">삭제</a>
                    </li>
                    <li><a href="javascript:" class="memo_edit">수정</a>
                    </li>
                </ul>
                </li>

            </ul>


            <div class="tags">
                <a href="javascript:" class="tag">
                    <span class='tag_title memo_status'></span>
                </a>
            </div>
            
            <div class="block_content">
                <h2 class="title">
                                <span class='mdate'>2022/03/21</span><span class='atitle'>전화상담내역</span>
                </h2>


                <div class="byline">
                    <span class='update_datetime'>2022-01-15 PM 3:40</span> by <a class='awriter'>홍길동</a>
                </div>
                <p class="excerpt">
                    전화상담 완료<br>
                    담당자: 0000<br>
                    상담내용: 블라블라블라블라
                    <br><a href='#' class='text_more'>Read&nbsp;More</a>
                </p>
            </div>
        </div>
    </li>


</div>


<div id="attachment_sample" class="hide">
    <table>
    <tr>
        <th scope="row" class="th_num">1</th>
        <td class="td_filename"><a >계약서1.doc</a></td>
        <td class="td_x"><i class="fa fa-close"></i></td>
    </tr>
    </table>

</div>




<!-- richtext editor -->
<script src="js/editor/bootstrap-wysiwyg.js"></script>
<script src="js/editor/external/jquery.hotkeys.js"></script>
<script src="js/editor/external/google-code-prettify/prettify.js"></script>



<!-- textarea resize -->
<script src="js/textarea/autosize.min.js"></script>
<script>
  autosize($('.resizable_textarea'));
</script>

<!-- editor -->
<script>
  $(document).ready(function() {
    $('.xcxc').click(function() {
      //$('#descr').val($('#editor').html());
      $('.descr').val($('.editor').html());

    });
  });



  $(function() {
    function initToolbarBootstrapBindings() {
      var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
          'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
          'Times New Roman', 'Verdana'
        ],
        fontTarget = $('[title=Font]').siblings('.dropdown-menu');
      $.each(fonts, function(idx, fontName) {
        fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
      });
      $('a[title]').tooltip({
        container: 'body'
      });
      $('.dropdown-menu input').click(function() {
          return false;
        })
        .change(function() {
          $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
        })
        .keydown('esc', function() {
          this.value = '';
          $(this).change();
        });

      $('[data-role=magic-overlay]').each(function() {
        var overlay = $(this),
          target = $(overlay.data('target'));
        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
      });
      if ("onwebkitspeechchange" in document.createElement("input")) {
        var editorOffset = $('#editor').offset();
        $('#voiceBtn').css('position', 'absolute').offset({
          top: editorOffset.top,
          left: editorOffset.left + $('#editor').innerWidth() - 35
        });
      } else {
        $('#voiceBtn').hide();
      }
    };

    function showErrorAlert(reason, detail) {
      var msg = '';
      if (reason === 'unsupported-file-type') {
        msg = "Unsupported format " + detail;
      } else {
        console.log("error uploading file", reason, detail);
      }
      $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
        '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
    };
    initToolbarBootstrapBindings();
    $('#editor').wysiwyg({
      fileUploadError: showErrorAlert
    });
    window.prettyPrint && prettyPrint();
  });



















  $(function() {
    function initToolbarBootstrapBindings() {
      var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
          'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
          'Times New Roman', 'Verdana'
        ],
        fontTarget = $('[title=Font]').siblings('.dropdown-menu');
      $.each(fonts, function(idx, fontName) {
        fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
      });
      $('a[title]').tooltip({
        container: 'body'
      });
      $('.dropdown-menu input').click(function() {
          return false;
        })
        .change(function() {
          $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
        })
        .keydown('esc', function() {
          this.value = '';
          $(this).change();
        });

      $('[data-role=magic-overlay]').each(function() {
        var overlay = $(this),
          target = $(overlay.data('target'));
        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
      });
      if ("onwebkitspeechchange" in document.createElement("input")) {
        var editorOffset = $('.editor').offset();
        $('#voiceBtn').css('position', 'absolute').offset({
          top: editorOffset.top,
          left: editorOffset.left + $('.editor').innerWidth() - 35
        });
      } else {
        $('#voiceBtn').hide();
      }
    };

    function showErrorAlert(reason, detail) {
      var msg = '';
      if (reason === 'unsupported-file-type') {
        msg = "Unsupported format " + detail;
      } else {
        console.log("error uploading file", reason, detail);
      }
      $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
        '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
    };
    initToolbarBootstrapBindings();
    $('.editor').wysiwyg({
      fileUploadError: showErrorAlert
    });
    window.prettyPrint && prettyPrint();
  });




</script>
<!-- /editor -->
<!-- 
<script type="text/javascript">
    $(document).ready(function() {

      $('#single_cal1').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_2"
      }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
      });

      $('#single_cal2').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_2"
      }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
      });

    });
  </script> -->