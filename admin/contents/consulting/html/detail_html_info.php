<?php

$sel = mysqli_query($dbcon, "select * from consulting where consulting_idx='".$_REQUEST['consulting_idx']."' ") or die(mysqli_error($dbcon));
$sel_num = mysqli_num_rows($sel);


$company_info;
if ($sel_num > 0) {
    $data = mysqli_fetch_assoc($sel);
    $company_info = $data;

}


$sel1 = mysqli_query($dbcon, "select *,IFNULL(date_format(mdate,'%Y%m%d'),'') as md,date_format(update_datetime,'%Y-%m-%d %H:%i') as utime from memo where consulting_idx='".$_POST['consulting_idx']."' order by mdate desc, memo_idx desc") or die(mysqli_error($dbcon));
$sel1_num = mysqli_num_rows($sel1);

$memo_list = array();
if ($sel1_num > 0) {
    while($data1 = mysqli_fetch_assoc($sel1)) {
        array_push($memo_list,$data1);
    }
}



$sel1 = mysqli_query($dbcon, "select *,IFNULL(date_format(mdate,'%Y%m%d'),'') as md,date_format(update_datetime,'%Y-%m-%d %H:%i') as utime from memo_product where consulting_idx='".$_POST['consulting_idx']."' order by mdate desc,memo_idx desc") or die(mysqli_error($dbcon));
$sel1_num = mysqli_num_rows($sel1);

$memo_product_list = array();
if ($sel1_num > 0) {
    while($data1 = mysqli_fetch_assoc($sel1)) {
        array_push($memo_product_list,$data1);
    }
}




$sel2 = mysqli_query($dbcon, "select * from attachment where consulting_idx='".$_REQUEST['consulting_idx']."' order by attachment_idx") or die(mysqli_error($dbcon));
$sel2_num = mysqli_num_rows($sel2);

$attachment_list = array();
if ($sel2_num > 0) {
    while($data2 = mysqli_fetch_assoc($sel2)) {
        array_push($attachment_list,$data2);
    }
}




$sel3 = mysqli_query($dbcon, "select * from manager where consulting_idx='".$_REQUEST['consulting_idx']."' order by manager_idx") or die(mysqli_error($dbcon));
$sel3_num = mysqli_num_rows($sel3);

$manager_list = array();
if ($sel3_num > 0) {
    while($data3 = mysqli_fetch_assoc($sel3)) {
        array_push($manager_list,$data3);
    }
}




?>

<!-- editor -->
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
<link href="css/editor/external/google-code-prettify/prettify.css" rel="stylesheet">
<link href="css/editor/index.css" rel="stylesheet">
<script type="text/javascript" src="js/bootstrap-filestyle.min.js"> </script>

<table class="table table-bordered table_company_key_info" consulting_idx="<?=$_REQUEST['consulting_idx']?>">

    <thead>
        <tr>
            <th colspan="6" class="company_name big_title"><?=$company_info['company_name']?></th>
        </tr>
    </thead>

    <tbody>
        <tr  class="tr_title">
            <th>계약상태</th>
            <th>직원수</th>
            <th>연간고용부담금</th>
            <th>거래품목</th>
            <th>거래가능품목</th>
            <th>-</th>

        </tr>

        <tr class="tr_value">
            <td class="consulting_status"><?=$company_info['consulting_status']?></td>
            <td class="employees"><?=$company_info['employees']?></td>
            <td class="employment_fee"><?=$company_info['employment_fee']?></td>
            <td class="trading_items"><?=$company_info['trading_items']?></td>
            <td class="tradeable_items"><?=$company_info['tradeable_items']?></td>
            <td class=""><button class="btn btn-primary btn_show_company_info">상세정보</button></td>

        </tr>

    </tbody>
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
            <td class="biz_num"><?=$company_info['biz_num']?></td>
            <th scope="row" class="mth">업태</td>
            <td class="biz_part"><?=$company_info['biz_part']?></td>
        </tr>

        <tr>
            <th scope="row" class="mth">법인번호</th>
            <td class="corp_num"><?=$company_info['corp_num']?></td>
            <th scope="row" class="mth">업종</td>
            <td class="biz_type"><?=$company_info['biz_type']?></td>
        </tr>

        <tr>
            <th scope="row" class="mth">대표자명</th>
            <td class="ceo_name"><?=$company_info['ceo_name']?></td>
            <th scope="row" class="mth">주소</td>
            <td class="address"><?=$company_info['address']?></td>
        </tr>

        <tr>
            <th scope="row" class="mth">전화번호</th>
            <td class="tel"><?=$company_info['tel']?></td>
            <th scope="row" class="mth">홈페이지</td>
            <td class="homepage"><?=$company_info['homepage']?></td>
        </tr>

        <tr>
            <th scope="row" class="mth">팩스번호</th>
            <td class="fax"><?=$company_info['fax']?></td>
            <th scope="row" class="mth">대금지급일</td>
            <td class="payment_date"><?=$company_info['payment_date']?></td>
        </tr>

        
        <tr>
            <th scope="row" class="mth">미팅여부</th>
            <td class="meeting"><?=$company_info['meeting']?></td>
            <th scope="row" class="mth">미팅담당자</td>
            <td class="meeting_person"><?=$company_info['meeting_person']?></td>
        </tr>



        <tr>
            <th scope="row" class="mth">메모</th>
            <td class="memo" colspan="3"><?=nl2br($company_info['memo'])?></td>

        </tr>


    </tbody>
