<!-- editor -->
<link href="https://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
<link href="css/editor/external/google-code-prettify/prettify.css" rel="stylesheet">
<link href="css/editor/index.css" rel="stylesheet">
<script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>

<table class="table table-bordered table_company_key_info">

        <tr>
            <th class="company_name big_title">고객사를 선택해주세요</th>
        </tr>

</table>


<table class="table table-bordered table_company_info">

    <thead>
        <tr>
            <th colspan="4" class="company_info mtitle">
                기본정보
                <ul class="nav navbar-right panel_toolbox" style="min-width:unset;">

                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-ellipsis-v" style="color:#00b8ff;"></i></a>
                      <ul class="dropdown-menu" role="menu" >
                        <li><a href="javascript:" class="company_del">삭제</a>
                        </li>
                        <li><a href="javascript:" class="company_edit">수정</a>
                        </li>
                      </ul>
                    </li>

                </ul>
            
            
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th scope="row" class="mth">사업자번호</th>
            <td class="biz_num"></td>
            <th scope="row" class="mth">업태</td>
            <td class="biz_type"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">법인번호</th>
            <td class="corp_num"></td>
            <th scope="row" class="mth">업종</td>
            <td class="biz_part"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">대표자명</th>
            <td class="ceo_name"></td>
            <th scope="row" class="mth">주소</td>
            <td class="address"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">전화번호</th>
            <td class="tel"></td>
            <th scope="row" class="mth">홈페이지</td>
            <td class="homepage"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">팩스번호</th>
            <td class="fax"></td>
            <th scope="row" class="mth">메모</td>
            <td class="memo"></td>
        </tr>
    </tbody>
</table>



<table class="table table-bordered table_manager_info">

    <thead>
        <tr>
            <th colspan="6" class="manager_info mtitle">담당자정보

            </th>
        </tr>
        <tr class=" ">
            <th scope="row" class="mth">이름</th>
            <th scope="row" class="mth">직위</th>
            <th scope="row" class="mth">부서</th>
            <th scope="row" class="mth">이메일</th>
            <th scope="row" class="mth">전화번호</th>
            <th scope="row" class="mth">휴대폰</th>
        </tr>
    </thead>

    <tbody>
        <tr class="">
            <td class="manager_name"></td>
            <td class="manager_position"></td>
            <td class="manager_department"></td>
            <td class="manager_email"></td>
            <td class="manager_tel"></td>
            <td class="manager_hp"></td>
        </tr>

    </tbody>
</table>


<div class="x_panel x_panel_product_info">
        
        <div class="col-md-6 title_right_col" style=" padding-right:0px;">
            <h2>거래품목 <small></small></h2>
        </div>
        <!-- <div class="col-md-6 title_right_col" style="text-align:right; padding-right:0px;">
             <button class="btn btn-info  btn_add_product">추가</button>
        </div> -->
</div>

<table class="table table-bordered table_product_info hide">
    <thead>
        <tr>
            <th scope="row" class="mth product_name">품명</th>
            <th scope="row" class="mth product_price">단가</th>
            <th scope="row" class="mth tax">부가세</th>
            <th scope="row" class="mth price_sum">합계</th>
            <th scope="row" class="product_setting">관리</th>
        </tr>

<!--         
        <tr class="new_tr">
            <td class="product_name">
                        <select id="new_product" name="product_idx" class="select2_single form-control" tabindex="-1" style="display: none;">
                            <option value="0">전체</option>
                            <?php

                            

                            $sel_product = mysqli_query($dbcon, "select product_idx,product_name,product_price,is_vat from ".$db_flower.".product where display='on' order by product_name ") or die(mysqli_error($dbcon));
                            $sel_product_num = mysqli_num_rows($sel_product);
                            

                            if ($sel_product_num > 0) {
                            while($data_product = mysqli_fetch_assoc($sel_product)) {?>
                                <option value="<?=$data_product['product_idx']?>" price="<?=$data_product['product_price']?>" is_vat="<?=$data_product['is_vat']?>"><?=$data_product['product_name']?></option>

                            <?}
                            }
                            
                            ?>
                            
                        </select>
                        
            </td>
            <td class="client_price"><input type="text" name="client_price" class="form-control col-xs-12"></td>
            <td class="client_price_tax"><input type="text" name="client_price_tax" class="form-control col-xs-12"></td>
            <td class="client_price_sum"><input type="text" name="client_price_sum" class="form-control col-xs-12"></td>
            <td class="product_setting">
                <button class="btn btn-info  btn_save_product">추가</button>
            </td>

        </tr> -->

    </thead>

    <tbody>


        <tr>
            <td class="product_name"></td>
            <td class="client_price"></td>
            <td class="client_price_tax"></td>
            <td class="client_price_sum"></td>
            <td class="product_setting">

                <ul class="nav navbar-right panel_toolbox" style="min-width:unset;">

                    <li class="dropdown" style="border-bottom:unset;">
                    <a href="#" class="dropdown-toggle ellipsis" data-toggle="dropdown" role="button" aria-expanded="false" ><i class="fa fa-ellipsis-v" style="color: #0fdef3;"></i></a>
                    <ul class="dropdown-menu" role="menu" >
                        <!-- <li><a href="javascript:" class="product_del">삭제</a>
                        </li> -->
                        <li><a href="javascript:" class="product_edit">수정</a>
                        </li>
                    </ul>
                    </li>

                </ul>

            </td>

        </tr>

    </tbody>
</table>