</table>


<!-- 

<table class="table table-bordered settlement_info">

    <thead>
        <tr>
            <th colspan="4" class="company_info mtitle">
                정산정보
                         
            
            </th>
        </tr>
    </thead>

    <tbody>
        

        <tr>
            <th scope="row" class="mth">정산기간</th>
            <td class="settlement_period"></td>
            <th scope="row" class="mth">대금지급일</td>
            <td class="payment_date"></td>
        </tr>

        <tr>
            <th scope="row" class="mth">정산일</th>
            <td class="settlement_date"></td>
            <th scope="row" class="mth">비고</td>
            <td class="settlement_memo"></td>
        </tr>


    </tbody>
</table> -->



<table class="table table-bordered table_manager_info">

    <thead>
        <tr>
            <th colspan="8" class="manager_info mtitle">담당자정보
                <div class="navbar-right btn_show_manager" style="text-align:right; padding-right:0px; font-size:11pt; cursor:pointer;">
                    <i class="fa fa-chevron-down"></i>
                </div>
                <div class="navbar-right btn_hide_manager hide" style="text-align:right; padding-right:0px; font-size:11pt; cursor:pointer;">
                    <i class="fa fa-chevron-up"></i>
                </div>
            </th>
        </tr>
        <tr class="hide_tr">
            <th scope="row" class="mth manager_th_item_in_charge">담당업무</th>

            <th scope="row" class="mth manager_th_name">이름</th>
            <th scope="row" class="mth manager_th_department">부서</th>
            <th scope="row" class="mth manager_th_position">직위</th>
            <th scope="row" class="mth manager_th_email">이메일</th>
            <th scope="row" class="mth manager_th_tel">전화번호</th>
            <th scope="row" class="mth manager_th_hp">휴대폰</th>
            <th scope="row" class="mth manager_th_settlement_date">정산마감일</th>
        </tr>
    </thead>

    <tbody>

    <?php
       for ($i=0; $i < count($manager_list) ; $i++) { ?>

        <tr class="hide_tr">
            <td class="manager_item_in_charge"><?=$manager_list[$i]['manager_item_in_charge']?></td>
            <td class="manager_name"><?=$manager_list[$i]['manager_name']?></td>
            <td class="manager_position"><?=$manager_list[$i]['manager_position']?></td>
            <td class="manager_department"><?=$manager_list[$i]['manager_department']?></td>
            <td class="manager_email"><?=$manager_list[$i]['manager_email']?></td>
            <td class="manager_tel"><?=$manager_list[$i]['manager_tel']?></td>
            <td class="manager_hp"><?=$manager_list[$i]['manager_hp']?></td>
            <td class="manager_settlement_date"><?=$manager_list[$i]['manager_settlement_date']?></td>
        </tr>

        
       <?}

    ?>


    </tbody>
</table>