<div class="x_panel x_panel_sender_info">
        
        <div class="col-md-6 title_right_col" style=" padding-right:0px;">
            <h2>보내는분 <small></small></h2>
        </div>
        <div class="col-md-6 title_right_col" style="text-align:right; padding-right:0px;">
             <button class="btn btn-info btn_add_sender">추가</button>
             
        </div>
</div>

<table class="table table-bordered table_sender_info table_list">
    <tbody>
        <tr client_flower_sender_idx="">
            <th scope="row" class="mth">보내는분</th>
            <td class="sender_name"></td>

            <td class="sender_setting">

                <ul class="nav navbar-right panel_toolbox" style="min-width:unset;">

                    <li class="dropdown" style="border-bottom:unset;">
                    <a href="#" class="dropdown-toggle ellipsis" data-toggle="dropdown" role="button" aria-expanded="false" ><i class="fa fa-ellipsis-v" style="color: #0fdef3;"></i></a>
                    <ul class="dropdown-menu" role="menu" >
                        <li><a href="javascript:" class="product_del">삭제</a>
                        </li>
                        <li><a href="javascript:" class="product_edit">수정</a>
                        </li>
                    </ul>
                    </li>

                </ul>

            </td>
        </tr>


    </tbody>
</table>








<div class="x_panel x_panel_memo_history ">
    <div class="x_title">
    <h2>영업기록 <small></small></h2>
    <div class="col-md- title_right_col" style="text-align:right; padding-right:0px;">
        <button class="btn btn-primary btn_add_history">영업기록추가</button>
    </div>
    <div class="clearfix"></div>
    </div>
    <div class="x_content">
    <ul class="list-unstyled timeline ul_memo_history">

    <li id="memo_add_section" class="hide">
        <div class="block">
            <div class="tags">
            <a href="javascript:" class="tag">
                <span class="memo_status">상담</span>
            </a>
            </div>
            <div class="block_content">
            <h2 class="title">
            <div class="form-group">
                        <input type="text" id="memo_title" senderholder="타이틀" required="required" class="form-control col-md-7 col-xs-12">
                        <br><br>
            </h2>

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
                        <input class="span2" senderholder="URL" type="text" data-edit="createLink" />
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
            <button class="btn btn-info btn_cancel_history">취소</button> <button class="btn btn-success btn_save_history">저장하기</button>
            </div>

        </div>
        </li>


    </ul>

    </div>
</div>





<div class="x_panel x_panel_attachment">
    <div class="x_title">
        <h2>견적서/계약서 <small></small></h2>

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

<!---
<table class="table table-bordered">

    <thead>
        <tr>
            <th class="client_docu mtitle">견적서/계약서</th>
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
                    <span class='tag_title memo_status'>상담</span>
                </a>
            </div>
            
            <div class="block_content">
                <h2 class="title">
                                <a href='#' class='atitle'>전화 상담</a>
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
        <td class="td_filename"><a href='' download>계약서1.doc</a></td>
        <td class="td_x"><i class="fa fa-close"></i></td>
    </tr>
    </table>

</div>


<div id="manager_sample" class="hide">
    <table>
        <tr class="">
            <td class="manager_name"></td>
            <td class="manager_position"></td>
            <td class="manager_department"></td>
            <td class="manager_email"></td>
            <td class="manager_tel"></td>
            <td class="manager_hp"></td>
        </tr>

    </table>

</div>



<div id="client_product_sample" class="hide">
    <table>
        <tbody>
            <tr>
                <td class="product_name"></td>
                <td class="client_price"></td>
                <td class="client_price_tax"></td>
                <td class="client_price_sum"></td>
                <td class="product_setting">

                    <ul class="nav navbar-right panel_toolbox" style="min-width:unset;">

                        <li class="dropdown" style="border-bottom:unset;">
                        <a href="#" class="dropdown-toggle ellipsis" data-toggle="dropdown" role="button" aria-expanded="false" ><i class="fa fa-ellipsis-v" style="color: #0fdef3;"></i></a>
                        <ul class="dropdown-menu" role="menu" >
                            <!-- <li><a href="javascript:" class="product_del">삭제</a>
                            </li> -->
                            <li><a href="javascript:" class="product_edit">수정</a>
                            </li>
                        </ul>
                        </li>

                    </ul>

                </td>

            </tr>

        </tbody>
    </table>


</div>




<div id="sender_sample" class="hide">
        
    <table class="table table-bordered table_sender_info">
        <tbody>
            <tr client_flower_sender_idx="">
                <th scope="row" class="mth">보내는분</th>
                <td class="sender_name"><input type="text" name="sender_name" class="form-control col-xs-12"></td>

                <td class="sender_setting">

                    <ul class="nav navbar-right panel_toolbox" style="min-width:unset;">

                        <li class="dropdown" style="border-bottom:unset;">
                        <a href="#" class="dropdown-toggle ellipsis" data-toggle="dropdown" role="button" aria-expanded="false" ><i class="fa fa-ellipsis-v" style="color: #0fdef3;"></i></a>
                        <ul class="dropdown-menu" role="menu" >
                            <li><a href="javascript:" class="sender_del">삭제</a>
                            </li>
                            <li><a href="javascript:" class="sender_edit">수정</a>
                            </li>
                        </ul>
                        </li>

                    </ul>

                </td>
            </tr>
           

        </tbody>
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
      $('#descr').val($('#editor').html());
    });


    //select2 -->
    $(".select2_single").select2({
      senderholder: "상품 선택",
      allowClear: true
    });

    // /select2 -->
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
</script>
<!-- /editor -->