<div class="" role="tabpanel" data-example-id="togglable-tabs" id="memo_history">
    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">

        <li role="presentation" class="active"><a href="#tab_content2" role="tab" id="contract-tab" data-toggle="tab" aria-expanded="true">영업 및 계약</a>
        </li>
        <li role="presentation" class=""><a href="#tab_content1" id="product-tab" role="tab" data-toggle="tab" aria-expanded="false">품목</a>
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
                    <button class="btn btn-primary btn_add_product_history" >품목기록추가</button>
                </div>
                <div class="clearfix"></div>
                </div>
                <div class="x_content">
                <ul class="list-unstyled timeline ul_memo_product_history">

                <li id="memo_add_section" class="hide">
                    <div class="block">
                        <div class="tags">
                        <a href="javascript:" class="tag">
                            <span class="memo_status">상조용품</span>
                        </a>
                        </div>




                                        <div class="form-group">

                                        영업일: <input placeholder='영업일' type='date' class='mdate' name='date1'><br>

                                        <select id="product_type" name="product_type" placeholder="품목" required="required" >
                                            <option >상조용품</option>
                                            <option >장례서비스</option>
                                            <option >화훼</option>
                                            <option >종이컵</option>
                                            <option >복사용지</option>
                                            <option >인쇄</option>
                                            <option >유니폼</option>
                                            <option >안전화</option>
                                            <option >장갑</option>
                                            <option >휴지</option>
                                            <option >위생관리용역</option>
                                            <option >임가공</option>
                                            <option >선물세트</option>
                                            <option >커피</option>
                                            <option >특판</option>
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
                    <button class="btn btn-primary btn_add_history">영업기록추가</button>
                </div>
                <div class="clearfix"></div>
                </div>
                <div class="x_content">
                <ul class="list-unstyled timeline ul_memo_history">

                <li id="memo_add_section" class="hide">
                    <div class="block">
                        <div class="tags">
                        <a href="javascript:" class="tag_warning tag">
                            <span class="memo_status">TM</span>
                        </a>
                        </div>
                        <div class="block_content">




                        
                        영업일: <input placeholder='영업일' type='date' class='mdate' name='date2'><br>
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
















<div class="" role="tabpanel" data-example-id="togglable-tabs" id="attatchment_tabs">
    <ul id="attatchTab" class="nav nav-tabs bar_tabs" role="tablist">
        <li role="presentation" class="active attatch_tab"><a id="attatch-tab1" role="tab" data-toggle="tab" aria-expanded="true">전체</a>
        </li>
        <li role="presentation" class=" attatch_tab"><a  role="tab" id="attatch-tab2" data-toggle="tab" aria-expanded="false">사업자등록증</a>
        </li>
        <li role="presentation" class=" attatch_tab"><a  role="tab" id="attatch-tab3" data-toggle="tab" aria-expanded="false">계약서</a>
        </li>
        <li role="presentation" class=" attatch_tab"><a  role="tab" id="attatch-tab4" data-toggle="tab" aria-expanded="false">견적서</a>
        </li>
        <li role="presentation" class=" attatch_tab"><a  role="tab" id="attatch-tab5" data-toggle="tab" aria-expanded="false">기타</a>
        </li>
    </ul>





    <div class="x_panel x_panel_attatchment">
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

                    <?php

                        for ($i=0;$i<count($attachment_list);$i++ )
                        {
                            
                            $filename_ex = explode('/',$attachment_list[$i]['filename']);
                            $this_length = count($filename_ex) - 1;
                            $fex = explode(".",$filename_ex[$this_length]);
                            $fex_length = count($fex) - 1;
                            $ext = $fex[$fex_length];
                            
                            if($ext == "JPEG" || $ext == "JPG" || $ext == "PNG" || $ext == "PDF"  ||  $ext == "XLSX" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || $ext == "pdf" ||  $ext == "xlsx" ){
                            ?>

                                <tr>
                                    <th scope="row" class="th_num"><?=$i+1?></th>
                                    <td><a href='' link='<?=$attachment_list[$i]['filename']?>'><?=$filename_ex[$this_length]?></a></td>
                                    <td class='td_x'><i class="fa fa-close"></i></td>

                                </tr>
                            <?

                            }else{
                            ?>
                                <tr>
                                    <th scope="row" class="th_num"><?=$i+1?></th>
                                    <td><a  href='<?=$attachment_list[$i]['filename']?>' download><?=$filename_ex[$this_lenth]?></a></td>
                                    <td class='td_x'><i class="fa fa-close"></i></td>

                                </tr>

                            <?
                            }
                        ?>

                        <?}
                    ?>

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
            <th class="consulting_docu mtitle">견적서/계약서</th>
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
                    <span class='tag_title memo_status'>TM</span>
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


<div id="manager_sample" class="hide">
    <table>
        <tr class="hide_tr">
            <td class="manager_item_in_charge"></td>

            <td class="manager_name"></td>

            <td class="manager_position"></td>
            <td class="manager_department"></td>
            <td class="manager_email"></td>
            <td class="manager_tel"></td>
            <td class="manager_hp"></td>
            <td class="manager_settlement_date"></td>

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
  </script>